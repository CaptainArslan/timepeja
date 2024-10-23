@extends('layouts.app')
@section('title', 'Dashboard')
<!-- start page title -->
@section('page_css')
    <style>
        /* set the default transition time */
        :root {
            --delay-time: .5s;
        }

        .gmaps {
            height: 600px;
        }


        @keyframes drop {
            0% {
                transform: translateY(-200px) scaleY(0.9);
                opacity: 0;
            }

            5% {
                opacity: 0.7;
            }

            50% {
                transform: translateY(0px) scaleY(1);
                opacity: 1;
            }

            65% {
                transform: translateY(-17px) scaleY(0.9);
                opacity: 1;
            }

            75% {
                transform: translateY(-22px) scaleY(0.9);
                opacity: 1;
            }

            100% {
                transform: translateY(0px) scaleY(1);
                opacity: 1;
            }
        }

        .drop {
            animation: drop 0.3s linear forwards var(--delay-time);
        }
    </style>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

    <style>
        :root {
            --building-color: #FF9800;
            --house-color: #0288D1;
            --shop-color: #7B1FA2;
            --warehouse-color: #558B2F;
        }

        .property {
            align-items: center;
            background-color: #FFFFFF;
            border-radius: 50%;
            color: #263238;
            display: flex;
            font-size: 14px;
            gap: 15px;
            height: 30px;
            justify-content: center;
            padding: 4px;
            position: relative;
            position: relative;
            transition: all 0.3s ease-out;
            width: 30px;
        }

        .property::after {
            border-left: 9px solid transparent;
            border-right: 9px solid transparent;
            border-top: 9px solid #FFFFFF;
            content: "";
            height: 0;
            left: 50%;
            position: absolute;
            top: 95%;
            transform: translate(-50%, 0);
            transition: all 0.3s ease-out;
            width: 0;
            z-index: 1;
            margin-top: -2px;
        }

        .property .icon {
            align-items: center;
            display: flex;
            justify-content: center;
            color: #FFFFFF;
        }

        .property .icon svg {
            height: 20px;
            width: auto;
        }

        .property .details {
            display: none;
            flex-direction: column;
            flex: 1;
        }

        .property .address {
            color: #9E9E9E;
            font-size: 10px;
            margin-bottom: 10px;
            margin-top: 5px;
        }

        .property .features {
            align-items: flex-end;
            display: flex;
            flex-direction: row;
            gap: 10px;
        }

        .property .features>div {
            align-items: center;
            background: #F5F5F5;
            border-radius: 5px;
            border: 1px solid #ccc;
            display: flex;
            font-size: 10px;
            gap: 5px;
            padding: 5px;
        }

        .property.highlight {
            background-color: #FFFFFF;
            border-radius: 8px;
            box-shadow: 10px 10px 5px rgba(0, 0, 0, 0.2);
            height: 80px;
            padding: 8px 15px;
            width: auto;
        }

        .property.highlight::after {
            border-top: 9px solid #FFFFFF;
        }

        .property.highlight .details {
            display: flex;
        }

        .property.highlight .icon svg {
            width: 50px;
            height: 50px;
        }

        .property .bed {
            color: #FFA000;
        }

        .property .bath {
            color: #03A9F4;
        }

        .property .size {
            color: #388E3C;
        }

        .property.highlight:has(.fa-house) .icon {
            color: var(--house-color);
        }

        .property:not(.highlight):has(.fa-house) {
            background-color: var(--house-color);
        }

        .property:not(.highlight):has(.fa-house)::after {
            border-top: 9px solid var(--house-color);
        }

        .property.highlight:has(.fa-building) .icon {
            color: var(--building-color);
        }

        .property:not(.highlight):has(.fa-building) {
            background-color: var(--building-color);
        }

        .property:not(.highlight):has(.fa-building)::after {
            border-top: 9px solid var(--building-color);
        }

        .property.highlight:has(.fa-warehouse) .icon {
            color: var(--warehouse-color);
        }

        .property:not(.highlight):has(.fa-warehouse) {
            background-color: var(--warehouse-color);
        }

        .property:not(.highlight):has(.fa-warehouse)::after {
            border-top: 9px solid var(--warehouse-color);
        }

        .property.highlight:has(.fa-shop) .icon {
            color: var(--shop-color);
        }

        .property:not(.highlight):has(.fa-shop) {
            background-color: var(--shop-color);
        }

        .property:not(.highlight):has(.fa-shop)::after {
            border-top: 9px solid var(--shop-color);
        }
    </style>
