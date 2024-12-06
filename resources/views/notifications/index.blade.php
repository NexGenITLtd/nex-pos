@extends('layouts.app')
@section('title', 'Notifications')
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
        <h1>Notifications</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Notifications</li>
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
                    <h3 class="card-title">Notifications</h3>
                    <div class="card-tools">
                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="mb-3" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">Mark All as Read</button>
                        </form>
                        <form action="{{ route('notifications.destroyAll') }}" method="POST" class="mb-3" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete All Notifications</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    @if($notifications->count() > 0)
                        <ul class="list-group">
                            @foreach($notifications as $notification)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $notification->data['supplier_name'] ?? 'Notification' }}</strong>
                                        <p class="mb-0">{{ $notification->data['message'] ?? '' }}</p>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        <br>
                                        <small class="text-muted">Store: {{ $notification->data['store_name'] ?? 'N/A' }}</small> <!-- Store name display -->
                                    </div>
                                    <div>
                                        @if($notification->read_at === null)
                                            <a href="{{ route('notifications.read', $notification->id) }}" class="btn btn-sm btn-primary">Mark as Read</a>
                                        @else
                                            <span class="badge badge-success">Read</span>
                                        @endif

                                        <!-- Delete Button -->
                                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this notification?')">Delete</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-3">
                            {{ $notifications->links() }} <!-- Pagination links -->
                        </div>
                    @else
                        <p>No notifications to display.</p>
                    @endif
                </div>
            </div>



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
