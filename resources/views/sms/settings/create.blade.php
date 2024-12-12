@extends('layouts.app')
@section('title', 'Create Sms Setting')
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
        <h1>Create Sms Setting</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Create Sms Setting</li>
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
                    <h1 class="card-title">Create SMS Setting</h1>
                    <div class="card-tools"></div>
                </div>
                <div class="card-body">
                @include('partials.alerts')

                <form action="{{ route('sms-settings.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- Store -->
                        <div class="col-md-4 form-group">
                            <label for="store_id">Store <span class="text-danger">*</span></label>
                            <select id="store_id" name="store_id" class="form-control @error('store_id') is-invalid @enderror" required>
                                <option value="">Select Store</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('store_id')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- API Key -->
                        <div class="col-md-4 form-group">
                            <label for="api_key">API Key <span class="text-danger">*</span></label>
                            <input type="text" id="api_key" name="api_key" class="form-control @error('api_key') is-invalid @enderror" value="{{ old('api_key') }}" required>
                            @error('api_key')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- API URL -->
                        <div class="col-md-4 form-group">
                            <label for="api_url">API URL <span class="text-danger">*</span></label>
                            <input type="text" id="api_url" name="api_url" class="form-control @error('api_url') is-invalid @enderror" value="{{ old('api_url') }}" required>
                            @error('api_url')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Sender ID -->
                        <div class="col-md-4 form-group">
                            <label for="sender_id">Sender ID <span class="text-danger">*</span></label>
                            <input type="text" id="sender_id" name="sender_id" class="form-control @error('sender_id') is-invalid @enderror" value="{{ old('sender_id') }}" required>
                            @error('sender_id')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div class="col-md-4 form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" class="form-control @error('message') is-invalid @enderror" rows="3">{{ old('message') }}</textarea>
                            @error('message')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- User Email -->
                        <div class="col-md-4 form-group">
                            <label for="user_email">User Email <span class="text-danger">*</span></label>
                            <input type="email" id="user_email" name="user_email" class="form-control @error('user_email') is-invalid @enderror" value="{{ old('user_email') }}" required>
                            @error('user_email')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Balance -->
                        <div class="col-md-4 form-group">
                            <label for="balance">Balance <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="balance" name="balance" class="form-control @error('balance') is-invalid @enderror" value="{{ old('balance') }}" required>
                            @error('balance')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- SMS Rate -->
                        <div class="col-md-4 form-group">
                            <label for="sms_rate">SMS Rate <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="sms_rate" name="sms_rate" class="form-control @error('sms_rate') is-invalid @enderror" value="{{ old('sms_rate') }}" required>
                            @error('sms_rate')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- SMS Count -->
                        <div class="col-md-4 form-group">
                            <label for="sms_count">SMS Count <span class="text-danger">*</span></label>
                            <input type="number" id="sms_count" name="sms_count" class="form-control @error('sms_count') is-invalid @enderror" value="{{ old('sms_count') }}" required>
                            @error('sms_count')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Save</button>

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