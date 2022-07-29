var theme_type = "textomi"

// color, font style
var graph_theme = null;

$.fn.graphVal = function(prop, val) {
	var gd = $(this).data("_graph_val") || {};
	if(prop == null) return gd;
	if(val == null) return gd[prop];
	
	gd[prop] = val;
	$(this).data("_graph_val", gd);
}

$.fn.cleanGraphVal = function(prop) {
	if(prop == null) $(this).data("_graph_val", {});
	var gd = $(this).data("_graph_val") || {};
	delete gd[prop];
	$(this).data("_graph_val", gd);
}

function load_graph_js(arr, _function) {
	var _arr_js = new Array();
	var _arr_css = new Array();

	var colortheme = theme_type;
	
	if(colortheme) {
		// json 파일 내부 홑따옴표(') 사용시 오류발생
		$.getJSON("/graph/colortheme/" + colortheme + ".json", function(json) { 			
			graph_theme = json; 
			if(graph_theme.fontcss.length > 0) $.getMultiCSS(graph_theme.fontcss, '');
		});
	}
	if($.browser.msie != true || ($.browser.msie == true && $.browser.version > 8)) {
		var _g_function = function() {
			if($.inArray("all", arr) > -1) {
				arr = [
				    "datalist",
					"trend_bubble",
					"egonetworks",
					"wordcloud",
					"keyword_bubble",
					"linechart",
					"barchart",
					"repute",
					"pie",
					"radar"
				];
			}

			var chkArr = [];
			$.each(arr, function(i, scr) {
				switch(scr) {
					case 'c3js':
						if(typeof(c3) != 'object' && $.inArray(scr, chkArr) < 0) {
							_arr_css.push("/graph/d3/c3.min.css");
							_arr_js.push("/graph/d3/c3.min.js");
							chkArr.push("c3js");
						}
						break;

						// 데이터 리스트
					case 'datalist':
						if (typeof(graph_datalist) != 'function' && $.inArray(scr, chkArr) < 0) _arr_js.push("/graph/datalist.js");
						break;

					// 에고네트워크
					case 'egonetworks': 
						if (typeof(graph_ego_networks) != 'function' && $.inArray(scr, chkArr) < 0) _arr_js.push("/graph/egonetworks.js");
						break;
						
						// 워드클라우드
					case 'wordcloud':
						if (typeof(graph_wordcloud) != 'function' && $.inArray(scr, chkArr) < 0) _arr_js.push("/graph/wordcloud.js");
						break;
						
					// 이슈 트렌드 그래프
					case 'trend_bubble':
						if (typeof(graph_trend_bubble) != 'function' && $.inArray(scr, chkArr) < 0) _arr_js.push("/graph/trend_bubble.js");
						break;
					
					// 키워드 트랜드 그래프
					case 'keyword_bubble':
						if (typeof(graph_keyword_bubble) != 'function' && $.inArray(scr, chkArr) < 0) {
							_arr_css.push("/graph/keyword_bubble.css");
							_arr_js.push("/graph/keyword_bubble.js");
							_arr_js.push("/graph/d3/d3-queue.js");
						}
						break;
						
					// 라인 차트
					case 'linechart':
						// lc_sty1, lc_sty2
						if(typeof(c3) != 'object' && $.inArray("c3js", chkArr) < 0) {
							_arr_css.push("/graph/d3/c3.min.css");
							_arr_js.push("/graph/d3/c3.min.js");
							chkArr.push("c3js");
						}
						if (typeof(graph_linechart) != 'function' && $.inArray(scr, chkArr) < 0) {
							_arr_css.push("/graph/linechart.css");
							_arr_js.push("/graph/linechart.js");


						}
						break;
					
					case 'barchart':
						// bc_sty1
						if(typeof(c3) != 'object' && $.inArray("c3js", chkArr) < 0) {
							_arr_css.push("/graph/d3/c3.min.css");
							_arr_js.push("/graph/d3/c3.min.js");
							chkArr.push("c3js");
						}
						if (typeof(graph_barchart) != 'function' && $.inArray(scr, chkArr) < 0) {
							_arr_css.push("/graph/barchart.css");
							_arr_js.push("/graph/barchart.js");
						}
						break;
						
					// 파이 차트
					case 'repute':
						if (typeof(graph_repute) != 'function' && $.inArray(scr, chkArr) < 0) _arr_js.push("/graph/repute.js");
						break;

					// 파이 차트
					case 'pie':
						if (typeof(graph_pie) != 'function' && $.inArray(scr, chkArr) < 0) _arr_js.push("/graph/pie.js");
						break;

					// 레이더 차트
					case 'radar':
						if (typeof(graph_radar) != 'function' && $.inArray(scr, chkArr) < 0) {
							_arr_css.push("/graph/radar.css");
							_arr_js.push("/graph/radar.js");
						}
						break;
				}

				chkArr.push(scr);
			});

			// 그래프 커스터마이징 스타일
			_arr_css.push("/graph/graph.css");

			$.getMultiCSS(_arr_css, '');
			$.getMultiScripts(_arr_js, '').done(_function);

			d3.selection.prototype.first = function() { return d3.select(this[0][0]); }; 
			d3.selection.prototype.last = function() { var last = this.size() - 1; return d3.select(this[0][last]); };
			d3.selection.prototype.moveToFront = function() { return this.each(function(){ this.parentNode.appendChild(this); }); };
			d3.selection.prototype.moveToBack = function() { return this.each(function() { var firstChild = this.parentNode.firstChild; if (firstChild) { this.parentNode.insertBefore(this, firstChild); } }); };
		};

		if(typeof(d3) != 'object') {
			$.when(
				// d3 로드 후 실행해야 함
				$.getScript( "/graph/d3/d3-3.5.6.min.js" ),
				//$.getScript( "/graph/d3/d3-4.7.2.min.js" ),
				$.Deferred(function( deferred ){
					$( deferred.resolve );
				})
			).done(_g_function);
		} else {
			_g_function();
		}
	}
}

