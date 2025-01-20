
@extends('layouts.app')
@section('title', 'Products')
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
        <h1>Products</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Products</li>
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
            <h3 class="card-title">{{ $cardHeader }}</h3>
            <div class="card-tools no-print">
              
              <form method="GET" action="{{ route('products.index') }}" class="form-inline mb-3">
                <label for="store_id">Filter by Store:</label>
                <select name="store_id" id="store_id" class="form-control form-control-sm" style="width: 200px; display: inline-block;">
                    <option value="">-- All Store --</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
                
                <button type="submit" class="btn btn-sm btn-primary ml-2">Filter</button>
                <a href="#" onclick="printDiv('app')" class="btn btn-sm btn-secondary ml-2">Print</a>
                @can('create product')
                <a href="{{route('products.create')}}" class="btn btn-sm btn-success ml-2">Add New</a>
                <a href="{{route('product-stock-ins.direct')}}" class="btn btn-sm btn-warning ml-2">Add Stock In</a>
                @endcan
            </form>
            

            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body" >
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                      <th>#</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Purchase Value</th>
                        <th>Sale Value</th>
                        <th>Extra</th>
                        <th>Total Stock</th>
                        <th>Total Sales</th>
                        <th>Available Qty</th>
                        <th class="no-print">Status</th>
                        <th class="no-print">Action</th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($products as $key => $product)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                      <td>{{ $product->id }}</td>
                      <td>{{ $product->name }}</td>
                      <td>@can('show profit'){{ $product->latestStockIn->purchase_price }}@endcan</td>
                      <td>{{ $product->latestStockIn->sell_price }}</td>
                      <td>
                        @php
                        $extra = 0;
                        if ($product->latestStockIn->purchase_price > 0) {
                            $extra = (($product->latestStockIn->sell_price - $product->latestStockIn->purchase_price) / $product->latestStockIn->purchase_price) * 100;
                        }
                        @endphp
                        @can('show profit')
                        {{ number_format($extra, 2) }}%
                        @endcan
                      </td>
                      @php
                          // Get the store_id based on user role or request
                          $storeId = (Auth::user()->role == 'station') ? Auth::user()->store_id : request('store_id');
                      @endphp

                      <!-- Total stock (filter by store_id) -->
                      <td>
                          @if($storeId)
                              {{-- If store_id is set, calculate stock for that store --}}
                              {{ $product->stockIns->where('store_id', $storeId)->sum('qty') }}
                          @else
                              {{-- If no store_id, calculate total stock across all stores --}}
                              {{ $product->stockIns->sum('qty') }}
                          @endif
                      </td>

                      <!-- Total sales (filter by store_id) -->
                      <td>
                          @if($storeId)
                              {{-- If store_id is set, calculate sales for that store --}}
                              {{ $product->sellProducts->where('store_id', $storeId)->sum('qty') }}
                          @else
                              {{-- If no store_id, calculate total sales across all stores --}}
                              {{ $product->sellProducts->sum('qty') }}
                          @endif
                      </td>

                      <!-- Available quantity: total stock - total sales -->
                      <td>
                          @php
                              // Calculate the available quantity by subtracting sales from stock
                              $stock = $storeId 
                                  ? $product->stockIns->where('store_id', $storeId)->sum('qty') 
                                  : $product->stockIns->sum('qty');
                                  
                              $sales = $storeId 
                                  ? $product->sellProducts->where('store_id', $storeId)->sum('qty') 
                                  : $product->sellProducts->sum('qty');

                              $sales_return = $storeId 
                                  ? $product->returnSellProducts->where('store_id', $storeId)->sum('qty') 
                                  : $product->returnSellProducts->sum('qty');
                              
                              $availableQty = ($stock+$sales_return)- $sales;
                          @endphp
                          {{ $availableQty }}
                      </td>
                      <td class="no-print">
                        <button class="btn {{ $product->status === 'active' ? 'btn-success' : 'btn-warning' }}  toggle-status btn-sm" 
                                data-product-id="{{ $product->id }}" 
                                data-status="{{ $product->status }}">
                            {{ $product->status === 'active' ? 'Set Inactive' : 'Set Active' }}
                        </button>
                        
                      
                        <span class="d-none status-badge badge {{ $product->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($product->status) }}
                        </span>
                        
                        
                    </td>
                      <td class="no-print">
                        
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        
                          <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline;">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                          </form>
                        
                        <span class="">
                          <a href="{{ route('barcode', ['id' => $product->id, 'type' => 'multi-line']) }}?qty={{ $availableQty }}" 
                           class="btn btn-info btn-sm" 
                           title="multi line">
                            Multi Line Barcode
                        </a>

                        <a href="{{ route('barcode', ['id' => $product->id, 'type' => 'one-line']) }}?qty={{ $availableQty }}" 
                           class="btn btn-success btn-sm" 
                           title="one line">
                            One Line Barcode
                        </a>
                        </span>
                      </td>
                  </tr>
              @endforeach
              </tbody>

            </table>

            </div>
            <span class="float-right">{{ $products->links() }}</span>
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

