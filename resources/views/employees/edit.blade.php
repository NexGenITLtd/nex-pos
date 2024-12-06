
@extends('layouts.app')
@section('title', 'Edit Employee')
@section('link')
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('backend/')}}/plugins/fontawesome-free/css/all.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('backend/')}}/dist/css/adminlte.min.css">
@endsection
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Employee Edit</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Employee Edit</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-12">
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

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">General Information</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-4 form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $employee->name) }}" placeholder="Enter employee's name">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Phone -->
                        <div class="col-md-4 form-group">
                            <label for="phone">Phone</label>
                            <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}" placeholder="+123456789">
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-4 form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}" placeholder="example@mail.com">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div class="col-md-4 form-group">
                            <label for="date_of_birth">Date of Birth{{$employee->date_of_birth}}</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $employee->date_of_birth) }}">
                            @error('date_of_birth')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NID -->
                        <div class="col-md-4 form-group">
                            <label for="nid">NID</label>
                            <input type="text" id="nid" name="nid" class="form-control" value="{{ old('nid', $employee->nid) }}" placeholder="National ID">
                            @error('nid')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Blood Group -->
                        <div class="col-md-4 form-group">
                            <label for="blood_group">Blood Group</label>
                            <input type="text" id="blood_group" name="blood_group" class="form-control" value="{{ old('blood_group', $employee->blood_group) }}" placeholder="A+, B-, O+...">
                            @error('blood_group')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="col-md-4 form-group">
                            <label for="image">Image</label>
                            <input type="file" id="image" name="image" class="form-control-file" @change="photo($event)">
                            <br>
                            <img :src="form.image" alt="Image" class="img-thumbnail" width="150">
                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Other Details -->
        <div class="col-md-12">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Other Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Store -->
                        <div class="col-md-4 form-group">
                            <label for="store_id">Store</label>
                            <select id="store_id" name="store_id" class="form-control">
                                <option value="">Select a store</option>
                                @foreach ($stores as $store)
                                    <option value="{{ $store->id }}" {{ old('store_id', $employee->store_id) == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('store_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Job Title -->
                        <div class="col-md-4 form-group">
                            <label for="job_title">Job Title</label>
                            <input type="text" id="job_title" name="job_title" class="form-control" value="{{ old('job_title', $employee->job_title) }}">
                            @error('job_title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Join Date -->
                        <div class="col-md-4 form-group">
                            <label for="join_date">Join Date</label>
                            <input type="date" id="join_date" name="join_date" class="form-control" value="{{ old('join_date', $employee->join_date) }}">
                            @error('join_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="col-md-4 form-group">
                            <label for="role">Role</label>
                            <select id="role" name="role" class="form-control">
                                <option value="">Select a role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role', $employee->role) == $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Salary -->
                        <div class="col-md-4 form-group">
                            <label for="salary">Salary</label>
                            <input type="number" id="salary" name="salary" class="form-control" value="{{ old('salary', $employee->salary) }}">
                            @error('salary')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="submit" value="Save Changes" class="btn btn-primary">
                </div>
            </div>
        </div>
    </div>
</form>



</section>
<!-- /.content -->
@endsection
@section('script')
<!-- vue -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.js"></script>
<!-- jQuery -->
<script src="{{asset('backend/')}}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('backend/')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="{{asset('backend/')}}/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('backend/')}}/dist/js/demo.js"></script>
<script>
  var app = new Vue({
    el: "#app",
    data: {
        form: {
            image: "{{ asset('images/employees/' . ($employee->image ?? 'default-image.png')) }}"

        },
        rows: [{}]
    },
    methods: {
        photo(event) {
            let file = event.target.files[0];
            let reader = new FileReader();
            reader.onload = (e) => {
                // The file's text will be printed here
                this.form.image = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        addRow: function () {
            this.rows.push({});
        },
        removeElement: function (row) {
            var index = this.rows.indexOf(row);
            this.rows.splice(index, 1);
        },
    }
});

</script> 
@endsection