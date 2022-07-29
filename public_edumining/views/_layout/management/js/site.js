
// menu GNB
$(function(){
	/*
	   $(".gnb > li").hover(function() {
		var $el = $(this),
			$list = $el.find("ul"),
			$anchor = $el.find("a"); 
                
        // hover 상태유지
        $el.addClass("active");
        
        // 2depth 열기
        $list.show();                
    }, function() {
        var $el = $(this);
        
        // 2depth 닫기
        $el
            .find("ul")
            .hide()
            .end()
            .removeClass("active");
    }); 
	*/

    $(".gnb > li").bind("mouseenter", function() {
		if( $(this).is(".js_on") ) {
			$(this).siblings(":not(.js_on)").removeClass('active').children("ul").hide();
		} else {
			$(this).siblings().removeClass('active').children("ul").hide();
		}
		$(this).addClass('active').children("ul").show();         
    }); 

	$(".gnb > li > ul > li").bind("mouseenter", function() {
		if( $(this).is(".js_on") ) {
			$(this).siblings(":not(.js_on)").removeClass('on');
		} else {
			$(this).siblings().removeClass('on');
		}
		$(this).addClass('on');
    }); 

	$(".gnb").bind("mouseleave", function() {
		$(this).find("> li:not(.js_on)").removeClass('active').children("ul").hide();
		$(this).find("> li > ul > li").removeClass("on");
		$(this).find("> li.js_on").addClass('active').children("ul").show().find("> li.js_on").addClass("on");
	});


    $(".gnb > li.js_on").addClass("active").find("ul").show().find("> li.js_on").addClass("on");
});

// TOP_BTN
$(function() {
	if($("div").hasClass("btn_top")){
		
		// hide #back-top first
		$("#btn_top").hide();

		// fade in #back-top
		$(function () {
			$(window).scroll(function () {
				if ($(this).scrollTop() > 480) {
					$('#btn_top').fadeIn();
				} else {
					$('#btn_top').fadeOut();
				}
			});

			// scroll body to 0px on click
			$("#btn_top a").on("click", function(){
				$('body,html').animate({
					scrollTop: 0
				}, 300);
				return false;
			});
		});
	}
});

// toggle click
function toggle(id) 
{ 
   $('#' + id).each(function() { 
      if($(this).css('display') == 'none') 
      { 
         $(this).show(); 
      } 
      else 
      { 
         $(this).hide(); 
      } 
   }); 
} 

// 디자인 적용된 라디오 버튼세트
function txCreateRadioButton(selector, on, off) {
	$(selector).click(function() {
		var name = $(this).data("name");
		$("input[type='hidden'][name='" + name + "']").val($(this).data("val"));

		if(on != null && on != "") {
			$("[data-name='" + name + "']").removeClass(on);
			$(this).addClass(on);
		}
		if(off != null && off != "") {
			$("[data-name='" + name + "']").addClass(off);
			$(this).removeClass(off);
		}
	}).each(function(i, o) {
		var name = $(o).data("name");
		var el = $("input[type='hidden'][name='" + name + "']");
		if(!el.is("input")) {
			el = $('<input type="hidden" name="' + name + '" />');	
		}
		$(o).after(el);

		if($(o).data("checked") == true) {
			if(off != null && off != "") $(o).removeClass(off);
			if(on != null && on != "") $(o).addClass(on);
			el.val($(o).data("val"));
		}
	});
}
// 디자인 적용된 온오프(chk) 버튼
function txCreateCheckButton(selector, on, off) {
	$(selector).click(function() {
		var name = $(this).data("name");

		if($(this).data("checked") == true) {
			$(this).data("checked", false);
			
			if(on != null && on != "") $(this).removeClass(on);
			if(off != null && off != "") $(this).addClass(off);

			$("input[type='hidden'][name='" + name + "']").val("");
		} else {
			$(this).data("checked", true)

			if(on != null && on != "") $(this).addClass(on);
			if(off != null && off != "") $(this).removeClass(off);

			$("input[type='hidden'][name='" + name + "']").val($(this).data("val"));
		}
	}).each(function(i, o) {
		var name = $(o).data("name");
		var el = $('<input type="hidden" name="' + name + '" />');
		$(o).after(el);

		if($(o).data("checked") == true) {
			if(on != null && on != "") $(o).addClass(on);
			if(off != null && off != "") $(this).removeClass(off);
			el.val($(o).data("val"));
		}	
	});
}