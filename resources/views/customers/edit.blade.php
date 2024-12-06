
@extends('layouts.app')
@section('title', 'Edit Customer')
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
        <h1>Customer Edit</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Customer Edit</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <form action="{{route('customers.update',$customer->id)}}" method="POST" enctype="multipart/form-data">
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
            <h3 class="card-title">Customer Info</h3>
            <div class="card-tools">
              <a href="{{route('customers.index')}}" class="btn btn-success float-right"><i class="fa fa-angle-double-left"></i> Back</a>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4 form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{$customer->name}}">
              </div>
              <div class="col-md-4 form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{$customer->phone}}">
              </div>
              <div class="col-md-4 form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{$customer->email}}">
              </div>
              <div class="form-group">
                <input type="checkbox" name="membership" id="membership" value="Member" 
                    {{ $customer->membership === 'Member' ? 'checked' : '' }}>
                <label for="membership">Membership</label>
                <br>
                <small>Check if you want to become a member</small>
            </div>
              <div class="col-md-4 form-group">
                <label for="discount">Discount</label>
                <input type="discount" id="discount" name="discount" class="form-control" value="{{$customer->discount}}">
              </div>
              <div class="col-md-4 form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" class="form-control" rows="1">{{$customer->address}}</textarea>
              </div>
              <div class="col-md-4 form-group">
                <label for="image">Image</label>
                <input type="file" name="image" id="image"  @change="photo($event)" class="form-control-file">
                <br>
                <img :src="form.image" alt="" width="150" height="150">
              </div>
            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <div class="row">
      <div class="col-12">
        <input type="submit" value="Update" class="btn btn-success float-right">
      </div>
    </div>
          </div>    
        </div>
        <!-- /.card -->
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
            image: "{{ asset('images/customers/' . ($customer->img ?? 'default-image.png')) }}"
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
        // The file's text will be printed here
        this.form.image = e.target.result
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