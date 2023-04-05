@extends('layouts.app')
@section('title', 'Upcoming Trips')
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
            <h4 class="page-title">Upcoming Trips</h4>
        </div>
    </div>
</div>
<!-- Start Content  -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- <h4 class="header-title">Select Organization</h4> -->
                <form action="{{ route('driver.upcomingTrips') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <label for="organization">Select Oganization</label>
                            <select class="form-control" data-toggle="select2" data-width="100%" id="organization" name="o_id" required>
                                <option value="">Select</option>
                                @forelse ($organizations as $organization)
                                <option value="{{ $organization->id }}" {{ $organization->id == request()->input('o_id') ? 'selected' : '' }}>{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>
                                @empty
                                <option value="">Please select</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="driver">Select Driver</label>
                            <select class="form-control" data-toggle="select2" name="driver" data-width="100%" id="driver">
                                <option value="">Select</option>
                            </select>
                        </div>
                        <!-- <div class="col-md-2">
                            <label for="status">Status</label>
                            <select class="form-control" data-toggle="select2" data-width="100%" id="status">
                                <option value="">Online</option>
                                <option value="">Offline</option>
                            </select>
                        </div> -->
                        <div class="col-md-3">
                            <label for="from">From</label>
                            <input class="form-control today-date" name="from" id="from" type="date" name="date" value="{{ request()->input('from', old('from')) }}">
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-3">
                            <label for="to">To</label>
                            <input class="form-control today-date" name="to" id="to" type="date" name="date" value="{{ request()->input('to', old('to')) }}">
                        </div>
                        <div class="col-md-1">
                            <label for="publish_schedule">.</label>
                            <button type="submit" class="btn btn-success" id="publish_schedule" name="filter"> Submit </button>
                        </div>
                    </div> <!-- end row -->
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>


@if(isset($_POST['filter']))
<div class="row">
    <div class="col-12 table-responsive">
        <div class="card">
            <!-- <div class="card-header">
                <button type="button" type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#creatDriver"> Add </button>
            </div> -->
            <div class="card-header">
                <div class="d-flex ">
                    <div class="col-2">
                        <h4 class="header-title">Upcoming Trips</h4>
                    </div>
                    <!-- <div class="col-8">
                        selection show here
                    </div>
                    <div class="col-1">
                        <button class="btn btn-danger">Delete</button>
                    </div> -->
                </div>
            </div>
            <div class="card-body">
                <!-- <h4 class="header-title">Latest Driver</h4> -->
                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                        <tr>
                            <th>
                                <input type="checkbox">
                            </th>
                            <th>Date</th>
                            <!-- <th>Organization Name</th> -->
                            <th>Time</th>
                            <th>Route</th>
                            <th>Vehicle</th>
                            <th>Trip Status</th>
                            <th>Delay Reason</th>
                            <th>Action</th>
                        </tr>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($trips as $trip)
                        <tr>
                            <td>
                                <input type="checkbox" name="schedule_ids[]" id="" value="{{ $trip->id }}">
                            </td>
                            <td> {{ formatDate($trip->date) }} </td>
                            <!-- <td><b><a href="#" data-bs-toggle="modal" data-bs-target="#modal_organization">Punjab University</a></b></td> -->
                            <td> {{ formatTime($trip->time) }} </td>
                            <td> <span class=" text-danger">{{ $trip->routes['number'] }}</span> - {{ $trip->routes['from'] }}<span class="text-success"> TO </span> {{ $trip->routes['to'] }} </td>
                            <td> {{ $trip->vehicles['number'] }} </td>
                            <td><span class="badge bg-warning"> {{$trip->trip_status}} </span></td>
                            <td>Nill</td>
                            <td>
                                <div class="btn-group btn-group-sm" style="float: none;">
                                    <button type="button" type="button" class="tabledit-edit-button btn btn-success" style="float: none;"><span>Start Trip</span></button>
                                </div>
                                <!-- <div class="btn-group btn-group-sm" style="float: none;">
                                    <button type="button" type="button" class="tabledit-edit-button btn btn-dark" style="float: none;" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                        <span>Delay Trip</span>
                                    </button>
                                </div> -->
                                <div class="btn-group btn-group-sm" style="float: none;">
                                    <button type="button" type="button" class="tabledit-edit-button btn btn-danger" style="float: none;">
                                        <span>End Trip</span>
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
</div>
@endif

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Delay Upcoming Trip</h4>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body p-4">
                <form>
                    <div class="mb-3">
                        <label for="role_name" class="form-label">Till which date you want to Delay?</label>
                        <input type="date" id="role_name" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="textarea" class="form-label">Reason to Delay</label>
                        <textarea class="form-control" id="textarea" rows="5" style="height: 156px;"></textarea>
                    </div>
                    <div class="text-end">
                        <button type="button" type="submit" class="btn btn-success waves-effect waves-light">Submit</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
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

        $('#organization').val("{{ old('o_id') }}").trigger('change');

        $("#Id option:selected").text();

        $('#organization').change(function(e) {
            e.preventDefault();
            checkOrgValue()
        });
    });

    function checkOrgValue() {
        var id = $('#organization').val();
        if (id != '') {
            var csrf_token = "{{ csrf_token() }}";
            $.ajax({
                type: "GET",
                url: "/driver/get-org-driver/" + id,
                data: {
                    "_token": csrf_token
                },
                success: function(response) {
                    if (response.status == 'success') {
                        appedDrivers(response.data);
                    } else {
                        alert('error Occured while driver fetching')
                    }
                }
            });
        }
    }

    checkOrgValue();

    // alert("{{request()->input('driver')}}");
    // $('#driver').val("{{request()->input('driver')}}").trigger('change');

    function appedDrivers(res) {
        $('#driver').empty();
        let html = `<option value="" selected>Please Select</option>`;
        res.map((item) => {
            html += `<option value="${item.id}">${item.name}</option>`;
        });
        $('#driver').append(html);
    }
</script>

@endsection