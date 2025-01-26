@extends('layouts.app')
@section('title', 'Stock List')
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
        <h1>Stock</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Stock</li>
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
            <h3 class="card-title">Stock Information</h3>
            <div class="card-tools">
              
              <a href="{{route('product-stock-ins.direct')}}" class="btn btn-sm btn-warning ml-2">Stock-In</a>
              <a href="{{route('stockins.create')}}" class="btn btn-sm btn-success">Bulk Stock-In</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="table-responsive">
              <table id="example2" class="table table-sm table-bordered table-striped">
                <thead>
                <tr>
                  <!-- <th>#</th> -->
                  <th>ID</th>
                  <th width="8%">invoice_no</th>
                  <th>store</th>
                  <!-- <th>stock-in date</th> -->
                  <th class="text-center p-0">
                    <table id="stock-table" class="table table-sm table-bordered bg-light mb-0">
                      <thead>
                        <tr>
                          <td colspan="8">Stock Details</td>
                        </tr>
                        <tr>
                            <!-- <th>#</th> -->
                            <!-- <th>Product Code</th> -->
                            <th>Product (Code)</th>
                            <th>Supplier</th>
                            <!-- <th>Rack</th> -->
                            <th>Qty</th>
                            <th>Purchase Price</th>
                            <th>Sell Price</th>
                            <th>Total Price</th>
                            <!-- <th>Expiration Date</th> -->
                            <!-- <th>Alert Date</th> -->
                        </tr>
                      </thead>
                      </table>
                  </th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($batchs as $key => $batch)
                <tr>
                  <!-- <td>{{ $loop->iteration }}</td> -->
                  <td><a href="{{route('stockins.show',$batch->id)}}" class="text-bold">{{$batch->id}}</a></td>
                  <td><a href="{{route('stockins.show',$batch->id)}}" class="text-bold">{{$batch->invoice_no}}</a></td>
                  <td>{{$batch->store->name}}</td>
                  <!-- <td>{{$batch->stock_date}}</td> -->
                  <td>
                  <div class="table-responsive">
                    <table id="stock-table" class="table table-sm table-bordered bg-light mb-0 p-0">
                        <thead>
                        <tr>
                            <!-- <th>#</th> -->
                            <!-- <th>Product Code</th> -->
                            <th>Product (Code)</th>
                            <th>Supplier</th>
                            <!-- <th>Rack</th> -->
                            <th>Qty</th>
                            <th>Purchase Price</th>
                            <th>Sell Price</th>
                            <th>Total Price</th>
                            <!-- <th>Expiration Date</th> -->
                            <!-- <th>Alert Date</th> -->
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $total_qty = 0;
                            $total_price = 0;
                        @endphp
                        @foreach($batch->stock_ins as $key => $stock_in)
                            @php
                                $total_qty += $stock_in->qty;
                                $total_price += $stock_in->purchase_price * $stock_in->qty;
                            @endphp
                            <tr data-stock-in-id="{{ $stock_in->id }}">
                                <!-- <td>{{ $key+1 }}</td> -->
                                
                                <!-- <td class="editable product-code-edit" data-field="product_id" data-selected="{{ $stock_in->product_id }}">
                                    {{ $stock_in->product_id }}
                                </td> -->
                                <td class="product-name">{{ $stock_in->product->name }} ({{ $stock_in->product_id }})</td>
                                <td class="editable supplier-edit" data-field="supplier_id" data-selected="{{ $stock_in->supplier_id }}">
                                    {{ $stock_in->supplier->name }}
                                </td>
                                <!-- <td class="editable rack-edit" data-field="rack_id" data-selected="{{ $stock_in->rack_id }}">
                                    {{ ($stock_in->rack)?$stock_in->rack->name:'' }}
                                </td> -->
                                <td class="editable" data-field="qty">{{ $stock_in->qty }}</td>
                                <td class="editable" data-field="purchase_price">{{ $stock_in->purchase_price }}</td>
                                <td class="editable" data-field="sell_price">{{ $stock_in->sell_price }}</td>
                                <td>{{ $stock_in->purchase_price * $stock_in->qty }}</td>
                                <!-- <td class="editable" data-field="expiration_date">{{ $stock_in->expiration_date }}</td> -->
                                <!-- <td class="editable" data-field="alert_date">{{ $stock_in->alert_date }}</td> -->
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="3">Total</th>
                            <th>{{ $total_qty }}</th>
                            <th></th>
                            <th colspan="4">{{ $total_price }}</th>
                        </tr>
                        </tfoot>
                    </table>
                  </div>
                  </td>
                   
                  <td>
                    <a href="{{route('stockins.show',$batch->id)}}" class="btn btn-primary btn-sm">View/Edit</a>
                    <form action="{{ route('stockins.destroy', $batch->id) }}" method="POST" style="display: inline;">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                      </form>
                  </td>
                </tr>
                @endforeach
                </tbody>
                
              </table>
            </div>
            <span class="float-right">{{ $batchs->links() }}</span>
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
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "columnDefs": [
        // Disable ordering for the fourth column (index 3)
        { 'orderable': false, targets: [3] }
    ],
    "info": true,
    "autoWidth": false
});

  });
</script>
@endsection