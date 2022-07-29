$(document).ready(function() {

	// refresh page fadein
	$("#wrap, .wrap_pop, #wrap_login").addClass("fadein");

	// left menu
	$("#btn_bar").click(function() {
		$("#wrap").toggleClass("gnb_open");
	});

});