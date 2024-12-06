@extends('layouts.app')
@section('title', 'User Data Store')
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
        <h1>User Data Store</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">User Data Store</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content" id="app">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">User Data Store</h3>
            <div class="card-tools">
              <a href="{{route('user-phone-data.index')}}" class="btn btn-success float-right"><i class="fa fa-angle-double-left"></i> Back</a>
            </div>
          </div>
          <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('user-phone-data.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                      <div class="col-md-6 form-group">
                          <label for="file">Upload XLSX or CSV File:</label>
                          <input class="form-control-file" name="file" type="file" id="file" @change="loadColumns" required>
                      </div>
                      <div class="col-md-6 form-group">
                          <label for="column">Select Column for Phone Numbers:</label>
                          <select class="form-control" name="column" id="column" required>
                              <option v-for="(column, index) in columns" :value="index">@{{ column }}</option>
                          </select>
                      </div>

                      <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>  
          </div>
            
      </div>
    </div>
  </div>
</div>
</section>
@endsection
@section('script')
<!-- Vue -->
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<!-- jQuery -->
<script src="{{asset('backend/')}}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('backend/')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="{{asset('backend/')}}/dist/js/adminlte.min.js"></script>
<!-- Vue App Script -->
<script>
  new Vue({
    el: "#app",
    data: {
      columns: [], // To store column names
    },
    methods: {
      loadColumns(event) {
        const fileInput = event.target.files[0];
        if (fileInput) {
          const formData = new FormData();
          formData.append('file', fileInput);

          // Fetch column names
          fetch('{{ route('user-phone-data.get-columns') }}', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: formData,
          })
          .then(response => response.json())
          .then(data => {
            if (data.columns && data.columns.length > 0) {
              this.columns = data.columns; // Update columns array
            } else {
              alert('No columns found in the uploaded file.');
            }
          })
          .catch(error => {
            console.error('Error fetching columns:', error);
            alert('An error occurred while fetching columns. Please try again.');
          });
        }
      }
    }
  });
</script>
@endsection
