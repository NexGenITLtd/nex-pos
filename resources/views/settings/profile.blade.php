@extends('layouts.app')

@section('title', 'Profile')
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
        <h1>Profile</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Profile</li>
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
                    Profile
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('profile') }}" method="POST" enctype="multipart/form-data">
                      @csrf

                      <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', auth()->user()->name) }}">
                        @error('name')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', auth()->user()->phone) }}">
                        @error('phone')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="form-group">
                        <label for="img">Profile Image</label>
                        <input type="file" name="img" id="imgInput" class="form-control-file @error('img') is-invalid @enderror" onchange="previewImage(event)">
                        @error('img')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="form-group">
                        <label for="imgPreview">Image Preview:</label>
                        <img id="imgPreview" src="{{ old('img', auth()->user()->img ? asset('images/employees/' . auth()->user()->img) : asset('images/default.png')) }}" alt="Profile Image Preview" style="max-width: 200px; max-height: 200px; display: {{ auth()->user()->img ? 'block' : 'none' }};">
                      </div>

                      <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection

@section('script')
<!-- jQuery -->
<script src="{{ asset('backend/') }}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{ asset('backend/') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="{{ asset('backend/') }}/dist/js/adminlte.js"></script>
<script>
  function previewImage(event) {
    const imgPreview = document.getElementById('imgPreview');
    const file = event.target.files[0];
    
    if (file) {
      const reader = new FileReader();
      
      reader.onload = function(e) {
        imgPreview.src = e.target.result;
        imgPreview.style.display = 'block'; // Show the image once it's loaded
      }

      reader.readAsDataURL(file); // Read the file as a data URL
    } else {
      imgPreview.style.display = 'none'; // Hide the preview if no file is selected
      imgPreview.src = '';
    }
  }
</script>
@endsection
