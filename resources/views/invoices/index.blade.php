@extends('layouts.app')
@section('title', 'Invoice List')
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
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Invoice</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Invoice</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
<div class="container-fluid">
  	<div class="row">
  		<div class="col-12">
  			<div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ $cardHeader }}</h5>
                    <!-- Settings Icon -->
                    <span  title="Filter" id="settingsIcon" class=" btn-tool float-right ml-2 " style="font-size: 1.2rem; transition: transform 0.3s;">
                        <i class="fas fa-cog"></i>
                    </span>
                    <!-- Card Tools Section -->
                    <div class="card-tools no-print" id="settingsPanel" style="display: none;">
                    <form method="GET" action="{{ route('invoices.index') }}" class="form-inline mb-4" id="filterForm">
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="date_filter" class="sr-only">Filter by Date:</label>
                            <select name="date_filter" id="date_filter" class="form-control form-control-sm mr-2">
                                <option value="">-- Select Filter --</option>
                                <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="previous_day" {{ request('date_filter') == 'previous_day' ? 'selected' : '' }}>Previous Day</option>
                                <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                <option value="this_year" {{ request('date_filter') == 'this_year' ? 'selected' : '' }}>This Year</option>
                                <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                            </select>
                        </div>

                        <div class="form-group mx-sm-3 mb-2" id="customDateInputs" style="display: none;">
                            <label for="start_date" class="sr-only">Start Date:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control form-control-sm mr-2" placeholder="Start Date">
                            
                            <label for="end_date" class="sr-only">End Date:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control form-control-sm mr-2" placeholder="End Date">
                        </div>

                        <div class="form-group mx-sm-3 mb-2">
                            <label for="store_id" class="sr-only">Filter by Store:</label>
                            <select name="store_id" id="store_id" class="form-control form-control-sm mr-2">
                                <option value="">-- All Stores --</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mx-sm-3 mb-2 form-check">
                            <input type="checkbox" name="due" value="true" class="form-check-input mr-2" id="due">
                            <label class="form-check-label" for="due">Due Only</label>
                        </div>

                        <div class="form-group mx-sm-3 mb-2 form-check">
                            <input type="checkbox" name="full_paid" value="true" class="form-check-input mr-2" id="full_paid">
                            <label class="form-check-label" for="full_paid">Full Paid Only</label>
                        </div>

                        <button type="submit" class="btn btn-sm btn-primary mb-2">Apply Filter</button>
                        <a href="{{ route('invoices.pdf.download', request()->all()) }}" class="btn btn-sm btn-success ml-2 mb-2">Download PDF</a>
                        <a href="#" onclick="printDiv('app')" class="btn btn-sm btn-primary ml-2 mb-2">Print</a>
                    </form>

                    <script>
                        // JavaScript to toggle custom date inputs
                        const dateFilter = document.getElementById('date_filter');
                        const customDateInputs = document.getElementById('customDateInputs');
                        const filterForm = document.getElementById('filterForm');

                        // Show/hide custom date inputs based on initial selection
                        if (dateFilter.value === 'custom') {
                            customDateInputs.style.display = 'block';
                        }

                        dateFilter.addEventListener('change', function() {
                            if (this.value === 'custom') {
                                customDateInputs.style.display = 'block';
                            } else {
                                customDateInputs.style.display = 'none';
                            }
                        });

                        // Hide custom date inputs when the form is submitted
                        filterForm.addEventListener('submit', function() {
                            if (dateFilter.value !== 'custom') {
                                customDateInputs.style.display = 'none';
                            }
                        });
                    </script>
                    </div>
                </div>
  				
  				<div class="card-body">
  					<span class="table-responsive">
  						<!-- Display Filtered Invoices -->
					    
					    <table class="table table-bordered table-sm">
					        <thead>
					            <tr>
					                <th>Invoice ID</th>
					                <th>Customer</th>
					                <th>Total Bill</th>
					                <th>Total Return</th>
					                <th>Paid Amount</th>
					                <th>Due Amount</th>
					                <th>Discount(%)</th>
					                <th>Less Amount</th>
					                <th>Manager</th>
					                <th>Salesperson</th>
					                <th>Store</th>
					                <th>Date</th>
					                <th class="no-print">Action</th>
					            </tr>
					        </thead>
					        <tbody>
					            @forelse($invoices as $invoice)
					            <tr>
					                <td><a href="{{ route('invoices.show',$invoice->id) }}">{{ $invoice->id }}</a></td>
					                <td>{{ $invoice->customer_id }}-{{ $invoice->customer->name ? $invoice->customer->name : '' }}</td>
					                <td>{{ number_format($invoice->total_bill, 2) }}</td>
					                <td>{{ number_format($invoice->product_return, 2) }}</td>
					                <td>{{ number_format($invoice->paid_amount, 2) }}</td>
					                <td>{{ number_format($invoice->due_amount, 2) }}</td>
					                <td>{{ number_format($invoice->discount, 2) }}</td>
					                <td>{{ number_format($invoice->less_amount, 2) }}</td>
					                <td>{{ $invoice->manager_id }}-{{ $invoice->manager->name ?? 'No Manager Assigned' }}</td>
					                <td>{{ $invoice->sell_person_id }}-{{ $invoice->sell_person->name ?? 'No Seller Assigned' }}</td>
					                <td>{{ $invoice->store_id }}-{{ $invoice->store->name ?? 'N/A' }}</td>
					                <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
					                <td class="text-center no-print">@can('update invoice')<a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-primary btn-sm">Edit</a>@endcan @can('delete invoice')<a href="{{route('delete-invoice', $invoice->id)}}"class="btn btn-danger btn-sm">Delete</a>@endcan</td>
					            </tr>
					            @empty
					            <tr>
					                <td colspan="13">No invoices found</td>
					            </tr>
					            @endforelse
					        </tbody>
					    </table>
					    <!-- Invoice Summary (Optional) -->
					    @if($invoices->count() > 0)
					    <hr>
					    <h5 class="card-title">Invoice Summary</h5>
					    @php
					        $totalBill = $invoices->sum('total_bill');
					        $totalProductReturn = $invoices->sum('product_return');
					        $totalPaid = $invoices->sum('paid_amount');
					        $totalDue = $invoices->sum('due_amount');
					        $totalDiscount = $invoices->sum('discount');
					        $totalLess = $invoices->sum('less_amount');
					    @endphp

					    <table class="table table-bordered table-sm">
					        <thead>
					            <tr>
					                <th>Total Bill</th>
					                <th>Total Product Return</th>
					                <th>Total Paid</th>
					                <th>Total Due</th>
					                <th>Total Discount(%)</th>
					                <th>Total Less Amount</th>
					            </tr>
					        </thead>
					        <tbody>
					            <tr>
					                <td>{{ number_format($totalBill, 2) }}</td>
					                <td>{{ number_format($totalProductReturn, 2) }}</td>
					                <td>{{ number_format($totalPaid, 2) }}</td>
					                <td>{{ number_format($totalDue, 2) }}</td>
					                <td>{{ number_format($totalDiscount, 2) }}</td>
					                <td>{{ number_format($totalLess, 2) }}</td>
					            </tr>
					        </tbody>
					    </table>
					    @endif
  					</span>
  					
  				</div>

  			</div>
  		</div>
  	</div>

</div>
  <!-- /.container-fluid -->
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
      "paging": false,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": false,
      "autoWidth": false,
    });
  });
</script>
@endsection