@extends('layouts.app')
@section('title', 'Schedule Creation')
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
            <h4 class="page-title">Schedule Creation</h4>
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
                                <option>Select</option>
                                <option value="AK">123456 - branch - Punjab University</option>
                                <option value="HI">123456 - branch - Gujrant University</option>
                                <option value="CA">123456 - branch - Gift University</option>
                                <option value="NV">123456 - branch - Kips University</option>
                                <option value="OR">123456 - branch - Sialkot Univeristy</option>
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-2">
                            <label for="route_no">Select Route No</label>
                            <select class="form-control" data-toggle="select2" data-width="100%" id="route_no">
                                <option>Select</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-2">
                            <label for="vehicle">Select Vehicle Reg</label>
                            <select class="form-control" data-toggle="select2" data-width="100%" id="vehicle">
                                <option>Select</option>
                                <option value="AK">LHR-123</option>
                                <option value="HI">ISB-123</option>
                                <option value="CA">GAJ-123</option>
                                <option value="NV">GRW-123</option>
                                <option value="OR">LEV-123</option>
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-2">
                            <label for="driver">Select Driver</label>
                            <select class="form-control" data-toggle="select2" data-width="100%" id="driver">
                                <option>Select</option>
                                <option value="AK">Arslan</option>
                                <option value="HI">Qasim</option>
                                <option value="CA">Romi</option>
                                <option value="NV">Ashtisham</option>
                                <option value="OR">Azam</option>
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-2">
                            <label for="time">Time</label>
                            <input class="form-control" id="example-time" type="time" name="time">
                        </div> <!-- end col -->
                        <div class="col-md-1">
                            <label for="add_schedule"></label>
                            <button type="button" type="button" class="btn btn-success form-control" id="add_schedule"> Add </button>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<form action="">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex" style="justify-content: space-between;">
                    <div class="col-6">
                        <h4 class="header-title">Created Schedule</h4>
                    </div>
                    <div class="col-6 d-flex flex-row-reverse">
                        <button type="button" type="button" class="btn btn-danger">Publish</button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox">
                                </th>
                                <th>Date</th>
                                <th>Organization Name</th>
                                <th>Driver</th>
                                <th>Route No</th>
                                <th>Vehicle</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="checkbox">
                                </td>
                                <td>20/10/2023</td>
                                <td><b><a href="#" data-bs-toggle="modal" data-bs-target="#modal_organization">Punjab University</a></b></td>
                                <td>Ali</td>
                                <td><b>Grw <a href="#" class="text-success">10</a> LHR</b></td>
                                <td>GAO-123</td>
                                <td>09:00 AM</td>
                                <td><a href="#" class="action-icon"> <i class="mdi mdi-delete"></i></a></td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox">
                                </td>
                                <td>20/10/2023</td>
                                <td><b><a href="#" data-bs-toggle="modal" data-bs-target="#modal_organization">Punjab University</a></b></td>
                                <td>AZam</td>
                                <td><b>LHR <a href="#" class="text-success"> 15 </a> MLT </b></td>
                                <td>LHR-123</td>
                                <td>09:15 AM</td>
                                <td><a href="#" class="action-icon"> <i class="mdi mdi-delete"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
</form>



<!-- Modal -->
<div class="modal fade" id="modal_organization" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="organizationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="organizationLabel">Organization Detail</h5>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-xl-12">
                    <div class="card shadow-none">
                        <div class="card-body">
                            <div class="d-flex align-items-start mb-3">
                                <img class="d-flex me-3 rounded-circle avatar-lg" src="{{ asset('images/small/img-2.jpg') }}" alt="Generic placeholder image">
                                <div class="w-100">
                                    <h4 class="mt-0 mb-1">Punjab University</h4>
                                    <p class="text-muted mb-1">Branch GT Road</p>
                                    <p class="text-muted">Branch Code: 125345689</p>
                                </div>
                            </div>

                            <div class="">
                                <h4 class="font-13 text-muted text-uppercase">About :</h4>
                                <p class="mb-3">
                                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quam explicabo amet laudantium eveniet dicta officia atque veniam impedit, reprehenderit, labore error magnam vitae nihil suscipit iure animi. Consectetur, porro in?
                                </p>

                                <h4 class="font-13 text-muted text-uppercase mb-1">Company :</h4>
                                <p class="mb-3">Punjab University</p>

                                <!-- <h4 class="font-13 text-muted text-uppercase mb-1">Added :</h4>
                                <p class="mb-3"> April 22, 2016</p>

                                <h4 class="font-13 text-muted text-uppercase mb-1">Updated :</h4>
                                <p class="mb-0"> Dec 13, 2017</p> -->

                            </div>
                        </div>
                    </div> <!-- end card-->
                </div>
            </div>
        </div>
    </div>
</div>

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