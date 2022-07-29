$(document).ready(function() {

	// refresh page fadein
	$("#wrap, .wrap_pop, #wrap_login").addClass("fadein");

	// left menu
	$("#btn_bar").click(function() {
		$("#wrap").toggleClass("gnb_open");
	});
	$("#gnb").click(function() {
		$("#wrap").removeClass("gnb_open");
	});
	
	/*var burger = $('.menu-trigger');
    burger.each(function(index) {
        var $this = $(this);
        $this.on('click', function(e) {
            e.preventDefault();
            $(this).toggleClass('active-1');
            $('#gnb').toggleClass('gnb_on');
			$('.tablet_bg').fadeToggle('tablet_bg_on');

            var temp = $('#gnb').hasClass('gnb_on');
			$('.tablet_bg').click(function(){
				$('#gnb').removeClass('gnb_on');
				$('.tablet_bg').fadeOut('tablet_bg_on');
				burger.removeClass('active-1');
			});

            if (temp == true) {
                wheelActive = false;
            } else {
                wheelActive = true;
            }
        });
    });*/

});