/**
 * 그래프 로딩 이미지
 */
function loading_graph(selector, flg) {
	if(flg) {
		var h = $(selector).height();
		var w = $(selector).width();
		var pos = $(selector).position();

		var marginTop = parseInt($(selector).css("margin-top"));
		var marginLeft = parseInt($(selector).css("margin-left"));

		var css = {
			position:	"absolute",
			left:		pos.left + marginLeft,
			top:		pos.top + marginTop,
			height:		h + "px", 
			width:		w + "px",  
			lineHeight: h + "px", 
			textAlign:	"center",
			fontSize:	"60px",
			fontWeight:	"bold",
			color:		"#aaa",
			background:	"#fff", 
			opacity:	0.5,
			//textShadow: "0px -1px 1px #fff, 0px 1px 1px #fff, -1px 0px 1px #fff, 1px 0px 1px #fff"
		};

		$(selector).after($('<div class="graph_cover"><i class="fas fa-spinner fa-spin" aria-hidden="true"></i></div>').css(css));
	} else {
		$(selector + ' ~ .graph_cover').remove();
	}
}

/**
 * 오류 표시
 */
function check_graph_data(selector, json, display_alert) {
	if(json.result != "reload") {
		var h = $(selector).height();
		var w = $(selector).width();
		var pos = $(selector).position();
		
		var marginTop = parseInt($(selector).css("margin-top"));
		var marginLeft = parseInt($(selector).css("margin-left"));

		var css = {
			position:	"absolute",
			left:		pos.left + marginLeft,
			top:		pos.top + marginTop,
			height:		h + "px", 
			width:		w + "px",
			textAlign:	"center",
			//background:	"rgba(236,247,248,0.6)",
			background:	"rgba(255,255,255,0.8)",
			opacity:	1,
			fontFamily:	"Nanum Gothic"
		};

		var err_h = (w < 400 ? 40 : 80);
		var err_w = (w < 400 ? 200 : 400);

		var inCss = {
			height:		err_h,
			width:		err_w,
			lineHeight:	"20px",
			//padding:    "0",
			paddingTop:		Math.round((h - err_h) / 2) + "px",
			textAlign:	"center",
			//margin:		Math.round((h - err_h) / 5) + "px auto auto auto",
			margin:		(err_h / 2) + "px auto auto auto",
			display:	"inline-block",
			fontSize:	"13px", 
			fontWeight:	"normal", 
			//boxShadow:	"1px  1px  3px rgba(119,119,119,0.1)",
			background:	"url(/graph/images/bg_nodata.png) no-repeat bottom 90px center",
			color:		"#aaa",
			opacity:	1,
			borderRadius: 2
		};


		var err_msg = "";
		$.each((json.error).split("\n"), function(i, t) {
			err_msg += '<span style="display:inline-block;">' + t + '</span> ';
		});

		// 샘플데이터 알림
		if(json.result == "sample") {
			$(selector).after($('<div class="graph_error"></div>').css(css).append($('<div>' + err_msg + '</div>').css(inCss)));
			if(display_alert == true) alert(json.error);
			return true;
		}
		// 메시지
		else if(json.result == "message" && json.error != "") {
			$(selector).after($('<div class="graph_error"></div>').css(css).append($('<div>' + err_msg + '</div>').css(inCss)));
			if(display_alert == true) alert(json.error);
			return false;
		}
		// 오류
		else if(json.result == "error" || json.result == "afterlogin") {
			inCss.background = "none";
			//inCss.background = "rgba(236,247,248,0.6)";
			$(selector).after($('<div class="graph_error"></div>').css(css).append($('<div>' + err_msg + '</div>').css(inCss)));
			if(display_alert == true) alert(json.error);
			return false;
		}
		
		else {
			return true;
		}
	} else {
		$(selector + " ~ .graph_error").remove();
	}
}

