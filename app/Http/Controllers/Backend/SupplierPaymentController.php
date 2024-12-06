<?php
namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Requests\SupplierPaymentRequest;
use App\Models\SupplierPayment;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\BankAccount;
use App\Http\Controllers\Controller;

class SupplierPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view supplier payment')->only('index','show');
        $this->middleware('permission:create supplier payment')->only('create', 'store');
        $this->middleware('permission:update supplier payment')->only('edit', 'update');
        $this->middleware('permission:delete supplier payment')->only('destroy');
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
        $supplier_payment = SupplierPayment::create($request->validated());

        return redirect()->route('supplier-payments.index')->with('flash_success', '
            <script>
                Toast.fire({
                    icon: `success`,
                    title: `Supplier payment successfully added`
                })
            </script>
        ');
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
        $supplier_payment = SupplierPayment::findOrFail($id);
        $supplier_payment->update($request->validated());

        return redirect()->route('supplier-payments.index')->with('flash_success', '
            <script>
                Toast.fire({
                    icon: `success`,
                    title: `Supplier payment successfully updated`
                })
            </script>
        ');
    }

    public function destroy($id)
    {
        SupplierPayment::findOrFail($id)->delete();
        return redirect()->route('supplier-payments.index')->with('flash_success', '
            <script>
                Toast.fire({
                    icon: `success`,
                    title: `Supplier payment successfully deleted`
                })
            </script>
        ');
    }
}
