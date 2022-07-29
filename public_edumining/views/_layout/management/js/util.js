
$(function() {	
	initField(document);

	$.getMultiScripts = function(arr, path) {
		var _arr = $.map(arr, function(scr) {
			return $.getScript( (path||"") + scr );
		});

		_arr.push($.Deferred(function( deferred ){
			$( deferred.resolve );
		}));

		return $.when.apply($, _arr);
	};

	$.getMultiCSS = function(arr, path) {
		$.each(arr, function(i, scr) {
			$('<link/>', {rel: 'stylesheet', type: 'text/css', href: (path||"") + scr}).appendTo('head');
		});
	};
});

//browser detect
if(!('browser' in $)) {
	$.browser = (function() {
		var s = navigator.userAgent.toLowerCase();

		var match = /(chrome)[ \/]([\w.]+)/.exec( s ) ||
			/(webkit)[ \/]([\w.]+)/.exec( s ) ||
			/(opera)(?:.*version|)[ \/]([\w.]+)/.exec( s ) ||
			/(msie) ([\w.]+)/.exec( s ) ||
			s.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( s ) ||
		[];

		var b = {'msie':false, 'opera':false, 'mozilla':false, 'webkit':false, 'chrome':false, 'safari':false};
		var browser = match[ 1 ] || "";
		var version = match[ 2 ] || "0";

		if ( browser ) {
			b[ browser] = true;
			b.version = version;
		}

		if ( b.chrome ) {
			b.webkit = true;
		} else if ( b.webkit ) {
			b.safari = true;
		}

		return b;
	}());
}

if(!('device' in $)) {
	$.device = (function() {
		var ua = navigator.userAgent.toLowerCase();
		if(/ipad/.test(ua)) {
			return 'tablet';
		} else if(/lgtelecom/.test(ua) || /android/.test(ua) || /blackberry/.test(ua) || /iphone/.test(ua) 
				|| /samsung/.test(ua) || /symbian/.test(ua) 
				|| /sony/.test(ua) || /SCH-/.test(ua) || /SPH-/.test(ua)) {
			return 'mobile';
		}
		return 'pc';
	}());
}

//datepicker 기본값 설정
if('datepicker' in $) {
	$.datepicker.regional['ko'] = {
		closeText: '닫기',
		prevText: '이전달',
		nextText: '다음달',
		currentText: '오늘',
		monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dayNames: ['일','월','화','수','목','금','토'],
		dayNamesShort: ['일','월','화','수','목','금','토'],
		dayNamesMin: ['일','월','화','수','목','금','토'],
		weekHeader: '주차',
		dateFormat: 'yy-mm-dd',
		altFormat:  'yy-mm-dd',
		minDate: '1990-01-01',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		showOtherMonths: true,
		selectOtherMonths: true,
		yearSuffix: '년',
		changeYear: true,
		changeMonth: true,
		showButtonPanel: true,
		gotoCurrent : true,
		currentText: '오늘',
		closeText: '닫기'
	};
	$.datepicker.setDefaults($.datepicker.regional['ko']);

	// custom
	$.datepicker._updateDatepicker_original = $.datepicker._updateDatepicker;
	$.datepicker._updateDatepicker = function(inst) {
		$.datepicker._updateDatepicker_original(inst);

		var afterShow = this._get( inst, 'afterShow' );
		if (afterShow) {
			afterShow.apply( inst.input, [ inst.input, inst ]);  // trigger custom callback
		}
	};
	$.datepicker._gotoToday = function(id) {
		var date,
			target = $(id),
			inst = this._getInst(target[0]);

		date = new Date();
		inst.selectedDay = date.getDate();
		inst.drawMonth = inst.selectedMonth = date.getMonth();
		inst.drawYear = inst.selectedYear = date.getFullYear();

		this._notifyChange(inst);
		this._adjustDate(target);
	};
}

