@if (session('flash_message'))
    <div class="alert alert-info">
        <ul>
            <li>{{ session('flash_message') }}</li>
        </ul>
    </div>
@endif

@if (isset($errors) && count($errors) > 0)
    <div class="alert alert-danger custom d-flex" style="justify-content: space-between;">
        @foreach ($errors->all() as $error)
            {{ $error }}<br />
        @endforeach
        <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
            <span aria-hidden="true">&times;</span>
        </button> -->
    </div>
@endif

@if (Session::get('error'))
    <div class="alert alert-danger d-flex" style="justify-content: space-between;">
        {!! Session::get('error') !!}
        <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
            <span aria-hidden="true">&times;</span>
        </button> -->
    </div>
@endif

@if (Session::get('success'))
    <div class="alert alert-success d-flex" style="justify-content: space-between;">
        {!! Session::get('success') !!}
        <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
            <span aria-hidden="true">&times;</span>
        </button> -->
    </div>
@endif
