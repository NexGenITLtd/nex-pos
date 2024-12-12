@extends('layouts.app')
@section('title', 'Create Sms')
@section('link')
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('backend/')}}/plugins/fontawesome-free/css/all.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('backend/')}}/dist/css/adminlte.min.css">
@endsection
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Create Sms</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Create Sms</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Create SMS</h1>
                    <div class="card-tools"></div>
                </div>
                <div class="card-body">
                @include('partials.alerts')
                <form action="{{ route('sms.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="store_id">Store</label>
                        <select name="store_id" id="store_id" class="form-control" required>
                            <option value="">Select Store</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                        @error('store_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="store_balance">Store Balance</label>
                        <input type="text" id="store_balance" class="form-control" value="0" readonly>
                    </div>

                    <div class="form-group">
                        <label for="all_customers">
                            <input type="checkbox" id="all_customers" name="all_customers" value="1">
                            Send to All Customers
                        </label>

                        <label for="user_data">
                            <input type="checkbox" id="user_data" name="user_data" value="1">
                            Send to All User Data
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="recipient">Recipient</label>
                        <textarea name="recipient" id="recipient" class="form-control" rows="3" placeholder="Enter recipient phone number(s)" required></textarea>
                        @error('recipient')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea name="message" id="message" class="form-control" rows="5" placeholder="Enter your message" required></textarea>
                        @error('message')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="type">Message Type</label>
                        <select name="type" id="type" class="form-control">
                            <option value="generic">Generic</option>
                            <option value="promotion">Promotion</option>
                            <option value="alert">Alert</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Send SMS</button>
                </form>

            

                <script>
                    document.getElementById('all_customers').addEventListener('change', function () {
                        const recipientField = document.getElementById('recipient');

                        if (this.checked) {
                            // Fetch customer numbers and append them to the recipient field
                            fetch("{{ route('sms.customers') }}")
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Failed to fetch customer numbers.');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success && Array.isArray(data.customers)) {
                                        const currentRecipients = recipientField.value.trim();
                                        const newRecipients = data.customers.join(', ');

                                        // Avoid duplication if numbers already exist
                                        recipientField.value = currentRecipients
                                            ? `${currentRecipients}, ${newRecipients}`
                                            : newRecipients;
                                    } else {
                                        throw new Error(data.message || 'Failed to fetch customer numbers.');
                                    }
                                })
                                .catch(error => {
                                    toastr.error(error.message || 'An unexpected error occurred.');
                                });
                        } else {
                            // Clear only the fetched numbers when "All Customers" is unchecked
                            const currentRecipients = recipientField.value.split(',').map(num => num.trim());
                            fetch("{{ route('sms.customers') }}")
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success && Array.isArray(data.customers)) {
                                        const fetchedNumbers = data.customers.map(num => num.trim());
                                        const remainingRecipients = currentRecipients.filter(num => !fetchedNumbers.includes(num));
                                        recipientField.value = remainingRecipients.join(', ');
                                    }
                                })
                                .catch(() => {
                                    // On error, we leave the field unchanged
                                });
                        }
                    });
                    document.getElementById('user_data').addEventListener('change', function () {
                        const recipientField = document.getElementById('recipient');

                        if (this.checked) {
                            // Fetch customer numbers and append them to the recipient field
                            fetch("{{ route('sms.user-data') }}")
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Failed to fetch customer numbers.');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success && Array.isArray(data.user_data)) {
                                        const currentRecipients = recipientField.value.trim();
                                        const newRecipients = data.user_data.join(', ');

                                        // Avoid duplication if numbers already exist
                                        recipientField.value = currentRecipients
                                            ? `${currentRecipients}, ${newRecipients}`
                                            : newRecipients;
                                    } else {
                                        throw new Error(data.message || 'Failed to fetch customer numbers.');
                                    }
                                })
                                .catch(error => {
                                    toastr.error(error.message || 'An unexpected error occurred.');
                                });
                        } else {
                            // Clear only the fetched numbers when "All Customers" is unchecked
                            const currentRecipients = recipientField.value.split(',').map(num => num.trim());
                            fetch("{{ route('sms.user-data') }}")
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success && Array.isArray(data.user_data)) {
                                        const fetchedNumbers = data.user_data.map(num => num.trim());
                                        const remainingRecipients = currentRecipients.filter(num => !fetchedNumbers.includes(num));
                                        recipientField.value = remainingRecipients.join(', ');
                                    }
                                })
                                .catch(() => {
                                    // On error, we leave the field unchanged
                                });
                        }
                    });


                    document.getElementById('store_id').addEventListener('change', function () {
                        const storeId = this.value;
                        const balanceField = document.getElementById('store_balance');

                        if (storeId) {
                            // Fetch store balance using the store ID
                            fetch(`/stores/${storeId}/balance`)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Failed to fetch store balance.');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        balanceField.value = data.balance; // Display the store balance
                                    } else {
                                        balanceField.value = 0;
                                        toastr.error('Could not retrieve the store balance.');
                                    }
                                })
                                .catch(() => {
                                    balanceField.value = 0;
                                    toastr.error('An error occurred while fetching store balance.');
                                });
                        } else {
                            balanceField.value = 0; // Reset balance if no store is selected
                        }
                    });

                </script>


        </div>
            </div>
        </div>
    </div>
</div>
</section>
<!-- /.content -->
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