$.fn.serializeObject = function() {
	var o = {};
	var a = this.serializeArray();
	$.each(a, function() {
		if (o[this.name] !== undefined) {
			if (!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push(this.value || '');
		} else {
			o[this.name] = this.value || '';
		}
	});
	return o;
};



/////////////////////////////////////////////////////////////////////
// UI관련
function initField(selector) {
	if(selector == null) selector = document;
	
	// placeholder
	if($.browser.msie == true && $.browser.version <= 8) {
		initPlaceholder(selector);
	}
	
	// 숫자필드
	$(selector).find("input[type='text'].numeric").bind("keyup blur", function() {
		var v = $(this).val().replace(/[^0-9]+/g,"");
		if(v != $(this).val()) $(this).val(v);
	}).css({"imeMode":"disabled"});

	$(selector).find("input[type='text'].decimal").bind("keyup blur", function() {
		var v = $(this).val().replace(/[^0-9.-]+/g,"");
		if(v != $(this).val()) $(this).val(v);
	}).css({"imeMode":"disabled"});
	
	$(selector).find("input[type='text'].numcomma").bind("keyup blur", function() {
		var v = $(this).val().replace(/[^0-9]+/g,"");
		
		var regexp = /(^[+-]?\d+)(\d{3})/;
		while (regexp.test(v)) {
			v = v.replace(regexp, "$1,$2");
		}
		
		if(v != $(this).val()) $(this).val(v);
	}).css({"imeMode":"disabled"});
	
	// 입력불가 필드
	$(selector).find("input[readonly='readonly'],input[readonly]").css({
		"cursor":"basic",
		"background-color":"#f5f5f5"
		//"border":"1px solid"
	}).bind("keydown", function(event) {
		if ( event.which == 8 ) {
			 event.preventDefault();
		}
	});
	
	if('datepicker' in $.ui) {
		$(selector).find("input[type='text'].datepicker").addClass("readonly").datepicker({
			beforeShow: function(input, inst) {
				input = input.target || input;

				var max = $(input).data("datepicker-max");
				var min = $(input).data("datepicker-min");
				if(max) $( input ).datepicker('option', 'maxDate', max);
				if(min) $( input ).datepicker('option', 'minDate', min);
			},
			afterShow : function(input, inst) {	
				$(inst.dpDiv).find("td.ui-state-current > a").addClass("ui-state-hover");
			}
		});
		//$(selector).find("input[type='text'].datepicker").attr("readonly","readonly").addClass("readonly").datepicker();
	}

	if('button' in $.ui) {
		$(selector).find("input[type=button].button,input[type=submit].button,a.button").button();
	}
}
// placeholder
function initPlaceholder(selector) {
	if(selector == null) selector = document;
	
	$(selector).find('[placeholder]').focus(function(){
		$(this).removeClass('placeholder');
	}).blur(function(){
		if($(this).val() == "") {
			$(this).addClass('placeholder');
		}
	}).trigger('blur');
}

// 이미지 온/오프
function changeOnOff(obj, f, t) {
	if(!$(obj).is("img") && !$(obj).is("input")) return;
	var src = $(obj).attr("src");
	var ext = "gif";
	try {
		ext = src.substring(src.lastIndexOf(".")+1);
	} catch(e) {}
	
	if(f != "") {
		var pat = new RegExp(f + "[.]" + ext + "$", "i");
		if(!pat.test(src)) return;
	}
	if(t != "") {
		var pat = new RegExp(t + "[.]" + ext + "$", "i");
		if(pat.test(src)) return;
	}

	src = src.replace(f + "." + ext, t + "." + ext);
	$(obj).attr("src", src);	
}

// 탭 온/오프
function tabsOnOff(id, image_on, image_off) {
	if(image_on == null) image_on = "_on";
	if(image_off == null) image_off = "_off";
	
	$("#" + id + " a").hover(function() {
		$("#" + id).find("a").removeClass("on");
		changeOnOff($("#" + id + " a.active").children("img"), image_on, image_off);
		
		$(this).addClass("on");
		changeOnOff($(this).children("img"), image_off, image_on);
	}, function() {
		$(this).removeClass("on");
		changeOnOff($(this).children("img"), image_on, image_off);	
		
		// 이미지 ON 복귀
		if($("#" + id).find("a.on").length <= 0) {
			changeOnOff($("#" + id + " a.active").children("img"), image_off, image_on);
		}
	});
	
	if($("#" + id + " a.active").is("a")) {
		changeOnOff($("#" + id + " a.active").children("img"), image_off, image_on);
	}
}

// 온/오프(활성/비활성) 액션 처리
function stateOnOff(obj, link, param, column) {
	var use = param[column], f = 'on', t = 'off';
	switch(use) {
		case 'Y' : use = 'N'; f = 'on'; t = 'off'; break;
		default : use = 'Y'; f = 'off'; t = 'on'; break;
	}

	param[column] = use;
	$.post(link, param, function(response, status, xhr) {
		if(!load_error(response, status, xhr)) return;

		if(response == 'success') {
			changeOnOff($(obj).find("img"), f, t);
			$(obj).data("use", use);
		} else {
			//alert("A");
		}
	});
}

//탭 온/오프/서브슬라이드 : 메뉴
function tabsSlideOnOff(id, on, off) {
	// options : selector, image_on, image_off, class_on, class_off
	// options.child : selector

	if(!('class_on' in options)) options.class_on = "on";
	if(!('class_off' in options)) options.class_off = "off";
	if(!('class_active' in options)) options.class_active = "active";
	
	if(!('child' in options) || !('selector' in options.child) || options.child.selector == '') {
		options.child = false;
	}
	
	$(options.selector).children().hover(function() {
		// 초기 Active되어있는 탭
		if(!$(this).is("." + options.class_active)) {
			var aTab = $(options.selector).children("." + options.class_active);
			
			if(options.image_on != '' || options.image_off != '') {
				changeOnOff(oTab.find("img"), options.image_on, options.image_off);
			}
			if(options.class_off != '') oTab.addClass(options.class_off);
			if(options.class_on != '') oTab.removeClass(options.class_on);
			
			if(options.child !== false) {
				if(aTab.is('has(' + options.child.selector + ')')) {
					aTab.find(options.child.selector).stop(true,true).hide();
				}
			}
		} 
		
		// 초기 Active되어있는 탭
		if(options.child !== false) {
			$(this).find(options.child.selector).slideDown();
		}
		if(options.image_on != '' || options.image_off != '') {
			changeOnOff($(this).find("img"), options.image_off, options.image_on);
		}
		if(options.class_off != '') $(this).removeClass(options.class_off);
		if(options.class_on != '') $(this).addClass(options.class_on);
	}, function() {
		//
		if(!$(this).is("." + options.class_active)) {
			var aTab = $(options.selector).children("." + options.class_active);
			
			if(options.class_off != '') oTab.removeClass(options.class_off);
			if(options.class_on != '') oTab.addClass(options.class_on);

			if(options.child !== false) {
				aTab.find(options.child.selector).slideDown();
			}
			if(options.image_on != '' || options.image_off != '') {
				changeOnOff(oTab.find("img"), options.image_off, options.image_on);
			}
			
			$(this).find(options.child.selector).stop(true,true).hide();
			changeOnOff($(this).find("img"), options.image_on, options.image_off);
		}
	});
	
	// 초기화
	var aTab = $(options.selector).children("." + options.class_active);
	if(options.child !== false) {
		aTab.find(options.child.selector).slideDown();
	}
	changeOnOff(aTab.find("img"), options.image_off, options.image_on);
}

// 클릭시 탭컨텐츠 변경
function tabsClick(id, lay_selector) {
	var tab_selector = "#" + id + " a";
	if(lay_selector == null) {
		lay_selector = "#" + id + "_cont .section";
	}
	$(tab_selector).click(function() {
		var idx = $(tab_selector).index(this);
		$(tab_selector).removeClass("active").eq(idx).addClass("active");
		$(lay_selector).removeClass("active").eq(idx).addClass("active");
		return false;
	});
}

// 클릭시 탭컨텐츠 변경
function tabsClickShow(tab_selector, lay_selector, on, off) {
	var a_selector = tab_selector;
	var a_obj = $($.trim(tab_selector) + "." + on);

	if(!$(tab_selector).eq(0).is("a")) {
		a_selector = tab_selector + " a";
		a_obj = $($.trim(tab_selector) + "." + on + " a");
	}

	$(a_selector).click(function() {
		var idx = $(a_selector).index(this);
		var o = $(tab_selector).eq(idx);

		if(on != null && on != "") {
			$(tab_selector).removeClass(on);
			o.addClass(on);
		}

		if(off != null && off != "") {			
			$(tab_selector).addClass(off);
			o.removeClass(off);
		}

		$(lay_selector).hide().eq(idx).show();
		return false;
	});
	
	// 초기 탭 온오프
	if(a_obj.is("a")) {
		var idx = $(a_selector).index(a_obj);
		$(a_selector).eq(idx).click();
	} else {
		$(a_selector).eq(0).click();
	}
}

// 드롭다운 메뉴 생성(CSS필요)
function dropdown(objid) {
	var selector = "#" + objid;
	if(!$(selector).is("ul")) return;
	var _w_o = $(selector).attr("js-width");
	
	var o_this = $('<div class="dropdown" tabindex="0"></div>');
	o_this.insertBefore(selector);
	o_this.append($(selector).attr("id", ""));
	o_this.prepend('<input type="hidden" name="' + objid + '" id="' + objid + '" value="" /><span>&nbsp;</span>');
	
	var $o_span = $(o_this).find("span");

	var $o_span_btn = $("<span class=\"button\"></span>");
	var $o_span_title = $("<span class=\"title\">&nbsp;</span>");
	$o_span.empty().append($o_span_title).append($o_span_btn);

	var _w = $(o_this).find("ul").outerWidth();
	if(_w < 40) _w = 40;

	if(!isNaN(_w_o)) {
		_w_o = parseInt(_w_o, 10);
	} else {
		_w_o = _w;
	}
	
	var _w_btn = $o_span_btn.outerWidth();
	var _w_pad = parseInt($o_span.css("paddingLeft"), 10);
	$o_span_title.css({width: (_w_o - _w_pad) + "px"});

	_w_pad = parseInt($o_span.css("paddingRight"), 10) - _w_btn;
	_w = _w + _w_pad;

	$o_span.click(function() {
		var ul = $(this).next("ul");
		if( ul.is(":visible") ) ul.slideUp("fast");
		else ul.slideDown("fast");
		$(this).addClass("active");
	}).mouseover(function(){
		$(this).addClass("focus");
	}).mouseout(function() {
		$(this).removeClass("focus");
	});

	if(_w > _w_o + _w_btn) {
		$(o_this).children("ul").css({ width: _w + "px" }).hide();
	} else {
		$(o_this).children("ul").css({ width: _w_o + _w_btn + "px" }).hide();
	}
	
	$(o_this).find("li").unbind("click").click(function() {
		var v = $(this).attr("data");
		$(o_this).children("input").val(v);
		$(o_this).find("span > span.title").text($(this).text());
		$(o_this).find("ul").find("li").removeClass("focus").end().slideUp("fast");
		$o_span.removeClass("active");
	}).bind("mouseenter", function() { 
		$(this).addClass("focus");
	}).bind("mouseleave", function() { 
		$(this).removeClass("focus");
	});

	$(o_this).blur(function() {
		if( $(o_this).find("ul li.focus").length < 1 ) {
			$(o_this).find("ul").slideUp("fast");
			$o_span.removeClass("active");
		}
	}).css({ width: _w_o + _w_btn + "px" });
	$o_span_btn.focus(function() { $(o_this).focus(); });
	$o_span_title.focus(function() { $(o_this).focus(); });
	
	$(o_this).find(".selected").click();
}

// 슬라이드 배너 생성 (AJAX)
function initBanner(objId, url, displayLen, listControl) {	
	// 배너
	if($.trim(url) != "") {
		$.getJSON(url, function(json) {
			var bList = json.jsonResult.data;
			if(bList != null && bList.length > 0) {
				$("#" + objId + " > ul").empty();
	
	    		$.each(bList, function(i, v) {
	    			$("#" + objId + " > ul").append("<li><a href=\"" + v.link + "\" title=\"새창\" target=\"" + (v.link == "#" ? "_self" : "_blank") + "\"><img src=\"" 
	    					+ v.imgsrc + "\" alt=\"" 
	    					+ v.alt + "\"/></a></li>");
	    		});
	    		
	    		makeSliderBanner(objId, displayLen, listControl);
			}
		});
	} else {
		makeSliderBanner(objId, displayLen, listControl);
	}
}

// 슬라이드 배너 생성 (bx-slider)
function makeSliderBanner(objId, displayLen, listControl) {
	// 기본배너 설정
	var len = $("#" + objId + " > ul > li").length;
	if(len < displayLen) {
		for(var i = len; i < displayLen; i++) {
			$("#" + objId + " > ul").append("<li><img src=\"/u_img/no_image_banner.gif\" alt=\"배너없음\"/></a></li>");
		}
	}
	
	$("#" + objId + " ul").bxSlider({
		pager: false,
		minSlides: displayLen,
		maxSlides: displayLen,
		moveSlides: 1,
		slideWidth: 323,
		slideMargin: 8,
		auto: true,
		autoControls: true,
		pause: 5000
	});
	
	makeSliderControl(objId, listControl);
	
}

// 슬라이드 컨트롤 & 목록 생성 (bx-slider)
function makeSliderControl(objId, listControl) {
	if(listControl == null || listControl === false) return;
	
	var $ctrlList = $('<div class="bx-controls-auto-item"><a class="bx-list" href="' + listControl + '" target="' + target + '">List</a></div>'); 
	$("#" + objId + " .bx-controls").addClass("bx-has-controls-exp").children(".bx-controls-auto").append($ctrlList);
		
	// 목록
	//if(listControl === true || listControl == "list") {		
		if(!$("#" + objId).is(":has(div.bx-listbox)")) {
			var tit = $("#" + objId).attr("title");
			var $closeBtn =  $('<a class="bx-close" href="#">Close</a>').click(function() {
				$("#" + objId).find("a.bx-list").removeClass("active");
				$("#" + objId + " .bx-listbox").hide();
				
				return false;
			});
			var $ul = $("#" + objId).prepend('<div class="bx-listwrap"><div class="bx-listbox"></div></div>').find(".bx-listbox").append('<p class="tit">' + (typeof(tit) == "undefined" ? "" : tit) + ' 목록</p>').append($closeBtn).append("<ul></ul>").children("ul");

			$("#" + objId + " ul > li:not(.bx-clone)").each(function(i, obj) {
				var link = $(obj).find("a").attr("href");
				var text = $(obj).find("a").text();
				if(!text) text = $(obj).find("a").find("img").attr("alt");
				$ul.append('<li><em>' + (i + 1) + '</em><a href="' + link + '" target="_blank">' + text + '</a></li>');
			});	
			
			var wi = $("#" + objId).find(".bx-listwrap").width();
			var wi_b = parseInt($("#" + objId).css("borderWidth"), 10);
			
			wi += wi_b * 2;
			wi -= parseInt($("#" + objId).find(".bx-listbox").css("borderWidth"), 10) * 2;
			
			if(wi > 500) {
				wi = 500;
			}
			$("#" + objId).find(".bx-listbox").css({width:wi, right:"-" + wi_b + "px"});
		}
		
		// 마우스 액션
		var __slider_timer = null;
		var __slider_hide_time = 1000;
		var __slider_hide = function() {
			$ctrlList.children("a").removeClass("active");
			$("#" + objId + " .bx-listbox").hide();
			
			__slider_timer = null;
		};
		var __slider_show = function() {
			if(__slider_timer != null) {
				clearTimeout(__slider_timer);
				__slider_timer = null;
			}
			
			if(!$(this).is("active")) {
				$ctrlList.children("a").addClass("active");
				$("#" + objId + " .bx-listbox").show();
			}
		};
		
		$ctrlList.children("a").bind("mouseover", function() {
			__slider_show();
			return false;
		}).bind("mouseout", function() {
			__slider_timer = setTimeout(__slider_hide, __slider_hide_time);
			return false;
		});
		
		$("#" + objId + " .bx-listbox").bind("mouseover", function() {
			__slider_show();
			return false;
		}).bind("mouseout", function() {
			__slider_timer = setTimeout(__slider_hide, __slider_hide_time);
			return false;
		});
	//}
	
	// 클릭시 액션
	if(listControl === true || listControl == "list") {
		$ctrlList.find(".bx-list").click(function() {
			return false;
		});
	} else if(listControl != null) {
		var target = "_self";
		if(/^[ ]*http/i.test(listControl)) {
			target = "_blank";
		} 
		
		$ctrlList.find(".bx-list").attr("href", listControl).attr("target", target);
	} else {
		$ctrlList.find(".bx-list").click(function() {
			return false;
		});
	}
}

// 슬라이드 ticker 생성
function makeSliderTicker(selector, oneSlide, tic) {
	var a_slider;
	var a_item;
	var a_timer;
	
	if(oneSlide == true || $(selector + " ul li").length > 1) {
		a_slider = $(selector + " ul").bxSlider({
			onSlideAfter: function($slideElement, oldIndex, newIndex){	
				if(tic == true) {
					slider_ticker(newIndex);
				}
			},
			pager: false,
			mode: 'vertical',
			auto: false,
			controls: false,
			pause: 5000,
			responsive : false
		});	
		
		if(tic == true) {
			$(selector + " li").css({"textOverflow": "clip"}).find("div,span").css({"textOverflow": "clip"});
			$(selector + " ul").hover(function() {
				slider_ticker_stop();
			}, function() {
				slider_ticker_start();
			}).focus(function() {
				slider_ticker_stop();
			}).blur(function() {
				slider_ticker_start();
			});
			slider_ticker_start();
		} else {
			a_slider.startAuto();
		}
	} else if(tic == true ) {
		$(selector + " li").css({"textOverflow": "clip"}).find("div,span").css({"textOverflow": "clip"});
		makeAutoTicker(selector);
	}
	
	function slider_ticker_start() {
		if( ! slider_ticker(a_slider.getCurrentSlide()) ) {
			a_slider.startAuto();
		}
	}
	function slider_ticker_stop() {
		a_slider.stopAuto();
		a_item.stop(true, false);
		clearTimeout(a_timer);
	}
	
	function slider_ticker(index) {
		a_item = $(selector + " ul li:not(.bx-clone):eq(" + index + ")").find("span");
		var l = a_item.parent().width() - a_item.width();
		var m = parseInt(a_item.css("marginLeft"), 10);

		if(l < 0){
			var time = Math.floor((l - m) * -20);
			a_slider.stopAuto();
			a_timer = setTimeout(function(){
				a_item.animate({marginLeft:l}, time, function() {
					timer = setTimeout(function(){
						a_slider.startAuto();
						a_slider.goToNextSlide();
						a_item.css({marginLeft:0});
					},1500);
				});
			}, 1500);
			
			return true;
		}
		return false;
	}
	
	return a_slider;
}

// 오토 ticker 생성
function makeAutoTicker(selector) {
	var b_obj = $(selector + " li:eq(0)");
	var b_item = $(selector + " li:eq(0) span");
	
	if(b_item.width() > b_obj.width()) {
		var l = b_item.parent().width() - b_item.width();
		var time = Math.floor(l * -20);
		setTimeout(function(){
			b_item.animate({marginLeft:l}, time, function() {
				setTimeout(function(){
					b_item.css({marginLeft:0});
					makeAutoTicker(selector);
				},3000);
			});
		},3000);
	}
}



/////////////////////////////////////////////////////////////////////
// 폼체커

//공백 금지
function checkSpace( value )
{
	var pattern = /[\s]/g;
	if ( pattern.test( value ) == true )
	{
		alert( "공백은 입력할 수 없습니다!" );
		return false;
	}
	return true;
}

//알파벳 숫자 6-12 자리 체크
function checkID( obj )
{    
	var strId = obj.value;
	var minLen = 8;
	var maxLen = 16;
	var regExpr1 = /^[a-z]/;
    var regExpr2 = /^[a-z0-9]+$/;
	
	if ( !regExpr1.test(strId) ) {
		alert("아이디는 반드시 영문자(소문자)로 시작해야 합니다.");
		obj.focus();
		return false;
	} 

	if ( !regExpr2.test( strId ) || !( strId.length >= minLen && strId.length <= maxLen ) ) {
        alert("아이디는 영문자(소문자)로 시작하며, 영문자와 숫자로 이루어진\n " + minLen + "자리에서 " + maxLen +"자리 문자 이어야 합니다");
		obj.focus();
		return false;
    }

	return true;
}

//4-14자리 체크
function checkPass( obj )
{    
	var minLen = 8;
	var maxLen = 16;
	var upw = obj.value;
	if(!/^[a-zA-Z0-9]{"+minLen+","+maxLen+"}$/.test(upw) )
    { 
        alert('비밀번호는 숫자와 영문자 조합으로 " + minLen + "~" + maxLen +"자리를 사용해야 합니다.'); 
		obj.focus();
        return false;
    }

    if(/(\w)\1\1\1/.test(upw))
    {
        alert('비밀번호에 같은 문자를 4번 이상 사용하실 수 없습니다.'); 
		obj.focus();
        return false;
    }
	/*
    if(upw.search(uid)>-1)
    {
        alert('ID가 포함된 비밀번호는 사용하실 수 없습니다.'); 
        return false;
    }*/

    return true;
}

//이메일 체크
function checkEmailAll( email ) {
	if ( trim( email.value ) == "" ) {
		alert( "이메일 주소를 입력해 주십시요" );
		return false;
	}

	var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	if ( filter.test( email.value ) ) { 	
		return true; 
	} else { 
		alert( "잘못된 이메일 주소입니다." );
		//email.value = "";
		return false; 
	} 
}

//이메일 체크
//email1 = @ 앞부분, email2 = @ 뒤부분, email3 = @ 뒤부분 기타
function checkEmail( email1, email2 ) {
	if ( trim( email1.value ) == "" || trim( email2.value ) == "" ) {
		alert( "이메일 주소를 입력해 주십시요" );
		return false;
	}
	
	str = email1.value + "@" + email2.value;		

	var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	if ( filter.test( str ) ) { 
		//document.mainform.email.value = str;
		return true; 
	} else { 
		alert( "잘못된 이메일 주소입니다." );
		return false; 
	} 
}

// 이메일 String 파라미터 체크
function checkEmailStr( email ) {
	if ( trim( email ) == "" ) {
		alert( "이메일 주소를 입력해 주십시요" );
		return false;
	}

	var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	if ( filter.test( email ) )  	
		return true; 
	else 
	{ 
		alert( "잘못된 이메일 주소입니다." );
		return false; 
	} 
}

//숫자 체크
function checkNumber( obj ) {

	var regExpr2 = /^[0-9]+$/;	
	if ( obj.value.length > 0 && !regExpr2.test( obj.value ) ) {
		alert( "숫자만 입력가능합니다" );
		obj.value = '';
		obj.focus();
		return false;
    }
	
	return true;
}

//숫자 체크. default 1로 설정
function checkNumber1( obj ) {

	var regExpr2 = /^[0-9]+$/;	
	if ( obj.value.length > 0 && !regExpr2.test( obj.value ) ) {
		alert( "숫자만 입력가능합니다" );
		obj.value = 1;
		obj.focus();
		return false;
    }
	
	return true;
}

function checkMaxNum( obj, max ) {
	if ( obj.value > max ) {
		alert( max + "값 이상을 입력할 수 없습니다" );
		return obj.value = "";
	}
}

//숫자 체크
function checkSigned( obj ) 
{
	if ( obj.readOnly == true ) return;
	var regExpr2 = /^[0-9\-]+$/;	
	if ( obj.value.length > 0 && !regExpr2.test( obj.value ) ) {
		alert( "-(마이너스)와 숫자만 입력가능합니다" );
		obj.value = '';
		obj.focus();
		return false;
    }
	
	return true;
}



/////////////////////////////////////////////////////////////////////
// 캘린더

/**
jquery 캘린더 오브젝트 만들기
param : obj<String> - 생성할 캘린더 id. input box의 id name
 */
function makeCalendar( obj )
{
	if ( obj == "" ) return;
	
    $("#"+obj).datepicker({
        altField:   "#"+obj
    });	
}

/**
jquery 캘린더 클릭시 input box 의 datepicker실행
param : obj<Object> - 클릭된 Object
 */
function clickCalendar( obj )
{
	if ( obj == null ) return;
	
	$(obj).prev("input.datepicker").focus();
}

/**
jquery 캘린더 오브젝트 만들기
param : selector<String> - 생성할 캘린더 selector.
param : firstDay<Integer> - day number (0~6)
param : add<Integer> - week 선택가능한 주
*/
function makeCalendarWeek( selector, firstDay, addWeek )
{
	if(firstDay < 0 || firstDay > 6) firstDay = 0;
	if(addWeek == null) addWeek = 0;

	$( selector ).each(function(idx, obj) {
		var sel_date = "";
		var term = $(obj).val();
		if(term != null && $.trim(term) != "") {
			var t = term.split("~");
			if(t.length >= 2) {
				sel_date = $.trim(t[1]);
			}
		}

		var target = $('<input type="text" value="' + sel_date + '"/>').width($(obj).width());
		$(obj).before(target).bind("click focus", function(e) {
			$(target).focus();
		}).prop("readonly", true);

		target.css({
			"padding":$(obj).css("padding"), 
			"font-size":$(obj).css("font-size"), 
			"border" : $(obj).css("border"), 
			"line-height" : $(obj).css("line-height"), 
			"margin" : $(obj).css("margin")
		});
		target.css("margin-right", ((target.outerWidth() + parseInt($(obj).css("margin-left"))) * -1));

		$(target).datepicker({
			dateFormat: 'yy-mm-dd',
			altFormat:  'yy-mm-dd',
			showButtonPanel: true,
			closeText: '닫기',
			gotoCurrent : true,
			currentText : '이번주',
			beforeShowDay: function(date) {
				var format = $(target).datepicker( "option", "dateFormat" );
				var d = $.datepicker.formatDate(format, date);

				var term = $(obj).val();
				if(term != null && $.trim(term) != "") {
					var t = term.split("~");

					if(t.length >= 2 && $.trim(t[0]) <= d && d <= $.trim(t[1])) {
						return [true, "ui-state-week-current", ""];
					}
				}

				return [true, "", ""];
			},
			beforeShow: function(input, inst) {
				input = input.target || input;

				var max = $(input).data("datepicker-max");
				var min = $(input).data("datepicker-min");
				if(max) $( input ).datepicker('option', 'maxDate', max);
				if(min) $( input ).datepicker('option', 'minDate', min);

				if(addWeek !== '' || addWeek !== false || addWeek !== null) {
					var d = new Date();
					var dn = d.getDay();
					var t = d.getTime();
					dn = dn - firstDay < 0 ? dn - firstDay + 7 : dn - firstDay;
					t = t - (86400000 * dn) + 518400000;
					t = t + addWeek * 7 * 86400000; 
					d.setTime(t);

					$( input ).datepicker('option', 'maxDate', d);
				}
			},
			afterShow: function(input, inst) {
				input = input.target || input;

				$(inst.dpDiv).find("td > a").hover(function() {
					var format = $(target).datepicker( "option", "dateFormat" );
					$(inst.dpDiv).find("td > a").removeClass("ui-state-week-active");

					var y = $(this).parent().data("year");
					var m = $(this).parent().data("month") + 1;
					var d = $(this).text();
					var date = $.datepicker.parseDate(format, y + "-" + m + "-" + d);
					var dn = date.getDay();

					var a_list = $(inst.dpDiv).find("td > a");
					var idx = a_list.index(this);
	
					var s_idx = dn - firstDay < 0 ? idx - (dn - firstDay + 7) : idx - (dn - firstDay);
					var e_idx = s_idx+7;

					if(s_idx < 0) s_idx = 0;
					if(e_idx > a_list.length) e_idx = a_list.length;

					$(a_list).slice(s_idx, e_idx).addClass("ui-state-week-hover");
				}, function() {
					$(inst.dpDiv).find("td > a").removeClass("ui-state-week-hover");
					$(inst.dpDiv).find("td.ui-state-week-current > a").addClass("ui-state-week-active");
				});
				$(".ui-state-active").removeClass("ui-state-active");
				$(".ui-state-hover").removeClass("ui-state-hover");
				$(inst.dpDiv).find("td.ui-state-week-current > a").addClass("ui-state-week-active ui-state-week-hover");
			},
			onSelect: function(dateText, inst) {
				var format = $(target).datepicker( "option", "dateFormat" );

				var d = $.datepicker.parseDate(format, dateText);
				var dn = d.getDay();
				dn = dn - firstDay < 0 ? dn - firstDay + 7 : dn - firstDay;

				d.setTime(d.getTime() - 86400000 * dn);
				var sdate = $.datepicker.formatDate(format, d);

				d.setTime(d.getTime() + 518400000);
				var edate = $.datepicker.formatDate(format, d);

				$(obj).val(sdate + "~" + edate);
			}
		});
	});	
}
function makeCalendarWeek2( selector1, selector2, firstDay, addWeek, selector3)
{
	if(firstDay < 0 || firstDay > 6) firstDay = 0;
	if(addWeek == null) addWeek = 0;

	var sel_date = "";
	var term = $(selector1).val();
	if(term != null && $.trim(term) != "") {
		var t = term.split("~");
		if(t.length >= 2) {
			sel_date = $.trim(t[1]);
		}
	}

	$(selector1 + ", " + selector2).datepicker({
		dateFormat: 'yy-mm-dd',
		altFormat:  'yy-mm-dd',
		showButtonPanel: true,
		closeText: '닫기',
		gotoCurrent : true,
		currentText : '이번주',
		beforeShowDay: function(date) {
			var format = $(selector1).datepicker( "option", "dateFormat" );
			var d = $.datepicker.formatDate(format, date);

			var term_s = $(selector1).val();
			var term_e = $(selector2).val();
			
			if(term_s != "" && term_e != "" && $.trim(term_s) <= d && d <= $.trim(term_e)) {
				return [true, "ui-state-week-current", ""];
			}

			return [true, "", ""];
		},
		beforeShow: function(input, inst) {
			input = input.target || input;

			var max = $(input).data("datepicker-max");
			var min = $(input).data("datepicker-min");
			if(max) $( input ).datepicker('option', 'maxDate', max);
			if(min) $( input ).datepicker('option', 'minDate', min);

			if(addWeek !== '' || addWeek !== false || addWeek !== null) {
				var d = new Date();
				var dn = d.getDay();
				var t = d.getTime();
				dn = dn - firstDay < 0 ? dn - firstDay + 7 : dn - firstDay;
				t = t - (86400000 * dn) + 518400000;
				t = t + addWeek * 7 * 86400000; 
				d.setTime(t);

				$( input ).datepicker('option', 'maxDate', d);
			}
		},
		afterShow: function(input, inst) {
			input = input.target || input;

			$(inst.dpDiv).find("td > a").hover(function() {
				var format = $(selector1).datepicker( "option", "dateFormat" );
				$(inst.dpDiv).find("td > a").removeClass("ui-state-week-active");

				var y = $(this).parent().data("year");
				var m = $(this).parent().data("month") + 1;
				var d = $(this).text();
				var date = $.datepicker.parseDate(format, y + "-" + m + "-" + d);
				var dn = date.getDay();

				var a_list = $(inst.dpDiv).find("td > a");
				var idx = a_list.index(this);

				var s_idx = dn - firstDay < 0 ? idx - (dn - firstDay + 7) : idx - (dn - firstDay);
				var e_idx = s_idx+7;

				if(s_idx < 0) s_idx = 0;
				if(e_idx > a_list.length) e_idx = a_list.length;

				$(a_list).slice(s_idx, e_idx).addClass("ui-state-week-hover");
			}, function() {
				$(inst.dpDiv).find("td > a").removeClass("ui-state-week-hover");
				$(inst.dpDiv).find("td.ui-state-week-current > a").addClass("ui-state-week-active");
			});
			$(".ui-state-active").removeClass("ui-state-active");
			$(".ui-state-hover").removeClass("ui-state-hover");
			$(inst.dpDiv).find("td.ui-state-week-current > a").addClass("ui-state-week-active ui-state-week-hover");
		},
		onSelect: function(dateText, inst) {
			var format = $(selector1).datepicker( "option", "dateFormat" );

			var d = $.datepicker.parseDate(format, dateText);
			var dn = d.getDay();
			dn = dn - firstDay < 0 ? dn - firstDay + 7 : dn - firstDay;

			d.setTime(d.getTime() - 86400000 * dn);
			var sdate = $.datepicker.formatDate(format, d);

			d.setTime(d.getTime() + 518400000);
			var edate = $.datepicker.formatDate(format, d);

			$(selector1).datepicker( "setDate", sdate );
			$(selector2).datepicker( "setDate", edate );

			if(selector3 != null) {
				$(selector3).val(sdate + "~" + edate);
			}

			$(this).blur();
		}
	});
}

/**
jquery 캘린더 오브젝트 만들기
param : obj<String> - 생성할 캘린더 id. input box의 id name
*/
function makeCalendarTerm( obj, toStartObj, toEndObj )
{
	if ( obj == "" ) return;
	var nowDate = new Date();
	var nowYear = nowDate.getFullYear();

	if ( toStartObj != null ) toStartObj = $.trim(toStartObj);
	if ( toEndObj != null ) toEndObj = $.trim(toEndObj);
	
	$("#"+obj).datepicker({
	    altField:   "#"+obj, // alt
	    onSelect: function(dateText, inst) { 
	        //var date = $(this).datepicker('getDate');	    	
	    	var rs = makeTermFromDateToObject(dateText, 'WT', 0);
	    	var startDate = rs[0];
	    	var endDate = rs[1];
	    	
	        var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
	        
	        var strStartDate = $.datepicker.formatDate( "yy-mm-dd", startDate, inst.settings ); 
	        var strEndDate = $.datepicker.formatDate( "yy-mm-dd", endDate, inst.settings ); 
	        
	        $('#'+obj).val( strStartDate + " ~ " + strEndDate );
	        if(toStartObj != null && toStartObj != "") $('#' + toStartObj).val(strStartDate);
	        if(toEndObj != null && toEndObj != "") $('#' + toEndObj).val(strEndDate);
	    }
	});	
}

// 날짜 기간 설정
function makeTermFromDate(baseDate, type, cnt) {
	var rs = makeTermFromDateToObject(baseDate, type, cnt);
	var startDate = rs[0];
	var endDate = rs[1];
	
	startDate = startDate.getFullYear() + "-" + padZero(startDate.getMonth()+1) + "-" + padZero(startDate.getDate());
	endDate = endDate.getFullYear() + "-" + padZero(endDate.getMonth()+1) + "-" + padZero(endDate.getDate());

	// [s, e]
	return [ startDate, endDate ];
}

//날짜 기간 설정 (Object)
function makeTermFromDateToObject(baseDate, type, cnt) {
var y, m, d, baseDateStr;
	
	if ( baseDate == "" ) {
		baseDate = new Date();
	} else if (typeof(baseDate) == 'object'){ 	

	} else {
		baseDate = changeStringToDate(baseDate);
	}
	
	y = baseDate.getFullYear();
	m = baseDate.getMonth();
	d = baseDate.getDate();
	baseDateStr = y + "-" + padZero(m+1) + "-" + padZero(d);
	
	// cnt만큼 주단위로 움직여 월요일~일요일 날짜 반환
	if(type == "WT") {
    	var date = new Date(y, m, d + (7 * cnt));
    	if ( date.getDay() == 0 ) {
    		startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 1 - 7);
    		endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 7 - 7);
    	} else {
    		startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 1);
    		endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 7);	    		
    	}
    	
    	return [startDate, endDate];
	}
	
	if(cnt == 0) {
		return [baseDate, baseDate];
	}
	
	var date = new Date();
	var startDate = (cnt > 0 ? baseDate : null);
	var endDate = (cnt < 0 ? baseDate : null);
	
	if(type == "D") {
		date = new Date(y, m, d - cnt);
	} else if(type == "M") {
		date = new Date(y, m - cnt, d);
	} else if(type == "W") {
		date =  new Date(y, m, d - (cnt * 7));
	} else if(type == "Y") {
		date =  new Date(y - cnt, m, d);
	} 
	
	if(cnt > 0) endDate = date;
	else startDate = date;
	
	// [s, e]
	return [ startDate, endDate ];
}

