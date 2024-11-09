const origin = window.location.origin.split(":");
const domain = origin.splice(0, 2).join(":");
const port = 3000;
const ip = domain + ":" + port;

let markers = {};

const socket = io("http://localhost:3000", {
    // const socket = io("https://socket-testing.stoppick.com", {
    transports: ["polling", "websocket"],
});

socket.on("connect", () => {
    // console.log("user Connected");
});

socket.on("admin-connected", (admin) => {
    let trips = admin.trips;

    for (const scheduleId in trips) {
        // console.log('trip data:', trips);
        if (trips.hasOwnProperty(scheduleId)) {
            let trip = trips[scheduleId].trip;
            console.log("trip data:", trip);
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
                lng: trip.longitude,
            };

            let startPosition = {
                lat: route.from_latitude,
                lng: route.from_longitude,
            };

            let endPosition = {
                lat: route.to_latitude,
                lng: route.to_longitude,
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
                    console.error("Map not initialized yet");
                }

                // Create current, start, and end markers
                markers[scheduleId]["current"] = createMarker(
                    currentPosition,
                    map,
                    "Current Location",
                    infoWindoowoptions,
                    labelOptions
                );
                markers[scheduleId]["start"] = createMarker(
                    startPosition,
                    map,
                    "Start",
                    infoWindoowoptions,
                    {
                        text: "\ue88a",
                        fontFamily: "Material Icons",
                        color: "#ffffff",
                        fontSize: "20px",
                    }
                );
                markers[scheduleId]["end"] = createMarker(
                    endPosition,
                    map,
                    "End",
                    infoWindoowoptions,
                    {
                        text: "\ue7f1",
                        fontFamily: "Material Icons",
                        color: "#ffffff",
                        fontSize: "20px",
                    }
                );

                console.log(
                    `Created markers for trip with scheduleId: ${scheduleId}`
                );
            } else {
                console.log(
                    `Markers already exist for scheduleId: ${scheduleId}`
                );
            }
        }
    }
});

socket.on("admin-joined", (data) => {
    showSuccess("Admin has joined the socket");
    console.log("admin joined from client:", data);
});

socket.on("manager-connected", (data) => {
    console.log("manager connected from client:", data);
    managers[data.id] = data;
});

socket.on("manager-disconnected", (data) => {
    console.log("manager disconnected from client:", data);
});

window.addEventListener("beforeunload", () => {
    socket.emit("admin-disconnected", {
        ...admin,
    });
});

socket.on("trip-started", (trip) => {
    console.log("trip statrted from client of trips: ", trip);
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
        lng: trip.longitude,
    };

    let startPosition = {
        lat: route.from_latitude,
        lng: route.from_longitude,
    };

    let endPosition = {
        lat: route.to_latitude,
        lng: route.to_longitude,
    };

    let wayPoints = route?.way_points ?? []; // Use optional chaining for safety
    showSuccess("New Trip has been started: " + scheduleId);

    // Ensure map is initialized before setting the center
    if (map) {
        map.setCenter(startPosition); // Center the map on the start position
    } else {
        console.error("Map not initialized yet");
    }

    // // Create the start and end pins
    // const startPin = createPinFromImage(
    //     "https://developers.google.com/maps/documentation/javascript/examples/full/images/google_logo_g.svg",
    //     "white"
    // );

    // const endPin = createPinFromImage(
    //     "https://developers.google.com/maps/documentation/javascript/examples/full/images/google_logo_g.svg",
    //     "white"
    // );

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

    markers[scheduleId]["current"] = createMarker(
        currentPosition,
        map,
        "Current Location",
        infoWindoowoptions,
        labelOptions
    );

    if (map) {
        map.setCenter(currentPosition);
    } else {
        console.error("Map not initialized yet");
    }

    // Add start and end markers
    markers[scheduleId]["start"] = createMarker(
        startPosition,
        map,
        "Start",
        infoWindoowoptions,
        {
            text: "\ue88a",
            fontFamily: "Material Icons",
            color: "#ffffff",
            fontSize: "20px",
        }
    );
    markers[scheduleId]["end"] = createMarker(
        endPosition,
        map,
        "End",
        infoWindoowoptions,
        {
            text: "\ue7f1",
            fontFamily: "Material Icons",
            color: "#ffffff",
            fontSize: "20px",
        }
    );

    // calculateAndDisplayRoute(map, currentPosition, startPosition, endPosition, wayPoints,
    //     directionsService, directionsRenderer);
});

