@extends('layouts.app')
@section('title', 'Schedule Creation')
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
            <h4 class="page-title">Schedule Creation</h4>
        </div>
    </div>
</div>
<!-- Start Content  -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- <h4 class="header-title">Select Organization</h4> -->
                <form action="{{ route('schedule.store') }}" method="post" id="create_schedule">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <label for="organization">Select Oganization</label>
                            <select class="form-control" data-toggle="select2" name="organization" data-width="100%" id="organization" required>
                                <option value="">Select</option>
                                @forelse ($organizations as $organization)
                                <option value="{{ $organization->id }}">{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>
                                @empty
                                <option value="">Please select</option>
                                @endforelse
                            </select>
                            <span class="text-danger" id="organization_error"></span>
                        </div>
                        <div class="col-md-3">
                            <label for="route_no">Select Route No</label>
                            <select class="form-control" data-toggle="select2" name="route_no" data-width="100%" id="route_no" required>
                                <option value="" selected>Select</option>
                            </select>
                            <span class="text-danger" id="route_no_error"></span>
                        </div>
                        <div class="col-md-3">
                            <label for="vehicle">Select Vehicle Reg</label>
                            <select class="form-control" data-toggle="select2" name="vehicle" data-width="100%" id="vehicle" required>
                                <option value="" selected>Select</option>
                            </select>
                            <span class="text-danger" id="vehicle_error"></span>
                        </div>
                        <div class="col-md-3">
                            <label for="driver">Select Driver</label>
                            <select class="form-control" data-toggle="select2" name="driver" data-width="100%" id="driver" required>
                                <option value="" selected>Select</option>
                            </select>
                            <span class="text-danger" id="driver_error"></span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <label for="date">Select Date</label>
                            <input type="date" class="form-control today-date" id="date" name="date" onchange="addScheduleToTable()" required>
                            <span class="text-danger" id="date_error"></span>
                        </div>
                        <div class="col-md-3">
                            <label for="time">Time</label>
                            <input class="form-control" id="time" type="time" name="time" required>
                            <span class="text-danger" id="time_error"></span>
                        </div>
                        <div class="col-md-2">
                            <label for="add_schedule"></label>
                            <button type="submit" type="button" class="btn btn-success form-control" id="add_schedule"> Add </button>
                        </div>
                    </div> <!-- end row -->
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<div class="row">
    <div class="col-12">
        <form action="{{ route('schedule.publish', 'published') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-header d-flex" style="justify-content: space-arond;">
                    <div class="col-2 d-flex justify-content-center align-items-center">
                        <h4 class="header-title">Created Schedule</h4>
                    </div>
                    <div class="col-9 ">
                        <!-- <div class="col-md-4">
                            <input type="text" class="form-control font-weight-bold" value="bde1 - Hellen Ernser - Koeppfort">
                        </div> -->
                    </div>
                    <div class="col-1 d-flex flex-row-reverse">
                        <button type="submit" id="btn_published" class="btn btn-danger">Publish</button>
                    </div>
                </div>
                <div class="card-body table-container">
                    <table id="schedule-table" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" class="parent_checkbox">
                                </th>
                                <th>Date</th>
                                <th>Organization Name</th>
                                <th>Driver</th>
                                <th>Route No</th>
                                <th>Vehicle</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </form>
    </div><!-- end col-->
</div>
<!-- Modal -->

<!-- End Content  -->
@endsection

@section('page_js')
<script src="{{ asset('/libs/select2/js/select2.min.js') }}"></script>
<!-- Init js-->
<script src="{{ asset('/js/pages/form-advanced.init.js') }}"></script>

@include('partials.datatable_js')
<script>
    var table = $('#schedule-table').DataTable();
    $(document).ready(function() {
        $('#organization').change(function(e) {
            e.preventDefault();
            let id = $(this).val();
            let csrf_token = "{{ csrf_token() }}";
            $.ajax({
                type: "GET",
                url: "/get-schedule-route-driver-vehicle/" + id,
                data: {
                    '_token': csrf_token
                },
                success: function(response) {
                    if (response.status == 'success') {
                        $('#route_no').empty();
                        $('#vehicle').empty();
                        $('#driver').empty();
                        let routesOption = makeOptions(response.data['routes']);
                        let vehiclesOption = makeOptions(response.data['vehicles']);
                        let driversOption = makeOptions(response.data['drivers']);
                        $('#route_no').append(routesOption);
                        $('#vehicle').append(vehiclesOption);
                        $('#driver').append(driversOption);
                        addScheduleToTable()
                    } else {
                        console.log(response);
                    }
                }
            });
        });

        $('#create_schedule').submit(function(e) {
            e.preventDefault();
            if (checkOrganizationValidate()) {
                var form_data = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "{{ route('schedule.store') }}",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status == 'success') {
                            addRow(table, response.data);
                            $('#route_no').val(null).trigger('change');
                            $('#vehicle').val(null).trigger('change');
                            $('#driver').val(null).trigger('change');
                            $('#time').val('');
                        }
                    }
                });
            } else {
                alert('validation Error')
            }
        });


    });

    function addScheduleToTable() {
        table.clear().draw();
        let org = $('#organization').val();
        let date = $('#date').val();
        let csrf_token = "{{ csrf_token() }}";
        if (org != '' && date != '') {
            $.ajax({
                type: "GET",
                url: "{{ route('getSchedule') }}",
                data: {
                    orgId: org,
                    date: date,
                    "_token": csrf_token
                },
                success: function(response) {
                    if (response.status == 'success') {
                        if (response.data.length > 0) {
                            appendRows(table, response.data)
                        }
                    } else {
                        alert('error Occured while fetching schedule');
                    }
                }
            });
        }
    }

    function appendRows(table, res) {
        table.rows().nodes();
        // $('#schedule-table > tbody').empty();
        addRow(table, res);
    }

    function addRow(tableInstance, res) {
        res.map((item) => {
            tableInstance.row.add([
                `<td> 
                    <input type="checkbox" class="child_checkbox" value="${item.id}" name="schedule_ids[]" >
                    <input type="hidden" value="${item.organizations.id}" name="o_id" >
                </td>`,
                `<td>${item.date}</td>`,
                `<td><b>${item.organizations.name}</b></td>`,
                `<td>${item.drivers.name}</td>`,
                `<td><span class=" text-danger">${item.routes.number}</span> - ${item.routes.from} <span class="text-success"> TO </span> ${item.routes.to}</td>`,
                `<td>${item.vehicles.number}</td>`,
                `<td>${ formatTime(item.time) }</td>`,
                `<td>
                    <div class="btn-group btn-group-sm" style="float: none;">
                        <button type="button" class="tabledit-edit-button btn btn-success edit_btn" style="float: none;" data-bs-toggle="modal" data-bs-target="#edit_modal">
                            <span class="mdi mdi-pencil"></span>
                        </button>
                    </div>
                    <div class="btn-group btn-group-sm delete_schedule" data-id="${item.id}" style="float: none;" onclick="deleteScedule(this, '{{ csrf_token() }}')">
                        <button type="button" class="tabledit-edit-button btn btn-danger delete" style="float: none;">
                            <span class="mdi mdi-delete"></span>
                        </button>
                    </div>
                </td>`
            ]).draw(false);
        });
    }

    function deleteScedule(param, csrf_token) {
        let id = $(param).data('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrf_token
            }
        });
        var csrf_token = "{{ csrf_token() }}";
        $.ajax({
            type: "POST",
            url: "/schedule/delete/" + id,
            data: {
                'token': csrf_token
            },
            success: function(response) {
                if (response.status == 'success') {
                    $(param).closest('tr').css('background', 'tomato');
                    $(param).closest('tr').fadeOut(800, function() {
                        $(this).remove();
                    });
                } else {
                    alert('error Occured while deletion');
                }
            }
        });
    }

    function checkOrganizationValidate() {
        let orgNameErr = routeNoErr = vehicleRegErr = driverErr = dateErr = timeErr = true;
        let orgName = $('#organization').val();
        let routeNo = $('#route_no').val();
        let vehicleReg = $('#vehicle').val();
        let driver = $('#driver').val();
        let date = $('#date').val();
        let time = $('#time').val();
        // Validate Organization Name
        if (orgName === "") {
            setErrorMsg('#organization', "* Required!");
            orgNameErr = false;
        } else {
            setSuccessMsg('#organization');
        }

        // Validate Route Number
        if (routeNo === "") {
            setErrorMsg('#route_no', "* Required!");
            routeNoErr = false;
        } else {
            setSuccessMsg('#route_no');
        }

        // Validate vehicle
        if (vehicleReg === "") {
            setErrorMsg('#vehicle', "* Required!");
            vehicleRegErr = false;
        } else {
            setSuccessMsg('#vehicle');
        }

        // Validate Driver
        if (driver === "") {
            setErrorMsg('#driver', "* Required!");
            driverErr = false;
        } else {
            setSuccessMsg('#driver');
        }

        if (date === "") {
            setErrorMsg('#date', "* Required!");
            dateErr = false;
        } else {
            setSuccessMsg('#date');
        }

        if (time === "") {
            setErrorMsg('#time', "* Required!");
            timeErr = false;
        } else {
            setSuccessMsg('#time');
        }

        if ((orgNameErr && routeNoErr && vehicleRegErr && driverErr && dateErr && timeErr) == false) {
            return false;
        } else {
            return true;
        }
    }

    function getTime() {
        // Get the value of the time input field
        var time_value = document.getElementById('#time').value;

        // Split the time value into hours and minutes
        var parts = time_value.split(':');
        var hours = parseInt(parts[0], 10);
        var minutes = parseInt(parts[1], 10);

        // Determine whether it's AM or PM
        var am_pm = hours >= 12 ? 'PM' : 'AM';

        // Convert the hours to 12-hour format
        if (hours > 12) {
            hours -= 12;
        } else if (hours === 0) {
            hours = 12;
        }

        // Display the result
        alert(hours + ':' + minutes + ' ' + am_pm);
    }
</script>



@endsection