<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Report</title>
</head>

<body>
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
                    </div>
                    <div class="card-body">
                        <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Route Name</th>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Date</td>
                                    <td>Time</td>
                                    <td>Route Name</td>
                                    <td>Vehicle</td>
                                    <td>Driver</td>
                                    <!-- <td>Action</td> -->
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td>Time</td>
                                    <td>Route Name</td>
                                    <td>Vehicle</td>
                                    <td>Driver</td>
                                    <!-- <th>Action</th> -->
                                </tr>
                                {{-- @forelse($schedules as $schedule)
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
                                @endforelse --}}
                            </tbody>
                        </table>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </form>
        </div><!-- end col-->
    </div>
</body>

</html>