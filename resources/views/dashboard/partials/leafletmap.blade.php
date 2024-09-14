<script src="{{ asset('js\socketclient.js') }}"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
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
                document.getElementById("location-latlong").innerText =
                    ` Lat: ${position.coords.latitude}, Long: ${position.coords.longitude}`;
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
