@extends('layouts.app')
@section('title', 'Add request')
<!-- start page title -->
@section('page_css')
@include('partials.datatable_css')
<link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Add Request</h4>
        </div>
    </div>
</div>

<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <button type="button" type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop"> Add </button>
            </div>
            <div class="card-body">
                <h4 class="header-title">Latest Request</h4>
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Organization Name</th>
                            <th>Request Type</th>
                            <th>Name</th>
                            <th>School Name</th>
                            <th>Roll No</th>
                            <th></th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $request)
                        <tr>
                            <td>{{ $request->organization['name'] }}</td>
                            <td>{{ $request->type }}</td>
                            <td>Edinburgh</td>
                            <td>61</td>
                            <td>{{ $request->roll_no }}</td>
                            <td></td>
                            <td>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" class="tabledit-edit-button btn btn-success" style="float: none;" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><span class="mdi mdi-pencil"></span></button></div>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" class="tabledit-edit-button btn btn-danger" style="float: none;"><span class="mdi mdi-delete"></span></button></div>
                            </td>
                        </tr>

                        @empty

                        @endforelse
                    </tbody>
                </table>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<!-- end row-->
<!-- Modal -->
<div class="modal fade " id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Add Request</h4>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class=" modal-body p-4">
                <form action="#" method="post" enctype="multipart/form-data" id="request">
                    @csrf
                    <div id="basicwizard">
                        <div class="tab-content b-0 mb-0 pt-0">
                            <div class="tab-pane active" id="basictab2">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Text</label>
                                            <select name="o_id" id="org" class="form-select select2">
                                                <option value="" selected>Select Organization</option>
                                                @forelse ($organizations as $organization)
                                                <option value="{{ $organization->id }}">{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>
                                                @empty
                                                <option value="">Please select</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="user_type" class="form-label">User type Select</label>
                                            <select name="user_type" id="user_type" class="form-select select2">
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
                                            <select name="further_user_type" id="further_user_type" class="form-select select2" disabled="disabled">
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
                                <!-- Guardian student school -->
                                <div class="guradian_student_school_form_container" id="guradian_student_school_form_container">
                                </div>
                                <!-- Employee Guardian Form container -->
                                <div class="employee_guradian_form_container" id="employee_guradian_form_container">
                                </div>
                                <!-- guardian form -->
                                <div class="guradian_form_container">
                                </div>
                                <!-- <div class="text-end">
                                    <button type="button" type="submit" class="btn btn-success waves-effect waves-light">Save</button>
                                </div> -->
                                <!-- end row -->
                            </div>

                        </div> <!-- tab-content -->
                    </div> <!-- end #basicwizard-->
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection
@section('page_js')
@include('partials.datatable_js')
<script src="{{ asset('/libs/select2/js/select2.min.js') }}"></script>
<!-- Plugins js-->
<!-- <script src="{{asset('/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js')}}"></script> -->

<!-- Init js-->
<!-- <script src="{{asset('/js/pages/form-wizard.init.js')}}"></script> -->
<script>
    initializeSelect2('.select2', '#request');
</script>
<script src="{{asset('/js/Request.js')}}"></script>

@endsection