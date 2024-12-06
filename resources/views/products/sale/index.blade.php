
@extends('layouts.app')
@section('title', 'Product Sell List')
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
        <h1>Product Sell List</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Product Sell List</li>
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
            <h3 class="card-title">Product sell list</h3>
            <div class="card-tools">
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="table-responsive">
            	<table class="table table-sm table-striped table-bordered">
			        <thead>
			            <tr>
			                <th>ID</th>
			                <th>Invoice ID</th>
			                <th>Product ID</th>
			                <th>Product Name</th>
			                <th>Sell Price</th>
			                <th>Quantity</th>
			                <th>VAT</th>
			                <th>Discount</th>
			                <th>Created At</th>
			            </tr>
			        </thead>
			        <tbody>
			            @forelse ($sellProducts as $product)
			                <tr>
			                    <td>{{ $product->id }}</td>
			                    <td>{{ $product->invoice_id }}</td>
			                    <td>{{ $product->product_id }}</td>
			                    <td>{{ $product->product_name ?? 'N/A' }}</td>
			                    <td>{{ number_format($product->sell_price, 2) }}</td>
			                    <td>{{ $product->qty }}</td>
			                    <td>{{ number_format($product->vat, 2) }}</td>
			                    <td>{{ number_format($product->discount, 2) }}</td>
			                    <td>{{ $product->created_at }}</td>
			                </tr>
			            @empty
			                <tr>
			                    <td colspan="9" class="text-center">No products found</td>
			                </tr>
			            @endforelse
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