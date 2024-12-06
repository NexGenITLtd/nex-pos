@extends('layouts.app')
@section('title', 'Add New Salary Pay')
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
        <h1>Salary Pay Add</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Salary Pay Add</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <form action="{{route('salarypays.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Salary Pay Add</h3>
            <div class="card-tools">
              <a href="{{route('salarypays.index')}}" class="btn btn-success float-right"><i class="fa fa-angle-double-left"></i> Back</a>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4 form-group">
                <label for="employee_id">Employee</label>
                <select id="employee_id" name="employee_id" class="form-control">
                  @foreach($employees as $key=>$employee)
                  <option value="{{$employee->id}}">{{$employee->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4 form-group">
                <label for="bank_account_id">Account</label>
                <select id="bank_account_id" name="bank_account_id" class="form-control">
                  @foreach($bank_accounts as $key=>$bank_account)
                  <option value="{{$bank_account->id}}">{{$bank_account->bank_name}} - {{$bank_account->store->name}} - {{$bank_account->bank_account_no}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4 form-group">
                <label for="amount">Amount</label>
                <input type="number" id="amount" name="amount" class="form-control" value="{{old('amount')}}">
              </div>
              <div class="col-md-4 form-group">
                <label for="pay_for_month">Pay for month</label>
                <input type="date" id="pay_for_month" name="pay_for_month" class="form-control" value="{{old('pay_for_month')}}">
              </div>
              <div class="col-md-4 form-group">
                <label for="paid_date">Paid date</label>
                <input type="date" id="paid_date" name="paid_date" class="form-control" value="{{old('paid_date')}}">
              </div>
              <div class="col-md-4 form-group">
                <label for="note">Note</label>
                <input type="text" id="note" name="note" class="form-control" value="{{old('note')}}">
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