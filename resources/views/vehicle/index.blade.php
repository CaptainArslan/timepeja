@extends('layouts.app')
@section('title', 'Add Vehicle')
<!-- start page title -->
@section('page_css')
<!-- Plugins css -->
<link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App css -->
<!-- Plugins css -->

<link href="/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />
<link href="/libs/dropify/css/dropify.min.css" rel="stylesheet" type="text/css" />

<link href="{{ asset('libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
<link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
@include('partials.datatable_css')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between">
            <h4 class="page-title">All Vehicles</h4>
            <div class="page-title">
                <button type="button" type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#create_modal">
                    Add Vehicle </button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="" id="filter_form">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="organization">Select Oganization</label>
                            <select class="form-control select2_filter" id="organization">
                                <option value="">Select</option>
                                <option value="AK">123456 - branch - Punjab University</option>
                                <option value="HI">123456 - branch - Gujrant University</option>
                                <option value="CA">123456 - branch - Gift University</option>
                                <option value="NV">123456 - branch - Kips University</option>
                                <option value="OR">123456 - branch - Sialkot Univeristy</option>
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-3">
                            <label for="filter">Select Vehicle</label>
                            <select class="form-control select2_filter" id="filter">
                                <option value="">Select</option>
                                <option value="">All</option>
                                <option value="driver">Car</option>
                                <option value="vehicle">Bus</option>
                                <option value="route">Hiace</option>
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-2">
                            <label for="from">From</label>
                            <input class="form-control" id="example-date-1" type="date" name="from">
                        </div> <!-- end col -->
                        <div class="col-md-2">
                            <label for="to">To</label>
                            <input class="form-control" id="example-date" type="date" name="to">
                        </div> <!-- end col -->
                        <div class="col-md-1">
                            <label for="publish_schedule">.</label>
                            <button type="button" type="button" class="btn btn-success" id="publish_schedule"> Submit
                            </button>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end page title -->
<div class="row">
    <div class="col-lg-12 table-responsive">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h4 class="header-title">Vehicle List</h4>
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
                            <th>
                                <input type="checkbox">
                                <input type="hidden" value="{{$vehicle->id}}" class="db_id" name="id">
                                <input type="hidden" value="{{$vehicle->reg_date}}" class="reg_date" name="reg_date">
                                <input type="hidden" value="{{$vehicle->o_id}}" class="db_o_id" name="o_id">
                                <input type="hidden" value="{{$vehicle->v_type_id}}" class="db_v_type_id" name="v_type_id">
                                <input type="hidden" value="{{$vehicle->front_pic}}" class="db_front_pic" name="front_pic">
                                <input type="hidden" value="{{$vehicle->back_pic}}" class="db_back_pic" name="back_pic">
                                <input type="hidden" value="{{$vehicle->number}}" class="db_number" name="number">
                                <input type="hidden" value="{{$vehicle->number_pic}}" class="db_number_pic" name="number_pic">
                                <input type="hidden" value="{{$vehicle->created_at}}" class="db_created_at" name="created_at">
                            </th>
                            <th>{{ date("Y-m-d",strtotime($vehicle->created_at)) }}</th>
                            <th>{{ date("H:m:s",strtotime($vehicle->created_at)) }}</th>
                            <td>{{$vehicle->organizations['name']}}</td>
                            <td>{{$vehicle->vehiclesTypes['name']}}</td>
                            <td>{{$vehicle->number}}</td>
                            <td><img src="{{$vehicle->front_pic}}" alt="" width="50px" height="50px"></td>
                            <td><img src="{{$vehicle->number_pic}}" alt="" width="50px" height="50px"></td>
                            <td>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" class="tabledit-edit-button btn btn-success edit_btn" style="float: none;" data-bs-toggle="modal" data-bs-target="#edit_modal"><span class="mdi mdi-pencil"></span></button></div>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" class="tabledit-edit-button btn btn-danger delete" style="float: none;"><span class="mdi mdi-delete"></span></button></div>
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
<div class="modal fade" id="create_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="create_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="create_modalLabel">Add Vehicle</h5>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('vehicle.create') }}" method="POST" enctype="multipart/form-data" id="create_modal_form">
                    @csrf
                    <div class="col-xl-12">
                        <div class="card shadow-none">
                            <div class="card-body">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="organization" class="form-label">Organization Name</label>
                                        <select class="form-select select2" id="organization" name="o_id">
                                            @forelse ($organizations as $organization)
                                            <option value="{{$organization->id}}"> {{ucfirst($organization->name)}}
                                            </option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="veh_type" class="form-label">Vehicle Type</label>
                                            <select class="form-select select2" id="veh_type" name="v_type_id">
                                                @forelse ($vehicle_types as $vehicle_type)
                                                <option value="{{$vehicle_type->id}}"> {{ucfirst($vehicle_type->name)}}
                                                </option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <div class="mt-1">
                                                <input type="file" data-plugins="dropify" name="veh_front_pic" data-default-file="/images/small/img-2.jpg" />
                                                <p class="text-muted text-center mt-2 mb-0">Vehicle Picture from front
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Vehicle's Number</label>
                                            <input type="text" id="simpleinput" name="number" class="form-control">
                                        </div>
                                        <div class="col-12">
                                            <div class="mt-1">
                                                <input type="file" data-plugins="dropify" name="veh_back_pic" data-default-file="/images/small/img-2.jpg" />
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
<div class="modal fade" id="edit_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="edit_modalLabel">Edit Vehicle</h5>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('vehicle.edit') }}" method="POST" enctype="multipart/form-data" id="edit_modal_form">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">
                    <div class="col-xl-12">
                        <div class="card shadow-none">
                            <div class="card-body">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="edit_organization" class="form-label">Organization Name</label>
                                        <select class="form-select select3" id="edit_o_id" name="o_id">
                                            <option value="">Select Organization</option>
                                            @forelse ($organizations as $organization)
                                            <option value="{{$organization->id}}"> {{ucfirst($organization->name)}}
                                            </option>
                                            @empty
                                            <option>Organization not Available</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="edit_v_type_id" class="form-label">Vehicle Type</label>
                                            <select class="form-select select3" id="edit_v_type_id" name="v_type_id">
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
                                                <input type="file" data-plugins="dropify" name="veh_front_pic" data-default-file="/images/small/img-2.jpg" />
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
                                                <input type="file" data-plugins="dropify" name="edit_number_pic" data-default-file="/images/small/img-2.jpg" />
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

