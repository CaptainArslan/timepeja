@extends('layouts.app')
@section('title', 'LOG Reports')
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
            <h4 class="page-title">All LOG Reports</h4>
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
                        <div class="col-md-2">
                            <label for="selecttype">Select</label>
                            <select class="form-control" data-toggle="select2" data-width="100%" id="filter">
                                <option value="">Select</option>
                                <option value="driver">Driver</option>
                                <option value="vehicle">Vehicle</option>
                                <option value="route">Route</option>
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-2">
                            <label for="selecttype" class="text-capitalize" id="filter_select_label">Select</label>
                            <select class="form-control" data-toggle="select2" data-width="100%" id="filter_select" multiple>
                                <option value="">Select</option>
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
                            <label for="publish_schedule">.</label>
                            <button type="button" type="button" class="btn btn-success" id="publish_schedule"> Submit </button>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="header-title">Reports</h4>
            </div>
            <div class="card-body">
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox">
                            </th>
                            <th>Organization Name</th>
                            <th>Branch Name</th>
                            <th>Branch Code</th>
                            <th>Route No</th>
                            <th>Vehicle</th>
                            <th>Driver</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox">
                            </td>
                            <td><b><a href="#" data-bs-toggle="modal" data-bs-target="#modal_organization">Punjab University</a></b></td>
                            <td>GT Road Branch</td>
                            <td>123</td>
                            <td><b><a href="#">1</a></b></td>
                            <td>LHR-123</td>
                            <td>Ali</td>
                            <td>09:45 PM</td>
                            <td>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" type="button" class="tabledit-edit-button btn btn-success" style="float: none;"><span class="mdi mdi-pencil"></span></button></div>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" type="button" class="tabledit-edit-button btn btn-danger" style="float: none;"><span class="mdi mdi-delete"></span></button></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<!-- Modal -->

<!-- End Content  -->
@endsection

@section('page_js')
<script src="{{ asset('/libs/select2/js/select2.min.js') }}"></script>
<!-- Init js-->
<script src="{{ asset('/js/pages/form-advanced.init.js') }}"></script>

@include('partials.datatable_js')
<script>
    $(document).ready(function() {
        $('#filter').change(function(e) {
            e.preventDefault();
            var option = '';
            var value = $(this).val();
            $('#filter_select').empty();
            if (value == 'driver') {
                $('#filter_select_label').html(value);
                var option = driver_option();
            } else if (value == 'route') {
                $('#filter_select_label').html(value);
                var option = route_option();
            } else if (value == 'vehicle') {
                $('#filter_select_label').html(value);
                var option = vehicle_option();
            }else{
                var option = `<option value="">Select</option>`;
            }
            $('#filter_select').append(option);
        });
    });

    function driver_option() {
        return html = `<option value="">All</option>
                        <option value="">Azam </option>
                        <option value="">Afzal </option>
                        <option value="">Ali</option>`;
    }

    function vehicle_option() {
        return html = `<option value="">All</option>
                    <option value="">GAO-123 </option>
                    <option value="">LHR-123</option>
                    <option value="">KCH_123</option>`;
    }

    function route_option() {
        return html = `<option value="">All</option>
                        <option value="">LHR 10 GRW </option>
                        <option value="">GRM 15 MLT</option>
                        <option value="">LHR 20 KCH</option>`;
}
</script>

@endsection