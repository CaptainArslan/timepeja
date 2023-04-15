@extends('layouts.app')
@section('title', 'Drivers | Timepejao')
<!-- start page title -->
@section('page_css')
@include('partials.datatable_css')
<!-- Plugins css -->
<link href="/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />
<link href="/libs/dropify/css/dropify.min.css" rel="stylesheet" type="text/css" />
<link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
@endsection
@section('content')


<div class="row">
    <div class="col-12">
        <!-- <div class="page-title-box">
            <h4 class="page-title">Add Drivers</h4>
        </div> -->
        <div class="page-title-box d-flex justify-content-between">
            <h4 class="page-title">Drivers</h4>
            <div class="page-title">
                <button type="button" type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#creatDriver">
                    Add Driver </button>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('driver.index') }}" id="filterselect2" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <label for="organization">Select Oganization</label>
                            <select class="form-control filterselect2" id="organization" name="o_id" data-toggle="select2" data-width="100%">
                                <option value="">Select</option>
                                @forelse ($organizations as $organization)
                                <option value="{{ $organization->id }}" {{ $organization->id == request()->input('o_id') ? 'selected' : '' }}>{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>
                                @empty
                                <option value="">Please select</option>
                                @endforelse
                            </select>
                        </div>
                        <!-- <div class="col-md-3">
                            <label for="filter">Select Vehicle</label>
                            <select class="form-control filterselect2" id="filter">
                                <option value="">Select</option>
                                <option value="">All</option>
                                <option value="driver">Car</option>
                                <option value="vehicle">Bus</option>
                                <option value="route">Hiace</option>
                            </select>
                        </div> -->
                        <div class="col-md-3">
                            <label for="from">From</label>
                            <input class="form-control" type="date" value="{{ request()->input('from', old('from')) }}" name="from">
                        </div>
                        <div class="col-md-3">
                            <label for="to">To</label>
                            <input class="form-control" type="date" value="{{ request()->input('to', old('to')) }}" name="to">
                        </div>
                        <div class="col-md-1">
                            <label for="publish_schedule">.</label>
                            <button type="submit" class="btn btn-success" name="filter" value="filter" id="publish_schedule"> Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="row">
    <form action="{{ route('driver.multiDelete') }}" method="POST" id="driverForm">
        @csrf
        <div class="col-12 table-responsive">
            <div class="card">
                <!-- <div class="card-header">
                <button type="button" type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#creatDriver"> Add </button>
            </div> -->
                <div class="card-header">
                    <div class="d-flex justify-content-around">
                        <div class="col-2">
                            <h4 class="header-title">Drivers</h4>
                        </div>
                        <div class="col-8">
                            <!-- selection show here -->
                        </div>
                        <div class="col-1">
                            <button class="btn btn-danger" id="btnMultilDelete" disabled="disabled">Delete</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- <h4 class="header-title">Latest Driver</h4> -->
                    <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" class="parent_checkbox">
                                </th>
                                <th>Date</th>
                                <th>Otp</th>
                                <th>Organization Name</th>
                                <th>Name</th>
                                <th>Phone No</th>
                                <th>CNIC Number</th>
                                <th>License Number</th>
                                <th>CNIC Front</th>
                                <th>CNIC Back</th>
                                <th>License Front</th>
                                <th>License Back</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($drivers as $driver)
                            <tr>
                                <td>
                                    <input type="checkbox" class="child_checkbox" name="driver_ids[]" value="{{ $driver->id }}">
                                </td>
                                <td> {{ formatDate($driver->created_at) }} </td>
                                <td>{{ $driver->otp }}</td>
                                <td> {{ $driver->organization['name'] }} </td>
                                <td> {{ $driver->id }} {{ $driver->name }} </td>
                                <td> {{ $driver->phone }} </td>
                                <td> {{ $driver->cnic }} </td>
                                <td> {{ $driver->license_no }} </td>
                                <td> <img src="{{ $driver->cnic_front_pic }}" alt="cnic front" style="width: 50px; height: 50px; object-fit: contain;"> </td>
                                <td> <img src="{{ $driver->cnic_back_pic }}" alt="cnic back" style="width: 50px; height: 50px; object-fit: contain;"> </td>
                                <td> <img src="{{ $driver->license_no_front_pic }}" alt="licsence front" style="width: 50px; height: 50px; object-fit: contain;"> </td>
                                <td> <img src="{{ $driver->license_no_back_pic }}" alt="License Back" style="width: 50px; height: 50px; object-fit: contain;"> </td>
                                <td>
                                    <span class="badge  @if($driver->status) bg-success @else bg-danger @endif ">
                                        @if($driver->status) Active @else Deactive @endif</span>
                                </td>
                                <td>
                                    <input type="hidden" class="db_id" value="{{ $driver->id }}">
                                    <input type="hidden" class="db_org_name" value="{{ $driver->organization['id'] }}">
                                    <input type="hidden" class="db_name" value="{{ $driver->name }}">
                                    <input type="hidden" class="db_phone" value="{{ $driver->phone }}">
                                    <input type="hidden" class="db_cnic" value="{{  $driver->cnic }}">
                                    <input type="hidden" class="db_license" value="{{ $driver->license_no }}">
                                    <input type="hidden" class="db_cnic_front_pic" value="{{ $driver->cnic_front_pic }}">
                                    <input type="hidden" class="db_cnic_back_pic" value="{{ $driver->cnic_back_pic }}">
                                    <input type="hidden" class="db_license_no_front_pic" value="{{ $driver->license_no_front_pic }}">
                                    <input type="hidden" class="db_license_no_back_pic" value="{{ $driver->license_no_back_pic }}">
                                    <input type="hidden" class="db_status" value="{{ $driver->status }}">

                                    <div class="btn-group btn-group-sm edit_driver" style="float: none;" data-id="{{ $driver->id }}" onclick="fillEditForm(this)">
                                        <button type="button" type="button" class="tabledit-edit-button btn btn-success" style="float: none;" data-bs-toggle="modal" data-bs-target="#editDriver">
                                            <span class="mdi mdi-pencil"></span>
                                        </button>
                                    </div>
                                    <div class="btn-group btn-group-sm delete_driver" style="float: none;" data-id="{{ $driver->id }}" onclick="deleteDriver(this)">
                                        <button type="button" type="button" class="tabledit-edit-button btn btn-danger" style="float: none;">
                                            <span class="mdi mdi-delete"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div>
    </form>
</div>
<!-- end row-->

<!-- create Modal -->
<div class="modal fade" id="creatDriver" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="creatDriverLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="creatDriverLabel">Add Driver</h5>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-xl-12">
                    <div class="card shadow-none">
                        <div class="card-body">
                            <form action="{{ route('driver.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="example-select" class="form-label">Select Organization </label>
                                            <select class="form-select select2" id="org_name" name="o_id" required>
                                                <option value="">Select</option>
                                                @forelse ($organizations as $organization)
                                                <option value="{{ $organization->id }}">{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>
                                                @empty
                                                <option value="">Please select</option>
                                                @endforelse
                                            </select>
                                            <span class="text-danger" id="org_name_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Name</label>
                                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                                            <span class="text-danger" id="name_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Cnic Number</label>
                                            <input type="text" id="cnic" name="cnic" class="form-control" value="{{ old('cnic') }}" data-toggle="input-mask" data-mask-format="00000-0000000-0" required>
                                            <span class="text-danger" id="cnic_error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone No</label>
                                            <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" data-toggle="input-mask" data-mask-format="0000-0000000" required>
                                            <span class="text-danger" id="phone_error"></span>
                                        </div>
                                        <div class="d-flex">
                                            <div class="col-6">
                                                <div class="mt-1 px-1">
                                                    <input type="file" accept="image/*" data-plugins="dropify" data-default-file="" name="cnic_front" id="cnic_front" data-allowed-file-extensions='png jpg jpeg' required />
                                                    <p class="text-muted text-center mt-2 mb-0">Cnic Front</p>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mt-1 px-1">
                                                    <input type="file" accept="image/*" data-plugins="dropify" data-default-file="" name="cnic_back" id="cnic_back" data-allowed-file-extensions='png jpg jpeg' required />
                                                    <p class="text-muted text-center mt-2 mb-0">Cnic Back</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="license" class="form-label">License Number</label>
                                            <input type="text" id="license" name="license" class="form-control" value="{{ old('license') }}" data-toggle="input-mask" data-mask-format="0000000000-AAA" required>
                                            <span class="text-danger" id="license_error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="example-select" class="form-label">Select Status</label>
                                            <select class="form-select select2" id="status" name="status" required>
                                                <option value="" selected>Select Status</option>
                                                <option value="1">Active</option>
                                                <option value="0">Deactive</option>
                                            </select>
                                            <span class="text-danger" id="status_error"></span>
                                        </div>
                                        <div class="d-flex">
                                            <div class="col-6">
                                                <div class="mt-1 px-1">
                                                    <input type="file" accept="image/*" data-plugins="dropify" data-default-file="" name="license_front" id="license_front" data-allowed-file-extensions='png jpg jpeg' required />
                                                    <p class="text-muted text-center mt-2 mb-0">License Front</p>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mt-1 px-1">
                                                    <input type="file" accept="image/*" data-plugins="dropify" data-default-file="" name="license_back" id="license_back" data-allowed-file-extensions='png jpg jpeg' required />
                                                    <p class="text-muted text-center mt-2 mb-0">License Back</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end #basicwizard-->
                                <div class="text-end mt-2">
                                    <button type="submit" class="btn btn-success waves-effect waves-light" onclick=" validateForm()">Save</button>
                                </div>
                            </form>
                        </div> <!-- end card-body -->
                    </div>
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" type="button" class="btn btn-primary">Submit</button>
            </div> -->
        </div>
    </div>
</div>

<!-- Edit driver modal -->
<div class="modal fade" id="editDriver" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editDriverLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="editDriverLabel">Update Driver</h5>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-xl-12">
                    <div class="card shadow-none">
                        <div class="card-body">
                            <form action="{{ route('driver.edit') }}" id="edit_driver_form" method="post" enctype="multipart/form-data">
                                @csrf
                                {{-- @method('PUT') --}}
                                <input type="hidden" class="form-control" name="id" class="edit_id" id="edit_id">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="example-select" class="form-label">Select Organization </label>
                                            <select class="form-select editselect2" id="edit_org_name" name="o_id" required>
                                                <option value="">Select</option>
                                                @forelse ($organizations as $organization)
                                                <option value="{{ $organization->id }}">{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>
                                                @empty
                                                <option value="">Please select</option>
                                                @endforelse
                                            </select>
                                            <span class="text-danger" id="org_name_error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone No</label>
                                            <input type="text" id="edit_phone" name="phone" class="form-control" value="{{ old('phone') }}" data-toggle="input-mask" data-mask-format="0000-0000000" required>
                                            <span class="text-danger" id="phone_error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Cnic Number</label>
                                            <input type="text" id="edit_cnic" name="cnic" class="form-control" value="{{ old('cnic') }}" data-toggle="input-mask" data-mask-format="00000-0000000-0" required>
                                            <span class="text-danger" id="cnic_error"></span>
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Name</label>
                                            <input type="text" id="edit_name" name="name" class="form-control" value="{{ old('name') }}" required>
                                            <span class="text-danger" id="name_error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="license" class="form-label">License Number</label>
                                            <input type="text" id="edit_license" name="license" class="form-control" value="{{ old('license') }}" data-toggle="input-mask" data-mask-format="0000000000-AAA" required>
                                            <span class="text-danger" id="license_error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Status</label>
                                            <select class="form-select editselect2" id="edit_status" name="status" required>
                                                <option value="" selected>Select</option>
                                                <option value="1">Active</option>
                                                <option value="0">Deactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="d-flex">
                                            <div class="col-6">
                                                <div class="mt-1 px-1">
                                                    <input type="file" accept="image/*" data-plugins="dropify" data-default-file="" name="cnic_front" id="edit_cnic_front" data-allowed-file-extensions='png jpg jpeg' />
                                                    <p class="text-muted text-center mt-2 mb-0">Cnic Front</p>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mt-1 px-1">
                                                    <input type="file" accept="image/*" data-plugins="dropify" data-default-file="" name="cnic_back" id="edit_cnic_back" data-allowed-file-extensions='png jpg jpeg' />
                                                    <p class="text-muted text-center mt-2 mb-0">Cnic Back</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="d-flex">
                                            <div class="col-6">
                                                <div class="mt-1 px-1">
                                                    <input type="file" accept="image/*" data-plugins="dropify" data-default-file="" name="license_front" id="edit_license_front" data-allowed-file-extensions='png jpg jpeg' />
                                                    <p class="text-muted text-center mt-2 mb-0">License Front</p>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mt-1 px-1">
                                                    <input type="file" accept="image/*" data-plugins="dropify" data-default-file="" name="license_back" id="edit_license_back" data-allowed-file-extensions='png jpg jpeg' />
                                                    <p class="text-muted text-center mt-2 mb-0">License Back</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end #basicwizard-->
                                <div class="text-end mt-2">
                                    <button type="submit" class="btn btn-success waves-effect waves-light">Update</button>
                                </div>
                            </form>
                        </div> <!-- end card-body -->
                    </div>
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
@include('partials.datatable_js')
<script>
    $(document).ready(function() {

        initializeSelect2(".select2", '#creatDriver');
        initializeSelect2(".editselect2", '#editDriver');
        initializeSelect2(".filterselect2", '#filterselect2');

        /**
         * this will handle multiple form delete
         */
        $('#driverForm, #edit_driver_form').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting normally
            const form = this; // Store the form element in a variable
            // Display a SweetAlert confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to undo this action.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit it!',
                cancelButtonText: 'No, cancel it'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If the user confirms, submit the form
                    form.submit();
                }
            })
        });

        // $('#edit_driver_form').submit(function(e) {
        //     e.preventDefault();
        //     // Get form
        //     var form = $('#edit_driver_form')[0];
        //     // FormData object 
        //     var data = new FormData(form);
        //     console.log(data);
        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });
        //     Swal.fire({
        //         title: 'Are you sure?',
        //         icon: 'warning',
        //         confirmButtonColor: '#e64942',
        //         showCancelButton: true,
        //         confirmButtonText: 'Yes',
        //         cancelButtonText: `No`,
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             // var formData = new FormData(this);
        //             var formData = new FormData($('#edit_driver_form').get(0));
        //             console.log(formData);
        //             $.ajax({
        //                 type: "PUT",
        //                 url: $(this).attr('action'),
        //                 // data: $(this).serializeArray(),
        //                 data: formData,
        //                 success: function(response) {
        //                     Swal.fire(
        //                         'Updated!',
        //                         'Data Successfully Updated.!',
        //                         'success'
        //                     ).then((result) => {
        //                         location.reload();
        //                     });
        //                 },
        //                 error: (error) => {
        //                     console.log(JSON.stringify(error));
        //                 }
        //             });
        //         }
        //     });
        // });


        // Listen for changes to child and parent checkboxes
        $('.child_checkbox, .parent_checkbox').on('change', function() {
            // Check if any checkboxes are checked
            if ($('.child_checkbox:checked, .parent_checkbox:checked').length > 0) {
                // At least one checkbox is checked
                $('#btnMultilDelete').prop('disabled', false);
            } else {
                // No checkboxes are checked
                $('#btnMultilDelete').prop('disabled', true);
            }
        });


    });


    function fillEditForm(param) {
        let _this = $(param).parents('tr');
        console.log(_this.find('.db_cnic_front_pic').val());
        $('#edit_id').val(_this.find('.db_id').val());
        $('#edit_org_name').val(_this.find('.db_org_name').val()).trigger('change');
        $('#edit_name').val(_this.find('.db_name').val());
        $('#edit_phone').val(_this.find('.db_phone').val());
        $('#edit_cnic').val(_this.find('.db_cnic').val());
        $('#edit_license').val(_this.find('.db_license').val());
        $('#edit_status').val(_this.find('.db_status').val()).trigger('change');

        // alert(_this.find('.db_cnic_front_pic').val());

        resetPreviewDropify(_this.find('.db_cnic_front_pic').val(), "#edit_cnic_front");
        resetPreviewDropify(_this.find('.db_cnic_back_pic').val(), "#edit_cnic_back");
        resetPreviewDropify(_this.find('.db_license_no_front_pic').val(), "#edit_license_front");
        resetPreviewDropify(_this.find('.db_license_no_back_pic').val(), "#edit_license_back");
    }

    function validateForm() {
        let orgNameErr = nameErr = cnicErr = phoneErr = true;
        let orgName = $('#org_name').val();
        let name = $('#name').val();
        let phone = $('#phone').val();
        let cnic = $('#cnic').val();
        let license = $('#license').val();

        if (orgName === "") {
            setErrorMsg('#org_name', "* Required!");
            orgNameErr = false;
        } else {
            setSuccessMsg('#org_name');
        }

        if (name === "") {
            setErrorMsg('#name', "* Required!");
            nameErr = false;
        } else {
            setSuccessMsg('#name');
        }

        if (phone === "") {
            setErrorMsg('#phone', "* Required!");
            phoneErr = false;
        } else if (phone.length < 12) {
            setErrorMsg('#phone', "* Invalid Phone length!");
            phoneErr = false;
        } else {
            setSuccessMsg('#phone');
        }

        if (cnic === "") {
            setErrorMsg('#cnic', "* Required!");
            cnicErr = false;
        } else if (cnic.length < 12) {
            setErrorMsg('#cnic', "* Cnic Number must be of 13 number");
            cnicErr = false;
        } else {
            setSuccessMsg('#cnic');
        }

        if (license === "") {
            setErrorMsg('#license', "* Required!");
            licenseErr = false;
        } else if (license.length < 14) {
            setErrorMsg('#license', "* license Number must be of 13 Character");
            licenseErr = false;
        } else {
            setSuccessMsg('#license');
        }

        //Main validate all the 
        if ((orgNameErr && nameErr && cnicErr && phoneErr) == false) {
            return false;
        } else {
            return true;
        }

    }

    function deleteDriver(param) {
        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            confirmButtonColor: '#e64942',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: `No`,
        }).then((result) => {
            if (result.isConfirmed) {
                let id = $(param).data('id');
                $.ajax({
                    type: "POST",
                    url: "/driver/delete/" + id,
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $(param).parent().find('.child').css('background', 'tomato');
                            $(param).parent().find('.dt-hasChild').css('background', 'tomato');
                            $(param).closest('tr').fadeOut(800, function() {
                                $(this).parent().find('.dt-hasChild').remove();
                                $(this).parent().find('.child').remove();
                            });
                        } else {
                            alert('error Occured while deleting')
                        }
                    },
                    error: (error) => {
                        console.log(JSON.stringify(error));
                    }
                });
            }
        });
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    // function countCheckboxChecked() {
    //     // alert($('.child_checkbox:checked').length)
    //     if ($('.child_checkbox:checked, .parent_checkbox:checked').length > 0) {
    //         $('#btnMultilDelete').prop('disabled', false);
    //     } else {
    //         $('#btnMultilDelete').prop('disabled', true);
    //     }
    // }
</script>
<script src="{{ asset('/libs/select2/js/select2.min.js') }}"></script>
<!-- Plugins js-->
<!-- <script src="/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script> -->
<!-- Plugins js -->
<script src="/libs/dropzone/min/dropzone.min.js"></script>
<script src="/libs/dropify/js/dropify.min.js"></script>

<!-- Init js-->
<script src="/js/pages/form-fileuploads.init.js"></script>

@endsection