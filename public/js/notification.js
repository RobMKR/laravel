$(document).ready(function () {
    if(Notification.permission === "denied"){
        alert('You have blocked Our Notifications. If You want to see them again, allow it from your browser settings.');
    }
  	if(Notification.permission !== "granted"){
    	Notification.requestPermission();
    }
});

function notifyMe(params) {
    if (!Notification) {
        alert('Desktop notifications not available in your browser. Try Chromium.'); 
        return;
    }

    if (Notification.permission !== "granted"){
        Notification.requestPermission();
    } else {
	    var notification = new Notification('Laravel Test App', {
	        icon: '/img/Laravel.png',
	        body: "User " + params.user + " sends Notification. To see Notification click on The Notifications tab.",
	    });
        $(".panel-heading").click();
    }
}