<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    
</head>

<body>
    <div class="container my-5" id="A4">
        <style>
            body {
                background-color: #f8f9fa;
                color: #000;
            }
            p{
                margin-bottom: 0;
            }
            .invoice {
                background-color: #fff;
                padding: 10px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .invoice-header {
                border-bottom: 2px solid #dee2e6;
                margin-bottom: 10px;
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

            .table-invoice th,
            .table-invoice td {
                vertical-align: middle;
                border: 1px solid #dee2e6;
                padding : 4px;
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
                max-width: 70px;
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
              .footer {
                    position: fixed;
                    bottom: 0;
                    width: 100%;
                    background: #f8f9fa; /* Optional: Add a light background */
                    padding: 10px 0;
                    text-align: center;
                }
            }
        .text-muted { color: #000 !important;}
        .justify-content-between {
            display: flex;
            justify-content: space-between;
        }
        .text-end {
            text-align: right !important;
        }
        .align-items-center {
            display: flex;
            align-items: center;
        }
        
        </style>
        <div style="width: 100%">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="invoice" id="invoice">
                        <!-- Invoice Header -->
                        
                        <div class="invoice-header">
                            <div class="row justify-content-between"  style="width:100%">
                                <!-- Invoice number on left -->
                                <div class="col-md-6 text-left" style="width:50%";>
                                    <p>
                                    <h3 style="font-size: 18px;font-weight: bold !important;"><img src="{{ asset('images/stores/' . $invoice->store->logo) }}" alt="{{ $invoice->store->name }}" class="logo"><br>{{ $invoice->store->name }}</h3>
                                    </p>
                                </div>
                                <!-- Date on right -->
                                <div class="col-md-6 text-end" style="width:50%";>
                                    <h6 class="pb-0 mb-0"><strong>Head Office:</strong></h6>
                                    <p>{!! $invoice->store->address !!}<br><i class="fa fa-phone"></i> {{ $invoice->store->phone }}<br><i class="fa fa-envelope"></i> {{ $invoice->store->email }}</p>
                                    
                                </div>
                            </div>
                            <div class="row justify-content-between mb-0">
                                <div class="col-md-12">
                                    <table style="width: 100%"> 
                                        <tr>
                                            <td>
                                                <div class="text-left">
                                                    <p>Challan No: <strong>{{ $invoice->id }}</strong></p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <span style="border-radius: 25px;border: 1px solid #000;padding: 3px 15px;font-size: 14px" ><strong><i>DELIVERY CHALLAN</i></strong></span>
                                                    <div class="mt-2 text-bold">Customer ID: {{ $invoice->customer->id }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-end">
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
                                        <th class="text-center" width="10%">QTY</th>
                                        <th class="text-end" width="15%">UNIT PEICE</th>
                                        <!-- <th class="text-end">Dis(%)</th> -->
                                        <!-- <th class="text-end">Vat(%)</th> -->
                                        <th class="text-end">TOTAL</th>
                                        <th class="text-center" width="6%">REMARKS</th>
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
                                            <td class="text-center">{{ $product->qty }}</td>
                                            <td class="text-end">{{ number_format($product->sell_price, 2) }}</td>
                                            
                                            <!-- <td class="text-end">{{ number_format($product->discount, 2) }}%</td> -->
                                            <!-- <td class="text-end">{{ number_format($product->vat, 2) }}%</td> -->
                                            <td class="text-end">{{ number_format($totalProductPrice, 2) }}</td>
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
                                        <th colspan="4" class="text-end">Sub-total</th>
                                        <td class="text-end">{{ number_format($subtotal, 2) }}</td>
                                        
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
                                        <th colspan="4" class="text-end">Less Value</th>
                                        <td class="text-end">-{{ number_format($lessAmount, 2) }}</td>
                                        <td></td>
                                    </tr>

                                    <tr class="total-row" style="background-color: {{ $totalAmount > 0 ? '#d4f7d4' : '#ffffff' }}">
                                        <th colspan="4" class="text-end">Total Value</th>
                                        <td class="text-end">{{ number_format($totalAmount, 2) }}</td>
                                        <td></td>
                                    </tr>

                                    <tr class="total-row" style="background-color: {{ $invoice->payments->sum('amount') > 0 ? '#d4f7d4' : '#ffffff' }}">
                                        <th colspan="4" class="text-end">Cash Value</th>
                                        <td class="text-end">{{ number_format($invoice->payments->sum('amount'), 2) }}</td>
                                        <td></td>
                                    </tr>

                                    <tr class="total-row" style="background-color: {{ $invoice->due_amount > 0 ? '#ffff99' : '#ffffff' }}">
                                        <th colspan="4" class="text-end">Due Value</th>
                                        <td class="text-end">{{ number_format($invoice->due_amount, 2) }}</td>
                                        <td></td>
                                    </tr>

                                </tfoot>
    
                            </table>
                        </div>
    
                        <!-- Signature Section -->
                        <div class="footer text-center mt-4">
                        <div class="row">
                            <div class="col-sm-6 text-left">
                                <!-- <br><br> -->
                                <p>RECEIVED BY</p>
                            </div>
                            <div class="col-sm-6 text-end">
                                <!-- <br><br> -->
                                <p>AUTHORIZED BY</p>
                            </div>
                        </div>
                        
                        </div>

                    
                        <!-- Buttons -->
                        <div class="buttons no-print">
                            <a href="#" class="btn btn-primary btn-sm" onclick="printDiv('A4')">Print</a>
                            <a href="{{ route('invoice.pdf', $invoice->id) }}" class="btn btn-secondary btn-sm">Download</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</body>

</html>
