@extends('layouts.app')
@section('title', 'Vehicles')
<!-- start page title -->
@section('page_css')
<!-- Plugins css -->
<link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App css -->
<!-- Plugins css -->
<link href="{{ asset('/libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/libs/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ asset('libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
<link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
@include('partials.datatable_css')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between">
            <h4 class="page-title">Vehicles</h4>
            <div class="page-title">
                <button type="button" type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#createModal">
                    Add Vehicle </button>
            </div>
        </div>
    </div>
</div>
<!-- Filters -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('vehicle.index') }}" method="post" id="filterForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <label for="organization">Select Oganization</label>
                            <select class="form-select select1" id="o_id" name="o_id" required>
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
                            <select class="form-control select2_filter" id="filter">
                                <option value="">Select</option>
                                <option value="">All</option>
                                <option value="driver">Car</option>
                                <option value="vehicle">Bus</option>
                                <option value="route">Hiace</option>
                            </select>
                        </div> -->
                        <div class="col-md-3">
                            <label for="from">From</label>
                            <input class="form-control" type="date" name="from" value="{{ request()->input('from', old('from')) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="to">To</label>
                            <input class="form-control" type="date" name="to" value="{{ request()->input('to', old('to')) }}" required>
                        </div>
                        <div class="col-md-1">
                            <label for="publish_schedule">.</label>
                            <button type="submit" class="btn btn-success" name="filter"> Submit
                            </button>
                        </div>
                    </div> <!-- end row -->
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end page title -->
<div class="row">
    <form action="{{ route('vehicle.multiDelete') }}" id="vehicleForm" method="post" enctype="multipart/form-data">
        @csrf
        <div class="col-lg-12 table-responsive">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="header-title">Vehicle List</h4>
                        <button type="submit" id="btnMultilDelete" class="btn btn-danger delete_multiple" disabled="disabled">Delete</button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" class="parent_checkbox">
                                </th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Organization Name</th>
                                <th>Type</th>
                                <th>Number</th>
                                <th>Front picture</th>
                                <th>Number picture</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vehicles as $vehicle)
                            <tr>
                                <td>
                                    <input type="checkbox" class="child_checkbox" name="vehicle_ids[]" value="{{ $vehicle->id }}">
                                </td>
                                <td>{{ formatDate($vehicle->created_at) }}</td>
                                <td>{{ formatTime($vehicle->created_at)  }}</td>
                                <td>{{$vehicle->organization['name'] ?? '' }}</td>
                                <td>{{$vehicle->vehiclesTypes['name'] ?? ''}}</td>
                                <td> {{ $vehicle->id }} {{$vehicle->number}}</td>
                                <td>
                                    <img src="{{ $vehicle->front_pic }}" alt="front_pic" style="width: 50px; height: 50px; object-fit: contain;">
                                </td>
                                <td>
                                    <img src="{{ $vehicle->number_pic }}" alt="number_pic" style="width: 50px; height: 50px; object-fit: contain;">
                                </td>
                                <td>
                                    <input type="hidden" value="{{ $vehicle->id }}" class="db_id" name="id">
                                    <input type="hidden" value="{{ $vehicle->reg_date }}" class="reg_date" name="reg_date">
                                    <input type="hidden" value="{{ $vehicle->o_id }}" class="db_o_id" name="o_id">
                                    <input type="hidden" value="{{ $vehicle->v_type_id }}" class="db_v_type_id" name="v_type_id">
                                    <input type="hidden" value="{{ $vehicle->front_pic }}" class="db_front_pic" name="front_pic">
                                    <input type="hidden" value="{{ $vehicle->back_pic }}" class="db_back_pic" name="back_pic">
                                    <input type="hidden" value="{{ $vehicle->number }}" class="db_number" name="number">
                                    <input type="hidden" value="{{ $vehicle->number_pic }}" class="db_number_pic" name="number_pic">
                                    <input type="hidden" value="{{$vehicle->created_at }}" class="db_created_at" name="created_at">
                                    <div class="btn-group btn-group-sm" style="float: none;" data-id="{{ $vehicle->id  }}">
                                        <button type="button" class="tabledit-edit-button btn btn-success edit_btn" style="float: none;" data-bs-toggle="modal" data-bs-target="#editModal">
                                            <span class="mdi mdi-pencil"></span>
                                        </button>
                                    </div>
                                    <div class="btn-group btn-group-sm" style="float: none;">
                                        <button type="button" class="tabledit-edit-button btn btn-danger delete" style="float: none;">
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
        </div><!-- end col-->
    </form>
