<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        
        .invoice {
            background-color: #fff;
            padding: 0;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .invoice-header {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 0px;
            width: 100% !important;
        }

        .invoice-footer {
            border-top: 2px solid #dee2e6;
            margin-top: 5px;
        }

        .invoice-title {
            font-size: 26px;
            font-weight: bold;
            color: #007bff;
        }

        .table th,
        .table td {
            vertical-align: middle;
            border: 1px solid #dee2e6;
        }

        .total-row {
            border: 1px solid #dee2e6;
        }

        .total-row th {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
        }


        table {
            width: 100%;
            border-collapse: collapse;
        }

        .table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #fff;
        }
        .invoice-header  th, td {
            border: 0px solid black;
            padding: 0px;
            text-align: left;
        }
        .company-name {
            font-size: 26px;
            font-weight: bold;
            color: #000;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="invoice" id="invoice">
                    <!-- Invoice Header -->
                    <div class="invoice-header d-flex justify-content-between align-items-center">
                    	<table style="width: 100%">
                    		<thead>
                                <tr>
                                    <td style="text-align: left"><h2 class="invoice-title">Invoice #: {{ $invoice->id }}</h2><p style="margin-bottom: 0 !important;padding: 0 !important;">Date: {{ $invoice->created_at->format('F j, Y') }}</p></td>

                                    <td style="text-align: right">
                                        @if (!empty($invoice->store->logo) && file_exists(public_path('images/stores/' . $invoice->store->logo)))
                                        <h3 class="m-0">
                                            <img src="{{ public_path('images/stores/' . $invoice->store->logo) }}" 
                                                 alt="{{ $invoice->store->name }}" 
                                                 class="img-fluid logo" 
                                                 style="max-width: 100px; height: auto;"><br><small>{{ $invoice->store->name }}</small>
                                        </h3>
                                    @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="float: left;">
                                        <h6>Shop Info:</h6>
                                        <p>{!! $invoice->store->address !!}<br><i class="fa fa-phone"></i> {{ $invoice->store->phone }}<br><i class="fa fa-envelope"></i> {{ $invoice->store->email }}</p>
                                    </td>
                                    <td style="text-align: right;">
                                        <h6>Bill To:</h6>
                                        @if($invoice->customer)
                                        <p style="margin-bottom: 0">
                                        {!! $invoice->customer->name ? $invoice->customer->name . '<br>' : '' !!}
                                        {!! $invoice->customer->address ? $invoice->customer->address . '<br>' : '' !!}
                                        {!! $invoice->customer->phone ? $invoice->customer->phone : '' !!}
                                        {!! $invoice->customer->email ? '<br>Email: ' . $invoice->customer->email : '' !!}

                                        </p>
                                        @else
                                        <p style="margin-bottom: 0">No customer details available.</p>
                                        @endif
                                        <!-- <h6>Service By:</h6> -->
                                        <p style="margin-bottom: 0">
                                        <!-- {{ $invoice->manager->name ?? 'No Manager Assigned' }}<br> -->
                                        Service By: {{ $invoice->sell_person->name ?? 'No Seller Assigned' }}
                                        </p>
                                    </td>
                                </tr>
                    		</thead>
                    	</table>
            
                    </div>

                    

                    <!-- Invoice Items -->
                    <div class="table-responsive">
                        <table class="table table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Description</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Dis(%)</th>
                                    <th class="text-end">Vat(%)</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->sellProducts as $product)
                                    @php
                                        $discountAmount = ($product->sell_price * $product->discount) / 100;
                                        $priceAfterDiscount = $product->sell_price - $discountAmount;
                                        $vatAmount = ($priceAfterDiscount * $product->vat) / 100;
                                        $totalProductPrice = ($priceAfterDiscount + $vatAmount) * $product->qty;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $product->product_name }} ({{ $product->product_id }})</td>
                                        <td class="text-end">{{ number_format($product->sell_price, 2) }} {{ $invoice->store->currency }}</td>
                                        <td class="text-center">{{ $product->qty }}</td>
                                        <td class="text-end">{{ number_format($product->discount, 2) }}%</td>
                                        <td class="text-end">{{ number_format($product->vat, 2) }}%</td>
                                        <td class="text-end" style="text-align: right;">{{ number_format($totalProductPrice, 2) }} {{ $invoice->store->currency }}</td>
                                    </tr>
                                @endforeach
                                @if($invoice->returnSellProducts->isNotEmpty())
                                    <tr>
                                        <td class="text-center" style="text-align: center;" colspan="7">{{ __('Returned Products') }}</td>
                                    </tr>
                                    @foreach($invoice->returnSellProducts as $product)
                                        @php
                                            $discountAmount = ($product->sell_price * $product->discount) / 100;
                                            $priceAfterDiscount = $product->sell_price - $discountAmount;
                                            $vatAmount = ($priceAfterDiscount * $product->vat) / 100;
                                            $totalProductPrice = ($priceAfterDiscount + $vatAmount) * $product->qty;
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $product->product_name }} ({{ $product->product_id }})</td>
                                            <td class="text-end">{{ number_format($product->sell_price, 2) }}</td>
                                            <td class="text-center">-{{ $product->qty }}</td>
                                            <td class="text-end">{{ number_format($product->discount, 2) }}%</td>
                                            <td class="text-end">{{ number_format($product->vat, 2) }}%</td>
                                            <td class="text-end" style="text-align: right;">-{{ number_format($totalProductPrice, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            @php
                                // Calculate total for sell products
                                $sellSubtotal = $invoice->sellProducts->sum(function ($product) {
                                    $discountAmount = ($product->sell_price * $product->discount) / 100;
                                    $priceAfterDiscount = $product->sell_price - $discountAmount;
                                    $vatAmount = ($priceAfterDiscount * $product->vat) / 100;
                                    return ($priceAfterDiscount + $vatAmount) * $product->qty;
                                });

                                // Calculate total for returned products
                                $returnTotal = $invoice->returnSellProducts->sum(function ($product) {
                                    $discountAmount = ($product->sell_price * $product->discount) / 100;
                                    $priceAfterDiscount = $product->sell_price - $discountAmount;
                                    $vatAmount = ($priceAfterDiscount * $product->vat) / 100;
                                    return ($priceAfterDiscount + $vatAmount) * $product->qty;
                                });

                                // Adjust subtotal by deducting return products
                                $subtotal = $sellSubtotal - $returnTotal;

                                // Calculate invoice-wide discount
                                $invoiceDiscountAmount = ($subtotal * $invoice->discount) / 100;

                                // Less amount
                                $lessAmount = $invoice->less_amount;

                                // Calculate total VAT for sell and return products
                                $sellVat = $invoice->sellProducts->sum(function ($product) {
                                    $discountAmount = ($product->sell_price * $product->discount) / 100;
                                    $priceAfterDiscount = $product->sell_price - $discountAmount;
                                    return ($priceAfterDiscount * $product->vat) / 100 * $product->qty;
                                });

                                $returnVat = $invoice->returnSellProducts->sum(function ($product) {
                                    $discountAmount = ($product->sell_price * $product->discount) / 100;
                                    $priceAfterDiscount = $product->sell_price - $discountAmount;
                                    return ($priceAfterDiscount * $product->vat) / 100 * $product->qty;
                                });

                                $totalVat = $sellVat - $returnVat;

                                // Calculate final total
                                $totalAmount = $subtotal - $invoiceDiscountAmount - $lessAmount;
                            @endphp

                            <tfoot>
                                <tr class="total-row">
                                    <th colspan="6" class="text-end">Subtotal</th>
                                    <td class="text-end" style="text-align: right;">{{ number_format($subtotal, 2) }}</td>
                                </tr>
                                <tr class="total-row">
                                    <th colspan="6" class="text-end">Return Amount</th>
                                    <td class="text-end" style="text-align: right;">-{{ number_format($returnTotal, 2) }}</td>
                                </tr>
                                <tr class="total-row">
                                    <th colspan="6" class="text-end">Discount ({{ $invoice->discount }}%)</th>
                                    <td class="text-end" style="text-align: right;">-{{ number_format($invoiceDiscountAmount, 2) }}</td>
                                </tr>
                                <tr class="total-row">
                                    <th colspan="6" class="text-end">Less Amount</th>
                                    <td class="text-end" style="text-align: right;">-{{ number_format($lessAmount, 2) }}</td>
                                </tr>
                                <tr class="total-row">
                                    <th colspan="6" class="text-end">Total VAT</th>
                                    <td class="text-end" style="text-align: right;">{{ number_format($totalVat, 2) }}</td>
                                </tr>
                                <tr class="total-row">
                                    <th colspan="6" class="text-end">Total</th>
                                    <td class="text-end" style="text-align: right;">{{ number_format($totalAmount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Payment Summary -->
                    <div class="row invoice-footer">
                    	<table>
                    		<tbody>
                    			<tr>
                    				<td>
                    					<div>
				                            @if($invoice->payments->isNotEmpty())
				                                <p class="lead">Payment Methods:</p>
				                                @foreach($invoice->payments as $payment)
				                                    <p>{{ Str::title(Str::replace('_', ' ', $payment->payment_type)) }}: {{ number_format($payment->amount, 2) }} {{ $invoice->store->currency }}</p>
				                                @endforeach
				                            @else
				                                <p>No payments available.</p>
				                            @endif
				                        </div>
                    				</td>
                    				@if($invoice->due_amount>0)
                    				<td style="text-align: right;">
                    					<div class="text-end">
				                            <h5 class="text-danger" style="color: red;">@if($invoice->due_amount>0)Total Due: {{ number_format($invoice->due_amount, 2) }} {{ $invoice->store->currency }}@else Paid @endif</h5>
				                        </div>
                    				</td>
                    				@endif
                    			</tr>
                    		</tbody>
                    	</table>
                        
                        
                    </div>

                    <!-- Footer -->
                    <div class="invoice-footer text-center mt-3">
                        <p class="text-muted" style="text-align: center;">{{ $invoice->store->return_policy }}</p>
                    </div>
                    <div class="invoice-footer text-center mt-3">
                        <p class="text-muted" style="text-align: center;">Thank you for your shopping!</p>
                    </div>
                    <div class="invoice-footer text-center mt-3">
                        <p class="text-muted" style="text-align: center;">nexgenitltd.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
