
@extends('layouts.app')
@section('title', 'Edit Card')
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
        <h1>Edit Card</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Edit Card</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
<div class="container">
    <form action="{{ route('cards.update', $card->id) }}" method="post" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="row">
          <div class="col-md-12">
              <div class="card card-primary">
                  <div class="card-header">
                      <h3 class="card-title">Edit Card</h3>
                      <div class="card-tools">
                          <a href="{{ route('cards.index') }}" class="btn btn-success btn-sm"><i class="fa fa-angle-double-left"></i> Back</a>
                      </div>
                  </div>
                  <div class="card-body">
                      <div class="row">
                          <div class="col-md-12 form-group">
                              <label for="card_type">Name <span class="text-danger">*</span></label>
                              <input type="text" id="card_type" name="card_type" class="form-control @error('card_type') is-invalid @enderror" value="{{ old('card_type', $card->card_type) }}">
                              
                              <!-- Show error message if validation fails -->
                              @error('card_type')
                                  <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                          </div>
                      </div>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                      <div class="row">
                          <div class="col-12">
                              <input type="submit" value="Update" class="btn btn-success">
                          </div>
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