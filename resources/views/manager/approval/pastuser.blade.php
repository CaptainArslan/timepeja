@extends('layouts.app')
@section('title', 'Past User')
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
            <h4 class="page-title">Past Users</h4>
        </div>
    </div>
</div>
<!-- Start Content  -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- <h4 class="header-title">Select Organization</h4> -->
                <form action="{{ route('user.pastuser') }}" method="post" enctype="multipart/form-data">
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
                <div class="col-1">
                    <h4 class="header-title">Past Users</h4>
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
                                <input type="checkbox" class="child_checkboxsss">
                            </td>
                            <td>Ali</td>
                            <td>MSCS220444</td>
                            <td>8th/Sales</td>
                            <td>Johar Town Lahore</td>
                            <td><span class="badge bg-dark">Past User</span></td>
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