/** 예측 모델 그래프 **/

// document.write("<script src='https://d3js.org/d3-queue.v3.min.js'></script>");

var graph_keyword_bubble = function(id) {
	var url = "";
	var action = $(id+"Frm").attr("action");
	var graphName = $(id+"Frm input[name='graph']").val();
	var startDate = $(id+"Frm input[name='startDate']").val();
	var endDate = $(id+"Frm input[name='endDate']").val();
	var categoryCd = $(id+"Frm input[name='categoryCd']").val();
	var dateType = $(id+"Frm input[name='dateType']").val();
	
	url = (action.split("?").length == 1)? action + "?graph=" +graphName+ "&startDate=" +startDate+ "&endDate=" +endDate+ "&categoryCd=" +categoryCd+ "&dateType=" +dateType : action;
	
	$(id).data("graph-name", "keyword_bubble");
	$(id).data("graphUrl", url);
	$(id).data("graphData", "");
	$(id).data("graph-url", url);
	$(id).data("graph-data", "");
	$(id).attr("graph-properties", "");

	var dataUrl1 = url;
	var callIdx = 0;

	var index = 0;

	var category = null;
	var cycle = null;
	
	var margin = {
		top: 75,
		right: 100,
		bottom: 70,
		left: 180
	};

	var div = d3.select("body").append("div");
		// tootip 삭제
		//.attr("class", "tooltip")
		//.style("opacity", 0);
				
	var width = $(id).width() - margin.left - margin.right,
		height = 700 - margin.top - margin.bottom;
	
	var svg = d3.select(id).append("svg")
		.attr("width", width + margin.left + margin.right)
		.attr("height", height + margin.top + margin.bottom)
		.append("g")
		.attr("transform", "translate(" + margin.left + "," + margin.top + ")");

	var formatThousands = function(d) {
		//var name = ["1주", "2주", "3주", "4주", "5주"];
		var name = cycle;
		return name[d / 20];
	};

	var formatRatio = function(d) {
		var name = category;
		return name[(name.length - 1) - index++];
	}

	var uniLookup = {};

	var xAccessor = function(d, x) {
		return x(d.x);
	};

	var y1Accessor = function(d, y) {
		return y(d.y);
	};

	var y2Accessor = function(d, y) {
		return y(d.y);
	};

	var r1Accessor = function(d, r) {
		//return r(d.over);		
		return r(d.value);		
	}

	var xScale = d3.scale.linear(),
		y1Scale = d3.scale.linear(),
		r1Scale = d3.scale.sqrt();
	var y2Scale = d3.scale.linear();
	var config1 = {
		top: 0,
		left: 0,
		width: width,
		height: height,
		labelsToShow: [],
		dotClassName: "dot1",
		tickSize: 0,
		domainOpacity: 0,
		xAxisOffset: 0,
		yAxisOffset: 10,
		xTicks: 5,
		yTicks: 10
	};
	
	//console.log(dataUrl1);
	
	  d3.queue()
	    .defer(d3.json, dataUrl1)
	    .await(load);

	// When all data has loaded draw the scatter plot(s)
	function load(error, data1) {
			var over140 = data1.data;
			category = data1.category;
			cycle = data1.week;
			config1.labelsToShow = category;
			over140.forEach(function(d) { return uniLookup[d.name]; });

			// x 범위
			xScale.domain([0, 100]);
			y1Scale.domain([0, 45]);
			
			r1Scale.domain(d3.extent(over140, function(d) { return d.value; })).range([15, 42]);
			//r1Scale.domain(d3.extent(over140, function(d) { return d.over; })).range([15, 42]);
			y2Scale.domain(d3.extent(over140, function(d) { return d.max; }));
			
			load_graph(id, function(data1) {
				scatterPlot(over140, config1, xScale, y1Scale, r1Scale, xAccessor, y1Accessor, r1Accessor, formatThousands, formatRatio);
			});
	}

	// Draws a scatter plot
	function scatterPlot(data, cfg, x, y, r, xAcc, yAcc, rAcc, xFormat, yFormat) {
		var plot = svg.append("g")
			.attr("class", "scatter-plot")
			.attr("transform", "translate(" + [cfg.left, cfg.top] + ")");
		plot.append("rect")
			.attr("class", "chart-background")
			.attr("width", cfg.width)
			.attr("height", cfg.height);
		x.range([0, cfg.width]);
		y.range([cfg.height, 0]);

		var xAxis = d3.svg.axis().scale(x.nice()).orient("bottom").ticks(5)
			.tickSize(cfg.tickSize)
			.tickFormat(xFormat);

		var xAxisGroup = plot.append("g")
			//.attr("transform", "translate(" + [-77, cfg.height + cfg.xAxisOffset + 40] + ")")
			.attr("transform", "translate(" + [cfg.width / 10, -63] + ")")
			.attr("class", "xLabel")
			.call(xAxis);
		xAxisGroup.select(".domain").style("opacity", cfg.domainOpacity);
		
		var xLabel = plot.append("g")
			.append("text")
			.attr("class", "axis-label")
			.attr("text-anchor", "middle")
			.attr("transform", "translate(" + [cfg.width, cfg.height + 60] + ")")
			.text(cfg.xLabel);
		
		var yAxis = d3.svg.axis().scale(y.nice()).orient("left")
			.ticks(cfg.yTicks)
			.tickSize(cfg.tickSize)
			.tickFormat(yFormat);
		
		var yAxisGroup = plot.append("g")
			.attr("transform", "translate(" + [-cfg.yAxisOffset, 0] + ")")
			.attr("class", "yLabel")
			.call(yAxis);
		
		yAxisGroup.select(".domain").style("opacity", cfg.domainOpacity);
		var title = plot.append("g")
			.attr("transform", "translate(" + [cfg.width, -50] + ") ")
		title.append("text")
			.attr("class", "title")
			.attr("text-anchor", "middle")
			.text(cfg.title)
		title.append("text")
			.attr("class", "sub-title")
			.attr("y", 10)
			.attr("text-anchor", "middle")
			.text(cfg.subtitle);
			
		var gridLinesX = plot.append("g")
			.selectAll("line")
			.data(x.ticks(cfg.xTicks))
			.enter().append("line")
			.attr("class", "grid-line")
			.attr("x1", function(d) { return x(d); })
			.attr("y1", 0)
			.attr("x2", function(d) { return x(d); })
			.attr("y2", function(d) { return cfg.height; });
		
		var gridLinesY = plot.append("g")
			.selectAll("line")
			.data(y.ticks(cfg.yTicks))
			.enter().append("line")
			.attr("class", "grid-line")
			.attr("x1", 0)
			.attr("y1", function(d) { return y(d); })
			.attr("x2", function(d) { return cfg.width; })
			.attr("y2", function(d) { return y(d); });
		
		var circle_color = ["#F08CA4","#AEABD4","#88D2DB","#FFCD28","#F19D86","#99CB60","#92B6E0","#CD9DC8","#7BC8A8","#9BCFF0"];
		
		var class_name = ["dot1", "dot2", "dot3", "dot4", "dot5"];

		var dots = plot.append("g")
			.selectAll("circle")
			.data(data)
			.enter().append("circle")
			.attr("class", function(d, i) { return "dot" + (Math.floor(i / 5) + 1); })
			.attr("id", function(d, i) { return d.id = "c" + i; })
			.attr("cx", function(d) { return xAcc(d, x); })
			.attr("cy", function(d) { return yAcc(d, y); })
			.attr("r", function(d) { return rAcc(d, r); })
			.style("fill", function(d, i) {				
				return circle_color[Math.floor(i / 5)];
			})
			.on("mouseover", function(d, i) {
				
				var circle_idx = Math.floor(i / 5) + 1;
				d3.selectAll("circle.dot"+circle_idx)
					//.raise()
					.transition()
					.style("stroke-width", 1)
					.style("opacity", 0.7);

				d3.selectAll("text.dot-label"+circle_idx)
				.style('fill', "#000000")
				.style("opacity", 1);
				//showLabel(d);
			})
			.on("mousemove", moveLabel)
			.on("mouseout", function(d, i) {
				var circle_idx = Math.floor(i / 5) + 1;
				d3.selectAll("circle.dot"+circle_idx)	
				  .transition()
				  .style("stroke-width", 1)
				  .style("opacity", 0.4);

				d3.selectAll("text.dot-label"+circle_idx)
				  .style('fill', function(d, i) {
					return circle_color[Math.floor(9 - (d.y / 5))];
				})
				.style("opacity", 0.7);
				//hideLabel();
			});
		
		var labels = plot.append("g")
			.selectAll("text")
			.data(data.filter(function(a) {
				return (cfg.labelsToShow.indexOf(a.name) !== -1)? true : false;
				//freturn a.name;
			}))
			.enter().append("text")
			.attr("class", function(d, i) { return ("dot-label" + (Math.floor(i / 5) + 1)); })
			.attr("x", function(d) { return xAcc(d, x); })
			.attr("y", function(d) { return yAcc(d, y); })
			.attr("text-anchor", "middle")
			.attr("dy", function(d) { return -(rAcc(d, r) / 100); })
			.style('fill', function(d, i) { return circle_color[Math.floor(i / 5)]; })
			//.text(function(d) { return d.over.toFixed(1); });
			.text(function(d) { return d.value; });
	}

	function showLabel(d) {
		var coords = [d3.event.clientX, d3.event.clientY];
		var current_scroll_top = $(document).scrollTop();
		var top = (coords[1] - 50) + current_scroll_top,
			left = coords[0] - 50;
		div.transition()
			.duration(200)
			.style("opacity", 1);
		div.html("<b>" + d.name + "</b></br>" +
				"예측값: " + d.value)
			.style("top", top + "px")
			.style("left", left + "px");
	}

	function moveLabel() {
		var coords = [d3.event.clientX, d3.event.clientY];
		var current_scroll_top = $(document).scrollTop();
		var top = (coords[1] - 50) + current_scroll_top,
			left = coords[0] - 50;
		div.style("top", top + "px")
			.style("left", left + "px");
	}

	function hideLabel(d) {
		div.transition()
			.duration(200)
			.style("opacity", 0);
	}
};
