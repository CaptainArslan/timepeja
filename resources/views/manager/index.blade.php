@extends('layouts.app')
@section('title', 'Add Managers')
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
        <div class="page-title-box d-flex justify-content-between">
            <h4 class="page-title">Organizations</h4>
            <div class="page-title">
                <button type="button" type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#create_modal"> Add Organization </button>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('manager.store') }}" method="POST" id="filter_form">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="organization">Select Oganization</label>
                            <select class="form-control select2_filter" id="organization">
                                <option value="">Select</option>
                                @forelse ($organizations as $organization)
                                <option value="{{ $organization->id }}">{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>
                                @empty
                                <option value="">Please select</option>
                                @endforelse
                            </select>
                        </div> <!-- end col -->
                        <!-- <div class="col-md-3">
                            <label for="city">Select City</label>
                            <select class="form-control select2_filter" id="city">
                                <option value="">Select</option>
                                <option value="all">Lahore</option>
                                <option value="all">Multan</option>
                                <option value="all">Peshawar</option>
                                <option value="all">Islamabad</option>
                                <option value="all">Karachi</option>
                                <option value="all">Faisalabad</option>
                                <option value="all">Gujrat</option>
                            </select>
                        </div> -->
                        <div class="col-md-3">
                            <label for="from">Registration From</label>
                            <input type="date" class="form-control today-date" id="example-from" name="from">
                        </div> <!-- end col -->
                        <div class="col-md-3">
                            <label for="to">Registration To</label>
                            <input type="date" class="form-control today-date" id="example-to" name="to">
                        </div> <!-- end col -->
                        <div class="col-md-1">
                            <!-- <label for="route_list"></label> -->
                            <button type="button" type="button" class="btn btn-success" id="route_list" style="margin-top: 20px;"> Submit </button>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="col-2">
                    <h4 class="header-title">Latest Managers <!-- <a class="text-primary"> ( {{ $managers_count }} ) </a> --> </h4>
                </div>
                <!-- <div class="col-9">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="example-from" name="organization" value="" readonly style="font-weight: bold;">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control today-date" id="example-from" name="from">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control today-date" id="example-to" name="to">
                        </div>
                    </div>
                </div> -->
                <div class="col-1 d-none ">
                    <button class="btn btn-dark">Print</button>
                </div>
            </div>
            <div class="card-body table-container">
                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>OTP</th>
                            <th>Org Name</th>
                            <th>Branch Name</th>
                            <th>Branch Code</th>
                            <th>Org Types</th>
                            <th>Email</th>
                            <th>Phone No</th>
                            <th>Address</th>
                            <th>Transport Manager</th>
                            <th>Phone No</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Manager Picture</th>
                            <th>Head Name</th>
                            <th>Email</th>
                            <th>Phone No</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($organizations as $organization)
                        <tr>
                            <td>
                                {{ date("Y-m-d",strtotime($organization->created_at)) }} 
                                <input type="hidden" name="" id="" class="db_date">
                            </td>
                            <td>
                                {{ date("H:m:s",strtotime($organization->created_at)) }} 
                                <input type="hidden" name="" id="" class="db_time">
                            </td>
                            <td>
                                {{ $organization->manager['otp'] }} 
                                <input type="hidden" name="" id="" class="db_otp">
                            </td>
                            <td>
                                {{ $organization->name }} 
                                <input type="hidden" name="" id="" class="db_org_name">
                            </td>
                            <td>
                                {{ $organization->branch_name }} 
                                <input type="hidden" name="" id="" class="db_branch_name">
                            </td>
                            <td>
                                {{ $organization->branch_code }} 
                                <input type="hidden" name="" id="" class="db_branch_code">
                            </td>
                            <td>
                                {{ $organization->o_type }} 
                                <input type="hidden" name="" id="" class="db_org_type">
                            </td>
                            <td>
                                {{ $organization->email }} 
                                <input type="hidden" name="" id="" class="db_org_email">
                            </td>
                            <td>
                                {{ $organization->phone }} 
                                <input type="hidden" name="" id="" class="db_org_phone">
                            </td>
                            <td>
                                {{ $organization->address }} 
                                <input type="hidden" name="" id="" class="db_org_address">
                            </td>
                            <td>
                                {{ $organization->manager['name'] }} 
                                <input type="hidden" name="" id="" class="db_man_name">
                            </td>
                            <td>
                                {{ $organization->manager['email'] }} 
                                <input type="hidden" name="" id="" class="db_man_email">
                            </td>
                            <td>
                                {{ $organization->manager['phone'] }} 
                                <input type="hidden" name="" id="" class="db_man_phone">
                            </td>
                            <td>
                                {{ $organization->manager['address'] }} 
                                <input type="hidden" name="" id="" class="db_man_manager">
                            </td>
                            <td>
                                {{ $organization->manager['picture'] }} 
                                <input type="hidden" name="" id="" class="db_man_picture">
                            </td>
                            <td>
                                {{ $organization->manager['otp'] }} 
                                <input type="hidden" name="" id="" class="">
                            </td>
                            <td>
                                {{ $organization->manager['otp'] }} 
                                <input type="hidden" name="" id="" class="">
                            </td>
                            <td>
                                {{ $organization->manager['otp'] }} 
                                <input type="hidden" name="" id="" class="">
                            </td>
                            <td>
                                {{ $organization->manager['otp'] }} 
                                <input type="hidden" name="" id="" class="">
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" type="button" class="tabledit-edit-button btn btn-success" style="float: none;" data-bs-toggle="modal" data-bs-target="#create_modal"><span class="mdi mdi-pencil"></span></button></div>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" type="button" class="tabledit-edit-button btn btn-danger" style="float: none;"><span class="mdi mdi-delete"></span></button></div>
                            </td>
                        </tr>
                        @empty
                        <!--  -->
                        @endforelse
                    </tbody>
                </table>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<!-- end row-->
<!-- Modal -->
<div class="modal fade" id="create_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="create_modalLabel">Add Organization</h5>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-xl-12">
                    <div class="card shadow-none">
                        <div class="card-body">
                            <form action="{{ route('manager.store') }}" method="POST" id="organization_creation" enctype="multipart/form-data">
                                @csrf
                                <div id="basicwizard">
                                    <ul class="nav nav-pills bg-light nav-justified form-wizard-header mb-4">
                                        <li class="nav-item">
                                            <a href="#company" data-bs-toggle="tab" id="company_tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                <i class="fas fa-align-center"></i>
                                                <span class="d-none d-sm-inline">Organization Detail</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#company_head" data-bs-toggle="tab" id="company_head_tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2" onclick="checkOrgDetailForm()" disabled>
                                                <i class="fas fa-user"></i>
                                                <span class="d-none d-sm-inline">Company Head</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#transport_manager" data-bs-toggle="tab" data-toggle="tab" id="transport_manager_tab" class="nav-link rounded-0 pt-2 pb-2" onclick="checkOrgHeadForm()" disabled>
                                                <i class="fas fa-bus-alt"></i>
                                                <span class="d-none d-sm-inline">Transport Manager</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#financials" id="financials_tabs" value="financials" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2" onclick="checkTransportManagerForm()" disabled>
                                                <i class=" fas fa-money-bill-wave"></i>
                                                <span class="d-none d-sm-inline">Financial</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content b-0 mb-0 pt-0">
                                        <!-- Oganization detail -->
                                        <div class="tab-pane" id="company">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="org_name" class="form-label">Organization Name</label>
                                                        <input type="text" id="org_name" name="org_name" value="{{ old('org_name') }}" class="form-control" required>
                                                        <span class="text-danger" id="org_name_error"></span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="org_branch_code" class="form-label">Branch code</label>
                                                        <input type="number" id="org_branch_code" name="org_branch_code" value="{{ old('org_branch_code') }}" class="form-control">
                                                        <span class="text-danger" id="org_branch_code_error"></span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="org_email" class="form-label">Email</label>
                                                        <input type="email" id="org_email" name="org_email" class="form-control" value="{{ old('org_email') }}" placeholder="Email" required>
                                                        <span class="text-danger" id="org_email_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="org_branch_name" class="form-label">Branch Name</label>
                                                        <input type="text" id="org_branch_name" name="org_branch_name" class="form-control" value="{{ old('org_branch_name') }}">
                                                        <span class="text-danger" id="org_branch_name_error"></span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="org_type" class="form-label">Types</label>
                                                        <select class="form-select select2" id="org_type" name="org_type " required>
                                                            <option value="">Please Select Organization Type</option>
                                                            @forelse ($organization_types as $organizaton_type)
                                                            <option value="{{$organizaton_type->id}}">{{$organizaton_type->name}} ({{$organizaton_type->desc}})</option>
                                                            @empty
                                                            <option>No Option Found</option>
                                                            @endforelse
                                                        </select>
                                                        <span class="text-danger" id="org_type_error"></span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="org_phone" class="form-label">Phone No</label>
                                                        <input type="number" id="org_phone" name="org_phone" class="form-control" value="{{ old('org_phone') }}" required>
                                                        <span class="text-danger" id="org_phone_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 row">
                                                    <div class="mb-3 col-4">
                                                        <label for="org_state" class="form-label">State</label>
                                                        <select class="form-select select2" id="org_state" name="org_state" required>
                                                            <option value="" selected>Please Select State</option>
                                                            @forelse ($states as $state)
                                                            <option value="{{$state->id}}">{{ $state->name }}</option>
                                                            @empty
                                                            <option>No Avaiable</option>
                                                            @endforelse
                                                        </select>
                                                        <span class="text-danger" id="org_state_error"></span>
                                                    </div>
                                                    <div class="mb-3 col-4">
                                                        <label for="org_city" class="form-label">City</label>
                                                        <select class="form-select select2" id="org_city" name="org_city" required>
                                                            <option value="" selected>Please Select City</option>
                                                        </select>
                                                        <span class="text-danger" id="org_city_error"></span>
                                                    </div>
                                                    <div class="mb-3 col-4">
                                                        <label for="org_address" class="form-label">Address</label>
                                                        <input class="form-control" id="org_address" name="org_address" value="{{ old('org_address') }}">
                                                        <span class="text-danger" id="org_address_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Oganization head -->
                                        <div class="tab-pane" id="company_head">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="org_head_name" class="form-label">Head Name</label>
                                                        <input type="text" id="org_head_name" name="org_head_name" value="{{ old('org_head_name') }}" class="form-control" required>
                                                        <span class="text-danger" id="org_head_name_error"></span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="org_head_phone" class="form-label">Phone No</label>
                                                        <input type="number" id="org_head_phone" name="org_head_phone" value="{{ old('org_head_phone') }}" class="form-control" required>
                                                        <span class="text-danger" id="org_head_phone_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="org_head_email" class="form-label">Email</label>
                                                        <input type="email" id="org_head_email" name="org_head_email" value="{{ old('org_head_email') }}" class="form-control" placeholder="Email" required>
                                                        <span class="text-danger" id="org_head_email_error"></span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="org_head_address" class="form-label">Address</label>
                                                        <input class="form-control" id="org_head_address" name="org_head_address" value="{{ old('org_head_address') }}" rows="5"></input>
                                                    </div>
                                                </div> <!-- end col -->
                                            </div> <!-- end row -->
                                        </div>

                                        <!-- Company transport manager -->
                                        <div class="tab-pane" id="transport_manager">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="man_name" class="form-label">Name</label>
                                                        <input type="text" id="man_name" name="man_name" value="{{ old('man_name') }}" class="form-control" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="phone" class="form-label">Phone No</label>
                                                        <input type="number" id="man_phone" name="man_phone" value="{{ old('phone') }}" class="form-control" required>
                                                    </div>
                                                    <!-- <div class="mb-3">
                                                        <label for="man_password" class="form-label">Password</label>
                                                        <div class="input-group input-group-merge">
                                                            <input type="password" id="password" class="form-control" placeholder="Enter your password" name="password">
                                                            <div class="input-group-text" data-password="false">
                                                                <span class="password-eye"></span>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="man_email" class="form-label">Email</label>
                                                        <input type="email" id="man_email" name="man_email" value="{{ old('man_email') }}" class="form-control" placeholder="Email" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="man_pic" class="form-label">Manager Picture</label>
                                                        <input type="file" id="man_pic" name="man_pic" value="{{ old('man_pic') }}" class="form-control">
                                                    </div>
                                                    <!-- <div class="mb-3">
                                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                                        <div class="input-group input-group-merge">
                                                            <input type="password" id="password_confirmation" class="form-control" placeholder="Enter your password" name="password_confirmation">
                                                            <div class="input-group-text" data-password="false">
                                                                <span class="password-eye"></span>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                </div> <!-- end col -->
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="man_address" class="form-label">Address</label>
                                                        <textarea class="form-control" name="man_address" id="man_address" value="{{ old('man_address') }}" cols="30" rows="2" placeholder="123 Street lahore, Pakistan"></textarea>
                                                    </div>
                                                </div>
                                            </div> <!-- end row -->
                                        </div>

                                        <!-- Company financials -->
                                        <div class="tab-pane" id="financials">
                                            <!-- Wallet Activation -->
                                            <div class="row">
                                                <div class="col-md-12 mt-2">
                                                    <h4 class="header-title">Who will charge the fee from:</h4>
                                                    <div class="form-check mb-2 form-check-primary">
                                                        <input class="form-check-input" type="checkbox" name="wallet[]" id="org_wallet" value="org_wallet" checked>
                                                        <label class="form-check-label" for="org_wallet">Organization</label>
                                                    </div>
                                                    <div class="form-check mb-2 form-check-success">
                                                        <input class="form-check-input" type="checkbox" name="wallet[]" id="driver_wallet" value="driver_wallet" checked>
                                                        <label class="form-check-label" for="driver_wallet">Sub Contracting Driver</label>
                                                    </div>
                                                    <div class="form-check mb-2 form-check-danger">
                                                        <input class="form-check-input" type="checkbox" name="wallet[]" id="passenger_wallet" value="passenger_wallet" checked>
                                                        <label class="form-check-label" for="passenger_wallet">Passengers</label>
                                                    </div>
                                                </div>

                                                <!-- Amount of all users -->
                                                <div class="col-md-12 mt-3 mb-2">
                                                    <h4 class="header-title">Basis of payment:</h4>
                                                    <div class="row">
                                                        <div class="col-3 d-flex align-items-center">
                                                            <input class="form-check-input" type="checkbox" name="payment[]" id="org_payment" value="org_payment" checked="">
                                                            <label class="form-check-label mx-1" for="org_payment">Organization</label>
                                                        </div>
                                                        <div class="col-2">
                                                            <label for="org_amount" class="form-label">Amount</label>
                                                            <input class="form-control" type="number" placeholder="Amount" name="org_amount" value="{{ old('org_amount') }}">
                                                        </div>
                                                        <div class="col-1">
                                                            <label for="org_trial_days" class="form-label"> Days</label>
                                                            <input class="form-control " type="number" name="org_trial_days" id="org_trial_days" value="{{ old('org_trial_days') }}" onchange="setNextDate('#org_trial_days', '#org_trail_start_date', '#org_trail_end_date')">
                                                        </div>
                                                        <div class="col-3">
                                                            <label for="org_trail_start_date" class="form-label">Starting Date</label>
                                                            <input type="date" class="form-control today-date" id="org_trail_start_date" name="org_trail_start_date" value="{{ old('org_trail_start_date') }}" name="date" onchange="setDays('#org_trail_start_date','#org_trail_end_date','#org_trial_days'); setNextDate('#org_trial_days', '#org_trail_start_date', '#org_trail_end_date')">
                                                        </div>
                                                        <div class="col-3">
                                                            <label for="org_trail_end_date" class="form-label">Ending Date</label>
                                                            <input type="date" class="form-control" id="org_trail_end_date" name="org_trail_end_date" value="{{ old('org_trail_end_date') }}" name="date" onchange="setDays('#org_trail_start_date','#org_trail_end_date','#org_trial_days')">
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-3 d-flex align-items-center">
                                                            <input class="form-check-input" type="checkbox" name="payment[]" id="driver_payment" value="driver_payment" checked="">
                                                            <label class="form-check-label mx-1" for="driver_payment">Driver</label>
                                                        </div>
                                                        <div class="col-2 ">
                                                            <input class="form-control" type="number" placeholder="Amount" name="driver_amount" value="{{ old('driver_amount') }}" id="">
                                                        </div>
                                                        <div class="col-1 ">
                                                            <input class="form-control " type="number" name="driver_trial_days" id="driver_trial_days" value="{{ old('driver_trial_days') }}" onchange="setNextDate('#driver_trial_days', '#driver_trial_start_date', '#driver_trial_end_date')">
                                                        </div>
                                                        <div class="col-3">
                                                            <input type="date" class="form-control today-date" id="driver_trial_start_date" name="driver_trial_start_date" value="{{ old('driver_trial_start_date') }}" name="date" onchange="setDays('#driver_trial_start_date','#driver_trial_end_date','#driver_trial_days'); setNextDate('#driver_trial_days', '#driver_trial_start_date', '#driver_trial_end_date')">
                                                        </div>
                                                        <div class="col-3">
                                                            <input type="date" class="form-control" id="driver_trial_end_date" name="driver_trial_end_date" value="{{ old('driver_trial_end_date') }}" name="date" onchange="setDays('#driver_trial_start_date','#driver_trial_end_date','#driver_trial_days')">
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-3 d-flex align-items-center">
                                                            <input class="form-check-input" type="checkbox" name="payment[]" id="passenger_payment" value="passenger_payment" checked="">
                                                            <label class="form-check-label mx-1" for="passenger_payment">Passenger</label>
                                                        </div>
                                                        <div class="col-2 ">
                                                            <input class="form-control" type="number" placeholder="Amount" name="passenger_amount" value="{{ old('passenger_amount') }}">
                                                        </div>
                                                        <div class="col-1 ">
                                                            <input class="form-control" type="number" name="passenger_trial_days" id="passenger_trial_days" value="{{ old('passenger_trial_days') }}" onchange="setNextDate('#passenger_trial_days', '#passenger_trail_start_date', '#passenger_trail_end_date')">
                                                        </div>
                                                        <div class="col-3">
                                                            <input type="date" class="form-control today-date" id="passenger_trail_start_date" name="passenger_trail_start_date" value="{{ old('passenger_trail_start_date') }}" name="date">
                                                        </div>
                                                        <div class="col-3">
                                                            <input type="date" class="form-control" id="passenger_trail_end_date" name="passenger_trail_end_date" value="{{ old('passenger_trail_end_date') }}" name="date" onchange="setDays('#driver_trial_start_date','#driver_trial_end_date','#driver_trial_days')">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end mt-3">
                                                <button type="submit" class="btn btn-success waves-effect waves-light">Save</button>
                                            </div>
                                        </div> <!-- end row -->
                                    </div>
                                </div> <!-- tab-content -->
                            </form>
                        </div> <!-- end card-body -->
                    </div> <!-- end card-->
                </div>
            </div>
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

<!-- Jquery validation -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script> -->


<!-- Init js-->
<script src="/js/pages/form-wizard.init.js"></script>
<script>
    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select",
            allowClear: true,
            dropdownParent: $("#create_modal "), // modal : id modal
            width: "100%",
            height: "30px",
        });

        $(".select2_filter").select2({
            placeholder: "Select",
            allowClear: true,
            dropdownParent: $("#filter_form "), // modal : id modal
            width: "100%",
            height: "30px",
        });

        $('#org_state').change(function(e) {
            e.preventDefault();
            let id = $(this).val();
            let csrf_token = "{{ csrf_token() }}";
            $.ajax({
                type: "GET",
                url: "/get-cities/" + id,
                data: {
                    "_token": csrf_token
                },
                success: function(response) {
                    if (response.status == 'success') {
                        $('#org_city').empty();
                        let option = makeOptions(response.data)
                        $('#org_city').append(option);
                    }
                }
            });
        });

        $('.btn-close').click(function (e) { 
            e.preventDefault();
            $('#organization_creation')[0].reset();
        });
    });

    function orgDetailFormValidate() {
        let orgNameErr = orgTypeErr = orgEmailErr = orgPhoneErr = orgStateErr = orgCityErr = true;
        let orgName = $('#org_name').val();
        let orgType = $('#org_type').val();
        let orgEmail = $('#org_email').val();
        let orgPhone = $('#org_phone').val();
        let orgState = $('#org_state').val();
        let orgCity = $('#org_city').val();

        // Validate Organization Name
        if (orgName === "") {
            setErrorMsg('#org_name', "* Required!");
            orgNameErr = false;
        } else if (orgName.length <= 3) {
            setErrorMsg('#org_name', "* Name Must be greater than 3 Character!");
            orgNameErr = false;
        } else {
            setSuccessMsg('#org_name');
        }

        if (orgType === "") {
            setErrorMsg('#org_type', "* Required!");
            orgTypeErr = false;
        } else {
            setSuccessMsg('#org_type');
        }

        //Validate Email
        if (orgEmail === "") {
            setErrorMsg("#org_email", "* Required!");
            orgEmailErr = false;
        } else if (!isEmail(orgEmail)) {
            setErrorMsg("#org_email", "* invalid email format! ");
            orgEmailErr = false;
        } else {
            setSuccessMsg("#org_email");
        }

        //validate Phone
        if (orgPhone === "") {
            setErrorMsg('#org_phone', "* Required!");
            orgPhoneErr = false;
        } else if (!isPhone(orgPhone)) {
            setErrorMsg('#org_phone', "* Phone number length must be 11 digits and only numbers are allowed");
            orgPhoneErr = false;
        } else {
            setSuccessMsg('#org_phone');
        }

        //validate states
        if (orgState === "") {
            setErrorMsg('#org_state', "* Required!");
            orgStateErr = false;
        } else {
            setSuccessMsg('#org_state');
        }

        //validate Phone
        if (orgCity === "") {
            setErrorMsg('#org_city', "* Required!");
            orgCityErr = false;
        } else {
            setSuccessMsg('#org_city');
        }
    }

    function checkOrgDetailForm() {
        if (orgDetailFormValidate()) {
            $('#company_head_tab').prop('disabled', false);
            resetFormView();
            $('#company_head_tab').addClass('active');
            $('#company_head').addClass('active');
        } else {
            $('#company_head_tab').prop('disabled', true);
        }
    }

    function orgHeadFormValidate() {
        let orgHeadNameErr = orgHeadPhoneErr = orgHeadEmailErr = true;
        let orgHeadName = $('#org_head_name').val();
        let orgHeadPhone = $('#org_head_phone').val();
        let orgHeadEmail = $('#org_head_email').val();

        // Validate Organization Name
        if (orgHeadName === "") {
            setErrorMsg('#org_head_name', "* Required!");
            orgHeadNameErr = false;
        } else if (orgHeadName.length <= 3) {
            setErrorMsg('#org_head_name', "* Name Must be greater than 3 Character!");
            orgHeadNameErr = false;
        } else {
            setSuccessMsg('#org_head_name');
        }

        //Validate Email
        if (orgHeadEmail === "") {
            setErrorMsg("#org_head_email", "* Required!");
            orgHeadEmailErr = false;
        } else if (!isEmail(orgHeadEmail)) {
            setErrorMsg("#org_head_email", "* invalid email format! ");
            orgHeadEmailErr = false;
        } else {
            setSuccessMsg("#org_head_email");
        }

        //validate Phone
        if (orgHeadPhone === "") {
            setErrorMsg('#org_head_phone', "* Required!");
            orgHeadPhoneErr = false;
        } else if (!isPhone(orgHeadPhone)) {
            setErrorMsg('#org_head_phone', "* Phone number length must be 11 digits and only numbers are allowed");
            orgHeadPhoneErr = false;
        } else {
            setSuccessMsg('#org_head_phone');
        }

        if ((orgHeadNameErr && orgHeadEmailErr && orgHeadPhoneErr) == false) {
            return false;
        } else {
            return true;
        }
    }

    function checkOrgHeadForm() {
        if (orgHeadFormValidate()) {
            $('#transport_manager_tab').prop('disabled', false);
            resetFormView();
            $('#transport_manager_tab').addClass('active');
            $('#transport_manager').addClass('active');
        } else {
            $('#transport_manager_tab').prop('disabled', true);
        }
    }

    function resetFormView() {
        $('#company_tab').removeClass('active');
        $('#company').removeClass('active');
        $('#transport_manager_tab').removeClass('active');
        $('#transport_manager').removeClass('active');
        $('#financials_tabs').removeClass('active');
        $('#financials').removeClass('active');
        $('#company_head_tab').removeClass('active');
        $('#company_head').removeClass('active');
    }

    function transportManagerFormValidate() {
        let manNameErr = manPhoneErr = manEmailErr = true;
        let manName = $('#man_name').val();
        let manPhone = $('#man_phone').val();
        let manEmail = $('#man_email').val();

        // Validate Organization Name
        if (manName === "") {
            setErrorMsg('#man_name', "* Required!");
            manNameErr = false;
        } else if (manName.length <= 3) {
            setErrorMsg('#man_name', "* Name Must be greater than 3 Character!");
            manNameErr = false;
        } else {
            setSuccessMsg('#man_name');
        }

        //Validate Email
        if (manEmail === "") {
            setErrorMsg("#man_phone", "* Required!");
            manEmailErr = false;
        } else if (!isEmail(manEmail)) {
            setErrorMsg("#man_phone", "* invalid email format! ");
            manEmailErr = false;
        } else {
            setSuccessMsg("#man_phone");
        }

        //validate Phone
        if (manPhone === "") {
            setErrorMsg('#man_email', "* Required!");
            manPhoneErr = false;
        } else if (!isPhone(manPhone)) {
            setErrorMsg('#man_email', "* Phone number length must be 11 digits and only numbers are allowed");
            manPhoneErr = false;
        } else {
            setSuccessMsg('#man_email');
        }

        if ((manNameErr && manEmailErr && manPhoneErr) == false) {
            return false;
        } else {
            return true;
        }
    }

    function checkTransportManagerForm() {
        if (transportManagerFormValidate()) {
            $('#financials_tabs').prop('disabled', false);
            resetFormView();
            $('#financials_tabs').addClass('active');
            $('#financials').addClass('active');
        } else {
            $('#company_head_tab').prop('disabled', true);
        }
    }

    function setDays(startDateId, endDateId, setDaysId) {
        let start = $(startDateId).val();
        let end = $(endDateId).val();
        if (start != '' && end != '') {
            $(setDaysId).val(daysDifference(start, end));
        }
    }

    function setNextDate(daysId, startDateId, setEndDateId) {
        let date = new Date($(startDateId).val());
        let days = parseInt($(daysId).val(), 10);
        if (date != '' && days != '') {
            let nextDate = new Date(date.setDate(date.getDate() + days)).toISOString().split('.');
            output = nextDate[0].split('T')
            console.log(output[0]);
            if (output[0] != '') {
                $(setEndDateId).val(output[0]);
            }
        } else {}
    }

    function daysDifference(firstDate, secondDate) {
        var startDay = new Date(firstDate);
        var endDay = new Date(secondDate);

        // Determine the time difference between two dates     
        var millisBetween = startDay.getTime() - endDay.getTime();

        // Determine the number of days between two dates  
        var days = millisBetween / (1000 * 3600 * 24);

        // Show the final number of days between dates     
        return Math.round(Math.abs(days));
    }
</script>
@endsection