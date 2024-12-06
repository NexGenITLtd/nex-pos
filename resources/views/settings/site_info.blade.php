@extends('layouts.app')
@section('title', 'Settings')
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
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Settings</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Site Info</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    
    <form action="{{ route('site-info') }}" method="post" enctype="multipart/form-data">
        @csrf
    <!-- Bootstrap Card for Site Information -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Site Information</h3>
      </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="name">Site name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $site_info->name }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ $site_info->phone }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $site_info->email }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="currency">Currency</label>
                <input type="text" name="currency" id="currency" class="form-control" value="{{ $site_info->currency }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="short_about">Short About</label>
                <textarea name="short_about" id="short_about" class="form-control">{{ $site_info->short_about }}</textarea>
              </div>
            </div>
          </div>
        </div>
    </div>
    
    <!-- Bootstrap Card for Address -->
    <div class="card mt-3">
      <div class="card-header">
        <h3 class="card-title">Address & Map</h3>
      </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="address">Address</label>
                <textarea name="address" id="address" class="form-control">{{ $site_info->address }}</textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="map_embed">Map Embed (Width: 100%)</label>
                <textarea name="map_embed" id="map_embed" class="form-control">{{ $site_info->map_embed }}</textarea>
              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="card mt-3">
      <div class="card-header">
        <h3 class="card-title">Barcode</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="barcode_height">Height</label>
              <input type="text" name="barcode_height" id="barcode_height" class="form-control" value="{{ $site_info->barcode_height }}">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="barcode_width">Width</label>
              <input type="text" name="barcode_width" id="barcode_width" class="form-control" value="{{ $site_info->barcode_width }}">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap Card for Site Logos -->
    <div class="card mt-3">
      <div class="card-header">
        <h3 class="card-title">Site Logos</h3>
      </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6 form-group">
              <label for="logo">Logo (180x56)</label>
              <input type="file" name="logo" id="logo" class="form-control-file" @change="logo($event)">
              <br>
              <img :src="form.logo" alt="Logo" width="180" height="56">
            </div>
            
            <div class="col-md-6 form-group">
              <label for="fav_icon">Favicon (16x16)</label>
              <input type="file" name="fav_icon" id="fav_icon" class="form-control-file" @change="fav_icon($event)">
              <br>
              <img :src="form.fav_icon" alt="Favicon" width="16" height="16">
            </div>
          </div>
        </div>
    </div>

    <!-- Bootstrap Card for Address -->
    <div class="card mt-3">
      <div class="card-header">
        <h3 class="card-title">Invoice Info</h3>
      </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="return_policy">Return policy</label>
                <textarea name="return_policy" id="return_policy" class="form-control">{{ $site_info->return_policy }}</textarea>
              </div>
            </div>
            <div class="col-md-6 form-group">
              <label for="print_logo">Print Logo (120x120)</label>
              <input type="file" name="print_logo" id="print_logo" class="form-control-file" @change="print_logo($event)">
              <br>
              <img :src="form.print_logo" alt="Print Logo" width="120" height="120">
            </div>
          </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="card mt-3">
      <div class="card-body">
        <button type="submit" class="btn btn-outline-success btn-lg">Update</button>
      </div>
    </div>
	</form>
    
  </div><!-- /.container-fluid -->
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
<script>
  var app = new Vue({
      el: "#app",
      data: {
          form: {
            logo: "/images/logo/{{ $site_info->logo }}",
            print_logo: "/images/logo/{{ $site_info->print_logo }}",
            fav_icon: "/images/logo/{{ $site_info->fav_icon }}",
          },
      },
      methods: {
        logo(event){
          let file = event.target.files[0];
          let reader = new FileReader();
          reader.onload = (e) => {
            this.form.logo = e.target.result;
          };
          reader.readAsDataURL(file);
        },
        print_logo(event){
          let file = event.target.files[0];
          let reader = new FileReader();
          reader.onload = (e) => {
            this.form.print_logo = e.target.result;
          };
          reader.readAsDataURL(file);
        },
        fav_icon(event){
          let file = event.target.files[0];
          let reader = new FileReader();
          reader.onload = (e) => {
            this.form.fav_icon = e.target.result;
          };
          reader.readAsDataURL(file);
        },
      }
  });
</script> 
@endsection
