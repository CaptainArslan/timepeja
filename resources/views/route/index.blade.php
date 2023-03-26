@extends('layouts.app')
@section('title', 'Routes')
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
            <h4 class="page-title">Routes</h4>
            <div class="page-title">
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addRouteModal"> Add Routes </button>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
<!-- Filters -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- <h4 class="header-title">Select Organization</h4> -->
                <form action="{{ route('routes.index') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <label for="organization">Select Oganization</label>
                            <select class="form-control" data-toggle="select2" name="o_id" data-width="100%" id="organization">
                                <option value="" selected>Select</option>
                                @forelse ($organizations as $organization)
                                <option value="{{ $organization->id }}" {{ in_array($organization->id, old('dropdown', [])) ? 'selected' : '' }}>
                                    {{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}
                                </option>
                                @empty
                                <option value="">Please select</option>
                                @endforelse
                            </select>
                        </div>
                        <!-- <div class="col-md-3">
                            <label for="selecttype">Select</label>
                            <select class="form-control" data-toggle="select2" data-width="100%" id="filter">
                                <option value="">Select</option>
                                <option value="">All</option>
                                <option value="Gujranwala">Gujranwala</option>
                                <option value="Lahore">Lahore</option>
                                <option value="Peshawar">Peshawar</option>
                            </select>
                        </div> -->
                        <div class="col-md-3">
                            <label for="date-1">From</label>
                            <input class="form-control" type="date" name="from">
                        </div>
                        <div class="col-md-3">
                            <label for="date">To</label>
                            <input class="form-control" type="date" name="to">
                        </div>
                        <div class="col-md-1">
                            <label for="route_list"></label>
                            <button type="submit" class="btn btn-success" name="filter" id="route_list"> Submit </button>
                        </div>
                    </div> <!-- end row -->
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<!-- Table -->
<div class="row">
    <div class="col-lg-12 table-responsive">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h4 class="header-title">Route List</h4>
                    <!-- <button class="btn btn-danger">Delete</button> -->
                </div>
            </div>
            <div class="card-body">
                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Organization Name</th>
                            <th>Route Name</th>
                            <th>Route No</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($routes as $route)
                        <tr>
                            <td>{{ formatDate($route->created_at) }}</td>
                            <td>{{ formatTime($route->created_at) }}</td>
                            <td>{{ ($route->organizations['name']) ?? '' }} </td>
                            <td>{{ $route->name }}</td>
                            <td>{{ $route->number  }}</td>
                            <td>{{ $route->to }}</td>
                            <td>{{ $route->from }}</td>
                            <td>
                                <input type="hidden" class="form-control db_edit_id" name="" value="{{ $route->id }}">
                                <input type="hidden" class="form-control db_org_id" name="" value="{{ ($route->organizations['id']) ?? '' }}">
                                <input type="hidden" class="form-control db_route_name" name="" value="{{ $route->name }}">
                                <input type="hidden" class="form-control db_route_number" name="" value="{{ $route->number  }}">
                                <input type="hidden" class="form-control db_route_from" name="" value="{{ $route->from }}">
                                <input type="hidden" class="form-control db_route_from_lat" name="" value="{{ $route->from_latitude }}">
                                <input type="hidden" class="form-control db_route_from_long" name="" value="{{ $route->from_longitude }}">
                                <input type="hidden" class="form-control db_route_to" name="" value="{{ $route->to }}">
                                <input type="hidden" class="form-control db_route_to_lat" name="" value="{{ $route->to_latitude }}">
                                <input type="hidden" class="form-control db_route_to_long" name="" value="{{ $route->to_longitude }}">

                                <div class="btn-group btn-group-sm" style="float: none;">
                                    <button type="button" class="tabledit-edit-button btn btn-success edit_route" style="float: none;" data-bs-toggle="modal" data-bs-target="#editRouteModal">
                                        <span class="mdi mdi-pencil"></span>
                                    </button>
                                </div>
                                <div class="btn-group btn-group-sm delete_route" data-id="{{ $route->id }}" style="float: none;">
                                    <button type="button" class="tabledit-edit-button btn btn-danger" style="float: none;">
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
</div>
<!-- end Table-->

<!-- Modal -->
<div class="modal fade" id="addRouteModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="addRouteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Add Route</h4>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('routes.store') }}" method="POST" id="addRouteForm">
                    @csrf
                    <div class="mb-3">
                        <label for="org_name" class="form-label">Organization Name</label>
                        <select class="form-select select2_form" id="org_name" name="org_id" required>
                            <option value="">Select</option>
                            @forelse ($organizations as $organization)
                            <option value="{{ $organization->id }}">{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>
                            @empty
                            <option value="">Please select</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="route_no" class="form-label">Route No</label>
                        <input type="text" name="route_no" data-toggle="input-mask" data-mask-format="00000000000" class="form-control" onchange="setRouteName()" required>
                    </div>
                    <div class="mb-3">
                        <label for="from" class="form-label">From</label>
                        <input type="text" id="from" name="from" class="form-control" onchange="setRouteName()" required>
                    </div>
                    <div class="mb-3">
                        <label for="from" class="form-label">From Map</label>
                        <div id="gmaps-basic" class="gmaps" style="max-height: 100px;"></div>
                    </div>
                    <div class="mb-3">
                        <label for="to" class="form-label">To</label>
                        <input type="text" id="to" name="to" class="form-control" onchange="setRouteName()" required>
                    </div>
                    <div class="mb-3">
                        <label for="from" class="form-label">Select from Map</label>
                        <div id="gmaps-basic" class="gmaps" style="max-height: 100px;"></div>
                    </div>

                    <div class="mb-3">
                        <label for="route_name" class="form-label">Route Name</label>
                        <input type="text" id="route_name" name="route_name" class="form-control" readonly>
                    </div>
                    <div class="text-end">
                        <button type="submit" type="submit" class="btn btn-success waves-effect waves-light">Save</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Edit modal -->
<div class="modal fade" id="editRouteModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="editRouteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="editRouteModalLabel">Edit Route</h4>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('routes.edit') }}" method="POST" id="editModalform">
                    @csrf
                    <input type="hidden" id="edit_id" name="edit_id">
                    <div class="mb-3">
                        <label for="org_name" class="form-label">Organization Name</label>
                        <select class="form-select select2" id="edit_org_id" name="org_id" required>
                            <option value="">Select</option>
                            @forelse ($organizations as $organization)
                            <option value="{{ $organization->id }}">{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>
                            @empty
                            <option value="">Please select</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="route_no" class="form-label">Route No</label>
                        <input type="text" id="edit_route_no" data-toggle="input-mask" data-mask-format="00000000000" name="route_no" class="form-control" onchange="setEditRouteName()" required>
                    </div>
                    <div class="mb-3">
                        <label for="from" class="form-label">From</label>
                        <input type="text" id="edit_from" name="from" class="form-control" onchange="setEditRouteName()" required>
                    </div>
                    <div class="mb-3">
                        <label for="from" class="form-label">From Map</label>
                        <div id="gmaps-basic" class="gmaps" style="max-height: 100px;"></div>
                    </div>
                    <div class="mb-3">
                        <label for="to" class="form-label">To</label>
                        <input type="text" id="edit_to" name="to" class="form-control" onchange="setEditRouteName()" required>
                    </div>
                    <div class="mb-3">
                        <label for="from" class="form-label">Select from Map</label>
                        <div id="gmaps-basic" class="gmaps" style="max-height: 100px;"></div>
                    </div>

                    <div class="mb-3">
                        <label for="route_name" class="form-label">Route Name</label>
                        <input type="text" id="edit_route_name" name="route_name" class="form-control" readonly>
                    </div>
                    <div class="text-end">
                        <button type="submit" type="submit" class="btn btn-success waves-effect waves-light">Update</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection

@section('page_js')
<script src="{{ asset('/libs/select2/js/select2.min.js') }}"></script>
<!-- Init js-->
<script src="{{ asset('/js/pages/form-advanced.init.js') }}"></script>

@include('partials.datatable_js')

<script>
    $(document).ready(function() {

        // this function is call when add route modal open
        $('#addRouteModal').on('shown.bs.modal', function() {
            initializeSelect2(".select2_form", "#addRouteForm")
        });

         // this function is call when edit route modal open
        $('#editRouteModal').on('shown.bs.modal', function() {
            initializeSelect2(".select2", "#editModalform")
        });

        $('.edit_route').click(function(e) {
            e.preventDefault();
            $('#edit_id').val($(this).closest('tr').find('.db_edit_id').val());
            $('#edit_org_id').val($(this).closest('tr').find('.db_org_id').val()).trigger('change');
            $('#edit_route_no').val($(this).closest('tr').find('.db_route_number').val());
            $('#edit_from').val($(this).closest('tr').find('.db_route_from').val());
            $('#edit_to').val($(this).closest('tr').find('.db_route_to').val());
            $('#edit_route_name').val($(this).closest('tr').find('.db_route_name').val());
        });

        $('.delete_route').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var el = this;
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
                        url: "{{ route('routes.delete') }}",
                        data: {
                            "id": id,
                            "_token": "{{csrf_token()}}"
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                $(el).closest('tr').css('background', 'tomato');
                                $(el).closest('tr').fadeOut(800, function() {
                                    $(this).remove();
                                });
                                // Swal.fire(
                                //     'Deleted!',
                                //     'Data Successfully Deleted.!',
                                //     'success'
                                // ).then((result) => {
                                //     location.reload();
                                // });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'Error Occured while Deletion.!',
                                    'warning'
                                )
                            }
                        },
                        error: (error) => {
                            console.log(JSON.stringify(error));
                        }
                    });
                }
            });
        });
    });

    function setRouteName() {
        var routeno = $('#route_no').val();
        var to = $('#to').val();
        var from = $('#from').val();
        $('#route_name').val(routeno + '  ' + from + '  TO  ' + to);
    }

    function setEditRouteName() {
        var routeno = $('#edit_route_no').val();
        var to = $('#edit_to').val();
        var from = $('#edit_from').val();
        $('#edit_route_name').val(routeno + '  ' + from + '  TO  ' + to);
    }

    function showToast(message) {
        var toast = $('<div class="toast"></div>');
        toast.text(message);
        $('body').append(toast);
        setTimeout(function() {
            toast.fadeOut(500, function() {
                toast.remove();
            });
        }, 3000);
    }
    // <b> <span class=" text-danger">15</span> - Gujranwala <span class="text-success"> TO </span> Lahore </b>
</script>



@endsection