@extends('layouts.app')
@section('title', 'Create Category')
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
        <h1>Create Category</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Create Category</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container">
    <form action="{{route('categories.store')}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Create Category</h3>
            <div class="card-tools">
              <a href="{{route('categories.index')}}" class="btn btn-success btn-sm"><i class="fa fa-angle-double-left"></i> Back</a>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 form-group">
                  <label for="name">Name</label>
                  <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                  
                  <!-- Display error message for 'name' -->
                  @if ($errors->has('name'))
                      <div class="text-danger">
                          <small>{{ $errors->first('name') }}</small>
                      </div>
                  @endif
              </div>
              
              <div class="col-md-6 form-group">
                  <label for="parent_id">Category label</label>
                  <select id="parent_id" name="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                      <option value="">Main category</option>
                      @foreach($categories as $key => $category)
                          <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                      @endforeach
                  </select>
                  
                  <!-- Display error message for 'parent_id' -->
                  @if ($errors->has('parent_id'))
                      <div class="text-danger">
                          <small>{{ $errors->first('parent_id') }}</small>
                      </div>
                  @endif
              </div>
          </div>
          <!-- /.card-body -->
        </div>
        <div class="card-footer">
            <div class="row">
              <div class="col-md-12">
                <input type="submit" value="Submit" class="btn btn-success">
              </div>
            </div>
          </div>
        <!-- /.card -->
      </div>
    </div>
    
  </form>
  </div>
  
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

@endsection