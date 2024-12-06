<!DOCTYPE html>
<html>
<head>
    <title>Sell Products Report</title>
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
    <h2>{{ $cardHeader }}</h2>

    <table>
	    <thead>
	        <tr>
	            <th>ID</th>
	            <th>Invoice ID</th>
	            <th>Product (Code - Name)</th>
	            <th>Sell Price</th>
	            <th>Quantity</th>
	            <th>Sub Total</th>
	            <th>VAT</th>
	            <th>Discount (%)</th>
	            <th>Final Total</th>
	            <th>Created At</th>
	        </tr>
	    </thead>
	    <tbody>
	        @php
	            // Initialize variables for totals
	            $totalSellPrice = 0;
	            $totalQuantity = 0;
	            $totalVAT = 0;
	            $totalDiscount = 0;
	            $totalSubTotal = 0;
	            $totalFinalTotal = 0; // Final total after VAT and discount
	        @endphp

	        @forelse ($sellProducts as $product)
	            @php
	                // Calculate sub total (sell price * quantity)
	                $subTotal = $product->sell_price * $product->qty;

	                // Calculate VAT (assuming VAT is a percentage of the subTotal)
	                $vatAmount = ($subTotal * $product->vat) / 100;

	                // Apply VAT to the sub total
	                $subTotalWithVAT = $subTotal + $vatAmount;

	                // Calculate Discount as a percentage of subTotalWithVAT
	                $discountAmount = ($subTotalWithVAT * $product->discount) / 100;

	                // Final total after VAT and discount
	                $finalTotal = $subTotalWithVAT - $discountAmount;

	                // Add values to totals
	                $totalSellPrice += $product->sell_price;
	                $totalQuantity += $product->qty;
	                $totalVAT += $vatAmount;
	                $totalDiscount += $discountAmount;
	                $totalSubTotal += $subTotal;
	                $totalFinalTotal += $finalTotal;
	            @endphp

	            <tr>
	                <td>{{ $product->id }}</td>
	                <td>{{ $product->invoice_id }}</td>
	                <td>{{ $product->product_id }} - {{ $product->product_name ?? 'N/A' }}</td>
	                <td>{{ number_format($product->sell_price, 2) }}</td>
	                <td>{{ $product->qty }}</td>
	                <td>{{ number_format($subTotal, 2) }}</td>
	                <td>{{ number_format($vatAmount, 2) }}</td>
	                <td>{{ number_format($product->discount, 2) }}%</td>
	                <td>{{ number_format($finalTotal, 2) }}</td>
	                <td>{{ $product->created_at }}</td>
	            </tr>
	        @empty
	            <tr>
	                <td colspan="10" class="text-center">No products found</td>
	            </tr>
	        @endforelse
	    </tbody>
	    <tfoot>
	        <tr>
	            <th colspan="3" class="text-right">Total</th>
	            <th>{{ number_format($totalSellPrice, 2) }}</th>
	            <th>{{ $totalQuantity }}</th>
	            <th>{{ number_format($totalSubTotal, 2) }}</th>
	            <th>{{ number_format($totalVAT, 2) }}</th>
	            <th>{{ number_format($totalDiscount, 2) }}</th>
	            <th>{{ number_format($totalFinalTotal, 2) }}</th>
	            <th></th>
	        </tr>
	    </tfoot>
	</table>


</body>
</html>
