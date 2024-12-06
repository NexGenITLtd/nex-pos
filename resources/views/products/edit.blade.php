@extends('layouts.app')
@section('title', 'Edit Product')
@section('link')
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('backend/')}}/plugins/fontawesome-free/css/all.min.css">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('backend/')}}/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{asset('backend/')}}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('backend/')}}/dist/css/adminlte.min.css">
@endsection
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Edit Product</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Edit Product</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<!-- Main content -->
<section class="content">
  <div id="app">
    <form action="{{ route('products.update', $product->id) }}" method="post" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Product Information</h3>
              <div class="card-tools">
                <a href="{{ route('products.index') }}" class="btn btn-success float-right"><i class="fa fa-angle-double-left"></i> Back</a>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4 form-group">
                  <label for="name">Name</label>
                  <input type="text" id="name" name="name" class="form-control" value="{{ $product->name }}">
                </div>
                <div class="col-md-4 form-group">
                  <label for="product_category_id">Category</label>
                  <select id="product_category_id" name="product_category_id" class="form-control" @change="getSubCat" v-model="product_category_id">
                    <option v-for="cat in categories" :value="cat.id" :selected="cat.id == {{ $product->product_category_id }}">@{{ cat.name }}</option>
                  </select>
                </div>
                <div class="col-md-4 form-group">
                  <label for="product_sub_category_id">Sub-category</label>
                  <select id="product_sub_category_id" name="product_sub_category_id" class="form-control" v-model="product_sub_category_id">
                    <option v-for="subcat in subcategories" :value="subcat.id" :selected="subcat.id == {{ $product->product_sub_category_id }}">@{{ subcat.name }}</option>
                  </select>
                </div>
                <div class="col-md-4 form-group">
                  <label for="brand_id">Brand</label>
                  <select id="brand_id" name="brand_id" class="form-control" v-model="brand_id">
                    <option v-for="brand in brands" :value="brand.id" :selected="brand.id == {{ $product->brand_id }}">@{{ brand.name }}</option>
                  </select>
                </div>
                <div class="col-md-4 form-group">
                  <label for="unit">Unit</label>
                  <select id="unit" name="unit" class="form-control">
                    @foreach(App\Models\Unit::get() as $unit)
                    <option value="{{ $unit->name }}" {{ $unit->name == $product->unit ? 'selected' : '' }}>{{ $unit->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <div class="row">
                <div class="col-12">
                  <input type="submit" value="Update" class="btn btn-success float-right">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script src="{{asset('backend/')}}/plugins/jquery/jquery.min.js"></script>
<script src="{{asset('backend/')}}/plugins/select2/js/select2.full.min.js"></script>
<script src="{{asset('backend/')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('backend/')}}/dist/js/adminlte.min.js"></script>

<script>
  var app = new Vue({
      el: "#app",
      data: {
        product_category_id: @json($product->product_category_id),
        product_sub_category_id: @json($product->product_sub_category_id ?? 0),
        brand_id: @json($product->brand_id),
        subcategories: [],
        categories: [],
        brands: []
      },
      methods: {
        getCat() {
          axios.get("{{ route('api.categories') }}")
            .then(response => this.categories = response.data)
            .catch(error => console.error(error));
        },
        getSubCat() {
          const url = "{{ route('api.subcategories', ['id' => '__category_id__']) }}";
          axios.get(url.replace('__category_id__', this.product_category_id))
            .then(response => this.subcategories = response.data)
            .catch(error => console.error(error));
        },
        getBrand() {
          axios.get("{{ route('api.brands') }}")
            .then(response => this.brands = response.data)
            .catch(error => console.error(error));
        },
      },
      mounted() {
        this.getCat();
        this.getSubCat();
        this.getBrand();
      }
  });
</script>
@endsection
