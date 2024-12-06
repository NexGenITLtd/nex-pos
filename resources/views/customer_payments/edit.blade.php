@extends('layouts.app')
@section('title', 'Customer Payment Edit')
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
	    padding: 2px;
	}
	.card-header {
		padding: 5px 1.25rem;
	}
	.card-body{
		padding: 5px 1.25rem;
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
        <h1>Customer Payment Edit</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Customer Payment Edit</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  	<form action="{{route('customer-payments.update',$customer_payment->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('patch')
    <div class="row">
	    <div class="col-md-12">
	        <div class="card card-primary">
	          <div class="card-body">
	            <div class="row">
	              
	              <div class="col-md-6">
	              	<div class="form-group">
	              		<label for="invoice_id">Invoice ID</label>
		              	<div class="input-group">
		              		<input type="text" id="invoice_id" v-model="invoice_id" name="invoice_id" class="form-control" @keyup.enter="searchInvoice();jsonInvoice();" placeholder="Scan or type invoice no then enter" ref="invoiceIdInput" required disabled="true">
			                <!-- <div class="input-group-append" @click='searchInvoice();jsonInvoice();'>
			                	<span class="input-group-text" id="basic-addon2">Search</span>
			                </div> -->
		              	</div>
	              	</div>
	              	<div class="card card-primary">
						<div class="card-header">
						<h3 class="card-title">Payment summery</h3>
						</div>
				        <div class="card-body">
				          	<table class="table">
				          		<thead>
				          			<tr class="text-info">
				          				<th width="60%">Total payable amount</th>
				          				<th width="40%">@{{ total_payable_amount }}</th>
				          			</tr>
				          			<tr class="text-green">
				          				<th width="60%">Paid amount</th>
				          				<th width="40%">@{{ total_payments }}</th>
				          			</tr>
				          			<tr class="text-red">
				          				<th width="60%">Due amount</th>
				          				<th width="40%">@{{ remaining_amount }}</th>
				          			</tr>
				          		</thead>
				          	</table>
				        </div>
				        <!-- /.card-body -->
			        </div>
	              </div>
	              <div class="col-md-6">
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
					<div class="card-body" id="cash_payment">
						<table class="w-100">
							<tbody>
								<tr>
									<td width="40%">Cash payment</td>
									<td width="60%"><input type="text" class="form-control" name="cash_payment" id="cash_payment" placeholder="" v-model="cash_payment" @input="calculateTotal"></td>
								</tr>
								
								<tr>
									<td width="40%">Cash</td>
									<td width="60%">
										<select class="form-control" name="cash_account_no_id" id="cash_account_no_id" v-model="cash_account_no_id">
											<option value="">Select one</option>
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
				        <div class="card card-primary" id="card_payment">
				            <div class="card-header">
				                <h3 class="card-title">Card/Bank</h3>
				            </div>
				            <div class="card-body">
				                <table class="w-100">
				                    <tbody>
				                        <tr>
				                            <td width="40%">Card / AC payment</td>
				                            <td width="60%"><input type="text" class="form-control" name="card_payment" id="card_payment" placeholder="" v-model="card_payment" @input="calculateTotal"></td>
				                        </tr>
				                        <tr>
				                            <td width="40%">Card / AC number</td>
				                            <td width="60%"><input type="text" class="form-control" name="card_number" id="card_number" v-model="card_number" placeholder=""></td>
				                        </tr>
				                        <tr>
				                            <td width="40%">Card type</td>
				                            <td width="60%">
				                                <select class="form-control" name="card_type" id="card_type" v-model="card_type">
				                                    <option value="">Select one</option>
				                                    @foreach(App\Models\PaymentCardType::get() as $card_type)
				                                    <option value="{{$card_type->card_type}}">{{$card_type->card_type}}</option>
				                                    @endforeach
				                                </select>
				                            </td>
				                        </tr>
				                        <tr>
				                            <td width="40%">Account</td>
				                            <td width="60%">
				                                <select class="form-control" name="card_account_no_id" id="card_account_no_id" v-model="card_account_no_id">
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
				        <div class="card card-primary mt-3" id="mobile_payment">
				            <div class="card-header">
				                <h3 class="card-title">Mobile Gateway</h3>
				            </div>
				            <div class="card-body">
				                <table class="w-100">
				                    <tbody>
				                        <tr>
				                            <td width="40%">Mobile payment</td>
				                            <td width="60%"><input type="text" class="form-control" name="mobile_payment" id="mobile_payment" placeholder="" v-model="mobile_payment" @input="calculateTotal"></td>
				                        </tr>
				                            
				                        <tr>
				                            <td width="40%">Account</td>
				                            <td width="60%">
				                                <select class="form-control" name="mobile_account_no_id" id="mobile_account_no_id" v-model="mobile_account_no_id">
				                                    <option value="">Select one</option>
				                                    @foreach(App\Models\BankAccount::where('store_id', Auth::user()->store_id)->where('account_type', 'mobile')->get() as $bank_account)
				                                    <option value="{{$bank_account->id}}">{{$bank_account->bank_name}} ({{$bank_account->account_no}})</option>
				                                    @endforeach
				                                </select>
				                            </td>
				                        </tr>
				                        <tr>
				                            <td width="40%">Sender no</td>
				                            <td width="60%"><input type="text" class="form-control" name="sender_no" id="sender_no" v-model="sender_no" placeholder=""></td>
				                        </tr>
				                        <tr>
				                            <td width="40%">Trx no</td>
				                            <td width="60%"><input type="text" class="form-control" name="trx_no" id="trx_no" v-model="trx_no" placeholder=""></td>
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
	              </div>
	            </div>
	          </div>
	          <!-- /.card-body -->
	        </div>
	        
	        <span id="invoic_div">
	        	<div v-html="html_invoice"></div>
	        </span>
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
          cash_payment: 0,
          cash_account_no_id: '',
          card_payment: 0,
          card_number: '',
          card_type: '',
          card_account_no_id: '',
          mobile_payment: 0,
          sender_no: '',
          trx_no: '',
          mobile_account_no_id: '',
          due_amount: <?php echo $customer_payment->amount; ?>,
          total_payable_amount: <?php echo $customer_payment->amount; ?>,
          total_payments: 0,
          remaining_amount: 0,
          html_invoice: '',
          invoice_id: <?php echo $customer_payment->invoice_id; ?>,
      },
      methods: {
          jsonInvoice() {
              window.invoiceUrl = "{{ route('json.invoice', ['id' => '__invoice_id__']) }}";
              axios.get("/json/invoice/" + this.invoice_id)
                  .then(function (response) {
                      if (response.data && Object.keys(response.data).length > 0) {
                          app.due_amount = response.data['due_amount'];
                          app.total_payable_amount = response.data['due_amount'];
                          app.calculateTotal();
                      } else {
                          toastr.warning('No invoice data found for the provided ID.');
                      }
                  });
          },
          
          searchInvoice() {
              window.invoiceShowForPrintUrl = "{{ route('invoice_show_for_print', ['id' => '__invoice_id__']) }}";
              axios.get(window.invoiceShowForPrintUrl.replace('__invoice_id__', this.invoice_id))
                  .then(function (response) {
                      if (response.data && Object.keys(response.data).length > 0) {
                          app.html_invoice = response.data;
                      } else {
                          toastr.warning('No invoice data found for the provided ID.');
                          app.html_invoice = '';
                      }
                  })
                  .catch(function (error) {
                      if (error.response) {
                          if (error.response.status === 404) {
                              toastr.warning('Error 404: Invoice not found. Please check the invoice ID and try again.');
                          } else {
                              toastr.warning('Error: Unable to retrieve the invoice. Please try again later.');
                          }
                          app.html_invoice = '';
                      } else if (error.request) {
                          toastr.warning('Error: No response from the server. Please try again later.');
                          app.html_invoice = '';
                      } else {
                          toastr.warning('Error: An unexpected error occurred. Please try again.');
                          app.html_invoice = '';
                      }
                  });
          },
          
          calculateTotal() {
              let cashPayment = parseFloat(this.cash_payment) || 0;
              let cardPayment = parseFloat(this.card_payment) || 0;
              let mobilePayment = parseFloat(this.mobile_payment) || 0;

              let totalPayments = cashPayment + cardPayment + mobilePayment;

              let remainingAmount = this.due_amount - totalPayments;
              this.total_payments = totalPayments.toFixed(2);
              this.remaining_amount = remainingAmount.toFixed(2);
          },

          async submitForm() {
              let valid = true;

              if (!this.invoice_id) {
                  toastr.warning('Please enter invoice no');
                  this.$refs.invoiceIdInput?.focus(); // Ensure this ref exists in your template
                  valid = false;
              }

              if (valid) {
                  const formData = {
                      invoice_id: this.invoice_id,
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
                      total_payable_amount: this.total_payable_amount,
                      total_payments: this.total_payments,
                      remaining_amount: this.remaining_amount
                  };

                  try {
                      const response = await axios.patch(`{{ route('customer-payments.update', $customer_payment->id) }}`, formData);
                      toastr.success('Form submitted successfully');
                      this.searchInvoice();
                  } catch (error) {
                      toastr.error('An error occurred while submitting the form. Please try again.');
                  }
              }
          },

          resetForm() {
              this.invoice_id = '';
              this.cash_payment = 0;
              this.cash_account_no_id = '';
              this.card_payment = 0;
              this.card_number = '';
              this.card_type = '';
              this.card_account_no_id = '';
              this.mobile_payment = 0;
              this.sender_no = '';
              this.trx_no = '';
              this.mobile_account_no_id = '';
              this.total_payable_amount = 0;
              this.total_payments = 0;
              this.remaining_amount = 0;
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

          setPaymentAmounts(payment) {
              if (payment.payment_type === 'cash_payment') {
                  this.cash_payment = parseFloat(payment.amount);
                  this.cash_account_no_id = payment.bank_account_id;
              } else if (payment.payment_type === 'card_payment') {
                  this.card_payment = parseFloat(payment.amount);
                  this.card_account_no_id = payment.bank_account_id;
                  this.card_number = payment.payment_from_account_no;
                  this.card_type = payment.payment_trx_note;
                  const checkbox = document.getElementById('partialPaymentToggle');
                  checkbox.checked = true;
                  const partialPaymentCollapse = document.getElementById('partialPaymentCollapse');
                  $(partialPaymentCollapse).collapse('show');
              } else if (payment.payment_type === 'mobile_payment') {
                  this.mobile_payment = parseFloat(payment.amount);
                  this.mobile_account_no_id = payment.bank_account_id;
                  this.sender_no = payment.payment_from_account_no;
                  this.trx_no = payment.payment_trx_note;
              }
          }
      },

      mounted() {
          this.setPaymentAmounts(<?php echo json_encode($customer_payment); ?>); // Ensure this is a valid JavaScript object
          this.searchInvoice();
          this.jsonInvoice();
      }
  });
</script>

@endsection