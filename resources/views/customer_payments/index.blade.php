@extends('layouts.app')
@section('title', 'Customer Payments')
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
        <h1>Customer Payments</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Customer Payments</li>
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
            <h3 class="card-title">Customer Payments</h3>
            <div class="card-tools no-print">
            	<form method="GET" action="{{ route('customer-payments.index') }}" class="mb-4 form-inline">
		            <div class="form-row">
		                
		                <div class="form-group form-group mx-sm-3 mb-2">
		                    <label for="start_date">Start Date</label>
		                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
		                </div>

		                <div class="form-group form-group mx-sm-3 mb-2">
		                    <label for="end_date">End Date</label>
		                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
		                </div>
		                <button type="submit" class="btn btn-primary  mb-2">Filter</button>
		                <a href="{{route('customer-payments.create')}}" class="btn btn-success ml-2 mb-2">Add New</a>
		                <a href="#" onclick="printDiv('app')" class="btn btn-primary ml-2 mb-2 float-right">Print</a>
		            </div>
		            
		        </form>
              
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="table-responsive">
		        <table class="table table-bordered table-sm" id="example1">
		            <thead>
		                <tr>
		                    <th>ID</th>
		                    <th>Invoice ID</th>
		                    <th>Customer Phone</th>
		                    <th>Payment Type</th>
		                    <th>Amount</th>
		                    <th>Date</th>
		                    <th>Store</th>
		                    <th>Actions</th>
		                </tr>
		            </thead>
		            <tbody>
		                @foreach ($customerPayments as $payment)
		                    <tr>
		                        <td>{{ $payment->id }}</td>
		                        <td>{{ $payment->invoice_id }}</td>
		                        <td>{{ $payment->invoice->customer->phone }}</td>
		                        <td>{{ $payment->payment_type }}</td>
		                        <td>{{ $payment->amount }}</td>
		                        <td>{{ $payment->created_at->format('Y-m-d') }}</td>
		                        <td>{{ $payment->invoice && $payment->invoice->store ? $payment->invoice->store->name : 'N/A' }}</td>

		                        <td>
		                        	<span class="no-print">
			                            <a href="{{ route('customer-payments.edit', $payment->id) }}" class="btn btn-warning btn-delete btn-sm">Edit</a>
			                            <form action="{{ route('customer-payments.destroy', $payment->id) }}" method="POST" style="display:inline;">
			                                @csrf
			                                @method('DELETE')
			                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
			                            </form>
			                        </span>
		                        </td>
		                    </tr>
		                @endforeach
		            </tbody>
		        </table>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->
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