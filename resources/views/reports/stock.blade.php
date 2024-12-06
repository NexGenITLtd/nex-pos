@extends('layouts.app')
@section('title', 'Report')
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
        <h1 class="m-0">Report</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Report</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

@php
$stores = App\Models\Store::get();
@endphp
<!-- Main content -->
<section class="content">
    <div class="container-fluid" id="stock">
      <!-- Info boxes -->
      <div class="row">
          <div class="col-md-12">
              <div class="card">
                  <div class="card-header">
                      <h5 class="card-title">{{ $cardHeader }}</h5>
                      <div class="card-tools no-print">
                        <form method="GET" action="{{ route('report.stock') }}" class="form-inline d-flex align-items-center">
                            
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="store_id" class="sr-only">Filter by Store:</label>
                                <select name="store_id" id="store_id" class="form-control mr-2">
                                    <option value="">-- All Stores --</option>
                                    @foreach($stores as $store)
                                        <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary mb-2">Apply Filter</button>
                            <a href="#" class="btn btn-primary ml-2 mb-2" onclick="printDiv('stock')">Print</a>
                        </form>
                    </div>

                  </div>

                  <div class="card-body">
                      <div class="row">
                          <div class="col-md-12">
                              <div class="card">
                                  
                                  <div class="card-body">
                                    <span class="table-responsive">
                                      <h4>Stock Report Summary</h4>
                                    
                                      <table class="table table-sm table-bordered">
                                          <thead>
                                              <tr>
                                                  <th>Total Stock In Quantity</th>
                                                  <th>Total Available Stock Quantity</th>
                                                  <th>Total Available Stock Purchase Price</th>
                                                  <th>Total Available Stock Sell Price</th>
                                                  <th>Total Sold Quantity</th>
                                                  <th>Total Available Stock Profit</th> <!-- Added Profit Column -->
                                              </tr>
                                          </thead>
                                          <tbody>
                                              <tr>
                                                  <td>{{ number_format($total_stock_in_qty) }}</td>
                                                  <td>{{ number_format($total_available_stock_qty) }}</td>
                                                  <td>{{ number_format($total_available_stock_purchase_price, 2) }}</td>
                                                  <td>{{ number_format($total_available_stock_sell_price, 2) }}</td>
                                                  <td>{{ number_format($total_sold_qty) }}</td>
                                                  <td>{{ number_format($total_available_stock_profit, 2) }}</td> <!-- Display Profit -->
                                              </tr>
                                          </tbody>
                                      </table>

                                      <!-- Low Stock Alerts -->
                                      @if(count($low_stock_alerts) > 0)
                                          <h4>Low Stock Alerts</h4>
                                          <table class="table table-striped">
                                              <thead>
                                                  <tr>
                                                      <th>Product Name</th>
                                                      <th>Available Stock</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                                  @foreach($low_stock_alerts as $alert)
                                                      <tr>
                                                          <td>{{ $alert['product_name'] }}</td>
                                                          <td>{{ $alert['available_stock'] }}</td>
                                                      </tr>
                                                  @endforeach
                                              </tbody>
                                          </table>
                                      @else
                                          <p>No low stock alerts.</p>
                                      @endif

                                      <!-- Expiring Soon Alerts -->
                                      @if(count($expiring_soon_alerts) > 0)
                                          <h4>Expiring Soon Alerts</h4>
                                          <table class="table table-striped">
                                              <thead>
                                                  <tr>
                                                      <th>Product Name</th>
                                                      <th>Expiration Date</th>
                                                      <th>Quantity</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                                  @foreach($expiring_soon_alerts as $alert)
                                                      <tr>
                                                          <td>{{ $alert['product_name'] }} ({{ $alert['product_id'] }})</td>
                                                          <td>{{ $alert['expiration_date'] }}</td>
                                                          <td>{{ $alert['qty'] }}</td>
                                                      </tr>
                                                  @endforeach
                                              </tbody>
                                          </table>
                                      @else
                                          <p>No expiring soon stock alerts.</p>
                                      @endif
                                      </span>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <!-- /.col -->
      </div>
      <!-- /.row -->
    </div><!--/. container-fluid -->
</section>
<!-- /.content -->

@section('script')
<!-- jQuery -->
<script src="{{ asset('backend/') }}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{ asset('backend/') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="{{ asset('backend/') }}/dist/js/adminlte.js"></script>
<script type="text/javascript">
  function printDiv(divId) {
    var printContents = document.getElementById(divId).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
  }
</script>
@endsection
@endsection
