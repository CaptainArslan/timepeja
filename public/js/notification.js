function errorNotificationSound() {
    let audio = new Audio('/audio/error.mp3');
    audio.play().catch(function (error) {
        this.dispatch(new Events('click'));
        console.error('Audio playback failed:', error);
    });
}

function liveChatSound() {
    let audio = new Audio('/audio/livechat.mp3');
    audio.play().catch(function (error) {
        this.dispatch(new Events('click'));
        console.error('Audio playback failed:', error);
    });
}

function newNotificationSound() {
    let audio = new Audio('/audio/new-notification.mp3');
    audio.play().catch(function (error) {
        this.dispatch(new Events('click'));
        console.error('Audio playback failed:', error);
    });
}

function notificationSound() {
    let audio = new Audio('/audio/notification.mp3');
    audio.play().catch(function (error) {
        this.dispatch(new Events('click'));
        console.error('Audio playback failed:', error);
    });
}

function systemNotificationSound() {
    let audio = new Audio('/audio/system-notification.mp3');
    audio.play().catch(function (error) {
        this.dispatch(new Events('click'));
        console.error('Audio playback failed:', error);
    });
}

function typingSound() {
    let audio = new Audio('/audio/whatsapp-typing.mp3');
    audio.play().catch(function (error) {
        this.dispatch(new Events('click'));
        console.error('Audio playback failed:', error);
    });
}

