@extends('layouts.app')
@section('title', 'Supplier List')
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
        <h1>Suppliers</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Suppliers</li>
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
            <h3 class="card-title">Suppliers</h3>
            <div class="card-tools">
              <a href="{{route('suppliers.create')}}" class="btn btn-success float-right">Add New</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            @include('partials.alerts')
            <div class="table-responsive">
                <table id="suppliersTable" class="table table-sm table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Contact Person</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
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

<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> -->

<script>
    // Event delegation for delete buttons
    document.addEventListener('DOMContentLoaded', function() {
        // This is used to bind click event to dynamically added delete buttons
        document.querySelector('table').addEventListener('click', function(e) {
            // Check if the clicked element is a delete button
            if (e.target && e.target.classList.contains('delete-btn')) {
                e.preventDefault(); // Prevent default action

                const supplierId = e.target.getAttribute('data-id');
                const deleteForm = document.getElementById('delete-form-' + supplierId);

                // Show confirmation dialog with SweetAlert2
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form if confirmed
                        deleteForm.submit();
                    }
                });
            }
        });
    });
</script>

<!-- page script -->
<script>
    $(function () {
        $('#suppliersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('suppliers.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'contact_person', name: 'contact_person' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { data: 'address', name: 'address' },
                { data: 'image', name: 'image', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endsection