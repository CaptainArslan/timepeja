<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Route Report</title>
    <link rel="stylesheet" href="{{ asset('css/pdf_landscape.css') }}" media="all" />
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{{ getPdfLogo() }}">
        </div>
        <h1>
            Routes
        </h1>
        <div id="company" class="clearfix">
            <div>Stoppick</div>
            <div>Stoppick Association</div>
            <div>+92 300-1234567</div>
        </div>
        <div id="project">
            <div>
                <h5>{{ $routes[0]['organization']['code'] }} - {{ $routes[0]['organization']['name'] }}, {{ $routes[0]['organization']['branch_name'] }}, city<h5></h5>
            </div>
            <div><span>ADDRESS: </span>{!! $routes[0]['organization']['address'] !!}</div>
            <div><span>EMAIL: </span> {{ $routes[0]['organization']['email'] }}</div>
            <div><span>PHONE: </span> {{ $routes[0]['organization']['phone'] }}</div>
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
                routes
            </h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th class="service">DATE</th>
                    <th class="desc">ID</th>
                    <th class="desc">ORGANIZATION NAME</th>
                    <th class="desc">NAME</th>
                    <th class="desc">NUMBER</th>
                    <th class="desc">From</th>
                    <th class="desc">To</th>
                    <th class="desc">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($routes as $route)
                <tr>
                    <td>{{ formatDate($route['created_at']) }}</td>
                    <td>{{ $route['id'] }}</td>
                    <td>{{ $route['organization']['name'] }}</td>
                    <td>{{ $route['name'] }}</td>
                    <td>{{ $route['number'] }}</td>
                    <td>{{ $route['from'] }}</td>
                    <td>{{ $route['to'] }}</td>
                    <td>
                        @if ($route['status'])
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