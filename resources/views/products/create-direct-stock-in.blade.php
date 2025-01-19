@extends('layouts.app')
@section('title', 'Add New Stock')
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
        <h1>Add New Stock</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">Add New Stock</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Add Product Stock</h3>
          <div class="card-tools">
            <button @click="addNewRow" class="btn btn-primary">New Row</button>
            <a href="{{ route('products.index') }}" class="btn btn-success"><i class="fa fa-angle-double-left"></i> Back</a>
          </div>
        </div>
        <div class="card-body">
          <div id="">
            <div class="row">
              <div class="col-md-12 col-lg-12 mb-4" v-for="(row, index) in rows" :key="index">
                <div class="card">
                  <div class="card-header">
                    <h5>Product Entry @{{ index + 1 }}</h5>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <!-- Product Name -->
                      <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                        <label>Product Name<span class="text-red">*</span></label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="row.product_name"
                          placeholder="Enter product name"
                        />
                      </div>

                      <!-- Category -->
                      <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                        <label>Category<span class="text-red">*</span></label>
                        <select
                          class="form-control"
                          v-model="row.product_category_id"
                          @change="getSubCat(row)"
                        >
                          <option value="" disabled>Select Category</option>
                          <option v-for="category in categories" :key="category.id" :value="category.id">
                            @{{ category.name }}
                          </option>
                        </select>
                      </div>

                      <!-- Subcategory -->
                      <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                        <label>Subcategory</label>
                        <select
                          class="form-control"
                          v-model="row.product_sub_category_id"
                          :disabled="!row.subcategories.length"
                        >
                          <option value="" disabled>Select Subcategory</option>
                          <option v-for="subcategory in row.subcategories" :key="subcategory.id" :value="subcategory.id">
                            @{{ subcategory.name }}
                          </option>
                        </select>
                      </div>

                      <!-- Store -->
                      <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                        <label>Store</label>
                        <select
                          class="form-control"
                          v-model="row.store_id"
                          @change="getRacks(row)"
                        >
                          <option value="" disabled>Select Store<span class="text-red">*</span></option>
                          <option v-for="store in stores" :key="store.id" :value="store.id">
                            @{{ store.name }}
                          </option>
                        </select>
                      </div>

                      <!-- Rack -->
                      <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                        <label>Rack</label>
                        <select
                          class="form-control"
                          v-model="row.rack_id"
                          :disabled="!row.racks.length"
                        >
                          <option value="" disabled>Select Rack</option>
                          <option v-for="rack in row.racks" :key="rack.id" :value="rack.id">
                            @{{ rack.name }}
                          </option>
                        </select>
                      </div>

                      <!-- Supplier -->
                      <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                        <label>Supplier</label>
                        <select class="form-control" v-model="row.supplier_id">
                          <option value="" disabled>Select Supplier<span class="text-red">*</span></option>
                          <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                            @{{ supplier.name }}
                          </option>
                        </select>
                      </div>

                      <!-- Brand -->
                      <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                        <label>Brand</label>
                        <select class="form-control" v-model="row.brand_id">
                          <option value="" disabled>Select Brand</option>
                          <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                            @{{ brand.name }}
                          </option>
                        </select>
                      </div>

                      <!-- Unit -->
                      <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                        <label>Unit</label>
                        <select class="form-control" v-model="row.unit_id">
                          <option value="" disabled>Select Unit</option>
                          <option v-for="unit in units" :key="unit.id" :value="unit.id">
                            @{{ unit.name }}
                          </option>
                        </select>
                      </div>

                      <!-- Quantity -->
                      <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                        <label>Quantity<span class="text-red">*</span></label>
                        <input
                          type="number"
                          class="form-control"
                          v-model="row.qty"
                          placeholder="Enter quantity"
                          min="0"
                        />
                      </div>

                      <!-- Purchase Price -->
                      <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                        <label>Purchase Price<span class="text-red">*</span></label>
                        <input
                          type="number"
                          class="form-control"
                          v-model="row.purchase_price"
                          placeholder="Enter purchase price"
                          min="0"
                        />
                      </div>

                      <!-- Sell Price -->
                      <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                        <label>Sell Price<span class="text-red">*</span></label>
                        <input
                          type="number"
                          class="form-control"
                          v-model="row.sell_price"
                          placeholder="Enter sell price"
                          min="0"
                        />
                      </div>

                      <!-- Expiration Date -->
                      <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                        <label>Expiration Date</label>
                        <input type="date" class="form-control" v-model="row.expiration_date" />
                      </div>

                      <!-- Alert Date -->
                      <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3">
                        <label>Alert Date</label>
                        <input type="date" class="form-control" v-model="row.alert_date" />
                      </div>
                    </div>
                  </div>
                  <div class="card-footer text-right">
                    <button class="btn btn-success btn-sm" @click="saveNewRow(index)">
                      Save
                    </button>
                    <button class="btn btn-danger btn-sm" @click="cancelNewRow(index)">
                      Cancel
                    </button>
                  </div>
                </div>
              </div>

            </div>
          </div>

        </div>
        <div class="card-footer" v-if="rows.length>1">
          <!-- Add a Save All button -->
          <button class="btn btn-success" @click="saveAllRows">Save All</button>

        </div>  
      </div>
    </div>
  </div>
