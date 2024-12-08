
@extends('layouts.app')
@section('title', 'Edit Sms Setting')
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
        <h1>Edit Sms Setting</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Edit Sms Setting</li>
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
                    <h1 class="card-title">Edit SMS Setting</h1>
                    <div class="card-tools"></div>
                </div>
                <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('sms-settings.update', $smsSetting->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="store_id" class="form-label">Store ID</label>
                        <input type="number" class="form-control" id="store_id" name="store_id" value="{{ old('store_id', $smsSetting->store_id) }}">
                    </div>
                    <div class="mb-3">
                        <label for="api_key" class="form-label">API Key</label>
                        <input type="text" class="form-control" id="api_key" name="api_key" value="{{ old('api_key', $smsSetting->api_key) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="sender_id" class="form-label">Sender ID</label>
                        <input type="text" class="form-control" id="sender_id" name="sender_id" value="{{ old('sender_id', $smsSetting->sender_id) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="3">{{ old('message', $smsSetting->message) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="user_email" class="form-label">User Email</label>
                        <input type="email" class="form-control" id="user_email" name="user_email" value="{{ old('user_email', $smsSetting->user_email) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="balance" class="form-label">Balance</label>
                        <input type="number" step="0.01" class="form-control" id="balance" name="balance" value="{{ old('balance', $smsSetting->balance) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="sms_rate" class="form-label">SMS Rate</label>
                        <input type="number" step="0.01" class="form-control" id="sms_rate" name="sms_rate" value="{{ old('sms_rate', $smsSetting->sms_rate) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="sms_count" class="form-label">SMS Count</label>
                        <input type="number" class="form-control" id="sms_count" name="sms_count" value="{{ old('sms_count', $smsSetting->sms_count) }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
                </div>
            </div>
        </div>
    </div>
    
</div>
</section>
<!-- /.content -->
@endsection
@section('script')
<!-- vue -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.js"></script>
<!-- jQuery -->
<script src="{{asset('backend/')}}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('backend/')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="{{asset('backend/')}}/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('backend/')}}/dist/js/demo.js"></script>

@endsection