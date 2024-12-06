@extends('layouts.app')
@section('title', 'Profit Report')
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
        <h1 class="m-0">Profit Report</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Profit Report</li>
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
                        <form action="{{ route('profit.report') }}" method="GET" class="form-inline mb-4" >
				        
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
				                <button type="submit" class="btn btn-primary">Filter</button>
				                <a href="{{ route('profit.report') }}" class="btn btn-secondary ml-2">Reset</a>
				                <a href="#" onclick="printDiv('app')" class="btn btn-primary ml-2">Print</a>
				            </div>
				        
				        
				    	</form>
                      </div>

                  </div>

                  <div class="card-body">
                      <div class="row">
                          <div class="col-md-12">

							<table class="table table-sm table-bordered" style="width: 100%; border-collapse: collapse; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);">
							    <!-- Sales Section -->
							    <thead style="background: linear-gradient(135deg, #4CAF50, #81C784); color: white; text-align: left; font-size: 18px;">
							        <tr>
							            <th colspan="2">
							                <i class="fas fa-chart-line"></i> Sales Information
							            </th>
							        </tr>
							    </thead>
							    <tbody>
							        <tr style="background-color: #f1f8e9; text-align: right; font-size: 15px; transition: background-color 0.3s;">
							            <td>Total Sales</td>
							            <td>{{ number_format($totalSales, 2) }}</td>
							        </tr>
							        <tr style="background-color: #ffffff; text-align: right; font-size: 15px; transition: background-color 0.3s;">
							            <td>Total Returns</td>
							            <td>{{ number_format($totalReturns, 2) }}</td>
							        </tr>
							        <tr style="background-color: #f1f8e9; text-align: right; font-size: 15px; transition: background-color 0.3s;">
							            <td>Net Sales</td>
							            <td>{{ number_format($netSales, 2) }}</td>
							        </tr>
							    </tbody>

							    <!-- Purchase Section -->
							    <thead style="background: linear-gradient(135deg, #2196F3, #64B5F6); color: white; text-align: left; font-size: 18px;">
							        <tr>
							            <th colspan="2">
							                <i class="fas fa-box"></i> Purchase Information
							            </th>
							        </tr>
							    </thead>
							    <tbody>
							        <tr style="background-color: #e3f2fd; text-align: right; font-size: 15px; transition: background-color 0.3s;">
							            <td>Total Purchase Cost</td>
							            <td>{{ number_format($totalPurchaseCost, 2) }}</td>
							        </tr>
							        <tr style="background-color: #ffffff; text-align: right; font-size: 15px; transition: background-color 0.3s;">
							            <td>Total Return Purchase Cost</td>
							            <td>{{ number_format($totalReturnPurchaseCost, 2) }}</td>
							        </tr>
							        <tr style="background-color: #e3f2fd; text-align: right; font-size: 15px; transition: background-color 0.3s;">
							            <td>Net Purchase Cost</td>
							            <td>{{ number_format($netPurchaseCost, 2) }}</td>
							        </tr>
							    </tbody>

							    <!-- Profit Section -->
							    <thead style="background: linear-gradient(135deg, #FF9800, #FFB74D); color: white; text-align: left; font-size: 18px;">
							        <tr>
							            <th colspan="2">
							                <i class="fas fa-dollar-sign"></i> Profit Information
							            </th>
							        </tr>
							    </thead>
							    <tbody>
							        <tr style="background-color: #fff3e0; text-align: right; font-size: 15px; transition: background-color 0.3s;">
							            <td>Gross Profit</td>
							            <td>{{ number_format($grossProfit, 2) }}</td>
							        </tr>
							    </tbody>

							    <tfoot>
							        <tr style="background-color: #f1f1f1; font-size: 14px; font-weight: bold;">
							            <td colspan="2" style="text-align: center; padding: 10px;">
							                <em>Summary Report</em>
							            </td>
							        </tr>
							    </tfoot>
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
