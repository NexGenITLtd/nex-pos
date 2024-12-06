<!-- resources/views/units/edit.blade.php -->
@extends('layouts.app')
@section('title', 'Edit Unit')
@section('link')
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('backend/')}}/plugins/fontawesome-free/css/all.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('backend/')}}/dist/css/adminlte.min.css">
@endsection
@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Edit Unit</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Edit Unit</li>
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
            <h3 class="card-title">Edit Unit</h3>
            <div class="card-tools">
              <a href="{{route('units.index')}}" class="btn btn-success float-right"><i class="fa fa-angle-double-left"></i> Back</a>
            </div>
          </div>
            <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    @if(session('success'))
                      <div class="alert alert-success">
                          {{ session('success') }}
                      </div>
                  @endif

                  @if($errors->any())
                      <div class="alert alert-danger">
                          <ul>
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @endif
                    <form action="{{ route('units.update', $unit->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Unit Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $unit->name }}" required>
                        </div>


                        <button type="submit" class="btn btn-success">Update Unit</button>
                    </form>
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