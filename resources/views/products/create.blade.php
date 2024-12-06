@extends('layouts.app')
@section('title', 'Add New Product')
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
        <h1>Product Add</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Product Add</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content" id="app" v-cloak>
  <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Product information</h3>
            <div class="card-tools">
              <a href="{{ route('products.index') }}" class="btn btn-success float-right"><i class="fa fa-angle-double-left"></i> Back</a>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <!-- Name -->
              <div class="col-md-4 form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" aria-invalid="@error('name') true @enderror">
                @error('name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              <!-- Category -->
              <div class="col-md-4 form-group">
                <label for="product_category_id">Category</label>
                <select id="product_category_id" name="product_category_id" class="form-control @error('product_category_id') is-invalid @enderror" @change="getSubCat" v-model="product_category_id">
                  <option value="" disabled>Select a category</option>
                  <option v-for="cat in categories" :value="cat.id">@{{ cat.name }}</option>
                </select>
                @error('product_category_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              <!-- Sub-category -->
              <div class="col-md-4 form-group">
                <label for="product_sub_category_id">Sub-category</label>
                <select id="product_sub_category_id" name="product_sub_category_id" class="form-control @error('product_sub_category_id') is-invalid @enderror">
                  <option value="" disabled>Select a sub-category</option>
                  <option v-for="subcat in subcategories" :value="subcat.id">@{{ subcat.name }}</option>
                </select>
                @error('product_sub_category_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              <!-- Brand -->
              <div class="col-md-4 form-group">
                <label for="brand_id">Brand</label>
                <select id="brand_id" name="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
                  <option value="" disabled>Select a brand</option>
                  <option v-for="brand in brands" :value="brand.id">@{{ brand.name }}</option>
                </select>
                @error('brand_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              <!-- Unit -->
              <div class="col-md-4 form-group">
                <label for="unit">Unit</label>
                <select id="unit" name="unit" class="form-control @error('unit') is-invalid @enderror">
                  @foreach(App\Models\Unit::get() as $key => $unit)
                  <option value="{{ $unit->name }}" @if($key == 1) selected @endif>{{ $unit->name }}</option>
                  @endforeach
                </select>
                @error('unit')
                <span class="text-danger">{{ $message }}</span>
                @enderror
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
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->
      </div>
    </div>
  </form>
</section>
<!-- /.content -->
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js" integrity="sha512-bZS47S7sPOxkjU/4Bt0zrhEtWx0y0CRkhEp8IckzK+ltifIIE9EMIMTuT/mEzoIMewUINruDBIR/jJnbguonqQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- vue -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.js"></script>
<!-- jQuery -->
<script src="{{asset('backend/')}}/plugins/jquery/jquery.min.js"></script>
<!-- Select2 -->
<script src="{{asset('backend/')}}/plugins/select2/js/select2.full.min.js"></script>
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
      product_category_id: null,
      subcategories: [],
      categories: [],
      brands: [],
      subcategoriesUrl: "{{ route('api.subcategories', ['id' => '__category_id__']) }}", // Move the URL to Vue data
    },
    methods: {
      getCat() {
        axios.get("{{ route('api.categories') }}")
          .then(response => this.categories = response.data);
      },
      getSubCat() {
        // Replace the placeholder with the actual category ID
        const url = this.subcategoriesUrl.replace('__category_id__', this.product_category_id);
        axios.get(url)
          .then(response => this.subcategories = response.data);
      },
      getBrand() {
        axios.get("{{ route('api.brands') }}")
          .then(response => this.brands = response.data);
      },
    },
    mounted() {
      this.getCat();
      this.getBrand();
    }
  });
</script>

@endsection
