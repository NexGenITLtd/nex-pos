@extends('layouts.app')
@section('title', 'Return Product Sell List')
@section('link')
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('backend/')}}/plugins/fontawesome-free/css/all.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="{{asset('backend/')}}/ionicons/2.0.1/css/ionicons.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('backend/')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('backend/')}}/dist/css/adminlte.min.css">
<!-- Google Font: Source Sans Pro -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
@endsection
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Return Product Sell List</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Return Product Sell List</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">{{ $cardHeader }}</h5>
            <div class="card-tools">
            	<form method="GET" action="{{ route('return-sell-products.index') }}" class="form-inline">
                <div class="form-group mx-sm-3 mb-2">
                    <label for="date_filter" class="sr-only">Filter by Date:</label>
                    <select name="date_filter" id="date_filter" class="form-control mr-2">
                        <option value="">-- Select Filter --</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="previous_day" {{ request('date_filter') == 'previous_day' ? 'selected' : '' }}>Previous Day</option>
                        <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="this_year" {{ request('date_filter') == 'this_year' ? 'selected' : '' }}>This Year</option>
                        <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>

                <div class="form-group mx-sm-3 mb-2" id="customDateInputs" style="{{ request('date_filter') == 'custom' ? '' : 'display: none;' }}">
                    <label for="start_date" class="sr-only">Start Date:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control mr-2" value="{{ request('start_date') }}" placeholder="Start Date">
                    
                    <label for="end_date" class="sr-only">End Date:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control mr-2" value="{{ request('end_date') }}" placeholder="End Date">
                </div>

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
                <a href="{{ route('return-sell-products.pdf', request()->all()) }}" class="btn btn-success ml-2 mb-2">Download PDF</a>
                </form>

                <script>
                    document.getElementById('date_filter').addEventListener('change', function () {
                        var customDateInputs = document.getElementById('customDateInputs');
                        if (this.value === 'custom') {
                            customDateInputs.style.display = 'block';
                        } else {
                            customDateInputs.style.display = 'none';
                        }
                    });
                </script>
                
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Invoice ID</th>
                            <th>Product (Code - Name)</th>
                            <th>Sell Price</th>
                            <th>Quantity</th>
                            <th>Sub Total</th>
                            <th>VAT</th>
                            <th>Discount (%)</th>
                            <th>Final Total</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Initialize variables for totals
                            $totalSellPrice = 0;
                            $totalQuantity = 0;
                            $totalVAT = 0;
                            $totalDiscount = 0;
                            $totalSubTotal = 0;
                            $totalFinalTotal = 0; // Final total after VAT and discount
                        @endphp

                        @forelse ($returnSellProducts as $product)
                            @php
                                // Calculate sub total (sell price * quantity)
                                $subTotal = $product->sell_price * $product->qty;

                                // Calculate VAT (assuming VAT is a percentage of the subTotal)
                                $vatAmount = ($subTotal * $product->vat) / 100;

                                // Apply VAT to the sub total
                                $subTotalWithVAT = $subTotal + $vatAmount;

                                // Calculate Discount as a percentage of subTotalWithVAT
                                $discountAmount = ($subTotalWithVAT * $product->discount) / 100;

                                // Final total after VAT and discount
                                $finalTotal = $subTotalWithVAT - $discountAmount;

                                // Add values to totals
                                $totalSellPrice += $product->sell_price;
                                $totalQuantity += $product->qty;
                                $totalVAT += $vatAmount;
                                $totalDiscount += $discountAmount;
                                $totalSubTotal += $subTotal;
                                $totalFinalTotal += $finalTotal;
                            @endphp

                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->invoice_id }}</td>
                                <td>{{ $product->product_id }} - {{ $product->product_name ?? 'N/A' }}</td>
                                <td>{{ number_format($product->sell_price, 2) }}</td>
                                <td>{{ $product->qty }}</td>
                                <td>{{ number_format($subTotal, 2) }}</td>
                                <td>{{ number_format($vatAmount, 2) }}</td>
                                <td>{{ number_format($product->discount, 2) }}%</td>
                                <td>{{ number_format($finalTotal, 2) }}</td>
                                <td>{{ $product->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No products found</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Total</th>
                            <th>{{ number_format($totalSellPrice, 2) }}</th>
                            <th>{{ $totalQuantity }}</th>
                            <th>{{ number_format($totalSubTotal, 2) }}</th>
                            <th>{{ number_format($totalVAT, 2) }}</th>
                            <th>{{ number_format($totalDiscount, 2) }}</th>
                            <th>{{ number_format($totalFinalTotal, 2) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>

            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
@section('script')
<!-- jQuery -->
<script src="{{asset('backend/')}}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('backend/')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="{{asset('backend/')}}/plugins/datatables/jquery.dataTables.js"></script>
<script src="{{asset('backend/')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- AdminLTE App -->
<script src="{{asset('backend/')}}/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('backend/')}}/dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": false,
      "autoWidth": false,
    });
  });
</script>
@endsection