
@extends('layouts.app')
@section('title', 'Supplier Payment Alert List')
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
        <h1>Supplier Payment Alert</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Supplier Payment Alert</li>
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
            <h3 class="card-title">Supplier payment alert information</h3>
            <div class="card-tools">
              <a href="{{route('supplier-payment-alerts.create')}}" class="btn btn-success float-right">Add New</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="table-responsive">
              <table id="example2" class="table table-sm table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Supplier</th>
                        <th>Store</th> <!-- Added Store Column -->
                        <th>Amount</th>
                        <th>Pay Date</th>
                        <th>Notice Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($supplier_payment_alerts as $key => $supplier_payment_alert)
                    <tr>
                        <td>{{ $supplier_payment_alert->id }}</td>
                        <td>
                            {{ $supplier_payment_alert->supplier->name }}<br>
                            {{ $supplier_payment_alert->supplier->phone }}
                        </td>
                        <td>{{ $supplier_payment_alert->store->name }}</td> <!-- Display Store Name -->
                        <td>{{ number_format($supplier_payment_alert->amount, 2) }}</td> <!-- Format Amount -->
                        <td>{{ \Carbon\Carbon::parse($supplier_payment_alert->pay_date)->format('d M, Y') }}</td> <!-- Format Pay Date -->
                        <td>{{ \Carbon\Carbon::parse($supplier_payment_alert->notice_date)->format('d M, Y') }}</td> <!-- Format Notice Date -->
                        <td>
                            <a href="{{ route('supplier-payment-alerts.edit', $supplier_payment_alert->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            
                            <form action="{{ route('supplier-payment-alerts.destroy', $supplier_payment_alert->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this item?');">
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
            <span class="float-right">{{ $supplier_payment_alerts->links() }}</span>
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