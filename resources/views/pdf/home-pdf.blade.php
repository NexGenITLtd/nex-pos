<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1, h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        td {
            text-align: right;
        }
        .details-table th, .details-table td {
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>{{ $cardHeader }}</h1>
    <h2>Report Summary</h2>
    <table>
        <tr>
            <th>Total Invoices</th>
            <td>{{ $total_invoices }}</td>
        </tr>
        <tr>
            <th>Total Sales</th>
            <td>{{ number_format($total_sales, 2) }}</td>
        </tr>
        <tr>
            <th>Total Sell Return</th>
            <td>{{ number_format($total_return_sell, 2) }}</td>
        </tr>
        
        <tr>
            <th>Total Purchase Price</th>
            <td>{{ number_format($total_purchase_price, 2) }}</td>
        </tr>
        @can('show profit')
        <tr>
            <th>Total Profit</th>
            <td>{{ number_format($total_profit, 2) }}</td>
        </tr>
        @endcan
        <tr>
            <th>Total Due</th>
            <td>{{ number_format($total_due, 2) }}</td>
        </tr>
        <tr>
            <th>Total Supplier Payments</th>
            <td>{{ number_format($total_supplier_payment, 2) }}</td>
        </tr>
        <tr>
            <th>Total Expense</th>
            <td>{{ number_format($total_expense, 2) }}</td>
        </tr>
        <tr>
            <th>Total Salary</th>
            <td>{{ number_format($total_salary, 2) }}</td>
        </tr>
        <tr>
            <th>Cash in Hand</th>
            <td>{{ number_format($cash_in_hand, 2) }}</td>
        </tr>
    </table>

    <h3>Payment Details by Bank</h3>
    <table class="details-table">
        <thead>
            <tr>
                <th>Bank Name</th>
                <th>Account Number</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($paymentsWithDetails as $payment)
                <tr>
                    <td>{{ $payment['bank_name'] }}</td>
                    <td>{{ $payment['account_no'] }}</td>
                    <td>{{ number_format($payment['total_amount'], 2) }}</td>
                </tr>
            @endforeach
            @php
            $total_payments = $paymentsWithDetails->sum('total_amount');
            @endphp
            <tr>
                <td colspan="2">Total Payments</td>
                <td>{{ number_format($total_payments, 2) }}</td>
            </tr>
        </tbody>

    </table>

</body>
</html>
