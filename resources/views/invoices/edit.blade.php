@extends('layouts.app')
@section('title', 'Invoice Edit')
@section('link')
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('backend/')}}/plugins/fontawesome-free/css/all.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('backend/')}}/dist/css/adminlte.min.css">
<link rel="stylesheet" href="{{asset('backend/')}}/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{asset('backend/')}}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

<style type="text/css">
	.table td, .table th {
	    padding: 0;
	}
	.form-group {
	    margin-bottom: 4px;
	}
	.card, .card-body {
		padding: 2px !important;
		margin-bottom: 2px !important;
	}
	.card-header {
		padding: 2px 2px !important;
	}
	.card-body{
		padding: 2px 2px !important;
	}
	.select2-container .select2-selection--single {
	    width: 100%;
	    height: 38px;
	}

</style>
@endsection
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Invoice Edit</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Invoice Edit</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <form action="{{ route('invoices.update', $invoice->id) }}" method="post" enctype="multipart/form-data">
    @csrf
	@method('PUT')
    <div class="row">
	    <div class="col-md-8">
	        <div class="card card-primary d-none">
	          <div class="card-body">
	            <div class="row">
	              <div class="col-md-6 form-group">
	              	<label for="product_id">Product code</label>
	              	<div class="input-group">
	              		<input type="text" 
	              		list="productList"
	              		id="product_id" 
	              		v-model="product_id" 
	              		name="product_id" 
	              		class="form-control form-control-sm" 
	              		value="{{old('product_id')}}" 
	              		@keyup.enter="addCart();calculateTotal()">
		                <div class="input-group-append" @click="addCart();" placeholder="Scan or type then enter product code">
		                	<span class="input-group-text" id="basic-addon2"><i class="fa fa-plus"></i></span>
		                </div>
	              	</div>
	              	<!-- Datalist for product suggestions -->
				    <datalist id="productList">
				        @foreach($products as $product)
				            <option value="{{ $product->id }}">{{ $product->name }}</option>
				        @endforeach
				    </datalist>
	              </div>
	              <div class="col-md-6 form-group d-none">
	              	<label for="invoice_id">Search Invoice</label>
	              	<div class="input-group">
	              		<input type="text" id="invoice_id" v-model="invoice_id" name="invoice_id" class="form-control form-control-sm" value="{{old('invoice_id')}}" @keyup.enter="searchInvoice();" placeholder="Scan or type invoice no then enter">
		                <div class="input-group-append" @click='searchInvoice();'>
		                	<span class="input-group-text" id="basic-addon2">Search</span>
		                </div>
	              	</div>
	              </div>
	            </div>
	          </div>
	          <!-- /.card-body -->
	        </div>
	        <div class="card card-primary d-none" v-if="carts.length>0">
	          <div class="card-body p-1">
	            <div class="row">
	              <div class="col-md-12">
	                <table class="table-bordered" style="width: 100%">
	                	<thead>
	                		<tr>
	                			<td width="10%">Code</td>
	                			<td width="15%">Product name</td>
	                			<td width="10%">Qty</td>
	                			<td width="10%">Price</td>
	                			<td width="10%">Dis(%)</td>
	                			<td width="10%">Vat(%)</td>
	                			<td width="10%">Total</td>
	                			<td width="5%"></td>
	                		</tr>
	                	</thead>
	                	<tbody>
	                		<tr 
							  v-for="(cart, index) in carts" 
							  :key="cart.id" 
							  :class="{'bg-warning': cart.status === 'Return'}"
							>


	                			<td width="10%">@{{ cart.product_id }}</td>
	                			<td width="15%">@{{ cart.product_name }}</td>
	                			<td width="10%" contenteditable="true" @input="updateField(cart.id, 'qty', $event)">@{{ cart.qty }}</td>
	                			<td width="10%">@{{ cart.sell_price }}</td>
	                			<td width="10%" contenteditable="true" @input="updateField(cart.id, 'discount', $event)">@{{ cart.discount }}</td>
	                			<td width="10%" contenteditable="true" @input="updateField(cart.id, 'vat', $event)">@{{ cart.vat }}</td>
	                			<td width="10%">@{{ calculateProductTotal(cart).totalIncludingVAT }}</td>
	                			<td width="5%" class="text-center">
	                				<!-- <i class="fa fa-times text-red text-bold" @click='removeCart(cart.id);'></i> -->
	                			</td>
	                		</tr>
	                	</tbody>
	                </table>
	              </div>
	            </div>
	          </div>
	          <!-- /.card-body -->
	        </div>
	        <!-- /.card -->
	        <span id="invoic_div">
	        	<div v-html="html_invoice"></div>
	        </span>
	        
	    </div>
	    <div class="col-md-4" v-if="carts.length>0">
	    	<!-- <form @submit.prevent="submitForm" action="#"> -->
		        <div class="card card-primary">
					<div class="card-header">
					<h3 class="card-title">Payment summery</h3>
					</div>
			        <div class="card-body">
			          	<table class="table table-bordered text-bold">
						  <thead>
						    <tr class="text-primary">
						      <th width="60%">Item In Cart</th>
						      <th width="40%">@{{ carts.length }}</th>
						    </tr>
						    <tr class="text-secondary">
						      <th width="60%">Total Amount</th>
						      <th width="40%">@{{ total_price_including_vat }}</th>
						    </tr>
						    <tr class="text-warning">
						      <th width="60%">Discount Amount</th>
						      <th width="40%">@{{ total_discount }}</th>
						    </tr>
						    <tr class="text-info">
						      <th width="60%">Return Amount</th>
						      <th width="40%">@{{ product_return }}</th>
						    </tr>
						    <tr class="text-info">
						      <th width="60%">Net Payable Amount</th>
						      <th width="40%">@{{ total_payable_amount }}</th>
						    </tr>
						    <tr class="text-success">
						      <th width="60%">Paid Amount</th>
						      <th width="40%">@{{ total_payments }}</th>
						    </tr>
						    <tr class="text-danger">
						      <th width="60%">Due Amount</th>
						      <th width="40%">@{{ remaining_amount }}</th>
						    </tr>
						  </thead>
						</table>

			        </div>
			        <!-- /.card-body -->
		        </div>
		        <div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Customer</h3>
						<div class="card-tools">
							<label for="make_member" class="float-right text-green">
							  <input type="checkbox" name="make_member" id="make_member" @change="handleMakeMemberChange" :checked="make_member === 'Member'"> Make Member
							</label>
						</div>	
					</div>
					<div class="card-body">
						
						<div class="bg-default">
							<table class="w-100">
								<tbody>
									
									<tr>
										<td><input type="text" class="form-control form-control-sm" @input="getCustomer()" :required="!customer_id" v-model="phone"  ref="phoneInput" placeholder="Phone *"></td>
										<td><input type="text" class="form-control form-control-sm" :required="!customer_id" v-model="name" ref="nameInput" placeholder="Name *"></td>
									</tr>
									<tr>
										<td class="text-right">
											<div class="input-group d-none">
											    <select class="form-control form-control-sm select2" name="customer_id" id="customer_id" v-model="customer_id" ref="customerSelect" data-placeholder="Select a customer">
											    	<option value="0">-- Select customer --</option>
											        @foreach(App\Models\Customer::get() as $key=>$customer)
											            <option value="{{$customer->id}}">{{$customer->name}} ({{$customer->phone}})</option>
											        @endforeach
											    </select>
											</div>
											Sellperson 
										</td>
										<td>
											<select class="form-control form-control-sm" name="sale_person_id" id="sale_person_id" v-model="sale_person_id" ref="salePersonSelect" required data-placeholder="Select a sell person">
												<option value="0">-- Select sellperson --</option>
												@php
												    $sell_person_id = 0;
												$sell_persons = App\Models\User::where('store_id', Auth::user()->store_id)->where('role_id', 'sell_person')->get();
												@endphp
												@foreach($sell_persons  as $key => $sell_person)
										    	@php
												    $sell_person_id = ($key == 0) ? $sell_person->id : $sell_person_id;
												@endphp

										    	<option value="{{$sell_person->id}}">{{$sell_person->name}}</option>
										    	@endforeach
										    </select>
										</td>
									</tr>
									
								</tbody>
							</table>
						</div>
					</div>
					<!-- /.card-body -->
		        </div>
		        <div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Discount</h3>
					</div>
					<div class="card-body">
						<table class="w-100">
							<tbody>
								<tr>
									<td width="40%">Discount(%)</td>
									<td width="40%">Partial</td>
								</tr>
								<tr>
									<td width="60%"><input type="text" class="form-control form-control-sm" name="discount" id="discount" v-model="discount" placeholder="%" @input="calculateTotal"></td>
									<td width="60%"><input type="text" class="form-control form-control-sm" name="partial" id="partial" v-model="less" placeholder="tk" @input="calculateTotal"></td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- /.card-body -->
		        </div>
		        <div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Payment</h3>
						<div class="form-check float-right">
					        <input class="form-check-input" type="checkbox" id="partialPaymentToggle" aria-expanded="false" @click="toggleCollapsePartialPayment()">
					        <label class="form-check-label" for="partialPaymentToggle">
					            Partial Payment
					        </label>
					    </div>
					</div>
					<div class="card-body">
						<table class="w-100">
							<tbody>
								<tr>
									<td width="40%">Cash payment</td>
									<td width="40%">Cash</td>
								</tr>
								
								<tr>
									<td width="60%"><input type="text" class="form-control form-control-sm" name="cash_payment" id="cash_payment" placeholder="" v-model="cash_payment" @input="calculateTotal"></td>
									<td width="60%">
										<select class="form-control form-control-sm" name="cash_account_no_id" id="cash_account_no_id" v-model="cash_account_no_id">
											<option value="">-- Select cash --</option>
											@foreach(App\Models\BankAccount::where('store_id', Auth::user()->store_id)->where('account_type', 'cash')->get() as $key => $bank_account)
											<option value="{{$bank_account->id}}">{{$bank_account->bank_name}} ({{$bank_account->account_no}})</option>
											@endforeach
										</select>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- /.card-body -->
		        </div>
		        <div class="container m-0 p-0">
				    <!-- Collapsible Section -->
				    <div class="collapse" id="partialPaymentCollapse">
				        <!-- Card/Bank Section -->
				        <div class="card card-primary">
				            <div class="card-header">
				                <h3 class="card-title">Card/Bank</h3>
				            </div>
				            <div class="card-body">
				                <table class="w-100">
				                    <tbody>
				                        <tr>
				                            <td width="40%">Card / AC payment</td>
				                            <td width="60%"><input type="text" class="form-control form-control-sm" name="card_payment" id="card_payment" placeholder="" v-model="card_payment" @input="calculateTotal"></td>
				                        </tr>
				                        <tr>
				                            <td width="40%">Card / AC number</td>
				                            <td width="60%"><input type="text" class="form-control form-control-sm" name="card_number" id="card_number" v-model="card_number" placeholder=""></td>
				                        </tr>
				                        <tr>
				                            <td width="40%">Card type</td>
				                            <td width="60%">
				                                <select class="form-control form-control-sm" name="card_type" id="card_type" v-model="card_type">
				                                    <option value="">-- Select card type --</option>
				                                    @foreach(App\Models\PaymentCardType::get() as $card_type)
				                                    <option value="{{$card_type->card_type}}">{{$card_type->card_type}}</option>
				                                    @endforeach
				                                </select>
				                            </td>
				                        </tr>
				                        <tr>
				                            <td width="40%">Account</td>
				                            <td width="60%">
				                                <select class="form-control form-control-sm" name="card_account_no_id" id="card_account_no_id" v-model="card_account_no_id">
				                                    <option value="">Select one</option>
				                                    @foreach(App\Models\BankAccount::where('store_id', Auth::user()->store_id)->where('account_type', 'bank')->get() as $bank_account)
				                                    <option value="{{$bank_account->id}}">{{$bank_account->bank_name}} ({{$bank_account->account_no}})</option>
				                                    @endforeach
				                                </select>
				                            </td>
				                        </tr>
				                    </tbody>
				                </table>
				            </div>
				        </div>
				        
				        <!-- Mobile Gateway Section -->
				        <div class="card card-primary mt-3">
				            <div class="card-header">
				                <h3 class="card-title">Mobile Gateway</h3>
				            </div>
				            <div class="card-body">
				                <table class="w-100">
				                    <tbody>
				                        <tr>
				                            <td width="40%">Mobile payment</td>
				                            <td width="60%"><input type="text" class="form-control form-control-sm" name="mobile_payment" id="mobile_payment" placeholder="" v-model="mobile_payment" @input="calculateTotal"></td>
				                        </tr>
				                            
				                        <tr>
				                            <td width="40%">Account</td>
				                            <td width="60%">
				                                <select class="form-control form-control-sm" name="mobile_account_no_id" id="mobile_account_no_id" v-model="mobile_account_no_id">
				                                    <option value="">-- Select account --</option>
				                                    @foreach(App\Models\BankAccount::where('store_id', Auth::user()->store_id)->where('account_type', 'mobile')->get() as $bank_account)
				                                    <option value="{{$bank_account->id}}">{{$bank_account->bank_name}} ({{$bank_account->account_no}})</option>
				                                    @endforeach
				                                </select>
				                            </td>
				                        </tr>
				                        <tr>
				                            <td width="40%">Sender no</td>
				                            <td width="60%"><input type="text" class="form-control form-control-sm" name="sender_no" id="sender_no" v-model="sender_no" placeholder=""></td>
				                        </tr>
				                        <tr>
				                            <td width="40%">Trx no</td>
				                            <td width="60%"><input type="text" class="form-control form-control-sm" name="trx_no" id="trx_no" v-model="trx_no" placeholder=""></td>
				                        </tr>
				                    </tbody>
				                </table>
				            </div>
				        </div>
				    </div>
				    <!-- /.collapse -->
				</div>
		        <!-- /.card -->
		        <div class="text-center mt-3">
		            <button type="button" class="btn btn-primary btn-lg w-100" @click="submitForm">Submit</button>
		        </div>
		    <!-- </form> -->
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
<!-- Bootstrap 4 -->
<script src="{{asset('backend/')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('backend/')}}/plugins/select2/js/select2.full.min.js"></script>
<!-- <script src="{{asset('backend/')}}/plugins/inputmask/jquery.inputmask.min.js"></script> -->
<!-- AdminLTE App -->
<script src="{{asset('backend/')}}/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('backend/')}}/dist/js/demo.js"></script>
<script>

  var app = new Vue({
      el: "#app",
      data: {
      	sale_person_id: "{{ $invoice->sell_person_id }}",
        customer_id: "{{ $invoice->customer_id }}",
        name: '',
        email: '',
        phone: "{{ $invoice->customer->phone }}",
        make_member: '',

        status: '',
      	product_id: '',

      	cash_payment: 0,
      	cash_account_no_id: '1',

      	card_payment: 0,
      	card_number: '',
      	card_type: '',
      	card_account_no_id: '',

      	mobile_payment: 0,
      	sender_no: '',
      	trx_no: '',
      	mobile_account_no_id: '',

      	discount: <?php echo $invoice->discount ?>,
      	vat: 0,
      	less: <?php echo $invoice->less_amount ?>,
      	total_price: 0,
      	total_discount: 0,
      	total_vat: 0,
        total_price_including_vat: 0,
        total_payable_amount: 0,
        total_payments: 0,
        remaining_amount: 0,
        product_return: <?php echo $invoice->product_return ?>,
      	carts: <?php echo json_encode($mergedProducts) ?>,
      	html_invoice: '',
      	invoice_id: <?php echo $invoice->id ?>,
      },
      methods: {
      	handleStatusChange(event) {
		    this.status = event.target.checked ? 'Return' : '';
		},
		handleMakeMemberChange(event) {
		    this.make_member = event.target.checked ? 'Member' : '';
		},
      	searchInvoice() {
      		window.invoiceShowForPrintUrl = "{{ route('invoice_show_for_print', ['id' => '__invoice_id__']) }}";
			axios.get(window.invoiceShowForPrintUrl.replace('__invoice_id__', this.invoice_id))
				.then(function (response) {
					if (response.data && Object.keys(response.data).length > 0) {
						// If valid response data is found
						app.html_invoice = response.data;
						// console.log(response.data);
					} else {
						// Handle case where the response is empty
						toastr.warning('No invoice data found for the provided ID.');
						app.html_invoice = '';
					}
				})
				.catch(function (error) {
					if (error.response) {
						// The request was made, and the server responded with a status code that is not in the 2xx range
						if (error.response.status === 404) {
							// Handle 404 error specifically
							toastr.warning('Error 404: Invoice not found. Please check the invoice ID and try again.');
							app.html_invoice = '';
						} else {
							// Handle other status codes
							// console.log("Error response data:", error.response.data);
							toastr.warning('Error: Unable to retrieve the invoice. Please try again later.');
							app.html_invoice = '';
						}
					} else if (error.request) {
						// The request was made but no response was received
						// console.log("Error request:", error.request);
						toastr.warning('Error: No response from the server. Please try again later.');
						app.html_invoice = '';
					} else {
						// Something happened while setting up the request
						// console.log("Error message:", error.message);
						toastr.warning('Error: An unexpected error occurred. Please try again.');
						app.html_invoice = '';
					}
				});
		},
      	addCart: function(){
			const article = { id: this.product_id, invoice_id: this.invoice_id };
			axios.post("{{ route('sell-product.update') }}", article)
			.then(function (response) {

				// console.log(response.data);
				if(response.data=='insufficient'){
					toastr.success('Stock limited');
					// console.log('stock limited');
				}else if(response.data=='product_not_found'){
					toastr.success('Product not found');
					// console.log('Product not found');
				}else{
					app.carts = response.data;
					app.product_id = '';
					app.calculateTotal();
				}
				app.searchInvoice();

			})
			.catch(function (error) {
			    toastr.error("Error adding to cart:", error);
			})
			.finally(function() {
			    // app.isLoading = true;
			});
		},
		updateField(id, field, event, invoice_id) {
	      // this.products[id][field] = event.target.innerText;
	      // console.log(event.target.innerText);
	      let value = event.target.innerText;

	      const article = { id, field, value, invoice_id };
	      if(value>=0 && value !=''){
	      	axios.post("{{ route('sell-product.update-qty') }}", article)
			.then(function (response) {
				if(response.data=='insufficient'){
					toastr.warning('Stock limited');
					// console.log('stock limited');
				}
				// if(response.data=='qty'){
					
				// 	app.carts = response.data;
				// 	app.calculateTotal();
				// 	app.searchInvoice();
				// }
				else{
					app.carts = response.data;
					app.calculateTotal();
					app.searchInvoice();
				}
			});
	      }
	    },
		removeCart: function(id, invoice_id) {
		  const article = { id: id, invoice_id: invoice_id };

		  // SweetAlert confirmation
		  Swal.fire({
		    title: 'Are you sure?',
		    text: "Do you really want to delete this item?",
		    icon: 'warning',
		    showCancelButton: true,
		    confirmButtonColor: '#3085d6',
		    cancelButtonColor: '#d33',
		    confirmButtonText: 'Yes, delete it!'
		  }).then((result) => {
		    if (result.isConfirmed) {
		      // Proceed with the deletion if confirmed
		      axios.post("{{ route('sell-product.delete') }}", article)
		        .then(function(response) {
		          app.carts = response.data;
		          app.calculateTotal();
		          app.searchInvoice();

		          // Show success message after deletion
		          Swal.fire(
		            'Deleted!',
		            'The item has been removed from the cart.',
		            'success'
		          );
		        });
		    }
		  });
		},
		getCustomer: function(){
			const article = { phone: this.phone };
	      	axios.post("{{ route('api.get-customer') }}", article)
			.then(function (response) {
				// console.log(response);
				if(response.data){
					// console.log(response.data['name']);
					app.customer_id = response.data['id'];
					app.name = response.data['name'];
					if(response.data['membership']=='Member'){
						app.make_member = 'Member';
					}
					
				}else{
					app.customer_id = '0';
					app.name = '';
					app.make_member = '';
				}
			});
      	},

      	calculateProductTotal(cart) {
            const productTotal = cart.qty * cart.sell_price;
            const discountAmount = (productTotal * cart.discount) / 100;
            const totalAfterDiscount = productTotal - discountAmount;

            // Calculate VAT
            const vatAmount = (totalAfterDiscount * cart.vat) / 100;
            const totalIncludingVAT = totalAfterDiscount + vatAmount;

            return {
                totalAfterDiscount: totalAfterDiscount.toFixed(2),
                totalIncludingVAT: totalIncludingVAT.toFixed(2)
            };
        },
        calculateTotal() {
		    let totalPrice = 0;
		    let totalDiscount = 0;
		    let totalVAT = 0;
		    let totalPriceIncludingVAT = 0;
		    let productReturn = 0; // Initialize total return amount

		    // Ensure `this.carts` is an array
		    if (Array.isArray(this.carts)) {
		        this.carts.forEach(cart => {
		            // Ensure numeric values and fallback to 0 if not a valid number
		            const qty = parseFloat(cart.qty) || 0;
		            const sellPrice = parseFloat(cart.sell_price) || 0;
		            const discount = parseFloat(cart.discount) || 0;
		            const vat = parseFloat(cart.vat) || 0;
		            const isReturn = cart.status === 'Return'; // Check if the product is a return

		            // Calculate product total before discount
		            const productTotal = qty * sellPrice;

		            // Calculate discount amount
		            const discountAmount = (productTotal * discount) / 100;

		            // Calculate total after discount
		            const totalAfterDiscount = productTotal - discountAmount;

		            // Calculate VAT amount on discounted price
		            const vatAmount = (totalAfterDiscount * vat) / 100;

		            if (isReturn) {
		                // Add to total return calculation
		                productReturn += totalAfterDiscount + vatAmount;

		                // Subtract return values from totals
		                totalPrice -= totalAfterDiscount;
		                totalDiscount -= discountAmount;
		                totalVAT -= vatAmount;
		                totalPriceIncludingVAT -= (totalAfterDiscount + vatAmount);
		            } else {
		                // Add regular values to totals
		                totalPrice += totalAfterDiscount;
		                totalDiscount += discountAmount;
		                totalVAT += vatAmount;
		                totalPriceIncludingVAT += (totalAfterDiscount + vatAmount);
		            }
		        });

		        // Invoice total discount
		        let lessAmount = parseFloat(this.less) || 0;
		        let discountInTotalPercentage = parseFloat(this.discount) || 0;
		        let discountInTotalPercentageAmount = parseFloat(this.total_price_including_vat) * (discountInTotalPercentage / 100);

		        let invoiceTotalDiscount = lessAmount + discountInTotalPercentageAmount;
		        totalDiscount += invoiceTotalDiscount;

		        // Set totals with 2 decimal precision
		        this.total_price = totalPrice.toFixed(2);
		        this.total_discount = totalDiscount.toFixed(2);
		        this.total_vat = totalVAT.toFixed(2);
		        this.total_price_including_vat = totalPriceIncludingVAT.toFixed(2);
		        this.product_return = productReturn.toFixed(2); // Store the total return value

		        // Payment calculations
		        let cashPayment = parseFloat(this.cash_payment) || 0;
		        let cardPayment = parseFloat(this.card_payment) || 0;
		        let mobilePayment = parseFloat(this.mobile_payment) || 0;

		        // Calculate the sum of the payments
		        let totalPayments = cashPayment + cardPayment + mobilePayment;

		        // Subtract the total payments from the total price including VAT
		        let remainingAmount = parseFloat(this.total_price_including_vat) - (totalPayments + invoiceTotalDiscount);

		        let totalPayableAmount = parseFloat(this.total_price_including_vat) - invoiceTotalDiscount;

		        // Ensure the values are valid for display
		        this.total_payable_amount = totalPayableAmount.toFixed(2);
		        this.total_payments = totalPayments.toFixed(2);
		        this.remaining_amount = remainingAmount.toFixed(2);
		    } else {
		        toastr.error('this.carts is not an array');
		        app.carts = [];
		    }
		},
        calculateTotal1() {
            let totalPrice = 0;
			let totalDiscount = 0;
			let totalVAT = 0;
			let totalPriceIncludingVAT = 0;

			// Ensure `this.carts` is an array
			if (Array.isArray(this.carts)) {
			    this.carts.forEach(cart => {
			        // Ensure numeric values and fallback to 0 if not a valid number
			        const qty = parseFloat(cart.qty) || 0;
			        const sellPrice = parseFloat(cart.sell_price) || 0;
			        const discount = parseFloat(cart.discount) || 0;
			        const vat = parseFloat(cart.vat) || 0;

			        // Calculate product total before discount
			        const productTotal = qty * sellPrice;
			        
			        // Calculate discount amount
			        const discountAmount = (productTotal * discount) / 100;
			        
			        // Calculate total after discount
			        const totalAfterDiscount = productTotal - discountAmount;

			        // Calculate VAT amount on discounted price
			        const vatAmount = (totalAfterDiscount * vat) / 100;

			        // Accumulate totals
			        totalPrice += totalAfterDiscount;
			        totalDiscount += discountAmount;
			        totalVAT += vatAmount;
			        totalPriceIncludingVAT += (totalAfterDiscount + vatAmount);
			    });

			    // invoice total discount
			    let lessAmount = parseFloat(this.less) || 0;
			    let discountInTotalPercentage = parseFloat(this.discount) || 0;
			    let discountInTotalPercentageAmount = parseFloat(this.total_price_including_vat)*(discountInTotalPercentage/100);
			    
			    let invoiceTotalDiscount = lessAmount+discountInTotalPercentageAmount;
			    totalDiscount += invoiceTotalDiscount;
			    // console.log(invoiceTotalDiscount);

			    // Set totals with 2 decimal precision
			    this.total_price = totalPrice.toFixed(2);
			    this.total_discount = totalDiscount.toFixed(2);
			    this.total_vat = totalVAT.toFixed(2);
			    this.total_price_including_vat = totalPriceIncludingVAT.toFixed(2);

			    let cashPayment = parseFloat(this.cash_payment) || 0;
				let cardPayment = parseFloat(this.card_payment) || 0;
				let mobilePayment = parseFloat(this.mobile_payment) || 0;

				// Calculate the sum of the payments
				let totalPayments = cashPayment + cardPayment + mobilePayment;

				// Subtract the total payments from the total price including VAT
				let remainingAmount = (parseFloat(this.total_price_including_vat) - (totalPayments+invoiceTotalDiscount));

				let totalPayableAmount = (parseFloat(this.total_price_including_vat) - invoiceTotalDiscount);

				// Ensure the values are valid for display
				this.total_payable_amount = totalPayableAmount.toFixed(2);
				this.total_payments = totalPayments.toFixed(2);
				this.remaining_amount = remainingAmount.toFixed(2);
			} else {
			    toastr.error("this.carts is not an array");
			}

        },
        async submitForm() {
            // Validation flags
            let valid = true;

            // Check if sale_person_id is selected
            if (!this.sale_person_id) {
                toastr.warning('Please select a sale person.');
                // alert('Please select a sale person.');
                this.$refs.salePersonSelect?.focus(); // Optional chaining
                valid = false;
            }else

            // Check customer fields if no customer is selected
            if (!this.customer_id) {
                if (!this.name) {
	                toastr.warning('Please enter the full name.');
                    // alert('Please enter the full name.');
                    this.$refs.nameInput.focus(); // Focus on name input
                    valid = false;
                    return; // Exit on first error
                } else if (!this.phone) {
	                toastr.warning('Please enter the phone number.');
                    // alert('Please enter the phone number.');
                    this.$refs.phoneInput.focus(); // Focus on phone input
                    valid = false;
                    return; // Exit on first error
                }
            }
            // If everything is valid, proceed with form submission
            if (valid) {
                // Prepare the data for submission
                const formData = {
					_token: "{{ csrf_token() }}",
                    sale_person_id: this.sale_person_id,
                    customer_id: this.customer_id,
                    name: this.name,
                    email: this.email,
                    phone: this.phone,
                    make_member: this.make_member,

					cash_payment: this.cash_payment,
					cash_account_no_id: this.cash_account_no_id,

					card_payment: this.card_payment,
					card_number: this.card_number,
					card_type: this.card_type,
					card_account_no_id: this.card_account_no_id,

					mobile_payment: this.mobile_payment,
					sender_no: this.sender_no,
					trx_no: this.trx_no,
					mobile_account_no_id: this.mobile_account_no_id,

	                discount: this.discount,
	                vat: this.vat,
	                less: this.less,
	                product_return: this.product_return,
	                total_price: this.total_price,
	                total_discount: this.total_discount,
	                total_vat: this.total_vat,
	                total_price_including_vat: this.total_price_including_vat,
	                total_payable_amount: this.total_payable_amount,
	                total_payments: this.total_payments,
	                remaining_amount: this.remaining_amount,
	                carts: this.carts
                };

                try {
                    // console.log(formData);
                    const invoiceUpdateUrl = "{{ route('invoices.update', $invoice->id) }}";
                    // Send a POST request to your server
                    const response = await axios.put(invoiceUpdateUrl, formData);

                    this.invoice_id = response.data
                    this.searchInvoice();
                    // this.html_invoice = response.data;
                    
                    // Handle success response
                    // console.log('Form submitted successfully:', response.data);
                    
	                toastr.success('Form submitted successfully');
	                // window.location.reload();
                    // alert('Form submitted successfully!');
                    // Optionally reset the form fields after submission
                    // this.resetForm();
                } catch (error) {
                    // Handle error response
                    // console.error('There was an error submitting the form:', error);
                    // alert('An error occurred while submitting the form. Please try again.');
                    toastr.error('An error occurred while submitting the form. Please try again.');
                }
            }
        },
        
		toggleCollapsePartialPayment() {
	        const partialPaymentCollapse = document.getElementById('partialPaymentCollapse');
	        const checkbox = document.getElementById('partialPaymentToggle');
	        if (checkbox.checked) {
	            $(partialPaymentCollapse).collapse('show');
	        } else {
	            $(partialPaymentCollapse).collapse('hide');
	        }
	    },
	    setPaymentAmounts(payments) {

            payments.forEach(payment => {
                if (payment.payment_type === 'cash_payment') {
                    this.cash_payment += parseFloat(payment.amount);
                    this.cash_account_no_id = payment.bank_account_id;
                } else if (payment.payment_type === 'card_payment') {
                    this.card_payment += parseFloat(payment.amount);
                    this.card_account_no_id = payment.bank_account_id;
                    this.card_number = payment.payment_from_account_no;
	      			this.card_type = payment.payment_trx_note;
	      			const checkbox = document.getElementById('partialPaymentToggle');
	      			checkbox.checked = true;
	      			const partialPaymentCollapse = document.getElementById('partialPaymentCollapse');
					$(partialPaymentCollapse).collapse('show');

                } else if (payment.payment_type === 'mobile_payment') {
                    this.mobile_payment += parseFloat(payment.amount);
                    this.mobile_account_no_id = payment.bank_account_id;

			      	this.sender_no = payment.payment_from_account_no;
			      	this.trx_no = payment.payment_trx_note;
                }
            });

        },

      },
      mounted() {
      	this.getCustomer();
      	this.setPaymentAmounts(<?php echo $invoice->payments; ?>);
        this.searchInvoice();
        this.calculateTotal();
        this.calculateTotal();
        
      }
  });
</script>
@endsection