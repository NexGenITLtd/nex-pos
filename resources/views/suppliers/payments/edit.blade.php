@extends('layouts.app')
@section('title', 'Edit Supplier Payment')
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
        <h1>Supplier Payment Edit</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Supplier Payment Edit</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <form action="{{route('supplier-payments.update',$supplier_payment->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Supplier Payment Edit</h3>
            <div class="card-tools">
              <a href="{{route('supplier-payments.index')}}" class="btn btn-success float-right"><i class="fa fa-angle-double-left"></i> Back</a>
            </div>
          </div>
          <div class="card-body">
            @include('partials.alerts')
            <div class="row">
              <div class="col-md-4 form-group">
                <label for="supplier_id">Supplier</label>
                <select id="supplier_id" name="supplier_id" class="form-control">
                  @foreach($suppliers as $supplier)
                    <option @if($supplier->id == $supplier_payment->supplier_id) selected @endif value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                  @endforeach
                </select>
                @error('supplier_id')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-4 form-group">
                <label for="bank_account_id">Account</label>
                <select id="bank_account_id" name="bank_account_id" class="form-control">
                  @foreach($bank_accounts as $bank_account)
                    <option @if($bank_account->id == $supplier_payment->bank_account_id) selected @endif value="{{ $bank_account->id }}">
                      {{ $bank_account->bank_name }} - {{ $bank_account->store->name }} - {{ $bank_account->bank_account_no }}
                    </option>
                  @endforeach
                </select>
                @error('bank_account_id')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-4 form-group">
                <label for="store_id">Store</label>
                <select id="store_id" name="store_id" class="form-control">
                  @foreach($stores as $store)
                    <option @if($supplier_payment->store_id == $store->id) selected @endif value="{{ $store->id }}">{{ $store->name }}</option>
                  @endforeach
                </select>
                @error('store_id')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-4 form-group">
                <label for="amount">Amount</label>
                <input type="number" id="amount" name="amount" class="form-control" value="{{ $supplier_payment->amount }}">
                @error('amount')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-4 form-group">
                <label for="paid_date">Paid date</label>
                <input type="date" id="paid_date" name="paid_date" class="form-control" value="{{ $supplier_payment->paid_date }}">
                @error('paid_date')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-4 form-group">
                <label for="note">Note</label>
                <input type="text" id="note" name="note" class="form-control" value="{{ $supplier_payment->note }}">
                @error('note')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <div class="row">
              <div class="col-12">
                <input type="submit" value="Update" class="btn btn-success float-right">
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