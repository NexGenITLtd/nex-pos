<?php
namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Requests\SupplierPaymentRequest;
use App\Models\SupplierPayment;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\BankAccount;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Auth;

class SupplierPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view supplier-payment')->only('index','show');
        $this->middleware('permission:create supplier-payment')->only('create', 'store');
        $this->middleware('permission:update supplier-payment')->only('edit', 'update');
        $this->middleware('permission:delete supplier-payment')->only('destroy');
    }

    public function index(Request $request)
    {
        $dateFilter = $request->input('date_filter', 'this_month');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $storeId = $request->input('store_id');

        $supplierPaymentsQuery = SupplierPayment::query();

        if ($storeId) {
            $supplierPaymentsQuery->where('store_id', $storeId);
        }

        switch ($dateFilter) {
            case 'today':
                $supplierPaymentsQuery->whereDate('paid_date', today());
                break;
            case 'previous_day':
                $supplierPaymentsQuery->whereDate('paid_date', today()->subDay());
                break;
            case 'last_7_days':
                $supplierPaymentsQuery->whereDate('paid_date', '>=', today()->subDays(7));
                break;
            case 'this_month':
                $supplierPaymentsQuery->whereMonth('paid_date', now()->month);
                break;
            case 'this_year':
                $supplierPaymentsQuery->whereYear('paid_date', now()->year);
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $supplierPaymentsQuery->whereBetween('paid_date', [$startDate, $endDate]);
                }
                break;
        }

        $supplierPayments = $supplierPaymentsQuery->get();
        $stores = Store::all();
        $storeName = $storeId ? Store::find($storeId)->name : 'All Store';
        $cardHeader = 'Supplier Payments - ' . $storeName . ' - ' . ucfirst(str_replace('_', ' ', $dateFilter));

        return view('suppliers.payments.index', compact('supplierPayments', 'dateFilter', 'stores', 'cardHeader'));
    }

    public function create()
    {
        $bank_accounts = BankAccount::with('store')->get();
        $suppliers = Supplier::all();
        $stores = Store::all();
        return view("suppliers.payments.create", compact('bank_accounts', 'suppliers', 'stores'));
    }

    public function store(SupplierPaymentRequest $request)
    {
        // Begin a transaction for data integrity
        DB::beginTransaction();

        try {
            // Create the supplier payment
            $supplierPayment = SupplierPayment::create($request->validated());

            // Use the createTransaction function to handle balance updates and record the transaction
            Transaction::createTransaction(
                $supplierPayment->store_id,
                $supplierPayment->bank_account_id,
                Auth::user()->id,
                $supplierPayment->amount, // Debit the bank account
                0, // No credit
                "Supplier Payment: Supplier ID {$supplierPayment->supplier_id}, Amount: {$supplierPayment->amount}"
            );

            // Commit the transaction
            DB::commit();

            return redirect()->route('supplier-payments.index')->with('flash_success', '
                <script>
                    Toast.fire({
                        icon: `success`,
                        title: `Supplier payment successfully added`
                    })
                </script>
            ');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            return redirect()->route('supplier-payments.create')->with('flash_error', '
                <script>
                    Toast.fire({
                        icon: `error`,
                        title: `Failed to add supplier payment`
                    })
                </script>
            ');
        }
    }


    public function edit($id)
    {
        $supplier_payment = SupplierPayment::findOrFail($id);
        $bank_accounts = BankAccount::with('store')->get();
        $suppliers = Supplier::all();
        $stores = Store::all();
        return view("suppliers.payments.edit", compact('supplier_payment', 'bank_accounts', 'suppliers', 'stores'));
    }

    public function update(SupplierPaymentRequest $request, $id)
    {
        // Begin a transaction for data integrity
        DB::beginTransaction();

        try {
            // Retrieve the supplier payment
            $supplierPayment = SupplierPayment::findOrFail($id);

            // Reverse the previous transaction
            Transaction::createTransaction(
                $supplierPayment->store_id,
                $supplierPayment->bank_account_id,
                Auth::user()->id,
                0,
                $supplierPayment->amount, // Credit the previous payment amount
                "Reversed Supplier Payment: Supplier ID {$supplierPayment->supplier_id}, Amount: {$supplierPayment->amount}"
            );

            // Update the supplier payment record
            $supplierPayment->update($request->validated());

            // Create a new transaction for the updated payment
            Transaction::createTransaction(
                $request->store_id,
                $request->bank_account_id,
                Auth::user()->id,
                $request->amount, // Debit the new payment amount
                0,
                "Updated Supplier Payment: Supplier ID {$request->supplier_id}, Amount: {$request->amount}"
            );

            // Commit the transaction
            DB::commit();

            return redirect()->route('supplier-payments.index')->with('flash_success', '
                <script>
                    Toast.fire({
                        icon: `success`,
                        title: `Supplier payment successfully updated`
                    })
                </script>
            ');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            return redirect()->route('supplier-payments.edit', $id)->with('flash_error', '
                <script>
                    Toast.fire({
                        icon: `error`,
                        title: `Failed to update supplier payment`
                    })
                </script>
            ');
        }
    }



    public function destroy($id)
    {
        // Begin a transaction for data integrity
        DB::beginTransaction();

        try {
            // Retrieve the supplier payment
            $supplierPayment = SupplierPayment::findOrFail($id);

            // Use the createTransaction function to handle balance updates and record the transaction
            Transaction::createTransaction(
                $supplierPayment->store_id,
                $supplierPayment->bank_account_id,
                Auth::user()->id,
                0, // No debit, as the amount is refunded
                $supplierPayment->amount, // Credit the bank account
                "Deleted Supplier Payment: Supplier ID {$supplierPayment->supplier_id}, Refunded Amount: {$supplierPayment->amount}"
            );

            // Delete the supplier payment record
            $supplierPayment->delete();

            // Commit the transaction
            DB::commit();

            return redirect()->route('supplier-payments.index')->with('flash_success', '
                <script>
                    Toast.fire({
                        icon: `success`,
                        title: `Supplier payment successfully deleted`
                    })
                </script>
            ');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            return redirect()->route('supplier-payments.index')->with('flash_error', '
                <script>
                    Toast.fire({
                        icon: `error`,
                        title: `Failed to delete supplier payment`
                    })
                </script>
            ');
        }
    }


}
