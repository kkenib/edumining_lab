/**
 * 바 그래프
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

var graph_barchart = function(id, options, afterFunc) {
	$(id).data("graph-name", "barchart");

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
	options.axis.y2.show = options.axis.y2.show || false;
	options.tooltip = options.tooltip || {};
	options.legend = options.legend || {};
	options.legend.subData = options.legend.subData || false;
	options.date = options.date || {};
	
	// graph_barchart options
	// 외부에서 데이터 사용시 데이터 세팅해주는 함수
	options.dataset = options.dataset || null; 
	// 최초 보이는 그래프 개수
	options.data.active = options.data.active || 0;
	
	// 평균선 보이기
	options.data.avgline = options.data.avgline || false;
	// 기준선
	options.data.standard = options.data.standard || false;
	
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

	var legendId = targetId + "_legend",
		chartId = targetId + "_chart";
		
	var colors = graph_theme.barchart;
	options.color = options.color || {};
	options.color.point = options.color.point || {};
	options.color.pattern = options.color.pattern || colors;

	if(options.legend.show == false) {
		options.data.active = 0;
	} 

	fn.createOptions = function() {
		var _options = {};
		_options.bindto = chartId;

		_options.data = options.data;
		_options.data.type = "bar";
		_options.data.empty = {label: {text: "No Data"}};
		//_options.data.groups = [['data1', 'data2']];
		_options.data.groups = options.data.groups || [];
		_options.bar = { width: 30 };
		//_options.bar = { width: { ratio: 0.4 } };
		
		//options.data.axis = {data1: 'y'};
		//options.data.onclick = function (d, i) { console.log("onclick", d, i); };
		//options.data.onmouseover = function (d, i) { console.log("onmouseover", d, i); };
		//options.data.onmouseout = function (d, i) { console.log("onmouseout", d, i); };
		//options.axes = { "data2" : "y2" };

		_options.color = { pattern: options.color.pattern },
		_options.zoom = { enabled: false },
		_options.padding = options.padding || { top: 20 + (options.axis.x.position == "top" ? 24 : 0), right: 70, bottom: 0 },
		_options.grid = { x: { show: false }, y: { show: true } };
		_options.legend = { show: false };
			
		var x = options.axis.x;

		x.label = x.label || { text : "" };
		x.label.position = "0";
	
		_options.axis = {
			rotated : options.rotated,
			x: {
				type: 'categorized',
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
		y.tick.format = y.tick.format || d3.format("0,f");
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
		options.tooltip.isMonitoring = options.tooltip.isMonitoring || false;
		var tooltip = options.tooltip;
		tooltip.format = tooltip.format || {};
		_options.tooltip = {
			format: {
				value: tooltip.format.value
			},
			contents: function (d, defaultTitleFormat, defaultValueFormat, color) {
				var $$ = this, config = $$.config,
					titleFormat = config.tooltip_format_title || defaultTitleFormat,
					nameFormat = config.tooltip_format_name || function (name) { return name; },
					valueFormat = config.tooltip_format_value || defaultValueFormat,
					text, i, title, value, name, bgcolor;
				
				/* 기존 tooltip layout
				for (i = 0; i < d.length; i++) {
					if (! (d[i] && (d[i].value || d[i].value === 0))) { continue; }

					if (! text) {
						title = titleFormat ? titleFormat(d[i].x) : d[i].x;
						text = "<div class='c3-tooltip-wrap'><table class='" + $$.CLASS.tooltip + "'>" + (title || title === 0 ? "<tr><th colspan='2'>" + title + "</th></tr>" : "");
					}
					
					if(options.tooltip.isMonitoring) {
						name = title;
					} else {
						name = nameFormat(d[i].name);	
					}
					value = valueFormat(d[i].value, d[i].ratio, d[i].id, d[i].index);
					bgcolor = $$.levelColor ? $$.levelColor(d[i].value) : color(d[i].id);

					text += "<tr class='" + $$.CLASS.tooltipName + "-" + d[i].id + "'>";
					text += "<td class='name'><span style='background-color:" + bgcolor + "'></span>" + name + "</td>";
					text += "<td class='value'>" + value + "</td>";
					text += "</tr>";
				}
				
				text += "</table>";
				text += "<div class='c3-tooltip-caret'></div></div>"; */
					
					for (i = 0; i < d.length; i++) {
						name = nameFormat(d[i].name);
						value = valueFormat(d[i].value, d[i].ratio,  d[i].id, d[i].index);

						if (! (d[i] && (d[i].value || d[i].value === 0))) { continue; }
						if (! text) {
							title = titleFormat ? titleFormat(d[i].x) : d[i].x
							if(options.date.show == true) {
								var parse = title.split('/');
								var date = new Date(parse[0], parse[1]-1, parse[2]);
								titleFormat = d3.time.format("%Y.%m.%d");
								title = titleFormat(date);
							}
							bgcolor = $$.levelColor ? $$.levelColor(d[i].value) : color(d[i].id);
							
							if(options.legend.subData == true) {		// tooltip에 값외의 다른 데이터 입력 필요시 data.name에 데이터를 추가해서 사용 (legend: {show: false} 필요)
								text = "<div class='graph_hover graph_ttip'><ul class='hover_lgd point" + d[i].index + "'>";
								text += "<li><strong>" + title + "</strong></li>";
								text += "<li style='float: left;'>" + value + "억원&nbsp</li><li>(" + name[d[i].index][0] + "%)</li>";
								
								if(name[d[i].index][1] < 0){
									class_arrow = 'fas fa-arrow-down';
								} else {
									class_arrow = 'fas fa-arrow-up';
								}
								text += "<li>전주 대비 : <i class='" + class_arrow + "'></i> " + name[d[i].index][1] + "%</li>"
								text += "</ul></div>";
								
								return text;
							}
							
							if(d.length == 1) {			// 데이터가 하나일 경우
								if(options.color.point == true){
									text = "<div class='graph_hover graph_ttip'><ul class='hover_lgd point" + d[i].index + "'>";		// 특정 막대 포인트컬러
								} else {	
									// 막대마다 색깔 다르게
									text = "<div class='graph_hover graph_ttip'><strong>" + (name == "data1" ? "" : name)+"</strong><ul class='hover_lgd color" + d[i].index + "'>";		
								}
								name = title;		// title(d.x)과 name 바뀜
							} else if(d.length>= 5){
								text = "<div class='graph_hover graph_ttip column2'><strong>" + (title || title === 0 ? title : "")+"</strong>";
								text += "<ul class='hover_lgd'>";
							} else {
								text = "<div class='graph_hover graph_ttip'><strong>" + (title || title === 0 ? title : "")+"</strong>";
								text += "<ul class='hover_lgd'>";
							}
						}
									
						bgcolor = $$.levelColor ? $$.levelColor(d[i].value) : color(d[i].id);
									
						text += "<li><span>" + name + "</span>";
								
						if (config.tooltip_format_value) {
							text +=	"";
						}
						if((name == "긍정") || (name == "부정")) {
							text += "<em>" + value + "%</em></li>";
						} else if(name.substr(-1) == '률'){
							text += "<em>" + d[i].value + "%</em></li>";
						} else {
							text += "<em>" + value + "</em></li>";
						}
					}

					text += "</ul></div>";	
					
				return text;
			}
		};

		_options.oninit = function() {
			var innerWidth = d3.select(chartId + ' .c3-zoom-rect').attr('width');
			var innerHeight = d3.select(chartId + ' .c3-zoom-rect').attr('height');
		};


		_options.onrendered = function() {
			if(isOnRedraw != true) return;
			
			var innerWidth = +d3.select(chartId + ' .c3-zoom-rect').attr('width');
			var innerHeight = +d3.select(chartId + ' .c3-zoom-rect').attr('height');

			// x tick count 지정하지 않을시
			//d3.selectAll(chartId + ' .c3-axis-x .tick').filter(function() {
			//	return (d3.select(this).select("text").style("display") == "none");
			//}).remove();

			// 평균선			
			if(options.data.avgline == true) {
				d3.selectAll(chartId + " .grid-average-rect").remove();

				var gline = d3.select(chartId + " .grid-average > line");
				if(gline.node() != null) {
					d3.select(".c3-grid").append("rect")
						.attr("class", "grid-average-rect")
						.attr("width", innerWidth)
						.attr("height", innerHeight - gline.attr("y1"))
						.attr("x", 0)
						.attr("y", gline.attr("y1"))
						.attr("fill", "#e5e5e5")
						.style("opacity", 0.5);
				}
			}
			
			// color == true일 경우 bar마다 색깔이 바뀜
			if(options.color == true){
				var colorBar = $(chartId + '> svg > g:nth-child(2) > g.c3-chart > g.c3-chart-bars > g > g > path.c3-bar');
				
				var colors = ["#f18da5", "#acace4", "#85d9e0"," #f4ba00", "#f29d87", "#99cf60", "#8db7f1", "#d79dde", "#78ceab", "#95d3ff"];
				for(var i = 0; i<colorBar.length; i++) {
					colorBar.eq(i).attr("style", "stroke: " + colors[i % 10] + "; fill: " + colors[i % 10] + "; opacity: 1;");
				}
			} else if(options.color.point == true){
				var colorBar = $(chartId + '> svg > g:nth-child(2) > g.c3-chart > g.c3-chart-bars > g > g > path.c3-bar');
				
				var colors = ["#f18da5", "#acace4"];
				for(var i = 0; i<colorBar.length; i++) {
					color = i == 0 ? colors[0]:colors[1];
					colorBar.eq(i).attr("style", "stroke: " + color + "; fill: " + color + "; opacity: 1;");
				}
			}
			
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
		$.each(jsonData.data, function(i, v) {
			if(detail != null) dataset["data" + (i+1)] = detail[i] || [];
			v.unshift("data" + (i+1));
			columns.push(v);
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

		// 삭제
		data.unload = [];		
		$.each(chart.data.names(), function(k, v) {
			if(!(k in data.names)) {
				data.unload.push(k);
				data.names[k] = null;
			}
		});
		
		chart.load(data);
		//chart.groups([['data1', 'data2', 'data3']]);
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

		if('min' in jsonData) {
			var min = jsonData.min;
			if(typeof(min) != "object") {
				minmax.y.min = min;
			} else {
				minmax.y.min = min.y;
				minmax.y2.min = min.y2;
			}
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
		
		// x축 날짜 설정
		setTimeout(function() {
			if(options.date.show == true) {
				d3.selectAll(chartId + ' .c3-axis-x .tick > text > tspan').filter(function() {		
					var parse = $(this).text().substr(5)
					$(this).empty();
					$(this).text(parse);
				});
			}
		}, 100);
		
		d3.select(window).on('resize', function() {
			setTimeout(function() {
				if(options.date.show == true) {
					d3.selectAll(chartId + ' .c3-axis-x .tick > text > tspan').filter(function() {		
						var parse = $(this).text().substr(5)
						$(this).empty();
						$(this).text(parse);
					});
				}
			}, 100);
		});
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
			//.html(function (d) { return "<span><i></i></span>" + d.name; })
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
		if(max != null) {
			var dv = Math.pow(10, ("" + Math.ceil(max)).length-1) / 10;

			var cnt = [baseYTickCount, baseYTickCount+1, baseYTickCount-1];
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

			range.min[ycol] = min;
			range.max[ycol] = max + min;
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
			//$(targetId).append('<div id="' + legendId.substring(1) + '" class="c3-custom-legend"></div>');
			$(targetId).append('<div id="' + legendId.substring(1) + '" class="lgd_line"></div>');			// legend icon change circle
		}
		$(targetId).append('<div id="' + chartId.substring(1) + '"></div>');
		$(chartId).height(height);

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

graph_barchart.getChart = function(targetId) {
	var svg = d3.select(targetId + "_chart").select("svg");
	svg.on("getChart")();
};

graph_barchart.prototype.getChart = function() {
	var svg = d3.select(this.chartId).select("svg");
	return svg.on("getChart")();
};

graph_barchart.reset = function(targetId) {
	var svg = d3.select(targetId).select("svg");
	svg.on("reset")();
};

graph_barchart.prototype.reset = function() {
	var svg = d3.select(this.targetId).select("svg");
	svg.on("reset")();
};

graph_barchart.reload = function(targetId) {
	var svg = d3.select(targetId + "_chart").select("svg");
	svg.on("reload")();
};

graph_barchart.prototype.reload = function() {
	var svg = d3.select(this.chartId).select("svg");
	svg.on("reload")();
};