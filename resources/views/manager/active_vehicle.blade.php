@extends('layouts.app')
@section('title', 'Active Vehicles')
<!-- start page title -->
@section('page_css')
<!-- Plugins css -->
<link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />

<!-- App css -->
<link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

@include('partials.datatable_css')
@endsection
<!-- end page title -->
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Active Vehicle</h4>
        </div>
    </div>
</div>
<!-- Start Content  -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- <h4 class="header-title">Select Organization</h4> -->
                <form action="">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="organization">Select Oganization</label>
                            <select class="form-control" data-toggle="select2" data-width="100%" id="organization">
                                <option>Select</option>
                                <option value="AK">123456 - branch - Punjab University</option>
                                <option value="HI">123456 - branch - Gujrant University</option>
                                <option value="CA">123456 - branch - Gift University</option>
                                <option value="NV">123456 - branch - Kips University</option>
                                <option value="OR">123456 - branch - Sialkot Univeristy</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date-1">From</label>
                            <input class="form-control" id="example-date-1" type="date" name="date">
                        </div>
                        <div class="col-md-3">
                            <label for="date">To</label>
                            <input class="form-control" id="example-date" type="date" name="date">
                        </div>
                        <div class="col-md-1">
                            <label for="publish_schedule">.</label>
                            <button type="button" type="button" class="btn btn-success" id="publish_schedule"> Submit </button>
                        </div>
                    </div> <!-- end row -->
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<!--  Schedule Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex">
                <div class="col-2">
                    <h4 class="header-title">Active Vehicle</h4>
                </div>
                <div class="col-7">
                    <div class="row">
                        <div class="col-md-6">
                            <input class="form-control" id="" type="text" value="123456 - branch - Punjab University" name="organization" style="font-weight: bold;" readonly>
                        </div>
                        <!-- <div class="col-md-3">
                            <input class="form-control" id="example-date-1" type="date" name="date">
                        </div>
                        <div class="col-md-3">
                            <input class="form-control" id="example-date" type="date" name="date">
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox">
                            </th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Route Name</th>
                            <th>Vehicle</th>
                            <th>Driver</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox">
                            </td>
                            <td>20/12/2022</td>
                            <td>09:00 AM</td>
                            <td> 15 -  Gujranwala To Lahore </td>
                            <td>LHR-123</td>
                            <td>Ali</td>
                            <!-- <td>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" type="button" class="tabledit-edit-button btn btn-success" style="float: none;"><span class="mdi mdi-pencil"></span></button></div>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" type="button" class="tabledit-edit-button btn btn-danger" style="float: none;"><span class="mdi mdi-delete"></span></button></div>
                            </td> -->
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox">
                            </td>
                            <td>20/12/2022</td>
                            <td>09:15 AM</td>
                            <td> 15 -  Gujranwala To Lahore </td>
                            <td>GAO-123</td>
                            <td>Azam</td>
                            <!-- <td>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" type="button" class="tabledit-edit-button btn btn-success" style="float: none;"><span class="mdi mdi-pencil"></span></button></div>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" type="button" class="tabledit-edit-button btn btn-danger" style="float: none;"><span class="mdi mdi-delete"></span></button></div>
                            </td> -->
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox">
                            </td>
                            <td>20/12/2022</td>
                            <td>09:15 AM</td>
                            <td> 15 -  Gujranwala To Lahore </td>
                            <td>GAO-123</td>
                            <td>Afzaal</td>
                            <!-- <td>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" type="button" class="tabledit-edit-button btn btn-success" style="float: none;"><span class="mdi mdi-pencil"></span></button></div>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" type="button" class="tabledit-edit-button btn btn-danger" style="float: none;"><span class="mdi mdi-delete"></span></button></div>
                            </td> -->
                        </tr>
                    </tbody>
                </table>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<!-- end row -->
<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <h4 class="header-title mb-3">Map</h4>
            <div class="mb-3">
                <label class="form-label">Search</label>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search on map" aria-label="Username" aria-describedby="basic-addon1">
                    <span class="input-group-text" id="basic-addon1" role="button"><i class="fas fa-search"></i></span>
                </div>
            </div>
            <div id="gmaps-basic" class="gmaps"></div>
        </div>
    </div> <!-- end card-->
</div>
<!-- Modal -->

<!-- /.modal -->

<!-- End Content  -->
@endsection

@section('page_js')
<script src="{{ asset('/libs/select2/js/select2.min.js') }}"></script>
<!-- Init js-->
<script src="{{ asset('/js/pages/form-advanced.init.js') }}"></script>

@include('partials.datatable_js')
<script>
    $(document).ready(function() {

    });
</script>

@endsection