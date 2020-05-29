// Import and configure the Firebase SDK
// These scripts are made available when the app is served or deployed on Firebase Hosting
// If you do not serve/host your project using Firebase Hosting see https://firebase.google.com/docs/web/setup

importScripts('https://www.gstatic.com/firebasejs/6.3.3/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/6.3.3/firebase-messaging.js');
var firebaseConfig = {
    apiKey: "AIzaSyA18vNVvPA-COxFN3ZKFo-L5rpfi11VrZo",
    authDomain: "cybermeteors.firebaseapp.com",
    databaseURL: "https://cybermeteors.firebaseio.com",
    projectId: "cybermeteors",
    storageBucket: "",
    messagingSenderId: "568623151193",
    appId: "1:568623151193:web:c47cb1f3fe36bb88"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);

var messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    // Customize notification here
    var notificationTitle=payload.data.title;
           var notificationOptions={
                body : payload.data.body,
                icon : payload.data.icon,
               click_action: payload.data.click_action,
               ata: {
                   time: new Date().toString(),
                   click_action: payload.data.click_action
               }// To handle notification click when notification is moved to notification tray
            };

    return self.registration.showNotification(notificationTitle,
        notificationOptions);
});
self.addEventListener('notificationclick',function (event) {
    var click_action =  event.notification.data.click_action;
    console.log('background' +event.notification);
    event.notification.close();
    event.waitUntil(
        clients.openWindow(click_action)
    );
});
// [END background_handler]