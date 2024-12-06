@extends('layouts.app')
@section('title', 'Salary Pay List')
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
        <h1>Salary Pay</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Salary Pay</li>
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
            <h3 class="card-title">Salary pay information</h3>
            <div class="card-tools no-print">
              <a href="{{route('salarypays.create')}}" class="btn btn-success">Add New</a>
              <a href="#" onclick="printDiv('app')" class="btn btn-primary ml-2">Print</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="table-responsive">
              <table id="example2" class="table table-sm table-bordered table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Store</th>
                    <th>Bank Account</th>
                    <th>Amount</th>
                    <th>Note</th>
                    <th>Pay for Month</th>
                    <th>Paid Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $total = 0;
                  @endphp
                  @foreach($salary_pays as $salary_pay) <!-- Sort in descending order by ID -->
                    @php
                      $total += $salary_pay->amount;
                    @endphp
                    <tr>
                      <td>{{ $salary_pay->id }}</td>
                      <td>@if($salary_pay->employe){{ $salary_pay->employee->id }} - {{ $salary_pay->employee->name }} - {{ $salary_pay->employee->phone }}@endif</td>
                      <td>{{ $salary_pay->store->name }}</td>
                      <td>{{ $salary_pay->bank_account->id }} - {{ $salary_pay->bank_account->bank_name }}<br>{{ $salary_pay->bank_account->account_no }}</td>
                      <td>{{ $salary_pay->amount }}</td>
                      <td>{{ $salary_pay->note }}</td>
                      <td>{{ $salary_pay->pay_for_month }}</td>
                      <td>{{ $salary_pay->paid_date }}</td>
                      <td>
                        <a href="{{ route('salarypays.edit', $salary_pay->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <a href="{{ route('salarypays.destroy', $salary_pay->id) }}" class="btn btn-danger btn-sm">Delete</a>

                      </td>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="4">Total</th>
                    <th colspan="6">{{ $total }}</th>
                  </tr>
                </tfoot>

              </table>
            </div>
            <span class="float-right">{{ $salary_pays->links() }}</span>
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