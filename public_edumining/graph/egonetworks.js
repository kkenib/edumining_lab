/**
 * 에고네트워크 그래프
 * d3.js v3.x
 * jsonData	{nodes: array, links: array}
 * targetId	#chartid
 *
 * @date	2017-03-13
 * @author	HYJUNG
 */
function graph_ego_networks(id, options, afterFunc) {
	$(id).data("graph-name", "egonetworks");
	
	options = options || {};
	options.data = options.data || {};	
	
	var IE = $.browser.msie;
	var fn = {},
		targetId = id,
		svg = null,
		force = null,
		links = null,
		cgroup = null,
		//rects = null,
		btns = null,
		//labels = null,
		firstData = null,
		width = $(targetId).width(),
		height = $(targetId).height()-32,
		scale = Math.min(width, height) / 300;
		//scale = Math.sqrt(width * height / 300000);
	

	var colors = graph_theme.egonetworks.background;
	var fcolors = graph_theme.egonetworks.font;
	var existsSubNode = false;

	// 데이터 정리
	fn.dataSet = function(nodeData) {
		var max = d3.max(nodeData, function(d) { return d.group == 1 ? +d.value : null; });
		var min = d3.min(nodeData, function(d) { return d.group == 1 ? +d.value : null; });

		var max2 = d3.max(nodeData, function(d) { return d.group > 1 ? +d.value : null; });
		var min2 = d3.min(nodeData, function(d) { return d.group > 1 ? +d.value : null; });

		var maxGroup = d3.max(nodeData, function(d) { return d.group; });



		var baseRadius = Math.round(32 * scale);
		var scaleRadius = d3.scale.linear().domain( [min, max] ).range( [18 * scale, baseRadius] );

		var distance = (Math.min(width, height) - baseRadius) / 2 * 0.8;
		distance = (maxGroup > 1 ? distance - (40 * scale) : distance);

		var scaleDistance = d3.scale.linear().domain( [max, min] ).range( [distance * 0.5, distance] );
		var scaleDistanceLv2 = d3.scale.linear().domain( [max2, min2] ).range( [10 * scale,  13 * scale] );

		firstData = nodeData[0];
		existsSubNode = false;

		nodeData.forEach(function(d, i) {
			if(d.group == 0) {
				d.fixed = true;
				d.x = d.px = width / 2;
				d.y = d.py = height / 2;
				d.radius = baseRadius * 1.1;
			} else {
				d.fixed = false;
				d.x = d.px = Math.round(Math.random() * width * 0.5) + width * 0.25;
				d.y = d.py = Math.round(Math.random() * height * 0.6) + height * 0.2;
				d.radius = Math.floor(scaleRadius(d.value));
				if(d.group > 1) {
					d.size = Math.floor(scaleDistanceLv2(d.value));
				} else {
					d.size = Math.floor(scaleDistance(d.value));	
				}
			}
		});
		
		if(maxGroup > 1) {
			force.gravity(0.3).charge(-170 * scale);
		} else {
			force.gravity(0.05).charge(-100 * scale);
		}
	};

	// 링크 컬러
	fn.linkColor = function(d, i) {
		return (d.source.group <= 1) ? "#bebebe" : "#e3e3e3";
	};

	// 원 컬러
	fn.circleColor = function(d, i) {
		return d.group == 0 ? (d.color)? d.color : colors[0] : (d.group == 1 ? colors[d.key % (colors.length-1) + 1] : "#ffffff");
	};

	// 텍스트 컬러
	fn.textColor = function(d, i) {
		return d.group == 0 ? fcolors[0] : (d.group == 1 ? fcolors[d.key % (fcolors.length-1) + 1] : "#777777");
	};

	// 텍스트 그림자
	fn.textShadow = function(d, i) {
		var col = d.group == 0 ? colors[0] : (d.group == 1 ? colors[d.key % (colors.length-1) + 1] : "#ffffff");
		
		return (d.group <= 1) ? col + " -1px -1px 0px,"
				+ col + " -1px 1px 0px,"
				+ col + " 1px -1px 0px,"
				+ col + " 1px 1px 0px"
			: "";
	}

	fn.distance = function(d, i) {
		if(d.source.group > 1) {
			return d.target.radius + d.source.size;
		}
		return d.source.size + 30;
	};

	// drag&drop 
	fn.drag = {
		start : function(d, i) {
			d.ctime = new Date();
			force.stop();
		},
		move : function(d, i) {
			fn.reEllipseX(d, d3.event.dx);
			d.py += d3.event.dy;
			d.y += d3.event.dy;
			fn.tick();
		},
		end : function(d, i) {
			if(d.ctime != null && new Date() - d.ctime <= 500) {
				fn.click(d);
			} else {
				d.fixed = true;
				fn.tick();
				d3.select(this).select(".pin").style("opacity", 1);
			}

			d.ctime = null;
			force.resume();
		}
	};

	// double click
	fn.dblclick = function(d) {
		d.fixed = false;
		d3.select(this).select(".pin").style("opacity", 0);
	};

	// click detail
	fn.click = function(d) {
		if('onclick' in options.data && typeof(options.data.onclick) == "function") {
			options.data.onclick(d);
		}
	};

	// 버튼 위치
	fn.btnXY = function(source, target, radius) {
		var dx = source._x - target._x;
		var dy = source.y - target.y;

		var d = Math.atan2(dy, dx);

		var x = Math.cos(d) * (radius + 5);
		var y = Math.sin(d) * (radius + 5);

		return {x : source._x + x, y : source.y + y, angle: d * (180 / Math.PI)};
	};

	// 타원으로
	fn.ellipseX = function(d) {
		// 가로세로 비율이 많이 차이날 경우
		if(width > height * 1.3) {
			var sc = (width / height * 0.4);
			d._x = d.x + (d.x - width/2) * sc;
		} else {
			d._x = d.x;
		}
	};

	fn.reEllipseX = function(d, eX) {
		if(width > height * 1.3) {
		var sc = (width / height * 0.4);
			var x = (d._x + eX + (width/2) * sc) / (sc + 1) - d.x;

			d.px = d.px + x;
			d.x = d.x + x;
		} else {
			d.px = d.px + eX;
			d.x = d.x + eX;
		}
	};

	fn.tick = function() {
		cgroup.attr("transform", function(d) {
			fn.ellipseX(d);
			return "translate(" + d._x + "," + d.y + ")";
		});

		btns.attr("transform", function(d) {
			if(d.group == 1 && d.sub.length > 0) {
				var _d = fn.btnXY(d, firstData, d.radius);
				d3.select(this)
					.attr("cx", _d.x)
					.attr("cy", _d.y)
					.select("path.ego-btn-close")
						.attr("transform", "rotate(" + (_d.angle + 90) + ")");
				return "translate(" + _d.x + "," + _d.y + ")";
			}
			return "translate(0,0)";
		});

		links.attr("x1", function(d) { return d.source._x; })
			.attr("y1", function(d) { return d.source.y; })
			.attr("x2", function(d) { return d.target._x; })
			.attr("y2", function(d) { return d.target.y; });
	};

	fn.redraw = function(nodeData, linkData) {
		fn.reset();
		fn.dataSet(nodeData);	

		force
			.nodes(nodeData)
			.links(linkData)
			.start();
		
		var _links = svg.selectAll(".link")
			.data(linkData);

		links = _links.enter().append("line")
				.attr("class", "ego-link")
				.style("stroke-width", function(d) {
					return d.source.group <= 1 ? 1 : 1.5;
				})
				.style("stroke", fn.linkColor)
				.style("visibility", function(d) {
					return d.source.group <= 1 ? "visible" : "hidden";
				});

		var _nodes = svg.selectAll(".node")
			.data(nodeData);

		var nodes = _nodes.enter()
			.append("g");

		// 버튼 클릭과 드래그&드롭 이벤트 분리를 위해 g추가
		cgroup = nodes.append("g")
			.call(d3.behavior.drag()
				.on("dragstart", fn.drag.start)
				.on("drag", fn.drag.move)
				.on("dragend", fn.drag.end)
			)
			.on("dblclick", fn.dblclick);

		// 첫번째 노드
		var _first = cgroup.first();
		svg[0][0].appendChild(_first[0][0]);

		_first.append("circle")
			.attr("class", "ego-node ego-circle-first")
			.attr("r", function(d) {
				return d.radius * 1.2;
			})
			.attr("fill", fn.circleColor)
			.style("cursor", (options.data.clickable)? "pointer" : "default")
			.style("opacity", .4);

		_first.insert("circle")
			.attr("class", "ego-node ego-circle-first")
			.attr("r", function(d) {
				return d.radius;
			})
			.style("cursor", (options.data.clickable)? "pointer" : "default")
			.attr("fill", fn.circleColor);

		// 1dept
		var radiusAverage = 0;
		cgroup.filter(function(d) {
			radiusAverage += d.radius;
			return d.group == 1;
		}).append("circle")
			.attr("class", "ego-node ego-circle")
			.attr("r", function(d) {
				
				if(d.radius > (radiusAverage / linkData.length)) {
					return d.radius;
				}
				
				return 0;
			})
			.style("cursor", (options.data.clickable)? "pointer" : "default")
			.attr("fill", fn.circleColor);
		
		// 2dept
		var font_x = Math.round(11 * scale);
		font_x = font_x > 14 ? 14 : (font_x < 11 ? 11 : font_x);

		cgroup.filter(function(d) {
			return d.group > 1;	
		}).style("visibility", function(d) {
				return d.group <= 1 ? "visible" : "hidden";
		}).append("rect")
			.attr("class", "ego-node ego-rect")
			.attr("rx", (font_x + 8) / 2)
			.attr("ry", (font_x + 8) / 2)
			.attr("width", function(d) {
				var w = d.keyword.length * font_x;
				return w < 50 ? 50 : w;
			})
			.attr("height", font_x + 8)
			.attr("x", function (d) { return - d3.select(this).attr("width") / 2; })
			.attr("y", function (d) { return - d3.select(this).attr("height") / 2; })
			.attr("fill", fn.circleColor)
			.style("opacity", .95)
			.style("stroke", "#e3e3e3")
			.style("stroke-width", 2);

		cgroup.append("text")
			.attr("class", "ego-label")
			.attr("dy", ".35em")
			.attr("fill", fn.textColor)
			.attr("font-size", function(d) {
				return d.group == 0 ? Math.round(font_x * 1.2) : (d.group == 1 ? font_x : Math.round(font_x * 0.9));
			})
			//.style("text-shadow", fn.textShadow)
			.style("text-anchor", "middle")
			.style("font-family", "NanumSquareRound")
			.style("font-weight", function(d) {
				return d.group <= 1 ? "bold" : "normal";
			})
			.style("cursor", (options.data.clickable)? "pointer" : "default")
			.text(function(d) { return d.keyword; })
			.on("mouseover",function(d){
				if(options.data.clickable) {
					if(d.group != 0)
						d3.select(this).attr("font-size", 16);
				}
			})
			.on("mouseout",function(d){
				if(options.data.clickable) {
					if(d.group != 0)
						d3.select(this).attr("font-size", "inherit");
				}
			});
		/*
		// 고정핀표시
		cgroup.append("path")
			.attr("class", "pin")
			.attr("d", "M-26 -23 L-25 -10, -24 -23, -21 -23, -20 -24, -21 -25, -29 -25, -30 -24, -29 -23Z")
			.attr("fill", function(d) { return d.group <= 1 ? "#eee" : "#999999"; })
			.attr("opacity", function(d) {
				return d.fixed == true ? 1 : 0;
			})
		*/
		btns = nodes.filter(function(d) {
			return d.group == 1 && d.sub.length > 0;
		}).append("g")
			.attr("x-vis", "hidden")
			.attr("cx", 0)
			.attr("cy", 0)
			.on("click", function(d) {
				var el = d3.select(this),
					vis = "visible",
					vis_p = 1;

				if (el.attr("x-vis") == "visible") {
					vis = "hidden";
					vis_p = -1;
					el.select("path.ego-btn-open").style("visibility", "visible");
					el.select("path.ego-btn-close").style("visibility", "hidden");
				} else {
					el.select("path.ego-btn-open").style("visibility", "hidden");
					el.select("path.ego-btn-close").style("visibility", "visible");
				}
				el.attr("x-vis", vis);

				links.filter(function(dd) {
					return (d.subgroup == dd.source.group);
				}).style("visibility", vis);

				cgroup.filter(function(dd) {
					if(d.subgroup == dd.group) {
						// tick
						dd.px -= (el.attr("cx") - d._x) * vis_p;
						dd.py -= (el.attr("cy") - d.y) * vis_p;
						dd.fixed = false;
						return true;
					}
					return false;
				}).style("visibility", vis);

				fn.tick();
				force.resume();
			});

		var btn_r = (10 * scale);
		btns.append("circle")
			.attr("class", "ego-btn")
			.attr("r", btn_r > 10 ? 10 : (btn_r < 5 ? 5 : btn_r))
			.attr("fill", "#999999")
			.style("stroke", "#ffffff")
			.style("stroke-width", 2)
			.style("cursor", "pointer");

		// + 버튼
		var open_x = btn_r > 10 ? 5 : (btn_r < 5 ? 3 : 4);
		btns.append("path")
			.attr("class", "ego-btn-open")
			.attr("d", "M-1 -"+open_x+",L-1 -1,L-"+open_x+" -1,L-"+open_x+" 1,L-1 1,L-1 "+open_x+",L 1 "+open_x+",L 1 1,L "+open_x+" 1,L "+open_x+" -1,L 1 -1,L 1 -"+open_x+"Z")
			.attr("fill", "#ffffff")
			.style("cursor", "pointer");

		// close 버튼
		var close_x = btn_r < 5 ? 2 : 3;
		btns.append("path")
			.attr("class", "ego-btn-close")
			.attr("d", "M-"+(close_x * 2)+" -"+close_x+",L-"+close_x+" -"+close_x+",L0 0,L"+close_x+" -"+close_x+",L"+(close_x * 2)+" -"+close_x+",L0 "+close_x+"Z")
			.attr("fill", "#ffffff")
			.style("cursor", "pointer")
			.style("visibility", "hidden");

		// reset 버튼 (새로고침 버튼으로 인해 사용하지 않음)
		/*
		var resetBtn = svg.append("g")
			.attr("class", "btn_basic refresh")
			.on("click", function() {
				fn.redraw(nodeData, linkData);
			})
			.on("mouseover", function() {
				d3.select(this).select("rect").style("opacity", .8);
			})
			.on("mouseout", function() {
				d3.select(this).select("rect").style("opacity", 1);
			})
			.attr("transform", "translate(" + (width - 28) + "," + (height - 10) + ")");
 
		resetBtn.append("rect")
			//.attr("fill", colors[0])
			.attr("fill", "#ccc")
			.attr("x", -39.5)
			.attr("y", -34)
			.attr("width", 39.5)
			.attr("height", 34)
			.attr("rx", 2)
			.attr("ry", 2)
			.style("cursor", "pointer")

		
		resetBtn.append("text")
			.attr("dy", ".35em")
			.attr("fill", "#ffffff")
			.attr("font-size", 12)
			.style("text-anchor", "middle")
			.style("cursor", "pointer")
			.text("새로고침");
		*/


		_links.exit().remove();
		_nodes.exit().remove();
	};

	fn.reset = function() {
		svg.selectAll("*").remove();
	};

	// 시작
	!(function() {
		$(targetId).empty();

		force = d3.layout.force()
			.size([width, height])
			.linkDistance(fn.distance)
			.friction(0.8)
			.alpha(0)
			.gravity(0.3)
			// charge 설정시 서브노드때문에 설정된 길이와 상관없이 노드간격 멀어짐
			.charge(-170 * scale)
			.on("tick", fn.tick);

		svg = d3.select(targetId).append("svg")
			.attr("width", width)
			.attr("height", height)
			//.style("margin", "25px")
			.on("forceResume", function() {
				force.resume();
			})
			.on("reload", function() {
				load_graph(targetId, function(jsonData) {
					if(jsonData.nodes == null || jsonData.nodes.length <= 1) return false;

					fn.redraw(jsonData.nodes, jsonData.links);
				}, afterFunc, function() { fn.reset() });
			})
			.on("reset", function() {
				fn.reset();
			});
		svg.on("reload")();
	})();

	this.targetId = targetId;
};

graph_ego_networks.reset = function(targetId) {
	var svg = d3.select(targetId).select("svg");
	svg.on("reset")();
};

graph_ego_networks.prototype.reset = function() {
	var svg = d3.select(this.targetId).select("svg");
	svg.on("reset")();
};

graph_ego_networks.reload = function(targetId) {
	var svg = d3.select(targetId).select("svg");
	svg.on("reload")();
};

graph_ego_networks.prototype.reload = function() {
	var svg = d3.select(this.targetId).select("svg");
	svg.on("reload")();
};
	
graph_ego_networks.resume = function(targetId) {
	var svg = d3.select(targetId).select("svg");
	svg.on("forceResume")();
};

graph_ego_networks.prototype.resume = function() {
	var svg = d3.select(this.targetId).select("svg");
	svg.on("forceResume")();
};
