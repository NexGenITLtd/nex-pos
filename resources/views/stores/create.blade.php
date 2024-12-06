@extends('layouts.app')
@section('title', 'Add New Store')
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
        <h1>Store Add</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Store Add</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <form action="{{route('stores.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
      <div class="col-md-12">
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
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Store information</h3>
            <div class="card-tools">
              <a href="{{route('stores.index')}}" class="btn btn-success float-right"><i class="fa fa-angle-double-left"></i> Back</a>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4 form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{old('name')}}">
              </div>
              <div class="col-md-4 form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{old('phone')}}">
              </div>
              <div class="col-md-4 form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{old('email')}}">
              </div>
              <!-- Printer Paper Size Field -->
              <div class="col-md-4 form-group">
                  <label for="printer_paper_size">Printer Paper Size</label>
                  <select name="printer_paper_size" class="form-control" required>
                      <option value="80mm">80mm</option>
                      <option value="58mm">58mm</option>
                      <option value="A4">A4</option>
                  </select>
              </div>
              <div class="col-md-4 form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" class="form-control" rows="4">{{old('address')}}</textarea>
              </div>
              <div class="col-md-4 form-group">
                <label for="return_policy">Return policy</label>
                <textarea id="return_policy" name="return_policy" class="form-control" rows="4">{{old('return_policy')}}</textarea>
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
        <input type="submit" value="Submit" class="btn btn-success float-right">
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