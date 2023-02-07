@extends('layouts.app')
@section('title', 'Add Managers')
<!-- start page title -->
@section('page_css')
@include('partials.datatable_css')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Add Managers</h4>
        </div>
    </div>
</div>

<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <button type="button" type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop"> Add Organization </button>
            </div>
            <div class="card-body">
                <h4 class="header-title">Latest Managers <a class="text-primary"> ({{ count($users) }}) </a> </h4>
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox">
                            </th>
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
                        @forelse ($users as $user)
                        <tr>
                            <td>
                                <input type="checkbox">
                            </td>
                            <td>20/12/2022</td>
                            <td>10:00 AM</td>
                            <td>{{ $user->otp }}</td>
                            <td><b><a href="#">{{ $user->name }}</a></b></td>
                            <td>UOG</td>
                            <td>14358</td>
                            <td>University</td>
                            <td>{{ $user->email }}</td>
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
                        @empty
                        <tr>
                            No Data Found
                        </tr>
                        @endforelse

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
                            <form action="{{ route('manager.store') }}" method="POST" enctype="multipart/form-data">
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
                                                        <input type="text" id="org_branch_name" name="org_branch_name" class="form-control" value="{{ old('org_branch_name') }}">
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
                                                        <input type="number" id="org_phone" name="org_phone" class="form-control" value="{{ old('org_phone') }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 row">
                                                    <div class="mb-3 col-4">
                                                        <label for="org_address" class="form-label">Address</label>
                                                        <input class="form-control" id="org_address" name="org_address" value="{{ old('org_address') }}"></input>
                                                    </div>
                                                    <div class="mb-3 col-4">
                                                        <label for="org_state" class="form-label">State</label>
                                                        <select class="form-select" id="org_state" name="org_state">
                                                            <option value="1">Punjab</option>
                                                            <option value="2">Sindh</option>
                                                            <option value="3">Balochistan</option>
                                                            <option value="4">KPK</option>
                                                            <option value="5">Gilgit Baltistan</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-4">
                                                        <label for="org_city" class="form-label">City</label>
                                                        <select class="form-select" id="org_city" name="org_city">
                                                            <option value="1">Lahore</option>
                                                            <option value="2">Islamabad</option>
                                                            <option value="3">Karachi</option>
                                                            <option value="4">Gujranwala</option>
                                                            <option value="5">Faiasalabad</option>
                                                        </select>
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
                                                        <label for="phone" class="form-label">Phone No</label>
                                                        <input type="number" id="phone" name="phone" value="{{ old('phone') }}" class="form-control">
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
                                                        <input type="email" id="man_email" name="man_email" value="{{ old('man_email') }}" class="form-control" placeholder="Email">
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
                                                    <h4 class="header-title">Who will charge the fee form:</h4>
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
                                                    <h4 class="header-title">Basis of payment first:</h4>
                                                    <div class="row">
                                                        <div class="col-3 d-flex align-items-center">
                                                            <input class="form-check-input" type="checkbox" name="payment[]" id="org_payment" value="org_payment" checked="">
                                                            <label class="form-check-label mx-1" for="org_payment">Organization</label>
                                                        </div>
                                                        <div class="col-3 ">
                                                            <label for="org_amount" class="form-label">Amount</label>
                                                            <input class="form-control" type="number" placeholder="Amount" name="org_amount" value="{{ old('org_amount') }}">
                                                        </div>
                                                        <div class="col-2 ">
                                                            <label for="org_trial_days" class="form-label">Trial Days</label>
                                                            <input class="form-control" type="number" name="org_trial_days" value="{{ old('org_trial_days') }}">
                                                        </div>
                                                        <div class="col-2">
                                                            <label for="org_trail_start_date" class="form-label">Starting Date</label>
                                                            <input type="date" class="form-control" id="org_trail_start_date" name="org_trail_start_date" value="{{ old('org_trail_start_date') }}" name="date">
                                                        </div>
                                                        <div class="col-2">
                                                            <label for="org_trail_end_date" class="form-label">Ending Date</label>
                                                            <input type="date" class="form-control" id="org_trail_end_date" name="org_trail_end_date" value="{{ old('org_trail_end_date') }}" name="date">
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-3 d-flex align-items-center">
                                                            <input class="form-check-input" type="checkbox" name="payment[]" id="driver_payment" value="driver_payment" checked="">
                                                            <label class="form-check-label mx-1" for="driver_payment">Driver</label>
                                                        </div>
                                                        <div class="col-3 ">
                                                            <input class="form-control" type="number" placeholder="Amount" name="driver_amount" value="{{ old('driver_amount') }}">
                                                        </div>
                                                        <div class="col-2 ">
                                                            <input class="form-control" type="number" name="driver_trial_days" value="{{ old('driver_trial_days') }}">
                                                        </div>
                                                        <div class="col-2">
                                                            <input type="date" class="form-control" id="driver_trial_start_date" name="driver_trial_start_date" value="{{ old('driver_trial_start_date') }}" name="date">
                                                        </div>
                                                        <div class="col-2">
                                                            <input type="date" class="form-control" id="driver_trial_end_date" name="driver_trial_end_date" value="{{ old('driver_trial_end_date') }}" name="date">
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-3 d-flex align-items-center">
                                                            <input class="form-check-input" type="checkbox" name="payment[]" id="passenger_payment" value="passenger_payment" checked="">
                                                            <label class="form-check-label mx-1" for="passenger_payment">Passenger</label>
                                                        </div>
                                                        <div class="col-3 ">
                                                            <input class="form-control" type="number" placeholder="Amount" name="passenger_amount" value="{{ old('passenger_amount') }}">
                                                        </div>
                                                        <div class="col-2 ">
                                                            <input class="form-control" type="number" name="passenger_trial_days" value="{{ old('passenger_trial_days') }}">
                                                        </div>
                                                        <div class="col-2">
                                                            <input type="date" class="form-control" id="passenger_trail_start_date" name="passenger_trail_start_date" value="{{ old('passenger_trail_start_date') }}" name="date">
                                                        </div>
                                                        <div class="col-2">
                                                            <input type="date" class="form-control" id="passenger_trail_end_date" name="passenger_trail_end_date" value="{{ old('passenger_trail_end_date') }}" name="date">
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
@include('partials.datatable_js')
<!-- Plugins js-->
<script src="/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

<!-- Init js-->
<script src="/js/pages/form-wizard.init.js"></script>
<script>
    $(document).ready(function() {

    });
</script>
@endsection