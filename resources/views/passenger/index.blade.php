@extends('layouts.app')
@section('title', 'Passengers')
<!-- start page title -->
@section('page_css')
@include('partials.datatable_css')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Add Passengers</h4>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex">
                <button type="button" type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop"> Add </button>
            </div>
            <div class="card-body">
                <h4 class="header-title">Latest Passengers</h4>
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Organization Name</th>
                            <th>Passenger Type</th>
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
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<!-- end row-->
<!-- Modal -->
<div class="modal fade " id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Add Passenger</h4>
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
@include('partials.datatable_js')
<!-- Plugins js-->
<script src="/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
<!-- Init js-->
<script src="/js/pages/form-wizard.init.js"></script>
<script>
    $(document).ready(function() {
        $('#user_type').change(function(e) {
            e.preventDefault();
            $('#further_user_type').empty();
            var options = '';
            var user_type = $(this).val();
            options = get_user_further_type_option(user_type);
            $('#further_user_type').append(options);
        });
    });

    function get_user_further_type_option(param) {
        var option = '';
        if (param == 'student' || param == 'student_guardian') {
            option = `
                <option value="">Select</option>
                <option value="primary_level">Primary level (Play groun to 5th)</option>
                <option value="middle_level">Middle Level (6th to 8th) </option>
                <option value="high_level">Higher school (9th to 10th) </option>
                <option value="college">Intermediate / college (11th / 12th)</option>
                <option value="university">Bachelors / university</option>
                <option value="university">Master / university</option>
            `;
        } else {
            option = `<option value="">Select</option>`;
        }
        return option;
    }

    $('#user_type').change(function(e) {
        e.preventDefault();
        set_further_user_type_dropdown();
    });

    function set_further_user_type_dropdown() {
        var type = $('#user_type').val();
        if (type == 'student' || type == 'student_guardian') {
            $('#further_user_type').prop('disabled', false);
        } else {
            $('#further_user_type').prop('disabled', true);
        }
    }
    set_further_user_type_dropdown();

    function check_user_type() {
        var html = '';
        var type = $('#further_user_type').val();
        var type = $('#user_type').val();
        if (type == 'primary_level' || type == 'middle_level' || type == 'high_level') {
            html = student_school();
            empty_form();
            $('#student_school_form_container').append(html);
        } else if (type == 'college') {
            html = student_college();
            empty_form();
            $('#student_college_form_container').append(html);
        } else if (type == 'university') {
            html = student_university();
            empty_form();
            $('#student_university_form_container').append(html);
        } else {
            empty_form();
        }
    }

    function empty_form() {
        $('#student_school_form_container').empty();
        $('#student_college_form_container').empty();
        $('#student_university_form_container').empty();
    }

    check_user_type()

    $('#further_user_type').change(function(e) {
        e.preventDefault();
        check_user_type()
    });

    function student_school() {
        return `
        <form id="student_school_form">
            <div class="row">
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Roll No</label>
                        <input type="text" class="form-control">
                    </div>
                </div> 
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Class</label>
                        <input type="text" class="form-control">
                    </div>
                </div> 
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Section</label>
                        <input type="text" class="form-control">
                    </div>
                </div> 
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Student Card</label>
                        <input type="file" class="form-control">
                    </div>
                </div> 
            </div>
        </form>`;
    }

    function student_college() {
        return `<form id="student_college_form">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Roll No</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Qualification Level</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Class</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Section</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Batch Year</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Degree / Course Duration</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Student Card</label>
                                <input type="file" class="form-control">
                            </div>
                        </div> 
                    </div>
                </form>`;
    }

    function student_university() {
        return ` <form id="student_university_form">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Roll No</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Qualification Level</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Department</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Class</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Class Timing</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Batch Year</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Degree Duration</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Student Card</label>
                                <input type="file" class="form-control">
                            </div>
                        </div> 
                    </div>
                </form>`;
    }

    function employee_form() {
        return ` <form>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Employee ID</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Department</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Designation / Postion / Post</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Employee Card</label>
                                <input type="file" class="form-control">
                            </div>
                        </div> 
                    </div>
                </form>`;
    }
</script>
@endsection
<div class="guardians_info" style="display: none;">
    <div class="row">
        <div class="col-lg-4">
            <div class="mb-3">
                <label for="simpleinput" class="form-label">CNIC</label>
                <input type="text" class="form-control">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="mb-3">
                <label for="simpleinput" class="form-label">Realation With Student</label>
                <input type="text" class="form-control">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="mb-3">
                <label for="simpleinput" class="form-label">Guardian Code</label>
                <input type="text" class="form-control">
            </div>
        </div>
    </div>
</div>

<div class="info" style="display: none;">
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
                    <select name="" id="" class="form-select">
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
                    <select name="" class="form-select">
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
        </div>
    </form>
</div>