// 기간 생성해서 input or selectbox 에 표시
function makeTermSetField( type, cType, cnt, startObjId, endObjId ) {
	cnt = cnt * -1;
	if(type == "DAY") {
		var date = $("#" + endObjId).val();
		var rs = makeTermFromDate(date, cType, cnt);

		$("#" + startObjId).val(rs[0]);
	}
	else if(type == "MONTH") {
		if(typeof(startObjId) != "object" || startObjId.length < 2 
				|| typeof(endObjId) != "object" || endObjId.length < 2) {
			return;
		}
		
		var y = parseInt($("#" + endObjId[0]).val(), 10);
		var m = parseInt($("#" + endObjId[1]).val(), 10) - 1;
		
		var date = new Date(y, m, 1);
		var rs = makeTermFromDateObject(date, cType, cnt);
		date = rs[0];
		
		// [20131218 cocktial]jQuery Option 변경 방식 변경
		$("#" + startObjId[0] + " option:contains('" + date.getFullYear() + "')").prop("selected", true);
		$("#" + startObjId[1] + " option:contains('" + padZero(date.getMonth()+1) + "')").prop("selected", true);		
	}
	else if(type == "YEAR") {
		var y =  parseInt($("#" + endObjId).val(), 10);
		$("#" + startObjId + " option:contains('" + parseInt(y-cnt) + "')").prop("selected", true);
		$("#" + startObjId).val(parseInt(y-cnt));
	}
}

