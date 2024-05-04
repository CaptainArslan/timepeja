@extends('layouts.app')
@section('title', 'Add Setting')
<!-- start page title -->
@section('page_css')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Add Setting</h4>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('setting.google') }}" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-md-10">
                            <label for="from">Google Map Api Key</label>
                            <input class="form-control mt-2" type="text" value="@if($settings){{$settings->credentials}}@endif" name="credentials">
                        </div>
                        <div class="col-md-1">
                            <label for="publish_schedule">.</label>
                            <button type="submit" class="btn btn-success mt-2" name="filter" value="filter" id="publish_schedule"> Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
@section('page_js')

@endsection