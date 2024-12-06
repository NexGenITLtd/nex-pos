@extends('layouts.app')

@section('title', 'Change Password')
@section('link')
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('backend/')}}/plugins/fontawesome-free/css/all.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('backend/')}}/dist/css/adminlte.min.css">
@endsection
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Change Password</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Change Password</li>
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
                    Change Password
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('change-password.update') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" name="current_password" id="current_password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="new_password_confirmation">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
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
<script src="{{ asset('backend/') }}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{ asset('backend/') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="{{ asset('backend/') }}/dist/js/adminlte.js"></script>
@endsection