<!-- DataTables Responsive -->
<script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.0/js/responsive.bootstrap4.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.2.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.0/js/buttons.bootstrap4.min.js"></script>

<!-- JSZip -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- PDFMake -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<!-- DataTables Buttons HTML5 Export, Print and ColVis -->
<script src="https://cdn.datatables.net/buttons/2.2.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.0/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.0/js/buttons.colVis.min.js"></script>

<!-- AdminLTE App -->
<script src="{{asset('backend/')}}/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('backend/')}}/dist/js/demo.js"></script>
<!-- page script -->

<script>
  $(function () {
    $("#example1").DataTable({
      
      "responsive": true,
      "lengthChange": true,
      "lengthMenu": [ [20, 50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 1000], [20, 50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 1000] ],
      "autoWidth": false,
      "buttons": [
        {
          extend: "copy",
          exportOptions: {
            columns: ':not(.no-print)'
          }
        },
        {
          extend: "csv",
          exportOptions: {
            columns: ':not(.no-print)'
          }
        },
        {
          extend: "excel",
          exportOptions: {
            columns: ':not(.no-print)'
          }
        },
        {
          extend: "pdf",
          exportOptions: {
            columns: ':not(.no-print)'
          }
        },
        {
          extend: "print",
          exportOptions: {
            columns: ':not(.no-print)'
          }
        },
        "colvis"
      ]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>

<!-- page script -->
<script>
var toggleStatusUrl = @json(route('products.toggle-status', ['product' => '__product_id__']));
document.addEventListener('DOMContentLoaded', function () {
    // Select all toggle-status buttons
    const toggleButtons = document.querySelectorAll('.toggle-status');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.dataset.productId; // Get the product ID
            const currentStatus = this.dataset.status; // Get the current status
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active'; // Determine the new status

            // Replace the placeholder with the actual product ID in the URL
            const url = toggleStatusUrl.replace('__product_id__', productId);

            // Send the toggle request to the server
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                body: JSON.stringify({ status: newStatus }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the button's data-status and text
                    this.dataset.status = newStatus;
                    this.textContent = newStatus === 'active' ? 'Set Inactive' : 'Set Active';

                    // Change the button color based on status
                    if (newStatus === 'active') {
                        $(this).removeClass('btn-warning').addClass('btn-success'); // Green for active
                    } else {
                        $(this).removeClass('btn-success').addClass('btn-warning '); // Red for inactive
                    }

                    // Check if the status badge exists and update it
                    const statusBadge = this.nextElementSibling;
                    if (statusBadge) {
                        statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                        statusBadge.className = `status-badge badge ${newStatus === 'active' ? 'badge-success d-none' : 'badge-danger d-none'}`;
                    }

                    toastr.success(`Product status updated to ${newStatus}`);
                } else {
                    toastr.warning('Failed to update product status');
                }
            })
            .catch(error => {
                console.error('Error updating status:', error);
                toastr.warning('An error occurred while updating the status');
            });
        });
    });
});


</script>
@endsection