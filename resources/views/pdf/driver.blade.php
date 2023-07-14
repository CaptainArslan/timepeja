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
        <h1>Drivers</h1>
        <div id="company" class="clearfix">
            <div>Stoppick</div>
            <div>Stoppick Association</div>
            <div>+92 300-1234567</div>
            <!-- <div><a href="mailto:company@example.com">company@example.com</a></div> -->
        </div>
        <div id="project">

            {{-- <div>
                <h5>{{ $drivers[0]['organization']['code'] }} {{ $drivers[0]['organization']['name'] }}, {{ $drivers[0]['organization']['branch_name'] }}, city<h5></h5>
            </div>
            <div><span>ADDRESS</span>{{ $drivers[0]['organization']['address'] }}</div>
            <div><span>EMAIL</span> {{ $drivers[0]['organization']['email'] }}</div>
            <div><span>PHONE</span> {{ $drivers[0]['organization']['phone'] }}</div> --}}
            <!-- <div><span>FROM</span>{{ formatDate(request()->input('from')) }}</div>
            <div><span>TO</span>{{ formatDate(request()->input('to')) }}</div> -->
        </div>
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
                    <th class="desc">NAME</th>
                    <th class="desc">PHONE NUMBER</th>
                    <th class="desc">CNIC NO</th>
                    <th class="desc">LICENSE NO</th>
                    <th class="desc">STATUS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
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