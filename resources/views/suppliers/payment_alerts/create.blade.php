@extends('layouts.app')
@section('title', 'Add New Supplier Payment Alert')
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
        <h1>Supplier Payment Alert Add</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Supplier Payment Alert Add</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <form action="{{ route('supplier-payment-alerts.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Supplier Payment Alert Information</h3>
            <div class="card-tools">
              <a href="{{ route('supplier-payment-alerts.index') }}" class="btn btn-success float-right">
                <i class="fa fa-angle-double-left"></i> Back
              </a>
            </div>
          </div>
          <div class="card-body">
            @include('partials.alerts')
            <div class="row">
              <!-- Supplier -->
              <div class="col-md-4 form-group">
                <label for="supplier_id">Supplier</label>
                <select id="supplier_id" name="supplier_id" class="form-control">
                  @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                      {{ $supplier->name }}
                    </option>
                  @endforeach
                </select>
                @error('supplier_id')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>

              <!-- Store -->
              <div class="col-md-4 form-group">
                <label for="store_id">Store</label>
                <select id="store_id" name="store_id" class="form-control">
                  @foreach($stores as $store)
                    <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                      {{ $store->name }}
                    </option>
                  @endforeach
                </select>
                @error('store_id')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>

              <!-- Amount -->
              <div class="col-md-4 form-group">
                <label for="amount">Amount</label>
                <input type="number" id="amount" name="amount" class="form-control" value="{{ old('amount') }}" min="1">
                @error('amount')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>

              <!-- Pay Date -->
              <div class="col-md-4 form-group">
                <label for="pay_date">Pay Date</label>
                <input type="date" id="pay_date" name="pay_date" class="form-control" value="{{ old('pay_date') }}">
                @error('pay_date')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>

              <!-- Notice Date -->
              <div class="col-md-4 form-group">
                <label for="notice_date">Notice Date</label>
                <input type="date" id="notice_date" name="notice_date" class="form-control" value="{{ old('notice_date') }}">
                @error('notice_date')
                  <div class="text-danger">{{ $message }}</div>
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
          <!-- /.card-footer -->
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
