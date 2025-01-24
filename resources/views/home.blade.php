@extends('layouts.app')
@section('title', 'Dashboard')
@section('link')
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="{{asset('backend/')}}/plugins/fontawesome-free/css/all.min.css">
<!-- IonIcons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('backend/')}}/dist/css/adminlte.min.css">
<style type="text/css">
    .btn-tool {
        background: 0 0;
        color: #adb5bd;
        font-size: .875rem;
        margin: -3px 0;
        padding: .25rem .5rem;
    }
</style>
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
@php
$stores = App\Models\Store::get();
@endphp
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-12">
                @can('show dashboard')
                <div class="card">
                    <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-sms"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Total SMS</span>
                                <span class="info-box-number">
                                {{ $smsSetting->rate != 0 ? $smsSetting->balance / $smsSetting->rate : 0 }}
                                
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-file-alt"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Total Send</span>
                                <span class="info-box-number">{{ $smsSetting->sms_count }}</span>
                            </div>
                            <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <!-- fix for small devices only -->
                        <div class="clearfix hidden-md-up"></div>

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-truck"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Total Supplier</span>
                                <span class="info-box-number">{{ $totalSupplier }}</span>
                            </div>
                            <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Total Customer</span>
                                <span class="info-box-number">{{ $totalCustomers }}</span>
                            </div>
                            <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header ">
                        <h5 class="card-title mb-0">{{ $cardHeader }}</h5>
                        <!-- Settings Icon -->
                        <span  title="Filter" id="settingsIcon" class=" btn-tool float-right ml-2 " style="font-size: 1.2rem; transition: transform 0.3s;">
                            <i class="fas fa-cog"></i>
                        </span>
                        <!-- Card Tools Section -->
                        <div class="card-tools none-print" id="settingsPanel" style="display: none;">
                            <form method="GET" action="" class="form-inline">
                                <div class="form-group mx-sm-3">
                                    <label for="date_filter" class="sr-only">Filter by Date:</label>
                                    <select name="date_filter" id="date_filter" class="form-control form-control-sm ">
                                        <option value="">-- Select Filter --</option>
                                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                                        <option value="previous_day" {{ request('date_filter') == 'previous_day' ? 'selected' : '' }}>Previous Day</option>
                                        <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                        <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                        <option value="this_year" {{ request('date_filter') == 'this_year' ? 'selected' : '' }}>This Year</option>
                                        <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                                    </select>
                                </div>

                                <div class="form-group mx-sm-3" id="customDateInputs" style="{{ request('date_filter') == 'custom' ? '' : 'display: none;' }}">
                                    <label for="start_date" class="sr-only">Start Date:</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}" placeholder="Start Date">

                                    <label for="end_date" class="sr-only">End Date:</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}" placeholder="End Date">
                                </div>

                                <div class="form-group mx-sm-3">
                                    <label for="store_id" class="sr-only">Filter by Store:</label>
                                    <select name="store_id" id="store_id" class="form-control form-control-sm ">
                                        <option value="">-- All Stores --</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm">Apply Filter</button>
                                <a href="{{ route('report.pdf', request()->all()) }}" class="btn btn-success btn-sm ml-2">Download PDF</a>
                            </form>
                        </div>
                        
                    </div>

                

                        <div class="row">
                            <!-- Total Invoices -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file-alt"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Invoices</span>
                                        <span class="info-box-number">{{ $total_invoices }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Sales -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chart-bar"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Sales</span>
                                        <span class="info-box-number">{{ number_format($total_sales, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Return Sell -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-undo-alt"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Sell Return</span>
                                        <span class="info-box-number">{{ number_format($total_return_sell, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sold Product Purchase Price -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-tags"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Purchase Price</span>
                                        <span class="info-box-number">{{ number_format($total_purchase_price, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            @can('show profit')
                            <!-- Total Profit -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-teal elevation-1"><i class="fas fa-dollar-sign"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Profit</span>
                                        <span class="info-box-number">{{ number_format($total_profit, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            @endcan
                            <!-- Total Due -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-hand-holding"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Due</span>
                                        <span class="info-box-number">{{ number_format($total_due, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Supplier Payment -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-building"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Supplier Payment</span>
                                        <span class="info-box-number">{{ number_format($total_supplier_payment, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Expense -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-dark elevation-1"><i class="fas fa-wallet"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Expense</span>
                                        <span class="info-box-number">{{ number_format($total_expense, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Employee Salary -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-light elevation-1"><i class="fas fa-user-tie"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Employee Salary</span>
                                        <span class="info-box-number">{{ number_format($total_salary, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Cash In Hand -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-indigo elevation-1"><i class="fas fa-hand-holding-usd"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Cash In Hand</span>
                                        <span class="info-box-number">{{ number_format($cash_in_hand, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @if(!empty($$paymentsWithDetails))
                            <!-- Payment Details -->
                            <div class="col-12 col-sm-6 col-md-6">
                                <div class="info-box">
                                    <div class="card w-100 p-0 m-0">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-university"></i>  Cash & Bank Account-Wise Payments</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body p-0 mb-0">
                                            <table class="table table-bordered table-striped mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Bank Name</th>
                                                        <th>Total Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($paymentsWithDetails as $payment)
                                                        <tr>
                                                            <td>{{ $payment['bank_name'] }}</td>
                                                            <td style="color: {{ $payment['total_amount'] > 10000 ? 'red' : 'green' }};">
                                                                {{ number_format($payment['total_amount'], 2) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Bank Current Balance -->
                            <div class="col-12 col-sm-6 col-md-6">
                                <div class="info-box">
                                    <div class="card w-100 p-0 m-0">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-university"></i> Cash & Bank Current Balance</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body p-0 mb-0">
                                            <table class="table table-bordered table-striped mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Bank Name</th>
                                                        <th>Account No</th>
                                                        <th>Current Balance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($bankAccounts as $bankAccount)
                                                        <tr>
                                                            <td>{{ $bankAccount->bank_name }}</td>
                                                            <td>{{ $bankAccount->account_no }}</td>
                                                            <td style="color: {{ $bankAccount->current_balance > 10000 ? 'red' : 'green' }};">
                                                                {{ number_format($bankAccount->current_balance, 2) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                @endcan
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!--/. container-fluid -->
</section>
<!-- /.content -->
@endsection
@section('script')

<script>
    let isClockwise = true; // Variable to track rotation direction
    const settingsIcon = document.getElementById('settingsIcon');
    const settingsPanel = document.getElementById('settingsPanel');

    settingsIcon.addEventListener('click', function () {
        // Toggle settings panel visibility
        if (settingsPanel.style.display === 'none' || settingsPanel.style.display === '') {
            settingsPanel.style.display = 'block';
        } else {
            settingsPanel.style.display = 'none';
        }

        // Rotate the icon
        if (isClockwise) {
            settingsIcon.style.transform = 'rotate(90deg)';
        } else {
            settingsIcon.style.transform = 'rotate(-90deg)';
        }
        isClockwise = !isClockwise; // Toggle rotation direction
    });
</script>
<script>
    document.getElementById('date_filter').addEventListener('change', function () {
        var customDateInputs = document.getElementById('customDateInputs');
        if (this.value === 'custom') {
            customDateInputs.style.display = 'block';
        } else {
            customDateInputs.style.display = 'none';
        }
    });
</script> 
<!-- jQuery -->
<script src="{{asset('backend/')}}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{asset('backend/')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="{{asset('backend/')}}/dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="{{asset('backend/')}}/plugins/chart.js/Chart.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('backend/')}}/dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('backend/')}}/dist/js/pages/dashboard3.js"></script>
@endsection
