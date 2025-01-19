@extends('layouts.app')
@section('title', 'Daily Report')
@section('link')
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="{{ asset('backend/') }}/plugins/fontawesome-free/css/all.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('backend/') }}/dist/css/adminlte.min.css">
<style type="text/css">
	.report-maker-table th, .report-maker-table td{
		padding: 4px 6px;
	}
</style>
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Daily Report</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Daily Report</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid" id="dailyReports">
      <!-- Info boxes -->
      <div class="row">
          <div class="col-md-12">
              <div class="card">
                  <div class="card-header">
                        <h5 class="card-title">{{ $cardHeader }}</h5>
                        <div class="card-tools no-print">
                            <form method="GET" action="" class="form-inline">
							    <div class="form-group mx-sm-3 mb-2">
							        <label for="date" class="sr-only">Date:</label>
							        <input 
							            type="date" 
							            name="date" 
							            id="date" 
							            class="form-control form-control-sm mr-2" 
							            value="{{ request('date', \Carbon\Carbon::today()->toDateString()) }}" 
							            placeholder="Date">
							    </div>

							    <div class="form-group mx-sm-3 mb-2">
							        <label for="store_id" class="sr-only">Filter by Store:</label>
							        <select name="store_id" id="store_id" class="form-control form-control-sm mr-2">
							            @foreach($stores as $store)
										    <option value="{{ $store->id }}" 
										        {{ request('store_id') == $store->id || $store_id == $store->id ? 'selected' : '' }}>
										        {{ $store->name }}
										    </option>
										@endforeach

							        </select>
							    </div>

							    <button type="submit" class="btn btn-primary btn-sm mb-2">Apply Filter</button>
							    <a href="{{ route('dailyreports.index') }}" class="btn btn-success btn-sm ml-2 mb-2 float-right"><i class="fa fa-angle-double-left"></i> Back</a>
							</form>

                        </div>
                    </div>
                     @if($date && $store_id)
                  <div class="card-body">
                      <div class="row">
                          <div class="col-md-12">
                          	@if ($errors->any())
							    <div class="alert alert-danger">
							        <ul>
							            @foreach ($errors->all() as $error)
							                <li>{{ $error }}</li>
							            @endforeach
							        </ul>
							    </div>
							@endif

                          	<form action="{{ route('dailyreports.store') }}" method="POST" class="p-4 bg-light shadow-lg rounded">
							    @csrf
							    <table class="table table-bordered report-maker-table">

							        <!-- Hidden Inputs -->
							        <tr class="d-none">
							            <td>Date</td>
							            <td>
							                <input type="date" class="form-control" name="date" 
							                       value="{{ old('date', $date) }}" readonly />
							                <input type="hidden" class="form-control" name="store_id" 
							                       value="{{ old('store_id', $store_id) }}" readonly />
							            </td>
							        </tr>

							        <!-- Summary Section -->
							        <tr><th colspan="2" class="text-center bg-success text-white">Summary</th></tr>
							        <tr>
							            <td>Total Invoices</td>
							            <td>
							                <input type="number" class="form-control text-center" name="total_invoices" 
							                       v-model="total_invoices" 
							                       value="{{ old('total_invoices', $total_invoices) }}" readonly />
							            </td>
							        </tr>
							        <tr>
							            <td>Total Sales</td>
							            <td>
							                <input type="number" class="form-control text-center" name="total_sales" 
							                       v-model="total_sales" 
							                       value="{{ old('total_sales', $total_sales) }}" 
							                       placeholder="{{ $website_info->currency }}" readonly />
							            </td>
							        </tr>
							        @can('show profit')
									<tr>
										<td>Total Profit</td>
										<td>
											<input type="number" class="form-control text-center" name="total_profit" 
												v-model="total_profit" 
												value="{{ old('total_profit', $total_profit) }}" 
												placeholder="{{ $website_info->currency }}" readonly />
										</td>
									</tr>
									@endcan

									@cannot('show profit')
									<input type="hidden" name="total_profit" v-model="total_profit" 
										value="{{ old('total_profit', $total_profit) }}" />
									@endcannot

							        <!-- Cash Management Section -->
							        <tr><th colspan="2" class="text-center bg-warning text-dark">Cash Management</th></tr>
							        <tr>
							            <td>Previous Day Cash</td>
							            <td>
							                <input type="number" class="form-control text-center" name="previous_cash_in_hand" 
							                       @input="calculation()" 
							                       v-model="previous_cash_in_hand" 
							                       value="{{ old('previous_cash_in_hand', $previous_cash_in_hand) }}" 
							                       placeholder="{{ $website_info->currency }}" step="0.01" />
							            </td>
							        </tr>
							        <tr>
							            <td>Extra Cash From Owner</td>
							            <td>
							                <input type="number" class="form-control text-center" name="extra_cash" 
							                       @input="calculation()" 
							                       v-model="extra_cash" 
							                       value="{{ old('extra_cash', $extra_cash) }}" 
							                       placeholder="{{ $website_info->currency }}" step="0.01" />
							            </td>
							        </tr>

							        <!-- Returns and Purchases -->
							        <tr><th colspan="2" class="text-center bg-info text-white">Returns and Purchases</th></tr>
							        <tr>
							            <td>Total Sell Return</td>
							            <td>
							                <input type="number" class="form-control text-center" name="total_return_sell" 
							                       v-model="total_return_sell" 
							                       value="{{ old('total_return_sell', $total_return_sell) }}" 
							                       placeholder="{{ $website_info->currency }}" readonly />
							            </td>
							        </tr>
							        @can('show profit')
							        <tr>
							            <td>Total Purchase Price</td>
							            <td>
							                <input type="number" class="form-control text-center" name="total_purchase_price" 
							                       v-model="total_purchase_price" 
							                       value="{{ old('total_purchase_price', $total_purchase_price) }}" 
							                       placeholder="{{ $website_info->currency }}" readonly />
							            </td>
							        </tr>
							        @endcan

									@cannot('show profit')
									<input type="hidden" name="total_purchase_price" 
							                       v-model="total_purchase_price" 
							                       value="{{ old('total_purchase_price', $total_purchase_price) }}" />
									@endcannot

							        <!-- Expense and Payments -->
							        <tr><th colspan="2" class="text-center bg-danger text-white">Expenses and Payments</th></tr>
							        <tr>
							            <td>Total Supplier Payments</td>
							            <td>
							                <input type="number" class="form-control text-center" name="total_supplier_payment" 
							                       v-model="total_supplier_payment" 
							                       value="{{ old('total_supplier_payment', $total_supplier_payment) }}" 
							                       placeholder="{{ $website_info->currency }}" readonly />
							            </td>
							        </tr>
							        <tr>
							            <td>Total Expense</td>
							            <td>
							                <input type="number" class="form-control text-center" name="total_expense" 
							                       v-model="total_expense" 
							                       value="{{ old('total_expense', $total_expense) }}" 
							                       placeholder="{{ $website_info->currency }}" readonly />
							            </td>
							        </tr>
							        <tr>
							            <td>Extra Expense</td>
							            <td>
							                <input type="number" class="form-control text-center" name="extra_expense" 
							                       @input="calculation()" 
							                       v-model="extra_expense" 
							                       value="{{ old('extra_expense', 0) }}" 
							                       placeholder="{{ $website_info->currency }}" step="0.01" />
							            </td>
							        </tr>
							        <tr>
							            <td>Total Salary</td>
							            <td>
							                <input type="number" class="form-control text-center" name="total_salary" 
							                       v-model="total_salary" 
							                       value="{{ old('total_salary', $total_salary) }}" 
							                       placeholder="{{ $website_info->currency }}" readonly />
							            </td>
							        </tr>

							        <!-- Deposits -->
							        <tr><th colspan="2" class="text-center bg-success text-white">Deposits</th></tr>
							        <tr>
							            <td>Owner Cash & Bank Deposit </td>
							            <td>
							                <input type="number" class="form-control text-center" name="owner_deposit" 
							                       @input="calculation()" 
							                       v-model="owner_deposit" 
							                       value="{{ old('owner_deposit', $owner_deposit) }}" 
							                       placeholder="{{ $website_info->currency }}" step="0.01" readonly />
							            </td>
							        </tr>
							        <tr class="d-none">
							            <td>Owner Bank Deposit</td>
							            <td>
							                <input type="number" class="form-control text-center" name="bank_deposit" 
							                       @input="calculation()" 
							                       v-model="bank_deposit" 
							                       value="{{ old('bank_deposit', 0) }}" 
							                       placeholder="{{ $website_info->currency }}" step="0.01" readonly/>
							            </td>
							        </tr>

							        <!-- Final Calculations Section -->
							        <tr><th colspan="2" class="text-center bg-danger text-white">Final Calculations</th></tr>
							        <tr>
							            <td>Total Due</td>
							            <td>
							                <input type="number" class="form-control text-center" name="total_due" 
							                       v-model="total_due" 
							                       value="{{ old('total_due', $total_due) }}" 
							                       placeholder="{{ $website_info->currency }}" readonly />
							            </td>
							        </tr>
							        @can('show profit')
							        <tr>
							            <td>Net Profit</td>
							            <td>
							                <input type="number" class="form-control text-center" name="net_profit" 
							                       @input="calculation()" 
							                       v-model="net_profit" 
							                       value="{{ old('net_profit', 0) }}" 
							                       placeholder="{{ $website_info->currency }}" readonly />
							            </td>
							        </tr>
							        @endcan

									@cannot('show profit')
									<input type="hidden" name="net_profit" 
							                       @input="calculation()" 
							                       v-model="net_profit" 
							                       value="{{ old('net_profit', 0) }}" />
									@endcannot

							        <!-- Cash in Hand for Next Day Section -->
							        <tr><th colspan="2" class="text-center bg-info text-white">Cash in Hand for Next Day</th></tr>
							        <tr>
							            <td>Cash in Hand</td>
							            <td>
							                <input type="number" class="form-control text-center" name="cash_in_hand" 
							                       v-model="cash_in_hand" 
							                       value="0" 
							                       placeholder="{{ $website_info->currency }}" readonly />
							            </td>
							        </tr>
							    </table>
							    <button type="submit" class="btn btn-lg btn-success w-100 no-print">Save Report</button>
							</form>

							<style>
							    .table th, .table td {
							        vertical-align: middle;
							        text-align: center;
							        font-weight: 16px;
							        font-weight: bold;
							    }
							    .table input {
							        text-align: center;
							        font-weight: bold;
							    }
							    .table input[readonly] {
							        background-color: #f1f1f1;
							    }
							    .form-control {
							        box-shadow: none;
							        border-radius: 10px;
							    }
							    .btn {
							        font-size: 18px;
							        font-weight: bold;
							    }
							    .table th {
							        font-size: 16px;
							        font-weight: bold;
							    }
							</style>



                          </div>
                      </div>
                  </div>
                  @endif
              </div>
          </div>
          <!-- /.col -->
      </div>
      <!-- /.row -->
    </div><!--/. container-fluid -->
