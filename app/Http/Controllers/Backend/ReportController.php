<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Store;
use App\Models\Product;
use App\Models\EmployeeSalary;
use App\Models\StockIn;
use App\Models\SellProduct;
use App\Models\Cart;
use App\Models\SupplierPayment;
use App\Models\Supplier;
use Carbon\Carbon;
use PDF;
use Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:show total report')->only('generateReport','generateStockReport','generateStockReportPdf');
    }
    public function generateReport(Request $request)
    {
        $cardHeader = 'Report for ';

        // Set default date range based on the selected filter
        $dateFilter = $request->input('date_filter');
        switch ($dateFilter) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                $cardHeader .= 'Today';
                break;
            case 'previous_day':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday();
                $cardHeader .= 'Previous Day';
                break;
            case 'last_7_days':
                $startDate = Carbon::now()->subDays(6);
                $endDate = Carbon::today();
                $cardHeader .= 'Last 7 Days';
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $cardHeader .= 'This Month';
                break;
            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                $cardHeader .= 'This Year';
                break;
            case 'all_time':
                $startDate = Carbon::minValue();
                $endDate = Carbon::now();
                $cardHeader .= 'All Time';
                break;
            case 'custom':
                $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
                $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now()->endOfMonth();
                $cardHeader .= 'Custom Range (' . $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y') . ')';
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $cardHeader .= 'This Month';
                break;
        }

        // Filter by store
        $store_id = $request->input('store_id');
        if ($store_id) {
            $store = Store::find($store_id);
            if ($store) {
                $cardHeader .= ' for ' . $store->name;
            }
        } else {
            $cardHeader .= ' for All Stores';
        }

        
        

        // invoice
        $totalInvoices = 0;
        $totalInvoiceSales = 0;
        $totalInvoiceReturnSell = 0;
        $totalInvoiceDue = 0;
        $totalInvoiceProfit = 0;

        // Fetch total invoices based on the date range and store
        $invoicesQuery = Invoice::with('sellProducts', 'returnSellProducts')  // Add the returnSellProducts relationship if it exists
            ->whereBetween('created_at', [$startDate, $endDate]);

        // If store_id is provided, filter by store
        if ($store_id) {
            $invoicesQuery->where('store_id', $store_id);
        }

        // Calculate total invoices
        $totalInvoices = $invoicesQuery->count();

        // Calculate total sales (sum of total_bill across all invoices)
        $totalInvoiceSales = $invoicesQuery->sum('total_bill');

        // Fetch total returned product amount from invoices (if `product_return` is a field in the invoices table, adjust if necessary)
        $totalInvoiceReturnSell = $invoicesQuery->sum('product_return');  // Assuming `product_return` is stored as a column, otherwise use a relationship

        // Fetch total due amount (only sum invoices where due_amount is greater than 0)
        $totalInvoiceDue = $invoicesQuery->where('due_amount', '>', 0)->sum('due_amount');

        $totalSoldPurchasePrice = $invoicesQuery->with('sellProducts')->get()->sum(function ($invoice) {
            return $invoice->sellProducts->sum(function ($sellProduct) {
                return $sellProduct->purchase_price * $sellProduct->qty;
            });
        });


        // Calculate total profit (Sales - Purchase Price) — Ensure you have the total purchase price of sold products
        $totalInvoiceProfit = ($totalInvoiceSales + $totalInvoiceReturnSell) - $totalSoldPurchasePrice;
        // end invoice

        // product
        $products = Product::with([
            'sellProducts' => function ($query) use ($startDate, $endDate, $store_id) {
                $query->where('store_id', $store_id)
                      ->whereBetween('created_at', [$startDate, $endDate]); // Filter by date range
            },
            'returnSellProducts' => function ($query) use ($startDate, $endDate, $store_id) {
                $query->where('store_id', $store_id)
                      ->whereBetween('created_at', [$startDate, $endDate]); // Filter by date range
            },
            'stockIns' => function ($query) use ($startDate, $endDate, $store_id) {
                $query->where('store_id', $store_id)
                      ->whereBetween('created_at', [$startDate, $endDate]) // Filter by date range
                      ->orderBy('id', 'desc');
            }
        ])
        ->get();
        // Initialize variables for totals and values
        $totalStock = 0;
        $totalSold = 0;
        $totalReturned = 0;
        $totalStockValue = 0;
        $totalSellValue = 0;
        $totalReturnValue = 0;
        $totalAvailableQty = 0;
        $totalAvailableStockValue = 0;
        $totalSoldPurchasePrice = 0; // Initialize total purchase price for sold products
        $totalAvailableStockPurchaseCost = 0; // Initialize total purchase cost of available stock

        foreach ($products as $product) {
            // Calculate total stock and stock value (purchase price * qty)
            $stockQty = $product->stockIns->sum('qty');
            $totalStock += $stockQty;
            $totalStockValue += $product->stockIns->sum(function ($stockIn) {
                return $stockIn->purchase_price * $stockIn->qty; // stock value (purchase price * qty)
            });

            // Calculate total sold and sell value (sell price * qty)
            $soldQty = $product->sellProducts->sum('qty');
            $totalSold += $soldQty;
            $totalSellValue += $product->sellProducts->sum(function ($sellProduct) {
                return $sellProduct->sell_price * $sellProduct->qty; // sell value (sell price * qty)
            });

            // Calculate purchase price for sold products
            $totalSoldPurchasePrice += $product->sellProducts->sum(function ($sellProduct) {
                return $sellProduct->purchase_price * $sellProduct->qty; // purchase price * sold qty
            });

            // Calculate total returned and return value (purchase price * qty)
            $returnedQty = $product->returnSellProducts->sum('qty');
            $totalReturned += $returnedQty;
            $totalReturnValue += $product->returnSellProducts->sum(function ($returnProduct) {
                return $returnProduct->purchase_price * $returnProduct->qty; // return value (purchase price * qty)
            });

            // Calculate available quantity
            $availableQty = $stockQty - $soldQty + $returnedQty;
            $totalAvailableQty += $availableQty; // Sum up available quantities

            // Calculate available stock value
            $availableValue = $totalStockValue - $totalSellValue + $totalReturnValue;
            $totalAvailableStockValue += $availableValue; // Sum up available value

            // Calculate total available stock purchase cost
            $totalAvailableStockPurchaseCost += $product->stockIns->sum(function ($stockIn) {
                return $stockIn->purchase_price * $stockIn->qty; // purchase price of available stock
            });
        }

        // Calculate Total Available Stock Profit
        $totalAvailableStockProfit = $totalAvailableStockValue - $totalAvailableStockPurchaseCost;
        // end product



        // supplier
        
        // Query suppliers with their relationships
        $query = Supplier::with(['stockIns', 'supplierPayments']);

        // Initialize cumulative totals
        $totalSupplierPurchase = 0;
        $totalSupplierPayment = 0;
        $totalSupplierDue = 0;
        $totalSupplierAdvance = 0;

        // Fetch suppliers and calculate totals
        $suppliers = $query->get()->map(function ($supplier) use ($startDate, $endDate, $store_id, &$totalSupplierPurchase, &$totalSupplierPayment, &$totalSupplierDue, &$totalSupplierAdvance) {
            $filteredStockIns = $supplier->stockIns->filter(function ($stockIn) use ($startDate, $endDate, $store_id) {
                $dateMatch = (empty($startDate) || $stockIn->created_at >= $startDate) &&
                             (empty($endDate) || $stockIn->created_at <= $endDate);
                $storeMatch = (empty($store_id) || $stockIn->store_id == $store_id);

                return $dateMatch && $storeMatch;
            });

            $totalPurchase = $filteredStockIns->sum(function ($stockIn) {
                return $stockIn->purchase_price * $stockIn->qty;
            });

            $filteredPayments = $supplier->supplierPayments->filter(function ($payment) use ($startDate, $endDate, $store_id) {
                $dateMatch = (empty($startDate) || $payment->paid_date >= $startDate) &&
                             (empty($endDate) || $payment->paid_date <= $endDate);
                $storeMatch = (empty($store_id) || $payment->store_id == $store_id);

                return $dateMatch && $storeMatch;
            });

            $totalPayment = $filteredPayments->sum('amount');

            // Calculate due amount or advance payment
            $due = max(0, $totalPurchase - $totalPayment);
            $advance = max(0, $totalPayment - $totalPurchase);

            // Accumulate totals
            $totalSupplierPurchase += $totalPurchase;
            $totalSupplierPayment += $totalPayment;
            $totalSupplierDue += $due;
            $totalSupplierAdvance += $advance;

            // Return individual supplier details
            return [
                'name' => $supplier->name,
                'totalPurchase' => $totalPurchase,
                'totalPayment' => $totalPayment,
                'due' => $due,
                'advance' => $advance,
            ];
        });
        // end supplier


        // expence
        $totalExpense = 0;
        // Fetch total expense
        $expenseQuery = Expense::whereBetween('expense_date', [$startDate, $endDate]);
        if ($store_id) {
            $expenseQuery->where('store_id', $store_id);
        }
        $totalExpense = $expenseQuery->sum('amount');
        // end expence

        // employee salary
        $totalSalary = 0;
        // Fetch total employee salary
        $salaryQuery = EmployeeSalary::whereBetween('paid_date', [$startDate, $endDate]);
        if ($store_id) {
            $salaryQuery->where('store_id', $store_id);
        }
        $totalSalary = $salaryQuery->sum('amount');
        // end employee salary

        $stores = Store::get();
        // Pass calculated totals and values to the view
        return view('reports.index', compact(
            'cardHeader',
            'stores', 
            // stock
            'totalStock', 
            'totalSold', 
            'totalReturned', 
            'totalStockValue', 
            'totalSellValue', 
            'totalReturnValue', 
            'totalAvailableQty',
            'totalAvailableStockValue',
            'totalSoldPurchasePrice',
            'totalAvailableStockProfit', // Include total available stock profit

            // invoice 
            'totalInvoices',
            'totalInvoiceSales',
            'totalInvoiceReturnSell',
            'totalInvoiceDue',
            'totalInvoiceProfit',

            // supplier
            'suppliers',
            'totalSupplierPurchase',
            'totalSupplierPayment',
            'totalSupplierDue',
            'totalSupplierAdvance',

            // 
            'totalExpense',
            'totalSalary',



        ));

    }

    public function generateStockReport(Request $request)
    {
        $cardHeader = 'Stock Report';

        // Filter by store if selected
        $store_id = $request->input('store_id');
        $store = Store::find($store_id);
        if ($store) {
            $cardHeader .= ' for ' . $store->name;
        } else {
            $cardHeader .= ' for All Stores';
        }

        // Initialize variables for stock metrics
        $total_stock_in_qty = 0;
        $total_available_stock_qty = 0;
        $total_available_stock_sell_price = 0;
        $total_stock_in_value = 0;
        $total_sold_qty = 0;
        $total_available_stock_profit = 0;
        $total_available_stock_purchase_price = 0; // New metric
        $low_stock_alerts = [];
        $expiring_soon_alerts = [];

        // Get all products based on the store filter
        if ($store) {
            $products = Product::whereHas('sellProducts', function ($query) use ($store_id) {
                    $query->where('store_id', $store_id);
                })
                ->orWhereHas('stockIns', function ($query) use ($store_id) {
                    $query->where('store_id', $store_id);
                })
                ->with([
                    'sellProducts' => function ($query) use ($store_id) {
                        $query->where('store_id', $store_id);
                    },
                    'returnSellProducts' => function ($query) use ($store_id) {
                        $query->where('store_id', $store_id);
                    },
                    'stockIns' => function ($query) use ($store_id) {
                        $query->where('store_id', $store_id);
                    }
                ])
                ->get();
        } else {
            // If no storeId, get all products with all sellProducts and stockIns
            $products = Product::with(['sellProducts', 'returnSellProducts', 'stockIns'])->get();
        }

        // Calculate stock metrics for each product
        foreach ($products as $product) {
            // Total stock from StockIn
            $total_stock = $product->stockIns->sum('qty');

            // Total sold from SellProduct
            $total_sold_qty_per_product = $product->sellProducts->sum('qty');

            // Total return from ReturnSellProduct
            $total_return_qty_per_product = $product->returnSellProducts->sum('qty');

            // Add to total stock-in quantity (include returns)
            $total_stock_in_qty += $total_stock + $total_return_qty_per_product;

            // Add to total sold quantity
            $total_sold_qty += $total_sold_qty_per_product;

            // Calculate available stock
            $available_stock = ($total_stock + $total_return_qty_per_product) - $total_sold_qty_per_product;
            $total_available_stock_qty += $available_stock;

            // Calculate total stock-in value (purchase price * qty)
            $total_stock_in_value += $product->stockIns->sum(function ($stockIn) {
                return $stockIn->purchase_price * $stockIn->qty;
            });

            // Calculate available stock value and metrics
            foreach ($product->stockIns as $stockIn) {
                $stock_in_qty = $stockIn->qty;

                // Deduct sold quantity from stock-in batch
                $sold_qty_in_batch = min($stock_in_qty, $total_sold_qty_per_product);
                $available_stock_in_batch = $stock_in_qty - $sold_qty_in_batch;

                // Include returns in available stock
                $return_qty_in_batch = min($available_stock_in_batch, $total_return_qty_per_product);
                $adjusted_available_stock = $available_stock_in_batch + $return_qty_in_batch;

                // Add to total available stock value
                $total_available_stock_sell_price += $adjusted_available_stock * $stockIn->sell_price;

                // Add to total available stock purchase price
                $total_available_stock_purchase_price += $adjusted_available_stock * $stockIn->purchase_price;

                // Calculate available stock profit for the adjusted stock
                if ($adjusted_available_stock > 0) {
                    $profit = ($stockIn->sell_price - $stockIn->purchase_price) * $adjusted_available_stock;
                    $total_available_stock_profit += $profit;
                }

                // Reduce the remaining return quantity after adjusting the batch
                $total_return_qty_per_product -= $return_qty_in_batch;
            }

            // Low stock alert
            if ($available_stock < 5) {
                $low_stock_alerts[] = [
                    'product_name' => $product->name,
                    'available_stock' => $available_stock,
                ];
            }

            // Expiring soon alerts
            $expiringSoonStockIns = $product->stockIns->where('expiration_date', '<=', Carbon::now()->addDays(30));

            foreach ($expiringSoonStockIns as $stockIn) {
                $expiring_soon_alerts[] = [
                    'product_name' => $product->name,
                    'product_id' => $product->id,
                    'expiration_date' => Carbon::parse($stockIn->expiration_date)->format('Y-m-d'), // Parse the date before formatting
                    'qty' => $stockIn->qty,
                ];
            }
        }

        return view('reports.stock', [
            'cardHeader' => $cardHeader,
            'total_stock_in_qty' => $total_stock_in_qty,
            'total_available_stock_qty' => $total_available_stock_qty,
            'total_available_stock_sell_price' => $total_available_stock_sell_price,
            'total_stock_in_value' => $total_stock_in_value,
            'total_sold_qty' => $total_sold_qty,
            'total_available_stock_profit' => $total_available_stock_profit,
            'total_available_stock_purchase_price' => $total_available_stock_purchase_price, // Pass to the view
            'low_stock_alerts' => $low_stock_alerts,
            'expiring_soon_alerts' => $expiring_soon_alerts,
        ]);
    }

    public function generateStockReportPdf(Request $request)
    {
        $cardHeader = 'Stock Report';

        // Filter by store if selected
        $store_id = $request->input('store_id');
        $store = Store::find($store_id);
        if ($store) {
            $cardHeader .= ' for ' . $store->name;
        } else {
            $cardHeader .= ' for All Stores';
        }

        // Initialize variables for stock metrics
        $total_stock_in_qty = 0;
        $total_available_stock_qty = 0;
        $total_available_stock_sell_price = 0;
        $total_stock_in_value = 0;
        $total_sold_qty = 0;
        $total_available_stock_profit = 0;
        $total_available_stock_purchase_price = 0; // New metric
        $low_stock_alerts = [];
        $expiring_soon_alerts = [];

        // Get all products based on the store filter
        if ($store) {
            $products = Product::whereHas('sellProducts', function ($query) use ($store_id) {
                    $query->where('store_id', $store_id);
                })
                ->orWhereHas('stockIns', function ($query) use ($store_id) {
                    $query->where('store_id', $store_id);
                })
                ->with([
                    'sellProducts' => function ($query) use ($store_id) {
                        $query->where('store_id', $store_id);
                    },
                    'returnSellProducts' => function ($query) use ($store_id) {
                        $query->where('store_id', $store_id);
                    },
                    'stockIns' => function ($query) use ($store_id) {
                        $query->where('store_id', $store_id);
                    }
                ])
                ->get();
        } else {
            // If no storeId, get all products with all sellProducts and stockIns
            $products = Product::with(['sellProducts', 'returnSellProducts', 'stockIns'])->get();
        }

        // Calculate stock metrics for each product
        foreach ($products as $product) {
            // Total stock from StockIn
            $total_stock = $product->stockIns->sum('qty');

            // Total sold from SellProduct
            $total_sold_qty_per_product = $product->sellProducts->sum('qty');

            // Total return from ReturnSellProduct
            $total_return_qty_per_product = $product->returnSellProducts->sum('qty');

            // Add to total stock-in quantity (include returns)
            $total_stock_in_qty += $total_stock + $total_return_qty_per_product;

            // Add to total sold quantity
            $total_sold_qty += $total_sold_qty_per_product;

            // Calculate available stock
            $available_stock = ($total_stock + $total_return_qty_per_product) - $total_sold_qty_per_product;
            $total_available_stock_qty += $available_stock;

            // Calculate total stock-in value (purchase price * qty)
            $total_stock_in_value += $product->stockIns->sum(function ($stockIn) {
                return $stockIn->purchase_price * $stockIn->qty;
            });

            // Calculate available stock value and metrics
            foreach ($product->stockIns as $stockIn) {
                $stock_in_qty = $stockIn->qty;

                // Deduct sold quantity from stock-in batch
                $sold_qty_in_batch = min($stock_in_qty, $total_sold_qty_per_product);
                $available_stock_in_batch = $stock_in_qty - $sold_qty_in_batch;

                // Include returns in available stock
                $return_qty_in_batch = min($available_stock_in_batch, $total_return_qty_per_product);
                $adjusted_available_stock = $available_stock_in_batch + $return_qty_in_batch;

                // Add to total available stock value
                $total_available_stock_sell_price += $adjusted_available_stock * $stockIn->sell_price;

                // Add to total available stock purchase price
                $total_available_stock_purchase_price += $adjusted_available_stock * $stockIn->purchase_price;

                // Calculate available stock profit for the adjusted stock
                if ($adjusted_available_stock > 0) {
                    $profit = ($stockIn->sell_price - $stockIn->purchase_price) * $adjusted_available_stock;
                    $total_available_stock_profit += $profit;
                }

                // Reduce the remaining return quantity after adjusting the batch
                $total_return_qty_per_product -= $return_qty_in_batch;
            }

            // Low stock alert
            if ($available_stock < 5) {
                $low_stock_alerts[] = [
                    'product_name' => $product->name,
                    'available_stock' => $available_stock,
                ];
            }

            // Expiring soon alerts
            $expiringSoonStockIns = $product->stockIns->where('expiration_date', '<=', Carbon::now()->addDays(30));

            foreach ($expiringSoonStockIns as $stockIn) {
                $expiring_soon_alerts[] = [
                    'product_name' => $product->name,
                    'product_id' => $product->id,
                    'expiration_date' => Carbon::parse($stockIn->expiration_date)->format('Y-m-d'), // Parse the date before formatting
                    'qty' => $stockIn->qty,
                ];
            }
        }

        $data = [
            'cardHeader' => $cardHeader,
            'total_stock_in_qty' => $total_stock_in_qty,
            'total_available_stock_qty' => $total_available_stock_qty,
            'total_available_stock_sell_price' => $total_available_stock_sell_price,
            'total_stock_in_value' => $total_stock_in_value,
            'total_sold_qty' => $total_sold_qty,
            'total_available_stock_profit' => $total_available_stock_profit,
            'total_available_stock_purchase_price' => $total_available_stock_purchase_price,
            'low_stock_alerts' => $low_stock_alerts,
            'expiring_soon_alerts' => $expiring_soon_alerts,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('reports.pdf.stock-pdf', $data);

        // Return the PDF as a downloadable file
        return $pdf->download('stock-report.pdf');
    }

}
