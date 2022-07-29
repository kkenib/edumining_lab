var scripts = document.getElementsByTagName('script');
var myScript = scripts[ scripts.length - 1 ];
var queryString = myScript.src.replace(/^[^\?]+\??/,'');
var params = parseQuery( queryString );

function parseQuery ( query ) {
  var Params = new Object ();
  if ( ! query ) return Params; // return empty object
  var Pairs = query.split(/[;&]/);
  for ( var i = 0; i < Pairs.length; i++ ) {
    var KeyVal = Pairs[i].split('=');
    if ( ! KeyVal || KeyVal.length != 2 ) continue;
    var key = unescape( KeyVal[0] );
    var val = unescape( KeyVal[1] );
    val = val.replace(/\+/g, ' ');
    Params[key] = val;
  }
  return Params;
}

// <script type="text/javascript" src="cookie.js"></script>
//<div id="popup1" class="jspopup" js-start="20140122" js-end="20140222" js-size="300:300" js-pos="100:100">
//	����
//</div>
//
// js-auto-open : [false] true�� �������ε��� ����
// js-not-today : [false] true�� todayǥ������ ���� 
// js-modal : [false] true�� ��׶��� ȸ�� ó��
// js-start : [] ��¥(yyyyMMdd) ������
// js-end : [] ��¥(yyyyMMdd) ������
// js-size : [auto:auto] width:height ������ ���� auto:100, 100:100���� ��������
// js-pos : [center] top:left ������ ���� 100:center, 100:100���� ��������
if(!('auto' in params) || params['auto'] != "N") {
	$(function() {
		jspopup_create_all();
	});
}
function jspopup_create_all() {
	$(".jspopup").each(function() {	
		jspopup_create(this);
	});
}
function jspopup_create(selector) {
	var $obj = $(selector);
	var id = $obj.attr("id");
	var parent = document.body;
	
	var is_auto = $obj.attr("js-auto-open") || "false";
	var is_not_today = $obj.attr("js-not-today") || "false";
	var size = $obj.attr("js-size") || "auto:auto";
	var pos = $obj.attr("js-pos") || "center:center";
	var target = $obj.attr("js-target") || "";
	var cookie_name = jspopup_cookie_name(id);

	var today_h = (is_auto == "true" && is_not_today != "true" ? 24 : 0);
	var today_p = 10;
	var close_w = 21;
	var top = 0, left = 0;
	var width = 0, height = 0;

	var $cont = $obj.attr("id", "");
	var $win = $('<div class="jspopup_layer" id="' + id + '"></div>');
	var $wrap = $('<div class="jspopup_wrap" id="' + id + 'wrap"></div>');
		
	if(target != "") {
		parent = "#" + target;
	} else {
		// target�� ������ ������ window
		$win.css({position:"fixed"});
	}

	$win.appendTo(parent);
	$wrap.appendTo($win);
	
	// ������
	if(size == "auto") size = "auto:auto";
	size = size.split(":");
	width = size[0];
	height = size[1];

	if(width == "auto") width = $owin.width();
	if(height == "auto") height = $owin.height();

	width = parseInt(width, 10);
	height = parseInt(height, 10);

	$cont.css({paddingBottom: (parseInt($cont.css('paddingBottom'),10) + today_h) + "px"}).appendTo($wrap).show();
	$win.css({width: width + "px", height: (height + today_h) + "px"});
	$wrap.css({width: width + "px", height: (height + today_h) + "px"});
			
	var innerHeight = parseInt($wrap[0].clientHeight, 10);
	var innerWidth = parseInt($wrap[0].clientWidth, 10);
	
	if(is_auto == "true" && is_not_today != "true") {
		var $today = $('<div class=\"today\"><input type="checkbox" id="' + id + 'check" checked="checked" /> <label for="' + id + 'check">���� �Ϸ� ��â�� ���� �ʽ��ϴ�.</label></div>');		
		$today.css({top:(innerHeight - today_h) + "px", width:(innerWidth - today_p) + "px"}).appendTo($wrap).children("input").click(function() {
			if($(this).is(":checked")) {
				jspopup_close(id);
			}
		});
	}
	
	var $close = $('<div class="close"><img src="/resources/js/jspopup/btn_close.gif" alt="�ݱ�" width="' + close_w + '" height="' + close_w + '"/></div>');
	$close.css({left:(innerWidth - close_w) + "px"}).appendTo($wrap);

	$close.children("img").click(function() {
		jspopup_close(id);
	});
	
	// ��׶��� ǥ��
	jspopup_background(id);

	$win.hide();
	if(is_auto == "true") {
		jspopup_open(id, true, true);
	}
	console.log("create start");
}

