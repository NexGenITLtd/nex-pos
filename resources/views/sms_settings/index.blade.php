@extends('layouts.app')
@section('title', 'Sms Setting List')
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
        <h1>Sms Setting</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Sms Setting</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
<div class="container">
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">SMS Settings</h1>
                    <div class="card-tools"><a href="{{ route('sms-settings.create') }}" class="btn btn-primary mb-3">Add New SMS Setting</a></div>
                </div>
                <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                

                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Store</th>
                            <th>API Key</th>
                            <!-- <th>Sender ID</th> -->
                            <!-- <th>User Email</th> -->
                            <th>Balance</th>
                            <th>SMS Rate</th>
                            <th>SMS Count</th>
                            <!-- <th>Actions</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($smsSettings as $smsSetting)
                            <tr>
                                <td>{{ $smsSetting->id }}</td>
                                <td>{{ $smsSetting->store->name??'No Store Setup' }}</td>
                                <td>{{ $smsSetting->api_key }}</td>
                                <!-- <td>{{ $smsSetting->sender_id }}</td> -->
                                <!-- <td>{{ $smsSetting->user_email }}</td> -->
                                <td>{{ $smsSetting->balance }}</td>
                                <td>{{ $smsSetting->sms_rate }}</td>
                                <td>{{ $smsSetting->sms_count }}</td>
                                <td>
                                    <!-- <a href="{{ route('sms-settings.edit', $smsSetting->id) }}" class="btn btn-warning btn-sm">Edit</a> -->
                                    {{-- Add delete functionality if necessary --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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