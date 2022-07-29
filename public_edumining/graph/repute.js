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
function graph_repute(id, afterFunc) {
	$(id).data("graph-name", "repute");

	var fn = {},
		targetId = id,
		svg = null,
		arc = null,
		pie = null,
		btns = null,
		width = $(targetId).width(),
		height = $(targetId).height(),
		outRadius = Math.round(width/2 * 0.95 < height * 0.75) ? width/2 * 0.95 : height * 0.75,
		inRadius = Math.round(outRadius * 0.52),
		// 평판 표시를 위한 100이하 사이즈 깨짐
		// textlist 가 3개 이상은 다른 감성끼리 텍스트 겹칠수 있음
		scale = outRadius / 270,
		textRadius = outRadius + 20 * scale;
		
	var colors = graph_theme.repute.background;
	var fcolors = graph_theme.repute.font;

	fn.smile = function(cx, cy, r) {
		var mr = r * 0.6;
		var rr = r * 0.2;
		var ir = 2 * scale / 2;

		//alert((cx - mr));
		return "M" + (cx - r) + "," + cy + " " +
			"a" + r + "," + r + " 0 1,0 " + r*2  + ",0 " +
			"a" + r + "," + r + " 0 1,0 " + -r*2 + ",0 " +
			//눈
			"M" + (cx - mr) + " " + (cy - rr) + " " +
			"a" + ir + "," + ir + " 0 1,0 " + ir*2  + ",0 " +
			"a" + ir + "," + ir + " 0 1,0 " + -ir*2 + ",0 " +
			"M" + (cx + mr) + " " + (cy - rr) + " " +
			"a" + ir + "," + ir + " 0 1,0 " + -ir*2  + ",0 " +
			"a" + ir + "," + ir + " 0 1,0 " + ir*2 + ",0 " +
			// 입
			"M" + (cx - mr) + " " + (cy + rr) + " " +
			"C " + (cx - rr) + " " + (cy + mr) + ", " + (cx + rr) + " " + (cy + mr) + ", " + (cx + mr) + " " + (cy + rr)
			"M" + cx + "," + cy + "Z";
	};

	fn.angry = function(cx, cy, r) {
		var mr = r * 0.5;
		var rr = r * 0.2;
		var ir = r * 0.7;

		return "M" + (cx - r) + "," + cy + " " +
			"a" + r + "," + r + " 0 1,0 " + r*2  + ",0 " +
			"a" + r + "," + r + " 0 1,0 " + -r*2 + ",0 " +
			// 눈
			"M" + (cx - ir) + " " + (cy - rr) + " " +
			"L" + (cx - rr) + " " + (cy - rr) + " " +
			"M" + (cx + ir) + " " + (cy - rr) + " " +
			"L" + (cx + rr) + " " + (cy - rr) + " " +
			// 입
			"M" + (cx - mr) + " " + (cy + mr) + " " +
			"C " + (cx - rr) + " " + (cy + rr) + ", " + (cx + rr) + " " + (cy + rr) + ", " + (cx + mr) + " " + (cy + mr)
			"M" + cx + "," + cy + "Z";
	};


	fn.create = function() {
		var centerX = width/2;
		var centerY = height * 0.8;

		// Pie
		svg.append("g")
			.attr("class", "arcs")
			.attr("transform", "translate(" + centerX + "," + centerY  + ")");

		// Background
		var info = svg.append("g")
			.attr("transform", "translate(" + centerX + "," + centerY  + ")");

		info.append("path")
			.attr("d", "M" + -outRadius + ", 0 L" + outRadius + ", 0 " +
				"M" + -outRadius + ", " + (65 * scale) + " L" + outRadius + ", " + (65 * scale) + " "
			)
			.attr("stroke", "#f1f1f1")
			.style("stroke-width", 2.5 * scale);

		/* keris에서 사용 안함
		info.append("path")
			.attr("d", "M" + (-outRadius * 0.65) + ", " + (32 * scale) + " L" + -(30 * scale) + ", " + (32 * scale) + " " +
				"M" + (outRadius * 0.65) + ", " + (32 * scale) + " L" + (30 * scale) + ", " + (32 * scale) + " "
			)
			.attr("stroke", "#e5e5e5")
			.style("stroke-width", 2 * scale);
		*/
		
		info.append("path")
			.attr("d", fn.smile(-1 * outRadius * 0.9, 30 * scale, 16 * scale))
			.attr("stroke", fcolors[0])
			.attr("stroke-width", 2 * scale)
			.attr("stroke-linejoin", "round")
			.attr("fill", "none");

		info.append("path")
			.attr("d", fn.angry(1 * outRadius * 0.9, 30 * scale, 16 * scale))
			//.attr("stroke", fcolors[2])
			.attr("stroke", fcolors[1])
			.attr("stroke-width", 2 * scale)
			.attr("stroke-linejoin", "round")
			.attr("fill", "none");

		//var text = ["긍정", "중립", "부정"];
		var text = ["긍정", "부정"];
		info.selectAll(".text")
			.data(text)
			.enter().append("text")
				.attr("dy", ".35em")
				.attr("fill", function(d, i) { return fcolors[i%fcolors.length]; })
				//.attr("x", function(d, i) { return (i -1) * outRadius * 0.75 ; })
				.attr("x", function(d, i) { return ((i*2) -1) * outRadius * 0.75 ; })
				.attr("y", 30 * scale)
				.attr("font-size", 17 * scale)
				.style("text-anchor", "middle")
				.style("font-weight", "bolder")
				.text(function(d) {return d});

		// Button
		svg.append("g")
			.attr("class", "btns")
			.attr("transform", "translate(" + centerX + "," + centerY  + ")");

		// Label
		svg.append("g")
			.attr("class", "labels")
			.attr("transform", "translate(" + centerX + "," + centerY  + ")");

	}

	fn.redraw = function(jsonData) {
		fn.reset();

		var data = pie(jsonData.data);

		var arcs = svg.select("g.arcs").selectAll(".arc").data(data);
		var labels = svg.select("g.labels").selectAll(".label").data(data);
		var btns = svg.select("g.btns").selectAll(".btn").data(data);

		var maxIndex = 0;
		var max = 0;
		data.forEach(function(d, i) {
			if(max < d.value && d.data.textlist != null && d.data.textlist.length > 0) {
				maxIndex = i;
				max = d.value;
			}
		});

		fn._createPie(arcs);
		fn._createLabel(labels);
		fn._createTextlist(labels, maxIndex);
		fn._createBtn(btns, maxIndex);
		fn._createImage(jsonData.image, jsonData.title);
		
		arcs.exit().remove();
		labels.exit().remove();
		btns.exit().remove();
	};
	
	// PIE Mouse Enter
	fn.mouseenter = function(d, i) {
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
	};

	// PIE Mouse Leave
	fn.mouseleave = function(d, i) {
		svg.selectAll("text.label")
			.attr("opacity", 1);
			//.style("font-size", Math.round(40 * scale) + "px")
			//.attr("dy", ".35em");
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

	// 상세 텍스트
	fn._createTextlist = function(labels, maxIndex) {
		var textlists = [];
		var groupY = [];
		var resetPosition = function(oY, add) {
			oY.add += add;
			oY.startY += add;
			oY.endY += add;
			return oY;
		};

		labels.each(function(d, i) {
			if(d.data.textlist == null || d.data.textlist.length == 0) {
				var _groupY = {"anchor" : 0, "startY" : 0, "endY" : 0, add : 0};
				groupY.push(_groupY);
				return;
			}
			
			// 중심각도
			var a = (d.endAngle + d.startAngle)/2;
			var anc = a <= 0 ? "end" : "start";
			var n = (d.data.textlist.length - 1) / 2;

			var start = textlists.length;
			var end = start + d.data.textlist.length - 1;
			
			d.data.textlist.forEach(function(d2, i2) {
				var y = Math.cos(a) * -outRadius;
				y = y + 25 * (i2 - n);

				var text = {
					"m" : (anc == "start" ? 1 : -1),
					"value" : d2, 
					"anchor" : anc,
					"group" : i,
					"y" : y
				};
				textlists.push(text);
			});

			var _groupY = {"anchor" : anc, "startY" : textlists[start].y - 12, "endY" : textlists[end].y + 13, add : 0};

			// 상/하단 위치
			if(_groupY.startY < -outRadius)  _groupY = resetPosition(_groupY, -outRadius - _groupY.startY);
			if(_groupY.endY > 0) _groupY = resetPosition(_groupY, -(_groupY.endY));

			groupY.push(_groupY);
		});

		// LEFT
		!(function() {
			// 겹침발생시 상단으로 밀기
			for(var i = 0; i < groupY.length -1; i++) {
				if(groupY[i].anchor == "start") break;

				for(var j = i+1; j < groupY.length; j++) {
					if(groupY[j].anchor == "start") break;

					// 겹침
					if(groupY[i].startY >= groupY[j].startY && groupY[i].startY < groupY[j].endY) {
						groupY[j] = resetPosition(groupY[j], -(groupY[j].endY - groupY[i].startY));
					}
				}
			}

			i = i-1;
			// 상단 제한 초과시 하단으로 밀기
			if(groupY[i].startY < -outRadius) {
				groupY[i] = resetPosition(groupY[i], -outRadius - groupY[i].startY);

				for(; i > 0; i--) {
					for(var j = i-1; j >= 0; j--) {
						// 겹침
						if(groupY[j].startY >= groupY[i].startY && groupY[j].startY < groupY[i].endY) {
							groupY[j] = resetPosition(groupY[j], groupY[i].endY - groupY[j].startY);
						}
					}
				}
			}
		})();

		// RIGHT
		!(function() {
			// 겹침발생시 상단으로 밀기
			for(var i = groupY.length -1; i > 0; i--) {
				if(groupY[i].anchor == "end") break;

				for(var j = i-1; j >= 0; j--) {
					if(groupY[j].anchor == "end") break;

					// 겹침
					if(groupY[i].startY >= groupY[j].startY && groupY[i].startY < groupY[j].endY) {
						groupY[j] = resetPosition(groupY[j], -(groupY[j].endY - groupY[i].startY));
					}
				}
			}

			i = i+1;
			// 상단 제한 초과시 하단으로 밀기
			if(groupY[i].startY < -outRadius) {
				groupY[i] = resetPosition(groupY[i], -outRadius - groupY[i].startY);

				for(; i < groupY.length - 1; i++) {
					for(var j = i+1; j < groupY.length; j++) {
						// 겹침
						if(groupY[j].startY >= groupY[i].startY && groupY[j].startY < groupY[i].endY) {
							groupY[j] = resetPosition(groupY[j], groupY[i].endY - groupY[j].startY);
						}
					}
				}
			}
		})();

		var lists = svg.select("g.labels").append("g")
			.attr("class", "textlist")
			.selectAll(".textlist")
				.data(textlists);

		lists.enter().append("text")
			.attr("class", function(d, i) { return "textlist" + d.group; })
			.attr("fill", function(d, i) { return fcolors[d.group%fcolors.length]; })
			.attr("dy", ".35em")
			.attr("x", function(d, i) {
				var y = d.y + groupY[d.group].add;
				var x = Math.sqrt(Math.pow(textRadius, 2) - Math.pow(y, 2));
				return (isNaN(x) ? 0 : x) * d.m;
			})
			.attr("y", function(d, i) {
				return d.y + groupY[d.group].add;
			})
			.attr("font-size", 13)
			.style("font-family", graph_theme.fontfamily)
			.attr("text-anchor", function(d, i) { return d.anchor; })
			.style("visibility", function(d, i) {
				return d.group == maxIndex ? "visible" : "hidden";
			})
			.style("cursor", "default")
			.text(function(d, i) { 
				var w = Math.floor((width / 2 - Math.abs(d3.select(this).attr("x")) - 30) / 12);
				var txt = w < d.value.length ? d.value.slice(0, w-2) + ".."  : d.value;
				return (d.m > 0 ? "• " + txt : txt + " •"); 
			})
			.on("mouseover", function(d) {
				$(targetId).find(".g_tooltip")
					.text(d.value)
					.css({
						"position" : "absolute",
						"marginLeft": width/2 + this.getBBox().x , 
						"marginTop": this.getBBox().y + 20 - height*0.2,
						"width" : 180,
						"border" : "1px solid #ddd",
						"borderRadius" : "5px",
						"background" : "#ffffff",
						"line-height" : 1.6,
						"padding" : "8px 10px",
						"textAlign" : "left"
					})
					.show();
			})
			.on("mouseout", function() {
				$(targetId).find(".g_tooltip").hide();
			});

		lists.exit().remove();
	};

	// 버튼 생성
	fn._createBtn = function(btns, maxIndex) {
		var _outRadius = outRadius - 2; // border;
		var btn = btns.enter().append("g")
			.style("visibility", function(d, i) {
				return d.value == 0 || d.data.textlist == null || d.data.textlist.length <= 0 ? "hidden" : "visible";
			})
			.attr("x-vis", function(d, i) {
				return i == maxIndex ? "visible" : "hidden";
			})
			.attr("transform", function(d) {
				var a = (d.endAngle + d.startAngle)/2;
				var x = Math.sin(a) * _outRadius;
				var y = Math.cos(a) * -_outRadius;
				return "translate(" + x + "," + y + ")";
			})
			.on("click", function(d, i) {
				var el = d3.select(this);
				var target = svg.selectAll("text.textlist" + i);

				var vis = "visible";
				if (el.attr("x-vis") == "visible") {
					vis = "hidden";
					el.select("path.ego-btn-open").style("visibility", "visible");
					el.select("path.ego-btn-close").style("visibility", "hidden");
				} else {
					el.select("path.ego-btn-open").style("visibility", "hidden");
					el.select("path.ego-btn-close").style("visibility", "visible");
				}
				el.attr("x-vis", vis);
				target.style("visibility", vis);
			});

		btn.append("circle")
			.attr("class", "ego-btn")
			.attr("r", 10)
			.attr("fill", "#999999")
			.style("stroke", "#ffffff")
			.style("stroke-width", 2.5)
			.style("cursor", "pointer");

		// + 버튼
		btn.append("path")
			.attr("class", "ego-btn-open")
			.attr("d", "M-1 -5,L-1 -1,L-5 -1,L-5 1,L-1 1,L-1 5,L 1 5,L 1 1,L 5 1,L 5 -1,L 1 -1,L 1 -5Z")
			.attr("fill", "#ffffff")
			.style("visibility", function(d, i) {
				return i == maxIndex || d.value == 0 || d.data.textlist == null || d.data.textlist.length <= 0 ? "hidden" : "visible";
			})
			.style("cursor", "pointer");

		// close 버튼
		btn.append("path")
			.attr("class", "ego-btn-close")
			.attr("d", "M-6 -3,L-3 -3,L0 0,L3 -3,L6 -3,L0 3Z")
			.attr("transform", function(d) {
				var d = (d.endAngle + d.startAngle)/2 * 180 / Math.PI;
				return "rotate(" + d + ")";
			})
			.attr("fill", "#ffffff")
			.style("cursor", "pointer")
			.style("visibility", function(d, i) {
				return i != maxIndex || d.value == 0 || d.data.textlist == null || d.data.textlist.length <= 0  ?  "hidden" : "visible";
			});
	};

	// 라벨
	fn._createLabel = function(labels) {
		labels.enter().append("text")
			.text(function(d, i) { return (d.value == 0 ? "" : d.data.label); })
			.attr("class", "label")
			.attr("fill", "#ffffff")
			.attr("dy", ".35em")
			.attr("transform", function(d) {
                return "translate(" + arc.centroid(d) + ")";
            })
			.attr("text-anchor", "middle")
			.style("font-size", Math.round(40 * scale) + "px")
			.style("letter-spacing",  Math.round(-3 * scale) + "px")
			.style("font-weight", "bolder")
			.style("text-shadow", function(d, i) { 
				return "1px 1px 0px " + colors[i%colors.length] + 
					", -1px 1px 0px " + colors[i%colors.length] + 
					", 1px -1px 0px " + colors[i%colors.length] + 
					", -1px -1px 0px " + colors[i%colors.length]; 
			})
			.on("mouseenter", fn.mouseenter);
	}

	// 중앙 이미지
	fn._createImage = function(path, title) {
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
		} else {
			fn._createTitle(title);
		}

		/*
		 * 패턴 사이즈가 실 이미지 사이즈와 맞지않아 흐릿하게 보이는 문제
		var pattern = svg.append("defs")
			.append("pattern")
				.attr("id", targetId + "_g_logo")
				.attr('patternUnits', 'userSpaceOnUse')
				.attr('width', inRadius * 1.6)
				.attr('height', inRadius * 0.6)
				.attr("x", 0)
				.attr("y", 0)

		pattern.append("image")
			.attr("xlink:href", path)
			.attr("width", inRadius * 1.6)
			.attr("height", inRadius * 0.6)

		svg.select("g.labels").append("rect")
			.attr("class", "g_logo")
			.attr("transform", "translate(" + (-inRadius * 1.6 / 2) + "," + -inRadius * 0.6 + ")")
			.attr("width", inRadius * 1.6)
			.attr("height", inRadius * 0.6)
			.style("overflow", "hidden")
			.attr("fill", "url(#" + targetId + "_g_logo)");
		*/
	};

	// 중앙 이미지대신 텍스트
	fn._createTitle = function(title) {
		$(targetId).find("img.g_logo").remove();

		svg.select("g.labels").append("text")
			.text(title)
			.attr("class", "g_logo")
			.attr("fill", "#000000")
			.attr("dy", ".35em")
			.attr("x", 0)
			.attr("y", -inRadius * 0.3)
			.attr("text-anchor", "middle")
			//.style("font-size", Math.round(45 * scale) + "px")
			.style("font-size", Math.round(35 * scale) + "px")
			.style("letter-spacing",  Math.round(-3 * scale) + "px")
			.style("font-weight", "bolder");
	};

	fn.reset = function() {
		svg.select("defs").remove();
		svg.select("g.arcs").selectAll("*").remove();
		svg.select("g.labels").selectAll("*").remove();
		svg.select("g.btns").selectAll("*").remove();
	};


	// 시작
	!(function() {
		// 이미지 위치 고정 때문에
		$(targetId).empty().css("position", "relative");

		arc = d3.svg.arc()
			.outerRadius(outRadius)
			.innerRadius(inRadius);

		pie = d3.layout.pie()
			.sort(null)
			.value(function(d) { return d.value; })
			.startAngle(-90 * (Math.PI/180))
			.endAngle(90 * (Math.PI/180));

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

		$(targetId).append("<div class='g_tooltip'></div>");

		fn.create();
		svg.on("reload")();
	})();

	this.targetId = targetId;
};

graph_repute.reset = function(targetId) {
	var svg = d3.select(targetId).select("svg");
	svg.on("reset")();
};

graph_repute.prototype.reset = function() {
	var svg = d3.select(this.targetId).select("svg");
	svg.on("reset")();
};

graph_repute.reload = function(targetId) {
	var svg = d3.select(targetId).select("svg");
	svg.on("reload")();
};

graph_repute.prototype.reload = function() {
	var svg = d3.select(this.targetId).select("svg");
	svg.on("reload")();
};