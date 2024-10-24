@extends('layouts.app')
@section('title', 'All Requests')
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
            <h4 class="page-title">All Requests</h4>
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
                                <option value="Arslan">Arslan</option>
                                <option value="Rashid">Rashid</option>
                                <option value="Ahtisham">Ahtisham</option>
                                <option value="Qasim">Qasim</option>
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
                <h4 class="header-title">Requests List</h4>
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Organization Name</th>
                            <th>Request Type</th>
                            <th>Name</th>
                            <th>School Name</th>
                            <th>Roll No</th>
                            <th>Gender</th>
                            <th>Action</th>
                        </tr>
                    </thead>


                    <tbody>
                        <tr>
                            <td><b><a href="#">Tiger Nixon</a></b></td>
                            <td>System Architect</td>
                            <td>Edinburgh</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>$320,800</td>
                            <td>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" class="tabledit-edit-button btn btn-success" style="float: none;" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><span class="mdi mdi-pencil"></span></button></div>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" class="tabledit-edit-button btn btn-danger" style="float: none;"><span class="mdi mdi-delete"></span></button></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> <!-- end card body-->
        </div><!-- end col-->
    </div>
    <!-- end row-->
    <!-- Modal -->
    <div class="modal fade " id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h4 class="modal-title" id="myCenterModalLabel">Update Request</h4>
                    <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class=" modal-body p-4">
                    <form>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Text</label>
                                    <select name="" id="" class="form-select">
                                        <option value="">Select Organization</option>
                                        <option value="pu">123 - pu - org Name</option>
                                        <option value="uos">456 - UOS - org Name</option>
                                        <option value="uog">789 - UOG - org Name</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="user_type" class="form-label">User type Select</label>
                                    <select name="" id="user_type" class="form-select">
                                        <option value="">Select</option>
                                        <option value="student">student</option>
                                        <option value="student_guardian">Student Guardian</option>
                                        <option value="employee">Employee</option>
                                        <option value="employee_guardian">Employee Guardian</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="further_user_type" class="form-label">Further type Select</label>
                                    <select name="" id="further_user_type" class="form-select" disabled="disabled">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Name</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Phone No</label>
                                    <input type="number" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Email Address</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Home Address</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">House No.</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Street No.</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Town</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Additional Details / Nearby (Optional)</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">City</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Transport Pick-UP Loaction</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Address</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">City</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <!-- Student form container  -->
                        <div class="student_school_form_container" id="student_school_form_container">
                        </div>
                        <div class="student_college_form_container" id="student_college_form_container">
                        </div>
                        <div class="student_university_form_container" id="student_university_form_container">
                        </div>
                        <!-- Employee form -->
                        <div class="employee_form_container">
                        </div>
                        <!-- guardian form -->
                        <div class="guradian_form_container">
                        </div>
                        <div class="text-end">
                            <button type="button" type="submit" class="btn btn-success waves-effect waves-light">Save</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    @endsection

    @section('page_js')
    <script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
    <!-- Init js-->
    <script src="{{ asset('js/pages/form-advanced.init.js') }}"></script>

    <script src="{{asset('js/passenger.js')}}"></script>

    @include('partials.datatable_js')


    @endsection