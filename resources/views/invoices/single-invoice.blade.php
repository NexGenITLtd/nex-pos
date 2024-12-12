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
        body {
            background-color: #f8f9fa;
        }

        .invoice {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .invoice-header {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 20px;
        }

        .invoice-footer {
            border-top: 2px solid #dee2e6;
            margin-top: 20px;
        }

        .invoice-title {
            font-size: 32px;
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

        .logo {
            max-width: 120px;
            height: auto;
        }

        .buttons {
            margin-top: 20px;
            text-align: right;
        }
        @media print {
          .no-print {
            display: none;
          }
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="invoice p-5 " id="invoice">
                    <!-- Invoice Header -->
                    <div class="invoice-header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="invoice-title">Invoice</h2>
                            <p>Invoice #: {{ $invoice->id }}</p>
                            <p>Date: {{ $invoice->created_at->format('F j, Y') }}</p>
                        </div>
                        <div class="text-end">

                            @if (!empty($invoice->store->logo) && file_exists(public_path('images/stores/' . $invoice->store->logo)))
                                <img src="{{ asset('images/stores/' . $invoice->store->logo) }}" alt="{{ $invoice->store->name }}" class="logo">
                            @else
                                <h5>{{ $invoice->store->name }}</h5>
                            @endif

                            <p>{{ $invoice->store->address }}<br>Phone: {{ $invoice->store->phone }}<br>Email: {{ $invoice->store->email }}</p>
                        </div>
                    </div>

                    <!-- Customer Details -->
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h6>Bill To:</h6>
                            @if($invoice->customer)
                                <p>
                                    {!! $invoice->customer->name ? $invoice->customer->name . '<br>' : '' !!}
                                    {!! $invoice->customer->address ? $invoice->customer->address . '<br>' : '' !!}
                                    {!! $invoice->customer->phone ? $invoice->customer->phone . '<br>' : '' !!}
                                    {!! $invoice->customer->email ? 'Email: ' . $invoice->customer->email : '' !!}

                                </p>
                            @else
                                <p>No customer details available.</p>
                            @endif
                        </div>
                        <div class="col-sm-6 text-end">
                            <h6>Service By:</h6>
                            <p>
                                {{ $invoice->manager->name ?? 'No Manager Assigned' }}<br>
                                Seller: {{ $invoice->sell_person->name ?? 'No Seller Assigned' }}
                            </p>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
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
                                        <td class="text-end">{{ number_format($product->sell_price, 2) }} {{ $website_info->currency }}</td>
                                        <td class="text-center">{{ $product->qty }}</td>
                                        <td class="text-end">{{ number_format($product->discount, 2) }}%</td>
                                        <td class="text-end">{{ number_format($product->vat, 2) }}%</td>
                                        <td class="text-end">{{ number_format($totalProductPrice, 2) }} {{ $website_info->currency }}</td>
                                    </tr>
                                @endforeach
                                @if($invoice->returnSellProducts->isNotEmpty())
                                    <tr>
                                        <td class="text-center" colspan="7">{{ __('Returned Products') }}</td>
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
                                            <td class="text-end">-{{ number_format($totalProductPrice, 2) }}</td>
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
                                    <td class="text-end">{{ number_format($subtotal, 2) }}</td>
                                </tr>
                                <tr class="total-row">
                                    <th colspan="6" class="text-end">Return Amount</th>
                                    <td class="text-end">-{{ number_format($returnTotal, 2) }}</td>
                                </tr>
                                <tr class="total-row">
                                    <th colspan="6" class="text-end">Discount ({{ $invoice->discount }}%)</th>
                                    <td class="text-end">-{{ number_format($invoiceDiscountAmount, 2) }}</td>
                                </tr>
                                <tr class="total-row">
                                    <th colspan="6" class="text-end">Less Amount</th>
                                    <td class="text-end">-{{ number_format($lessAmount, 2) }}</td>
                                </tr>
                                <tr class="total-row">
                                    <th colspan="6" class="text-end">Total VAT</th>
                                    <td class="text-end">{{ number_format($totalVat, 2) }}</td>
                                </tr>
                                <tr class="total-row">
                                    <th colspan="6" class="text-end">Total</th>
                                    <td class="text-end">{{ number_format($totalAmount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Payment Summary -->
                    <div class="row invoice-footer">
                        <div class="col-sm-6">
                            @if($invoice->payments->isNotEmpty())
                                <p class="lead">Payment Methods:</p>
                                @foreach($invoice->payments as $payment)
                                    <p>{{ Str::title(Str::replace('_', ' ', $payment->payment_type)) }}: {{ number_format($payment->amount, 2) }} {{ $website_info->currency }}</p>
                                @endforeach
                            @else
                                <p>No payments available.</p>
                            @endif
                        </div>
                        
                        <div class="col-sm-6 text-end">
                            <h5>@if($invoice->due_amount>0)Total Due: {{ number_format($invoice->due_amount, 2) }} {{ $website_info->currency }}@else Paid @endif</h5>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="invoice-footer text-center mt-3">
                        <p class="text-muted">{{ $invoice->store->return_policy }}</p>
                    </div>
                    <div class="invoice-footer text-center mt-3">
                        <p class="text-muted">Thank you for your shopping!</p>
                    </div>
                    <div class="invoice-footer text-center mt-3">
                        <p class="text-muted">nexgenitltd.com</p>
                    </div>
                    <!-- Buttons -->
                    <div class="buttons no-print">
                        <a href="{{ route('invoice.pdf', $invoice->id) }}" class="btn btn-secondary">Download</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">


    function printDiv(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
    function downloadImage(divId) {
        html2canvas(document.getElementById(divId)).then(function(canvas) {
            // Create a link element
            var link = document.createElement('a');
            link.href = canvas.toDataURL(); // Convert the canvas to a data URL
            link.download = 'invoice_' + divId + '.png'; // Set the file name
            link.click(); // Trigger the download
        });
    }
</script>
</body>

</html>
