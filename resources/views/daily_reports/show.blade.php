@extends('layouts.app')
@section('title', 'Daily Report')
@section('link')
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="{{ asset('backend/') }}/plugins/fontawesome-free/css/all.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('backend/') }}/dist/css/adminlte.min.css">
<style type="text/css">
	.report-maker-table th, .report-maker-table td{
		padding: 3px 4px;
	}
</style>
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Daily Report</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Daily Report</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid" id="dailyReports">
        
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                    	<!-- Store Info Section -->
				        <div class="row mt-0">
				            <div class="col-md-12 text-center">
				                <div class="d-flex justify-content-between align-items-center">
				                    <div class="text-left">
				                        <h3 class="mb-0">{{ $dailyreport->store->name }}</h3>
				                        <p class="mb-0">{{ $dailyreport->store->phone }}</p>
				                        <p class="mb-0">{{ $dailyreport->store->address }}</p>
				                    </div>
				                    <div>
				                        <p><strong>Date:</strong> {{ $dailyreport->date }}</p>
				                    </div>
				                </div>
				            </div>
				        </div>

                        
                        <div class="card-tools no-print">
                            <a href="#" onclick="printDiv('app')" class="btn btn-sm btn-primary"><i class="fa fa-print"></i></a>
                            <a href="{{ route('dailyreports.index') }}" class="btn btn-success btn-sm ml-2 mb-2 float-right"><i class="fa fa-angle-double-left"></i> Back</a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="row mt-0">
                        	
                            <div class="col-md-12"><h5 class="text-center mt-0">{{ $cardHeader }}</h5>
                                <table class="table table-sm table-bordered report-maker-table">
                                    
                                    <!-- Summary Section -->
                                    <tr><th colspan="2" class="text-center bg-success text-white">Summary</th></tr>
                                    <tr>
                                        <td>Total Invoices</td>
                                        <td class="text-right"><span>{{ intval($dailyreport->total_invoices) }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Total Sales</td>
                                        <td class="text-right"><span>{{ $dailyreport->total_sales }} {{ $website_info->currency }}</span></td>
                                    </tr>
                                    @if(Auth::user()->role == 'admin')
                                    <tr>
                                        <td>Total Profit</td>
                                        <td class="text-right"><span>{{ $dailyreport->total_profit }} {{ $website_info->currency }}</span></td>
                                    </tr>
                                    @endif
                                    <!-- Cash Management Section -->
                                    <tr><th colspan="2" class="text-center bg-warning text-dark">Cash Management</th></tr>
                                    <tr>
                                        <td>Previous Day Cash</td>
                                        <td class="text-right"><span>{{ $dailyreport->previous_cash_in_hand }} {{ $website_info->currency }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Extra Cash From Owner</td>
                                        <td class="text-right"><span>{{ $dailyreport->extra_cash }} {{ $website_info->currency }}</span></td>
                                    </tr>

                                    <!-- Returns and Purchases -->
                                    <tr><th colspan="2" class="text-center bg-info text-white">Returns and Purchases</th></tr>
                                    <tr>
                                        <td>Total Sell Return</td>
                                        <td class="text-right"><span>{{ $dailyreport->total_return_sell }} {{ $website_info->currency }}</span></td>
                                    </tr>
                                    @if(Auth::user()->role == 'admin')
                                    <tr>
                                        <td>Total Purchase Price</td>
                                        <td class="text-right"><span>{{ $dailyreport->total_purchase_price }} {{ $website_info->currency }}</span></td>
                                    </tr>
                                    @endif
                                    <!-- Expense and Payments -->
                                    <tr><th colspan="2" class="text-center bg-danger text-white">Expenses and Payments</th></tr>
                                    <tr>
                                        <td>Total Supplier Payments</td>
                                        <td class="text-right"><span>{{ $dailyreport->total_supplier_payment }} {{ $website_info->currency }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Total Expense</td>
                                        <td class="text-right"><span>{{ $dailyreport->total_expense }} {{ $website_info->currency }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Extra Expense</td>
                                        <td class="text-right"><span>{{ $dailyreport->extra_expense }} {{ $website_info->currency }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Total Salary</td>
                                        <td class="text-right"><span>{{ $dailyreport->total_salary }} {{ $website_info->currency }}</span></td>
                                    </tr>

                                    <!-- Deposits -->
                                    <tr><th colspan="2" class="text-center bg-success text-white">Owner Deposits</th></tr>
                                    <tr>
                                        <td>Cash </td>
                                        <td class="text-right"><span>{{ $dailyreport->owner_deposit }} {{ $website_info->currency }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Bank</td>
                                        <td class="text-right"><span>{{ $dailyreport->bank_deposit }} {{ $website_info->currency }}</span></td>
                                    </tr>

                                    <!-- Final Calculations Section -->
                                    <tr><th colspan="2" class="text-center bg-danger text-white">Final Calculations</th></tr>
                                    <tr>
                                        <td>Total Due</td>
                                        <td class="text-right"><span>{{ $dailyreport->total_due }} {{ $website_info->currency }}</span></td>
                                    </tr>
                                    @if(Auth::user()->role == 'admin')
                                    <tr>
                                        <td>Net Profit</td>
                                        <td class="text-right"><span>{{ $dailyreport->net_profit }} {{ $website_info->currency }}</span></td>
                                    </tr>
                                    @endif
                                    <!-- Cash in Hand for Next Day Section -->
                                    <tr><th colspan="2" class="text-center bg-info text-white">Cash in Hand</th></tr>
                                    <tr>
                                        <td>Cash in Hand</td>
                                        <td class="text-right"><span>{{ $dailyreport->cash_in_hand }} {{ $website_info->currency }}</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Manager and Owner Signature Section -->
                        <div class="row row-signature">
                            <div class="col-md-6 text-center" style="width: 50%">
                                <hr>
                                <p>Manager's Signature</p>
                            </div>
                            <div class="col-md-6 text-center" style="width: 50%">
                                <hr>
                                <p>Owner's Signature</p>
                            </div>
                        </div>

                        <style>
                            .table th, .table td {
                                vertical-align: middle;
                                text-align: center;
                                font-weight: bold;
                                padding: 10px;
                                font-size: 16px;
                                color: #000;
                            }
                            .table th {
                                background-color: #f7f7f7;
                            }
                            .table td {
                                background-color: #f1f1f1;
                            }
                            .text-center {
                                text-align: center;
                            }
                            .row-signature {
                                margin-top: 50px !important;
                            }
                            .col-md-12 {
                                margin-top: 5px;
                            }
                        </style>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/. container-fluid -->
</section>

<!-- /.content -->

@section('script')
<!-- jQuery -->
<script src="{{ asset('backend/') }}/plugins/jquery/jquery.min.js"></script>
<!-- vue -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.js"></script>
<!-- Bootstrap -->
<script src="{{ asset('backend/') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="{{ asset('backend/') }}/dist/js/adminlte.js"></script>

@endsection
@endsection
