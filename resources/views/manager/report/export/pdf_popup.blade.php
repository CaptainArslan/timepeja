<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>History Report | PDF</title>
</head>
<body>
    <!-- <div class="card"> -->
        <!-- <div class="card-body"> -->
          <div class="container-fluid">
            <div class="row d-flex align-items-baseline">
              <div class="col-12 col-sm-12 col-md-12 col-xl-12 col-xxl-12">
                <!-- <div class="brand-section"> -->
                <div class="row" style="justify-content: space-between;">
                    <div class="col-4">
                        <h1 class="text-black">Stoppick</h1>
                    </div>
                    <div class="col-4"></div>
                    <div class="col-4">
                        <div class="company-details">
                            <p class="text-black">Stoppick Association </p>
                            <p class="text-black">+92 300-1234567</p>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
                <!-- <p style="color: #7e8d9f;font-size: 20px;">Invoice >> <strong>ID: #123-123</strong></p> -->
              </div>
            <!--   <div class="col-xl-3 float-end">
                <a class="btn btn-light text-capitalize border-0" data-mdb-ripple-color="dark"><i
                    class="fas fa-print text-primary"></i> Print</a>
                <a class="btn btn-light text-capitalize" data-mdb-ripple-color="dark"><i
                    class="far fa-file-pdf text-danger"></i> Export</a>
              </div> -->
              <hr>
            </div>
      
            <div class="container-fluid">
              <div class="col-12 col-sm-12 col-md-12 col-xl-12 col-xxl-12">
                <!-- div class="text-center">
                  <i class="fab fa-mdb fa-4x ms-0" style="color:#5d9fc5 ;"></i>
                  <p class="pt-0">MDBootstrap.com</p>
                </div> -->
      
              </div>
      
      
              <div class="row m-0 p-0">
                <div class="col-12 col-sm-12 col-md-12 col-xl-12 col-xxl-12">
                    <div class="h5">(System Unique code) {{ $report[0]['organizations']['name'] }}, {{ $report[0]['organizations']['branch_name'] }}, city </div>
                  <ul class="list-unstyled">
                    <li class="text-muted">Address: <span style="color:#5d9fc5 ;">{{ $report[0]['organizations']['address'] }}</span></li>
                    <li class="text-muted">Email: <span style="color:#5d9fc5 ;">{{ $report[0]['organizations']['email'] }}</span></li>
                    <li class="text-muted">Phone: <span style="color:#5d9fc5 ;">{{ $report[0]['organizations']['phone'] }}</span></li>
                    <li class="text-muted">From: <span style="color:#5d9fc5 ;">{{ formatDate(request()->input('from')) }}</span></li>
                    <li class="text-muted">To: <span style="color:#5d9fc5 ;">{{ formatDate(request()->input('to')) }}</span></li>
                  </ul>
                </div>
              </div>
      
              <div class="row">
                <div class="h3">
                @if(request()->input('type') == 'driver')
                Drivers
                @elseif(request()->input('type') == 'vehicle')
                Vehicles
                @elseif(request()->input('type') == 'route')
                Routes
                @endif
                </div>
              </div>
      <table>
          <thead style="background-color:#84B0CA ;" class="text-white">
            <tr>
              <th>Date</th>
              <th nowrap>Scheduled Time</th>
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
              <th nowrap>Actual trip start/ End time</th>
              <th>Trip Status</th>
              <th>Status</th>
              <th>Delay Reason</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              @forelse($report as $dt)
                <td>{{ formatDate($dt['date']) }}</td>
                <td nowrap>{{ formatTime($dt['time']) }}</td>
                @if(request()->input('type') == 'driver')
                <td>{{ $dt['drivers']['name'] }}</td>
                <td>{{ $dt['vehicles']['number'] }}</td>
                <td>{{ $dt['routes']['number'] }} {{ $dt['routes']['from'] }} To {{ $dt['routes']['to'] }}</td>
                @elseif(request()->input('type') == 'vehicle')
                <td>{{ $dt['vehicles']['number'] }}</td>
                <td>{{ $dt['drivers']['name'] }}</td>
                <td>{{ $dt['routes']['number'] }} {{ $dt['routes']['from'] }} To {{ $dt['routes']['to'] }}</td>
                @elseif(request()->input('type') == 'route')
                <td>{{ $dt['routes']['number'] }} {{ $dt['routes']['from'] }} To {{ $dt['routes']['to'] }}</td>
                <td>{{ $dt['vehicles']['number'] }}</td>
                <td>{{ $dt['drivers']['name'] }}</td>
                @endif
                <td nowrap>{{ formatTime($dt['start_time']) }} / {{ formatTime($dt['end_time']) }} </td>
                <td>{{ $dt['trip_status'] }} </td>
                <td>@if($dt['is_delay'])  <span style="color:red">Delay </span>  @else On-Time @endif </td>
                <td>{{ ($dt['delayed_reason']) ?? 'N/A' }} </td>
              </tr>
            @empty  
            @endforelse
          </tbody>

          </table>
              <hr>
              <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-xl-12 col-xxl-12 text-center">
                    <p>&copy; Copyright 2023 - Stoppick. All rights reserved.
                        <br/><a href="{{ route('home') }}">www.stoppick.com</a>
                    </p>
                 </div>
              </div>
            </div>
          </div>
        <!-- </div> -->
      <!-- </div> -->
</body>
</html>
  