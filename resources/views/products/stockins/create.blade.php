
@extends('layouts.app')
@section('title', 'Stock Ins')
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
        <h1>Stock Ins</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Stock Ins</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Stock Ins</h3>
            <div class="card-tools">
              <a href="{{route('stockins.index')}}" class="btn btn-success float-right"><i class="fa fa-angle-double-left"></i> Back</a>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

              <div class="col-md-3 form-group">
                <label for="product_id">Product</label>
                <select v-model="product_id" @change='getProductName();' name="product_id" class="form-control select2">
                  <option value="0">Select one</option>
                  @foreach($products as $key => $product)
                  <option value="{{$product->id}}">{{$product->name}}({{$product->id}})</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3 form-group">
                <label for="supplier_id">Supplier</label>
                <select v-model="supplier_id" name="supplier_id" @change='getSupplierName();' class="form-control select2">
                  <option value="0">Select one</option>
                  @foreach($suppliers as $key => $supplier)
                  <option value="{{$supplier->id}}" @if($key==0) selected="" @endif>{{$supplier->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-2 form-group">
                <label for="qty">Quantity</label>
                <input type="number" v-model="qty" name="qty" class="form-control" step="0.1">
              </div>
              <div class="col-md-2 form-group">
                <label for="expiration_date">Expiration Date</label>
                <input type="date" v-model="expiration_date" name="expiration_date" class="form-control ">
              </div>
              <div class="col-md-2 form-group">
                <label for="alert_date">Alert Date</label>
                <div class="input-group">
                  <input type="date" class="form-control" v-model="alert_date" name="alert_date">
                  <span class="input-group-append">
                    <button type="button" class="btn btn-info btn-flat" @click="addRow"><i class="fas fa-plus"></i></button>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <form action="{{ route('stockins.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @METHOD("POST")
          <div class="card-body">
            <div class="row" v-if="product_rows.length > 0">
              <div class="col-12 col-sm-12">
                <div class="col-md-12">
                  <table id="mytable" class="table table-bordred table-striped table-responsive">
                    <thead>
                      <tr>
                        <th rowspan="2">Product</th>
                        <th rowspan="2">Supplier</th>
                        <th rowspan="2">Qty</th>
                        <th colspan="2">Price</th>
                        <th rowspan="2">Rack ID</th>
                        <th rowspan="2">Expiration Date</th>
                        <th rowspan="2">Alert Date</th>
                      </tr>
                      <tr>
                        <th>Purchase</th>
                        <th>Sale</th>
                      </tr>
                    </thead>
                    <tbody id="add_more_section">
                      <tr v-for="(row,k) in product_rows" :key="k">
                        <td>@{{row.product_name}}<input type="hidden" name="product_id[]" id="product_id" v-model="row.product_id"></td>
                        <td>@{{row.supplier_name}}<input type="hidden" name="supplier_id[]" id="supplier_id" v-model="row.supplier_id"></td>
                        <td><input type="number" step="0.1" name="qty[]" id="qty" v-model="row.qty" style="width:100px;"></td>
                        <td><input type="number" step="0.1" name="purchase_price[]" id="purchase_price" v-model="row.purchase_price" style="width:100px;"></td>
                        <td><input type="number" step="0.1" name="sell_price[]" id="sell_price" v-model="row.sell_price" style="width:100px;"></td>
                        <td>
                          <select name="rack_id[]" style="padding: 3px 3px;">
                            <option value="0">Select One</option>
                             <option v-for="(rack, index) in racks" :value="rack.id" :selected="index === 0">@{{rack.name}}</option>

                          </select>
                        </td>
                        <td><input type="date" name="expiration_date[]" id="expiration_date" v-model="row.expiration_date"></td>
                        <td><div class="input-group-append">
                          <input type="date" name="alert_date[]" id="alert_date" v-model="row.alert_date">
                            <span  v-on:click="removeElement(row);" style="cursor: pointer" title="remove" class="input-group-text text-danger"><i class="fas fa-minus"></i></span>
                          </div></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <div class="card-body" v-if="product_rows.length > 0">
            <div class="row">
              <div class="col-md-2 form-group">
                <label for="stock_date">Stock Date</label>
                  <input type="date" name="stock_date" v-model="stock_date" class="form-control form-control-sm">

              </div>
              <div class="col-md-2 form-group">
                <label for="invoice_no">Invoice no.</label>
                <input type="text" name="invoice_no" v-model="invoice_no" class="form-control form-control-sm" value="{{old('invoice_no')}}">
              </div>
              <div class="col-md-3 form-group">
                <label for="store_id">Store</label>
                <select name="store_id" class="form-control form-control-sm select2">
                  <option value="0">Select one</option>
                  @foreach(App\Models\Store::get() as $key_store => $store)
                  <option value="{{$store->id}}" @if($key_store==0) selected="" @endif>{{$store->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-2 form-group">
                <label for="batch">Batch</label>
                <input type="number" step="0.1" name="batch_id" class="form-control form-control-sm" value="{{App\Models\Batch::count()+1}}">
              </div>
            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer" v-if="product_rows.length > 0">
            <div class="row">
              <div class="col-12">
                <input type="submit" value="Submit" class="btn btn-success float-right">
              </div>
            </div>
          </div>
          <!-- /.card-footer -->
          </form>
        </div>
        <!-- /.card -->
      </div>
    </div>
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
$(function () {
    // Initialize Select2 Elements (This should only be done once in mounted)
    $('.select2').select2();
});

const currentDate = new Date();
const oneYearLater = new Date(currentDate);
oneYearLater.setFullYear(currentDate.getFullYear() + 1);

// Calculate default alert_date (current date + 1 year - 10 days)
const alertDate = new Date(oneYearLater);
alertDate.setDate(oneYearLater.getDate() - 10);

// Format dates to `YYYY-MM-DD`
const formatDate = (date) => date.toISOString().split('T')[0];

var app = new Vue({
  el: "#app",
  data: {
    product_id: 0,
    product_name: '',
    purchase_price: 0,
    sell_price: 0,
    supplier_id: 0,
    supplier_name: '',
    expiration_date: formatDate(oneYearLater), // Default expiration date
    alert_date: formatDate(alertDate),        // Default alert date
    qty: 5,
    racks: [],
    product_rows: [],
    stock_date: '',
    invoice_no: '',
    // Store the URLs for the API requests
    productUrl: "{{ route('api.product', ['id' => '__product_id__']) }}",
    supplierUrl: "{{ route('api.supplier', ['id' => '__supplier_id__']) }}",
    racksUrl: "{{ route('api.racks', ['store_id' => '__store_id__']) }}",
  },
  methods: {
    getProductName: function() {
      axios.get(this.productUrl.replace('__product_id__', this.product_id))
        .then(response => {
            if (response.data[0]['stock_ins'].length) {
                let a = response.data[0]['stock_ins'].length - 1;
                this.purchase_price = response.data[0]['stock_ins'][0]['purchase_price'];
                this.sell_price = response.data[0]['stock_ins'][a]['sell_price'];
            }
            this.product_name = response.data[0]['name'];
        });
    },

    getSupplierName: function() {
      axios.get(this.supplierUrl.replace('__supplier_id__', this.supplier_id))
        .then(response => {
            this.supplier_name = response.data[0]['name'];
        });
    },

    getRacks: function(store_id) {
      if (!store_id) return; // If no store_id, skip the request
      axios.get(this.racksUrl.replace('__store_id__', store_id))
        .then(response => {
            this.racks = response.data;
        });
    },

    addRow: function () {
      if (this.product_id !== 0 && this.supplier_id !== 0) {
        this.product_rows.push({
          product_id: this.product_id,
          product_name: this.product_name,
          supplier_name: this.supplier_name,
          supplier_id: this.supplier_id,
          expiration_date: this.expiration_date,
          alert_date: this.alert_date,
          qty: this.qty,
          purchase_price: this.purchase_price,
          sell_price: this.sell_price
        });
      } else {
        toastr.error('Please select product and supplier');
      }
    },

    removeRow: function (row) {
      var index = this.product_rows.indexOf(row);
      this.product_rows.splice(index, 1);
    },

    setFilename: function (event) {
      this.filename = event.target.name;
    }
  },

  beforeMount() {
    // Set stock date and invoice number
    let today = new Date();
    this.stock_date = today.toISOString().split('T')[0];
    this.invoice_no = today.toISOString().split('T')[0];
  },

  mounted() {
    // Initialize Select2 and bind change events to update Vue model
    const self = this;
    $('.select2').select2().on('change', function () {
      const name = $(this).attr('name');
      const value = $(this).val();

      // Update Vue model dynamically based on the select change
      if (name === 'product_id') {
        self.product_id = value;
        self.getProductName();
      } else if (name === 'supplier_id') {
        self.supplier_id = value;
        self.getSupplierName();
      }
    });

    this.getRacks(this.store_id); // Call for racks on mounted if store_id is available
  },

  updated() {
    // Reinitialize Select2 after Vue DOM updates (for dynamic content updates)
    $('.select2').select2();
  },
});
</script>

@endsection