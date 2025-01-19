@extends('layouts.app')
@section('title', 'Report')
@section('link')
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="{{ asset('backend/') }}/plugins/fontawesome-free/css/all.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('backend/') }}/dist/css/adminlte.min.css">
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Report</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Report</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->


<!-- Main content -->
<section class="content" id="report">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ $cardHeader }}</h5>
                        <div class="card-tools no-print">
                            <form method="GET" action="{{ route('reports.index') }}" class="form-inline">
                                <div class="form-group mx-sm-3 mb-2">
                                    <label for="date_filter" class="sr-only">Filter by Date:</label>
                                    <select name="date_filter" id="date_filter" class="form-control mr-2">
                                        <option value="">-- Select Filter --</option>
                                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                                        <option value="previous_day" {{ request('date_filter') == 'previous_day' ? 'selected' : '' }}>Previous Day</option> <!-- New option -->
                                        <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                        <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                        <option value="this_year" {{ request('date_filter') == 'this_year' ? 'selected' : '' }}>This Year</option>
                                        <option value="all_time" {{ request('date_filter') == 'all_time' ? 'selected' : '' }}>All Time</option>
                                        <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                                    </select>
                                </div>

                                <div class="form-group mx-sm-3 mb-2" id="customDateInputs" style="{{ request('date_filter') == 'custom' ? '' : 'display: none;' }}">
                                    <label for="start_date" class="sr-only">Start Date:</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control mr-2" value="{{ request('start_date') }}" placeholder="Start Date">

                                    <label for="end_date" class="sr-only">End Date:</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control mr-2" value="{{ request('end_date') }}" placeholder="End Date">
                                </div>

                                <div class="form-group mx-sm-3 mb-2">
                                    <label for="store_id" class="sr-only">Filter by Store:</label>
                                    <select name="store_id" id="store_id" class="form-control mr-2">
                                        <option value="">-- All Stores --</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary mb-2">Apply Filter</button>
                                <a href="#" onclick="printDiv('report')" class="btn btn-primary ml-2 mb-2">Print</a>
                            </form>

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
                        </div>
                    </div>

                    <div class="card-body">
                      <div class="card">
                        <div class="card-body">
                            <!-- Main Title -->
                            <div class="row mb-4">
                                <div class="col-12 text-center">
                                    <h1 class="h3">Probability Report</h1>
                                    <p class="text-muted">A detailed overview of stock, sales, and returns data.</p>
                                </div>
                            </div>

                            <!-- Metrics Boxes -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th>Metric</th>
                                                    <th>Amount</th>
                                                    <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Total Stock</td>
                                                    <td>{{ $totalStock }}</td>
                                                    <td>Total quantity of items currently in stock.</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Stock-in Purchase Value</td>
                                                    <td>{{ $website_info->currency }} {{ number_format($totalStockInPurchaseValue, 2) }}</td>
                                                    <td>Total purchase value of stock-in (Purchase Price x Quantity).</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Stock-in Sell Value</td>
                                                    <td>{{ $website_info->currency }} {{ number_format($totalStockInSellValue, 2) }}</td>
                                                    <td>Total sell value of stock-in (Sell Price x Quantity).</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Sold</td>
                                                    <td>{{ $totalSold }}</td>
                                                    <td>Total quantity of items sold.</td>
                                                </tr>
                                                <tr>
                                                    <td>Sold Products' Purchase Price</td>
                                                    <td>{{ $website_info->currency }} {{ number_format($totalSoldPurchasePrice, 2) }}</td>
                                                    <td>Total purchase cost of all sold products.</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Sold Value</td>
                                                    <td>{{ $website_info->currency }} {{ number_format($totalSellValue, 2) }}</td>
                                                    <td>Total revenue from sold items.</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Returned</td>
                                                    <td>{{ $totalReturned }}</td>
                                                    <td>Total quantity of items returned by customers.</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Return Purchase Value</td>
                                                    <td>{{ $website_info->currency }} {{ number_format($totalReturnPurchaseValue, 2) }}</td>
                                                    <td>Total value of returned items (Purchase Price x Quantity).</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Return Sell Value</td>
                                                    <td>{{ $website_info->currency }} {{ number_format($totalReturnSellValue, 2) }}</td>
                                                    <td>Total value of returned items (Sell Price x Quantity).</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Available Quantity</td>
                                                    <td>{{ $totalAvailableQty }}</td>
                                                    <td>Net available stock after sales and returns.</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Available Stock-in Purchase Value</td>
                                                    <td>{{ $website_info->currency }} {{ number_format($totalAvailableStockInValue, 2) }}</td>
                                                    <td>Purchase value of all available stock-in.</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Available Stock Sell Value</td>
                                                    <td>{{ $website_info->currency }} {{ number_format($totalAvailableStockAfterSellValue, 2) }}</td>
                                                    <td>Available stock sell value.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                      </div>
                      <div class="card">
                          <div class="card-body">
                            <!-- Title for the Report -->
                            <div class="row mb-3">
                                <div class="col-12 text-center">
                                    <h3 >Net Sell Report</h3>
                                    <p class="text-muted">Overview of total sales, returns, due amounts, and profit for the selected period.</p>
                                </div>
                            </div>

                            <!-- Report Cards -->
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>Metric</th>
                                                <th>Amount</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Total Invoices</td>
                                                <td>{{ $totalInvoices }}</td>
                                                <td>Total number of invoices generated.</td>
                                            </tr>
                                            <tr>
                                                <td>Total Sales Value</td>
                                                <td>{{ $website_info->currency }} {{ number_format($totalInvoiceSales, 2) }}</td>
                                                <td>Total amount from all sales invoices.</td>
                                            </tr>
                                            <tr>
                                                <td>Total Returned Value</td>
                                                <td>{{ $website_info->currency }} {{ number_format($totalInvoiceReturnSell, 2) }}</td>
                                                <td>Total value of returned products from invoices.</td>
                                            </tr>
                                            <tr>
                                                <td>Total Due Value</td>
                                                <td>{{ $website_info->currency }} {{ number_format($totalInvoiceDue, 2) }}</td>
                                                <td>Total amount due for unpaid invoices.</td>
                                            </tr>
                                            <tr>
                                                <td>Net Sell Value</td>
                                                <td>{{ $website_info->currency }} {{ number_format($totalInvoiceSell, 2) }}</td>
                                                <td>Total sell from all invoices after returns and costs.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                          </div>
                      </div>
                      
                        <!-- Supplier Summary Box -->
                        <div class="card">
                            
                            <div class="card-body">
                                <!-- Title for the Report -->
                                <div class="row mb-3">
                                    <div class="col-12 text-center">
                                        <h3>Supplier Summary</h3>
                                        <p class="text-muted">Detailed breakdown of purchases, payments, dues, and advances for the selected period.</p>
                                    </div>
                                </div>
                                <!-- Cumulative Totals -->
                                <div class="row">

                                    <div class="col-12">
                                        <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th>Metric</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Total Purchase</td>
                                                    <td>{{ $website_info->currency }} {{ number_format($totalSupplierPurchase, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Payment</td>
                                                    <td>{{ $website_info->currency }} {{ number_format($totalSupplierPayment, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Due</td>
                                                    <td>{{ $website_info->currency }} {{ number_format($totalSupplierDue, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Advance</td>
                                                    <td>{{ $website_info->currency }} {{ number_format($totalSupplierAdvance, 2) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>

                                </div>

                                <!-- Individual Supplier Data -->
                                <div class="row">
                                    <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered table-striped">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th>Supplier Name</th>
                                                    <th>Total Purchase ({{ $website_info->currency }} )</th>
                                                    <th>Total Payment ({{ $website_info->currency }} )</th>
                                                    <th>Total Due ({{ $website_info->currency }} )</th>
                                                    <th>Total Advance ({{ $website_info->currency }} )</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($suppliers as $supplier)
                                                <tr>
                                                    <td>{{ $supplier['name'] }}</td>
                                                    <td>{{ number_format($supplier['totalPurchase'], 2) }}</td>
                                                    <td>{{ number_format($supplier['totalPayment'], 2) }}</td>
                                                    <td>{{ number_format($supplier['due'], 2) }}</td>
                                                    <td>{{ number_format($supplier['advance'], 2) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <!-- Title for the Report -->
                                <div class="row mb-3">
                                    <div class="col-12 text-center">
                                        <h3>Financial Summary Report</h3>
                                        <p class="text-muted">Detailed breakdown of total expenses and salaries for the selected period.</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <!-- Financial Summary Table -->
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered mt-4">
                                                <thead class="bg-primary text-white">
                                                    <tr>
                                                        <th>Report Type</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Total Expense</td>
                                                        <td>{{ $website_info->currency }} {{ number_format($totalExpense, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Salary</td>
                                                        <td>{{ $website_info->currency }} {{ number_format($totalSalary, 2) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    

                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!--/. container-fluid -->
</section>
<!-- /.content -->

@section('script')
<!-- jQuery -->
<script src="{{ asset('backend/') }}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{ asset('backend/') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="{{ asset('backend/') }}/dist/js/adminlte.js"></script>
@endsection
@endsection
