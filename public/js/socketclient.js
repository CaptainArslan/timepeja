const origin = window.location.origin.split(":");
const domain = origin.splice(0, 2).join(":");
const port = 3000;
const ip = domain + ":" + port;

// const socket = io("http://localhost:3000", {
const socket = io("https://socket-testing.stoppick.com", {
    transports: ["polling", "websocket"],
});

socket.on("connect", () => {
    // console.log("user Connected");
    // socket.emit("message", "connection request from client");
});

// socket.on("message", (msg) => {
//     console.log(msg);
// });
