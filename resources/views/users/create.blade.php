@extends('layouts.app')
@section('title', 'Add New User')

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
        <h1>User Add</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">User Add</li>
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
    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">General</h3>
                <div class="card-tools">
                    <a href="{{ route('users.index') }}" class="btn btn-success float-right">
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
                        <select id="role" name="roles[]" class="form-control" multiple>
                            <option value="">Select Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}"
                                    @if(is_array(old('roles')) && in_array($role, old('roles')))
                                        selected
                                    @endif
                                >
                                    {{ $role }}
                                </option>
                                
                            @endforeach
                        </select>
                        @error('role')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Password -->
                    <div class="col-md-4 form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control">
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-4 form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                        @error('password_confirmation')
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
          }
      }
  });
</script> 
@endsection