//날짜 yyyy-mm-dd / yyyy-mm-dd HH:ii:ss 형태를 Date로 변환
function changeStringToDate(dateText) {
	// new Date( dateText ) IE7에서 안됨
	
	var y = parseInt(dateText.substring(0,4), 10);
	var m = parseInt(dateText.substring(5,7), 10) - 1; // 0 ~ 11
	var d = parseInt(dateText.substring(8,10), 10);
	
	if(dateText.length > 10) {
		var h = parseInt(dateText.substring(11,13), 10);
		var i = parseInt(dateText.substring(14,16), 10);
		var s = 0;
		if(dateText.length > 16) {
			s = parseInt(dateText.substring(17,19), 10);
		}
		return new Date(y, m, d, h, i, s);
	} else {
		return new Date(y, m, d);
	}
}
// 날짜 치환 (dateFormat 을 사용하세요)
function changeDateToString(date, is_time) {
	var dateStr = date.getFullYear() + "-" + padZero(date.getMonth()+1) + "-" + padZero(date.getDate());
	var timeStr = padZero(date.getHours()) + ":" + padZero(date.getMinutes()) + ":" + padZero(date.getSeconds());

	if(is_time == true) {
		return dateStr + " " + timeStr;
	} else {
		return dateStr;
	}
}

