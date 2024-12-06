@extends('layouts.app')
@section('title', 'Expense List')
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
        <h1>Expense</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Expense</li>
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
            <h3 class="card-title">Expense information</h3>
            <div class="card-tools">
              <a href="{{route('expenses.create')}}" class="btn btn-success float-right">Add New</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="table-responsive">
              <table id="example2" class="table table-sm table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>store</th>
                  <th>bank_account</th>
                  <th>amount</th>
                  <th>note</th>
                  <th>expense_date</th>
                  <th>action</th>
                </tr>
                </thead>
                <tbody>
                @php
                  $total = 0;
                @endphp
                @foreach($expenses as $key => $expense)
                @php
                  $total += $expense->amount;
                @endphp
                <tr>
                  <td>{{$expense->id}}</td>
                  <td>{{$expense->store->name}}<br>{{$expense->store->phone}}</td>
                  <td>{{$expense->bank_account->bank_name}}<br>{{$expense->bank_account->account_no}}</td>
                  <td>{{$expense->amount}}</td>
                  <td>{{$expense->expense_type}}</td>
                  <td>{{$expense->expense_date}}</td>
                  <td><span class=""><a href="{{route('expenses.edit',$expense->id)}}"class="btn btn-primary btn-sm">Edit</a>
                    <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm">
                          Delete
                      </button>
                  </form>
                  </span></td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th colspan="3">Total</th>
                  <th>{{ $total }}</th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
                </tfoot>
              </table>
            </div>
            <span class="float-right">{{ $expenses->links() }}</span>
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