</section>
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
   window.routes = {
    productDirectStockIns: "{{ route('product-direct-stock-ins') }}",
    subcategories: "{{ route('api.subcategories', ['id' => 'ID']) }}",
    racks: "{{ route('api.racks', ['store_id' => 'STORE_ID']) }}",
    categories: "{{ route('api.categories') }}",
  };
  new Vue({
  el: "#app",
  data: {
    rows: [],
    categories: [],
    subcategories: [],
    brands: {!! json_encode($brands) !!},
    racks: [],
    suppliers: {!! json_encode($suppliers) !!},
    stores: {!! json_encode($stores) !!},
    units: {!! json_encode($units) !!},
  },
  methods: {
    addNewRow() {
      const twoYearsLater = new Date();
      twoYearsLater.setFullYear(twoYearsLater.getFullYear() + 2);
      const defaultExpirationDate = twoYearsLater.toISOString().split('T')[0];

      const alertDateObj = new Date(twoYearsLater);
      alertDateObj.setDate(alertDateObj.getDate() - 10);
      const alertDate = alertDateObj.toISOString().split('T')[0];

      this.rows.unshift({
        product_name: ``,
        product_category_id: null,
        product_sub_category_id: null,
        store_id: null,
        supplier_id: null,
        brand_id: null,
        rack_id: null,
        unit_id: null,
        qty: null,
        purchase_price: null,
        sell_price: null,
        expiration_date: defaultExpirationDate,
        alert_date: alertDate,
        subcategories: [],
        racks: []
      });
    },
    saveNewRow(index) {
      const row = this.rows[index];
      axios
        .post(window.routes.productDirectStockIns, row)
        .then(response => {
          console.log(response.data.message);
          toastr.success(response.data.message);
          this.rows.splice(index, 1);
          if (this.rows.length >= 0) {
            this.addNewRow();
          }
        })
        .catch(error => {
          console.error('Error:', error.response?.data?.message || 'An error occurred.');
          toastr.error('Error:', error.response?.data?.message || 'An error occurred.');
        });
    },
    saveAllRows() {
      if (this.rows.length) {
        this.rows.forEach((_, index) => {
          this.saveNewRow(index);
          toastr.info(`Product ${index + 1} saving.`);
        });
      } else {
        toastr.error("No rows to save.");
      }
    },
    cancelNewRow(index) {
      this.rows.splice(index, 1); // Remove the row
    },
    getSubCat(row) {
      if (!row.product_category_id) return;

      const url = window.routes.subcategories.replace('ID', row.product_category_id);
      axios
        .get(url)
        .then(response => {
          row.subcategories = response.data;
        })
        .catch(error => {
          console.error('Error fetching subcategories:', error.response?.data?.message || 'An error occurred.');
        });
    },
    getRacks(row) {
      if (!row.store_id) return;

      const url = window.routes.racks.replace('STORE_ID', row.store_id);
      axios
        .get(url)
        .then(response => {
          row.racks = response.data;
        })
        .catch(error => {
          console.error('Error fetching racks:', error.response?.data?.message || 'An error occurred.');
        });
    },
    getCat() {
      axios
        .get(window.routes.categories)
        .then(response => {
          this.categories = response.data;
        })
        .catch(error => {
          console.error('Error fetching categories:', error.response?.data?.message || 'An error occurred.');
        });
    }
  },
  mounted() {
    this.getCat();
    this.addNewRow();
  },
});

</script>
@endsection