//날짜 yyyy-mm-dd 형태를 yyyymmdd로 변환
function changeDateInt(pdate) {
	var date = pdate.split("-");
	
	intDate = date[0]+date[1]+date[2];
	
	return parseInt(intDate);
}



/////////////////////////////////////////////////////////////////////
// 쿠키

function getCookieVal(offset) {
    var endstr = document.cookie.indexOf (";", offset);
    if (endstr == -1) {
        endstr = document.cookie.length;
    }
    value = document.cookie.substring(offset, endstr);
    if(value == 'null' || value == '' || value == null) return null;
    else return unescape(value);
}

function getCookie(name) {
    var arg = name + "=";
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;

    while (i < clen) {
        var j = i + alen;
        if (document.cookie.substring(i, j) == arg) {
            return getCookieVal (j);
        }
        i = document.cookie.indexOf(" ", i) + 1;
        if (i == 0) break;
    }
    return null;
}

function setCookie (name, value) {
    var argv = setCookie.arguments;
    var argc = setCookie.arguments.length;
    var expires = (argc > 2) ? argv[2] : null;
    var path = (argc > 3) ? argv[3] : "/";
    var domain = (argc > 4) ? argv[4] : null;
    var secure = (argc > 5) ? argv[5] : false;

	if(typeof(expires) != "object") {
		var todayDate = new Date();
		todayDate.setDate( todayDate.getDate() + expires );
		expires = todayDate;
	}

    document.cookie = name + "=" + escape (value) +
        ((expires == null) ? "" : ("; expires=" + expires.toGMTString())) +
        ((path == null) ? "" : ("; path=" + path)) +
        ((domain == null) ? "" : ("; domain=" + domain)) +
        ((secure == true) ? "; secure" : "");
}

function deleteCookie(name) {
    var exp = new Date();
    exp.setTime (exp.getTime() - 1);
    var cval = getCookie (name);
    if (cval != null) {
		setCookie(name, '', exp, '/');
    }
}



/////////////////////////////////////////////////////////////////////
// google visualization
// 1,2 구분해서 사용할 필요 없음

// 차트 데이터 타입변경
function googleChartDataFormat(chartCol, dataTable) {
	for ( var i = 0; i < chartCol.length; i++) {
		if(chartCol[i].format != null) {
			if(chartCol[i].type == 'date' || chartCol[i].type == 'datetime') {
				var f = new google.visualization.DateFormat({
					pattern: chartCol[i].format
				});
				f.format(dataTable, i);
			} else {
				var f = new google.visualization.NumberFormat({
					pattern: chartCol[i].format,
					fractionDigits: 2
				});
				f.format(dataTable, i);
			}
		}
	}
	return dataTable;
}

// 차트 데이터 타입 변경
function googleChartDataPattern(i, pattern, chartRow) {
	for(var j = 0; j < chartRow.length; j++){
		var data = chartRow[j][i];
		chartRow[j][i] = __googleChartDataPattern(pattern, data);
	}
	return chartRow;
}

// 차트 데이터 타입 변경
function __googleChartDataPattern(pattern, data) {
	var dataFormat = '';
	if(typeof(pattern) == 'object') {
		$.each(pattern, function(key, val) {
			switch(key) {
				case 'format': dataFormat = val; return;
				case 'dataPrev': data = val + data; return;
				case 'dataNext': data = data + val; return;
			}
		});
	} else {
		dataFormat = pattern;
	}
	
	// number
	if(dataFormat == 'nn') {
		return parseInt(data, 10);
	}
	
	var iYearFull = dataFormat.indexOf('yyyy');
	var iYear = dataFormat.indexOf('yy');
	var iMon = dataFormat.indexOf('MM');
	var iDay = dataFormat.indexOf('dd');
	var iHour = dataFormat.indexOf('HH');
	var iMin = dataFormat.indexOf('mm');
	
	// date, datetime
	if(iYearFull >= 0 || iYear >= 0 || iMon >= 0 || iDay >= 0) {
		var y = 1900;
		var m = 1;
		var d = 1;
		var h = 0;
		var i = 0;
		
		// year
		if(iYearFull >= 0) y = parseInt(data.substring(iYearFull, iYearFull + 4), 10);
		else if(iYear >= 0) {
			y = parseInt(data.substring(iYear, iYear + 2), 10);
			if(y < 50) y += 2000;
		}
		
		// month(0~11)
		if(iMon >= 0)	m = parseInt(data.substring(iMon, iMon + 2), 10) -1;
		// day
		if(iDay >= 0)	d = parseInt(data.substring(iDay, iDay + 2), 10);
		// hour
		if(iHour >= 0)	h = parseInt(data.substring(iHour, iHour + 2), 10);
		// minute
		if(iMin >= 0)	i = parseInt(data.substring(iMin, iMin + 2), 10);
		
		return new Date(y, m, d, h, i, 0);
	}
	// timeofday
	else if(iHour >= 0 || iMin >= 0) {
		var h = 0;
		var i = 0;
		
		// hour
		if(iHour >= 0)	h = parseInt(data.substring(iHour, iHour + 2), 10);
		// minute
		if(iMin >= 0)	i = parseInt(data.substring(iMin, iMin + 2), 10);
		
		return [h, m, 0, 0];
	} 

	return data;
}
function getGoogleChartDataPattern(termType) {
	if(termType == 'DAY') return 'yy.MM.dd';
	if(termType == 'MON') return 'yy년 MM월';
	if(termType == 'YEAR') return 'yyyy년';
}
function getGoogleChartDataPatternSimple(termType) {
	if(termType == 'DAY') return 'HH시';
	if(termType == 'MON') return 'yy.MM.dd';
	if(termType == 'YEAR') return 'yy년 MM월';
}

// 차트1
function googleChartDraw1(chartId, chartCol, chartRow, options) {
	if(chartRow.length < 1) {
		$("#" + chartId).html("데이터가 없습니다.").addClass("nodata");
		return;
	} else {
		$("#" + chartId).removeClass("nodata");
	}
	
	if(!('visualization' in google)) {
		google.load('visualization', '1.1', {'packages' : [ 'corechart', 'table', 'controls' ]});
	}
	
	var axi = 1;
	if(options.seriesReset === true) {
		options = googleChartSeriesOptions(options, chartCol);
		
		if(options.vAxesRightUse === true) {
			axi = 2;
		}
	}
	
	google.setOnLoadCallback(function() {
		var data = new google.visualization.DataTable();
		
		var isDataFormat = false;
		for ( var i = 0; i < chartCol.length; i++) {
			if(chartCol[i].format != null) {
				isDataFormat = true;
			}
			if(chartCol[i].name != null) {
				chartCol[i].label = chartCol[i].name;
			}
			data.addColumn(chartCol[i]);
			
			// 문자로된 날짜를 날짜 오브젝트로 변경
			if( (chartCol[i].type == 'date' 
						|| chartCol[i].type == 'datetime' 
						|| chartCol[i].type == 'timeofday'
				) && typeof(chartRow[0][i]) == 'string'
			) {
				chartRow = googleChartDataPattern(i, chartCol[i].dataPattern, chartRow);			
			} else if(chartCol[i].type == 'number' && typeof(chartRow[0][i]) == 'string') {
				chartRow = googleChartDataPattern(i, 'nn', chartRow);
			}
		}
		data.addRows(chartRow);
		if(isDataFormat) {
			googleChartDataFormat(chartCol, data);
		}

		options = options || {};
		
		if(options.width == "auto") {
			options.width = $("#" + chartId).innerWidth();
		} 
		if(options.height == "auto") {
			options.height = $("#" + chartId).innerHeight();
		}
			  
		var chartType = '';
		if(options.chartType == 'area') {
			chartType = 'AreaChart';
		} else if(options.chartType == 'col') {
			chartType = 'ColumnChart';
		} else if(options.chartType == 'bar') {
			chartType = 'BarChart';
		} else if(options.chartType == 'steppedArea') {
			chartType = 'SteppedArea';
		} else {
			chartType = 'LineChart';
		}
		
		if(options.control === true || options.animation === true) {
			googleChartControlDraw(chartType, chartId, data, options);
		} else {
			var wrapper = googleChartWrapperDraw(chartType, chartId, data, options);
			wrapper.draw();
		}
	});
}

