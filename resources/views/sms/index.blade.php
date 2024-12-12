@extends('layouts.app')
@section('title', 'Sms Histories')
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
        <h1>Sms Histories</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Sms Histories</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">SMS Histories</h1>
                    <div class="card-tools"><a href="{{ route('sms.create') }}" class="btn btn-primary mb-3">Add New SMS</a></div>
                </div>
                <div class="card-body">
                  @include('partials.alerts')

        <table class="table table-bordered" id="example2">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Message</th>
                    <th>Recipient</th>
                    <th>Parts</th>
                    <th>Cost</th>
                    <th>Response</th>
                    <th>Date Sent</th>
                </tr>
            </thead>
            <tbody>
                @forelse($smsHistories as $sms)
                    <tr>
                        <td>{{ ucfirst($sms->type) }}</td>
                        <td>{{ $sms->message }}</td>
                        <td>{{ $sms->recipient }}</td>
                        <td>{{ $sms->sms_parts }}</td>
                        <td>{{ $sms->sms_cost }} {{ $website_info->currency }}</td>
                        <td>{{ $sms->response }}</td>
                        <td>{{ $sms->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No SMS history found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination for SMS history -->
        {{ $smsHistories->links() }}
        </div>

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