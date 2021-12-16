importScripts('https://www.gstatic.com/firebasejs/7.2.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.2.0/firebase-messaging.js');


firebase.initializeApp({
	messagingSenderId: "DEBE IR EL messagingSenderId"
});

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload){
	console.log('poopayload',payload)
	var obj = JSON.parse(payload.data.notification)
	var notificationTitle= obj.title;
	var notificationOptions ={
		body:obj.body,
		icon: obj.icon
	}
	return self.registration.showNotification(notificationTitle,notificationOptions)
})
