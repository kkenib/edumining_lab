/**
 * 평판 차트
 * d3.js v3.x
 * jsonData	{title: 8seconds, image: 이미지경로, data : [{value : 70, label: 80%, textlist: ["좋아요", "배송이 빨라요", "재질이 부드러워요"]}] 
 * sort : positive, neutral, negative
 * targetId	#chartid
 *
 * @date	2017-04-20
 * @author	HYJUNG
 */
function graph_pie(id, options, afterFunc) {
	$(id).data("graph-name", "pie");

	options = options || {};
	options.data = options.data || {};
	options.legend = options.legend || {};
	//options.dataset = options.dataset || null; 
	
	options.legend.show = typeof options.legend.show === 'undefined' ? true : options.legend.show;
	options.legend.clickable = typeof options.legend.clickable === 'undefined' ? false : options.legend.clickable;
	
	var fn = {},
		targetId = id,
		svg = null,
		arc = null,
		pie = null,
		btns = null,
		width = $(targetId).width(),
		height = $(targetId).height(),
		extraInRadius = 0,
		extraOutRadius = 0,
		outRadius = Math.round(width/2 * 0.55 < height * 0.35) ? width/2 * 0.55 : height * 0.35,
		inRadius = Math.round(outRadius * 0.52),
		//inRadius = 0,
		
		scale = outRadius / 270,
		textRadius = outRadius + 20 * scale;
	
	var colors = graph_theme.pie.background;
	var fcolors = graph_theme.pie.font;

	fn.create = function() {
		var centerX = (width/2);
		var centerY = height * 0.5;
		
		// 범례 true일 경우 X좌표 조정
		if(options.legend.show == true) {
			centerX = Math.round(width/2 * 0.9);
		}
		
		// Pie
		svg.append("g")
			.attr("class", "arcs")
			.attr("transform", "translate(" + centerX + "," + centerY  + ")");

		// Background
		svg.append("g")
			.attr("transform", "translate(" + centerX + "," + centerY  + ")");

		// Label
		svg.append("g")
			.attr("class", "labels")
			.attr("transform", "translate(" + centerX + "," + centerY  + ")");

		// Legend
		svg.append("g")			
			.attr("class", "legend")
			.attr("transform", "translate(" + (centerX/3) + "," + centerY  + ")");
	}

	fn.redraw = function(jsonData) {
		fn.reset();
		
		var data = pie(jsonData.data);
		
		var arcs = svg.select("g.arcs").selectAll(".arc").data(data);
		var labels = svg.select("g.labels").selectAll(".label").data(data);
		var legend = svg.select("g.legend").selectAll(".legend").data(data);
		
		// 차트 생성
		fn._createPie(arcs);
		
		// 라벨 생성
		fn._createLabel(labels);
		
		// 범례 생성
		if(options.legend.show == true) {
			fn._createLegend(legend);
		}
	
		// 중앙 이미지 또는 텍스트 생성
		if(jsonData.image) {
			fn._createImage(jsonData.image);
		} else {
			fn._createTitle(jsonData.title, jsonData.icon);
		}
		
		arcs.exit().remove();
		labels.exit().remove();
		legend.exit().remove();
	};
	
	// click
	fn.click = function(d, i) {
		if('onclick' in options.data && typeof(options.data.onclick) == "function") {
			options.data.onclick(d,i);
		}
	};
	
	// PIE Mouse Enter
	fn.mouseenter = function(d, i) {
		/* 텍스트 강조 type
		svg.selectAll("text.label").each(function(d2, i2) {
			if(i2 == i) {
				d3.select(this)
					.attr("opacity", 1);
					//.style("font-size", Math.round(50 * scale) + "px")
					//.attr("dy", ".4em");
			} else {
				//d3.select(this).attr("opacity", 0.5);
			}
		});
		*/
		
		// tooltip type
		//console.log(width, height, this.getBBox().x, this.getBBox().y);
		$(targetId).find(".g_tooltip")
			.html("<li style='color:"+d.data.lcolor+"'><span style='color:#444444'>" + d.data.itemNm + " &nbsp;&nbsp;" + d.data.label +"%" + "</span></li>")
			.css({
				"position" : "absolute",
				"marginLeft": (width/2) + this.getBBox().x, 
				"marginTop": this.getBBox().y + 20 - (height*0.4),
				"width" : 120,
				"border" : "1px solid #ddd",
				"borderRadius" : "5px",
				"background" : "#ffffff",
				"line-height" : 1.6,
				"padding" : "8px 10px",
				"textAlign" : "left"
			})
			.show();
	};

	// PIE Mouse Leave
	fn.mouseleave = function(d, i) {
		// tooltip type
		$(targetId).find(".g_tooltip").hide();
		
		/* 텍스트 강조 type
		svg.selectAll("text.label")
			.attr("opacity", 1);
			.style("font-size", Math.round(40 * scale) + "px")
			.attr("dy", ".35em");
		*/
	};

	// PIE 생성
	fn._createPie = function(arcs) {
		arcs.enter().append("path")
			//.attr("d", arc)
			.attr("class", "pie")
			.attr("fill", function(d, i) { return colors[i%colors.length]; })
			.style("stroke", "#ffffff")
			.style("stroke-width", 4 * scale)
			.on("mouseenter", fn.mouseenter)
			.on("mouseleave", fn.mouseleave)
				.transition()
				.duration(5000)
				.attrTween("d", function(d) {
					this._current = this._current || d;
					var interpolate = d3.interpolate(this._current, d);
					this._current = interpolate(0);
					return function(t) {
						return arc(interpolate(t));
					};
				});
	};

	// 라벨
	fn._createLabel = function(labels) {
		labels.enter().append("text")
			.text(function(d, i) {
				d.data.lcolor = colors[i%colors.length];
				//return (d.value == 0 ? "" : d.data.label);
				return (d.value == 0 ? "" : (d.data.label + "%"));
			})
			.attr("class", "label")
			.attr("fill", "#ffffff")
			.attr("dy", ".35em")
			.attr("transform", function(d) {
                return "translate(" + arc.centroid(d) + ")";
            })
			.attr("text-anchor", "middle")
			.style("font-size", "16px")
			.style("letter-spacing",  "0")
			.style("font-weight", "500")
			.style("text-shadow", function(d, i) { 
				return "1px 1px 0px " + colors[i%colors.length] + 
					", -1px 1px 0px " + colors[i%colors.length] + 
					", 1px -1px 0px " + colors[i%colors.length] + 
					", -1px -1px 0px " + colors[i%colors.length]; 
			})
			//.on("mouseenter", fn.mouseenter)
			.style("cursor", "default");
	}

	// 범례
	var centerX = Math.round(width/2 * 0.9);
	var centerY = height * 0.5;
	fn._createLegend = function(names) {
		var namesSize = names[0].length;
		centerY = centerY/namesSize;
		
		var legend = names.enter().append('g')
			.attr("transform", "translate(" + (centerX) + "," + (-inRadius) + ")");

		legend.append('circle')
			.attr('cx', ((outRadius+extraOutRadius)*scale))
			.attr("cy", function(d, i) {
				var y = (centerY-35) + (i*(58 * scale));
				return y;
			})
			.attr('r', 5)
			.attr("fill", function(d, i) {
				return d.data.lcolor;
			});
		
		legend.append("text")
			.text(function(d, i) { return d.data.itemNm; })
			.attr("class", "legend")
			.attr("x", ((outRadius+extraOutRadius)*scale)+13)
			.attr("y", function(d, i) {
				var y = (centerY-30) + (i*(58 * scale));
				return y;
			})
			.style("cursor", function(d,i) {
				return (options.legend.clickable == true)? "pointer" : "default";
			})
			.on("click", function (d,i) {
				if(options.legend.clickable == true) {
					fn.click(d, i);
				}
			});
	}
	
	// 중앙 이미지
	fn._createImage = function(path) {
		path = $.trim(path);

		if(path != "") {
			$(targetId).find("img.g_logo").remove();

			var img = new Image();
			img.src = path;
			img.onload = function() {
				$(img).css("marginLeft", $(img).width()/-2);
			};
			img.onerror = function() {
				fn._createTitle(title);
			};
			$(img).appendTo(targetId).addClass("g_logo").css({
				"position" : "absolute",
				"bottom" : height * 0.23,
				"left" : "50%",
				"maxWidth": inRadius * 1.6,
				"maxHeight": inRadius * 0.6
			});
		}
	};

	// 중앙  텍스트, icon
	fn._createTitle = function(title, icon) {		
		$(targetId).find("img.g_logo").remove();

		svg.select("g.labels").append("text")
			.text(title)
			.attr("class", "g_logo")
			.attr("fill", "#444444")
			.attr("dy", ".35em")
			.attr("x", 0)
			.attr("y", (icon)? -inRadius * 0.3 : 0)
			.attr("text-anchor", "middle")
			.style("font-size", "16px")
			.style("letter-spacing", "-0.5px")
			.style("font-weight", "500");
		
		// font awesome icon 추가
		if(icon) {
			svg.select("g.labels").append("text")
			   .attr("x", -inRadius * 0.2)
			   .attr("y", inRadius * 0.2)
			   .attr("fill", "#aaa")
			   .attr("font-family", "FontAwesome")
			   .attr("font-size", Math.round(50 * scale) + "px")
			   .attr("class", "fa")
			   .text(icon.keyUnicode);
		}
	};

	fn.reset = function() {
		svg.select("defs").remove();
		svg.select("g.arcs").selectAll("*").remove();
		svg.select("g.labels").selectAll("*").remove();
		svg.select("g.legend").selectAll("*").remove();
	};


	// 시작
	!(function() {
		// 이미지 위치 고정 때문에
		//$(targetId).empty().css("position", "relative");
		
		// 특정 그래프에 대한 전체 사이즈 조정
		var graphName = $(targetId + "Frm > input[name='graph'").val();
		if(graphName === "brandAnalysisPN" || graphName === "brandSentimentPN") {
			extraInRadius = 110;
			extraOutRadius = 110;
		}
		
		arc = d3.svg.arc()
			.outerRadius(outRadius + (extraInRadius/3))
			.innerRadius(inRadius + (extraOutRadius/3));

		pie = d3.layout.pie()
			.sort(null)
			.value(function(d) { return d.value; });
			//.startAngle(-90 * Math.PI)
			//.endAngle(90 * (Math.PI/180));
		
		svg = d3.select(targetId).append("svg")
			.attr("width", width)
			.attr("height", height)
			.on("reload", function() {
				load_graph(targetId, function(jsonData) {
                    if(jsonData == null || jsonData.data == null || jsonData.data.length <= 0) return false;
                    fn.redraw(jsonData);
				}, afterFunc, function() { fn.reset() });
			})
			.on("reset", function() {
				fn.reset();
			});

		// tooltip class
		$(targetId).append("<div class='g_tooltip'></div>");
		
		fn.create();
		svg.on("reload")();
	})();

	this.targetId = targetId;
};

graph_pie.reset = function(targetId) {
	var svg = d3.select(targetId).select("svg");
	svg.on("reset")();
};

graph_pie.prototype.reset = function() {
	var svg = d3.select(this.targetId).select("svg");
	svg.on("reset")();
};

graph_pie.reload = function(targetId) {
	var svg = d3.select(targetId).select("svg");
	svg.on("reload")();
};

graph_pie.prototype.reload = function() {
	var svg = d3.select(this.targetId).select("svg");
	svg.on("reload")();
};