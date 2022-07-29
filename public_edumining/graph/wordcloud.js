
/**
 * data : [nodes : {name, key, group, value, subgroup, sub : [index]}, links: {target, source, value}]
 */
var graph_wordcloud = function(id, afterFunc) {
	$(id).data("graph-name", "wordcloud");
	
	var fn = {},
		svg = null,
		targetId = id,
		groupStyle = [0,1,2,3,3],
		width = $(targetId).width(),
		height = $(targetId).height(),
		_scl = (width > height ? width / height : height / width),
		scaleWidth = Math.floor(width * (_scl >= 3 ? 0.7 : (_scl >= 2 ? 0.8 : 1))),
		scale = Math.sqrt(scaleWidth * height / 150000),
		margin = 10 * scale;

	if(scale > 1) scale = 1;
	margin = margin < 15 ? 15 : margin;

	var svgWidth = 0;
	var IE = $.browser.msie;
	var fcolors = graph_theme.wordcloud.font;
	var bgcolors = graph_theme.wordcloud.background;
	var fweight = [800, 800, 700, 700];
	
	// 데이터 정리
	fn.dataSet = function(nodeData) {
		var max = d3.max(nodeData, function(d) { return +d.frequency; });
		var min = d3.min(nodeData, function(d) { return +d.frequency; });
		
		// 임시
		var scaleSize = d3.scale.linear().domain( [min, max * 1.2] ).range( [13, 50 * scale] );
//		var scaleSize = d3.scale.linear().domain( [min, max * 1.2] ).range( [20 * scale, 50 * scale] );
		var scaleGroup = d3.scale.linear().domain( [min, max] ).range( [groupStyle.length, 0]);

		var div = Math.ceil(nodeData.length / groupStyle.length);
		nodeData.forEach(function(d, i) {
			d.group = groupStyle[Math.floor(i / div)];

			if(i == 0) {
				d.sort = Math.floor(Math.random() * 100);
				d.size = 55 * scale;
			} else {
				d.sort = Math.floor(Math.random() * 100);
				d.size = scaleSize(d.frequency) + 5;
				
				// 임시로 지움
				//d.size = Math.floor(d.group == 1 ? d.size * 0.7 : d.size);
			}
			
			// 글자길이가 너무길때 사이즈 조정
			if(d.keyword.length*d.size > (svgWidth- (margin))) {
				d.size = (svgWidth-(margin)) / d.keyword.length
			}
			d.rank = i+1;
		});

		// 정렬
		nodeData = nodeData.sort(function(a, b) {
			return a.sort - b.sort;
		});
	};

	fn.redraw = function(nodeData) {
		svgWidth = Number(svg.attr("width")) + (margin*2);
		fn.reset();
		fn.dataSet(nodeData);

		var _g = svg.selectAll("g")
			.data(nodeData);

		var g = _g.enter().append("g")
			.attr("class", function(d) {
				return "word-style" + (d.group + 1);
			});

		// title
		if($(id).attr('data-itemnm')) {
			var legend = svg.append('g')
			.attr('class', targetId+"_legend")
			.attr('transform', "translate(5, 10)");
			
			var legendCY = 20;
			legend.append('circle')
				.attr('cy', legendCY)
				.attr('r', 5)
				.style('fill', $(id).attr('data-lcolor'));
			
			legend.append('text')
				.attr('x', 10)
				.attr('y', legendCY+5)
				.style('font-size', '14px')
				.style('font-weight', '500')
				.text($(id).attr('data-itemnm') + ' : ' + $(id).attr('data-label') + '%');
		}
		
		// background
		var defs = svg.append("defs");
		for(var i = 0; i < bgcolors.length; i++) {
			if($.trim(bgcolors[i]) == "" || $.trim(bgcolors[i]) == "underline") continue;

			var pattern = defs
				.append("pattern")
					//.attr("id", targetId + "_bg" + (i + 1))
					.attr("id", targetId + "_bg")
					.attr('patternUnits', 'userSpaceOnUse')
					.attr('width', 4)
					.attr('height', 4);

			// svg only
			pattern.append("rect").attr("class", "bgc").attr("x", 0).attr("y", 0);

			pattern.append("rect").attr("x", 0).attr("y", 0);
			pattern.append("rect").attr("x", 2).attr("y", 0);
			pattern.append("rect").attr("x", 3).attr("y", 1);
			pattern.append("rect").attr("x", 0).attr("y", 2);
			pattern.append("rect").attr("x", 2).attr("y", 2);
			pattern.append("rect").attr("x", 1).attr("y", 3);

			pattern.selectAll("rect")
				.attr("fill", "#000000")
				.attr("width", 0)
				.attr("height", 0)
				.attr("opacity", .2);

			pattern.select("rect.bgc")
				.attr("width", 4)
				.attr("height", 4)
				//.attr("fill", bgcolors[i])
				.attr("fill", "#ec7327")
				.attr("opacity", 1);

			//svg.selectAll("g.word-style" + (i + 1))
			svg.selectAll("g")
				.append("rect");
		}
		var rect = svg.selectAll("g > rect");
		var line_count = 1;
		var textWidth = 0;
		var wordCutLine = 0;
		
		var text = g.append("text")
			.text(function(d) {
				return d.keyword;
			})
			.attr("class", function(d) {
				return "rank" + d.rank;
			})
			.attr("dy", ".3em")
			.attr("fill", function(d) {				
				return fcolors[d.group];
			})
			.attr("font-size", function(d) {
				return d.size;
			})
			.style("font-family", graph_theme.fontfamily)
			.style("font-weight", function(d) {
				return fweight[d.group];
			})
			.style("line-height", "1")
			//.style("cursor", "pointer")
			.attr("x", function(d) {
				wordCutLine += (this.getBBox().width+(margin*2));
				var x = textWidth + (d.group == 1 ? margin : 10);
				if(wordCutLine > svgWidth) {
					line_count++;
					wordCutLine = (this.getBBox().width+(margin*2));
				}
				
				textWidth += this.getBBox().width + (d.group == 1 ? margin * 3 : margin);
				return x;
			});
		textWidth -= margin;
		textWidth = Math.ceil(textWidth);

		//var lineCount = Math.ceil(textWidth / (scaleWidth - margin*2));
		//var lineWidth = textWidth / lineCount;
		
		var left = 0;
		var top = 0;
		var next = -1;
		var _resetTextPos = function(n) {
			//var lineWidthTmp = lineWidth * n;
			var lnHeight = 0;
			var lnWidth = 0;
			var x = 0
			var flat = true;
			var ln = text.filter(function(d, i) {
				var bbox = this.getBBox();
				
				//var x1 = bbox.x;
				//var x2 = bbox.x + bbox.width * 0.5;
				x += (bbox.width + (margin*2));
				//if(i > next && x2 <= lineWidthTmp) {
				if(i > next && x <= svgWidth) {
					if(lnHeight < bbox.height) {
						lnHeight = bbox.height;
					}
					lnWidth += bbox.width + (d.group == 1 ? margin * 3 : margin);
					
					next = i;
					flat = false;
					return true;
				}
				if(flat)
					x = 0;
				
				return false;
			});

			var left = ln.first().node().getBBox().x;
			var reX = (width - lnWidth) * 0.5 - left;
			ln.attr("x", function(d) {
				return this.getBBox().x + reX;
			}).attr("y", top + lnHeight * 0.5);

			top += lnHeight + margin * scale;
			left = lnWidth;
		};

		// X Pos && Line Pos
		//for(var n = 1; n <= lineCount; n++) {
		for(var n = 1; n <= line_count; n++) {
			_resetTextPos(n);
		}

		// Y Pos
		var reY = (height - top) * 0.5;
		text.attr("y", function(d) {
			return parseFloat(d3.select(this).attr("y")) + reY;
		});

		// background size
		rect.each(function(d, i) {
			var bbox = d3.select(this.parentNode).select("text").node().getBBox();
			var ymargin = margin*0.5;
			// font style 때문에 여백 생김
			var gX = d.keyword.length * bbox.width * 0.02;
			var gY = 1;
			var wm = (d.keyword.length - 1) * 2;

			if(IE == true) {
				ymargin = 0;
				gX = 0;
				wm = 0;
			}

			text_class = d3.select(this.parentNode).select("text").attr('class');
			var r = (bbox.height + ymargin * 2) * 0.5;
			
			var text_length = d3.select(this.parentNode).select("text")[0][0].textContent.length;
			var xmargin = margin*2;
			//if(text_class == "rank1") xmargin = margin*(1+(text_length*0.57));
			var IE_margin = 0;
			if(text_class == "rank1") {
				xmargin = margin*(1+((text_length+2)*0.23));
				if(IE == true) {
					IE_margin = 7;
					xmargin = margin*(1+((text_length+5)*0.16));
				}
			}
			
			d3.select(this)
				.attr("id", "rect_"+text_class)
				.attr("x", bbox.x - margin)
				//.attr("y", bbox.y - ymargin + gY)
				.attr("y", bbox.y - ymargin + gY + IE_margin)
				//.attr("width", bbox.width + margin*2 - gX + wm)
				//.attr("height", bbox.height + ymargin*2 + gY)
				.attr("width", bbox.width + xmargin - gX + wm - IE_margin)
				.attr("height", bbox.height + ymargin*2 + gY - IE_margin)
				.attr("rx", r)
				.attr("ry", r)
				//.style("fill", "url(#" + targetId + "_bg" + (d.group + 1) + ")")
				.style("fill", "url(#" + targetId + "_bg)")
				.style("display", "none")
				//.style("fill", "#eee");
		});

		// underline
		for(var i = 0; i < bgcolors.length; i++) {
			if($.trim(bgcolors[i]) == "" || $.trim(bgcolors[i]) != "underline") continue;
			
			svg.selectAll("g.word-style" + (i + 1)).each(function(d, i) {
				var bbox = d3.select(this).select("text").node().getBBox();
				var gX = d.keyword.length * bbox.width * 0.02;

				if(IE == true) {
					gX = 0;
				}

				d3.select(this)
					.append("line")
						.attr("x1", bbox.x)
						.attr("y1", bbox.y + bbox.height)
						//.attr("x2", bbox.x + bbox.width - gX)
						.attr("x2", bbox.x + bbox.width)
						.attr("y2", bbox.y + bbox.height)
						.attr("stroke-width", 2)
						.attr("stroke", function(d) {
							return fcolors[d.group];
						})
						.style("shape-rendering", "crispEdges");
		
			});
		}

		_g.exit().remove();
	};

	fn.reset = function() {
		svg.selectAll("*").remove();
	};

	// 시작
	!(function() {
		$(targetId).empty();

		svg = d3.select(targetId).append("svg")
			.attr("width", width)
			.attr("height", height)
			.style("overflow", "visible")
			.on("colorset", function(color) {
				if($.isArray(color)) {					
					fcolors = color;
				} else if($.isPlainObject(color)) {
					if('font' in color) fcolors = color.font;
					if('background' in color) bgcolors = color.background;
				} else {					
					fcolors[0] = color;
				}
			})
			.on("reload", function() {
				load_graph(targetId, function(jsonData) {
					if(jsonData.length <= 0) return false;

					fn.redraw(jsonData);
				}, afterFunc, function() { fn.reset(); });
			})
			.on("reset", function() {
				fn.reset();
			});
		svg.on("reload")();
	})();

	this.targetId = targetId;
};

graph_wordcloud.reset = function(targetId) {
	var svg = d3.select(targetId).select("svg");
	svg.on("reset")();
};

graph_wordcloud.prototype.reset = function() {
	var svg = d3.select(this.targetId).select("svg");
	svg.on("reset")();
};

graph_wordcloud.reload = function(targetId) {
	var svg = d3.select(targetId).select("svg");
	svg.on("reload")();
};

graph_wordcloud.prototype.reload = function() {
	var svg = d3.select(this.targetId).select("svg");
	svg.on("reload")();
};

graph_wordcloud.colorset = function(targetId, color) {
	var svg = d3.select(targetId).select("svg");
	svg.on("colorset")(color);
};

graph_wordcloud.prototype.colorset = function(color) {
	var svg = d3.select(this.targetId).select("svg");
	svg.on("colorset")(color);
};