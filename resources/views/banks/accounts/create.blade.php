@extends('layouts.app')
@section('title', 'Create Account')
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
        <h1>Create Account</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Create Account</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <form action="{{ route('accounts.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Create Account</h3>
                    <div class="card-tools">
                        <a href="{{ route('accounts.index') }}" class="btn btn-success btn-sm float-right"><i class="fa fa-angle-double-left"></i> Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Bank Name -->
                        <div class="col-md-4 form-group">
                            <label for="bank_name">Bank name <span class="text-danger">*</span></label>
                            <input type="text" id="bank_name" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name') }}">
                            @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Account No -->
                        <div class="col-md-4 form-group">
                            <label for="account_no">Account no <span class="text-danger">*</span></label>
                            <input type="text" id="account_no" name="account_no" class="form-control @error('account_no') is-invalid @enderror" value="{{ old('account_no') }}">
                            @error('account_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Account Type -->
                        <div class="col-md-4 form-group">
                            <label for="account_type">Account type <span class="text-danger">*</span></label>
                            <select id="account_type" name="account_type" class="form-control @error('account_type') is-invalid @enderror">
                                <option value="cash" {{ old('account_type') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank" {{ old('account_type') == 'bank' ? 'selected' : '' }}>Bank</option>
                                <option value="mobile" {{ old('account_type') == 'mobile' ? 'selected' : '' }}>Mobile</option>
                            </select>
                            @error('account_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Initial Balance -->
                        <div class="col-md-4 form-group">
                            <label for="initial_balance">Initial balance <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" id="initial_balance" name="initial_balance" class="form-control @error('initial_balance') is-invalid @enderror" value="{{ old('initial_balance') }}">
                            @error('initial_balance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Store -->
                        <div class="col-md-4 form-group">
                            <label for="store_id">Store <span class="text-danger">*</span></label>
                            <select id="store_id" name="store_id" class="form-control @error('store_id') is-invalid @enderror">
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                                @endforeach
                            </select>
                            @error('store_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <div class="row">
                      <div class="col-12">
                          <input type="submit" value="Submit" class="btn btn-success float-right">
                      </div>
                  </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
    
</form>

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