socket.on("trip-location", (trip) => {
    console.log("Current location received from client:", trip);
    let managerId = trip.managerId;
    let scheduleId = trip.selected_schedule.id;
    let route = trip.selected_schedule.routes;

    // // Create the start and end pins
    // const startPin = createPinFromImage(
    //     "https://developers.google.com/maps/documentation/javascript/examples/full/images/google_logo_g.svg",
    //     "white"
    // );

    // const endPin = createPinFromImage(
    //     "https://developers.google.com/maps/documentation/javascript/examples/full/images/google_logo_g.svg",
    //     "white"
    // );

    let currentPosition = {
        lat: trip.latitude,
        lng: trip.longitude,
    };

    let startPosition = {
        lat: route.from_latitude,
        lng: route.from_longitude,
    };

    let endPosition = {
        lat: route.to_latitude,
        lng: route.to_longitude,
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
        if (markers[scheduleId]["current"]) {
            // console.log('latest location received from client:', trip, markers[scheduleId]['current']);

            // Update the existing marker's position
            markers[scheduleId]["current"].setPosition(
                new google.maps.LatLng(currentPosition.lat, currentPosition.lng)
            );
        } else {
            console.error(
                "No current marker found for scheduleId:",
                scheduleId
            );
            // Create the current location marker if it doesn't exist
            markers[scheduleId]["current"] = createMarker(
                currentPosition,
                map,
                "Current Location",
                infoWindoowoptions,
                labelOptions
            );
        }

        // Update start and end markers if they exist, otherwise create them
        if (!markers[scheduleId]["start"]) {
            markers[scheduleId]["start"] = createMarker(
                startPosition,
                map,
                "Start",
                infoWindoowoptions,
                {
                    text: "\ue88a",
                    fontFamily: "Material Icons",
                    color: "#ffffff",
                    fontSize: "20px",
                }
            );
        }

        // Update end marker if it exists, otherwise create it
        if (!markers[scheduleId]["end"]) {
            markers[scheduleId]["end"] = createMarker(
                endPosition,
                map,
                "End",
                infoWindoowoptions,
                {
                    text: "\ue7f1",
                    fontFamily: "Material Icons",
                    color: "#ffffff",
                    fontSize: "20px",
                }
            );
        }
    } else {
        // If no markers for this schedule, initialize and create all markers
        markers[scheduleId] = {};
        // Create current, start, and end markers
        markers[scheduleId]["current"] = createMarker(
            currentPosition,
            map,
            "Current Location",
            infoWindoowoptions,
            labelOptions
        );
        markers[scheduleId]["start"] = createMarker(
            startPosition,
            map,
            "Start",
            infoWindoowoptions,
            {
                text: "\ue88a",
                fontFamily: "Material Icons",
                color: "#ffffff",
                fontSize: "20px",
            }
        );
        markers[scheduleId]["end"] = createMarker(
            endPosition,
            map,
            "End",
            infoWindoowoptions,
            {
                text: "\ue7f1",
                fontFamily: "Material Icons",
                color: "#ffffff",
                fontSize: "20px",
            }
        );

        // calculate the route
        // calculateAndDisplayRoute(map, currentPosition, startPosition, endPosition, wayPoints,
        //     directionsService, directionsRenderer);
    }
});

socket.on("trip-ended", (trip) => {
    console.log("Trip ended from client:", trip);
    let scheduleId = trip.selected_schedule.id;

    // Check if there are markers for the trip in the `markers` object
    if (markers[scheduleId]) {
        console.log(`Removing markers for schedule: ${scheduleId}`);

        // Safely remove all markers associated with this trip if they exist
        ["current", "start", "end"].forEach((type) => {
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
});
