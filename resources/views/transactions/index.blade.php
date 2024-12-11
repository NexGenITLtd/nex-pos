@extends('layouts.app')
@section('title', 'Transactions')
@section('link')
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="{{ asset('backend/') }}/plugins/fontawesome-free/css/all.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('backend/') }}/dist/css/adminlte.min.css">
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Transactions</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Transactions</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Transactions</h1>
            <div class="card-tools">
                <a href="{{ route('transactions.index') }}" class="btn btn-primary"><i class="fas fa-reload"></i> Refresh</a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form action="{{ route('transactions.index') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <label for="store_id" class="form-label">Store</label>
                        <select name="store_id" id="store_id" class="form-control">
                            <option value="">All Stores</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                    {{ $store->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="bank_account_id" class="form-label">Bank Account</label>
                        <select name="bank_account_id" id="bank_account_id" class="form-control">
                            <option value="">All Accounts</option>
                            @foreach($bankAccounts as $account)
                                <option value="{{ $account->id }}" {{ request('bank_account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->account_no }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>

            <!-- Transactions Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Store</th>
                            <th>Bank Account</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                            <th>Created By</th>
                            <th>Note</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $transaction->store->name ?? 'N/A' }}</td>
                                <td>{{ $transaction->bankAccount->bank_name ?? 'N/A' }}-{{ $transaction->bankAccount->account_no ?? 'N/A' }}</td>
                                <td class="text-success">{{ $transaction->debit > 0 ? number_format($transaction->debit, 2) : '-' }}</td>
                                <td class="text-danger">{{ $transaction->credit > 0 ? number_format($transaction->credit, 2) : '-' }}</td>
                                <td>{{ number_format($transaction->balance, 2) }}</td>
                                <td>{{ $transaction->creator->name ?? 'N/A' }}</td>
                                <td>{{ $transaction->note }}</td>
                                <td>{{ $transaction->created_at->format('d-m-Y H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
    
</section>
<!-- /.content -->

@section('script')
<!-- jQuery -->
<script src="{{ asset('backend/') }}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{ asset('backend/') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="{{ asset('backend/') }}/dist/js/adminlte.js"></script>
@endsection
@endsection
