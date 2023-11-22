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
            <img src="{{ getPdfLogo() }}">
        </div>
        <h1>{{ ucfirst(request()->status) }}</h1>
        <div id="company" class="clearfix">
            <div>Stoppick</div>
            <div>Stoppick Association</div>
            <div>+92 300-1234567</div>
            <!-- <div><a href="mailto:company@example.com">company@example.com</a></div> -->
        </div>
        <div id="project">
            <div>
                <h5>
                    {{ $organization->code }} {{ $organization->name }}, {{ $organization->branch_name}}, city
                </h5>
            </div>
            <div><span>ADDRESS</span>{{ $organization->address }}</div>
            <div><span>EMAIL</span> {{ $organization->email }}</div>
            <div><span>PHONE</span> {{ $organization->phone }}</div>
        </div>
    </header>
    <main>
        <div>
            <h3>
                {{ ucfirst(request()->status) }}
            </h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Student/Employee Name</th>
                    <th>Roll No/Employee ID</th>
                    <th>Class/Department</th>
                    <th>Town/City</th>
                    <th>Transport Facility Start Date</th>
                    <th>Transport Facility End Date</th>
                    <th>No of Guardian</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $request)
                <tr>
                    <td>{{ $request->name }}</td>
                    <td>{{ $request->getUserId() }}</td>
                    <td>{{ $request->getUserClassOrDepartment() }}</td>
                    <td>{{ $request->town ? $request->town : '' }} {{ optional($request->city)->name }}</td>
                    <td>{{ $request->transport_start_date  }}</td>
                    <td>{{ $request->transport_end_date  }}</td>
                    <td>{{ $request->child_requests_count ?? 0  }}</td>
                </tr>
                @endforeach
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