@extends('layouts.app')
@section('title', 'Product Sale')
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
	.card {
		margin-bottom: 2px;
	}
	.card-header {
		padding: 2px 2px;
	}
	.card-body{
		padding: 2px 2px;
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
        <h1>Product Sale</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Product Sale</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <form action="{{route('invoices.create')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="card">
    	<div class="card-body p-2">
    		<div class="row">
			    <div class="col-md-8">
			        <div class="card card-primary">
			          <div class="card-header p-2">
			          	<table class="w-100">
						    <tbody>
						        <tr>
						            <!-- Product Selection -->
						            <td width="70">
						                <div class="form-group">
						                    <!-- Product Input -->
						                    <div class="input-group">
											    <input type="text" 
											           list="productList" 
											           id="product_id" 
											           v-model="product_id" 
											           name="product_id" 
											           class="form-control form-control-sm" 
											           placeholder="Scan or type product code, then press enter"
											           @keyup.enter="addCart(); calculateTotal()">
											    <div class="input-group-append">
											        <span class="btn btn-sm" 
											                :class="product_id ? 'btn-success' : 'btn-secondary'" 
											                :disabled="!product_id" 
											                @click="addCart(); calculateTotal()">
											            <i class="fa fa-plus"></i>
											        </span>
											    </div>
											</div>

						                    <!-- Product List -->
						                    <datalist id="productList">
						                        @foreach($products as $product)
						                            <option value="{{ $product->id }}">{{ $product->name }}</option>
						                        @endforeach
						                    </datalist>
						                </div>
						                <div class="form-group d-flex justify-content-between align-items-center">
						                    	
											    <!-- Return Checkbox -->
											    <label for="status" class="d-flex align-items-center text-danger mb-0">
											        <input type="checkbox" 
											               name="status" 
											               id="status" 
											               @change="handleStatusChange" 
											               :checked="status === 'Return'">
											        <span class="ml-2">Return</span>
											    </label>
											</div>
						            </td>

						            <!-- Invoice Search -->
						            <td width="30">
						                <div class="form-group ml-2">
						                    <!-- <label for="invoice_id" class="float-right">Find Invoice</label> -->
						                    <div class="input-group">
						                        <input type="text" 
						                               id="invoice_id" 
						                               v-model="invoice_id" 
						                               name="invoice_id" 
						                               class="form-control form-control-sm" 
						                               placeholder="Find Invoice"
						                               @keyup.enter="searchInvoice()">
						                        <div class="input-group-append" 
						                             @click="searchInvoice()">
						                            <span class="input-group-text">
						                                <i class="fa fa-search"></i>
						                            </span>
						                        </div>
						                    </div>
						                    
						                </div>
						                <!-- Direct Sell Button -->
											<span class="btn btn-primary float-right btn-sm" @click="addNewRow"><i class="fa fa-tags"></i> Direct-Sell</span>
						            </td>
						        </tr>
						    </tbody>
						</table>

			            <div class="row" v-if="rows.length>0">
			            	<div class="col-md-12">
			            		<div class="table-responsive">
			            			<table id="stock-table" class="table table-bordered table-sm w-100">
								      <thead>
								        <tr>
								          <td>P.Name</td>
								          <td class="d-none">Supplier</td>
								          <td class="d-none">Rack</td>
								          <td>Qty</td>
								          <td>P.Price</td>
								          <td>S.Price</td>
								          <td class="d-none">Expiration Date</td>
								          <td class="d-none">Alert Date</td>
								          <td class="text-center"><span v-if="rows.length>0" class="btn btn-success btn-sm" @click="saveAllRows">Save All</span></td>
								        </tr>
								      </thead>
								      <tbody>
								        <tr v-for="(row, index) in rows" :key="index">
								          <td>
								            <input v-model="row.product_name" type="text" class="form-control form-control-sm" placeholder="Enter product name" />
								          </td>
								          <td class="d-none">
								            <select v-model="row.supplier_id" class="form-control form-control-sm supplier-dropdown">
								              <option v-for="supplier in suppliers" :value="supplier.id" :key="supplier.id">
								                @{{ supplier.name }}
								              </option>
								            </select>
								          </td>
								          <td class="d-none">
								            <select v-model="row.rack_id" class="form-control form-control-sm rack-dropdown">
								            	<option value="" disabled>Select Rack</option>
								              <option v-for="rack in racks" :value="rack.id" :key="rack.id">
								                @{{ rack.name }} (@{{ rack.id }})
								              </option>
								            </select>
								          </td>
								          <td><input v-model="row.qty" type="number" step="0.1" class="form-control form-control-sm" /></td>
								          <td><input v-model="row.purchase_price" type="number" step="0.1" class="form-control form-control-sm" /></td>
								          <td><input v-model="row.sell_price" type="number" step="0.1" class="form-control form-control-sm" /></td>
								          <td class="d-none"><input v-model="row.expiration_date" type="date" class="form-control form-control-sm" /></td>
								          <td class="d-none"><input v-model="row.alert_date" type="date" class="form-control form-control-sm" /></td>
								          <td class="text-center">
								          	<span class="btn-group">
								          		<a href="#" @click="saveNewRow(index)" class="save-new-row-btn btn-success btn-sm">
												  <i class="fas fa-check"></i> <!-- Font Awesome check icon -->
												</a>
												<a href="#" @click="cancelNewRow(index)" class="cancel-new-row-btn btn-danger btn-sm">
												  <i class="fas fa-times"></i> <!-- Font Awesome times (cross) icon -->
												</a>
								          	</span>
								          </td>
								        </tr>
								      </tbody>
								      
								  </table>
			            		</div>
			            	</div>
			            </div>
			          </div>
			          <!-- /.card-header -->
			          <div class="card-body p-2">
			            <div class="row">
			              <div class="col-md-12" v-if="carts.length>0">
			              	<div class="table-responsive">
			              		<table class="table-bordered w-100">
				                	<thead class="info">
				                		<tr>
				                			<td width="15%">Product (Code)</td>
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
				                			<td width="20%">@{{ cart.product_name }} (@{{ cart.product_id }})</td>
				                			<td width="13%" contenteditable="true" @input="updateField(cart.id, 'qty', $event)">@{{ cart.qty }}</td>
				                			<td width="10%">@{{ cart.sell_price }}</td>
				                			<td width="10%" contenteditable="true" @input="updateField(cart.id, 'discount', $event)">@{{ cart.discount }}</td>
				                			<td width="10%" contenteditable="true" @input="updateField(cart.id, 'vat', $event)">@{{ cart.vat }}</td>
				                			<td width="10%">@{{ calculateProductTotal(cart).totalIncludingVAT }}</td>
				                			<td width="7%" class="text-center">
				                				<span class="btn btn-danger btn-xs d-block" @click='removeCart(cart.id);'><i class="fa fa-times text-bold"></i></span>
				                			</td>
				                		</tr>
				                	</tbody>
				                </table>
			              	</div>
			              </div>
			            </div>
			            <div id="invoic_div">
			            	<center>
			            		<div v-html="html_invoice"></div>
			            	</center>
				        	
				        </div>
			          </div>
			          <!-- /.card-body -->
			        </div>
			        <!-- /.card -->
			        
			    </div>
			    <div class="col-md-4" v-if="carts.length>=0">
			        <div class="card card-primary">
						<div class="card-header p-2">
						<h3 class="card-title">
						Bill Summary
						</h3>
						<div class="card-tools">
							<select class="form-control form-control-sm" name="sale_person_id" id="sale_person_id" v-model="sale_person_id" ref="salePersonSelect" required data-placeholder="Select a sell person">
								<option value="0">-- Select sellperson --</option>
								@php
								    $sell_person_id = 0;
								    $sell_persons = App\Models\User::where('store_id', Auth::user()->store_id)->where('role_id', '4')->get();
								@endphp
						    	@foreach($sell_persons  as $key => $sell_person)
						    	@php
								    $sell_person_id = ($key == 0) ? $sell_person->id : $sell_person_id;
								@endphp

						    	<option value="{{$sell_person->id}}">{{$sell_person->name}}</option>
						    	@endforeach
						    </select>
						</div>
						</div>
				        <div class="card-body p-2">
				          	<table class="w-100 table-bordered text-bold">
							  <thead>
							    <tr class="text-primary">
							      <td width="60%">Item In Cart</td>
							      <td class="text-right" width="40%">@{{ carts.length }}</td>
							    </tr>
							    <tr class="text-secondary">
							      <td width="60%">Total Amount</td>
							      <td class="text-right" width="40%">@{{ total_price_including_vat }}</td>
							    </tr>
							    <tr class="text-warning">
							      <td width="60%">Discount Amount</td>
							      <td class="text-right" width="40%">@{{ total_discount }}</td>
							    </tr>
							    <tr class="text-info">
							      <td width="60%">Return Rmount</td>
							      <td class="text-right" width="40%">@{{ product_return }}</td>
							    </tr>
							    <tr class="text-info">
							      <td width="60%">Net Payable Amount</td>
							      <td class="text-right" width="40%">@{{ total_payable_amount }}</td>
							    </tr>
							    <tr class="text-success">
							      <td width="60%">Paid Amount</td>
							      <td class="text-right" width="40%">@{{ total_payments }}</td>
							    </tr>
							    <tr class="text-danger">
							      <td width="60%">Due Amount</td>
							      <td class="text-right" width="40%">@{{ remaining_amount }}</td>
							    </tr>
							  </thead>
							</table>

				        </div>
				        <!-- /.card-body -->
			        </div>
			        <div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">
							@if (isset($smsSetting->balance) && isset($smsSetting->sms_rate) && $smsSetting->balance < $smsSetting->sms_rate)
								<div class="alert alert-warning">
									Insufficient balance to send SMS. Please recharge your account.
								</div>
							@endif


							<div class="form-check">
								<label class="text-danger" for="send_sms">
									<input class="form-check-input" type="checkbox" id="send_sms" v-model="send_sms" name="send_sms" value="1">
									Send SMS
								</label>
							</div>
							</h3>
							<div class="card-tools">
								<label for="make_member" class="float-right text-green mb-0 mr-1"><input type="checkbox" name="make_member" id="make_member" @change="handleMakeMemberChange" :checked="make_member === 'Member'"> Make Member</label>
							</div>	
						</div>
						<div class="card-body p-2">
							
							<div class="bg-default">
								<table class="w-100">
									<tbody>
										<tr>
											<td><input type="text" class="form-control form-control-sm" @input="getCustomer()" :required="!customer_id" v-model="phone"  ref="phoneInput" placeholder="Phone *"></td>
											<td><input type="text" class="form-control form-control-sm" :required="!customer_id" v-model="name" ref="nameInput" placeholder="Name"></td>
											<div class="input-group d-none">
											    <select class="form-control select2" name="customer_id" id="customer_id" v-model="customer_id" ref="customerSelect" data-placeholder="Select a customer">
											    	<option value="0">-- Select customer --</option>
											    	
											        @foreach(App\Models\Customer::get() as $key=>$customer)
											            <option value="{{$customer->id}}">{{$customer->name}} ({{$customer->phone}})</option>
											        @endforeach
											    </select>
											</div>
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
						<div class="card-body p-2">
							<table class="w-100">
								<tbody>
									<tr>
										<td width="40%">Discount(%)</td>
										<td width="40%">Partial Less ({{ $website_info->currency }})</td>
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
							<h3 class="card-title">Cash Payment</h3>
							<div class="card-tools">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="partialPaymentToggle" aria-expanded="false" @click="toggleCollapsePartialPayment()">
						        	<label class="form-check-label text-danger text-bold" for="partialPaymentToggle">Partial Payment</label>

								</div>
						    </div>
						</div>
						<div class="card-body p-2">
							<table class="w-100">
								<tbody>
									<tr>
										<td width="40%">Payment ({{ $website_info->currency }})</td>
										<td width="40%">Cash A/C No</td>
									</tr>
									<tr>
										<td width="60%"><input type="text" class="form-control form-control-sm" name="cash_payment" id="cash_payment" placeholder="" v-model="cash_payment" @input="calculateTotal"></td>
										<td width="60%">
											<select class="form-control form-control-sm" name="cash_account_no_id" id="cash_account_no_id" v-model="cash_account_no_id">
												<option value="">-- Select cash --</option>
												@php
												    $cash_account_no_id = 0;
												@endphp
												@foreach(App\Models\BankAccount::where('store_id', Auth::user()->store_id)->where('account_type', 'cash')->get() as $key => $bank_account)
												@php
												    $cash_account_no_id = ($key == 0) ? $bank_account->id : $cash_account_no_id;
												@endphp
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
					                <h3 class="card-title">Card / Bank Payment</h3>
					            </div>
					            <div class="card-body p-2">
					                <table class="w-100">
					                    <tbody>
					                        <tr>
					                            <td width="40%">Payment ({{ $website_info->currency }})</td>
					                            <td width="60%"><input type="text" class="form-control form-control-sm" name="card_payment" id="card_payment" placeholder="" v-model="card_payment" @input="calculateTotal"></td>
					                        </tr>
					                        <tr>
					                            <td width="40%">A/C No</td>
					                            <td width="60%"><input type="text" class="form-control form-control-sm" name="card_number" id="card_number" v-model="card_number" placeholder=""></td>
					                        </tr>
					                        <tr>
					                            <td width="40%">Card Type</td>
					                            <td width="60%">
					                                <select class="form-control form-control-sm" name="card_type" id="card_type" v-model="card_type">
					                                    <option value="">-- Select card type --</option>
					                                    @php
														    $card_type = "Visa";
														@endphp

					                                    @foreach(App\Models\PaymentCardType::get() as $key => $card)
					                                    
					                                    <option value="{{$card->card_type}}">{{$card->card_type}}</option>
					                                    @endforeach
					                                </select>
					                            </td>
					                        </tr>
					                        <tr>
					                            <td width="40%">Account No</td>
					                            <td width="60%">
					                                <select class="form-control form-control-sm" name="card_account_no_id" id="card_account_no_id" v-model="card_account_no_id">
					                                    <option value="">-- Select account --</option>
					                                    @php
														    $card_account_no_id = '';
														@endphp

					                                    @foreach(App\Models\BankAccount::where('store_id', Auth::user()->store_id)->where('account_type', 'bank')->get() as $key => $bank_account)
					                                    @php
														    $card_account_no_id = ($key == 0) ? $bank_account->id : $card_account_no_id;
														@endphp
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
					                <h3 class="card-title">Mobile Payment</h3>
					            </div>
					            <div class="card-body p-2">
					                <table class="w-100">
					                    <tbody>
					                        <tr>
					                            <td width="40%">Payment ({{ $website_info->currency }})</td>
					                            <td width="60%"><input type="text" class="form-control form-control-sm" name="mobile_payment" id="mobile_payment" placeholder="" v-model="mobile_payment" @input="calculateTotal"></td>
					                        </tr>
					                            
					                        <tr>
					                            <td width="40%">Account No</td>
					                            <td width="60%">
					                                <select class="form-control form-control-sm" name="mobile_account_no_id" id="mobile_account_no_id" v-model="mobile_account_no_id">
					                                    <option value="">-- Select account --</option>
					                                    @php
														    $mobile_account_no_id = '';
														@endphp

					                                    @foreach(App\Models\BankAccount::where('store_id', Auth::user()->store_id)->where('account_type', 'mobile')->get() as $key => $bank_account)
					                                    @php
														    $mobile_account_no_id = ($key == 0) ? $bank_account->id : $mobile_account_no_id;
														@endphp
					                                    <option value="{{$bank_account->id}}">{{$bank_account->bank_name}} ({{$bank_account->account_no}})</option>
					                                    @endforeach
					                                </select>
					                            </td>
					                        </tr>
					                        <tr>
					                            <td width="40%">Sender No</td>
					                            <td width="60%"><input type="text" class="form-control form-control-sm" name="sender_no" id="sender_no" v-model="sender_no" placeholder=""></td>
					                        </tr>
					                        <tr>
					                            <td width="40%">TrxId</td>
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
			            <button type="button" class="btn btn-primary btn-md w-100" @click="submitForm">Submit</button>
			        </div>
			    </div>
		    </div>
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
	$(function () {
	    //Initialize Select2 Elements
	    $('.select2').select2()
	});
  var app = new Vue({
      el: "#app",
      data: {
      	sale_person_id: "{{ $sell_person_id }}",
        customer_id: "0",
        name: '',
        phone: '',
        make_member: '',
		send_sms: true,

        status: '',
      	product_id: '',

      	cash_payment: 0,
      	cash_account_no_id: "{{ $cash_account_no_id }}",

      	card_payment: 0,
      	card_number: 'xxxx xxxxxx xxxxx',
      	card_type: "{{ $card_type }}",
      	card_account_no_id: "{{ $card_account_no_id }}",

      	mobile_payment: 0,
      	sender_no: 'xxxxx-xxxxxx',
      	trx_no: 'xxxx',
      	mobile_account_no_id: "{{ $mobile_account_no_id }}",

      	discount: 0,
      	vat: 0,
      	less: 0,
      	total_price: 0,
      	total_discount: 0,
      	total_vat: 0,
        total_price_including_vat: 0,
        total_payable_amount: 0,
        total_payments: 0,
        product_return: 0,
        remaining_amount: 0,

      	carts: [],
      	html_invoice: '',
      	invoice_id: '',



		rows: [],
		suppliers: {!! json_encode($suppliers) !!},
		racks: {!! json_encode($racks) !!},
	    
      },
      methods: {
      	// Function to start saving all rows one by one
	    saveAllRows() {
		  if (this.rows.length) {
		    // Iterate backward to avoid index shifting issues when removing rows
		    for (let index = this.rows.length - 1; index >= 0; index--) {
		      try {
		        this.saveNewRow(index);
		        toastr.info(`Product ${index + 1} is being saved.`);
		      } catch (error) {
		        console.error(`Error saving row ${index + 1}:`, error);
		        toastr.error(`Failed to save Product ${index + 1}.`);
		      }
		    }
		  } else {
		    toastr.error("No rows to save.");
		  }
		},


      	addNewRow() {
	      const twoYearsLater = new Date();
	      twoYearsLater.setFullYear(twoYearsLater.getFullYear() + 2);
	      const defaultExpirationDate = twoYearsLater.toISOString().split('T')[0];

	      // Calculate alert date (10 days before expiration date)
	      const alertDateObj = new Date(twoYearsLater);
	      alertDateObj.setDate(alertDateObj.getDate() - 10);
	      const alertDate = alertDateObj.toISOString().split('T')[0];

	      this.rows.unshift({
	        product_name: ``,
	        supplier_id: '1',
	        store_id: "{{Auth::user()->store_id}}",
	        rack_id: '',
	        qty: '1',
	        purchase_price: '',
	        sell_price: '',
	        expiration_date: defaultExpirationDate,
	        alert_date: alertDate,
	      });
	    },
	    saveNewRow(index) {
		    const row = this.rows[index]; // Get the row object

		    axios.post("{{ route('product-direct-store-sell') }}", row)
		      .then(response => {
		        // Show success toast message
		        toastr.success(response.data.message, 'Success');
		        
		        // Remove the row from the list
		        this.rows.splice(index, 1);

		        // Update the cart and recalculate totals
		        app.cartList();
		        app.calculateTotal();
		      })
		      .catch(error => {
		        // Show error toast message
		        toastr.error(error.response.data.message, 'Error');
		      });
		},
		

	    cancelNewRow(index) {
	      // Remove the row without saving
	      this.rows.splice(index, 1);
	    },
  
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
			const article = { id: this.product_id, status: this.status };
			axios.post("{{ route('api.add-to-cart') }}", article)
			.then(function (response) {
				if(response.data=='insufficient'){
					toastr.warning('Stock limited');
					// console.log('stock limited');
				}else if(response.data=='product_not_found'){
					toastr.warning('Product not found');
					// console.log('Product not found');
				}else{
					// app.carts = response.data;
					app.product_id = '';
					app.status = '';
					app.cartList();
					app.calculateTotal();
				}
			})
			.catch(function (error) {
			    toastr.error("Error adding to cart:", error);
			})
			.finally(function() {
			    // app.isLoading = true;
			});
		},
		updateField(id, field, event) {
	      let value = event.target.innerText;

	      const article = { id, field, value };
	      if(value>=0 && value !=''){
	      	axios.post("{{ route('api.update-to-cart') }}", article)
			.then(function (response) {
				if(response.data=='insufficient'){
					toastr.warning('Stock limited');
					// console.log('stock limited');
				}else{
					// app.carts = response.data;
					app.cartList();
					app.calculateTotal();
				}
			});
	      }
	    },
      	removeCart: function(id){
      		window.removeToCartUrl = "{{ route('api.remove-to-cart', ['id' => '__id__']) }}";

			axios.get(window.removeToCartUrl.replace('__id__', id))
			.then(function (response) {
				// app.carts = response.data;
				app.cartList();
				app.calculateTotal();
			});
      	},
      	cartList: function(){
			axios.get("{{ route('api.cart-list') }}")
			.then(function (response) {
				app.carts = response.data;
				app.calculateTotal();
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
						app.discount = response.data['discount'];
						app.calculateTotal();
					}
				}else{
					app.customer_id = '0';
					app.name = '';
					app.make_member = '';
				}
			});
			app.calculateTotal();
      	},
        async submitForm() {
            // Validation flags
            let valid = true;

            // Check if sale_person_id is selected
            if (!this.sale_person_id) {
                toastr.warning('Please select a sale person.');
                this.$refs.salePersonSelect?.focus(); // Optional chaining
                valid = false;
            }else

            // Check customer fields if no customer is selected
            if (!this.customer_id) {
                if (!this.name) {
	                toastr.warning('Please enter the full name.');
                    this.$refs.nameInput.focus(); // Focus on name input
                    valid = false;
                    return; // Exit on first error
                } else if (!this.phone) {
	                toastr.warning('Please enter the phone number.');
                    this.$refs.phoneInput.focus(); // Focus on phone input
                    valid = false;
                    return; // Exit on first error
                }
            }

            // If everything is valid, proceed with form submission
            if (valid) {
                // Prepare the data for submission
                const formData = {
                    sale_person_id: this.sale_person_id,
                    customer_id: this.customer_id,
                    name: this.name,
                    phone: this.phone,
                    make_member: this.make_member,
					send_sms: this.send_sms,

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
                    // Send a POST request to your server
                   const response = await axios.post("{{ route('invoices.store') }}", formData);

                    this.invoice_id = response.data;
                    this.searchInvoice();
                    
                    // Handle success response
                    // console.log('Form submitted successfully:', response.data);
                    
	                toastr.success('Submitted successfully!');
                    // Optionally reset the form fields after submission
                    this.resetForm();
                } catch (error) {
                    // Handle error response
                    // console.error('There was an error submitting the form:', error);
                    toastr.warning('An error occurred while submitting the form. Please try again.');
                }
            }
        },
        resetForm() {
			// Reset all form fields to their initial values
		    this.sale_person_id = "{{ ($sell_person_id)?$sell_person_id:0 }}";
        	this.customer_id = "0";
		    this.name = '';
		    this.phone = '';
		    this.make_member = '';
		    this.send_sms = true;


		    this.cash_payment = 0;
	      	this.cash_account_no_id = "{{ $cash_account_no_id }}";

	      	this.card_payment = 0;
	      	this.card_number = 'xxxx xxxxxx xxxxx';
	      	this.card_type = "{{ $card_type }}";
	      	this.card_account_no_id = "{{{ $card_account_no_id }}}";

	      	this.mobile_payment = 0;
	      	this.sender_no = 'xxxxx-xxxxxx';
	      	this.trx_no = 'xxxx';
	      	this.mobile_account_no_id = "{{ $mobile_account_no_id }}";

		    this.discount = 0;
		    this.vat = 0;
		    this.less = 0;
		    this.product_return = 0;
		    this.total_price = 0;
		    this.total_discount = 0;
		    this.total_vat = 0;
		    this.total_price_including_vat = 0;
		    this.total_payable_amount = 0;
		    this.total_payments = 0;
		    this.remaining_amount = 0;

		    // Optionally reset the carts or set them to a fresh state
		    this.carts = []; // Reset to initial PHP state
		},
		toggleCollapsePartialPayment() {
	        const partialPaymentCollapse = document.getElementById('partialPaymentCollapse');
	        const checkbox = document.getElementById('partialPaymentToggle');
	        if (checkbox.checked) {
	            $(partialPaymentCollapse).collapse('show');
	        } else {
	            $(partialPaymentCollapse).collapse('hide');
	        }
	    }
      },
      mounted() {
        this.cartList();
        const self = this;

	    // Initialize Select2 and bind change events to update Vue model
	    $('.select2').select2().on('change', function () {
	      const value = $(this).val();
	      self.product_id = value; // Update the Vue model
	    });
      },
	  updated() {
	    // Reinitialize Select2 after Vue DOM updates
	    $('.select2').select2();
	  },
  });
</script>
@endsection