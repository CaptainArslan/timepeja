<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Report</title>
    <style>
        body {
            background-color: #F6F6F6;
            margin: 0;
            padding: 0;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            padding: 0;
        }

        p {
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin-right: auto;
            margin-left: auto;
        }

        .brand-section {
            background-color: #0d1033;
            padding: 10px 40px;
        }

        .logo {
            width: 50%;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .col-6 {
            width: 50%;
            flex: 0 0 auto;
        }

        .col-3 {
            width: 30%;
            flex: 0 0 auto;
        }

        .text-white {
            color: #fff;
        }

        .company-details {
            float: right;
            text-align: right;
        }

        .body-section {
            padding: 16px;
            border: 1px solid gray;
        }

        .heading {
            font-size: 20px;
            margin-bottom: 08px;
        }

        .sub-heading {
            color: #262626;
            margin-bottom: 05px;
        }

        table {
            background-color: #fff;
            width: 100%;
            border-collapse: collapse;
        }

        table thead tr {
            border: 1px solid #111;
            background-color: #f2f2f2;
        }

        table td {
            vertical-align: middle !important;
            text-align: center;
        }

        table th,
        table td {
            padding-top: 08px;
            padding-bottom: 08px;
        }

        /* .table-bordered {
            box-shadow: 0px 0px 5px 0.5px gray;
        } */

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #dee2e6;
        }

        .text-right {
            text-align: end;
        }

        .w-20 {
            width: 20%;
        }

        .float-right {
            float: right;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="brand-section">
            <div class="row" style="justify-content: space-between;">
                <div class="col-3">
                    <h1 class="text-white">Stoppick</h1>
                </div>
                <div class="col-3">
                    <div class="company-details">
                        <p class="text-white">Stoppick Association </p>
                        <p class="text-white">+92 300-1234567</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="body-section">
            <div class="row">
                <div class="col-6">
                    <h2 class="heading"> (System Unique code) {{ $reports[0]->organizations['name'] }}, {{ $reports[0]->organizations['branch_name'] }}, city</h2>
                    <p class="sub-heading">Address: {{ $reports[0]->organizations['address'] }}</p>
                    <!-- here manager information for that organization, email, name, phone -->
                    <p class="sub-heading">Email: {{ $reports[0]->organizations['email'] }} </p>
                    <p class="sub-heading">Phone Number: {{ $reports[0]->organizations['phone'] }}</p>
                </div>
                <div class="col-6">
                    <!-- <h2 class="heading">Organiation Code: {{ $reports[0]->organizations['branch_code'] }}</h2> -->
                    <p class="sub-heading">From: {{ formatDate(request()->input('from')) }} </p>
                    <p class="sub-heading">To: {{ formatDate(request()->input('to')) }}</p>
                    <!-- <p class="sub-heading">Order Date: 20-20-2021 </p> -->
                </div>
            </div>
        </div>

        <div class="body-section">
            <h3 class="heading">
                @if(request()->input('type') == 'driver')
                Drivers
                @elseif(request()->input('type') == 'vehicle')
                Vehicles
                @elseif(request()->input('type') == 'route')
                Routes
                @endif
            </h3>
            <br>
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Scheduled Time</th>
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
                        <th>Status</th>
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
                        <td>@if($report->is_delay)  <span style="color:red">Delay </span>  @else On-Time @endif </td>
                        <td>{{ ($report->delayed_reason) ?? 'N/A' }} </td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
            <!-- <br>
            <h3 class="heading">Payment Status: Paid</h3>
            <h3 class="heading">Payment Mode: Cash on Delivery</h3> -->
        </div>

        <div class="body-section">
            <p>&copy; Copyright 2023 - Stoppick. All rights reserved.
                <a href="{{ route('home') }}" class="float-right">www.stoppick.com</a>
            </p>
        </div>
    </div>

</body>

</html>