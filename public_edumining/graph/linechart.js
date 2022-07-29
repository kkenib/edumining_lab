/**
 * 패션 트렌드 그래프
 * d3.js v3.x
 * jsonData	{x: array, data: [array, array, array, ...]}
 * targetId	#chartid
 * options {
		data : names : array(jsonData.data 와 순서 동일)
		axis.x : tick : { count, format }
		axis.y : min, max, label : { text }
		axis.y2 : min, max, label : { text }

 }
 *
 * @date	2017-04-13
 * @author	HYJUNG
 * 개수가 많아질 경우 속도가 느림 - 디자인 제거시 개선의 여지는 있음
 */
var graph_linechart = function(id, options, afterFunc) {
	$(id).data("graph-name", "linechart");

	var _self = this;
	options = options || {};

	// c3 options
	options.data = options.data || {};
	options.data.x = options.data.x || 'x';
	options.data.columns = options.data.columns || [];
	options.axis = options.axis || {};
	options.axis.x = options.axis.x || {};
	options.axis.y = options.axis.y || {};
	options.axis.y2 = options.axis.y2 || {};
	options.axis.y2.show = options.axis.y2.show === true ? true : false;
	options.subchart = options.subchart || {};
	options.subchart.show = options.subchart.show === true ? true : false;
	options.tooltip = options.tooltip || {};
	options.legend = options.legend || {};
	options.date = options.date || {};

	// graph_linechart options
	// 외부에서 데이터 사용시 데이터 세팅해주는 함수
	options.dataset = options.dataset || null; 
	// 최초 보이는 그래프 개수
	options.data.active = options.data.active || 0;
	// 평균선 보이기
	options.data.avgline = options.data.avgline || false;
	
	// 기준선
	options.data.standard = options.data.standard || false;
	
	// 시즌 보이기
	options.season = false;
	options.season.show = options.season.show !== false ? true : false;
		
	options.predictionbackground = options.predictionbackground || {};
	options.predictionbackground.show = options.predictionbackground.show === true ? true : false;
	options.emotionBackground = options.emotionBackground || {};
	options.emotionBackground.show = options.emotionBackground.show === true ? true : false;

	var fn = {},
		targetId = id,
		svg = null,
		chart = null,
		chartTop = 0,
		chartLeft = 0,
		baseYTickCount = 5,
		baseXTickCount = 10,
		changeYTickCount = {"y" : baseYTickCount, "y2" : baseYTickCount},
		changeXTickCount = baseXTickCount,
		legendStep = options.legend.step || 1,
		legendHeight = 0,
		width = $(targetId).width(),
		height = $(targetId).height(),
		average = 0,
		isOnRedraw = false;

	var season = {"SS" : [3, 4, 5, 6, 7, 8], "FW" : [9, 10, 11, 12, 1, 2]};

	var legendId = targetId + "_legend",
		chartId = targetId + "_chart";
		
	var colors = graph_theme.linechart;
	options.color = options.color || {};
	options.color.pattern = options.color.pattern || colors;

	if(options.legend.show == false) {
		options.data.active = 0;
	} 

	var defaultDateFormat = d3.time.format("%Y.%m.%d");
	var locale = {};
	locale.textomi = d3.locale({
      decimal: ".",
      thousands: ",",
      grouping: [3],
      currency: ["\\", ""],
      dateTime: "%Y/%m/%d %a %X",
      date: "%Y/%m/%d",
      time: "%H:%M:%S",
      periods: ["오전", "오후"],
      days: ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"],
      shortDays: ["일", "월", "화", "수", "목", "금", "토"],
      months: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
      shortMonths: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"]
    });

	var dateFormat = locale[theme_type].timeFormat.multi([
			["%H:00",		function(d) { return d.getHours() != 0; }], // 시간 일표시
			["%d",			function(d) { return d.getDate() != 1; }], // 1일제외 일표시
			["%m/%d",		function(d) { return d.getMonth(); }], // 년도 표시
			["%y' %m/%d",	function(d) { return !d.getMonth(); }] // 년도 표시
	]);

	fn.createOptions = function() {
		var _options = {};
		_options.bindto = chartId;

		_options.data = options.data;

		//_options.data.empty = {label: {text: "No Data"}};

		// 선택된 포인트 표시
		_options.data.selection = {
			enabled: true,
			multiple: true,
			grouped: false
		};
		
		//options.data.axis = {data1: 'y'};
		//options.data.onclick = function (d, i) { console.log("onclick", d, i); };
		//options.data.onmouseover = function (d, i) { console.log("onmouseover", d, i); };
		//options.data.onmouseout = function (d, i) { console.log("onmouseout", d, i); };
		//options.axes = { "data2" : "y2" };

		var format = d3.time.format(" %y.%m.%d ");

		_options.line = { connectNull : true },
		_options.point = options.point || { show: true, r: 6, focus : { expand: { r: 6 } }, select : { r: 6 } },
		_options.color = { pattern: options.color.pattern },
		_options.zoom = { enabled: false },
		_options.padding = options.padding || { top: 10 + (options.axis.x.position == "top" ? 14 : 0), right: 15, bottom: 0 },
		_options.grid = { x: { show: false }, y: { show: true } };
		_options.legend = { show: false };
		

		var x = options.axis.x;
		x.label = x.label || { text : "( 단위 : 차수 )" };
		x.label.position = "0";

		x.tick = x.tick || { format: function(x) {
				
				return x;//dateFormat(x);
			},
			fit: false, culling: false, centered: true };

		if(options.axis.x.position == "top") {
			x.label.position = "inner-right";
		}
		_options.axis = {
			x: {
				type: 'timeseries',
				tick: x.tick,
				padding: {left: 0, right: 0},
				label: x.label
			}
		};

		var y = options.axis.y;
		y.label = y.label || { text : "" };
		y.tick = y.tick || { count: function () {
				return changeYTickCount["y"]; 
			}, centered: false };
		y.tick.format = d3.format("0,f");

		_options.axis.y = {
			min : y.min,
			max : y.max,
			tick: y.tick,
			padding: {top: 0, bottom: 0},
			label: { text: y.label.text, position: 'outer-top' }
		};

		// use y2
		if(options.axis.y2.show == true) {
			var y2 = options.axis.y2;
			delete _options.padding.right;

			y2.label = y2.label || { text : "지수" };
			y2.tick = y2.tick || { count: function () {
				return changeYTickCount["y2"];
			}, centered: true };
			y2.tick.format = d3.format("0,f");
			_options.axis.y2 = {
				show: true,
				min : y2.min,
				max : y2.max,
				tick: y2.tick,
				padding: {top: 0, bottom: 0},
				label: { text: y2.label.text, position: 'outer-top' }
			};
		}
		
		var tooltip = options.tooltip;
		tooltip.format = tooltip.format || {};
		_options.tooltip = {
			//grouped: false,
			format: {
				title: tooltip.format.title || defaultDateFormat,
				value: tooltip.format.value
			},
			contents: function (d, defaultTitleFormat, defaultValueFormat, color) {
				var $$ = this, config = $$.config,
					//titleFormat = config.tooltip_format_title || defaultTitleFormat,
					titleFormat = defaultTitleFormat,
					nameFormat = config.tooltip_format_name || function (name) { return name; },
					valueFormat = config.tooltip_format_value || defaultValueFormat,
					text, i, title, value, name, bgcolor;

				var week = ['일', '월', '화', '수', '목', '금', '토'];

				// tootip 'name'에 따른 길이 설정
				var nameLenChk = 'small';
				for (i = 0; i < d.length; i++) {
					name = nameFormat(d[i].name);
					if (name.length > 4 ){
						nameLenChk = '';
					}
				}
				/*
				for (i = 0; i < d.length; i++) {

					name = nameFormat(d[i].name);
					value = valueFormat(d[i].value, d[i].ratio,  d[i].id, d[i].index);
					bgcolor = $$.levelColor ? $$.levelColor(d[i].value) : color(d[i].id);

					if (! (d[i] && (d[i].value || d[i].value === 0))) { continue; }
					if (! text) {
						title = titleFormat ? titleFormat(d[i].x) : d[i].x;
						title = title.split(".")[2] +" 차";
						//if(options.date.show == true) {
							//title += "(" + week[new Date(d[i].x).getDay()] + ")";							
						//}
						text = "<div class='graph_hover " + nameLenChk + " graph_ttip'><strong>" + (title || title === 0 ? title : "")+"</strong>";
					}
						

					text += "<li>";
					text += "<span>" + name					
					text += "</span><em>" + value + "%</em></li>";
				}

				text += "</ul></div>";
				*/

				
				
				/*기존 tooltip layout*/
				for (i = 0; i < d.length; i++) {
					if (! (d[i] && (d[i].value || d[i].value === 0))) { continue; }
					
					if (! text) {
						//title = titleFormat ? titleFormat(d[i].x) : d[i].x;
						//title = title.split(".")[2] +" 차";
						title = titleFormat(d[i].x);
						text = "<div class='c3-tooltip-wrap'><table class='" + $$.CLASS.tooltip + "'>" + (title || title === 0 ? "<tr><th colspan='2'>" + title + "</th></tr>" : "");
					}

					name = nameFormat(d[i].name);
					value = valueFormat(d[i].value, d[i].ratio, d[i].id, d[i].index);
					bgcolor = $$.levelColor ? $$.levelColor(d[i].value) : color(d[i].id);

					text += "<tr class='" + $$.CLASS.tooltipName + "-" + d[i].id + "'>";
					text += "<td class='name'><span style='background-color:" + bgcolor + "'></span>" + name + "</td>";
					text += "<td class='value'>" + value + "%</td>";
					text += "</tr>";
				}

				text += "</table>";
				text += "<div class='c3-tooltip-caret'></div></div>";
				
			
				return text;
			}
		};

		if(options.subchart.show == true) {
			_options.subchart = {
				show: true,
				size: { height: 40 },
				grid: { x: { show: true }, y: { show: false } },
				class: "subchart"
			};
		}

		_options.oninit = function() {
			var innerWidth = d3.select(chartId + ' .c3-zoom-rect').attr('width');
			var innerHeight = d3.select(chartId + ' .c3-zoom-rect').attr('height');
			d3.selectAll(chartId + " > svg > g").attr("class", function(d, i) { 
				return ["chart", "subchart", ""][i];
			});
			

			// X 상단으로 이동
			if(options.axis.x.position == "top") {
				d3.select(chartId + " .chart .c3-chart").append("line").attr("class", "underline").attr('x1', 0).attr('y1', innerHeight).attr('x2', innerWidth).attr('y2', innerHeight);
				var h = parseFloat(d3.select(chartId + ' .chart .underline').style("stroke-width"));
				if(h > 1) {
					d3.select(chartId + ' .chart .underline').attr('y1', innerHeight-h+1).attr('x2', innerWidth).attr('y2', innerHeight-h+1);
				}
			}

			// subchart
			if(options.subchart.show == true) {
				d3.select(chartId + ' .subchart .c3-brush').attr('clip-path', '');
				d3.select(chartId + ' .subchart .c3-axis-x').attr('clip-path', '');

				// resize
				var resize = d3.selectAll(chartId + ' .resize');
				d3.select(chartId + ' .resize.e').append('path').attr('d', 'M0,15A6,6 0 0 1 6,21V24A6,6 0 0 1 0,30ZM2,23V22M4,23V22');
				d3.select(chartId + ' .resize.w').append('path').attr('d', 'M-0,15A6,6 0 0 0 -6,21V24A6,6 0 0 0 -0,30ZM-2,23V22M-4,23V22');
				
				d3.select(chartId + ' .subchart .c3-axis-x').append('line').attr("class", "underline").attr('x1', 0).attr('y1', 2).attr('x2', 0).attr('y2', 2);
			}
		};


		_options.onrendered = function() {
			if(isOnRedraw != true) return;

			var innerWidth = +d3.select(chartId + ' .c3-zoom-rect').attr('width');
			var innerHeight = +d3.select(chartId + ' .c3-zoom-rect').attr('height');

			// x tick count 지정하지 않을시
			//d3.selectAll(chartId + ' .c3-axis-x .tick').filter(function() {
			//	return (d3.select(this).select("text").style("display") == "none");
			//}).remove();

			// X 상단으로
			if(options.axis.x.position == "top") {
				d3.selectAll(chartId + " .chart .c3-axis-x").attr("transform", "translate(0,-24)");
				d3.selectAll(chartId + " .chart .c3-axis-x .domain").remove();
				d3.selectAll(chartId + " .chart .c3-axis-x .tick > line").remove();
				d3.selectAll(chartId + " .chart .c3-axis-x .tick > text").attr("y", 5);
			}

			// 평균선			
			if(options.data.avgline == true) {
				d3.selectAll(chartId + " .grid-average-rect").remove();

				var gline = d3.select(chartId + " .grid-average > line");
				if(gline.node() != null) {
					d3.select(chartId + " .c3-grid").append("rect")
						.attr("class", "grid-average-rect")
						.attr("width", innerWidth)
						.attr("height", innerHeight - gline.attr("y1"))
						.attr("x", 0)
						.attr("y", gline.attr("y1"))
						.attr("fill", "#e5e5e5")
						.style("opacity", 0.5);
				}
			}

			// 시즌 표시
			if(options.season.show == true) {
				d3.selectAll(chartId + " .grid-session-underline").remove();
				//d3.select("g.c3-grid-lines").attr("clip-path", "");

				d3.selectAll(chartId + " .grid-season > text").each(function(d, i) {
					var ln = d3.select(this.previousElementSibling);
					var ln_x1 = parseFloat(ln.attr("x1")) + 25;

					d3.select(this)
						.attr("transform", "")
						.attr("dx", "")
						.attr("dy", "")
						.attr("x", ln_x1)
						.attr("y", options.axis.x.position == "top" ? innerHeight + 13  : 12);
						//.attr("y", options.axis.x.position == "top" ? innerHeight + 13 : -2);
				});
			}

			// subchart
			if(options.subchart.show == true) {
				d3.select(chartId + ' .c3-brush .background').attr('width', innerWidth);
				d3.select(chartId + ' .subchart .underline').attr('x2', innerWidth);

				d3.selectAll(chartId + ' .subchart .c3-axis-x .tick > circle').remove();
				d3.selectAll(chartId + ' .subchart .c3-axis-x .tick').filter(function() {
					return (d3.select(this).select("text").style("display") != "none");
				}).append("circle").attr("r", 4).attr("cx", 0).attr("cy", 2.5);
				
				// 감성분석 긍부정추이 배경
				if(options.emotionBackground.show == true) {
					
					setSentimentBackground(chartId);

					d3.select(window).on('resize', function() {
						setTimeout(function() {
							setSentimentBackground(_chartId);
						}, 100);
					});
				}
			}
			
			// highlight 표시
			// c3-chart-lines 
			//d3.selectAll(chartId + " .c3-chart-lines > .c3-chart-line > .c3-circles > .c3-circle").on("click", function() {
			//	console.log("circle click");
			//});
	
		};

		return _options;
	};

	// 데이터 셋
	fn.dataSet = function(jsonData) {
		var data = {}, 
			columns = [];

		var x = jsonData.x;
		x.unshift("x");
		columns.push(x);
	
		var detail = jsonData.detail || null;
		var dataset = {};
		var axes = {};
		$.each(jsonData.data, function(i, v) {			
			if(detail != null) dataset["data" + (i+1)] = detail[i] || [];
			v.unshift("data" + (i+1));
			columns.push(v);

			// axes에서 y2 설정이 먹히지 않아 주석처리 함
			/*if('y2' in jsonData && jsonData.y2 != null && $.inArray(i, jsonData.y2) > -1) {
				axes["data" + (i+1)] = "y2";
			} else {
				axes["data" + (i+1)] = "y";
			}*/
		});
		
		if(options.dataset != null) options.dataset(dataset);
		data.columns = columns;
	
		if('name' in jsonData) {
			var names = {};
			$.each(jsonData.name, function(i, v) {
				names["data" + (i+1)] = v;
			});
			data.names = names;
		}

		if('frequency' in jsonData) {
			var frequencies = {};
			$.each(jsonData.frequency, function(i, v) {
				frequencies["data" + (i+1)] = v;
			});
			data.frequencies = frequencies;
		}
			
		// 삭제
		data.unload = [];		
		$.each(chart.data.names(), function(k, v) {
			if(!(k in data.names)) {
				data.unload.push(k);
				data.names[k] = null;
			}
		});
		

		chart.data.axes(axes);
		chart.load(data);
		chart.unzoom();
	};

	// 옵션 변경
	fn.optionSet = function(jsonData) {
		// x값 설정
		changeXTickCount = jsonData.x.length < baseXTickCount ? 0 : baseXTickCount;

		// y값 설정
		var minmax = {"y" : {}};

		if(options.axis.y2.show == true) {
			minmax.y2 = {};
		}

		if('max' in jsonData) {
			var max = jsonData.max;
			if(typeof(max) != "object") {
				minmax.y.max = max;
			} else {
				minmax.y.max = max.y;
				minmax.y2.max = max.y2;
			}
		}
		
		if('min' in jsonData) {
			var min = jsonData.min;
			if(typeof(min) != "object") {
				minmax.y.min = min;
			} else {
				minmax.y.min = min.y;
				minmax.y2.min = min.y2;
			}
		}
		
		$.each(minmax, function(ycol, m) {
			fn._setYAxisRange(ycol, m.min, m.max);
		});

		// 범례 표시
		if(options.legend.show != false) {
			fn._customLegend();
		}

		// 활성화 개수 지정
		if(options.data.active > 0) {
			var names = chart.data.names();

			var i = 0;
			var hideItem = [];
			var showItem = [];
			$.each(names, function(k, v) {
				if(v == null) return;

				if(i >= options.data.active) {
					hideItem.push(k);
					fn._customLegendToggle(k);
				} else {
					showItem.push(k);
				}
				i++;
			});

			if(showItem.length > 0) chart.show(showItem);
			if(hideItem.length > 0) chart.hide(hideItem);
		} else {
			chart.show();
		}

		if(options.data.avgline == true) {
			var datas = chart.data();
			var axes = chart.data.axes();
			var _sum = 0, _len = 0;
			datas.forEach(function(data, k) {
				if(axes == null || !(data.id in axes) || axes[data.id] == 'y') {
					_sum += d3.sum(data.values, function(d) {return d.value;});
					_len += data.values.length;
				}
			});
			chart.ygrids([{axis: 'y', value: (_sum / _len), text: 'Average', position: 'end', class: "grid-average"}]);
		}
		
		// 기준선
		if(options.data.standard.show == true) {
			var standard = options.data.standard;
			chart.ygrids([{axis: 'y', value: standard.value, text: standard.text, position: 'end', class: "grid-standard"}]);
		}
		
		// 불호황지수 예측 데이터부분 배경처리
		if(options.predictionbackground.show == true) {
			
			// legend 추가
			var predict_legend = d3.select(legendId);
			var predict_span = predict_legend.append('span')
				.attr('class', 'predict_area bar');
			predict_span.text('예측구간');
			
			// 기준선 - 점선 표시
			var grid_season = [];
			var datas = chart.data();
			var x = chart.x()["data1"];		
			var year = x[x.length - 4].getFullYear();
			var month = x[x.length - 4].getMonth() + 1;
			
			var currentMonthValue = chart.data()[0]['values'][x.length - 4]['value'];
			var nextMonthValue = chart.data()[0]['values'][x.length - 3]['value'];			
			var currentMonth = (x[x.length - 4].getFullYear()) + "." + (x[x.length - 4].getMonth() + 1);
			var nextMonth = (x[x.length - 3].getFullYear()) + "." + (x[x.length - 3].getMonth() + 1);
			
			setMonthlyComparison(".perctbox", currentMonth, currentMonthValue, 0);
			setMonthlyComparison(".perctbox", nextMonth, nextMonthValue, 1);
			
			grid_season.push({axis: "x", value: new Date(year + "/"+month+"/01"), text: "", position: (options.axis.x.position == "top" ? "start" : "end"), class: "grid-season"});
			chart.xgrids(grid_season);
			
			setPredictionbackground(chartId, 4);
			d3.select(window).on('resize', function() {
				setTimeout(function() {
					setPredictionbackground(chartId, 4);
				}, 100);
			});
		}
		
		// 감성분석 긍부정추이 배경
		if(options.emotionBackground.show == true) {
			
			setSentimentBackground(chartId);

			d3.select(window).on('resize', function() {
				setTimeout(function() {
					setSentimentBackground(_chartId);
				}, 100);
			});
		}
	};

	// 범례 커스터마이징
	fn._customLegend = function() {
		var names = chart.data.names();

		var _names = [];
		$.each(names, function(k, v) {
			if(v == null) return;
			_names.push({id: k, name: v});
		});
	
		var w = Math.ceil(100 / (names.length / legendStep));

		d3.select(legendId).selectAll('span').remove();
		var a = d3.select(legendId).selectAll('.span')
			.data(_names);
		a.enter().append('span')
			.attr("class", function(d) { return "leg_" + d.id; })
			//.html(function (d) { return "<span><i></i></span>" + d.name; }) //line+circle
			.html(function (d) { return "<span></span>" + d.name; })//circle
			.style("width", w + "%")
			.each(function (d) {
				d3.select(this)
					.select("span")
						.style("background-color", chart.color(d.id))
						
						//.select("i")
							//.style("background-color", chart.color(d.id))
			})
			.on("mouseover", function (d) {
				chart.focus(d.id);
			})
			.on("mouseout", function (d) {
				chart.revert();
			})
			.on("click", function (d) {
				chart.toggle(d.id);
				fn._customLegendToggle(d.id);
			});
		a.exit().remove();

		legendHeight = $(legendId).outerHeight();
		height = $(targetId).height() - legendHeight;
		$(chartId).css({height : height, maxHeight: height});
	};

	// 범례 토글
	fn._customLegendToggle = function(id) {
		
		var span = d3.select(legendId).select("span.leg_" + id);
		var hidden = span.attr("data-hidden");
		span.classed('c3-legend-item-hidden', hidden != "true");
		span.attr("data-hidden", hidden != "true");
		
		// 범례 히든시 이미지 border 처리
		if(hidden != "true") {
			d3.select(legendId).select("span.leg_" + id).select("span").style("background-color", "");
			d3.select(legendId).select("span.leg_" + id).select("span").style("border", "0px solid " + chart.color(id));
		} else {
			d3.select(legendId).select("span.leg_" + id).select("span").style("border", "");
			d3.select(legendId).select("span.leg_" + id).select("span").style("background-color", chart.color(id));
		}
	};

	// 범례 상태값
	fn._getLegendStat = function(id) {
		return d3.select(legendId).select("span.leg_" + id).attr("data-hidden");
	};


	// Y값 근사값으로 변경 
	fn._setYAxisRange = function(ycol, imin, imax) {
		var tick = baseYTickCount,
			max = null,
			min = null,
			range = {"min" : {}, "max" : {}},
			datas = chart.data(),
			axes = chart.data.axes();

		min = imin;
		max = imax;

		/* 
		// 실제 min/max값 찾기
		datas.forEach(function(data, k) {
			if((ycol == 'y' && (axes == null || !(data.id in axes))) || axes[data.id] == ycol) {
				var _max = d3.max(data.values, function(d) {return d.value;});
				if(max == null || _max > max) max = _max;

				var _min = d3.min(data.values, function(d) {return d.value;});
				if(min == null || _min < min) min = _min;
			}
		});

		if(imin == null) {
			if(min > 0 && max / min < 1.5) {
				var dv = Math.pow(10, ("" + Math.floor(min)).length - 1) / 10;
				min = Math.floor(d / dv) * dv;
			} else if(min == null || min > 0) {
				min = 0;
			}
		} else {
			min = Math.floor(imin);
		}

		max = (imax != null && imax > max ? imax : max) - min;
		*/

		if(min != null) range.min[ycol] = min;
		if(max != null) {
			var dv = Math.pow(10, ("" + Math.ceil(max)).length-1) / 10;
			dv = (dv == 1 ? 5 : dv);

			var cnt = [baseYTickCount, baseYTickCount+1, baseYTickCount-1];

			// TODO : 임시(item pos데이터 때문에)
			// y2가 0 이하일 경우 y의 tick와 위치가 어긋남
			// y의 tick에 맞춰서 강제 지정
			if(ycol == "y2") cnt = [min < 0 ? changeYTickCount["y"]-1 : changeYTickCount["y"]];

			var max2 = null;
			var tick2 = 0;
			var dv2 = null;
			for(var i = 0; i < cnt.length; i++) {
				var _cnt = cnt[i] - 1;
				var _max = Math.ceil(max / _cnt / dv) * dv * _cnt;
				var _dv = _max / _cnt;
				if(max2 == null || max2 >= _max && (dv2 == null || dv2 > _dv)) {
					tick2 = cnt[i];
					max2 = _max;
					dv2 = _dv;
				}
			}
			max = max2;
			tick = tick2;

			changeYTickCount[ycol] = tick;
			if(ycol == "y2" && min < 0) {
				min = Math.round(max / (tick - 1)) * -1;
				changeYTickCount[ycol] = tick + 1;
			}

			range.min[ycol] = min;
			range.max[ycol] = max;
		}

		chart.axis.range(range);
	};

	fn.reset = function() {
		isOnRedraw = false;

		chart.unload();
		chart.ygrids.remove();
		
		d3.select(chartId + " .grid-average > line").remove();
		d3.select(chartId + " .grid-average-rect").remove();
		
		$(legendId).children().remove();
		isOnRedraw = true;
	};


	// 시작
	!(function() {
		$(targetId).empty();

		if(options.legend.show != false) {
			$(targetId).append('<div id="' + legendId.substring(1) + '" class="lgd_line"></div>');
		}
		
		$(targetId).append('<div id="' + chartId.substring(1) + '"></div>');		
		$(chartId).height(height);
		$(id).addClass("lc_sty1");
		
		var option = fn.createOptions();
		chart = c3.generate(option);
		svg = d3.select(chartId).select("svg");		
		svg.on("reload", function() {
				load_graph(targetId, function(jsonData) {
					if(jsonData.x == null || jsonData.x.length == 0 
						|| jsonData.data == null || jsonData.data.length == 0) return false;

					isOnRedraw = false;
					fn.dataSet(jsonData);
					fn.optionSet(jsonData);
					isOnRedraw = true;
					
				}, afterFunc, function() { fn.reset(); });
			})
			.on("getChart", function() {
				return chart;
			})
			.on("reset", function() {
				fn.reset();
			});

		svg.on("reload")();	

	})();

	this.targetId = targetId;
	this.chartId = chartId;
};

graph_linechart.getChart = function(targetId) {
	var svg = d3.select(targetId + "_chart").select("svg");
	svg.on("getChart")();

};

graph_linechart.prototype.getChart = function() {
	var svg = d3.select(this.chartId).select("svg");
	return svg.on("getChart")();
};

graph_linechart.reset = function(targetId) {
	var svg = d3.select(targetId).select("svg");
	svg.on("reset")();
};

graph_linechart.prototype.reset = function() {
	var svg = d3.select(this.targetId).select("svg");
	svg.on("reset")();
};

graph_linechart.reload = function(targetId) {
	var svg = d3.select(targetId + "_chart").select("svg");
	svg.on("reload")();
};

graph_linechart.prototype.reload = function() {
	var svg = d3.select(this.chartId).select("svg");
	svg.on("reload")();
};