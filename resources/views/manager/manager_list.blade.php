@extends('layouts.app')
@section('title', 'All Managers')
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
            <h4 class="page-title">All Managers</h4>
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
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h4 class="header-title">Managers List</h4>
                    <button class="btn btn-danger">Delete</button>
                </div>
            </div>
            <div class="card-body">
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox">
                            </th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Org Name</th>
                            <th>Branch Name</th>
                            <th>Branch Code</th>
                            <th>Org Types</th>
                            <th>Email</th>
                            <th>Phone No</th>
                            <th>Address</th>
                            <th>Head Name</th>
                            <th>Email</th>
                            <th>Phone No</th>
                            <th>Address</th>
                            <th>Transport Manager</th>
                            <th>Phone No</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Manager Picture</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox">
                            </td>
                            <td>20/12/2022</td>
                            <td>10:00 AM</td>
                            <td><b><a href="#">Tiger Nixon</a></b></td>
                            <td>UOG</td>
                            <td>14358</td>
                            <td>University</td>
                            <td>uoguni@gmail.com</td>
                            <td>055-486215</td>
                            <td>Gujrat,Pakistan</td>
                            <td>Shami</td>
                            <td>shami@gmail.com</td>
                            <td>055-435258</td>
                            <td>Lahore Punjab Pakistan</td>
                            <td>Qasim</td>
                            <td>qasim@gmail.com</td>
                            <td>055-435258</td>
                            <td>Lahore Punjab Pakistan</td>
                            <td>img</td>
                            <td>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" type="button" class="tabledit-edit-button btn btn-success" style="float: none;" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><span class="mdi mdi-pencil"></span></button></div>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" type="button" class="tabledit-edit-button btn btn-danger" style="float: none;"><span class="mdi mdi-delete"></span></button></div>
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
                <h5 class="modal-title" id="staticBackdropLabel">Add Organization</h5>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-xl-12">
                    <div class="card shadow-none">
                        <div class="card-body">
                            <form action="{{ route('managers.store') }}" method="POST">
                                @csrf
                                <div id="basicwizard">
                                    <ul class="nav nav-pills bg-light nav-justified form-wizard-header mb-4">
                                        <li class="nav-item">
                                            <a href="#company" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                <i class="fas fa-align-center"></i>
                                                <span class="d-none d-sm-inline">Organization Detail</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#company_head" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                <i class="fas fa-user"></i>
                                                <span class="d-none d-sm-inline">Company Head</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#transport_manager" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                <i class="fas fa-bus-alt"></i>
                                                <span class="d-none d-sm-inline">Transport Manager</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#financials" id="financials_tabs" value="financials" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
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
                                                        <input type="text" id="org_name" name="org_name" value="{{ old('org_name') }}" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="org_branch_code" class="form-label">Branch code</label>
                                                        <input type="number" id="org_branch_code" name="org_branch_code" value="{{ old('org_branch_code') }}" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="org_email" class="form-label">Email</label>
                                                        <input type="email" id="org_email" name="org_email" class="form-control" value="{{ old('org_email') }}" placeholder="Email">
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="org_branch_name" class="form-label">Branch Name</label>
                                                        <input type="text" id="org_branch_name" class="form-control" value="{{ old('org_branch_name') }}" name="org_branch_name">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="org_type" class="form-label">Types</label>
                                                        <select class="form-select" id="org_type" name="org_type">
                                                            <option value="">Please Select Organization Type</option>
                                                            @forelse ($organizaton_types as $organizaton_type)
                                                            <option value="{{$organizaton_type->id}}">{{$organizaton_type->name}} ({{$organizaton_type->desc}})</option>
                                                            @empty
                                                            <option>No Option Found</option>
                                                            @endforelse
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="org_phone" class="form-label">Phone No</label>
                                                        <input type="number" id="org_phone" class="form-control" value="{{ old('org_phone') }}" name="org_phone">
                                                    </div>
                                                </div> <!-- end col -->
                                                <div class="mb-3">
                                                    <label for="org_address" class="form-label">Address</label>
                                                    <input class="form-control" id="org_address" name="org_address" value="{{ old('org_address') }}"></input>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Oganization head -->
                                        <div class="tab-pane" id="company_head">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="org_head_name" class="form-label">Head Name</label>
                                                        <input type="text" id="org_head_name" name="org_head_name" value="{{ old('org_head_name') }}" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="org_head_phone" class="form-label">Phone No</label>
                                                        <input type="number" id="org_head_phone" name="org_head_phone" value="{{ old('org_head_phone') }}" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="org_head_email" class="form-label">Email</label>
                                                        <input type="email" id="org_head_email" name="org_head_email" value="{{ old('org_head_email') }}" class="form-control" placeholder="Email">
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
                                                        <input type="text" id="man_name" name="man_name" value="{{ old('man_name') }}" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="man_phone" class="form-label">Phone No</label>
                                                        <input type="number" id="man_phone" name="man_phone" value="{{ old('man_phone') }}" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="man_email" class="form-label">Email</label>
                                                        <input type="email" id="man_email" name="man_email" value="{{ old('man_email') }}" class="form-control" placeholder="Email">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="man_pic" class="form-label">Manager Picture</label>
                                                        <input type="file" id="man_pic" name="man_pic" value="{{ old('man_pic') }}" class="form-control">
                                                    </div>
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
                                            <div class="row mt-2 mb-2">
                                                <div class="row ">
                                                    <h4 class="header-title">Organization Free Trial:</h4>
                                                    <div class="col-lg-4 mb-2">
                                                        <label for="org_trail_days" class="form-label">Day's</label>
                                                        <input type="number" id="org_trail_days" name="org_trail_days" class="form-control">
                                                    </div>
                                                    <div class="col-lg-4 mb-2">
                                                        <label for="org_start_date" class="form-label">Starting Date</label>
                                                        <input class="form-control" id="org_start_date" name="org_start_date" value="{{ old('org_start_date') }}" type="date" name="date">
                                                    </div>
                                                    <div class="col-lg-4  mb-2">
                                                        <label for="org_end_date" class="form-label">Ending Date</label>
                                                        <input class="form-control" id="org_end_date" name="org_end_date" value="{{ old('org_end_date') }}" type="date" name="date">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 mt-2">
                                                        <h4 class="header-title">Who will charge the fee form:</h4>
                                                        <div class="form-check mb-2 form-check-primary">
                                                            <input class="form-check-input" type="checkbox" name="wallet[]" id="manager_wallet" value="manager_wallet">
                                                            <label class="form-check-label" for="manager_wallet">Manager</label>
                                                        </div>

                                                        <div class="form-check mb-2 form-check-success">
                                                            <input class="form-check-input" type="checkbox" name="wallet[]" id="driver_wallet" value="driver_wallet">
                                                            <label class="form-check-label" for="driver_wallet">Driver</label>
                                                        </div>

                                                        <div class="form-check mb-2 form-check-danger">
                                                            <input class="form-check-input" type="checkbox" name="wallet[]" id="passenger_wallet" value="passenger_wallet">
                                                            <label class="form-check-label" for="passenger_wallet">Passengers</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mt-3 mb-2">
                                                        <h4 class="header-title">Basis of payment first:</h4>
                                                        <div class="row">
                                                            <div class="col-3 d-flex align-items-center">
                                                                <input class="form-check-input" type="checkbox" name="payment[]" id="manager_payment" value="manager_payment" checked="">
                                                                <label class="form-check-label mx-1" for="manager_payment">Manager</label>
                                                            </div>
                                                            <div class="col-3 ">
                                                                <label for="org_start_date" class="form-label">Amount</label>
                                                                <input class="form-control" type="number" placeholder="Amount" name="manager_amount" value="{{ old('manager_amount') }}">
                                                            </div>
                                                            <div class="col-3">
                                                                <label for="org_start_date" class="form-label">Starting Date</label>
                                                                <input class="form-control" id="org_start_date" name="org_start_date" value="{{ old('org_start_date') }}" type="date" name="date">
                                                            </div>
                                                            <div class="col-3">
                                                                <label for="org_end_date" class="form-label">Ending Date</label>
                                                                <input class="form-control" id="org_end_date" name="org_end_date" value="{{ old('org_end_date') }}" type="date" name="date">
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-3 d-flex align-items-center">
                                                                <input class="form-check-input" type="checkbox" name="payment[]" id="manager_payment" value="manager_payment" checked="">
                                                                <label class="form-check-label mx-1" for="manager_payment">Driver</label>
                                                            </div>
                                                            <div class="col-3 ">
                                                                <!-- <label for="org_start_date" class="form-label">Amount</label> -->
                                                                <input class="form-control" type="number" placeholder="Amount" name="manager_amount" value="{{ old('manager_amount') }}">
                                                            </div>
                                                            <div class="col-3">
                                                                <!-- <label for="org_start_date" class="form-label">Starting Date</label> -->
                                                                <input class="form-control" id="org_start_date" name="org_start_date" value="{{ old('org_start_date') }}" type="date" name="date">
                                                            </div>
                                                            <div class="col-3">
                                                                <!-- <label for="org_end_date" class="form-label">Ending Date</label> -->
                                                                <input class="form-control" id="org_end_date" name="org_end_date" value="{{ old('org_end_date') }}" type="date" name="date">
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-3 d-flex align-items-center">
                                                                <input class="form-check-input" type="checkbox" name="payment[]" id="manager_payment" value="manager_payment" checked="">
                                                                <label class="form-check-label mx-1" for="manager_payment">Passenger</label>
                                                            </div>
                                                            <div class="col-3 ">
                                                                <!-- <label for="org_start_date" class="form-label">Amount</label> -->
                                                                <input class="form-control" type="number" placeholder="Amount" name="manager_amount" value="{{ old('manager_amount') }}">
                                                            </div>
                                                            <div class="col-3">
                                                                <!-- <label for="org_start_date" class="form-label">Starting Date</label> -->
                                                                <input class="form-control" id="org_start_date" name="org_start_date" value="{{ old('org_start_date') }}" type="date" name="date">
                                                            </div>
                                                            <div class="col-3">
                                                                <!-- <label for="org_end_date" class="form-label">Ending Date</label> -->
                                                                <input class="form-control" id="org_end_date" name="org_end_date" value="{{ old('org_end_date') }}" type="date" name="date">
                                                            </div>
                                                        </div>
                                                        <!--<div class="d-flex mt-1" style="justify-content: center; align-items: center;">
                                                            <div class="form-check form-check-success col-lg-4">
                                                                <input class="form-check-input" type="checkbox" value="driver_payment" id="driver1" name="payment[]" checked="">
                                                                <label class="form-check-label" for="driver1">Driver</label>
                                                            </div>
                                                            <div class="col-lg-8 d-flex">
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" type="number" placeholder="Amount" name="driver_amount">
                                                                </div>
                                                                <div class="col-lg-6 px-1">
                                                                    <input class="form-control" type="date" id="driver_trail_end_date" name="driver_trail_end_date"
                                                                    placeholder="Select date">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex mt-1" style="justify-content: center; align-items: center;">
                                                            <div class="form-check form-check-danger col-lg-4">
                                                                <input class="form-check-input" type="checkbox" value="passenger_payment" id="passenger_payment" name="payment[]" checked="">
                                                                <label class="form-check-label" for="passenger_payment">Passenger</label>
                                                            </div>
                                                            <div class="col-lg-8 d-flex">
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" type="number" placeholder="Amount" name="passenger_amount">
                                                                </div>
                                                                <div class="col-lg-6 px-1">
                                                                    <input class="form-control" type="date" id="example-date" placeholder="Select date" name="passenger_trail_end_date">
                                                                </div>
                                                            </div>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div> <!-- end row -->
                                            <div class="text-end">
                                                <button type="button" type="submit" class="btn btn-success waves-effect waves-light">Save</button>
                                            </div>
                                        </div>
                                    </div> <!-- tab-content -->
                                </div> <!-- end #basicwizard-->
                            </form>
                        </div> <!-- end card-body -->
                    </div> <!-- end card-->
                </div>
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


@endsection