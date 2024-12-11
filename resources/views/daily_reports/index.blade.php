
@extends('layouts.app')
@section('title', 'Daily Reports')
@section('link')
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('backend/')}}/plugins/fontawesome-free/css/all.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="{{asset('backend/')}}/ionicons/2.0.1/css/ionicons.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('backend/')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('backend/')}}/dist/css/adminlte.min.css">
<!-- Google Font: Source Sans Pro -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
@endsection
@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Daily Reports</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Daily Reports</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content" id="report">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
	        <div class="card-header">
	            <h1 class="card-title">{{ $filterDescription }}</h1>
	            <div class="card-tools">
	            	<form method="GET" action="{{ route('dailyreports.index') }}" class="form-inline">
                                <div class="form-group mx-sm-3 mb-2">
                                    <label for="date_filter" class="sr-only">Filter by Date:</label>
                                    <select name="date_filter" id="date_filter" class="form-control form-control-sm mr-2">
    <option value="">-- Select Filter --</option>
    <option value="today" {{ $dateFilter == 'today' ? 'selected' : '' }}>Today</option>
    <option value="previous_day" {{ $dateFilter == 'previous_day' ? 'selected' : '' }}>Previous Day</option>
    <option value="last_7_days" {{ $dateFilter == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
    <option value="this_month" {{ $dateFilter == 'this_month' ? 'selected' : '' }}>This Month</option>
    <option value="this_year" {{ $dateFilter == 'this_year' ? 'selected' : '' }}>This Year</option>
    <option value="all_time" {{ $dateFilter == 'all_time' ? 'selected' : '' }}>All Time</option>
    <option value="custom" {{ $dateFilter == 'custom' ? 'selected' : '' }}>Custom Range</option>
</select>

                                </div>

                                <div class="form-group mx-sm-3 mb-2" id="customDateInputs" style="{{ request('date_filter') == 'custom' ? '' : 'display: none;' }}">
                                    <label for="start_date" class="sr-only">Start Date:</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control form-control-sm mr-2" value="{{ request('start_date') }}" placeholder="Start Date">

                                    <label for="end_date" class="sr-only">End Date:</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control form-control-sm mr-2" value="{{ request('end_date') }}" placeholder="End Date">
                                </div>

                                <div class="form-group mx-sm-3 mb-2">
                                    <label for="store_id" class="sr-only">Filter by Store:</label>
                                    <select name="store_id" id="store_id" class="form-control form-control-sm mr-2">
                                        <option value="">-- All Stores --</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm mb-2">Apply Filter</button>
                                <a href="#" onclick="printDiv('report')" class="btn btn-primary btn-sm ml-2 mb-2">Print</a>
                                <a href="{{ route('dailyreports.create') }}" class="btn btn-primary  btn-sm ml-2 mb-2">Add New</a>
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
                <div class="table-responsive">
				    <table class="table table-sm table-bordered" id="example1">
					    <thead>
					        <tr>
					            <th>#</th>
					            <th>Store</th>
					            <th>Date</th>
					            <th>Invoices</th>
					            <th>Previous Cash</th>
					            <th>Extra Cash From Owner</th>
					            <th>Sales</th>
					            <th>Return Sell</th>
					            @can('show profit')
					            <th>Purchase Price</th>
					            <th>Profit</th>
					            <th>Net Profit</th>
					            @endcan
					            <th>Due</th>
					            <th>Supplier Payments</th>
					            <th>Expense</th>
					            <th>Salary</th>
					            <th>Extra Expense</th>
					            <th>Owner Cash Deposit</th>
					            <th>Owner Bank Deposit</th>
					            <th>Cash in Hand</th>
					            <th>Actions</th>
					        </tr>
					    </thead>
					    <tbody>
					        @foreach($reports as $report)
					            <tr>
					                <td>{{ $loop->iteration }}</td>
					                <td>{{ $report->store->name }}</td>
					                <td>{{ $report->date }}</td>
					                <td>{{ number_format($report->total_invoices, 2) }}</td>
					                <td>{{ number_format($report->previous_cash_in_hand, 2) }}</td>
					                <td>{{ number_format($report->extra_cash, 2) }}</td>
					                <td>{{ number_format($report->total_sales, 2) }}</td>
					                <td>{{ number_format($report->total_return_sell, 2) }}</td>
					                @can('show profit')
					                <td>{{ number_format($report->total_purchase_price, 2) }}</td>
					                <td>{{ number_format($report->total_profit, 2) }}</td>
					                <td>{{ number_format($report->net_profit, 2) }}</td>
					                @endcan
					                <td>{{ number_format($report->total_due, 2) }}</td>
					                <td>{{ number_format($report->total_supplier_payment, 2) }}</td>
					                <td>{{ number_format($report->total_expense, 2) }}</td>
					                <td>{{ number_format($report->total_salary, 2) }}</td>
					                <td>{{ number_format($report->extra_expense, 2) }}</td>
					                <td>{{ number_format($report->owner_deposit, 2) }}</td>
					                <td>{{ number_format($report->bank_deposit, 2) }}</td>
					                <td>{{ number_format($report->cash_in_hand, 2) }}</td>
					                <td>
					                    <a href="{{ route('dailyreports.show', $report->id) }}" class="btn btn-info btn-sm">Show</a>
					                    
					                    <a href="{{ route('dailyreports.edit', $report->id) }}" class="btn btn-warning btn-sm">Edit</a>
					                    <form action="{{ route('dailyreports.destroy', $report->id) }}" method="POST" style="display:inline;">
					                        @csrf
					                        @method('DELETE')
					                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
					                    </form>
					                    
					                </td>
					            </tr>
					        @endforeach
					    </tbody>
					    <tfoot>
					        <tr>
					            <th colspan="3">Total</th>
					            <th>{{ number_format($reports->sum('total_invoices'), 2) }}</th>
					            <th>{{ number_format($reports->sum('previous_cash_in_hand'), 2) }}</th>
					            <th>{{ number_format($reports->sum('extra_cash'), 2) }}</th>
					            <th>{{ number_format($reports->sum('total_sales'), 2) }}</th>
					            <th>{{ number_format($reports->sum('total_return_sell'), 2) }}</th>
					            @can('show profit')
					            <th>{{ number_format($reports->sum('total_purchase_price'), 2) }}</th>
					            <th>{{ number_format($reports->sum('total_profit'), 2) }}</th>
					            <th>{{ number_format($reports->sum('net_profit'), 2) }}</th>
					            @endcan
					            <th>{{ number_format($reports->sum('total_due'), 2) }}</th>
					            <th>{{ number_format($reports->sum('total_supplier_payment'), 2) }}</th>
					            <th>{{ number_format($reports->sum('total_expense'), 2) }}</th>
					            <th>{{ number_format($reports->sum('total_salary'), 2) }}</th>
					            <th>{{ number_format($reports->sum('extra_expense'), 2) }}</th>
					            <th>{{ number_format($reports->sum('owner_deposit'), 2) }}</th>
					            <th>{{ number_format($reports->sum('bank_deposit'), 2) }}</th>
					            <th>{{ number_format($reports->sum('cash_in_hand'), 2) }}</th>
					            <th></th>
					        </tr>
					    </tfoot>
					</table>

				</div>

            </div>
        </div>
      </div>
    </div>
  </div>
</section>
 
@endsection
@section('script')
<!-- jQuery -->
<script src="{{asset('backend/')}}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('backend/')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="{{asset('backend/')}}/plugins/datatables/jquery.dataTables.js"></script>
<script src="{{asset('backend/')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- AdminLTE App -->
<script src="{{asset('backend/')}}/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('backend/')}}/dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": false,
      "autoWidth": false,
    });
  });
</script>
@endsection