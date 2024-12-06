@extends('layouts.app')
@section('title', 'Supplier Payment List')
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
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>{{ $cardHeader ?? 'Supplier Payment Information' }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Supplier Payment</li>
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
            <h3 class="card-title">{{ $cardHeader ?? 'Supplier payment information' }}</h3>
            <div class="card-tools no-print">
              <form method="GET" action="{{ route('supplier-payments.index') }}" class="form-inline"> 
                <div class="form-group mx-sm-3 mb-2">
                  <label for="date_filter" class="sr-only">Filter by Date:</label>
                  <select name="date_filter" id="date_filter" class="form-control mr-2">
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
                <a href="#" onclick="printDiv('app')" class="btn btn-primary ml-2 mb-2">Print</a>
                <a href="{{ route('supplier-payments.create') }}" class="btn btn-primary ml-2 mb-2">Add New</a>
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
          <!-- /.card-header -->
          <div class="card-body">
            <div class="table-responsive">
              <table id="" class="table table-sm table-bordered table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Supplier</th>
                    <th>Bank Account</th>
                    <th>Store</th>
                    <th>Amount</th>
                    <th>Note</th>
                    <th>Paid Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $total = 0;
                  @endphp
                  @foreach($supplierPayments as $supplierPayment)
                    @php
                      $total += $supplierPayment->amount;
                    @endphp
                    <tr>
                      <td>{{ $supplierPayment->id }}</td>
                      <td>{{ $supplierPayment->supplier->name }}<br>{{ $supplierPayment->supplier->phone }}</td>
                      <td>{{ $supplierPayment->bank_account->bank_name }}<br>{{ $supplierPayment->bank_account->account_no }}</td>
                      <td>{{ $supplierPayment->store->name }}</td>
                      <td>{{ $supplierPayment->amount }}</td>
                      <td>{{ $supplierPayment->note }}</td>
                      <td>{{ $supplierPayment->paid_date }}</td>
                      <td>
                        <a href="{{ route('supplier-payments.edit', $supplierPayment->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('supplier-payments.destroy', $supplierPayment->id) }}" method="POST" style="display:inline;">
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
                    <th colspan="4">Total</th>
                    <th>{{ $total }}</th>
                    <th colspan="3"></th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
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