@extends('layouts.app')
@section('title', 'Add New Supplier')
@section('link')
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('backend/')}}/plugins/fontawesome-free/css/all.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('backend/')}}/dist/css/adminlte.min.css">
@endsection
@section('content')
<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Supplier</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Add Supplier</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content">
    <form action="{{ route('suppliers.store') }}" method="post" enctype="multipart/form-data" id="supplierForm">
        @csrf
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Supplier Details</h3>
                <a href="{{ route('suppliers.index') }}" class="btn btn-success float-right">
                    <i class="fa fa-angle-double-left"></i> Back
                </a>
            </div>
            <div class="card-body">
                @include('partials.alerts')

                <div class="row">
                    @php
                        $fields = [
                            ['label' => 'Name', 'name' => 'name', 'type' => 'text'],
                            ['label' => 'Contact Person', 'name' => 'contact_person', 'type' => 'text'],
                            ['label' => 'Phone', 'name' => 'phone', 'type' => 'text'],
                            ['label' => 'Email', 'name' => 'email', 'type' => 'email'],
                        ];
                    @endphp

                    @foreach ($fields as $field)
                        <div class="col-md-4 form-group">
                            <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                            <input type="{{ $field['type'] }}" id="{{ $field['name'] }}" name="{{ $field['name'] }}"
                                   class="form-control" value="{{ old($field['name']) }}">
                            @error($field['name']) <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    @endforeach

                    <div class="col-md-4 form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" class="form-control" rows="1">{{ old('address') }}</textarea>
                        @error('address') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="image">Image</label>
                        <input type="file" name="image" id="image" class="form-control-file" @change="previewImage">
                        <br>
                        <img :src="form.image" alt="Preview" width="150" height="150">
                        @error('image') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success float-right">Submit</button>
            </div>
        </div>
    </form>
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
    new Vue({
        el: "#supplierForm",
        data: {
            form: {
              image: "{{ asset('images/default.png' ) }}",
            }
        },
        methods: {
            previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.form.image = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }
        }
    });
</script>
@endsection
