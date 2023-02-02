@extends('layouts.app')
@section('title', 'All Routes')
<!-- start page title -->
@section('page_css')
<!-- Plugins css -->
<link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />

<!-- App css -->
<link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

@include('partials.datatable_css')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">All Routes</h4>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- <h4 class="header-title">Select Organization</h4> -->
                <form action="">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="organization">Select Oganization</label>
                            <select class="form-control" data-toggle="select2" data-width="100%" id="organization">
                                <option value="">Select</option>
                                <option value="AK">123456 - branch - Punjab University</option>
                                <option value="HI">123456 - branch - Gujrant University</option>
                                <option value="CA">123456 - branch - Gift University</option>
                                <option value="NV">123456 - branch - Kips University</option>
                                <option value="OR">123456 - branch - Sialkot Univeristy</option>
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-3">
                            <label for="selecttype">Select</label>
                            <select class="form-control" data-toggle="select2" data-width="100%" id="filter">
                                <option value="">Select</option>
                                <option value="">All</option>
                                <option value="Gujranwala">Gujranwala</option>
                                <option value="Lahore">Lahore</option>
                                <option value="Peshawar">Peshawar</option>
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-2">
                            <label for="date-1">From</label>
                            <input class="form-control" id="example-date-1" type="date" name="date">
                        </div> <!-- end col -->
                        <div class="col-md-2">
                            <label for="date">To</label>
                            <input class="form-control" id="example-date" type="date" name="date">
                        </div> <!-- end col -->
                        <div class="col-md-1">
                            <label for="route_list"></label>
                            <button type="button" type="button" class="btn btn-success" id="route_list"> Submit </button>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<div class="row">
    <div class="col-lg-12 table-responsive">
        <div class="card">
            
            <div class="card-body">
                <h4 class="header-title"> Route List</h4>
                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Organization Name</th>
                            <th>Route Name</th>
                            <th>To</th>
                            <th>From</th>
                            <th>Route No</th>
                            <th>Action</th>
                        </tr>
                    </thead>


                    <tbody>
                        <tr>
                            <td>2/1/2023</td>
                            <td>9:00 PM</td>
                            <td><b><a href="#">Tiger Nixon</a></b></td>
                            <td>System Architect</td>
                            <td>Edinburgh</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td><a href="#" class="action-icon"> <i class="mdi mdi-delete"></i></a></td>
                        </tr>
                    </tbody>
                </table>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<!-- end row-->
@endsection

@section('page_js')
<script src="{{ asset('/libs/select2/js/select2.min.js') }}"></script>
<!-- Init js-->
<script src="{{ asset('/js/pages/form-advanced.init.js') }}"></script>

@include('partials.datatable_js')


@endsection