@endsection
@section('content')

    @include('dashboard.partials.dashborad-page')

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">Map</h4>
                <div class="mb-3">
                    <label class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search on map" aria-label="Username"
                            aria-describedby="basic-addon1">
                        <span class="input-group-text" id="basic-addon1" role="button"><i class="fas fa-search"></i></span>
                    </div>
                </div>
                <div id="map" class="gmaps"></div>
            </div>
        </div> <!-- end card-->
    </div>

@endsection

@section('page_js')

    <script src="{{ asset('js/socketclient.js') }}"></script>
    <script src="https://use.fontawesome.com/releases/v6.2.0/js/all.js"></script>
    <script>
        (g => {
            var h, a, k, p = "The Google Maps JavaScript API",
                c = "google",
                l = "importLibrary",
                q = "__ib__",
                m = document,
                b = window;
            b = b[c] || (b[c] = {});
            var d = b.maps || (b.maps = {}),
                r = new Set,
                e = new URLSearchParams,
                u = () => h || (h = new Promise(async (f, n) => {
                    await (a = m.createElement("script"));
                    e.set("libraries", [...r] + "");
                    for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
                    e.set("callback", c + ".maps." + q);
                    a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                    d[q] = f;
                    a.onerror = () => h = n(Error(p + " could not load."));
                    a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                    m.head.append(a)
                }));
            d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() =>
                d[l](f, ...n))
        })({
            key: "AIzaSyAnviR5bZwRYNdstAiky365nBxvVKswzzQ",
            v: "weekly",
            // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
            // Add other bootstrap parameters as needed, using camel case.
        });
    </script>

    <script>
        let markers = {};
        let routePath = {};
        let schedule = @json($schedule);
        let trips = {};
        let managers = {};
        let admin = @json($admin);

        socket.on('manager-connected', (data) => {
            console.log('manager connected from client:', data);
            managers[data.id] = data;
        });

        socket.on('manager-disconnected', (data) => {
            console.log('manager disconnected from client:', data);
            delete managers[data.id];
        });

        let initialLocation = {
            lat: 32.1955303,
            lng: 74.202066
        };

        let infoWindoowoptions = {
            address: "215 Emily St, MountainView, CA",
            description: "Single family house with modern design",
            type: "bus",
            driver: "John Doe",
            route: 0,
            size: 1,
        };

        let labelOptions = {
            text: "\ue530", // codepoint from https://fonts.google.com/icons
            fontFamily: "Material Icons",
            color: "#ffffff",
            fontSize: "20px",
        };

        const labels = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        let labelIndex = 0;

        const intersectionObserver = new IntersectionObserver((entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    entry.target.classList.add("drop");
                    intersectionObserver.unobserve(entry.target);
                }
            }
        });

        async function initMap() {
            // Request needed libraries.
            const {
                Map
            } = await google.maps.importLibrary("maps");
            const {
                AdvancedMarkerElement
            } = await google.maps.importLibrary("marker");

            map = new Map(document.getElementById("map"), {
                center: {
                    lat: initialLocation.lat,
                    lng: initialLocation.lng
                },
                zoom: 14,
                mapTypeId: google.maps.MapTypeId.TERRAIN,
                mapId: "4504f8b37365c3d0",
            });

            // Get directions between starting and ending points
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
            });

            // console.log('admin connetion data socket call');
            socket.emit('admin-connected', {
                socketId: socket.id,
                ...admin
            });
        }

        socket.on('admin-connected', (admin) => {
            let trips = admin.trips;
            console.log('admin connected from client:', admin);

            for (const scheduleId in trips) {
                // console.log('trip data:', trips);
                if (trips.hasOwnProperty(scheduleId)) {
                    let trip = trips[scheduleId].trip;
                    console.log('trip data:', trip);
                    let route = trip.selected_schedule.routes;

                    // Create the start and end pins
                    const startPin = createPinFromImage(
                        "https://developers.google.com/maps/documentation/javascript/examples/full/images/google_logo_g.svg",
                        "white"
                    );

                    const endPin = createPinFromImage(
                        "https://developers.google.com/maps/documentation/javascript/examples/full/images/google_logo_g.svg",
                        "white"
                    );

                    let currentPosition = {
                        lat: trip.latitude,
                        lng: trip.longitude
                    };

                    let startPosition = {
                        lat: route.from_latitude,
                        lng: route.from_longitude
                    };

                    let endPosition = {
                        lat: route.to_latitude,
                        lng: route.to_longitude
                    };

                    infoWindoowoptions = {
                        address: route.name,
                        description: route.name,
                        type: "bus",
                        driver: trip.selected_schedule.drivers,
                        route: route,
                        size: 1,
                    };

                    let wayPoints = route?.way_points ?? [];

                    // Check if markers for this schedule already exist
                    if (!markers[scheduleId]) {
                        // Initialize the markers object for this scheduleId
                        markers[scheduleId] = {};

                        if (map) {
                            map.setCenter(currentPosition); // Center the map on the start position
                        } else {
                            console.error('Map not initialized yet');
                        }

                        // Create current, start, and end markers
                        markers[scheduleId]['current'] = createMarker(currentPosition, map, "Current Location",
                            infoWindoowoptions, labelOptions);
                        markers[scheduleId]['start'] = createMarker(startPosition, map, "Start",
                            infoWindoowoptions, {
                                text: "\ue88a",
                                fontFamily: "Material Icons",
                                color: "#ffffff",
                                fontSize: "20px",
                            });
                        markers[scheduleId]['end'] = createMarker(endPosition, map, "End", infoWindoowoptions, {
                            text: "\ue7f1",
                            fontFamily: "Material Icons",
                            color: "#ffffff",
                            fontSize: "20px",
                        });

                        console.log(`Created markers for trip with scheduleId: ${scheduleId}`);
                    } else {
                        console.log(`Markers already exist for scheduleId: ${scheduleId}`);
                    }
                }
            }

        });

        // Listen for socket events outside of the async function
        // Socket event listener
        socket.on("trip-started", (trip) => {
            console.log('trip statrted from client of trips: ', trip);
            let managerId = trip.managerId;
            let scheduleId = trip.selected_schedule.id;
            let route = trip.selected_schedule.routes;
            let driver = trip.selected_schedule.driver;

            // Ensure trips[managerId] is initialized before adding the schedule
            // if (!trips[managerId]) {
            //     trips[managerId] = {};
            // }
            // trips[managerId][scheduleId] = trip; // Store trip data

            trips[scheduleId] = trip;

            let currentPosition = {
                lat: trip.latitude,
                lng: trip.longitude
            };

            let startPosition = {
                lat: route.from_latitude,
                lng: route.from_longitude
            };

            let endPosition = {
                lat: route.to_latitude,
                lng: route.to_longitude
            };

            let wayPoints = route?.way_points ?? []; // Use optional chaining for safety
            showSuccess("New Trip has been started: " + scheduleId);

            // Ensure map is initialized before setting the center
            if (map) {
                map.setCenter(startPosition); // Center the map on the start position
            } else {
                console.error('Map not initialized yet');
            }

            // Create the start and end pins
            const startPin = createPinFromImage(
                "https://developers.google.com/maps/documentation/javascript/examples/full/images/google_logo_g.svg",
                "white"
            );

            const endPin = createPinFromImage(
                "https://developers.google.com/maps/documentation/javascript/examples/full/images/google_logo_g.svg",
                "white"
            );

            // Ensure markers[scheduleId] is initialized before adding individual markers
            if (!markers[scheduleId]) {
                markers[scheduleId] = {};
            }

            infoWindoowoptions = {
                address: route.name,
                description: route.name,
                type: "bus",
                driver: trip.selected_schedule.drivers,
                route: route,
                size: 1,
            };

            markers[scheduleId]['current'] = createMarker(currentPosition, map, "Current Location",
                infoWindoowoptions,
                labelOptions);

            if (map) {
                map.setCenter(currentPosition);
            } else {
                console.error('Map not initialized yet');
            }

            // Add start and end markers
            markers[scheduleId]['start'] = createMarker(startPosition, map, "Start", infoWindoowoptions, {
                text: "\ue88a",
                fontFamily: "Material Icons",
                color: "#ffffff",
                fontSize: "20px",
            });
            markers[scheduleId]['end'] = createMarker(endPosition, map, "End", infoWindoowoptions, {
                text: "\ue7f1",
                fontFamily: "Material Icons",
                color: "#ffffff",
                fontSize: "20px",
            });

            // calculateAndDisplayRoute(map, currentPosition, startPosition, endPosition, wayPoints,
            //     directionsService, directionsRenderer);

        });

        socket.on("trip-location", (trip) => {
            console.log('Current location received from client:');
            let managerId = trip.managerId;
            let scheduleId = trip.selected_schedule.id;
            let route = trip.selected_schedule.routes;

            // Create the start and end pins
            const startPin = createPinFromImage(
                "https://developers.google.com/maps/documentation/javascript/examples/full/images/google_logo_g.svg",
                "white"
            );

            const endPin = createPinFromImage(
                "https://developers.google.com/maps/documentation/javascript/examples/full/images/google_logo_g.svg",
                "white"
            );

            let currentPosition = {
                lat: trip.latitude,
                lng: trip.longitude
            };

            let startPosition = {
                lat: route.from_latitude,
                lng: route.from_longitude
            };

            let endPosition = {
                lat: route.to_latitude,
                lng: route.to_longitude
            };

            infoWindoowoptions = {
                address: route.name,
                description: route.name,
                type: "bus",
                driver: trip.selected_schedule.drivers,
                route: route,
                size: 1,
            };

            let wayPoints = route?.way_points ?? []; // Use optional chaining for safety

            // Check if the schedule already has markers
            if (markers[scheduleId]) {
                // Update current marker position if it exists
                if (markers[scheduleId]['current']) {
                    // console.log('latest location received from client:', trip, markers[scheduleId]['current']);

                    // Update the existing marker's position
                    markers[scheduleId]['current'].setPosition(new google.maps.LatLng(currentPosition.lat,
                        currentPosition.lng));
                } else {
                    console.error('No current marker found for scheduleId:', scheduleId);
                    // Create the current location marker if it doesn't exist
                    markers[scheduleId]['current'] = createMarker(currentPosition, map, "Current Location",
                        infoWindoowoptions,
                        labelOptions);
                }

                // Update start and end markers if they exist, otherwise create them
                if (!markers[scheduleId]['start']) {
                    markers[scheduleId]['start'] = createMarker(startPosition, map, "Start", infoWindoowoptions, {
                        text: "\ue88a",
                        fontFamily: "Material Icons",
                        color: "#ffffff",
                        fontSize: "20px",
                    });
                    // markers[scheduleId]['start'] = createMarker(startPosition, map, "Start", startPin
                    //     .element);
                }
                if (!markers[scheduleId]['end']) {
                    markers[scheduleId]['end'] = createMarker(endPosition, map, "End", infoWindoowoptions, {
                        text: "\ue7f1",
                        fontFamily: "Material Icons",
                        color: "#ffffff",
                        fontSize: "20px",
                    });
                }
            } else {
                // If no markers for this schedule, initialize and create all markers
                markers[scheduleId] = {};
                // Create current, start, and end markers
                markers[scheduleId]['current'] = createMarker(currentPosition, map, "Current Location",
                    infoWindoowoptions, labelOptions);
                markers[scheduleId]['start'] = createMarker(startPosition, map, "Start", infoWindoowoptions, {
                    text: "\ue88a",
                    fontFamily: "Material Icons",
                    color: "#ffffff",
                    fontSize: "20px",
                });
                markers[scheduleId]['end'] = createMarker(endPosition, map, "End", infoWindoowoptions, {
                    text: "\ue7f1",
                    fontFamily: "Material Icons",
                    color: "#ffffff",
                    fontSize: "20px",
                });

                // calculate the route
                // calculateAndDisplayRoute(map, currentPosition, startPosition, endPosition, wayPoints,
                //     directionsService, directionsRenderer);
            }
        });

        socket.on('trip-ended', (trip) => {
            console.log('Trip ended from client:', trip);
            let scheduleId = trip.selected_schedule.id;

            // Check if there are markers for the trip in the `markers` object
            if (markers[scheduleId]) {
                console.log(`Removing markers for schedule: ${scheduleId}`);

                // Safely remove all markers associated with this trip if they exist
                ['current', 'start', 'end'].forEach(type => {
                    if (markers[scheduleId][type]) {
                        markers[scheduleId][type].setMap(null); // Remove marker from map
                    }
                });

                // Delete the markers from the markers object after they are removed
                delete markers[scheduleId];
            } else {
                console.error(`No markers found for schedule: ${scheduleId}`);
            }

            // Remove the trip from the `trips` object
            if (trips[scheduleId]) {
                delete trips[scheduleId];
                console.log(`Trip ${scheduleId} has been removed from trips object.`);
            } else {
                console.error(`No trip found with ID: ${scheduleId}`);
            }

            // Optionally, emit an acknowledgment to the server if needed
            // socket.emit('trip-ended-acknowledged', {
            //     scheduleId: scheduleId,
            //     message: `Trip ${scheduleId} has been successfully ended and markers removed.`
            // });
        });

        function createAnimatedMarker(position, map, title = "Current Position", content = null) {
            // Create a new marker with the provided parameters and assign a unique id
            const marker = new google.maps.marker.AdvancedMarkerElement({
                map,
                position: position,
                title: title,
            });

            // Add a click event listener to the marker
            marker.addListener("click", () => {
                toggleHighlight(marker, infoWindoowoptions);
            });

            // Handle content animation
            const markerContent = marker.content;

            if (markerContent) {
                markerContent.style.opacity = "0";

                markerContent.addEventListener("animationend", () => {
                    markerContent.classList.remove("drop");
                    markerContent.style.opacity = "1";
                });

                // Set a random delay for the animation
                const time = 1 + Math.random(); // 1s delay + random for visibility
                markerContent.style.setProperty("--delay-time", time + "s");

                // Create an IntersectionObserver if needed
                // Assuming you have a previously defined intersectionObserver
                if (typeof intersectionObserver !== 'undefined') {
                    intersectionObserver.observe(markerContent);
                }
            }

            return marker;
        }

        function createMarker(position, map, title, infoWindoowoptions, label = null) {
            // Create a new marker using the standard google.maps.Marker class
            const marker = new google.maps.Marker({
                position: position,
                map: map,
                label: label,
                title: title,
            });

            // Create an info window with custom content
            const infoWindow = new google.maps.InfoWindow({
                content: buildContent(infoWindoowoptions)
            });

            // Add a click event listener to open the info window
            marker.addListener("click", () => {
                // Open the info window on marker click
                infoWindow.open(map, marker);
            });

            return marker;
        }

        function removeMarker(id) {
            if (markers[id]) {
                markers[id].setMap(null);
                delete markers[id];
            }
        }

        // Draw polyline
        function drawPolyline($paths = []) {
            // Create and display a polyline
            routePath[id] = new google.maps.Polyline({

                path: $paths,
                geodesic: true,
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 2,
                map: map,
            });
        }

        // Function to create a custom pin
        function createPinFromImage(glyphSrc = null, glyphColor = "white", scale = 1) {

            pin = new google.maps.marker.PinElement({
                scale: scale,
                // glyph: glyphImg,
                glyphColor: glyphColor,
            });

            if (glyphSrc) {
                const glyphImg = document.createElement("img");
                glyphImg.src = glyphSrc;
                pin.glyph = glyphImg;
            }

            return pin;
        }

        // Calculate and display route
        function calculateAndDisplayRoute(map, currentPosition, startPosition, endPosition, waypointsArray,
            directionsService, directionsRenderer) {
            const request = {
                origin: currentPosition, // Use current location as the origin
                destination: endPosition,
                // waypoints: waypointsArray.map(point => ({
                //     location: new google.maps.LatLng(point.latitude, point.longitude),
                //     stopover: true
                // })), // Extract latitude and longitude for waypoints
                travelMode: google.maps.TravelMode.DRIVING,
            };

            directionsService.route(request, (result, status) => {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(result);
                } else {
                    console.error("Error calculating directions:", status);
                }
            });
        }

        function toggleHighlight(markerView, property) {
            if (markerView.content && markerView.content.classList.contains("highlight")) {
                markerView.content.classList.remove("highlight");
                markerView.setZIndex(null);
            } else {
                markerView.content.classList.add("highlight");
                markerView.setZIndex(1);
            }
        }

        function buildContent(property) {
            const content = document.createElement("div");

            // content.classList.add("property");
            content.innerHTML = `
                <div class="icon">
                    <i aria-hidden="true" class="fa fa-icon fa-${property.type}" title="${property.type}"></i>
                    <span class="fa-sr-only">${property.type}</span>
                </div>
                <div class="details">
                    <div class="address">${property.address}</div>
                    <div class="features">
                        <div>
                            <i class="fa-solid fa-user" title="Driver"></i>
                            <span>${property.driver.name}</span>
                        </div>
                    </div>
                </div>
            `;
            return content;
        }

        initMap();
    </script>
@endsection
