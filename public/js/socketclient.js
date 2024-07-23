const origin = window.location.origin.split(":");
const domain = origin.splice(0, 2).join(":");
const port = 3000;
const ip = domain + ":" + port;

// const socket = io("http://localhost:3000");
// const socket = io("https://node-socket-app.vercel.app", {
const socket = io("http://socket-test.stoppick.com", {
    transports: ["polling", "websocket"],
});

socket.on("connect", () => {
    socket.emit("message", "Hello from client : " + socket.id);

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
});

socket.on("message", (msg) => {
    console.log("New message received from server: " + msg);
});
