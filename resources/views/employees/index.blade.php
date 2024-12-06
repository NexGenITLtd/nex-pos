@extends('layouts.app')
@section('title', 'Employees')
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
        <h1>Employees</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Employees</li>
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
            <h3 class="card-title">Employees</h3>
            <div class="card-tools">
              <a href="{{route('employees.create')}}" class="btn btn-success float-right">Add New</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <!-- Success and Error Messages -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="table-responsive">
              <table id="example2" class="table table-sm table-bordered table-striped">
                <thead>
                    
                    <tr>
                        <!-- General Info Columns -->
                        <th>ID</th>
                        <th>Store ID</th>
                        <th>Name</th>
                        <th>Phone</th>

                        <!-- Employee Info Columns -->
                        <th>Email</th>
                        <th>Job Title</th>
                        <th>Date of Birth</th>
                        <th>Join Date</th>
                        <th>Salary</th>
                        <th>NID</th>

                        <!-- Access Info Columns -->
                        <th>Image</th>
                        <th>Blood Group</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $emp)
                    <tr>
                        <!-- General Info Data -->
                        <td>{{ $emp->id }}</td>
                        <td>@if($emp->store){{ $emp->store->name }}@endif</td>
                        <td>{{ $emp->name }}</td>
                        <td>{{ $emp->phone }}</td>

                        <!-- Employee Info Data -->
                        <td>{{ $emp->email }}</td>
                        <td>{{ $emp->job_title }}</td>
                        <td>{{ $emp->date_of_birth }}</td>
                        <td>{{ $emp->join_date }}</td>
                        <td>{{ $emp->salary }}</td>
                        <td>{{ $emp->nid }}</td>

                        <!-- Access Info Data -->
                        <td>
                            <img src="{{ asset($emp->image && file_exists(public_path('images/employees/' . $emp->image)) ? 'images/employees/' . $emp->image : 'images/default.png') }}" height="60" width="60" alt="No Image">
                        </td>
                        <td>{{ $emp->blood_group }}</td>
                        <td>{{ $emp->role }}</td>

                        <!-- Action Column -->
                        <td>
                            <span class="btn-group">
                                <a href="{{ route('employees.edit', $emp->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ route('employees.destroy', $emp->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>


            </div>
            <span class="float-right">{{ $employees->links() }}</span>
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
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "pageLength": 10,
    });
  });
</script>
@endsection
