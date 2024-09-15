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

        let initialLocation = {
            lat: 32.1955303,
            lng: 74.202066
        };
        let options = {
            address: "215 Emily St, MountainView, CA",
            description: "Single family house with modern design",
            price: "$ 3,889,000",
            type: "home",
            bed: 5,
            bath: 4.5,
            size: 300,
            position: {
                lat: 32.1955303,
                lng: 74.202066
            },
        };

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

            const map = new Map(document.getElementById("map"), {
                center: {
                    lat: initialLocation.lat,
                    lng: initialLocation.lng
                },
                zoom: 14,
                mapTypeId: google.maps.MapTypeId.TERRAIN,
                mapId: "4504f8b37365c3d0",
            });

            // Get directions between starting and ending points
            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
            });

            // const currentPin = createPinFromImage();

            const startPin = createPinFromImage(
                "https://developers.google.com/maps/documentation/javascript/examples/full/images/google_logo_g.svg",
                "white"
            );

            const endPin = createPinFromImage(
                "https://developers.google.com/maps/documentation/javascript/examples/full/images/google_logo_g.svg",
                "white"
            );

            // if (navigator.geolocation) {
            //     navigator.geolocation.watchPosition(
            //         (position) => {
            //             // Success callback
            //             const {
            //                 latitude,
            //                 longitude
            //             } = position.coords;
            //             document.getElementById("location-latlong").innerText =
            //                 `Lat: ${latitude}, Long: ${longitude}`;

            //             // Emit location data
            //             socket.emit("location", {
            //                 socket_id: socket.id,
            //                 latitude,
            //                 longitude,
            //                 ...schedule
            //             });
            //         },
            //         (error) => {
            //             // Error callback
            //             let errorMessage;
            //             switch (error.code) {
            //                 case error.PERMISSION_DENIED:
            //                     errorMessage = "User denied the request for Geolocation.";
            //                     break;
            //                 case error.POSITION_UNAVAILABLE:
            //                     errorMessage = "Location information is unavailable.";
            //                     break;
            //                 case error.TIMEOUT:
            //                     errorMessage = "The request to get user location timed out.";
            //                     break;
            //                 case error.UNKNOWN_ERROR:
            //                     errorMessage = "An unknown error occurred.";
            //                     break;
            //             }
            //             console.error("Error getting location data: " + errorMessage);
            //         }, {
            //             enableHighAccuracy: true, // Use high accuracy if available
            //             timeout: 10000, // Timeout for obtaining the location
            //             maximumAge: 0 // Do not use cached location
            //         }
            //     );
            // } else {
            //     alert("Geolocation is not supported by this browser.");
            // }

            socket.on("location", (data) => {
                console.log('data received from client:  ' + data.socket_id, data);
                let id = data.socket_id;
                let route = data.route;

                let position = {
                    lat: data.latitude,
                    lng: data.longitude
                };

                let startPosition = {
                    lat: route.from_latitude,
                    lng: route.from_longitude
                };

                let endPosition = {
                    lat: route.to_latitude,
                    lng: route.to_longitude
                };

                let wayPoints = route.waypoints ?? [];

                if (markers[id]) {
                    console.log('updating marker position with new position');
                    markers[id].position = new google.maps.LatLng(position.lat, position.lng);

                    calculateAndDisplayRoute(map, position, startPosition, endPosition, wayPoints,
                        directionsService,
                        directionsRenderer);

                } else {
                    console.log('creating new marker');
                    showSuccess("New Trips has been started");
                    map.setCenter(position);
                    markers[id] = createAnimatedMarker(id, position, map, "Current Position");
                    markers[id]['start'] = createAnimatedMarker(id, startPosition, map, "Start Position",
                        startPin.element);
                    markers[id]['end'] = createAnimatedMarker(id, endPosition, map, "Start Position",
                        endPin.element);

                    console.log('calculating the route with new marker');
                    calculateAndDisplayRoute(map, position, startPosition, endPosition, wayPoints,
                        directionsService,
                        directionsRenderer);

                }

            });
        }

        function createAnimatedMarker(id, position, map, title = "Current Position", content = null) {
            // Create a new marker with the provided parameters
            const marker = new google.maps.marker.AdvancedMarkerElement({
                map,
                position: position,
                title: title,
                content: content ?? buildContent(options),
            });

            // Add a click event listener to the marker
            marker.addListener("click", () => {
                toggleHighlight(marker, options);
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

        function createMarker(position, map, title, content) {
            // Create a new marker with the provided parameters
            const marker = new google.maps.marker.AdvancedMarkerElement({
                map: map,
                position: position,
                title: title,
                content: content,
            });

            // Optionally, add a click event listener to the marker
            marker.addListener("click", () => {
                alert(`${title} marker clicked!`);
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
            if (markerView.content.classList.contains("highlight")) {
                markerView.content.classList.remove("highlight");
                markerView.zIndex = null;
            } else {
                markerView.content.classList.add("highlight");
                markerView.zIndex = 1;
            }
        }

        function buildContent(property) {
            const content = document.createElement("div");

            content.classList.add("property");
            content.innerHTML = `
                <div class="icon">
                    <i aria-hidden="true" class="fa fa-icon fa-${property.type}" title="${property.type}"></i>
                    <span class="fa-sr-only">${property.type}</span>
                </div>
                <div class="details">
                    <div class="price">${property.price}</div>
                    <div class="address">${property.address}</div>
                    <div class="features">
                    <div>
                        <i aria-hidden="true" class="fa fa-bed fa-lg bed" title="bedroom"></i>
                        <span class="fa-sr-only">bedroom</span>
                        <span>${property.bed}</span>
                    </div>
                    <div>
                        <i aria-hidden="true" class="fa fa-bath fa-lg bath" title="bathroom"></i>
                        <span class="fa-sr-only">bathroom</span>
                        <span>${property.bath}</span>
                    </div>
                    <div>
                        <i aria-hidden="true" class="fa fa-ruler fa-lg size" title="size"></i>
                        <span class="fa-sr-only">size</span>
                        <span>${property.size} ft<sup>2</sup></span>
                    </div>
                    </div>
                </div>
                `;
            return content;
        }
        initMap();
    </script>
@endsection
