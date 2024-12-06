@extends('layouts.app')
@section('title', 'Edit Asset')
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
        <h1>Edit Asset</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Edit Asset</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <form action="{{route('assets.update',$asset->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    @METHOD('PUT')
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Edit Asset</h3>
            <div class="card-tools">
              <a href="{{ route('assets.index') }}" class="btn btn-success float-right"><i class="fa fa-angle-double-left"></i> Back</a>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4 form-group">
                <label for="store_id">Store</label>
                <select id="store_id" name="store_id" class="form-control">
                  @foreach($stores as $key=>$store)
                  <option @if($store->id==$asset->store_id) selected @endif value="{{$store->id}}">{{$store->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4 form-group">
                <label for="name">Asset name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{$asset->name}}">
              </div>
              <div class="col-md-4 form-group">
                <label for="unit_price">Unit price</label>
                <input type="number" id="unit_price" name="unit_price" class="form-control" value="{{$asset->unit_price}}" v-model="unit_price" @keyup="cal()">
              </div>
              <div class="col-md-4 form-group">
                <label for="qty">Qty</label>
                <input type="number" id="qty" name="qty" class="form-control" value="{{$asset->qty}}" v-model="qty" @keyup="cal()">
              </div>
              <div class="col-md-4 form-group">
                <label for="amount">Total amount</label>
                <input type="number" id="amount" name="amount" class="form-control" value="{{$asset->amount}}" v-model="amount" @keyup="cal()">
              </div>
              <div class="col-md-4 form-group">
                <label for="purchase_date">Purchase date</label>
                <input type="date" id="purchase_date" name="purchase_date" class="form-control" value="{{$asset->purchase_date}}">
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
<script>
  var app = new Vue({
    el: "#app",
    data: {
      unit_price: "{{$asset->unit_price}}",
      qty: "{{$asset->qty}}",
      amount: "{{$asset->amount}}",
    },
    methods: {
      cal(){
        this.amount = this.unit_price*this.qty;
      }
    }
  });
</script> 
@endsection