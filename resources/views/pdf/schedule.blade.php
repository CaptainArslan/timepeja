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
        <h1> {{ $title }} Schedule </h1>
        <div id="company" class="clearfix">
            <div>Stoppick</div>
            <div>Stoppick Association</div>
            <div>+92 300-1234567</div>
        </div>
        <div id="project">
            <div>
                <h5>{{ $organization['code'] }} {{ $organization['name'] }}, {{ $organization['branch_name'] }}, city<h5>
            </div>
            <div><span>ADDRESS</span>{{ $organization['address'] }}</div>
            <div><span>EMAIL</span> {{ $organization['email'] }}</div>
            <div><span>PHONE</span> {{ $organization['phone'] }}</div>
            <div><span>Date</span>{{ $date }}</div>
        </div>
    </header>
    <main>
        <div>
            <h3> {{ $title }} Schedule </h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th class="service">DATE</th>
                    <th class="desc">SCHEDULE TIME</th>
                    <th>DRIVER</th>
                    <th>VEHICLE</th>
                    <th>ROUTE NO</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $row)
                <tr>
                    <td class="service">{{ formatDate($row['date']) }}</td>
                    <td class="desc">{{ formatTime($row['time']) }}</td>
                    <td>{{ $row['drivers']['name'] }}</td>
                    <td>{{ $row['vehicles']['number'] }}</td>
                    <td>{{ $row['routes']['name'] }} </td>
                    <td>{{ $title }} </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No Data Found</td>
                </tr>
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