/**
 * 브라우저체크 후 그래프 표시불가 오류 표시
 */
function check_graph_browser(id) {
	if($.browser.msie == true && $.browser.version <= 8) {
		if(!check_graph_data(id, {result:'error', error: "Internet Explorer 9 이상의 환경을 요구합니다."})) return false;
	}
	return true;
}

/**
 * 그래프 데이터 로드
 */
function load_graph(id, create_func, create_after_func, reset_func, display_loading, display_alert) {
	check_graph_data(id, {result:"reload"}, display_alert);
	if(!check_graph_browser(id)) return;

	if(display_loading !== false) loading_graph(id, true);

	var url = $(id + "Frm").attr("action");
	var type = $(id + "Frm").attr("method") == "post" ? "post" : "get";
	var datas = load_graph_param(id);
	$(id).data("graph-url", url);
	$(id).data("graph-data", datas);

	var fullUrl = new Array(url, datas).join(url.indexOf("?") > -1 ? "&" : "?");
	fullUrl = fullUrl.replace(/[&?]*[ ]*$/, "");

	$.ajax({
		"url" : url,
		"type": type,
		"data" : datas,
		"complete" : function() {
			loading_graph(id, false);
		},
		"success" : function(json) {
			if(!check_graph_data(id, json, display_alert)) {
				return;
			}
			var cls = $(id).attr("class") || "";
			cls = $.trim(cls.replace(/_g_[^ ]*/g, ""));
			$(id).attr("class", cls);

			$(id).addClass("_g_" + $(id).data("graph-name"));
			$(id + "_source").addClass("_g_source");
			$(id + "_text").addClass("_g_text");

			$(id).data("data1", null);
			$(id).data("data2", null);$(id).data("data2", null);

			if(json.count <= 0) {
				if(!check_graph_data(id, {result:"message", error: "선택하신 기간에 분석할\n데이터가 없습니다."}, display_alert)) {
					if(reset_func != null) reset_func();
					$(id).trigger("reset");
					return;
				}
			}

			try {
				$(id).trigger("before");
				console.log(json.data);
				var rs = create_func(json.data);
				//console.log(rs);

				if(rs === false && !check_graph_data(id, {result:"message", error: "선택하신 기간에 분석할\n데이터가 없습니다."}, display_alert)) {
					if(reset_func != null) reset_func();
					$(id).trigger("reset");
					return;
				}
				if(create_after_func != null) create_after_func(json.data);
				$(id).trigger("after", [json.data]);

			} catch (e) {
				loading_graph(id, false);
				if(typeof(console) != "undefined") console.log(e);
				if(!check_graph_data(id, {result:"error", error:"그래프를 로드하지\n못하였습니다."}, display_alert)) {
					if(reset_func != null) reset_func();
					$(id).trigger("reset");
					return;
				}
			}
		},
		"error": function(e) {
			loading_graph(id, false);
			if(typeof(console) != "undefined") console.log(e);
			if(!check_graph_data(id, {result:"error", error:"데이터를 가져오지\n못하였습니다."}, display_alert)) {
				if(reset_func != null) reset_func();
				$(id).trigger("reset");
				return;
			}
		},
		"dataType" : "json"
	});
}

function load_graph_param(id) {
	var datas = $(id + "Frm").serialize();
	
	// 사이트 폼 데이터 추가
	if($(id + "Frm").data("autosubmit") == true || $(id + "Frm").data("autosubmit-include") == true) {
		var _datas = $("#sideFrm").serialize();
		datas += (datas != "" && _datas != "" ? "&" : "") + _datas;
	}
	return datas;
}