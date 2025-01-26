@extends('layouts.app')
@section('title', 'Customers')
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
        <h1>Customers</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Customers</li>
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
            <h3 class="card-title">Customers</h3>
            <div class="card-tools no-print">
              
              <a href="{{route('customers.create')}}" class="btn btn-success">Add New</a>
              <a href="#" onclick="printDiv('app')" class="btn btn-primary ml-2 float-right">Print</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="table-responsive">
              <table id="example2" class="table table-bordered table-striped table-sm">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>name</th>
                  <th>phone</th>
                  <th>email</th>
                  <th>address</th>
                  <th>status</th>
                  <th>discount</th>
                  <th>total purchase</th>
                  <th>total paid</th>
                  <th>total due</th>
                  <th>image</th>
                  <th class="no-print">action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($customers as $key => $customer)
                <tr>
                  <td>{{$customer->id}}</td>
                  <td>{{$customer->name}}</td>
                  <td>{{$customer->phone}}</td>
                  <td>{{$customer->email}}</td>
                  <td>{{$customer->address}}</td>
                  <td>{{ ($customer->membership)?$customer->membership:'General' }}</td>
                  <td>{{$customer->discount}}</td>
                  <td>{{$customer->invoices->sum('total_bill')}}</td>
                  <td>{{$customer->invoices->sum('paid_amount')}}</td>
                  <td>{{$customer->invoices->sum('due_amount')}}</td>
                  
                  <td>
                    @if($customer->img && file_exists(public_path('images/customers/' . $customer->img)))
                      <img src="{{ asset('images/customers/' . $customer->img) }}" height="60" width="60">
                    @else
                      <img src="{{ asset('images/default.png') }}" height="60" width="60" alt="No Image">
                    @endif
                  </td>
                  <td class="no-print"><span><a href="{{route('customers.edit',$customer->id)}}"class="btn btn-primary btn-sm">Edit</a>
                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm" >
                          Delete
                      </button>
                  </form>
                  </span>
                </td>
                </tr>
                @endforeach
                </tbody>
                
              </table>
            </div>
            <span class="float-right">{{ $customers->links() }}</span>
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
      "info": true,
      "autoWidth": false,
    });
  });
</script>
@endsection