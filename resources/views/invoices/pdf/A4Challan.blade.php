<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
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
            /* border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
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
            padding: 4px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #fff;
        }
        .invoice-header  th, td {
            border: 0px solid black;
            padding: 3px;
            text-align: left;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #000;
        }
    </style>
           
</head>

<body>
    <div class="container-fluid">
        
        <div style="width: 100%">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="invoice" id="invoice">
                        <!-- Invoice Header -->
                        
                        <div class="invoice-header">
                            
                            <div class="row justify-content-between mb-0">
                                <div class="col-md-12">
                                    <table style="width: 100%">
                                        <tr>
                                            <td>
                                                <h4 style="font-size: 16px;font-weight: bold !important;"><img src="{{ asset('images/stores/' . $invoice->store->logo) }}" alt="" class="logo"><br>{{ $invoice->store->name }}</h4>
                                            </td>
                                            <td style="text-align: right">
                                                <h5 class="pb-0 mb-0"><strong>Head Office:</strong></h5>
                                                <p>{!! $invoice->store->address !!}<br><i class="fa fa-phone"></i> {{ $invoice->store->phone }}<br><i class="fa fa-envelope"></i> {{ $invoice->store->email }}</p>
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%">
                                        <tr>
                                            <td>
                                                <div class="text-left">
                                                    <p>Challan No: <strong>{{ $invoice->id }}</strong></p>
                                                </div>
                                            </td>
                                            <td style="text-align: center">
                                                
                                                    <strong><i><span style="border-radius: 25px;border: 1px solid #000;padding:6px 15px;font-size: 14px" >DELIVERY CHALLAN</span></i></strong><br><br>
                                                    <strong>Customer ID: {{ $invoice->customer->id }}</strong>
                                            </td>
                                            <td>
                                                <div style="text-align: right">
                                                    <p>Date: {{ $invoice->created_at->format('j F, Y') }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
    
                        <!-- Customer Details -->
                        <div class="row mb-2">
                            
                            <div class="col-sm-12">
                                <table style="width: 100%">
                                    <tr>
                                        <td class="text-left" width="33%">Party Name: {!! $invoice->customer->name ? $invoice->customer->name : ''  !!}</td>
                                        <td class="text-left" width="33%">Phone: {!! $invoice->customer->phone ? $invoice->customer->phone : '' !!}</td>
                                        <td class="text-left">Email: {!! $invoice->customer->email ? $invoice->customer->email : '' !!}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" width="33%">Buyer: {!! $invoice->customer->buyer ? $invoice->customer->buyer : '' !!}</td>
                                        <td class="text-left" width="33%">Brand: {!! $invoice->customer->brand ? $invoice->customer->brand : '' !!}</td>
                                        <td class="text-left" width="33%">Address: {!! $invoice->customer->address ? $invoice->customer->address : '' !!}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
    
                        <!-- Invoice Items -->
                        <div class="table-responsive">
                            <table class="table-invoice table  table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="10%">SL NO.</th>
                                        <th>DESCRIPTION</th>
                                        <th style="text-align: center" width="10%">QTY</th>
                                        <th style="text-align: right" width="15%">UNIT PEICE</th>
                                        <!-- <th class="text-end">Dis(%)</th> -->
                                        <!-- <th class="text-end">Vat(%)</th> -->
                                        <th style="text-align: right">TOTAL</th>
                                        <th style="text-align: center" width="6%">REMARKS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoice->sellProducts as $key => $product)
                                        @php
                                            $discountAmount = ($product->sell_price * $product->discount) / 100;
                                            $priceAfterDiscount = $product->sell_price - $discountAmount;
                                            $vatAmount = ($priceAfterDiscount * $product->vat) / 100;
                                            $totalProductPrice = ($priceAfterDiscount + $vatAmount) * $product->qty;
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $product->product_name }} ({{ $product->product_id }})</td>
                                            <td style="text-align: center">{{ $product->qty }}</td>
                                            <td style="text-align: right">{{ number_format($product->sell_price, 2) }}</td>
                                            <!-- <td class="text-end">{{ number_format($product->discount, 2) }}%</td> -->
                                            <!-- <td class="text-end">{{ number_format($product->vat, 2) }}%</td> -->
                                            <td style="text-align: right">{{ number_format($totalProductPrice, 2) }}</td>
                                            <td class="text-center"></td>
                                        </tr>
                                    @endforeach
                                    @if($invoice->returnSellProducts->isNotEmpty())
                                        <tr>
                                            <td class="text-center" colspan="6">{{ __('Returned Products') }}</td>
                                        </tr>
                                        @foreach($invoice->returnSellProducts as $product)
                                            @php
                                                $discountAmount = ($product->sell_price * $product->discount) / 100;
                                                $priceAfterDiscount = $product->sell_price - $discountAmount;
                                                $vatAmount = ($priceAfterDiscount * $product->vat) / 100;
                                                $totalProductPrice = ($priceAfterDiscount + $vatAmount) * $product->qty;
                                            @endphp
                                            <tr>
                                                <td>{{ $product->product_name }} ({{ $product->product_id }})</td>
                                                <td style="text-align: right">{{ number_format($product->sell_price, 2) }}</td>
                                                <td class="text-center">-{{ $product->qty }}</td>
                                                <td style="text-align: right">{{ number_format($product->discount, 2) }}%</td>
                                                <td style="text-align: right">{{ number_format($product->vat, 2) }}%</td>
                                                <td style="text-align: right">-{{ number_format($totalProductPrice, 2) }}</td>
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
                                        <th colspan="4" style="text-align: right">Sub-total</th>
                                        <td style="text-align: right">{{ number_format($subtotal, 2) }}</td>
                                        
                                        <td></td>
                                    </tr>
                                    <!-- <tr class="total-row">
                                        <th colspan="4" class="text-end">Return Amount</th>
                                        <td class="text-end">-{{ number_format($returnTotal, 2) }}</td>
                                        <td></td>
                                    </tr> -->
                                    <!-- <tr class="total-row">
                                        <th colspan="4" class="text-end">Discount ({{ $invoice->discount }}%)</th>
                                        <td class="text-end">-{{ number_format($invoiceDiscountAmount, 2) }}</td>
                                        <td></td>
                                    </tr> -->
                                    
                                    <!-- <tr class="total-row">
                                        <th colspan="4" class="text-end">Total VAT</th>
                                        <td class="text-end">{{ number_format($totalVat, 2) }}</td>
                                        <td></td>
                                    </tr> -->
                                    
                                    <tr class="total-row" style="background-color: {{ $lessAmount < 0 ? '#ffdddd' : '#ffffff' }}">
                                        <th colspan="4" style="text-align: right">Less Value</th>
                                        <td style="text-align: right">-{{ number_format($lessAmount, 2) }}</td>
                                        <td></td>
                                    </tr>

                                    <tr class="total-row" style="background-color: {{ $totalAmount > 0 ? '#d4f7d4' : '#ffffff' }}">
                                        <th colspan="4" style="text-align: right">Total Value</th>
                                        <td style="text-align: right">{{ number_format($totalAmount, 2) }}</td>
                                        <td></td>
                                    </tr>

                                    <tr class="total-row" style="background-color: {{ $invoice->payments->sum('amount') > 0 ? '#d4f7d4' : '#ffffff' }}">
                                        <th colspan="4" style="text-align: right">Cash Value</th>
                                        <td style="text-align: right">{{ number_format($invoice->payments->sum('amount'), 2) }}</td>
                                        <td></td>
                                    </tr>

                                    <tr class="total-row" style="background-color: {{ $invoice->due_amount > 0 ? '#ffff99' : '#ffffff' }}">
                                        <th colspan="4" style="text-align: right">Due Value</th>
                                        <td style="text-align: right">{{ number_format($invoice->due_amount, 2) }}</td>
                                        <td></td>
                                    </tr>

                                </tfoot>
                            </table>
                        </div>
    
                        <!-- Signature Section -->
                        <div class="footer text-center mt-4">
                        <div class="row">
                            <div class="col-md-12 ">
                            <table style="width: 100%;position: fixed;
                    bottom: 20;
                    width: 100%;
                    padding: 10px 5px;
                    text-align: center;">
                                        <tr>
                                            <td>
                                            <p>RECEIVED BY</p>
                                            </td>
                                            <td style="text-align: right">
                                                <p>AUTHORIZED BY</p>
                                            </td>
                                        </tr>
                                    </table>
                            </div>
                        </div>
                        
                        </div>

                    
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</body>

</html>
