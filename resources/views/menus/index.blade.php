<!-- resources/views/menus/index.blade.php -->
@extends('layouts.app')
@section('title', 'Menus')

@section('link')
<!-- External Styles -->
<link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/ionicons/2.0.1/css/ionicons.min.css') }}">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('backend/dist/css/adminlte.min.css') }}">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Menus</h3>
                <div class="card-tools">
                    @can('create menu')
                    <a href="{{ route('menus.create') }}" class="btn btn-success">Add New</a>
                    @endcan
                    @can('view role')
                    <a href="{{ route('roles.index') }}" class="btn btn-primary">Roles</a>
                    @endcan
                    @can('view permission')
                    <a href="{{ route('permissions.index') }}" class="btn btn-info">Permissions</a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mt-4 w-100" id="example2">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Order</th>
                                <th>Route</th>
                                <th>Icon</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menus as $menu)
                            <tr id="menu-{{ $menu->id }}">
                                <td>{{ $menu->name }}</td>
                                <td>{{ $menu->order }}</td>
                                <td>{{ $menu->route }}</td>
                                <td><i class="{{ $menu->icon }}"></i></td>
                                <td class="text-center">
                                    @can('update menu')
                                    <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                    @endcan
                                    @can('delete menu')
                                    <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-warning btn-sm">Delete</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @foreach ($menu->children as $child)
                            <tr id="menu-{{ $child->id }}">
                                <td>-- {{ $child->name }}</td>
                                <td>{{ $child->order }}</td>
                                <td>{{ $child->route }}</td>
                                <td><i class="{{ $child->icon }}"></i></td>
                                <td class="text-center">
                                    @can('update menu')
                                    <a href="{{ route('menus.edit', $child->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                    @endcan
                                    @can('delete menu')
                                    <form action="{{ route('menus.destroy', $child->id) }}" method="POST" class="d-inline">
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
                <div class="d-flex justify-content-center mt-3">
                    {{ $menus->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('backend/dist/js/adminlte.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<script>
    $(document).ready(function () {
        $('#example2 tbody').sortable({
            update: function (event, ui) {
                let orderedIds = $(this).sortable('toArray');
                $.ajax({
                    url: '{{ route('menus.updateOrder') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ordered_ids: orderedIds
                    },
                    success: function (response) {
                        console.log('Order updated successfully!');
                    },
                    error: function (xhr) {
                        console.error('Error updating order:', xhr.responseText);
                    }
                });
            }
        });

        $('#example2').DataTable({
            paging: false,
            lengthChange: false,
            searching: true,
            ordering: false,
            info: false,
            autoWidth: false,
        });
    });
</script>
@endsection
