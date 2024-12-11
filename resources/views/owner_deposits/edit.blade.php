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
        <h1>Edit Owner Transaction</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Edit Owner Transaction</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  
    <div class="row">
      <div class="col-md-12">
        
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Edit Owner Transaction</h3>
            <div class="card-tools">
              <a href="{{ route('owner-deposits.index') }}" class="btn btn-success float-right"><i class="fa fa-angle-double-left"></i> Back</a>
            </div>
          </div>
          <div class="card-body">
            @include('partials.alerts')

            <form action="{{ route('owner-deposits.update', $ownerDeposit->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="store_id" class="form-label">Store</label>
                        <select name="store_id" id="store_id" class="form-control" required>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}" {{ $store->id == $ownerDeposit->store_id ? 'selected' : '' }}>
                                    {{ $store->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ $ownerDeposit->date }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="bank_account_id" class="form-label">Bank Account</label>
                        <select name="bank_account_id" id="bank_account_id" class="form-control" required>
                            @foreach ($bankAccounts as $account)
                                <option value="{{ $account->id }}" {{ $account->id == $ownerDeposit->bank_account_id ? 'selected' : '' }}>
                                    {{ $account->account_no }} - {{ $account->bank_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control" step="0.01" value="{{ $ownerDeposit->amount }}" required>
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="transaction_type" class="form-label">Transaction Type</label>
                        <select name="transaction_type" id="transaction_type" class="form-control" required>
                            <option value="deposit" {{ $ownerDeposit->transaction_type == 'deposit' ? 'selected' : '' }}>Deposit Owner</option>
                            <option value="withdrawal" {{ $ownerDeposit->transaction_type == 'withdrawal' ? 'selected' : '' }}>Withdrawal Get From Owner</option>
                        </select>
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="note" class="form-label">Note (Optional)</label>
                        <textarea name="note" id="note" class="form-control" rows="3">{{ $ownerDeposit->note }}</textarea>
                    </div>

                    <div class="col-md-12 form-group">
                        <button type="submit" class="btn btn-primary mt-3">Update Transaction</button>
                    </div>

                </div>
            </form>
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