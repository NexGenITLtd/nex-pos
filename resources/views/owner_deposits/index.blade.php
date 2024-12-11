@extends('layouts.app')
@section('title', 'Owner Deposits')
@section('link')
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('backend/')}}/plugins/fontawesome-free/css/all.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="{{asset('backend/')}}/ionicons/2.0.1/css/ionicons.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('backend/')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('backend/')}}/dist/css/adminlte.min.css">
<!-- Google Font: Source Sans Pro -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
@endsection
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Owner Deposits</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Owner Deposits</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          
          <div class="card-header">
            <h5 class="card-title">{{ $cardHeader??'Owner Deposits' }}</h5>
            <!-- Settings Icon -->
            <span  title="Filter" id="settingsIcon" class=" btn-tool float-right ml-2 " style="font-size: 1.2rem; transition: transform 0.3s;">
                <i class="fas fa-cog"></i>
            </span>
            <!-- Card Tools Section -->
            <div class="card-tools no-print" id="settingsPanel" style="display: none;">
              <form method="GET" action="{{ route('owner-deposits.index') }}" class="form-inline mb-4" id="filterForm">
                  <div class="form-group mx-sm-3 mb-2">
                      <label for="date_filter" class="sr-only">Filter by Date:</label>
                      <select name="date_filter" id="date_filter" class="form-control form-control-sm mr-2">
                          <option value="">-- Select Filter --</option>
                          <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                          <option value="previous_day" {{ request('date_filter') == 'previous_day' ? 'selected' : '' }}>Previous Day</option>
                          <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                          <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                          <option value="this_year" {{ request('date_filter') == 'this_year' ? 'selected' : '' }}>This Year</option>
                          <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                      </select>
                  </div>
                  

                  <div class="form-group mx-sm-3 mb-2" id="customDateInputs" style="display: none;">
                      <label for="start_date" class="sr-only">Start Date:</label>
                      <input type="date" name="start_date" id="start_date" class="form-control form-control-sm mr-2" placeholder="Start Date">
                      
                      <label for="end_date" class="sr-only">End Date:</label>
                      <input type="date" name="end_date" id="end_date" class="form-control form-control-sm mr-2" placeholder="End Date">
                  </div>
                  <div class="form-group mx-sm-3 mb-2">
                        <select name="transaction_type" id="transaction_type" class="form-control form-control-sm mr-2" required>
                            <option value="deposit"  {{ request('transaction_type') == 'deposit' ? 'selected' : '' }}>Deposit Owner</option>
                            <option value="withdrawal" {{ request('transaction_type') == 'withdrawal' ? 'selected' : '' }}>Withdrawal Get From Owner</option>
                        </select>
                    </div>
                  <div class="form-group mx-sm-3 mb-2">
                      <label for="store_id" class="sr-only">Filter by Store:</label>
                      <select name="store_id" id="store_id" class="form-control form-control-sm mr-2">
                          <option value="">-- All Stores --</option>
                          @foreach($stores as $store)
                              <option value="{{ $store->id }}">{{ $store->name }}</option>
                          @endforeach
                      </select>
                  </div>
                  <button type="submit" class="btn btn-sm btn-primary mb-2">Apply Filter</button>
                  <a href="#" onclick="printDiv('app')" class="btn btn-sm btn-primary ml-2 mb-2">Print</a>
                  <a href="{{route('owner-deposits.create')}}" class="btn btn-success btn-sm  ml-2 mb-2"">Add New</a>
              </form>

              <script>
                  // JavaScript to toggle custom date inputs
                  const dateFilter = document.getElementById('date_filter');
                  const customDateInputs = document.getElementById('customDateInputs');
                  const filterForm = document.getElementById('filterForm');

                  // Show/hide custom date inputs based on initial selection
                  if (dateFilter.value === 'custom') {
                      customDateInputs.style.display = 'block';
                  }

                  dateFilter.addEventListener('change', function() {
                      if (this.value === 'custom') {
                          customDateInputs.style.display = 'block';
                      } else {
                          customDateInputs.style.display = 'none';
                      }
                  });

                  // Hide custom date inputs when the form is submitted
                  filterForm.addEventListener('submit', function() {
                      if (dateFilter.value !== 'custom') {
                          customDateInputs.style.display = 'none';
                      }
                  });
              </script>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered table-sm" id="example2">
                <thead>
                    <tr>
                        <th>Store ID</th>
                        <th>Amount</th>
                        <th>Transaction Type</th>
                        <th>Date</th>
                        <th>Note</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ownerDeposits as $deposit)
                    <tr>
                        <td>{{ $deposit->store_id }}</td>
                        <td>{{ $deposit->amount }}</td>
                        <td>{{ $deposit->transaction_type }}</td>
                        <td>{{ $deposit->date }}</td>
                        <td>{{ $deposit->note }}</td>
                        <td>
                            <!-- Add action buttons like Edit, Delete, etc. -->
                            <a href="{{ route('owner-deposits.edit', $deposit->id) }}" class="btn btn-info btn-sm">Edit</a>
                            <form action="{{ route('owner-deposits.destroy', $deposit->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-delete btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
@section('script')
<script>
    let isClockwise = true; // Variable to track rotation direction
    const settingsIcon = document.getElementById('settingsIcon');
    const settingsPanel = document.getElementById('settingsPanel');

    settingsIcon.addEventListener('click', function () {
        // Toggle settings panel visibility
        if (settingsPanel.style.display === 'none' || settingsPanel.style.display === '') {
            settingsPanel.style.display = 'block';
        } else {
            settingsPanel.style.display = 'none';
        }

        // Rotate the icon
        if (isClockwise) {
            settingsIcon.style.transform = 'rotate(90deg)';
        } else {
            settingsIcon.style.transform = 'rotate(-90deg)';
        }
        isClockwise = !isClockwise; // Toggle rotation direction
    });
</script>
<!-- jQuery -->
<script src="{{asset('backend/')}}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('backend/')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="{{asset('backend/')}}/plugins/datatables/jquery.dataTables.js"></script>
<script src="{{asset('backend/')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- AdminLTE App -->
<script src="{{asset('backend/')}}/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('backend/')}}/dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
    });
  });
</script>
@endsection