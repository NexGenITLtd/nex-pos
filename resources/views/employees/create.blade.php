@extends('layouts.app')
@section('title', 'Add New Employee')

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
        <h1>Employee Add</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Employee Add</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    

    <!-- General Information -->
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
    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">General</h3>
                <div class="card-tools">
                    <a href="{{ route('employees.index') }}" class="btn btn-success float-right">
                        <i class="fa fa-angle-double-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Name -->
                    <div class="col-md-4 form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="col-md-4 form-group">
                        <label for="phone">Phone</label>
                        <input 
                        type="text" 
                        id="phone" 
                        name="phone" 
                        class="form-control" 
                        value="{{ old('phone') }}" 
                        pattern="^\+?[0-9\s\-]{7,15}$" 
                        title="Please enter a valid phone number (e.g., +123456789 or 0123456789)">

                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-4 form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div class="col-md-4 form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                        @error('date_of_birth')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- NID -->
                    <div class="col-md-4 form-group">
                        <label for="nid">NID</label>
                        <input type="text" id="nid" name="nid" class="form-control" value="{{ old('nid') }}">
                        @error('nid')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Blood Group -->
                    <div class="col-md-4 form-group">
                        <label for="blood_group">Blood Group</label>
                        <input 
                        type="text" 
                        id="blood_group" 
                        name="blood_group" 
                        class="form-control" 
                        value="{{ old('blood_group') }}" 
                        pattern="^(A|B|AB|O)[+-]$" 
                        title="Please enter a valid blood group (e.g., A+, O-, AB+).">
                        @error('blood_group')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Image -->
                    <div class="col-md-4 form-group">
                        <label for="image">Image</label>
                        <input type="file" name="image" id="image" class="form-control-file">
                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
                <!-- Other Information -->
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Others</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Store -->
                    <div class="col-md-4 form-group">
                        <label for="store_id">Store</label>
                        <select id="store_id" name="store_id" class="form-control">
                            <option value="0">Select one</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                            @endforeach
                        </select>
                        @error('store_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div class="col-md-4 form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" class="form-control">
                            <option value="">Select one</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Job Title -->
                    <div class="col-md-4 form-group">
                        <label for="job_title">Job Title</label>
                        <input type="text" id="job_title" name="job_title" class="form-control" value="{{ old('job_title') }}">
                        @error('job_title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Join Date -->
                    <div class="col-md-4 form-group">
                        <label for="join_date">Join Date</label>
                        <input type="date" id="join_date" name="join_date" class="form-control" value="{{ old('join_date') }}">
                        @error('join_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Salary -->
                    <div class="col-md-4 form-group">
                        <label for="salary">Salary</label>
                        <input type="number" id="salary" name="salary" class="form-control" value="{{ old('salary') }}">
                        @error('salary')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <input type="submit" value="Submit" class="btn btn-success float-right">
            </div>
        </div>
    </form>
</div>

</div>
</section>
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
            image: '',
          },
          rows: [
                {}
            ]
      },
      methods: {
          photo(event){
            let file = event.target.files[0];
            let reader = new FileReader();
            reader.onload = (e) => {
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
