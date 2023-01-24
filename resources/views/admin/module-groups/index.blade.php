@extends('layouts.app')
@section('title', 'Modules Groups')
<!-- start page title -->
@section('page_css')
@include('partials.datatable_css')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Modules Groups</h4>
        </div>
    </div>
</div>
@endsection

@section('page_js')
@include('partials.datatable_js')
@endsection