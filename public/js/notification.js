var Panel = new function(){
    this.open = function(time){
        if(!$('.panel-body').is(':visible')){
            $('.panel-body').show(time);
        }
    };

    this.close = function(time){
        if($('.panel-body').is(':visible')){
            $('.panel-body').hide(time);
        }
    };

    this.toggle = function(time){
        $('.panel-body').toggle(time);
    };
}

$(document).ready(function () {

    window.name = 'test';

    /* Toggle notification bar */
    $('.notification-bar .panel-heading').click(function(){
        Panel.toggle(300);
    });

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
    }
}