// 차트2 양쪽 
function googleChartDraw2(chartId, chartCol, chartRow, options) {
	if(chartRow.length < 1) {
		$("#" + chartId).html("데이터가 없습니다.").addClass("nodata");
		return;
	} else {
		$("#" + chartId).removeClass("nodata");
	}
	
	if(!('visualization' in google)) {
		google.load('visualization', '1.1', {'packages' : [ 'corechart', 'table', 'controls' ]});
	}
	
	if(options.seriesReset === true) {
		options = googleChartSeriesOptions(options, chartCol);
	}
	
	google.setOnLoadCallback(function() {
		var data = new google.visualization.DataTable();

		var isDataFormat = false;
		for ( var i = 0; i < chartCol.length; i++) {
			if(chartCol[i].format != null) {
				isDataFormat = true;
			}
			if(chartCol[i].name != null) {
				chartCol[i].label = chartCol[i].name;
			}
			data.addColumn(chartCol[i]);
			
			// 문자로된 날짜를 날짜 오브젝트로 변경
			if( (chartCol[i].type == 'date' 
						|| chartCol[i].type == 'datetime' 
						|| chartCol[i].type == 'timeofday'
				) && typeof(chartRow[0][i]) == 'string'
			) {
				chartRow = googleChartDataPattern(i, chartCol[i].dataPattern, chartRow);			
			} else if(chartCol[i].type == 'number' && typeof(chartRow[0][i]) == 'string') {
				chartRow = googleChartDataPattern(i, 'nn', chartRow);
			}
		}
		data.addRows(chartRow);
		if(isDataFormat) {
			googleChartDataFormat(chartCol, data);
		}
		
		options = options || {};
		
		if(options.width == "auto") {
			options.width = $("#" + chartId).innerWidth();
		} 
		if(options.height == "auto") {
			options.height = $("#" + chartId).innerHeight();
		}
		
		var chartType = '';
		if(options.chartType == 'area') {
			chartType = 'AreaChart';
		} else if(options.chartType == 'col') {
			chartType = 'ColumnChart';
		} else if(options.chartType == 'bar') {
			chartType = 'BarChart';
		} else if(options.chartType == 'steppedArea') {
			chartType = 'SteppedArea';
		} else {
			chartType = 'LineChart';
		}
		
		if(options.control === true || options.animation === true) {
			googleChartControlDraw(chartType, chartId, data, options);
		} else {
			var wrapper = googleChartWrapperDraw(chartType, chartId, data, options);
			wrapper.draw();
		}
	});
}

// 콤보차트1
function googleComboChartDraw1(chartId, chartCol, chartRow, options) {
	if(chartRow.length < 1) {
		$("#" + chartId).html("데이터가 없습니다.").addClass("nodata");
		return;
	} else {
		$("#" + chartId).removeClass("nodata");
	}
	
	if(!('visualization' in google)) {
		google.load('visualization', '1.1', {'packages' : [ 'corechart', 'table', 'controls' ]});
	}
	
	var axi = 1;
	if(options.seriesReset === true) {
		options = googleChartSeriesOptions(options, chartCol);
		
		if(options.vAxesRightUse === true) {
			axi = 2;
		}
	}
	
	google.setOnLoadCallback(function() {
		var data = new google.visualization.DataTable();

		var isDataFormat = false;
		for ( var i = 0; i < chartCol.length; i++) {
			if(chartCol[i].format != null) {
				isDataFormat = true;
			}
			if(chartCol[i].name != null) {
				chartCol[i].label = chartCol[i].name;
			}
			data.addColumn(chartCol[i]);
			
			// 문자로된 날짜를 날짜 오브젝트로 변경
			if( (chartCol[i].type == 'date' 
						|| chartCol[i].type == 'datetime' 
						|| chartCol[i].type == 'timeofday'
				) && typeof(chartRow[0][i]) == 'string'
			) {
				chartRow = googleChartDataPattern(i, chartCol[i].dataPattern, chartRow);			
			} else if(chartCol[i].type == 'number' && typeof(chartRow[0][i]) == 'string') {
				chartRow = googleChartDataPattern(i, 'nn', chartRow);
			}
		}
		data.addRows(chartRow);
		if(isDataFormat) {
			googleChartDataFormat(chartCol, data);
		}
		
		options = options || {};
		
		if(options.width == "auto") {
			options.width = $("#" + chartId).innerWidth();
		} 
		if(options.height == "auto") {
			options.height = $("#" + chartId).innerHeight();
		}
		
		if(options.control === true) {
			googleChartControlDraw('ComboChart', chartId, data, options);
		} else {
			var wrapper = googleChartWrapperDraw('ComboChart', chartId, data, options);
			wrapper.draw();
		}
	});
}

// 콤보차트2 양쪽
function googleComboChartDraw2(chartId, chartCol, chartRow, options) {
	if(chartRow.length < 1) {
		$("#" + chartId).html("데이터가 없습니다.").addClass("nodata");
		return;
	} else {
		$("#" + chartId).removeClass("nodata");
	}
	
	if(!('visualization' in google)) {
		google.load('visualization', '1.1', {'packages' : [ 'corechart', 'table', 'controls' ]});
	}
	
	if(options.seriesReset === true) {
		options = googleChartSeriesOptions(options, chartCol);
	}
	
	google.setOnLoadCallback(function() {
		var data = new google.visualization.DataTable();

		var isDataFormat = false;
		for ( var i = 0; i < chartCol.length; i++) {
			if(chartCol[i].format != null) {
				isDataFormat = true;
			}
			if(chartCol[i].name != null) {
				chartCol[i].label = chartCol[i].name;
			}
			data.addColumn(chartCol[i]);
			
			// 문자로된 날짜를 날짜 오브젝트로 변경
			if( (chartCol[i].type == 'date' 
						|| chartCol[i].type == 'datetime' 
						|| chartCol[i].type == 'timeofday'
				) && typeof(chartRow[0][i]) == 'string'
			) {
				chartRow = googleChartDataPattern(i, chartCol[i].dataPattern, chartRow);			
			} else if(chartCol[i].type == 'number' && typeof(chartRow[0][i]) == 'string') {
				chartRow = googleChartDataPattern(i, 'nn', chartRow);
			}
		}
		data.addRows(chartRow);
		if(isDataFormat) {
			googleChartDataFormat(chartCol, data);
		}
		
		options = options || {};
		
		if(options.width == "auto") {
			options.width = $("#" + chartId).innerWidth();
		} 
		if(options.height == "auto") {
			options.height = $("#" + chartId).innerHeight();
		}
			
		options = googleChartOptions(options, axi);
		if(chartRow.length > (options.width - 200 /10)) {
			options.pointSize = 0;
		}
		
		if(options.control === true) {
			googleChartControlDraw('ComboChart', chartId, data, options);
		} else {
			var wrapper = googleChartWrapperDraw('ComboChart', chartId, data, options);
			wrapper.draw();
		}
	});
}

// 버블차트
function googleBubbleChartDraw(chartId, chartCol, chartRow, options) {
	if(chartRow.length < 1) {
		$("#" + chartId).html("데이터가 없습니다.").addClass("nodata");
		return;
	} else {
		$("#" + chartId).removeClass("nodata");
	}
	
	if(!('visualization' in google)) {
		google.load('visualization', '1.1', {'packages' : [ 'corechart' ]});
	}
	
	google.setOnLoadCallback(function() {
		//var data = new google.visualization.arrayToDataTable(chartRow);
		var data = new google.visualization.DataTable();

		var isDataFormat = false;
		for ( var i = 0; i < chartCol.length; i++) {
			if(chartCol[i].format != null) {
				isDataFormat = true;
			}
			if(chartCol[i].name != null) {
				chartCol[i].label = chartCol[i].name;
			}
			data.addColumn(chartCol[i]);
		}
		data.addRows(chartRow);
		if(isDataFormat) {
			googleChartDataFormat(chartCol, data);
		}
		
		options = options || {};
		
		if(options.width == "auto") {
			options.width = $("#" + chartId).innerWidth();
		} 
		if(options.height == "auto") {
			options.height = $("#" + chartId).innerHeight();
		}	
		
		options = googleChartOptions(options, 3);
		
		options.bubble = {opacity: 0.6, textStyle: {fontSize: 13}, stroke: "#fff"};
		
		options.colorAxis = {};
		options.colorAxis.colorAxis = googleColorsHigh(-1);
		options.colors = googleColorsHigh(-1);
		
		options.hAxis = {};
		options.hAxis.baselineColor = '#ddd';
		options.hAxis.gridlines = {color: "#ddd", count:4};
		options.hAxis.minValue = 0;
		options.hAxis.maxValue = 100;
		
		options.vAxis = {};
		options.vAxis.baselineColor = '#ddd';
		options.vAxis.gridlines = {color: "#ddd", count:4};
		options.vAxis.minValue = 0;
		options.vAxis.maxValue = 100;	
		
		options.sizeAxis = {};
		options.sizeAxis.maxSize = 35;
		options.sizeAxis.minSize = 19;

		options.tooltip.trigger = 'none';
		
		// IE하위버전에서 오류남
		if(options.ani === true) {
			if(!supports_canvas()) {
				options.ani = false;
			} else {
				options.sizeAxis.minSize = 0;
			}
		}
		
		var wrapper = googleChartWrapperDraw('BubbleChart', chartId, data, options);
		
		options.sizeAxis.minSize = 19;
		wrapper.setOptions(options);
		wrapper.draw();
	});

}

// 차트 컨트롤 생성
function googleChartControlDraw(chartType, chartId, dataTable, options) {
//	var dataView = new google.visualization.DataView(dataTable);
//	dataView.setColumns([0, 1]);
//		
//	var table = new google.visualization.Table(document.getElementById(chartId + 'Table'));
//	table.draw(dataView, null);
	
	var dashboard = new google.visualization.Dashboard(document.getElementById(chartId + 'Dashboard'));
	var wrapper = googleChartWrapperDraw(chartType, chartId, dataTable, options);
	
	///////////////////////////////////////////////////////////////////////	
//	var controlChartOption = JSON.parse(JSON.stringify(options));
//	controlChartOption.height = 50;
//	controlChartOption.width = 0;
//	controlChartOption.chartArea = {'width': '98%', 'height': 50};
//	controlChartOption.seriesType = options.seriesType;
//	controlChartOption.legend.position = 'none';		
//	// controlChartOption.hAxis.baselineColor = none;
	
	var controlOption = {
		'controlType': 'ChartRangeFilter',
		'containerId': chartId + 'Control',
		'options': {
			'filterColumnIndex': 0,
			'ui': {
				'chartType': chartType,	
				'chartOptions': {
					'chartArea': {'width': '98%'},
					'hAxis': {'baselineColor': 'none'},
					'colors': googleColors(-1),
					'seriesType': options.seriesType
				} /*controlChartOption*/,
				//'chartView': {'columns': [0, 1]},
				'minRangeSize': 86400000
			}
		}
		//, 'state': {'range': {'start': start, 'end': end}}
	};
	
	if(options.controlViewIndex) {
		controlOption.options.ui.chartView = {'columns' : options.controlViewIndex};
	}
	if(options.controlSeries) {
		controlOption.options.ui.chartOptions.series = options.controlSeries;		
	} else {
		controlOption.options.ui.chartOptions.series = options.series;
	}
	
	var control = new google.visualization.ControlWrapper(controlOption);

	dashboard.bind(control, wrapper);
	dashboard.draw(dataTable);
}

