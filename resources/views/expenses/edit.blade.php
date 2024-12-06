@extends('layouts.app')
@section('title', 'Edit Expense')
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
        <h1>Expense Edit</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Expense Edit</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <form action="{{route('expenses.update',$expense->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
      <div class="col-md-12">
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
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Expense information edit</h3>
            <div class="card-tools">
              <a href="{{route('expenses.index')}}" class="btn btn-success float-right"><i class="fa fa-angle-double-left"></i> Back</a>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4 form-group">
                <label for="store_id">Store</label>
                <select id="store_id" name="store_id" class="form-control">
                  @foreach($stores as $key=>$store)
                  <option @if($store->id==$expense->store_id) selected @endif value="{{$store->id}}">{{$store->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4 form-group">
                <label for="bank_account_id">Account</label>
                <select id="bank_account_id" name="bank_account_id" class="form-control">
                  @foreach($bank_accounts as $key=>$bank_account)
                  <option @if($bank_account->id==$expense->bank_account_id) selected @endif value="{{$bank_account->id}}">{{$bank_account->bank_name}} - {{$bank_account->store->name}} - {{$bank_account->bank_account_no}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4 form-group">
                <label for="amount">Amount</label>
                <input type="number" id="amount" name="amount" class="form-control" value="{{$expense->amount}}">
              </div>
              <div class="col-md-4 form-group">
                <label for="expense_date">Expense date</label>
                <input type="date" id="expense_date" name="expense_date" class="form-control" value="{{$expense->expense_date}}">
              </div>
              <div class="col-md-4 form-group">
                <label for="expense_type">Note</label>
                <input type="text" id="expense_type" name="expense_type" class="form-control" value="{{$expense->expense_type}}">
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
          form: {
          },
          rows: [
                {}
          ]
      },
      methods: {
        
      }
  });
</script> 
@endsection