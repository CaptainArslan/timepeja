// Log successful connection
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('Connected to Pusher successfully.');
    newNotificationSound();
});

// Log connection state changes
window.Echo.connector.pusher.connection.bind('state_change', (states) => {
    console.log('Pusher connection state changed from ' + states.previous + ' to ' + states.current);
    systemNotificationSound()
});

// Log disconnection
window.Echo.connector.pusher.connection.bind('disconnected', () => {
    console.log('Disconnected from Pusher.');
});

// Log error
window.Echo.connector.pusher.connection.bind('error', (error) => {
    console.error('Pusher connection error:', error);
});

Echo.channel('example').listen('ExampleEvent', function (event) {
    document.getElementById('trip-count').innerHTML = event.message;
});
