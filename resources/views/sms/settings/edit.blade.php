
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
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Edit SMS Setting</h1>
                    <div class="card-tools"></div>
                </div>
                <div class="card-body">
                    @include('partials.alerts')

                    <form action="{{ route('sms-settings.update', $smsSetting->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="store_id">Store <span class="text-danger">*</span></label>
                                <select id="store_id" name="store_id" class="form-control @error('store_id') is-invalid @enderror">
                                    <option value="">Select Store</option>
                                    @foreach($stores as $store)
                                        <option value="{{ $store->id }}" {{ old('store_id', $smsSetting->store_id) == $store->id ? 'selected' : '' }}>
                                            {{ $store->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('store_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 form-group">
                                <label for="api_key">API Key <span class="text-danger">*</span></label>
                                <input type="text" id="api_key" name="api_key" class="form-control @error('api_key') is-invalid @enderror" value="{{ old('api_key', $smsSetting->api_key) }}" required>
                                @error('api_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 form-group">
                                <label for="api_url">API URL <span class="text-danger">*</span></label>
                                <input type="text" id="api_url" name="api_url" class="form-control @error('api_url') is-invalid @enderror" value="{{ old('api_url', $smsSetting->api_url) }}" required>
                                @error('api_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="sender_id">Sender ID <span class="text-danger">*</span></label>
                                <input type="text" id="sender_id" name="sender_id" class="form-control @error('sender_id') is-invalid @enderror" value="{{ old('sender_id', $smsSetting->sender_id) }}" required>
                                @error('sender_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 form-group">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" class="form-control @error('message') is-invalid @enderror" rows="3">{{ old('message', $smsSetting->message) }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 form-group">
                                <label for="user_email">User Email <span class="text-danger">*</span></label>
                                <input type="email" id="user_email" name="user_email" class="form-control @error('user_email') is-invalid @enderror" value="{{ old('user_email', $smsSetting->user_email) }}" required>
                                @error('user_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 form-group">
                                <label for="balance">Balance <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" id="balance" name="balance" class="form-control @error('balance') is-invalid @enderror" value="{{ old('balance', $smsSetting->balance) }}" required>
                                @error('balance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 form-group">
                                <label for="sms_rate">SMS Rate <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" id="sms_rate" name="sms_rate" class="form-control @error('sms_rate') is-invalid @enderror" value="{{ old('sms_rate', $smsSetting->sms_rate) }}" required>
                                @error('sms_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 form-group">
                                <label for="sms_count">SMS Count <span class="text-danger">*</span></label>
                                <input type="number" id="sms_count" name="sms_count" class="form-control @error('sms_count') is-invalid @enderror" value="{{ old('sms_count', $smsSetting->sms_count) }}" required>
                                @error('sms_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Update Settings</button>
                        </div>
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