<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoices PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

        <!-- Display Filtered Invoices -->
        <h3>{{ $cardHeader }}</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Invoice ID</th>
                    <th>Customer</th>
                    <th>Total Bill</th>
                    <th>Total Return</th>
                    <th>Paid Amount</th>
                    <th>Due</th>
                    <th>Dis(%)</th>
                    <th>Less</th>
                    <th>Manager</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr>
                    <td><a href="{{ route('invoices.show',$invoice->id) }}">{{ $invoice->id }}</a></td>
                    <td>{{ $invoice->customer_id }}-{{ $invoice->customer->name ? $invoice->customer->name : '' }}</td>
                    <td>{{ number_format($invoice->total_bill, 2) }}</td>
                    <td>{{ number_format($invoice->product_return, 2) }}</td>
                    <td>{{ number_format($invoice->paid_amount, 2) }}</td>
                    <td>{{ number_format($invoice->due_amount, 2) }}</td>
                    <td>{{ number_format($invoice->discount, 2) }}</td>
                    <td>{{ number_format($invoice->less_amount, 2) }}</td>
                    <td>{{ $invoice->manager_id }}-{{ $invoice->manager->name ?? 'No Manager Assigned' }}</td>
                    <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10">No invoices found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <!-- Invoice Summary (Optional) -->
        @if($invoices->count() > 0)
        <hr>
        <h5>Invoice Summary</h5>
        @php
            $totalBill = $invoices->sum('total_bill');
            $totalProductReturn = $invoices->sum('product_return');
            $totalPaid = $invoices->sum('paid_amount');
            $totalDue = $invoices->sum('due_amount');
            $totalDiscount = $invoices->sum('discount');
            $totalLess = $invoices->sum('less_amount');
        @endphp

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Total Bill</th>
                    <th>Total Product Return</th>
                    <th>Total Paid</th>
                    <th>Total Due</th>
                    <th>Total Discount (%)</th>
                    <th>Total Less Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ number_format($totalBill, 2) }}</td>
                    <td>{{ number_format($totalProductReturn, 2) }}</td>
                    <td>{{ number_format($totalPaid, 2) }}</td>
                    <td>{{ number_format($totalDue, 2) }}</td>
                    <td>{{ number_format($totalDiscount, 2) }}</td>
                    <td>{{ number_format($totalLess, 2) }}</td>
                </tr>
            </tbody>
        </table>
        @endif

</body>
</html>
