@if(session('success'))
    <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <span class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </span>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <span class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </span>
    </div>
@endif
