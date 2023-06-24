@extends('layouts.app')
@section('title', 'History Reports Driver-Vehicle-Route ')
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
            <h4 class="page-title">History Reports</h4>
        </div>
    </div>
</div>

<!-- Start Filters  -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- <h4 class="header-title">Select Organization</h4> -->
                <form action="{{ route('log.reports') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <label for="organization">Select Oganization</label>
                            <select class="form-control" data-toggle="select2" data-width="100%" name="o_id" id="o_id" required onchange="getOrgOption()">
                                <option value="">Select</option>
                                @forelse ($org_dropdowns as $organization)
                                <option value="{{ $organization->id }}" {{ $organization->id == request()->input('o_id') ? 'selected' : '' }}>{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>
                                @empty
                                <option value="">Please select</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="selecttype">Select</label>
                            <select class="form-control" data-toggle="select2" name="type" data-width="100%" id="type" onchange="getOrgOption()" required>
                                <option value="" @if(!request()->input('type')) selected @endif>Select</option>
                                <option value="driver" @if(request()->input('type') == 'driver') selected @endif>Driver</option>
                                <option value="vehicle" @if(request()->input('type') == 'vehicle') selected @endif>Vehicle</option>
                                <option value="route" @if(request()->input('type') == 'route') selected @endif>Route</option>
                            </select>

                        </div>
                        <div class="col-md-3">
                            <label for="date-1">From</label>
                            <input class="form-control today-date" type="date" name="from" value="{{ request()->input('from', old('from')) }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date">To</label>
                            <input class="form-control today-date" type="date" name="to" value="{{ request()->input('to', old('to')) }}">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label for="selecttype" class="text-capitalize" id="select_label">Select</label>
                            <select class="form-control" id="selection" multiple="multiple" name="selection[]" data-placeholder="Please Select" multiple required>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label for="publish_schedule"></label>
                            <button type="submit" class="btn btn-success" id="publish_schedule" name="filter" value="filter"> Submit </button>
                        </div>
                    </div> <!-- end row -->
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
@if(isset($_POST['filter']))
<div class="row">
    <div class="col-12">
        <form action="{{ route('log.reports') }}" method="POST" id="" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header d-flex">
                    <div class="col-1">
                        <h4 class="header-title">
                            @if(request()->input('type') == 'driver')
                            Drivers
                            @elseif(request()->input('type') == 'vehicle')
                            Vehicles
                            @elseif(request()->input('type') == 'route')
                            Routes
                            @endif
                        </h4>
                    </div>
                    <div class="col-10">
                        <div class="row">
                            <div class="col-md-2">
                                <input class="form-control" type="hidden" name="o_id" style="font-weight: bold;" value="{{ request()->input('o_id') }}" readonly>
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" type="hidden" name="type" value="{{ request()->input('type') }}" readonly>
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" type="hidden" name="from" value="{{ request()->input('from') }}" readonly>
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" type="hidden" name="to" value="{{ request()->input('to')}}" readonly>
                            </div>
                            <div class="col-md-3">
                                <input class="form-control" type="hidden" name="selection" value="{{ request()->input('selection') ? implode(',', request()->input('selection')) : '' }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <!-- change the button type from submit to button -->
                        <button type="submit" name="export" id="export" value="export" class="btn btn-primary">Export</button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <td>Date</td>
                                <td>Scheduled Time</td>
                                @if(request()->input('type') == 'driver')
                                <th>Driver</th>
                                <th>Vehicle</th>
                                <th>Route No</th>
                                @elseif(request()->input('type') == 'vehicle')
                                <th>Vehicle</th>
                                <th>Driver</th>
                                <th>Route No</th>
                                @elseif(request()->input('type') == 'route')
                                <th>Route No</th>
                                <th>Vehicle</th>
                                <th>Driver</th>
                                @endif
                                <th>Actual trip start/ End time</th>
                                <th>Trip Status</th>
                                <th>Delay</th>
                                <th>Delay Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                            <tr>
                                <td>{{ formatDate($report->date) }}</td>
                                <td>{{ formatTime($report->time) }}</td>
                                @if(request()->input('type') == 'driver')
                                <td>{{ $report->drivers['name'] }}</td>
                                <td>{{ $report->vehicles['number'] }}</td>
                                <td>{{ $report->routes['number'] }} {{ $report->routes['from'] }} To {{ $report->routes['to'] }}</td>
                                @elseif(request()->input('type') == 'vehicle')
                                <td>{{ $report->vehicles['number'] }}</td>
                                <td>{{ $report->drivers['name'] }}</td>
                                <td>{{ $report->routes['number'] }} {{ $report->routes['from'] }} To {{ $report->routes['to'] }}</td>
                                @elseif(request()->input('type') == 'route')
                                <td>{{ $report->routes['number'] }} {{ $report->routes['from'] }} To {{ $report->routes['to'] }}</td>
                                <td>{{ $report->vehicles['number'] }}</td>
                                <td>{{ $report->drivers['name'] }}</td>
                                @endif
                                <td>{{ formatTime($report->start_time) }} / {{ formatTime($report->end_time) }} </td>
                                <td>{{ $report->trip_status }} </td>
                                <td>@if($report->is_delay) Delay @else N/A @endif </td>
                                <td>{{ $report->delayed_reason }}</td>
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
@endsection

@section('page_js')

<script>
    /**
     * Undocumented function
     *
     * @return void
     */
    $(document).ready(function() {
        $('#export').click(function() {
            window.open("{{route('pdf.popup')}}", 'Popup', 'width=1000,height=700');
        });
});
    function getOrgOption() {
        // alert('option');
        // $('#selection').empty();
        let type = $('#type').val();
        let o_id = $('#o_id').val();
        if (type != '' && o_id != '') {
            let csrf_token = "{{ csrf_token() }}";
            $.ajax({
                type: "get",
                url: "/get-driver-vehicle-route",
                data: {
                    '_token': csrf_token,
                    'o_id': o_id,
                    'type': type
                },
                success: function(response) {
                    if (response.status = 'success') {
                        $('#selection').empty();
                        let option = '<option value="">Select </option>';
                        option = getOption(response.data, type)
                        $('#selection').append(option).trigger('change');
                    }
                }
            });
        } else {
            // console.log('error Occured while fetching');
        }
    }

    getOrgOption();

    /**@abstract */
    function getOption(res, type) {
        let html = '<option value="all">All</option>';
        res.map((item) => {
            html += `<option value="${item.id}"> ${item.name}</option>`;
        });
        // console.log(html);
        return html
    }
</script>

@include('partials.datatable_js')
<script src="{{ asset('/libs/select2/js/select2.min.js') }}"></script>
<!-- Init js-->
<script src="{{ asset('/js/pages/form-advanced.init.js') }}"></script>
@endsection