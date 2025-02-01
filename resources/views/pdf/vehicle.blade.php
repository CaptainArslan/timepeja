<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Vehicle Report</title>
    <link rel="stylesheet" href="{{ asset('css/pdf_landscape.css') }}" media="all" />
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{{ getPdfLogo() }}">
        </div>
        <h1>
            Vehicles
        </h1>
        <div id="company" class="clearfix">
            <div>{{ env('APP_NAME') }}</div>
            <div>Stoppick Association</div>
            <div>+92 300-1234567</div>
        </div>
        <div id="project">
            <div>
                <h5>{{ $vehicles[0]['organization']['full_address'] }}</h5>
            </div>
            <div><span>ADDRESS: </span>{!! $vehicles[0]['organization']['full_address'] !!}</div>
            <div><span>EMAIL: </span> {{ $vehicles[0]['organization']['email'] }}</div>
            <div><span>PHONE: </span> {{ $vehicles[0]['organization']['phone'] }}</div>
            <div><span>FROM: </span>
                @if (request()->input('from') !== '')
                {{ request()->input('from') }}
                @endif
            </div>
            <div><span>TO: </span>
                @if (request()->input('from') !== '')
                {{ request()->input('to') }}
                @endif
            </div>
        </div>
    </header>
    <main>
        <div>
            <h3>
                All Vehicles
            </h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th class="service">DATE</th>
                    <th class="desc">ID</th>
                    <th class="desc">ORGANIZATION NAME</th>
                    <th class="desc">TYPE</th>
                    <th class="desc">NUMBER</th>
                    <th class="desc">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehicles as $vehicle)
                <tr>
                    <td>{{ formatDate($vehicle['created_at']) }}</td>
                    <td>{{ $vehicle['id'] }}</td>
                    <td>{{ $vehicle['organization']['full_name'] }}</td>
                    <td>{{ $vehicle['vehicle_type']['name'] }}</td>
                    <td>{{ $vehicle['number'] }}</td>
                    <td>{{ $vehicle['status'] }}</td>
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