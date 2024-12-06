<!-- resources/views/menus/index.blade.php -->
@extends('layouts.app')
@section('title', 'Menus')
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
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Menus</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Menus</li>
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
            <h1 class="card-title">Menus</h1>
            <div class="card-tools">
              
              @can('create menu')
              <a href="{{ route('menus.create') }}" class="btn btn-success">Add New</a>
              @endcan
              @can('view role')
              <a href="{{ route('roles.index') }}" class="btn btn-primary">Roles</a>
              @endcan
              @can('view permission')
              <a href="{{ route('permissions.index') }}" class="btn btn-info">Premissions</a>
              @endcan
            </div>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                  <table class="table table-sm table-bordered mt-4 w-100" id="example2">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Route</th>
                            <th>Icon</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menus as $menu)
                            <tr>
                                <td>{{ $menu->name }}</td>
                                <td>{{ $menu->route }}</td>
                                <td><i class="{{ $menu->icon }}"></i></td>
                                <td class="text-center">
                                  @can('update menu')
                                    <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                    @endcan
                                    @can('delete menu')
                                    <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-warning btn-sm">Delete</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>

                            <!-- List submenus if present -->
                            @foreach ($menu->children as $child)
                                <tr>
                                    <td>-- {{ $child->name }}</td>
                                    <td>{{ $child->route }}</td>
                                    <td><i class="{{ $child->icon }}"></i></td>
                                    
                                    <td class="text-center">
                                      @can('update menu')
                                        <a href="{{ route('menus.edit', $child->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        @endcan
                                        @can('delete menu')
                                        <form action="{{ route('menus.destroy', $child->id) }}" method="POST" style="display:inline;">
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
            </div>

            <!-- Pagination Links -->
            <div class="row mt-3">
                <div class="col-md-12 d-flex justify-content-center">
                    {{ $menus->links() }}
                </div>
            </div>
        </div>

        </div>
      </div>
    </div>
  </div>
</section>
 
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
      "ordering": false,
      "info": false,
      "autoWidth": false,
    });
  });
</script>
@endsection