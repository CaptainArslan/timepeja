@extends('layouts.app')
@section('title', 'Vehicle List')
<!-- start page title -->
@section('page_css')
<!-- Plugins css -->
<link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />

<!-- App css -->
<link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

@include('partials.datatable_css')
<!-- Plugins css -->
<link href="/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />
<link href="/libs/dropify/css/dropify.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">All Vehicles</h4>
        </div>
    </div>
</div>
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
                                <option value="">Select</option>
                                <option value="AK">123456 - branch - Punjab University</option>
                                <option value="HI">123456 - branch - Gujrant University</option>
                                <option value="CA">123456 - branch - Gift University</option>
                                <option value="NV">123456 - branch - Kips University</option>
                                <option value="OR">123456 - branch - Sialkot Univeristy</option>
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-3">
                            <label for="selecttype">Select Vehicle</label>
                            <select class="form-control" data-toggle="select2" data-width="100%" id="filter">
                                <option value="">Select</option>
                                <option value="">All</option>
                                <option value="driver">Car</option>
                                <option value="vehicle">Bus</option>
                                <option value="route">Hiace</option>
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
<!-- end page title -->
<div class="row">
    <div class="col-lg-12 table-responsive">
        <div class="card">
        <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h4 class="header-title">Vehicle List</h4>
                    <button class="btn btn-danger">Delete</button>
                </div>
            </div>
            <div class="card-body">
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <td>
                                <input type="checkbox">
                            </td>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Organization Name</th>
                            <th>Vehicle Type</th>
                            <th>Vehicle's Number</th>
                            <th>Vehicle picture from front</th>
                            <th>Vehicle license plate picture</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>


                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox">
                            </td>
                            <td></td>
                            <td></td>
                            <td><b><a href="#">Tiger Nixon</a></b></td>
                            <td>Bus</td>
                            <td>GAO-123</td>
                            <td></td>
                            <td></td>
                            <td>2023/01/19</td>
                            <td>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" class="tabledit-edit-button btn btn-success" style="float: none;" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><span class="mdi mdi-pencil"></span></button></div>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" class="tabledit-edit-button btn btn-danger" style="float: none;"><span class="mdi mdi-delete"></span></button></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<!-- end row-->
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="staticBackdropLabel">Update Vehicle</h5>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('vehicle.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="col-xl-12">
                        <div class="card shadow-none">
                            <div class="card-body">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="organization" class="form-label">Organization Name</label>
                                        <select class="form-select" id="organization" name="org_id">
                                            <option value="">Select Organization</option>
                                            <option value="">GC Faisalabad</option>
                                            <option value="">GC Lahore</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="veh_type" class="form-label">Vehicle Type</label>
                                            <select class="form-select" id="veh_type" name="veh_id">
                                                <option value="">Please Select Vehicle Type</option>
                                                <option value="">Car</option>
                                                <option value="">Bus</option>
                                                <option value="">Hiace</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <div class="mt-1">
                                                <input type="file" data-plugins="dropify" name="veh_front_pic" data-default-file="/images/small/img-2.jpg" />
                                                <p class="text-muted text-center mt-2 mb-0">Vehicle Picture from front</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Vehicle's Number</label>
                                            <input type="text" id="simpleinput" name="veh_no" class="form-control">
                                        </div>
                                        <div class="col-12">
                                            <div class="mt-1">
                                                <input type="file" data-plugins="dropify" name="veh_back_pic" data-default-file="/images/small/img-2.jpg" />
                                                <p class="text-muted text-center mt-2 mb-0">Vehicle license plate picture</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card-body -->
                            <div class="text-end">
                                <button type="button" type="submit" class="btn btn-success ">Save</button>
                            </div>
                        </div> <!-- end card-->
                    </div>
                </form>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" type="button" class="btn btn-primary">Submit</button>
            </div> -->
        </div>
    </div>
</div>
@endsection

@section('page_js')
<script src="{{ asset('/libs/select2/js/select2.min.js') }}"></script>
<!-- Init js-->
<script src="{{ asset('/js/pages/form-advanced.init.js') }}"></script>

@include('partials.datatable_js')
<!-- Plugins js-->
<script src="/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

<!-- Plugins js -->
<script src="/libs/dropzone/min/dropzone.min.js"></script>
<script src="/libs/dropify/js/dropify.min.js"></script>

<!-- Init js-->
<script src="/js/pages/form-fileuploads.init.js"></script>

<!-- Init js-->
<script src="/js/pages/form-wizard.init.js"></script>
<script>
    $(document).ready(function() {

    });
</script>
@endsection