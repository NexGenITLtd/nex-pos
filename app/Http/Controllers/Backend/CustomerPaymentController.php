<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerPayment;
use App\Models\Store;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Auth;

class CustomerPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view customer-payment')->only('index','show');
        $this->middleware('permission:create customer-payment')->only('create', 'store');
        $this->middleware('permission:update customer-payment')->only('edit', 'update');
        $this->middleware('permission:delete customer-payment')->only('destroy');
    } 
    public function index(Request $request)
    {
        // Retrieve the filter inputs
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Build the query to filter customer payments
        $query = CustomerPayment::query();


        // Filter by start and end date if provided
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Get the filtered results
        $customerPayments = $query->get();
        $stores = Store::all(); // Fetch all stores

        // Pass the filtered results and the filter values to the view
        return view('customer_payments.index', compact('customerPayments', 'startDate', 'endDate'));
    }

    public function create()
    {
        return view('customer_payments.create');
    }
public function show($id)
    {
        //
    }
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            DB::transaction(function() use ($request, &$invoice) {

                // Validate request data (optional but recommended)
                $request->validate([
                    'invoice_id' => 'required|exists:invoices,id',
                    'cash_payment' => 'nullable|numeric|min:0',
                    'card_payment' => 'nullable|numeric|min:0',
                    'mobile_payment' => 'nullable|numeric|min:0',
                ]);

                // Fetch the invoice
                $invoice = Invoice::findOrFail($request->invoice_id);
                
                // Calculate due amount based on total payable and new payments
                $due = $invoice->due_amount - $request->total_payments;

                // Update invoice payment and due amounts
                $invoice->paid_amount += $request->total_payments;
                $invoice->due_amount = max($due, 0); // Ensure due amount is not negative
                $invoice->save();

                // Process Payments
                $this->processPayment(Auth::user()->store_id, $invoice->id, 'cash_payment', $request->cash_payment, $request->cash_account_no_id);
                $this->processPayment(Auth::user()->store_id, $invoice->id, 'card_payment', $request->card_payment, $request->card_account_no_id, $request->card_number, $request->card_type);
                $this->processPayment(Auth::user()->store_id, $invoice->id, 'mobile_payment', $request->mobile_payment, $request->mobile_account_no_id, $request->sender_no, $request->trx_no);
            });

            // Return back with success message
            return redirect()->back()->with('success', 'Payment processed successfully.');
        }

        // Return back if the method is not POST
        return redirect()->back()->withErrors('Invalid request method.');
    }

    

    public function edit($id)
    {
        $customer_payment = CustomerPayment::findOrFail($id); // Assuming invoice data is needed

        // Return the edit view with the customer payment and related data
        return view('customer_payments.edit', compact('customer_payment'));
    }

    public function update(Request $request, $id)
    {
        // Retrieve the invoice
        $invoice = Invoice::findOrFail($request->invoice_id);
        DB::transaction(function() use ($request, $id, &$invoice) {

            // Validate request data (optional but recommended)
            $request->validate([
                'invoice_id' => 'required|exists:invoices,id',
                'cash_payment' => 'nullable|numeric|min:0',
                'card_payment' => 'nullable|numeric|min:0',
                'mobile_payment' => 'nullable|numeric|min:0',
                'total_payments' => 'required|numeric|min:0',  // Ensuring total_payments is sent and valid
            ]);

            // Retrieve the customer payment for the invoice
            $customer_payment = CustomerPayment::where('id', $id)->firstOrFail();

            // Store the previous paid amount
            $previousPaidAmount = $customer_payment->amount;

            // Step 1: Subtract the previous paid amount and adjust the due
            $invoice->paid_amount -= $previousPaidAmount;
            $invoice->due_amount += $previousPaidAmount;

            // Step 2: Apply new payments to paid amount and due amount
            $invoice->paid_amount += $request->total_payments;
            $invoice->due_amount = max($invoice->due_amount - $request->total_payments, 0); // Ensure due amount does not go negative

            // Save the invoice with updated payment and due amount
            $invoice->save();
            // return $request->cash_account_no_id;
            // Update Payments
            if ($request->filled('cash_account_no_id')) {
                $this->updatePaymentById($id, $request->cash_payment, 'cash_payment', $request->cash_account_no_id);
            }
            if ($request->filled('card_account_no_id')) {
                $this->updatePaymentById($id, $request->card_payment, 'card_payment',$request->card_account_no_id, $request->card_number, $request->card_type);
            }
            if ($request->filled('mobile_account_no_id')) {
                $this->updatePaymentById($id, $request->mobile_payment,'mobile_payment', $request->mobile_account_no_id, $request->sender_no, $request->trx_no);
            }
        });

        // Return back with success message
        return 1;
    }

    public function destroy($id)
    {
        // Start a database transaction to ensure atomicity
        DB::transaction(function() use ($id) {

            // Find the customer payment or throw a 404 error
            $customerPayment = CustomerPayment::findOrFail($id);

            // Retrieve the associated invoice
            $invoice = Invoice::findOrFail($customerPayment->invoice_id);
            // Capture relevant details for the transaction
            $store_id = $invoice->store_id;
            $paid_amount = $customerPayment->amount;
            $user_id = auth()->id();

            // Step 1: Adjust the invoice's paid amount and due amount
            $invoice->paid_amount -= $customerPayment->amount;
            $invoice->due_amount = max($invoice->due_amount + $customerPayment->amount, 0); // Recalculate due amount

            // Save the updated invoice
            $invoice->save();

            // Step 2: Delete the customer payment
            $customerPayment->delete();


            // Record the transaction for the deletion
            if ($paid_amount > 0) {
                Transaction::createTransaction(
                    $store_id,
                    $invoice->bank_account_id, // Assuming the invoice has a reference to the bank account
                    $paid_amount, // Debit the amount paid from the bank account
                    0, // No credit
                    $user_id,
                    "Invoice Deleted: Invoice ID #$id"
                );
            }
        });

        // Redirect back to the customer payments index with a success message
        return redirect()->route('customer-payments.index')->with('success', 'Customer Payment deleted successfully!');
    }

    private function processPayment($store_id, $invoice_id, $payment_type, $amount, $account_id, $account_no = null, $trx_note = null)
    {
        if ($amount > 0) {
            $customer_payment = new CustomerPayment;
            $customer_payment->store_id = $store_id;
            $customer_payment->invoice_id = $invoice_id;
            $customer_payment->payment_type = $payment_type;
            $customer_payment->amount = $amount;
            $customer_payment->bank_account_id = $account_id;
            $customer_payment->payment_from_account_no = $account_no;
            $customer_payment->payment_trx_note = $trx_note;
            $customer_payment->save();


            // Record the transaction
			Transaction::createTransaction(
				$store_id,
				$account_id,
				0, // No debit for this transaction
				$amount, // Credit the bank account
				auth()->id(),
				"Invoice payment for Invoice #$invoice_id"
			);
        }
    }


    protected function updatePaymentById($paymentId, $amount, $cardType = null, $accountId, $cardNumber = null, $senderNo = null, $trxNo = null)
{
    // Find the existing customer payment by ID
    $customerPayment = CustomerPayment::findOrFail($paymentId);

    // Store the previous amount of the payment
    $previousAmount = $customerPayment->amount ?? 0;
    $difference = $amount - $previousAmount;

    // If the amount has changed, create a transaction
    if ($difference != 0) {
        Transaction::createTransaction(
            $customerPayment->store_id, // Store ID from the customer payment
            $accountId, // Bank account ID for the transaction
            $difference < 0 ? abs($difference) : 0, // Debit for reductions
            $difference > 0 ? $difference : 0, // Credit for additions
            auth()->id(), // User ID who made the update
            "Updated Payment: Invoice ID #$customerPayment->invoice_id" // Transaction description
        );
    }

    // Update the payment details with the new values
    $customerPayment->update([
        'amount' => $amount,
        'bank_account_id' => $accountId, // Updated account ID
        'card_type' => $cardType, // Card type (nullable)
        'payment_from_account_no' => $senderNo, // Sender account number (nullable)
        'payment_trx_note' => $trxNo, // Transaction note (nullable)
    ]);
}


}