</div>
<!-- end row-->
<!-- Modal -->
<div class="modal fade" id="createModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="createModalLabel">Add Vehicle</h5>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('vehicle.store') }}" method="POST" enctype="multipart/form-data" id="createModal_form">
                    @csrf
                    <div class="col-xl-12">
                        <div class="card shadow-none">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="o_id" class="form-label">Select Organization</label>
                                            <select class="form-select select2" id="o_id" name="o_id" required>
                                                <option value="">Select</option>
                                                @forelse ($organizations as $organization)
                                                <option value="{{ $organization->id }}">{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="veh_type" class="form-label">Vehicle Type</label>
                                            <select class="form-select select2" id="veh_type" name="v_type_id" required>
                                                <option value="" selected>Please select</option>
                                                @forelse ($vehicle_types as $vehicle_type)
                                                <option value="{{$vehicle_type->id}}"> {{ucfirst($vehicle_type->name)}}
                                                </option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <div class="mt-1">
                                                <input type="file" accept="image/*" data-allowed-file-extensions="jpg jpeg png" data-default-file="" data-plugins="dropify" name="veh_front_pic" data-default-file="" />
                                                <p class="text-muted text-center mt-2 mb-0">Vehicle Picture from front
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Vehicle's Number</label>
                                            <input type="text" id="simpleinput" name="number" class="form-control" required>
                                        </div>
                                        <div class="col-12">
                                            <div class="mt-1">
                                                <input type="file" accept="image/*" data-allowed-file-extensions="jpg jpeg png" data-plugins="dropify" name="veh_license_plate" data-default-file="" />
                                                <p class="text-muted text-center mt-2 mb-0">Vehicle license plate
                                                    picture</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card-body -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </div> <!-- end card-->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="editModalLabel">Edit Vehicle</h5>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('vehicle.edit') }}" method="POST" enctype="multipart/form-data" id="editModal_form">
                    @csrf
                    <input type="hidden" class="form-control" name="id" id="edit_id">
                    <div class="col-xl-12">
                        <div class="card shadow-none">
                            <div class="card-body">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="edit_organization" class="form-label">Organization Name</label>
                                        <select class="form-select editselect2" id="edit_o_id" name="o_id">
                                            <option value="">Select Organization</option>
                                            @forelse ($organizations as $organization)
                                            <option value="{{ $organization->id }}">{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>

                                            @empty
                                            <option>Organization not Available</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="edit_v_type_id" class="form-label">Vehicle Type</label>
                                            <select class="form-select editselect2" id="edit_v_type_id" name="v_type_id">
                                                <option value="">Please Select Vehicle Type</option>
                                                @forelse ($vehicle_types as $vehicle_type)
                                                <option value="{{$vehicle_type->id}}"> {{ucfirst($vehicle_type->name)}}
                                                </option>
                                                @empty
                                                <option>Vehicle Type not Available</option>
                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <div class="mt-1">
                                                <input type="file" data-plugins="dropify" accept="image/*" data-allowed-file-extensions="jpg jpeg png" name="veh_front_pic" id="edit_front_pic" data-default-file="{{ asset('uploads/vehicles/placeholder.jpg') }}" />
                                                <p class="text-muted text-center mt-2 mb-0">Vehicle Picture from front
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="edit_number" class="form-label">Vehicle's Number</label>
                                            <input type="text" id="edit_number" name="number" class="form-control">
                                        </div>
                                        <div class="col-12">
                                            <div class="mt-1">
                                                <input type="file" data-plugins="dropify" accept="image/*" data-allowed-file-extensions="jpg jpeg png" name="veh_license_plate" id="edit_number_pic" data-default-file="{{ asset('uploads/vehicles/placeholder.jpg') }}" />
                                                <p class="text-muted text-center mt-2 mb-0">Vehicle license plate
                                                    picture</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card-body -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </div> <!-- end card-->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page_js')
<script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
@include('partials.datatable_js')
<!-- Plugins js-->
<script src="/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>



<!-- Init js-->
<script src="/js/pages/form-wizard.init.js"></script>
<!-- Plugins js -->
<script src="{{asset('libs/dropzone/min/dropzone.min.js')}}"></script>
<script src="{{asset('libs/dropify/js/dropify.min.js')}}"></script>

<!-- Init js-->
<script src="{{asset('js/pages/form-fileuploads.init.js')}}"></script>




<script>
    $(document).ready(function() {

        initializeSelect2(".select2", '#createModal');
        initializeSelect2(".editselect2", '#editModal');
        initializeSelect2(".select1", '#filterForm')

        /**
         * this function will popup the edit modal of the driver
         */
        $('.edit_btn').on('click', function() {
            var _this = $(this).parents('tr');
            $('#edit_id').val(_this.find('.db_id').val());
            $('#edit_number').val(_this.find('.db_number').val());
            $('#edit_o_id').val(_this.find('.db_o_id').val()).trigger('change');
            $('#edit_v_type_id').val(_this.find('.db_v_type_id').val()).trigger('change');
            resetPreviewDropify(_this.find('.db_front_pic').val(), "#edit_front_pic");
            resetPreviewDropify(_this.find('.db_number_pic').val(), "#edit_number_pic");
        });

        /**
         * delete single record of vehicle
         */
        $('.delete').click(function(e) {
            e.preventDefault();
            var el = this;
            var id = $(this).closest("tr").find('.db_id').val();
            console.log(id);
            Swal.fire({
                title: 'Are you sure?',
                text: "Once Deleted, you will not be able to recover this record!!",
                icon: 'warning',
                confirmButtonColor: '#e64942',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: `No`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route('vehicle.delete') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                        },
                        success: function(response) {
                            $(el).closest('tr').css('background', 'tomato');
                            $(el).closest('tr').fadeOut(800, function() {
                                $(this).remove();
                            });
                            // Swal.fire(
                            //     'Deleted!',
                            //     'Data Successfully Updated.!',
                            //     'success'
                            // ).then((result) => {

                            // });
                        },
                        error: (error) => {
                            console.log(JSON.stringify(error));
                        }
                    });
                }
            });
        });

        /**
         * this will handle multiple form delete
         */
        $('#vehicleForm').on('submit', function(e) {
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

    /**
     * Undocumented function
     *
     * @return void
     */
    // function countCheckboxChecked() {
    //     // alert($('.child_checkbox:checked').length)
    //     if ($('.child_checkbox:checked').length > 0) {
    //         $('#btnMultilDelete').prop('disabled', false);
    //     } else {
    //         $('#btnMultilDelete').prop('disabled', true);
    //     }
    // }
</script>
@endsection