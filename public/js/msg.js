$(document).ready(function(){

	/* If page has alerts, fade out after 3s */
	if($('.alert').length > 0){
		setTimeout(function(){
			$('.alert').fadeOut(500)
		}, 3000);
	}

	/* Toggle notification bar */
	$('.notification-bar .panel-heading').click(function(){
		$('.panel-body').toggle(300);
	});
});