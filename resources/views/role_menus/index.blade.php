<!-- resources/views/menus/index.blade.php -->
@extends('layouts.app')
@section('title', 'Menu List')
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded and parsed');
    const form = document.getElementById('roleMenuForm');

    // Handle form submission
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(form);
        const id = document.getElementById('roleMenuId').value;
        const url = id ? `/role-menus/${id}` : '/role-menus';
        const method = id ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            location.reload(); // Reload the page to see the changes
        })
        .catch(error => console.error('Error:', error));
    });

    // Edit role menu
    // Edit role menu
    document.querySelectorAll('.edit-role-menu').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const roleMenuId = row.getAttribute('data-id');
            const roleIdsAttr = row.getAttribute('data-role-ids');
            const menuIdsAttr = row.getAttribute('data-menu-ids');

            // Check if attributes exist before splitting
            const roleIds = roleIdsAttr ? roleIdsAttr.split(',') : [];
            const menuIds = menuIdsAttr ? menuIdsAttr.split(',') : [];

            console.log('Role IDs:', roleIds); // Debugging line
            console.log('Menu IDs:', menuIds); // Debugging line

            // Set selected role IDs
            const roleSelect = document.getElementById('role_id');
            Array.from(roleSelect.options).forEach(option => {
                option.selected = roleIds.includes(option.value); // Set selected options based on role IDs
            });

            // Set selected menu IDs
            const menuSelect = document.getElementById('menu_id');
            Array.from(menuSelect.options).forEach(option => {
                option.selected = menuIds.includes(option.value); // Set selected options based on menu IDs
            });

            // Set additional fields
            document.getElementById('roleMenuId').value = roleMenuId; // Set role menu ID
            document.getElementById('can_create').checked = row.querySelector('td:nth-child(4)').textContent === 'Yes';
            document.getElementById('can_edit').checked = row.querySelector('td:nth-child(5)').textContent === 'Yes';
            document.getElementById('can_delete').checked = row.querySelector('td:nth-child(6)').textContent === 'Yes';
            document.getElementById('can_view').checked = row.querySelector('td:nth-child(7)').textContent === 'Yes';
        });
    });


    // Delete role menu
    document.querySelectorAll('.delete-role-menu').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const roleMenuId = row.getAttribute('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/role-menus/${roleMenuId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire(
                            'Deleted!',
                            data.message,
                            'success'
                        );
                        location.reload(); // Reload the page to see the changes
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'An error occurred while deleting the role menu.',
                            'error'
                        );
                    });
                }
            });
        });
    });
});

</script>
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Product</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Product Sell List</li>
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
                <h3 class="card-title">Manage Role Menus</h3>
                <div class="card-tools">
                    <a href="{{ route('role_menus.assign_admin_menus') }}" class="btn btn-warning">Reassign All Menus to Admin Role</a>
                </div>
            </div>
            <div class="card-body">
                <form id="roleMenuForm" method="POST" action="{{ route('role-menus.store') }}">
    @csrf
    <input type="hidden" id="roleMenuId" name="id">

    <div class="form-row">
        <!-- Role Selection -->
        <div class="form-group col-md-4">
            <label for="role_id">Role</label>
            <select id="role_id" name="role_id[]" class="form-control" multiple required>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Menu Selection -->
        <div class="form-group col-md-4">
            <label for="menu_id">Menu</label>
            <select id="menu_id" name="menu_id[]" class="form-control" multiple required>
                @foreach($menus as $menu)
                    <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Inline Checkboxes for Permissions -->
        <div class="form-group col-md-4">
            <label>Permissions</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="can_create" name="can_create" value="1">
                <label class="form-check-label" for="can_create">Can Create</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="can_edit" name="can_edit" value="1">
                <label class="form-check-label" for="can_edit">Can Edit</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="can_delete" name="can_delete" value="1">
                <label class="form-check-label" for="can_delete">Can Delete</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="can_view" name="can_view" value="1">
                <label class="form-check-label" for="can_view">Can View</label>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div class="form-row">
        <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-primary">Save Role Menu</button>
        </div>
    </div>
</form>



                <hr>
                <h2>Existing Role Menus</h2>
                <div class="table-responsive">
                <table class="table table-sm table-bordered " id="example2">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Role</th>
                            <th>Menu</th>
                            <th>Can Create</th>
                            <th>Can Edit</th>
                            <th>Can Delete</th>
                            <th>Can View</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($roleMenus as $index => $roleMenu)
                        <tr data-id="{{ $roleMenu->id }}" data-role-ids="{{ $roleMenu->role_id }}" data-menu-ids="{{ $roleMenu->menu_id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $roleMenu->role->name }}</td>
                            <td>{{ $roleMenu->menu->name }}</td>
                            <td>{{ $roleMenu->can_create ? 'Yes' : 'No' }}</td>
                            <td>{{ $roleMenu->can_edit ? 'Yes' : 'No' }}</td>
                            <td>{{ $roleMenu->can_delete ? 'Yes' : 'No' }}</td>
                            <td>{{ $roleMenu->can_view ? 'Yes' : 'No' }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info edit-role-menu">Edit</button>
                                <button type="button" class="btn btn-sm btn-danger delete-role-menu">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                </table>
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
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
    });
  });
</script>
@endsection