@extends('layouts.app')
@section('title', 'Dashboard')
<!-- start page title -->
@section('page_css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <style>
        .gmaps {
            height: 500px;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title d-flex">Dashboard
                    <p id="connection-status"> </p>
                    <p id="location-latlong"> </p>
                </h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-md-6 col-xl-4">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                <i class="fe-map font-22 avatar-title text-info"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup"
                                        id="trip-count">{{ $tripCount }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">Total Trips Completed</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-4">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                <i class="fe-truck font-22 avatar-title text-primary"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $vehicleCount }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">Total Active Vehicles</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-4">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                <i class="fe-users font-22 avatar-title text-info"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $passengerCount }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">Total Passengers</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <!-- <div class="col-md-6 col-xl-3">
                                                                                                                                                                            <div class="widget-rounded-circle card">
                                                                                                                                                                                <div class="card-body">
                                                                                                                                                                                    <div class="row">
                                                                                                                                                                                        <div class="col-6">
                                                                                                                                                                                            <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                                                                                                                                                                                <i class="fe-eye font-22 avatar-title text-primary"></i>
                                                                                                                                                                                            </div>
                                                                                                                                                                                        </div>
                                                                                                                                                                                        <div class="col-6">
                                                                                                                                                                                            <div class="text-end">
                                                                                                                                                                                                <h3 class="text-dark mt-1"><span data-plugin="counterup">78.41</span>k</h3>
                                                                                                                                                                                                <p class="text-muted mb-1 text-truncate">Today's Visits</p>
                                                                                                                                                                                            </div>
                                                                                                                                                                                        </div>
                                                                                                                                                                                    </div> end row
                                                                                                                                                                                </div>
                                                                                                                                                                            </div> end widget-rounded-circle
                                                                                                                                                                        </div> -->
        <!-- end col-->
    </div>
    <!-- end row-->

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Action</a>
                        </div>
                    </div>

                    <h4 class="header-title mb-0">Total Revenue</h4>

                    <div class="widget-chart text-center" dir="ltr">

                        <div id="total-revenue" class="mt-0" data-colors="#4fc6e1"></div>

                        <h5 class="text-muted mt-0">Total sales made today</h5>
                        <h2>$178</h2>

                        <p class="text-muted w-75 mx-auto sp-line-2">Traditional heading elements are designed to work best
                            in the meat of your page content.</p>

                        <div class="row mt-4">
                            <div class="col-4">
                                <p class="text-muted font-13 mb-1 text-truncate">Target</p>
                                <h4><i class="fe-arrow-down text-danger me-1"></i>$7.8k</h4>
                            </div>
                            <div class="col-4">
                                <p class="text-muted font-13 mb-1 text-truncate">Last week</p>
                                <h4><i class="fe-arrow-up text-success me-1"></i>$1.4k</h4>
                            </div>
                            <div class="col-4">
                                <p class="text-muted font-13 mb-1 text-truncate">Last Month</p>
                                <h4><i class="fe-arrow-down text-danger me-1"></i>$15k</h4>
                            </div>
                        </div>

                    </div>
                </div>
            </div> <!-- end card -->
        </div> <!-- end col-->

        <div class="col-xl-8">
            <!-- Portlet card -->
            <div class="card">
                <div class="card-body">
                    <div class="card-widgets">
                        <a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                        <a data-bs-toggle="collapse" href="#cardCollpase4" role="button" aria-expanded="false"
                            aria-controls="cardCollpase4"><i class="mdi mdi-minus"></i></a>
                        <a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                    </div>
                    <h4 class="header-title mb-0">Invoices</h4>

                    <div id="cardCollpase4" class="collapse show" dir="ltr">
                        <div id="apex-area" class="apex-charts pt-3" data-colors="#7e57c2,#f7b84b,#CED4DC"></div>
                    </div> <!-- collapsed end -->
                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div>
    <!-- end row -->

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">Map</h4>
                <div class="mb-3">
                    <label class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search on map" aria-label="Username"
                            aria-describedby="basic-addon1">
                        <span class="input-group-text" id="basic-addon1" role="button"><i
                                class="fas fa-search"></i></span>
                    </div>
                </div>
                <div id="map" class="gmaps"></div>
            </div>
        </div> <!-- end card-->
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Edit Report</a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Action</a>
                        </div>
                    </div>

                    <h4 class="header-title mb-3">Vehicle List</h4>

                    <div class="table-responsive">
                        <table class="table table-borderless table-hover table-nowrap table-centered m-0">

                            <thead class="table-light">
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Vehicle Type</th>
                                    <th>Route </th>
                                    <th>Driver</th>
                                    <th>Organization</th>
                                    <th>City</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width: 36px;">
                                        <img src="{{ asset('images/users/user-2.jpg') }}" alt="contact-img"
                                            title="contact-img" class="rounded-circle avatar-sm" />
                                    </td>

                                    <td>
                                        <h5 class="m-0 fw-normal font-13">Tomaslau</h5>
                                        <p class="mb-0 text-muted"><small>Member Since 2017</small></p>
                                    </td>

                                    <td>
                                        <i class="mdi mdi-currency-btc text-primary"></i> BTC
                                    </td>

                                    <td>
                                        0.00816117 BTC
                                    </td>

                                    <td>
                                        0.00097036 BTC
                                    </td>

                                    <td>
                                        <a href="javascript: void(0);" class="btn btn-xs btn-light"><i
                                                class="mdi mdi-plus"></i></a>
                                        <a href="javascript: void(0);" class="btn btn-xs btn-danger"><i
                                                class="mdi mdi-minus"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 36px;">
                                        <img src="{{ asset('images/users/user-3.jpg') }}" alt="contact-img"
                                            title="contact-img" class="rounded-circle avatar-sm" />
                                    </td>

                                    <td>
                                        <h5 class="m-0 fw-normal font-13">Erwin E. Brown</h5>
                                        <p class="mb-0 text-muted"><small>Member Since 2017</small></p>
                                    </td>

                                    <td>
                                        <i class="mdi mdi-currency-eth text-primary"></i> ETH
                                    </td>

                                    <td>
                                        3.16117008 ETH
                                    </td>

                                    <td>
                                        1.70360009 ETH
                                    </td>

                                    <td>
                                        <a href="javascript: void(0);" class="btn btn-xs btn-light"><i
                                                class="mdi mdi-plus"></i></a>
                                        <a href="javascript: void(0);" class="btn btn-xs btn-danger"><i
                                                class="mdi mdi-minus"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 36px;">
                                        <img src="{{ asset('images/users/user-4.jpg') }}" alt="contact-img"
                                            title="contact-img" class="rounded-circle avatar-sm" />
                                    </td>

                                    <td>
                                        <h5 class="m-0 fw-normal font-13">Margeret V. Ligon</h5>
                                        <p class="mb-0 text-muted"><small>Member Since 2017</small></p>
                                    </td>

                                    <td>
                                        <i class="mdi mdi-currency-eur text-primary"></i> EUR
                                    </td>

                                    <td>
                                        25.08 EUR
                                    </td>

                                    <td>
                                        12.58 EUR
                                    </td>

                                    <td>
                                        <a href="javascript: void(0);" class="btn btn-xs btn-light"><i
                                                class="mdi mdi-plus"></i></a>
                                        <a href="javascript: void(0);" class="btn btn-xs btn-danger"><i
                                                class="mdi mdi-minus"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 36px;">
                                        <img src="{{ asset('images/users/user-5.jpg') }}" alt="contact-img"
                                            title="contact-img" class="rounded-circle avatar-sm" />
                                    </td>

                                    <td>
                                        <h5 class="m-0 fw-normal font-13">Jose D. Delacruz</h5>
                                        <p class="mb-0 text-muted"><small>Member Since 2017</small></p>
                                    </td>

                                    <td>
                                        <i class="mdi mdi-currency-cny text-primary"></i> CNY
                                    </td>

                                    <td>
                                        82.00 CNY
                                    </td>

                                    <td>
                                        30.83 CNY
                                    </td>

                                    <td>
                                        <a href="javascript: void(0);" class="btn btn-xs btn-light"><i
                                                class="mdi mdi-plus"></i></a>
                                        <a href="javascript: void(0);" class="btn btn-xs btn-danger"><i
                                                class="mdi mdi-minus"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 36px;">
                                        <img src="{{ asset('images/users/user-6.jpg') }}" alt="contact-img"
                                            title="contact-img" class="rounded-circle avatar-sm" />
                                    </td>

                                    <td>
                                        <h5 class="m-0 fw-normal font-13">Luke J. Sain</h5>
                                        <p class="mb-0 text-muted"><small>Member Since 2017</small></p>
                                    </td>

                                    <td>
                                        <i class="mdi mdi-currency-btc text-primary"></i> BTC
                                    </td>

                                    <td>
                                        2.00816117 BTC
                                    </td>

                                    <td>
                                        1.00097036 BTC
                                    </td>

                                    <td>
                                        <a href="javascript: void(0);" class="btn btn-xs btn-light"><i
                                                class="mdi mdi-plus"></i></a>
                                        <a href="javascript: void(0);" class="btn btn-xs btn-danger"><i
                                                class="mdi mdi-minus"></i></a>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Edit Report</a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Action</a>
                        </div>
                    </div>

                    <h4 class="header-title mb-3">Revenue History</h4>

                    <div class="table-responsive">
                        <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                            <thead class="table-light">
                                <tr>
                                    <th>Marketplaces</th>
                                    <th>Date</th>
                                    <th>Payouts</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <h5 class="m-0 fw-normal font-13">Themes Market</h5>
                                    </td>

                                    <td>
                                        Oct 15, 2018
                                    </td>

                                    <td>
                                        $5848.68
                                    </td>

                                    <td>
                                        <span class="badge bg-soft-warning text-warning">Upcoming</span>
                                    </td>

                                    <td>
                                        <a href="javascript: void(0);" class="btn btn-xs btn-light"><i
                                                class="mdi mdi-pencil"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <h5 class="m-0 fw-normal font-13">Freelance</h5>
                                    </td>

                                    <td>
                                        Oct 12, 2018
                                    </td>

                                    <td>
                                        $1247.25
                                    </td>

                                    <td>
                                        <span class="badge bg-soft-success text-success">Paid</span>
                                    </td>

                                    <td>
                                        <a href="javascript: void(0);" class="btn btn-xs btn-light"><i
                                                class="mdi mdi-pencil"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <h5 class="m-0 fw-normal font-13">Share Holding</h5>
                                    </td>

                                    <td>
                                        Oct 10, 2018
                                    </td>

                                    <td>
                                        $815.89
                                    </td>

                                    <td>
                                        <span class="badge bg-soft-success text-success">Paid</span>
                                    </td>

                                    <td>
                                        <a href="javascript: void(0);" class="btn btn-xs btn-light"><i
                                                class="mdi mdi-pencil"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <h5 class="m-0 fw-normal font-13">Envato's Affiliates</h5>
                                    </td>

                                    <td>
                                        Oct 03, 2018
                                    </td>

                                    <td>
                                        $248.75
                                    </td>

                                    <td>
                                        <span class="badge bg-soft-danger text-danger">Overdue</span>
                                    </td>

                                    <td>
                                        <a href="javascript: void(0);" class="btn btn-xs btn-light"><i
                                                class="mdi mdi-pencil"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <h5 class="m-0 fw-normal font-13">Marketing Revenue</h5>
                                    </td>

                                    <td>
                                        Sep 21, 2018
                                    </td>

                                    <td>
                                        $978.21
                                    </td>

                                    <td>
                                        <span class="badge bg-soft-warning text-warning">Upcoming</span>
                                    </td>

                                    <td>
                                        <a href="javascript: void(0);" class="btn btn-xs btn-light"><i
                                                class="mdi mdi-pencil"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <h5 class="m-0 fw-normal font-13">Advertise Revenue</h5>
                                    </td>

                                    <td>
                                        Sep 15, 2018
                                    </td>

                                    <td>
                                        $358.10
                                    </td>

                                    <td>
                                        <span class="badge bg-soft-success text-success">Paid</span>
                                    </td>

                                    <td>
                                        <a href="javascript: void(0);" class="btn btn-xs btn-light"><i
                                                class="mdi mdi-pencil"></i></a>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div> <!-- end .table-responsive-->
                </div>
            </div> <!-- end card-->
        </div> <!-- end col -->

    </div>
    <!-- end row -->
@endsection

@section('page_js')
    <script src="{{ asset('js\socketclient.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        $schedule = @json($schedule);
        // console.log($schedule);
        socket.on("connect", () => {
            console.log("Connected to server");
        });

        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(
                (position) => {
                    document.getElementById("location-latlong").innerText = ` Lat: ${position.coords.latitude}, Long: ${position.coords.longitude}`;
                    const {
                        latitude,
                        longitude
                    } = position.coords;
                    socket.emit("location", {
                        socket_id: socket.id,
                        latitude,
                        longitude,
                        ...$schedule
                    });
                },
                (error) => {
                    console.log("Error getting location data: " + error.message);
                }, {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0,
                }
            );
        } else {
            alert("Geolocation is not supported by this browser.");
        }

        const map = L.map('map').setView([0, 0], 16); // Set initial view

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 20,
            attribution: '&copy; <a href="http://appaloinc.com/">Appalo inc</a>'
        }).addTo(map);

        let markers = {};

        socket.on("location", (data) => {
            console.log(data);
            // Optionally, center the map on the new location
            // If a marker for this socket ID already exists, update its position
            // console.log(markers);
            if (markers[data.socketid]) {
                // console.log("update marker");
                markers[data.socketid].setLatLng([data.latitude, data.longitude]);
            } else {
                // console.log("create new marker");
                // If it doesn't exist, create a new marker
                map.setView([data.latitude, data.longitude]);
                markers[data.socketid] = L.marker([data.latitude, data.longitude])
                    .bindPopup(
                        `<b>Testing Data:</b><br>Lat: ${data.latitude}<br>Lon: ${data.longitude}`
                    ).addTo(map);
                L.circle([data.latitude, data.longitude], {
                    radius: 200,
                    // color: 'red',
                    // fillColor: '#f03',
                    // fillOpacity: 0.5
                }).addTo(map);
            }
        });

        socket.on("user-disconnected", (id) => {
            console.log("Disconnected from server", markers[id]);
            if (markers[id]) {
                map.removeLayer(markers[id]);
                delete markers[id];
            }
        });

        // Example initial marker
        // L.marker([32.1955345, 74.2019822]).addTo(map);
    </script>

    <!-- google maps api -->
    {{-- <script src="https://maps.google.com/maps/api/js?key=AIzaSyDsucrEdmswqYrw0f6ej3bf4M4suDeRgNA"></script>

    <!-- gmap js-->
    <script src="{{ asset('libs/gmaps/gmaps.min.js') }}"></script>

    <!-- Init js-->
    <script src="{{ asset('js/pages/google-maps.init.js') }}"></script> --}}

    <!-- Third Party js-->
    {{-- <script src="{{ asset('libs/apexcharts/apexcharts.min.js') }}"></script> --}}
    {{-- <script src="https://apexcharts.com/samples/assets/irregular-data-series.js"></script>
    <script src="https://apexcharts.com/samples/assets/ohlc.js"></script> --}}

    <!-- Dashboar 1 init js-->
    {{-- <script src="{{ asset('js/pages/dashboard-1.init.js') }}"></script> --}}

    <!-- init js -->
    {{-- <script src="{{ asset('js/pages/apexcharts.init.js') }}"></script> --}}
@endsection
