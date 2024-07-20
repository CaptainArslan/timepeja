const origin = window.location.origin.split(":");
const domain = origin.splice(0, 2).join(":");
const port = 3000;
const ip = domain + ":" + port;

const socket = io("http://localhost:3000");

socket.on("connect", () => {
    console.log(socket.id);

});

if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        (position) => {
            const { latitude, longitude } = position.coords;
            socket.emit("location", { latitude, longitude });
        },
        (error) => {
            console.log("Error getting location data: " + error.message);
        },
        {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0,
        }
    );
}

socket.on("message", (msg) => {
    console.log("New message received from server: " + msg);
});

socket.on("location", (location) => {
    console.log("New location received from server: " + location);
});
