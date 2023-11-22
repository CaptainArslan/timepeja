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
        <h1>
            Drivers
        </h1>
        <div id="company" class="clearfix">
            <div>Stoppick</div>
            <div>Stoppick Association</div>
            <div>+92 300-1234567</div>
        </div>
        @if (request()->input('o_id'))
        <div id="project">
            <div>
                <h5>{{ $drivers[0]['organization']['code'] }} - {{ $drivers[0]['organization']['name'] }}, {{ $drivers[0]['organization']['branch_name'] }}, city<h5></h5>
            </div>
            <div><span>ADDRESS: </span>{!! $drivers[0]['organization']['address'] !!}</div>
            <div><span>EMAIL: </span> {{ $drivers[0]['organization']['email'] }}</div>
            <div><span>PHONE: </span> {{ $drivers[0]['organization']['phone'] }}</div>
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
        @else

        @endif
    </header>
    <main>
        <div>
            <h3>
                Drivers
            </h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th class="service">DATE</th>
                    <th class="desc">ID</th>
                    <th class="desc">ORGANIZATION NAME</th>
                    <th class="desc">NAME</th>
                    <th class="desc">PHONE NUMBER</th>
                    <th class="desc">CNIC NO</th>
                    <th class="desc">LICENSE NO</th>
                    <th class="desc">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drivers as $driver)
                <tr>
                    <td>{{ formatDate($driver['created_at']) }}</td>
                    <td>{{ $driver['id'] }}</td>
                    <td>{{ $driver['organization']['name'] }}</td>
                    <td>{{ $driver['name'] }}</td>
                    <td>{{ $driver['phone'] }}</td>
                    <td>{{ $driver['cnic'] }}</td>
                    <td>{{ $driver['license_no'] }}</td>
                    <td>
                        @if ($driver['status'])
                        Active
                        @else
                        Deactive
                        @endif
                    </td>
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