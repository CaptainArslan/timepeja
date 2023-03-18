@extends('layouts.app')
@section('title', 'Published Schedule')
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
            <h4 class="page-title">Published Schedule</h4>
        </div>
    </div>
</div>
<!-- Start Content  -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- <h4 class="header-title">Select Organization</h4> -->
                <form action="{{ route('schedule.published') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <label for="organization">Select Oganization</label>
                            <select class="form-control" data-toggle="select2" name="o_id" data-width="100%" id="organization" required>
                                <option value="" selected>Select</option>
                                @forelse ($org_dropdowns as $organization)
                                @if (request()->input('o_id'))
                                <option value="{{ $organization->id }}" selected>{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>
                                @else
                                <option value="{{ $organization->id }}">{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>
                                @endif
                                @empty
                                <option value="">Please select</option>
                                @endforelse
                            </select>
                            <span></span>
                        </div>
                        <div class="col-md-3">
                            <label for="from">From</label>
                            <input class="form-control" name="from" id="from" type="date" value="{{ request()->input('from', old('from')) }}">
                        </div>
                        <div class="col-md-3">
                            <label for="to">To</label>
                            <input class="form-control" name="to" id="to" type="date" value="{{ request()->input('to', old('to')) }}">
                        </div>
                        <div class="col-md-1">
                            <label for="submit"></label>
                            <button type="submit" class="btn btn-success" name="filter" id="submit"> Submit </button>
                        </div>
                    </div> <!-- end row -->
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>


@if(isset($_POST['filter']))
<!--  Schedule Table -->
<div class="row">
    <div class="col-12">
        <form action="{{ route('schedule.published') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header d-flex">
                    <div class="col-1">
                        <h4 class="header-title">Schedule</h4>
                    </div>
                    <div class="col-8">
                        <div class="row">
                            <div class="col-md-6">
                                <input class="form-control" id="" type="hidden" value="{{ request()->input('o_id') }}" name="o_id" style="font-weight: bold;" readonly>
                            </div>
                            <div class="col-md-3">
                                <input class="form-control" id="selected_from" type="hidden" name="from" value="{{ request()->input('from') }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <input class="form-control" id="selected_to" type="hidden" name="to" value="{{ request()->input('to') }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 d-flex justify-content-around mx-1">
                        <button type="submit" class="btn btn-success" id="btn_modify" name="modify" value="modify" disabled>Modify</button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop" id="btn_replicate" disabled>Replicate</button>
                        <button type="submit" class="btn btn-primary" name="print" value="print">Export</button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" class="parent_checkbox" onchange="countCheckboxChecked()">
                                </th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Route Name</th>
                                <th>Vehicle</th>
                                <th>Driver</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $schedule)
                            <tr>
                                <td>
                                    <input type="checkbox" class="child_checkbox" value="{{ $schedule->id }}" name="" onchange="countCheckboxChecked()">
                                    <input type="hidden" name="schedule_ids[]" value="{{ $schedule->id }}">
                                </td>
                                <td>{{ $schedule->date }}</td>
                                <td>{{ formatTime($schedule->time, 'h:i:s A') }}</td>
                                <td> <span class=" text-danger">{{ $schedule->routes['number'] }}</span> -{{ $schedule->routes['from'] }} <span class="text-success"> To </span> {{ $schedule->routes['to'] }} </td>
                                <td>{{ $schedule->vehicles['number'] }}</td>
                                <td>{{ $schedule->drivers['name'] }}</td>
                                <!-- <td>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" type="button" class="tabledit-edit-button btn btn-success" style="float: none;"><span class="mdi mdi-pencil"></span></button></div>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" type="button" class="tabledit-edit-button btn btn-danger" style="float: none;"><span class="mdi mdi-delete"></span></button></div>
                            </td> -->
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </form>
    </div><!-- end col-->
</div>
@endif

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Replicate Schedule</h4>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body p-4">
                <form action="#" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="role_name" class="form-label">Select Date, when you want to replicate </label>
                        <input type="date" id="role_name" class="form-control" required>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success waves-effect waves-light">Replicate</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- End Content  -->
@endsection

@section('page_js')
<script src="{{ asset('/libs/select2/js/select2.min.js') }}"></script>
<!-- Init js-->
<script src="{{ asset('/js/pages/form-advanced.init.js') }}"></script>

@include('partials.datatable_js')
<script>
    $(document).ready(function() {});

    function countCheckboxChecked() {
        if ($('.child_checkbox:checked, .parent_checkbox:checked').length > 0) {
            $('#btn_modify').prop('disabled', false);
            $('#btn_replicate').prop('disabled', false);
        } else {
            $('#btn_modify').prop('disabled', true);
            $('#btn_replicate').prop('disabled', true);
        }
    }
</script>

@endsection