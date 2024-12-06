<!-- resources/views/menus/edit.blade.php -->
@extends('layouts.app')
@section('title', 'Menu Edit')
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
        <h1>Edit Menu</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Edit Menu</li>
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
            <h1 class="card-title">Edit Menu</h1>
            <div class="card-tools">
              <a href="{{route('menus.index')}}" class="btn btn-success float-right"><i class="fa fa-angle-double-left"></i> Back</a>
            </div>
          </div>
          <div class="card-body">
            <form action="{{ route('menus.update', $menu->id) }}" method="POST" class="row g-3 align-items-center">
                @csrf
                @method('PUT')

                <div class="col-auto">
                    <label for="name" class="visually-hidden">Menu Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $menu->name }}" placeholder="Menu Name" required>
                </div>

                <div class="col-auto">
                    <label for="route" class="visually-hidden">Route</label>
                    <input type="text" name="route" class="form-control" value="{{ $menu->route }}" placeholder="Route" list="routeList" autocomplete="off">
                      <datalist id="routeList">
                          @foreach($routes as $route)
                              <option value="{{ $route }}">
                          @endforeach
                      </datalist>
                </div>

                <div class="col-auto">
                    <label for="icon" class="visually-hidden">Icon</label>
                    <input type="text" name="icon" class="form-control" value="{{ $menu->icon }}" placeholder="Icon">
                </div>

                <div class="col-auto">
                    <label for="parent_id" class="visually-hidden">Parent Menu</label>
                    <select name="parent_id" class="form-control">
                        <option value="">-- Parent Menu --</option>
                        @foreach ($parentMenus as $parentMenu)
                            <option value="{{ $parentMenu->id }}" {{ $menu->parent_id == $parentMenu->id ? 'selected' : '' }}>
                                {{ $parentMenu->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <label for="order" class="visually-hidden">Order</label>
                    <input type="number" name="order" class="form-control" value="{{ $menu->order }}" placeholder="Order">
                </div>


                <div class="col-auto">
                    <button type="submit" class="btn btn-success mt-4">Update Menu</button>
                </div>
            </form>
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