// 컨트롤 포함된 차트 그리기
function googleChartWrapperDraw(chartType, chartId, dataTable, options) {
	var sDataTable;
	
	var wrapper = new google.visualization.ChartWrapper({
		chartType: chartType,
		options: options,
		containerId: chartId
	});

	if(options.ani === true) {
		options.animation = {duration: 1000, easing: 'out'};
		sDataTable = dataTable.clone();
		
		var row = dataTable.getNumberOfRows();
		var col = dataTable.getNumberOfColumns();
	
		var idx = 1;
		if(chartType == 'BubbleChart') {
			idx = 4;
		}
		
		for(var i = 1; i < row; i++) {
			for(var j = idx; j < col; j++) {
				if(!sDataTable.getColumnRole(j) && sDataTable.getColumnType(j) == 'number') {
					sDataTable.setValue(i, j, 0);
				}
			}
		}
		
		wrapper.setDataTable(sDataTable);
		wrapper.draw();
	} 

	wrapper.setDataTable(dataTable);
	return wrapper;
}

// series설정이 옵션에 따라 변경되어 설정하기 불편해서 col설정에 따라 변경되도록 추가
function googleChartSeriesOptions(options, chartCol) {
	options.series = {};
	
	var cIndex = -1;
	var controlSeriesOption = {};
	
	for(var i=1, j=0; i < chartCol.length; i++) {
		if(chartCol[i].role == null) {
			options.series[j] = {};
			var tIndex = 0;
			
			// 컨트롤 영역 series를 차트와 동일하게 설정
			var isSetCtrl = false;
			if(options.controlViewIndex != null && $.inArray(i, options.controlViewIndex) > -1) {
				cIndex++;
				isSetCtrl = true;
				
				controlSeriesOption[cIndex] = {color : googleColors(j)};
			}
						
			if(chartCol[i].series != null) {			 				
				for(var key in chartCol[i].series) {
					if(key == 'index') {
						tIndex = chartCol[i].series.index;
						
						// 오른쪽 축이 표시될때 차트2 자동 변경
						var tmpAxis = options.vAxes[tIndex];
						if(tmpAxis != null && tmpAxis.title != "" && tmpAxis.textPosition != "none" && tmpAxis.textPosition != "in" && 0 < tIndex) {
							options.vAxesRightUse = true;
						}
					} else if(key != 'colorSet') {
						options.series[j][key] = chartCol[i].series[key];
						
						if(isSetCtrl === true) {
							controlSeriesOption[cIndex][key] = chartCol[i].series[key];
						}
					}
				}

				// index가 설정되고나면 컬러 설정
				if(chartCol[i].series.colorSet) {
					if(options.vAxes[tIndex] == null) {
						options.vAxes[tIndex] = {};
					}
					if(!options.vAxes[tIndex].color) {
						if(chartCol[i].series.color) {
							options.vAxes[tIndex].color = chartCol[i].series.color;
						} else {
							options.vAxes[tIndex].color = googleColors(j);
						}
					}
				}
			}
			
			options.series[j].targetAxisIndex = tIndex;
			if(isSetCtrl === true) {
				controlSeriesOption[cIndex]['targetAxisIndex'] = tIndex;
			}
			j++;
		}
	}	
	
	//print_r(controlSeriesOption);
	
	// 컨트롤 영역
	if(cIndex > -1) {
		options.controlSeries = controlSeriesOption;
	}

	return options;
}

// 기본 옵션
function googleChartOptions(options, axi) {
	var commonOption = {
		width : 714,
		height : 400,
		fontName : '"Dotum", "Gulim"',
		chartArea: {left:60,top:50,width:650,height:360},
		fontSize : 12,
		//selectionMode : "multiple",
		pointSize: 2,
		tooltip: {textStyle:{fontSize:15}},
		legend: {position:'top',textStyle:{fontSize:11}},
		colors: googleColors(-1)
		//backgroundColor:"transparent"
		//explorer: {actions: ['dragToZoom', 'rightClickToReset']}
	};

	if(options) {
		$.each(options, function(key, val) {
			if(key == "vAxes" || key == "hAxes") {
				$.each(val, function(key2, val2) {
					if(!val[key2].titleTextStyle) {
						val[key2].titleTextStyle = {};
					}
					val[key2].titleTextStyle.fontSize = 11;
					//val[key2].titleTextStyle.bold = true;
					val[key2].titleTextStyle.italic = false;
					
					if(!val[key2].textStyle) {
						val[key2].textStyle = {};
					}
					val[key2].textStyle.fontSize = 11;
					
					if(val[key2].color != null) {
						val[key2].textStyle.color = val[key2].color;
						val[key2].titleTextStyle.color = val[key2].color;
					}
					
//					if(key == "hAxes") {
//						val[key2].slantedText = 1; // angle 적용
//						val[key2].slantedTextAngle = 30; // angle 30
//						val[key2].showTextEvery = 3; // 1, 4, 7번째 포인트에 라벨 출력
//					}
				});
			}	
			commonOption[key] = val;
		});
	}
	options = commonOption;
	if(axi == 3) {
		options.chartArea = {left:0,top:0,width:"100%",height:"100%"};
	} else if(axi == 2) {
		options.chartArea = {left:60,top:40,width:options.width-110,height:options.height-100};
	} else {
		options.chartArea = {left:60,top:40,width:options.width-90,height:options.height-100};
	}
	return options;
}

// 차트 기본 컬러
function googleColors(idx) {
	var colors = ['#8d082b','#c07010','#2660a7','#46a544','#962690','#04989a','#c65199','#074d4d','#2390d8','#5e6874'];
	if(idx == -1) {
		return colors;
	} else {
		return colors[idx];
	}
}

//차트 기본 컬러
function googleColorsHigh(idx) {
	var colors = ['#db9131','#73ab22','#22ab90','#227cab','#227cab','#4622ab','#4622ab','#8022ab','#ab2299','#ba0000'];
	if(idx == -1) {
		return colors;
	} else {
		return colors[idx];
	}
}



/////////////////////////////////////////////////////////////////////
// D3

// d3 force 차트
function d3ForceChartDraw(id, cdatas, count) {
	var width = $("#" + id).width(), height = $("#" + id).height();
	var linksize_base = Math.round((width > height ? height: width) / 2) - 70;
	var linksize_mul = 0;
	var color = d3.scale.category20();
	
	if(count != null && count > 1) {
		linksize_base = Math.round(linksize_base / 3); 
		linksize_mul = Math.round(linksize_base * 2 / count);
		linksize_base = linksize_base + linksize_mul;
	}
	
	$("#" + id).css({fontSize:"12px",lineHeight:"12px"});
	
	var force = d3.layout.force()
		.gravity(.05)
		.charge(-200)
		//.distance(150)
		.linkDistance(function(d) {
			return d.source.group == 0 ? 0 : d.source.group * linksize_mul + linksize_base; 
			//return (parseInt(Math.random() * 10) + 1) * 15 + 50;
			// return Math.sqrt(d.value) * 30 + 80; 
			//return 180 - (Math.sqrt(d.value) * 30); 
		})
		.size([width, height]);

	var svg = d3.select("#" + id).append("svg")
	.attr("width", width)
	.attr("height", height);

	force
	  .nodes(cdatas.nodes)
	  .links(cdatas.links)
	  .start();

	var link = svg.selectAll(".link")
		.data(cdatas.links)
		.enter().append("line")
		.attr("class", "link")
		.style("stroke-width", 1)
		.style("stroke", function(d) { return color(d.source.group); })
		//.style("stroke", "#999")
		.style("stroke-opacity", .6);

	var node = svg.selectAll(".node")
		.data(cdatas.nodes)
		.enter().append("circle")
		.attr("class", "node")
		.attr("r", function(d) { return d3ForceRound(d); })
		.style("fill", function(d) { return color(d.group); })
		.style("stroke-width", 2)
		.style("stroke", "#fff");

	node.append("title")
		.text(function(d) { return (d.group == 0 ? d.name : d.name + " : " + d.r); });

	var gnodes = svg.selectAll('g.gnode')
		.data(cdatas.nodes)
		.enter()
		.append('g')
		.classed('gnode', true);

	var rect = gnodes.append("rect")
		.attr("rx", 6)
		.attr("ry", 6)
		//.attr("y", -9)
		.attr("width", 0)
		.attr("height", 18)
		.style("fill", function(d) { return color(d.group); })
		.style("opacity", .3);
		
	var text = gnodes.append("text")
		.text(function(d) { return d.name; })
		.attr("dy", ".35em")
		.attr("y", function(d) { return d3ForceRound(d)+12; })
		.attr("text-anchor", "middle")
		//.attr("x", function(d) { return d3ForceRound(d) + 5; })
		.style("fill", "#000");
	
	text.each(function(obj, idx) {
		var pObj = d3.select(this.parentNode);
		if($.browser.msie != true) {
			pObj.select("rect")
				.attr("x", this.getBBox().x - 5)
				.attr("width", this.getBBox().width + 10)
				.attr("height", this.getBBox().height + 4)
				.attr("y", this.getBBox().y - 2);
		} else {
			pObj.select("rect")
				.attr("x", this.getBBox().x - 5)
				.attr("width", this.getBBox().width + 10)
				.attr("y", this.getBBox().y + 1);
		}
	});
		
	if(supports_canvas()) {
	 	node.call(force.drag);
	 	text.style("fill", "#fff");
	 	rect.style("opacity", .7);
	}

	force.on("tick", function() {
		link.attr("x1", function(d) { return d.source.x; })
		    .attr("y1", function(d) { return d.source.y; })
		    .attr("x2", function(d) { return d.target.x; })
		    .attr("y2", function(d) { return d.target.y; });

		node.attr("cx", function(d) { return d.x; })
		    .attr("cy", function(d) { return d.y; });
		
		gnodes.attr("transform", function(d) { 
		    return 'translate(' + [d.x, d.y] + ')'; 
		});
	});	
}

function d3ForceRound(d) {
	if(d.group == 0) return 20;
	else return Math.sqrt(d.r/100) * 5;
}

function d3ForceStroke(d) {
	//var s = Math.sqrt(d.source.r/100);
	//return s < 1 ? 1 : s;
	return 1;
}



/////////////////////////////////////////////////////////////////////
//기타

