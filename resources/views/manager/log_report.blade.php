@extends('layouts.app')
@section('title', 'LOG Reports')
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
            <h4 class="page-title">All LOG Reports</h4>
        </div>
    </div>
</div>
<!-- Start Content  -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- <h4 class="header-title">Select Organization</h4> -->
                <form action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <label for="organization">Select Oganization</label>
                            <select class="form-control" data-toggle="select2" data-width="100%" id="organization" required>
                                <option value="">Select</option>
                                @forelse ($organizations as $organization)
                                <option value="{{ $organization->id }}">{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>
                                @empty
                                <option value="">Please select</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="selecttype">Select</label>
                            <select class="form-control" data-toggle="select2" name="type" data-width="100%" id="filter" required>
                                <option value="">Select</option>
                                <option value="driver">Driver</option>
                                <option value="vehicle">Vehicle</option>
                                <option value="route">Route</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="selecttype" class="text-capitalize" id="select_label">Select</label>
                            <div class="col-12">
                                <select class="form-control" id="filter_select" multiple="multiple" data-placeholder="Please Select" multiple required>
                                    <option value="" selected>Please Select</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="date-1">From</label>
                            <input class="form-control today-date" id="" type="date" name="date">
                        </div>
                        <div class="col-md-4">
                            <label for="date">To</label>
                            <input class="form-control today-date" id="" type="date" name="date">
                        </div>
                        <div class="col-md-1">
                            <label for="publish_schedule">.</label>
                            <button type="submit" class="btn btn-success" id="publish_schedule" name="submit"> Submit </button>
                        </div>
                    </div> <!-- end row -->
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

@if(isset($_POST['submit']) && isset($_POST['type']) && $_POST['type'] == 'driver')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex">
                <div class="col-2">
                    <h4 class="header-title">Driver</h4>
                </div>
                <div class="col-7">
                    <div class="row">
                        <div class="col-md-6">
                            <input class="form-control" id="" type="text" value="123456 - branch - Punjab University" name="organization" style="font-weight: bold;" readonly>
                        </div>
                        <!-- <div class="col-md-3">
                            <input class="form-control" id="example-date-1" type="date" name="date">
                        </div>
                        <div class="col-md-3">
                            <input class="form-control" id="example-date" type="date" name="date">
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <td>Date</td>
                            <td>Scheduled Time</td>
                            <th>Driver</th>
                            <th>Vehicle</th>
                            <th>Route No</th>
                            <th>Actual trip start/ End time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>21/21/2023</td>
                            <td>06:00:00</td>
                            <td>Ali</td>
                            <td>LHR-123</td>
                            <td>10 Lahore to Multan</td>
                            <td> 02:00:00 / 20:15:00 </td>
                        </tr>
                        <tr>
                            <td>21/21/2023</td>
                            <td>06:00:00</td>
                            <td>Ali</td>
                            <td>LHR-123</td>
                            <td>15 Lahore to Gujranwala</td>
                            <td> 02:15:00 / 22:00:00 </td>
                        </tr>
                    </tbody>
                </table>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
@elseif(isset($_POST['submit']) && isset($_POST['type']) && $_POST['type'] == 'vehicle')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex">
                <div class="col-2">
                    <h4 class="header-title">Vehicles</h4>
                </div>
                <div class="col-7">
                    <div class="row">
                        <div class="col-md-6">
                            <input class="form-control" id="" type="text" value="123456 - branch - Punjab University" name="organization" style="font-weight: bold;" readonly>
                        </div>
                        <!-- <div class="col-md-3">
                            <input class="form-control" id="example-date-1" type="date" name="date">
                        </div>
                        <div class="col-md-3">
                            <input class="form-control" id="example-date" type="date" name="date">
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Schedule Time</th>
                            <th>Vehicle</th>
                            <th>Driver</th>
                            <th>Route No</th>
                            <th>Actual trip start/ End time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>20/20/2023</td>
                            <td>06:00:00</td>
                            <td>MLT-987</td>
                            <td>ALi</td>
                            <td>10 Lahore to Multan</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>20/20/2023</td>
                            <td>06:15:00</td>
                            <td>GAO-987</td>
                            <td>Afzaal</td>
                            <td>17 Lahore to Gujranwala</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>20/20/2023</td>
                            <td>09:00:00</td>
                            <td>GAJ-123</td>
                            <td>Numan</td>
                            <td>10 Lahore to Faisalabad</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
@elseif(isset($_POST['submit']) && isset($_POST['type']) && $_POST['type'] == 'route')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex">
                <div class="col-2">
                    <h4 class="header-title">Routes</h4>
                </div>
                <div class="col-7">
                    <div class="row">
                        <div class="col-md-6">
                            <input class="form-control" id="" type="text" value="123456 - branch - Punjab University" name="organization" style="font-weight: bold;" readonly>
                        </div>
                        <!-- <div class="col-md-3">
                            <input class="form-control" id="example-date-1" type="date" name="date">
                        </div>
                        <div class="col-md-3">
                            <input class="form-control" id="example-date" type="date" name="date">
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Schedule Time</th>
                            <th>Date</th>
                            <th>Route</th>
                            <th>Vehicle</th>
                            <th>Driver</th>
                            <th>Actual trip start/ End time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>09:00:00</td>
                            <td>20/20/2023</td>
                            <td>10 Lahore to Gujranwala</td>
                            <td>Gao 4268</td>
                            <td>Rehman</td>
                            <td>09:10:00 / 11:00:00</td>
                        </tr>
                    </tbody>
                </table>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
@endif

<!-- Modal -->

<!-- End Content  -->
@endsection

@section('page_js')
<script src="{{ asset('/libs/select2/js/select2.min.js') }}"></script>


@include('partials.datatable_js')
<script>
    $(document).ready(function() {

        $('#filter').change(function(e) {
            e.preventDefault();
            var value = $(this).val();
            var option = '';


            $('#filter_select').empty();
            if (value == 'driver') {
                $('#filter_select_label').html(value);
                var option = driver_option();
            } else if (value == 'route') {
                $('#filter_select_label').html(value);
                var option = route_option();
            } else if (value == 'vehicle') {
                $('#filter_select_label').html(value);
                var option = vehicle_option();
            } else {
                var option = `<option value="">Select</option>`;
            }

            $('#filter_select').append(option);
        });

    });

    function get_option() {
        let value = $('#filter').val();
        let option = `<option value="">Select</option>`;
        if (value == 'driver') {
            $('#select_label').html(value);
            option = driver_option();
        } else if (value == 'route') {
            $('#select_label').html(value);
            option = route_option();
        } else if (value == 'vehicle') {
            $('#select_label').html(value);
            option = vehicle_option();
        }

        console.log(option);
        $('#filter_select').append(option).trigger('change');
    }

    get_option()

    function driver_option() {
        return html = `<option value="">All</option>
                        <option value="">Azam </option>
                        <option value="">Afzal </option>
                        <option value="">Ali</option>`;
    }

    function vehicle_option() {
        return html = `<option value="">All</option>
                    <option value="">GAO-123 </option>
                    <option value="">LHR-123</option>
                    <option value="">KCH_123</option>`;
    }

    function route_option() {
        return html = `<option value="">All</option>
                        <option value="">LHR 10 GRW </option>
                        <option value="">GRM 15 MLT</option>
                        <option value="">LHR 20 KCH</option>`;
    }
</script>

<!-- Init js-->
<script src="{{ asset('/js/pages/form-advanced.init.js') }}"></script>
@endsection