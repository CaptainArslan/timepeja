@extends('layouts.app')
@section('title', 'Dashboard')
<!-- start page title -->
@section('page_css')


    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

    {{-- <style>
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
    </style> --}}
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
    <script src="https://use.fontawesome.com/releases/v6.2.0/js/all.js"></script>
    {{-- google map scripts --}}
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
        let routePath = {};
        let trips = {};
        let admin = @json($admin);

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

        initMap();
    </script>

    <script src="{{ asset('js/socketclient.js') }}"></script>
@endsection
