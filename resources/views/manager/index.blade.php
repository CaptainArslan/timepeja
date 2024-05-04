@extends('layouts.app')
@section('title', 'Add Managers')
<!-- start page title -->
@section('page_css')
<!-- Plugins css -->
<link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App css -->
<link href="{{ asset('libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
<link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
@include('partials.datatable_css')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between">
            <h4 class="page-title">Organizations {{ old('o_id') }}</h4>
            <div class="page-title">
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#createModal"> Add Organization </button>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('manager.index') }}" method="POST" id="filterForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <label for="organization">Select Oganization</label>
                            <select class="form-control select2Filter" id="organization_filter" name="o_id">
                                <option value="" selected>Select</option>
                                @forelse ($org_dropdowns as $organization)
                                <option value="{{ $organization->id }}" {{ $organization->id == request()->input('o_id') ? 'selected' : '' }}>{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>
                                @empty
                                <option value="">Please select</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="from">Registration From</label>
                            <input type="date" class="form-control" name="from" value="{{ request()->input('from', old('from')) }}">
                        </div>
                        <div class="col-md-3">
                            <label for="to">Registration To</label>
                            <input type="date" class="form-control" name="to" value="{{ request()->input('to', old('to')) }}">
                        </div>
                        <div class="col-md-1">
                            <!-- <label for="route_list"></label> -->
                            <button type="submit" class="btn btn-success" id="route_list" value="filter" name="filter" style="margin-top: 20px;"> Submit </button>
                        </div>
                    </div>
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- Filters Ends -->

<!-- Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="col-2">
                    <h4 class="header-title"> {{ !isset($_POST['filter']) ? 'Latest' : '' }} Managers </h4>
                </div>
                <div class="col-9">
                    <!-- <div class="row">
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="example-from" name="organization" value="" readonly style="font-weight: bold;">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="example-from" name="from">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="example-to" name="to">
                        </div>
                    </div> -->
                </div>
                <div class="col-1">
                    <!-- <button class="btn btn-dark">Print</button> -->
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
                            <th>Org Branch </th>
                            <th>Org Types</th>
                            <th>Org Email</th>
                            <th>Org Phone No</th>
                            <th>Org Address</th>
                            <th>Transport Manager</th>
                            <th>Manager Phone No</th>
                            <th>Manager Email</th>
                            <th>Manager Address</th>
                            <th>Manager Picture</th>
                            <th>Head Name</th>
                            <th>Head Email</th>
                            <th>Head Phone No</th>
                            <th>Head Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($organizations as $organization)
                        <tr>
                            <td> {{ formatDate($organization->created_at) }} </td>
                            <td> {{ formatTime($organization->created_at) }} </td>
                            <td> {{ ($organization->manager['otp']) ?? '' }} </td>
                            <td> {{-- $organization->id --}} {{ $organization->name }} </td>
                            <td> {{ isset($organization->branch_code) ? $organization->branch_code . ' - ' : '' }} {{ $organization->branch_name }} </td>
                            <td> {{-- isset($organization->organizationType['id']) ? $organization->organizationType['id'] .' - ' : '' --}} {{ ($organization->organizationType['name']) ?? '' }} </td>
                            <td> {{ $organization->email }} </td>
                            <td> {{ $organization->phone }} </td>
                            <td> {{ $organization->address }} </td>
                            <td> {{ $organization->manager['name']  }} </td>
                            <td> {{ $organization->manager['phone'] }} </td>
                            <td> {{ $organization->manager['email'] }} </td>
                            <td> {{ $organization->manager['address'] }} </td>
                            <td> <img src="{{ $organization->manager['picture'] }}" alt="" style="height: 50px; width: 50px; object-fit: contain;"> </td>
                            <td> {{ $organization->head_name}} </td>
                            <td> {{ $organization->head_email}} </td>
                            <td> {{ $organization->head_phone}} </td>
                            <td> {{ $organization->head_address}} </td>
                            <td>
                                <input type="hidden" name="" id="" value="{{ formatDate($organization->create_at) }}" class="db_date">
                                <input type="hidden" name="" id="" value="{{ formatTime($organization->create_at) }}" class="db_time">
                                <input type="hidden" name="" id="" value="{{ ($organization->manager['otp'])  }}" class="db_otp">
                                <input type="hidden" name="" id="" value="{{ $organization->name }}" class="db_org_name">
                                <input type="hidden" name="" id="" value="{{ $organization->id }}" class="db_org_id">
                                <input type="hidden" name="" id="" value="{{ $organization->branch_name }}" class="db_branch_name">
                                <input type="hidden" name="" id="" value="{{ $organization->branch_code }}" class="db_branch_code">
                                <input type="hidden" name="" id="" value="{{ isset($organization->organizationType['id']) ? $organization->organizationType['id'] .' - ' : ''  }}" class="db_org_type">
                                <input type="hidden" name="" id="" value="{{ $organization->email }}" class="db_org_email">
                                <input type="hidden" name="" id="" value="{{ $organization->phone }}" class="db_org_phone">
                                <input type="hidden" name="" id="" value="{{ $organization->address }}" class="db_org_address">
                                <input type="hidden" name="" id="" value="{{ $organization->manager['name']  }}" class="db_man_name">
                                <input type="hidden" name="" id="" value="{{ $organization->manager['email'] }}" class="db_man_email">
                                <input type="hidden" name="" id="" value="{{ $organization->manager['phone'] }}" class="db_man_phone">
                                <input type="hidden" name="" id="" value="{{ $organization->manager['address'] }}" class="db_man_manager_address">
                                <input type="hidden" name="" id="" value="{{ $organization->manager['picture'] }}" class="db_man_picture">
                                <input type="hidden" name="" id="" value="{{ $organization->head_name}}" class="db_head_name">
                                <input type="hidden" name="" id="" value="{{ $organization->head_email}}" class="db_head_email">
                                <input type="hidden" name="" id="" value="{{ $organization->head_phone}}" class="db_head_phone">
                                <input type="hidden" name="" id="" value="{{ $organization->head_address}}" class="db_head_address">
                                <!-- <div class="btn-group btn-group-sm" style="float: none;">
                                    <button type="button" class="tabledit-edit-button btn btn-success" style="float: none;" data-bs-toggle="modal" data-bs-target="#createModal">
                                        <span class="mdi mdi-pencil"></span>
                                    </button>
                                </div> -->
                                <div class="btn-group btn-group-sm delete_organization" style="float: none;" data-id="{{ $organization->id }}" onclick="deleteOrganization(this)">
                                    <button type="button" class="tabledit-edit-button btn btn-danger" style="float: none;">
                                        <span class="mdi mdi-delete"></span>
                                    </button>
                                </div>
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
<!-- Table Ends -->

<!-- Modal -->
<div class="modal fade" id="createModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="createModalLabel">Add Organization</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                            <a href="#company_head" data-bs-toggle="tab" id="company_head_tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2"
                                            onclick="checkOrgDetailForm()" disabled="disabled">
                                                <i class="fas fa-user"></i>
                                                <span class="d-none d-sm-inline">Company Head</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#transport_manager" data-bs-toggle="tab" data-toggle="tab" id="transport_manager_tab" class="nav-link rounded-0 pt-2 pb-2" onclick="checkOrgHeadForm()" disabled="disabled">
                                                <!--  -->
                                                <i class="fas fa-bus-alt"></i>
                                                <span class="d-none d-sm-inline">Transport Manager</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#financials" id="financials_tabs" value="financials" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2" onclick="checkTransportManagerForm()" disabled="disabled">
                                                <!--  -->
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
                                                        <input type="text" id="org_name" name="org_name" value="{{ old('org_name') }}" class="form-control" placeholder="Punjab University" required>
                                                        <span class="text-danger" id="org_name_error"></span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="org_branch_code" class="form-label">Branch code</label>
                                                        <input type="text" id="org_branch_code" name="org_branch_code" value="{{ old('org_branch_code') }}" class="form-control" data-toggle="input-mask" data-mask-format="00000000000" placeholder="12345">
                                                        <span class="text-danger" id="org_branch_code_error"></span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="org_email" class="form-label">Email</label>
                                                        <input type="email" id="org_email" name="org_email" class="form-control" value="{{ old('org_email') }}" placeholder="text@gmail.com" required>
                                                        <span class="text-danger" id="org_email_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="org_branch_name" class="form-label">Branch Name</label>
                                                        <input type="text" id="org_branch_name" name="org_branch_name" class="form-control" value="{{ old('org_branch_name') }}" placeholder="Lahore branch">
                                                        <span class="text-danger" id="org_branch_name_error"></span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="org_type" class="form-label">Company Title</label>
                                                        <select class="form-select select2" id="org_type" name="org_type" required>
                                                            <option value="" selected>Please Select Organization Type</option>
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
                                                        <input type="text" id="org_phone" name="org_phone" class="form-control" data-toggle="input-mask" data-mask-format="0000-0000000" value="{{ old('org_phone') }}" placeholder="0300-1234567" required>
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
                                                        <input type="text" id="org_head_name" name="org_head_name" placeholder="John Doe" value="{{ old('org_head_name') }}" class="form-control" required>
                                                        <span class="text-danger" id="org_head_name_error"></span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="org_head_phone" class="form-label">Phone No</label>
                                                        <input type="text" id="org_head_phone" name="org_head_phone" data-toggle="input-mask" data-mask-format="0000-0000000" value="{{ old('org_head_phone') }}" placeholder="0300-1234567" class="form-control" required>
                                                        <span class="text-danger" id="org_head_phone_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="org_head_email" class="form-label">Email</label>
                                                        <input type="email" id="org_head_email" name="org_head_email" placeholder="test@gmail.com" value="{{ old('org_head_email') }}" class="form-control" placeholder="Email" required>
                                                        <span class="text-danger" id="org_head_email_error"></span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="org_head_address" class="form-label">Address</label>
                                                        <input class="form-control" id="org_head_address" name="org_head_address" value="{{ old('org_head_address') }}" rows="5"></input>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Company transport manager -->
                                        <div class="tab-pane" id="transport_manager">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="man_name" class="form-label">Name</label>
                                                        <input type="text" id="man_name" name="man_name" value="{{ old('man_name') }}" placeholder="John Doe" class="form-control" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="phone" class="form-label">Phone No</label>
                                                        <input type="text" id="man_phone" name="man_phone" data-toggle="input-mask" data-mask-format="0000-0000000" value="{{ old('phone') }}" placeholder="0300-1234567" class="form-control" required>
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
                                                        <input type="email" id="man_email" name="man_email" value="{{ old('man_email') }}" aria-placeholder="text@gmail.com" class="form-control" placeholder="Email" required>
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
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="man_address" class="form-label">Address</label>
                                                        <textarea class="form-control" name="man_address" id="man_address" value="{{ old('man_address') }}" cols="30" rows="2" placeholder="123 Street lahore, Pakistan"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Company financials -->
                                        <div class="tab-pane" id="financials">
                                            <!-- Wallet Activation -->
                                            <div class="row">
                                                <div class="col-md-12 mt-2">
                                                    <h4 class="header-title">Who we will charge the fee from:</h4>
                                                    <div class="form-check mb-2 form-check-primary">
                                                        <input class="form-check-input" type="checkbox" name="wallet[]" id="org_wallet" value="org_wallet" required>
                                                        <label class="form-check-label" for="org_wallet">Organization</label>
                                                    </div>
                                                    <div class="form-check mb-2 form-check-primary">
                                                        <input class="form-check-input" type="checkbox" name="wallet[]" id="driver_wallet" value="driver_wallet" onclick="driverWallet(this)">
                                                        <label class="form-check-label" for="driver_wallet">Sub Contracting Driver</label>
                                                    </div>
                                                    <div class="form-check mb-2 form-check-primary">
                                                        <input class="form-check-input" type="checkbox" name="wallet[]" id="passenger_wallet" value="passenger_wallet" onclick="passengerWallet(this)">
                                                        <label class="form-check-label" for="passenger_wallet">Passengers</label>
                                                    </div>
                                                </div>

                                                <!-- Amount of all users -->
                                                <div class="col-md-12 mt-3 mb-2">
                                                    <h4 class="header-title">Basis of payment calculation:</h4>
                                                    <div class="row">
                                                        <div class="col-3 d-flex align-items-center">
                                                            <input class="form-check-input" type="checkbox" name="payment[]" id="org_payment" value="org_payment" onchange="orgPaymentCheck()">
                                                            <label class="form-check-label mx-1" for="org_payment">Organization</label>
                                                        </div>
                                                        <div class="col-2">
                                                            <label for="org_amount" class="form-label">Amount</label>
                                                            <input class="form-control" type="number" placeholder="Amount" name="org_amount" id="org_amount" value="{{ old('org_amount') }}">
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
                                                            <input class="form-check-input" type="checkbox" name="payment[]" id="driver_payment" value="driver_payment" onchange="driverPaymentCheck()">
                                                            <label class="form-check-label mx-1" for="driver_payment">Driver</label>
                                                        </div>
                                                        <div class="col-2 ">
                                                            <input class="form-control" type="number" placeholder="Amount" name="driver_amount" value="{{ old('driver_amount') }}" id="driver_amount">
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
                                                            <input class="form-check-input" type="checkbox" name="payment[]" id="passenger_payment" value="passenger_payment" onchange="passengerPaymentCheck()">
                                                            <label class="form-check-label mx-1" for="passenger_payment">Passenger</label>
                                                        </div>
                                                        <div class="col-2 ">
                                                            <input class="form-control" type="number" placeholder="Amount" name="passenger_amount" id="passenger_amount" value="{{ old('passenger_amount') }}">
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
                                        </div>
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
        initializeSelect2(".select2Filter", "#filterForm");

        // alert("{{old('organzation_id')}}")
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

        $('.btn-close').click(function(e) {
            e.preventDefault();
            $('#organization_creation')[0].reset();
        });

        // this function is call when add route modal open
        $('#createModal').on('shown.bs.modal', function() {
            initializeSelect2(".select2", "#createModal")
        });
    });

    function orgPaymentCheck() {
        $("#org_amount").prop("required", false);
        $("#org_trial_days").prop("required", false);
        $("#org_trail_start_date").prop("required", false);
        $("#org_trail_end_date").prop("required", false);
        if ($("#driver_payment").is(":checked")) {
            $("#org_amount").prop("required", true);
            $("#org_trial_days").prop("required", true);
            $("#org_trail_start_date").prop("required", true);
            $("#org_trail_end_date").prop("required", true);
        }
    }

    function driverPaymentCheck() {
        alert("hello");
        $("#driver_amount").prop("required", false);
        $("#driver_trial_days").prop("required", false);
        $("#driver_trial_start_date").prop("required", false);
        $("#driver_trial_end_date").prop("required", false);
        if ($("#driver_payment").is(":checked")) {
            $("#driver_amount").prop("required", true);
            $("#driver_trial_days").prop("required", true);
            $("#driver_trial_start_date").prop("required", true);
            $("#driver_trial_end_date").prop("required", true);
        }
    }


    function passengerPaymentCheck() {
        $("#passenger_amount").prop("required", false);
        $("#passenger_trial_days").prop("required", false);
        $("#passenger_trail_start_date").prop("required", false);
        $("#passenger_trail_end_date").prop("required", false);
        if ($("#driver_payment").is(":checked")) {
            $("#passenger_amount").prop("required", true);
            $("#passenger_trial_days").prop("required", true);
            $("#passenger_trail_start_date").prop("required", true);
            $("#passenger_trail_end_date").prop("required", true);
        }
    }

    function driverWallet(param) {
        if ($(param).is(":checked")) {
            $("#driver_payment").prop("checked", true);
        } else {
            $("#driver_payment").prop("checked", false);
        }
    }

    function passengerWallet(param) {
        if ($(param).is(":checked")) {
            $("#passenger_payment").prop("checked", true);
        } else {
            $("#passenger_payment").prop("checked", false);
        }
    }

    function deleteOrganization(param) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Once Deleted, All your record regarding to this Organization will be deleted!",
            icon: 'warning',
            confirmButtonColor: '#e64942',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: `No`,
        }).then((result) => {
            if (result.isConfirmed) {
                // var el = param;
                let id = $(param).data('id');
                // alert(id)
                let csrf_token = "{{ csrf_token() }}";

                $.ajax({
                    type: "DELETE",
                    url: "/organizatrion/" + id,
                    data: {
                        "_token": csrf_token
                    },
                    success: function(response) {
                        console.log(response)
                        if (response != '' && response.status == 'success') {
                            $(param).parent().find('.child').css('background', 'tomato');
                            $(param).parent().find('.dt-hasChild').css('background', 'tomato');
                            $(param).closest('tr').fadeOut(800, function() {
                                $(this).parent().find('.dt-hasChild').remove();
                                $(this).parent().find('.child').remove();
                            });
                        } else {
                            alert('error occured while deltion ')
                        }
                    },
                    error: (error) => {
                        console.log(JSON.stringify(error));
                    }
                });
            }
        });
    }

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
        }
        // else if (!isPhone(orgPhone)) {
        //     setErrorMsg('#org_phone', "* Invalid format!");
        //     orgPhoneErr = false;
        // } 
        else {
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

        if ((orgNameErr && orgTypeErr && orgEmailErr && orgPhoneErr && orgStateErr && orgCityErr) == false) {
            return false;
        } else {
            return true;
        }
    }

    function checkOrgDetailForm() {
        if (orgDetailFormValidate()) {
            $('#company_head_tab').removeAttr('disabled');
            resetFormView();
            $('#company_head_tab').addClass('active');
            $('#company_head').addClass('active');
        } else {
            $("#company_head_tab").attr("disabled", true);
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
        }
        // else if (!isPhone(orgHeadPhone)) {
        //     setErrorMsg('#org_head_phone', "* Phone number length must be 11 digits and only numbers are allowed");
        //     orgHeadPhoneErr = false;
        // } 
        else {
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
            $('#transport_manager_tab').removeAttr('disabled');
            resetFormView();
            $('#transport_manager_tab').addClass('active');
            $('#transport_manager').addClass('active');
        } else {
            $('#transport_manager_tab').attr('disabled', true);
        }
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
            setErrorMsg("#man_email", "* Required!");
            manEmailErr = false;
        } else if (!isEmail(manEmail)) {
            setErrorMsg("#man_email", "* invalid email format! ");
            manEmailErr = false;
        } else {
            setSuccessMsg("#man_email");
        }

        //validate Phone
        if (manPhone === "") {
            setErrorMsg('#man_phone', "* Required!");
            manPhoneErr = false;
        }
        // else if (!isPhone(manPhone)) {
        //     setErrorMsg('#man_email', "* Phone number length must be 11 digits and only numbers are allowed");
        //     manPhoneErr = false;
        // } 
        else {
            setSuccessMsg('#man_phone');
        }

        if ((manNameErr && manEmailErr && manPhoneErr) == false) {
            return false;
        } else {
            return true;
        }
    }

    function checkTransportManagerForm() {
        if (transportManagerFormValidate()) {
            $('#financials_tabs').removeAttr('disabled');
            resetFormView();
            $('#financials_tabs').addClass('active');
            $('#financials').addClass('active');
        } else {
            $('#financials_tabs').attr('disabled', true);
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
        // reset the attribute to disable
        $("#company_head_tab").attr("disabled", true);
        $('#transport_manager_tab').attr('disabled', true);
        $('#financials_tabs').attr('disabled', true);
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