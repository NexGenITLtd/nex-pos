
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    
</head>
<body>
    <div class="invoice" style="width: 80mm; box-sizing: border-box;font-size: 12px;" id="80mm">
        <style>
        /* Apply print-specific styles */
        @media print {
            body {
                font-size: 12px; /* Smaller font size for printing */
            }

            .invoice {
                width: 80mm; /* Ensure it fits the printer paper size */
                box-sizing: border-box;
            }

            table {
                width: 100%;
                
            }

            th, td {
                padding: 3px 4px !important;
                font-size: 12px;
                text-align: left;
            }

            th {
                text-align: center;
            }

            td.text-right {
                text-align: right;
            }

            .no-print {
                display: none;
            }

            .footer {
                font-size: 12px;
                margin-top: 5px;
                text-align: center;
            }

            img.logo {
                max-width: 35px;
                height: auto;
                
            }

            .store-info {
                font-size: 12px; line-height: 1.2;
            }
        }
    </style>
    <table style="width: 100%;">
        <tbody>
            <tr>
                <td style="text-align: left;">
                    <h5 style="margin-bottom: 0">@if (!empty($invoice->store->logo) && file_exists(public_path('images/stores/' . $invoice->store->logo)))
                    <img src="{{ asset('images/stores/' . $invoice->store->logo) }}" alt="{{ $invoice->store->name }}" class="logo" style="max-width: 35px; height: auto;">@endif{{ $invoice->store->name }}</h5>
                </td>
                <td style="text-align: right;">
                    <p style="margin-bottom: 0;font-size: 12px">{{ $invoice->created_at->format('F j, Y') }}</p>
                </td>
            </tr>
            <tr>
                <td colspan="2"><hr style="margin-top: 2px;margin-bottom: 2px;"></td>
            </tr>
            <tr>
                <td>
                    @if($invoice->customer)
                        <p style="margin-bottom: 0;font-size: 12px">Invoice #: {{ $invoice->id }}</p>
                        <p style="margin-bottom: 0;font-size: 12px">{{ $invoice->customer->name ?? 'Customer ID: '.$invoice->customer->id }}</p>
                        <p style="margin-bottom: 0;font-size: 12px">{{ $invoice->customer->phone ?? '' }}</p>
                    @endif
                </td>
                <td style="text-align: right;">
                    <p style="margin-bottom: 0;font-size: 12px">{{ $invoice->store->address }}</p>
                    <p style="margin-bottom: 0;font-size: 12px">Phone: {{ $invoice->store->phone }}</p>
                    <p style="margin-bottom: 0;font-size: 12px">Email: {{ $invoice->store->email }}</p>
                </td>
            </tr>
        </tbody>
    </table>


    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="font-size: 12px;text-align: left;border: 1px solid #B2B2B2;">Description</th>
                <th style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">Price</th>
                <th style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">Qty</th>
                <th style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">Dis%</th>
                <th style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">VAT%</th>
                <th style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">Total</th>
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
                <tr style="">
                    <td style="font-size: 12px;text-align: left;border: 1px solid #B2B2B2;">{{ $product->product_name }}</td>
                    <td style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">{{ number_format($product->sell_price, 2) }}</td>
                    <td style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">{{ number_format($product->qty, 2) }}</td>
                    <td style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">{{ number_format($product->discount, 2) }}</td>
                    <td style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">{{ number_format($product->vat, 2) }}</td>
                    <td style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">{{ number_format($totalProductPrice, 2) }}</td>
                </tr>
            @endforeach

            @if($invoice->returnSellProducts->isNotEmpty()) 
                <tr style="border-top: 1px solid #B2B2B2;border-bottom: 1px solid #B2B2B2;">
                    <td  colspan="6" style="font-size: 12px;text-align: center; ">{{ __('Returned Products') }}</td>
                </tr>
                @foreach($invoice->returnSellProducts as $product)
                    @php
                        $discountAmount = ($product->sell_price * $product->discount) / 100;
                        $priceAfterDiscount = $product->sell_price - $discountAmount;
                        $vatAmount = ($priceAfterDiscount * $product->vat) / 100;
                        $totalProductPrice = ($priceAfterDiscount + $vatAmount) * $product->qty;
                    @endphp
                    <tr>
                        <td style="font-size: 12px;text-align: left;border: 1px solid #B2B2B2;">{{ $product->product_name }} ({{ $product->product_id }})</td>
                        <td style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">-{{ number_format($product->sell_price, 2) }}</td>
                        <td style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">{{ $product->qty }}</td>
                        <td style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">{{ number_format($product->discount, 2) }}</td>
                        <td style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">{{ number_format($product->vat, 2) }}</td>
                        <td style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">-{{ number_format($totalProductPrice, 2) }}</td>
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
            <tr>
                <th colspan="5" style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">Subtotal</th>
                <th style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">{{ number_format($subtotal, 2) }}</th>
            </tr>
            <tr>
                <th colspan="5" style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">Discount</th>
                <th style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">-{{ number_format($invoiceDiscountAmount, 2) }}</th>
            </tr>
            <tr>
                <th colspan="5" style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">Total VAT</th>
                <th style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">{{ number_format($totalVat, 2) }}</th>
            </tr>
            <tr>
                <th colspan="5" style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">Less</th>
                <th style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">-{{ number_format($lessAmount, 2) }}</th>
            </tr>
            <tr>
                <th colspan="5" style="font-size: 12px; text-align: right;border: 1px solid #B2B2B2;">Total</th>
                <th style="font-size: 12px;text-align: right;border: 1px solid #B2B2B2;">{{ number_format($totalAmount, 2) }}</th>
            </tr>
        </tfoot>

    </table>


    <div>
        <h6 style="font-size: 12px; margin-bottom: 0">Payment Methods:</h6>
        <p style="margin-bottom: 0;font-size: 12px">
            @foreach($invoice->payments as $payment)
            {{ ucfirst(explode('_', $payment->payment_type)[0]) }} : {{ number_format($payment->amount, 2) }}, 
            @endforeach
        </p>
    </div>
    <hr style="margin-top: 2px;margin-bottom: 2px;">
    <div class="footer" style="font-size: 12px; margin-top: 5px; text-align: center;">
        <p style="margin-bottom: 0; font-size: 12px;text-align: center;">{{ $invoice->store->return_policy }}</p>
        <hr style="margin-top: 2px;margin-bottom: 2px;">
        <p style="margin-bottom: 0; font-size: 12px;text-align: center;">Thank you for your shopping!</p>
        <hr style="margin-top: 2px;margin-bottom: 2px;">
        <p style="margin-bottom: 0; font-size: 12px;text-align: center;">nexgenitltd.com</p>
    </div>
    
</div>
<div class="buttons no-print">
    <a href="#" class="btn btn-primary btn-sm" onclick="printDiv('80mm')">Print</a>
    <a href="{{ route('invoice.pdf', $invoice->id) }}" class="btn btn-secondary btn-sm">Download</a>
</div>

</body>
</html>