<!-- Sweet Alerts js -->
<script src="{{asset('libs/sweetalert2/sweetalert2.all.min.js')}}"></script>

<!-- Sweet alert init js-->
{{-- <script src="{{asset('js/pages/sweet-alerts.init.js')}}"></script> --}}
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
    });

    $('.edit_btn').on('click', function() {
        var _this = $(this).parents('tr');
        $('#edit_id').val(_this.find('.db_id').val());
        $('#edit_number').val(_this.find('.db_number').val());
        $('#edit_o_id').val(_this.find('.db_o_id').val()).trigger('change'); // Select the option with a value of ''1
        $('#edit_v_type_id').val(_this.find('.db_v_type_id').val()).trigger('change'); // Select the option with a value of '1'
        $('#edit_front_pic').attr('src', _this.find('.db_front_pic').val());
        $('#edit_number_pic').attr('src', _this.find('.db_number_pic').val());

    });
    
    $(document).ready(function() {
        $('#create_modal_form').submit(function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire(
                        'Done!',
                        'Inserted Successfully!',
                        'success'
                    ).then((result) => {
                        location.reload();
                    });
                },
                error: (error) => {
                    console.log(JSON.stringify(error));
                }
            });
        });
        
        $('#edit_modal_form').submit(function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                confirmButtonColor: '#e64942',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: `No`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        success: function(response) {
                            Swal.fire(
                                'Updated!',
                                'Data Successfully Updated.!',
                                'success'
                            ).then((result) => {
                                location.reload();
                            });
                        },
                        error: (error) => {
                            console.log(JSON.stringify(error));
                        }
                    });
                }
            });
        });

        $('.delete').click(function(e) {
            e.preventDefault();
            var el = this;
            var id = $(this).closest("tr").find('.db_id').val();
            console.log(id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
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
                            "id": id,
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'Data Successfully Updated.!',
                                'success'
                            ).then((result) => {
                                $(el).closest('tr').css(
                                    'background', 'tomato');
                                $(el).closest('tr').fadeOut(800,
                                    function() {
                                        $(this).remove();
                                    });
                            });
                        },
                        error: (error) => {
                            console.log(JSON.stringify(error));
                        }
                    });
                }
            });
        });
    });
</script>
@endsection