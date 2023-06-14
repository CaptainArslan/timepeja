<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Log Report</title>
    <link rel="stylesheet" href="{{ asset('css/pdf_landscape.css') }}" media="all" />
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{{ asset('images/logo.png') }}">
        </div>
        <h1>Log Report </h1>
        <div id="company" class="clearfix">
            <div>Stoppick</div>
            <div>Stoppick Association</div>
            <div>+92 300-1234567</div>
            <!-- <div><a href="mailto:company@example.com">company@example.com</a></div> -->
        </div>
        <div id="project">
            <div>
                <h5>{{ $report[0]['organizations']['code'] }} {{ $report[0]['organizations']['name'] }}, {{ $report[0]['organizations']['branch_name'] }}, city<h5></h5>
            </div>
            <div><span>ADDRESS</span>{{ $report[0]['organizations']['address'] }}</div>
            <div><span>EMAIL</span> {{ $report[0]['organizations']['email'] }}</div>
            <div><span>PHONE</span> {{ $report[0]['organizations']['phone'] }}</div>
            <div><span>FROM</span>{{ formatDate(request()->input('from')) }}</div>
            <div><span>TO</span>{{ formatDate(request()->input('to')) }}</div>
        </div>
    </header>
    <main>
        <div>
            <h3>
                @if(request()->input('type') == 'driver')
                Drivers
                @elseif(request()->input('type') == 'vehicle')
                Vehicles
                @elseif(request()->input('type') == 'route')
                Routes
                @endif
            </h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th class="service">DATE</th>
                    <th class="desc">SCHEDULE TIME</th>
                    @if(request()->input('type') == 'driver')
                    <th>DRIVER</th>
                    <th>VEHICLE</th>
                    <th>ROUTE NO</th>
                    @elseif(request()->input('type') == 'vehicle')
                    <th>VEHICLE</th>
                    <th>DRIVER</th>
                    <th>ROUTE NO</th>
                    @elseif(request()->input('type') == 'route')
                    <th>ROUTE NO</th>
                    <th>VEHICLE</th>
                    <th>DRIVER</th>
                    @endif
                    <th>ACTUIAL START/END TIME</th>
                    <th>TRIP STATUS</th>
                    <th>STATUS</th>
                    <th>DELAY REASON</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report as $row)
                <tr>
                    <td class="service">{{ formatDate($row['date']) }}</td>
                    <td class="desc">{{ formatTime($row['time']) }}</td>
                    @if(request()->input('type') == 'driver')
                    <td>{{ $row['drivers']['name'] }}</td>
                    <td>{{ $row['vehicles']['number'] }}</td>
                    <td>{{ $row['routes']['number'] }} {{ $row['routes']['from'] }} To {{ $row['routes']['to'] }}</td>
                    @elseif(request()->input('type') == 'vehicle')
                    <td>{{ $row['vehicles']['number'] }}</td>
                    <td>{{ $row['drivers']['name'] }}</td>
                    <td>{{ $row['routes']['number'] }} {{ $row['routes']['from'] }} To {{ $row['routes']['to'] }}</td>
                    @elseif(request()->input('type') == 'route')
                    <td>{{ $row['routes']['number'] }} {{ $row['routes']['from'] }} To {{ $row['routes']['to'] }}</td>
                    <td>{{ $row['vehicles']['number'] }}</td>
                    <td>{{ $row['drivers']['name'] }}</td>
                    @endif
                    <td>{{ formatTime($row['start_time']) }} / {{ formatTime($row['end_time']) }} </td>
                    <td>{{ $row['trip_status'] }} </td>
                    <td>@if($row['is_delay']) <span style="color:red">Delay </span> @else On-Time @endif </td>
                    <td>{{ ($row['delayed_reason']) ?? 'N/A' }} </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </main>
    <footer>
        <p>&copy; Copyright 2023 - Stoppick. All rights reserved.
            <br /><a href="{{ route('home') }}">www.stoppick.com</a>
        </p>
    </footer>
</body>

</html>