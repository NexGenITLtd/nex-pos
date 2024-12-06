@extends('layouts.app')
@section('title', 'Stock Details')
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
        <h1>Stock Details</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item ">Stock</li>
          <li class="breadcrumb-item active">Details</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
@php
    $batch_id = request()->route('id'); // 'id' corresponds to the batch_id in the URL
@endphp

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Batch #{{ $batch->id }} Stock Details</h3>
            <div class="card-tools no-print">
              <a href="{{route('stockins.index')}}" class="btn btn-success btn-sm mr-2"><i class="fa fa-angle-double-left"></i>  Back</a>
              <button id="add-new-row" class="btn btn-primary btn-sm mr-2">Add New Row</button>
              <button onclick="printDiv('app')" class="btn btn-info btn-sm mr-2">Print</button>
              <form action="{{ route('stockins.destroy', $batch->id) }}" method="POST" style="display: inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Delete All</button>
              </form>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="table-responsive">
              <table id="" class="table">
                <thead>
                <tr>
                  <th>invoice_no</th>
                  <th>store</th>
                  <th>stock_in_date</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>{{$batch->invoice_no}}</td>
                  <td>{{$batch->store->name}}</td>
                  <td>{{$batch->stock_date}}</td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-body">

            <div class="table-responsive">
              <table id="stock-table" class="table table-sm">
                  <thead>
                  <tr>
                      <th>#</th>
                      <th>Product Code</th>
                      <th>Product</th>
                      <th>Supplier</th>
                      <th>Rack</th>
                      <th>Qty</th>
                      <th>Purchase Price</th>
                      <th>Sell Price</th>
                      <th>Total Price</th>
                      <th>Expiration Date</th>
                      <th>Alert Date</th>
                      <th class="no-print">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  @php
                      $total_qty = 0;
                      $total_price = 0;
                  @endphp
                  @foreach($batch->stock_ins as $key => $stock_in)
                      @php
                          $total_qty += $stock_in->qty;
                          $total_price += $stock_in->purchase_price * $stock_in->qty;
                      @endphp
                      <tr data-stock-in-id="{{ $stock_in->id }}">
                          <td>{{ $key+1 }}</td>
                          
                          <td class="editable product-code-edit" data-field="product_id" data-selected="{{ $stock_in->product_id }}">
                              {{ $stock_in->product_id }}
                          </td>
                          <td class="product-name">{{ $stock_in->product->name }}</td>
                          <td class="editable supplier-edit" data-field="supplier_id" data-selected="{{ $stock_in->supplier_id }}">
                              {{ $stock_in->supplier->name }}
                          </td>
                          <td class="editable rack-edit" data-field="rack_id" data-selected="{{ $stock_in->rack_id }}">
                              {{ ($stock_in->rack)?$stock_in->rack->name:'' }}
                          </td>
                          <td class="editable" data-field="qty">{{ $stock_in->qty }}</td>
                          <td class="editable" data-field="purchase_price">{{ $stock_in->purchase_price }}</td>
                          <td class="editable" data-field="sell_price">{{ $stock_in->sell_price }}</td>
                          <td>{{ $stock_in->purchase_price * $stock_in->qty }}</td>
                          <td class="editable" data-field="expiration_date">{{ $stock_in->expiration_date }}</td>
                          <td class="editable" data-field="alert_date">{{ $stock_in->alert_date }}</td>
                          <td class="no-print"><button class="edit-btn btn btn-primary btn-sm no-print">Edit</button><button class="delete-btn btn btn-warning btn-sm no-print">Delete</button></td>
                      </tr>
                  @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                      <th colspan="5">Total</th>
                      <th>{{ $total_qty }}</th>
                      <th></th>
                      <th></th>
                      <th colspan="4">{{ $total_price }}</th>
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
  $(document).ready(function () {
    const csrfToken = '{{ csrf_token() }}'; // Store CSRF token for reuse

    // Edit Button Click Event
    $(document).on('click', '.edit-btn', function () {
        const $row = $(this).closest('tr');
        const $btn = $(this);

        if ($btn.text() === 'Edit') {
            // Switch to edit mode
            $row.find('.editable').each(function () {
                const $td = $(this);
                const value = $td.text();
                const field = $td.data('field');

                if (['product_id', 'supplier_id', 'rack_id'].includes(field)) {
                    let dropdown = '<select class="form-control" name="' + field + '">';

                    // Dynamically generate dropdown options
                    const options = field === 'product_id' ? {!! json_encode($products) !!} 
                                 : field === 'supplier_id' ? {!! json_encode($suppliers) !!} 
                                 : {!! json_encode($racks) !!};

                    options.forEach(option => {
                        dropdown += `<option value="${option.id}"${option.id == $td.data('selected') ? ' selected' : ''}>${option.name} (${option.id})</option>`;
                    });

                    dropdown += '</select>';
                    $td.html(dropdown);
                } else {
                    $td.html('<input type="text" class="form-control" name="' + field + '" value="' + value + '">');
                }
            });

            $btn.text('Save');
        } else {
            // Save mode
            const stockInId = $row.data('stock-in-id');
            const updateData = {};

            let hasError = false;

            $row.find('.editable').each(function () {
                const $td = $(this);
                const field = $td.data('field');
                let inputVal;

                if ($td.find('select').length) {
                    inputVal = $td.find('select').val();
                } else {
                    inputVal = $td.find('input').val();
                }

                if (!inputVal) {
                    toastr.error(field + ' cannot be empty');
                    hasError = true;
                    return false;
                }

                updateData[field] = inputVal;
                $td.html(inputVal); // Replace with plain text
            });

            if (hasError) return;

            // AJAX request to update the stock
            $.ajax({
                url: "{{ route('stockins.updateStock') }}",
                method: 'POST',
                data: { id: stockInId, fields: updateData, _token: csrfToken },
                success: function () {
                    toastr.success('Stock updated successfully!');
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    toastr.error('Failed to update stock.');
                }
            });

            $btn.text('Edit');
        }
    });

    // Delete Button Click Event
    $(document).on('click', '.delete-btn', function () {
        const $row = $(this).closest('tr');
        const stockInId = $row.data('stock-in-id');

        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then(result => {
            if (result.isConfirmed) {
                $.post("{{ route('stockins.deleteStock') }}", { id: stockInId, _token: csrfToken })
                    .done(() => {
                        toastr.success('Stock entry deleted!');
                        $row.remove();
                    })
                    .fail(() => toastr.error('Failed to delete stock entry.'));
            }
        });
    });

    // Add New Row
    $('#add-new-row').on('click', function () {
        const newRow = `
            <tr class="new-stock-row">
                <td>${'{{ $batch_id }}'}</td>
                <td class="editable" data-field="product_id">
                    <select class="form-control product-dropdown">
                        {!! $products->map(fn($product) => "<option value='{$product->id}'>{$product->name} ({$product->id})</option>")->implode('') !!}
                    </select>
                </td>
                <td>--</td>
                <td class="editable" data-field="supplier_id">
                    <select class="form-control supplier-dropdown">
                        {!! $suppliers->map(fn($supplier) => "<option value='{$supplier->id}'>{$supplier->name}</option>")->implode('') !!}
                    </select>
                </td>
                <td class="editable" data-field="rack_id">
                    <select class="form-control rack-dropdown">
                        {!! $racks->map(fn($rack) => "<option value='{$rack->id}'>{$rack->name} ({$rack->id})</option>")->implode('') !!}
                    </select>
                </td>
                <td class="editable" data-field="qty"><input type="number" step="0.1" class="form-control" name="qty"></td>
                <td class="editable" data-field="purchase_price"><input type="number" step="0.1" class="form-control" name="purchase_price"></td>
                <td class="editable" data-field="sell_price"><input type="number" step="0.1" class="form-control" name="sell_price"></td>
                <td>--</td>
                <td class="editable" data-field="expiration_date"><input type="date" class="form-control" name="expiration_date"></td>
                <td class="editable" data-field="alert_date"><input type="date" class="form-control" name="alert_date"></td>
                <td>
                    <button class="save-new-row-btn btn btn-success btn-sm">Save</button>
                    <button class="cancel-new-row-btn btn btn-danger btn-sm">Cancel</button>
                </td>
            </tr>`;
        $('#stock-table tbody').prepend(newRow);
    });

    // Save New Row
    $(document).on('click', '.save-new-row-btn', function () {
        const $row = $(this).closest('tr');
        const newStockData = {
            batch_id: '{{ $batch->id }}',
            rack_id: $row.find('.rack-dropdown').val(),
            product_id: $row.find('.product-dropdown').val(),
            supplier_id: $row.find('.supplier-dropdown').val(),
            qty: $row.find('input[name="qty"]').val(),
            purchase_price: $row.find('input[name="purchase_price"]').val(),
            sell_price: $row.find('input[name="sell_price"]').val(),
            expiration_date: $row.find('input[name="expiration_date"]').val(),
            alert_date: $row.find('input[name="alert_date"]').val(),
            _token: csrfToken
        };

        // Basic validation
        if (!newStockData.product_id || !newStockData.supplier_id || !newStockData.qty) {
            toastr.error('Please fill in all required fields.');
            return;
        }

        $.ajax({
            url: "{{ route('stockins.addStockModify') }}",
            method: 'POST',
            data: newStockData,
            success: function () {
                toastr.success('Stock entry added successfully!');
                location.reload();
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                toastr.error('Failed to add new stock entry.');
            }
        });
    });

    // Cancel Adding New Row
    $(document).on('click', '.cancel-new-row-btn', function () {
        $(this).closest('tr').remove();
    });
});


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