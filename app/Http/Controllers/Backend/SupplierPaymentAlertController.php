<?php

namespace App\Http\Controllers\Backend;

use App\Models\SupplierPaymentAlert;
use App\Models\Store;
use App\Models\Supplier;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierPaymentAlertRequest;
use App\Models\User;
use App\Notifications\SupplierPaymentAlertNotification;
use Carbon\Carbon;

class SupplierPaymentAlertController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view supplier payment alert')->only('index','show');
        $this->middleware('permission:create supplier payment alert')->only('create', 'store');
        $this->middleware('permission:update supplier payment alert')->only('edit', 'update');
        $this->middleware('permission:delete supplier payment alert')->only('destroy');
    }

    public function create()
    {
        return view("suppliers.payment_alerts.create", [
            'suppliers' => Supplier::all(),
            'stores' => Store::all()
        ]);
    }

    public function store(SupplierPaymentAlertRequest $request)
    {
        return $this->saveAlert(new SupplierPaymentAlert(), $request);
    }

    public function update(SupplierPaymentAlertRequest $request, $id)
    {
        $alert = SupplierPaymentAlert::findOrFail($id);
        return $this->saveAlert($alert, $request);
    }

    private function saveAlert(SupplierPaymentAlert $alert, SupplierPaymentAlertRequest $request)
    {
        $alert->fill($request->validated());
        $alert->save();
        $this->sendNotificationIfDue($alert);

        return redirect()->route('supplier-payment-alerts.index')->with('flash_success', '
            <script>
                Toast.fire({
                    icon: `success`,
                    title: `Supplier payment alert successfully saved`
                })
            </script>
        ');
    }

    public function index()
    {
        return view("suppliers.payment_alerts.index", [
            'supplier_payment_alerts' => SupplierPaymentAlert::with('supplier')->orderBy('id')->paginate(1000),
            'unreadNotifications' => auth()->user()->unreadNotifications
        ]);
    }

    public function edit($id)
    {
        return view("suppliers.payment_alerts.edit", [
            'supplier_payment_alert' => SupplierPaymentAlert::findOrFail($id),
            'suppliers' => Supplier::all(),
            'stores' => Store::all()
        ]);
    }

    public function destroy($id)
    {
        SupplierPaymentAlert::findOrFail($id)->delete();
        return redirect()->route('supplier-payment-alerts.index')->with('flash_success', '
            <script>
                Toast.fire({
                    icon: `success`,
                    title: `Supplier payment alert successfully deleted`
                })
            </script>
        ');
    }

    private function sendNotificationIfDue(SupplierPaymentAlert $alert)
    {
        if (Carbon::parse($alert->notice_date)->isToday()) {
            $adminUser = User::find(1); // Admin user
            $adminUser->notify(new SupplierPaymentAlertNotification([
                'supplier_name' => $alert->supplier->name,
                'amount' => $alert->amount,
                'pay_date' => $alert->pay_date,
                'notice_date' => $alert->notice_date,
            ]));
        }
    }
}
