@extends('layouts.app')
@section('title', 'Supplier Report')
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
        <h1 class="m-0">Supplier Report</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Supplier Report</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid" id="report">
    	<div class="row">
          <div class="col-md-12">
              <div class="card">
                  <div class="card-header">
                      <h5 class="card-title">{{ ($cardHeader)?$cardHeader:'' }}</h5>
                      <div class="card-tools no-print">
                        <form action="{{ route('supplier.report') }}" method="GET" class="form-inline mb-4" >
				        
				            <div class="form-group">
				                <label for="start_date">Start Date</label>
				                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="form-control">
				            </div>
				            <div class="form-group">
				                <label for="end_date">End Date</label>
				                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="form-control">
				            </div>
				            <div class="form-group">
				                <label for="store_id">Store</label>
				                <select name="store_id" id="store_id" class="form-control">
				                    <option value="">All Stores</option>
				                    @foreach($stores as $store)
				                        <option value="{{ $store->id }}" {{ $storeId == $store->id ? 'selected' : '' }}>
				                            {{ $store->name }}
				                        </option>
				                    @endforeach
				                </select>
				            </div>
				            <div class="form-group">
				                <label for="supplier_id">Supplier</label>
				                <select name="supplier_id" id="supplier_id" class="form-control">
				                    <option value="">All Suppliers</option>
				                    @foreach($allSuppliers as $supplier)
				                        <option value="{{ $supplier->id }}" {{ $supplierId == $supplier->id ? 'selected' : '' }}>
				                            {{ $supplier->name }}
				                        </option>
				                    @endforeach
				                </select>
				            </div>
				            <div class="form-group">
				                <button type="submit" class="btn btn-primary">Filter</button>
				                <a href="{{ route('supplier.report') }}" class="btn btn-secondary ml-2">Reset</a>
				                <a href="#" onclick="printDiv('app')" class="btn btn-primary ml-2">Print</a>
				            </div>
				        
				        
				    	</form>
                      </div>

                  </div>

                  <div class="card-body">
                      <div class="row">
                          <div class="col-md-12">
                            <table class="table table-sm table-bordered">
				                <thead>
				                    <tr>
				                        <th>#</th>
				                        <th>Supplier Name</th>
				                        <th>Total Purchase</th>
				                        <th>Total Payment</th>
				                        <th>Due</th>
				                        <th>Advance Payment</th>
				                    </tr>
				                </thead>
				                <tbody>
				                    @forelse ($suppliers as $index => $supplier)
				                        <tr>
				                            <td>{{ $index + 1 }}</td>
				                            <td>{{ $supplier['name'] }}</td>
				                            <td>{{ number_format($supplier['total_purchase'], 2) }} {{ $website_info->currency }}</td>
				                            <td>{{ number_format($supplier['total_payment'], 2) }} {{ $website_info->currency }}</td>
				                            <td>{{ number_format($supplier['due'], 2) }} {{ $website_info->currency }}</td>
				                            <td>{{ number_format($supplier['advance'], 2) }} {{ $website_info->currency }}</td>
				                        </tr>
				                    @empty
				                        <tr>
				                            <td colspan="6" class="text-center">No records found</td>
				                        </tr>
				                    @endforelse
				                </tbody>
				            </table>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <!-- /.col -->
      </div>
      <!-- /.row -->
    
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
