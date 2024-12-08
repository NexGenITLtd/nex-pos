@extends('layouts.app')
@section('title', 'Categories')
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
        <h1>Categories</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Categories</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Categories</h3>
            <div class="card-tools">
              @can('create categories')
              <a href="{{route('categories.create')}}" class="btn btn-success float-right">Add New</a>
              @endcan
            </div>
          </div>
          <!-- /.card-header -->
          <!-- /.card-header -->
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm table-bordered mt-4 w-100" class="example2">
              <thead>
                  <tr>
                      <th>Name</th>
                      <th>Parent Category</th>
                      <th class="text-center">Actions</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($categories as $category)
                      <!-- Parent category -->
                      <tr>
                          <td>{{ $category->name }}</td>
                          <td>N/A</td>
                          
                          <td class="text-center">
                              @can('update categories')
                                  <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary btn-sm">Edit</a>
                              @endcan
                              @can('delete categories')
                                  <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                      @csrf
                                      @method('DELETE')
                                      <button type="submit" class="btn btn-warning btn-sm">Delete</button>
                                  </form>
                              @endcan
                          </td>
                      </tr>

                      <!-- Subcategories -->
                      @foreach ($category->subcategories as $subcategory)
                          <tr>
                              <td>-- {{ $subcategory->name }}</td>
                              <td>{{ $category->name }}</td>
                              <td class="text-center">
                                  @can('update categorires')
                                      <a href="{{ route('categories.edit', $subcategory->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                  @endcan
                                  @can('delete categorires')
                                      <form action="{{ route('categories.destroy', $subcategory->id) }}" method="POST" style="display:inline;">
                                          @csrf
                                          @method('DELETE')
                                          <button type="submit" class="btn btn-warning btn-sm">Delete</button>
                                      </form>
                                  @endcan
                              </td>
                          </tr>
                      @endforeach
                  @endforeach
              </tbody>
          </table>

          </div>
          <span class="float-right">{{ $categories->links('pagination::bootstrap-4') }}</span>
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
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
    });
  });
</script>
@endsection