</section>
<!-- /.content -->

@section('script')
<!-- jQuery -->
<script src="{{ asset('backend/') }}/plugins/jquery/jquery.min.js"></script>
<!-- vue -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.js"></script>
<!-- Bootstrap -->
<script src="{{ asset('backend/') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="{{ asset('backend/') }}/dist/js/adminlte.js"></script>
<script>
	var app = new Vue({
  el: "#app",
  data: {
    date: "{{ $date }}",
    store_id: "{{ $store_id }}",
    total_invoices: "{{ $total_invoices }}",
    previous_cash_in_hand: "{{ $previous_cash_in_hand }}",
    extra_cash: 0,
    total_sales: "{{ $total_sales }}",
    total_return_sell: "{{ $total_return_sell }}",
    total_purchase_price: "{{ $total_purchase_price }}",
    total_profit: "{{ $total_profit }}",
    total_due: "{{ $total_due }}",
    total_supplier_payment: "{{ $total_supplier_payment }}",
    total_expense: "{{ $total_expense }}",
    total_salary: "{{ $total_salary }}",
    extra_expense: 0,
    owner_deposit: 0,
    bank_deposit: 0,
    cash_in_hand: 0,
    net_profit: 0,
  },
  methods: {
    calculation() {
        // Calculate total cash in hand based on input values
        let total_cash_in_hand = 
            parseFloat(this.previous_cash_in_hand || 0) +
            parseFloat(this.extra_cash || 0) +
            parseFloat(this.total_sales || 0) -
            (parseFloat(this.total_supplier_payment || 0) +
            parseFloat(this.total_expense || 0) +
            parseFloat(this.extra_expense || 0) +
            parseFloat(this.total_salary || 0) +
            parseFloat(this.owner_deposit || 0) +
            parseFloat(this.bank_deposit || 0));

        this.cash_in_hand = total_cash_in_hand.toFixed(2); // Update the cash in hand value

        // Calculate net profit: Total profit minus extra expenses, deposits, and additional deductions
        let total_expenses = 
            parseFloat(this.total_expense || 0) +
            parseFloat(this.extra_expense || 0) +
            parseFloat(this.owner_deposit || 0) +
            parseFloat(this.bank_deposit || 0);

        this.net_profit = (
            parseFloat(this.total_profit || 0)
        ).toFixed(2);
    },
},
mounted() {
    this.calculation(); // Perform initial calculation when component is mounted
},
});

</script>
@endsection
@endsection
