<!--
Developer info: 
=================
|---------------------------------------------------------|
| Name   | Ashik                                          |
| Skype  | ashikur551                                     |
| Phone  | +880 1731002123                                |
| Email  | ashikurashik.sc@gmail.com                      |
|---------------------------------------------------------|
-->

<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Admin | @yield('title')</title>
  <!-- favicon link-->
  <link rel="shortcut icon" type="image/icon" href="{{ asset('images') }}/logo/{{ $website_info->fav_icon }}" />
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
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script type="text/javascript">
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    })
  </script>
  <style type="text/css">
    .table thead tr th, tfoot tr th:first-letter{
      text-transform: capitalize;
    }
    @media print {
      .no-print {
        display: none;
      }
    }
  </style>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

</head>
<body class="">
<div id="app">
  @if(Session::has('flash_success'))
    {!! session('flash_success') !!}
  @endif
  <div class="wrapper">
    
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      @yield('content')
    </div>
    <!-- /.content-wrapper -->
    
    
  </div>
  <!-- ./wrapper -->
</div>
<!-- REQUIRED SCRIPTS -->

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
      "paging": false,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": false,
      "autoWidth": false,
    });
  });
</script>
<script type="text/javascript">
toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}
function copyText(id){

    // Get the text field
    var copyText = document.getElementById(id);

    // Select the text field
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices

    // Copy the text inside the text field
    navigator.clipboard.writeText(copyText.value);
    toastr.success('Copied to Clipboard');
    // Alert the copied text
    // alert("Copied the text: " + copyText.value);
}

function printDiv(divId) {
    var printContents = document.getElementById(divId).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}
function downloadImage(divId) {
    html2canvas(document.getElementById(divId)).then(function(canvas) {
        // Create a link element
        var link = document.createElement('a');
        link.href = canvas.toDataURL(); // Convert the canvas to a data URL
        link.download = 'request_' + divId + '.png'; // Set the file name
        link.click(); // Trigger the download
    });
}

document.querySelectorAll('.btn-danger').forEach(button => {
  button.addEventListener('click', function(e) {
    e.preventDefault(); // Prevent default action (like submitting a form)

    Swal.fire({
      title: 'Are you sure?',
      text: 'This action cannot be undone!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
      if (result.isConfirmed) {
        // Call the function to delete the item or submit a form, for example:
        // form.submit(); or send an AJAX request
        window.location.href = button.getAttribute('href'); // If the button is a link
      }
    });
  });
});

</script>
</body>
</html>