// Nonsync Ajax Call
function callAjax( callUrl, callParam, submitType )
{
	var url			= callUrl;
	var params	= callParam;
	var returnV	= null;

	$.ajaxSetup({"async":false});
	$.ajax({
		type: submitType,
		url:url,
		data:params,
		success:function(args){
			returnV = args;
		},
		error:function(e){
			returnV = false; //e;
		}
	});	
	$.ajaxSetup({"async":true});
	return returnV;
}

// Nonsync Ajax Call (json)
function callAjaxJson( callUrl, callParam, submitType )
{
	var url		= callUrl;
	var params	= callParam;
	var returnV = null;

	$.ajaxSetup({"async":false});
	$.ajax({
		type: submitType,
		url:url,
		data:params,
		datatype:'json',
		success:function(args){
			returnV = args;
		},
		error:function(e){
			returnV = e;
			//alert( "에러로 조회하지 못했습니다!" );
		}
	});	
	$.ajaxSetup({"async":true});
	return returnV;
}

// d3 force 차트 대신 버블차트 사용시 원 좌표
// opt {max, min, idx, cpX, cpY, wRadius, hRadius, apGap, apMax}
function roundXY(opt) {
	var a = 360.0 / opt.max * opt.idx;
	
	var radian = 3.1415926535 / 180. * a;
	var x = opt.cpX + opt.wRadius * Math.cos(radian);
	var y = opt.cpY + opt.hRadius * Math.sin(radian);

	opt.idx += 1;
	if(opt.idx >= opt.max) {
		opt.idx = 0;
		opt.apGap -= 5;
		opt.wRadius += opt.apGap;
		opt.hRadius += opt.apGap;
		opt.max = Math.round(opt.max + 4 + opt.apMax);
	}
	
	opt.x = x;
	opt.y = y;
	return opt;
}

// 태그삭제
function removeTags(input) { 
	return input.replace(/<[^>]+>/g, "");  
}

function trim( str ) {
	return str.replace(/(^\s*)|(\s*$)/gi, "");
}

function padZero(num) {
	return (num < 10)? '0' + num : num;
}

function movePage( page_num )
{
	document.forms[0].page_num.value = page_num;
	document.forms[0].submit();
}

/////////////////////////////////////////////////////////////////////
// 팝업
function openPopup(url, w, h, x, y, opt) {
	openPopupTarget(url, "", w, h, x, y, opt);
}

function openPopupTarget(url, target, w, h, x, y, opt) {
	if(opt == null) opt = "menubar=0,toolbar=0,status=0,scrollbars=1,resizable=0,directorys=0,copyhistory=0";
	if(x == null) x = 0;
	if(y == null) y = 0;
			
	var win = window.open(url, target, "left="+x+",top="+y+",width="+w+",height="+h+","+opt);
	if(win != null) win.focus();
}

function createOverlay(id, w, h) {
	$("#" + id).css({'position':'absolute', 'width':w, 'height':h, 'z-index':999}).addClass("ui-overlay");
	var ctnt = $('<div class="ui-widget ui-widget-content ui-corner-all"><div></div></div>').css({
		'position':'relative','padding':'8px 10px 10px 10px', 'overflow':'auto',
		'width':($("#" + id).width()-20) + 'px', 'height':($("#" + id).height()-18) + 'px'
	});
	ctnt.children("div").append($("#" + id).children());

	var close_btn = $('<a href="#" class="ui-overlay-close"><i class="fa fa-close"></i></a>').css({'position':'absolute', 'top':'4px', 'right':'5px', 'font-size':'25px'});
	close_btn.click(function() {
		$("#" + id).hide();
		return false;
	});

	$("#" + id).prepend($('<div class="ui-widget-shadow ui-corner-all"></div>').css({
		'position':'absolute','top':0, 'left':0, 
		'width':($("#" + id).outerWidth()+2) + 'px', 'height':($("#" + id).outerHeight()+2) + 'px'
	})).prepend('<div class="ui-widget-overlay"></div>');

	$("#" + id).find(".ui-widget-overlay").click(function() {
		$("#" + id).hide();
		return false;
	});

	$("#" + id).append(ctnt).append(close_btn).hide();
}
function openOverlay(id, l, t, f_overlay) {
	if(f_overlay === true) {
		$("#" + id).children(".ui-widget-overlay").hide();
	} else {
		$("#" + id).children(".ui-widget-overlay").show();
	}

	$("#" + id).css({'top':t, 'left':l}).addClass("ui-overlay");
	var ctnt = $("#" + id).children(".ui-widget-content");
	$("#" + id).show();
	if(ctnt.children("div").height() + 18 > ctnt.height()) {
		$("#" + id).children(".ui-overlay-close").css({'right':'20px'});
	}
}

/////////////////////////////////////////////////////////////////////
//치환
//문자열 치환 {AAA} -> data.AAA로 변환
function stringFormat(format, data) {
	return format.replace(/{([a-zA-Z][a-zA-Z0-9_]+)(:[a-z0-9()-]+)?}/g, function(match, key, fn) {
		var str = match;

		if(data == null) {
			return "";
		}
		if(key in data) {
			str = data[key] + '';
			try {
				if(fn != null && fn.length > 3) {
					/* :len(10) */
					var v = fn.length > 6 ? (fn.substring(5, fn.length-1) + '-').split('-') : null;
					fn = fn.substring(1, 4);
					switch(fn) {
					case 'len' : 
						if(v == null || v.length < 1) return str;
						var len = parseInt(v[0],10);
						str = str.length > len ? str.substring(0, len-2) + '..' : str;
						break;
					case 'sub' : 
						if(v == null || v.length < 2) return str;
						var s = parseInt(v[0],10);
						var e = parseInt(v[1],10);
						str = str.substring(s, e);
						break;
					}
				}
			} catch(e) {
				alert(e.message);
			}
		}
		
		return str;
	});
}
//날짜 치환
function dateFormat(format, date) {
 if (!this.valueOf()) return " ";
 
 var weekName = ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"];
 var d = date || new Date();

 return format.replace(/(yyyy|yy|MM|dd|E|HH|hh|mm|ss|a\/p)/g, function(match) {
     switch (match) {            
        case "yyyy": return d.getFullYear();
        case "yy": return d.getYear();
        case "MM": return padZero(d.getMonth() + 1);
        case "dd": return padZero(d.getDate());            
        case "E": return weekName[d.getDay()];            
        case "HH": return padZero(d.getHours());            
        case "hh": return padZero((h = d.getHours() % 12) ? h : 12);  
        case "mm": return padZero(d.getMinutes());            
        case "ss": return padZero(d.getSeconds());            
        case "a/p": return d.getHours() < 12 ? "오전" : "오후";
        default: return match;
      }
 });
}
// 숫자 치환
function numberFormat(num) {
	if(num==0) return 0;
 
	var reg = /(^[+-]?\d+)(\d{3})/;
	var n = (num + '');
 	while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');
 
	return n;
} 


//노드 전체 출력(디버깅용)
function print_r(arr, dept) {
	if(arr == null) return "null";
	dept = (dept == null ? 0 : dept);

	var tab = "";
	for(i=0; i < dept; i++) {
		tab += "\t";
	}
	//if(dept > 1) return "";

	var str = "";
	if(typeof(arr) == "object") {
		str = "Object(\n";
		$.each(arr, function(i, v) {
			if(typeof(v) == "object") {
				v = print_r(v, dept+1);
			} 
			str += tab + "\t[" + i + "] = " + v + "\n";
		});
		str += tab + ");\n";
	} else {
		str += "" + arr;
	}

	if(dept == 0) {
		//str = str.replace(/\n/g,"<br />").replace(/\t/g,"&nbsp; &nbsp; &nbsp; &nbsp;");
		//$("body").append(str);
		$("body").append($("<pre></pre>").append(document.createTextNode(str)));
	} else {
		return str;
	}
}

//캔버스 지원 여부
function supports_canvas() {
	return !!document.createElement('canvas').getContext;
}

// html tag 제거
function removeTag( html ) {
     return html.replace(/<(\/)?([a-zA-Z]*)(\\s[a-zA-Z]*=[^>]*)?(\\s)*(\/)?>/gi, "");
}


// 사업자 번호 체크
function reg_no_check(strNumb)
{

	if(strNumb.length        !=        10)
	{
		alert("사업자등록번호가 잘못되었습니다.");
        return (false);
	}
        
    sumMod        =        0;
    sumMod        +=        parseInt(strNumb.substring(0,1));
    sumMod        +=        parseInt(strNumb.substring(1,2)) * 3 % 10;
    sumMod        +=        parseInt(strNumb.substring(2,3)) * 7 % 10;
    sumMod        +=        parseInt(strNumb.substring(3,4)) * 1 % 10;
    sumMod        +=        parseInt(strNumb.substring(4,5)) * 3 % 10;
    sumMod        +=        parseInt(strNumb.substring(5,6)) * 7 % 10;
	sumMod        +=        parseInt(strNumb.substring(6,7)) * 1 % 10;
	sumMod        +=        parseInt(strNumb.substring(7,8)) * 3 % 10;
	sumMod        +=        Math.floor(parseInt(strNumb.substring(8,9)) * 5 / 10);
	sumMod        +=        parseInt(strNumb.substring(8,9)) * 5 % 10;
	sumMod        +=        parseInt(strNumb.substring(9,10));
	
	if(sumMod % 10        !=        0)
	{
		alert("사업자등록번호가 잘못되었습니다.");
		return false;
	}
}

function move(href) {
	location.href = href;
}

function charge_move(is_login) {

	if(is_login) {
		location.href="/member/charge.php";
	} else {
		alert('로그인후 이용이 가능합니다.');
		location.href="/member/login.php?goUrl=/member/charge.php";
	}
}

// div 영역 프린트
function div_area_print(div_name) {
	var printHtml = document.getElementById(div_name).innerHTML;
	page_print_openpop(printHtml);
}
function page_print_openpop(getInnerHTML) {
	var printArea = getInnerHTML;
	var win = null;
	win = window.open();
	self.focus();

	win.document.open();
	win.document.write('<'+'html'+'><'+'head'+'><'+'style'+'>');
	win.document.write('body, td {font-family:Verdana; font-size: 10pt;}');
	win.document.write('<'+'/'+'style'+'>');
	win.document.write('<link href="/css/admin_master.css" rel="stylesheet" type="text/css">');
	win.document.write('<link href="/j_admin/style.css" rel="stylesheet" type="text/css">');
	win.document.write('<'+'/'+'head'+'><'+'body'+'>');
	win.document.write(printArea);
	win.document.write('<'+'/'+'body'+'><'+'/'+'html'+'>');
	win.document.close();
	win.print();
	win.close();
}