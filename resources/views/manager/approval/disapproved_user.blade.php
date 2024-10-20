@extends('layouts.app')
@section('title', 'Disapproved User')
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
            <h4 class="page-title">Disapproved User</h4>
        </div>
    </div>
</div>
<!-- Start Content  -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- <h4 class="header-title">Select Organization</h4> -->
                <form action="" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <label for="organization">Select Oganization</label>
                            <select class="form-control select2" data-toggle="select2" name="o_id" data-width="100%" id="organization" required>
                                <option value="">Select</option>
                                @forelse ($organizations as $organization)
                                <option value="{{ $organization->id }}" {{ $organization->id == request()->input('o_id') ? 'selected' : '' }}>{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>
                                @empty
                                <option value="">Please select</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="organization">User Type</label>
                            <select class="form-control" data-toggle="select2" data-width="100%" name="type" id="user_type" required>
                                <option value="" @if(!request()->input('type')) selected @endif>Select</option>
                                <option value="student" @if(request()->input('type') == 'student') selected @endif>Student</option>
                                <option value="employee" @if(request()->input('type') == 'employee') selected @endif>Employee</option>
                                <option value="guardian" @if(request()->input('type') == 'guardian') selected @endif>Guardian</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="date-1">Registration From</label>
                            <input class="form-control today-date" id="" type="date" name="from" value="{{ request()->input('from', old('from')) }}">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <label for="date">Registration To</label>
                            <input class="form-control today-date" id="" type="date" name="to" value="{{ request()->input('to', old('to')) }}">
                        </div>
                        <div class="col-md-1">
                            <label for="submit">.</label>
                            <button type="submit" class="btn btn-success" id="submit" name="submit"> Submit </button>
                        </div>
                    </div> <!-- end row -->
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

@if(isset($_POST['submit']))
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex">
                <div class="col-2">
                    <h4 class="header-title">Disapproved Approvals</h4>
                </div>
                <div class="col-9">
                    <div class="row">
                        <div class="col-md-6">
                            <input class="form-control" id="" type="hidden" value="{{ request()->input('o_id', old('o_id')) }}" name="o_id" style="font-weight: bold;" readonly>
                        </div>
                        <div class="col-md-3">
                            <input class="form-control" id="" type="hidden" name="from" value="{{ request()->input('from', old('from')) }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <input class="form-control" id="" type="hidden" name="to" value="{{ request()->input('to', old('to')) }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <input class="form-control" id="" type="hidden" name="type" value="{{ request()->input('type', old('type')) }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="parent_checkbox">
                            </th>
                            <th>Student/Employee Name</th>
                            <th>Roll No/Employee ID</th>
                            <th>Class/Department</th>
                            <th>Town/City</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" class="child_checkbox">
                            </td>
                            <td>Ali</td>
                            <td>MSCS220444</td>
                            <td>8th/Sales</td>
                            <td>Johar Town Lahore</td>
                            <td><span class="badge bg-danger">Disapproved</span></td>
                            <td>
                                <a href="#" class="btn btn-success  show_request text-white action-icon"> <i class="mdi mdi-logout-variant"></i></a>
                                <!-- <a href="#" class="btn btn-danger  text-white action-icon"> <i class="mdi mdi-delete"></i></a> -->
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
@endif

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
                                <p class="mb-3">Vine Corporation</p>

                                <h4 class="font-13 text-muted text-uppercase mb-1">Added :</h4>
                                <p class="mb-3"> April 22, 2016</p>

                                <h4 class="font-13 text-muted text-uppercase mb-1">Updated :</h4>
                                <p class="mb-0"> Dec 13, 2017</p>

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
        $('.show_request').click(function(e) {
            e.preventDefault();
            var url = window.location.origin + "/user/approval";
            var w1 = window.open(
                url,
                "_blank",
                "width=850,height=650,left=150,top=200,toolbar=1,status=1"
            );

        });
    });
</script>

@endsection