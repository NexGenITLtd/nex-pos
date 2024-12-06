
@extends('layouts.app')
@section('title', 'User Data')
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
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>User Data</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">User Data</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h1 class="card-title">User Data</h1>
            <div class="card-tools">
            <div class="card-tools">
              <a href="{{ route('user-phone-data.create') }}" class="btn btn-primary">Upload New File</a>
                <form action="{{ route('user-phone-data.delete-all') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-delete">
                        <i class="fa fa-trash"></i> Delete All
                    </button>
                </form>
            </div>

              
            </div>
          </div>
            <div class="card-body">
                <div class="">
                    <div class="">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                        <table class="table table-data table-sm table-bordered" id="example1" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Phone</th>
                                    <th>Created At</th>
                                    <th class="text-center no-print">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userData as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->phone }}</td>
                                        <td>{{ $data->created_at }}</td>
                                        <td class="text-center no-print">
                                            <a class="btn btn-sm btn-primary" href="{{ route('user-phone-data.edit', $data->id) }}">Edit</a> |
                                            <form action="{{ route('user-phone-data.destroy', $data->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger btn-delete" type="submit">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <span class="float-right">{{ $userData->links() }}</span>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</section>
 
@endsection
@section('script')

<!-- jQuery -->
<script src="{{asset('backend/')}}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('backend/')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="{{asset('backend/')}}/plugins/datatables/jquery.dataTables.js"></script>
<script src="{{asset('backend/')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>

<!-- DataTables Responsive -->
<script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.0/js/responsive.bootstrap4.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.2.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.0/js/buttons.bootstrap4.min.js"></script>

<!-- JSZip -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- PDFMake -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<!-- DataTables Buttons HTML5 Export, Print and ColVis -->
<script src="https://cdn.datatables.net/buttons/2.2.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.0/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.0/js/buttons.colVis.min.js"></script>

<!-- AdminLTE App -->
<script src="{{asset('backend/')}}/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('backend/')}}/dist/js/demo.js"></script>
<!-- page script -->

<script>
  $(function () {
    $("#example1").DataTable({
      
      "responsive": true,
      "lengthChange": true,
      "lengthMenu": [ [20, 50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 1000], [20, 50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 1000] ],
      "autoWidth": false,
      "buttons": [
        {
          extend: "copy",
          exportOptions: {
            columns: ':not(.no-print)'
          }
        },
        {
          extend: "csv",
          exportOptions: {
            columns: ':not(.no-print)'
          }
        },
        {
          extend: "excel",
          exportOptions: {
            columns: ':not(.no-print)'
          }
        },
        {
          extend: "pdf",
          exportOptions: {
            columns: ':not(.no-print)'
          }
        },
        {
          extend: "print",
          exportOptions: {
            columns: ':not(.no-print)'
          }
        },
        "colvis"
      ]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
@endsection