// �ʱ� ���� ���̾�
function jspopup_get_obj(id) {
	var $owin = $("#" + id);
	
	if(!$owin.is(".jspopup")) {
		$owin = $("#" + id).find(".jspopup");
	}

	return $owin;
}
// ���̾� ��Ű��
function jspopup_cookie_name(id) {
	var $owin = jspopup_get_obj(id);
	var date = jspopup_get_date(id);
	return id + date[1];
}
// ���̾� �Ⱓ����
function jspopup_get_date(id) {
	var $owin = jspopup_get_obj(id);

	var start = $owin.attr("js-start");
	var end = $owin.attr("js-end");

	if(start == null || start == "undefined") start = "";
	if(end == null || end == "undefined") end = "";

	var date = new Date();
	var y = date.getFullYear();
	var m = ("0" + (date.getMonth() + 1)).slice(-2);
	var d = ("0" + date.getDate()).slice(-2);
	date = y + "" + m + "" + d;

	return {today:date, start:start, end:end};
}
// ���̾� ��ġ
function jspopup_get_position(id) {
	var $owin = jspopup_get_obj(id);

	var parent = document.body;
	var p_width = parseInt($(window).width(), 10);
	var p_height = parseInt($(window).height(), 10);

	var width = parseInt($("#" + id).width(), 10);
	var height = parseInt($("#" + id).height(), 10);
	
	var target = $owin.attr("js-target") || "";
	var pos = $owin.attr("js-pos") || "center:center";

	if(target != "") {
		parent = "#" + target;

		p_width =  parseInt($(parent).width(), 10);
		p_height =  parseInt($(parent).height(), 10);
	}

	if(pos == "center") pos = "center:center";
	pos = pos.split(":");

	var top = pos[0];
	var left = pos[1];

	if(top == "center") {
		top = parseInt((p_height - height) /2, 10);
	} else {
		top = parseInt(top, 10);
	}
	if(left == "center") {
		left = parseInt((p_width - width) /2, 10);
	} else {
		left = parseInt(left, 10);
	}

	return {left:left, top:top};
}
// ���̾� ǥ�ð��� ����
function jspopup_open_check(id) {
	var $owin = jspopup_get_obj(id);
	var cookie_name = jspopup_cookie_name(id);
	var result = {allclear:true, cookie:true, date:true};

	if(getCookie(cookie_name) == "Y") {
		result.allclear = false;
		result.cookie = false;
	}
	
	var date = jspopup_get_date(id);
	if((date.start && date.today < date.start) 
			|| (date.end && date.today > date.end)) {
		result.allclear = false;
		result.date = false;
	} 

	return result;
}
// ���̾� ����
function jspopup_open(id, is_cookie_check, is_date_check) {
	var $owin = jspopup_get_obj(id);
	var check = jspopup_open_check(id);
	
	// ��Ű�� Y�̸� ǥ������ �ʴ´�. 
	if(is_cookie_check === true && check.cookie == false) {
		return false;
	}

	// ��¥�� ������ ǥ������ �ʴ´�.
	if(is_date_check === true && check.date == false) {
		return false;
	}

	var pos = jspopup_get_position(id);

	$("#" + id).css({top: pos.top, left: pos.left}).show();
	$("#" + id + "_background").show();

	return true;
}
// ���̾� �ݱ�
function jspopup_close(id) {
	var cookie_name = jspopup_cookie_name(id);
	if($("#" + id).find(".today input[type='checkbox']").is(":checked")) {
		setCookie(cookie_name, "Y");
	}

	$("#" + id).hide();
	$("#" + id + "_background").hide();
}
// ��׶���
function jspopup_background(id) {
	var $owin = jspopup_get_obj(id);
	var visible = $owin.attr("js-modal") || "false";

	if(visible == "true") {
		$bg = $('<div id="' + id + '_background"></div>').css({
			position: "fixed",
			top: 0, left: 0, width: 0, height: 0, 
			background: "#000", opacity: 0.6
		}).hide();
		$("#" + id).before($bg);
	}
}