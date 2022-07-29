/**
 * 패션 트렌드 그래프
 * d3.js v3.x
 * jsonData	{nodes: array, links: array}
 * targetId	#chartid
 *
 * @date	2018-05-29
 * @author	HYJUNG
 */
var graph_trend_bubble = function(id, afterFunc) {
	$(id).data("graph-name", "trend_bubble");

	var _self = this;

	var fn = {},
		targetId = id,
		svg = null,
		dataGroup = null,
		focusGroup = null,
		width = $(targetId).width(),
		height = $(targetId).height(),
		scale = (height / 20) / 20,
		xLabelHeight = Math.min(Math.max(20, 30*scale), 30),
		yLabelWidth = Math.min(Math.max(60, 80*scale), 70),
		x1 = yLabelWidth,
		x2 = width,
		xLen = 15,
		xGap = (x2 - x1) / xLen,
		y1 = (xLabelHeight/2),
		y2 = (height) / 2,
		yGap = (y2 - y1) / 10,
		maxY = 0;

	if(xGap < 60) {
		xLen = Math.floor((x2 - x1) / 60);
		xGap = (x2 - x1) / xLen;
	}
	y1 = y1 + yGap;

	var fsize = Math.round(scale > 1 ? 13 : (scale < 0.68 ? 11 :  13*scale));
	var fsizeLarge = Math.round(fsize * 1.3);
	var fsizeSmall = fsize - 1;
	var fsizeXSmall = Math.round(fsize*0.85);

	// 총10칸 1칸 여백
	//var yTerm = [0, 10, 20, 60, 100, 200];
	var yTerm = [0, 10, 20, 60, 80, 100];
	var yTermRow = [0, 2, 2, 2, 1, 1];
	//var yTermLabel = ["0", "10", "20", "60", "∬", "200\n이상"];
	var yTermLabel = ["0", "10", "20", "60", "80", "100"];

	var scaleY = [];
	var grayBoundary = 0;
	
	var colors = ['#F18DA5','#ACACE4'];
	var fcolors = ['#DD6FA6','#6565db'];
	var bcolors = ['#F18DA5','#ACACE4'];
	var fontFamily = graph_theme.fontfamily;

	
	// 데이터 정리
	fn.dataSet = function(nodeData) {
		nodeData = nodeData.slice(1);
		
		var maxRadius = null, 
			minRadius = null, 
			maxX = null, 
			minX = null;

		nodeData.forEach(function(d, i) { 			
			d.value = +d.value;
			d.distance = +d.distance;
			d.percent = +d.percent;

			// x축 보정값 (임시로 보정값 뺌)
			//d.distance = d.distance > 40 ? 40 + (d.distance-40)/10 : d.distance;

			var absY = Math.abs(d.percent);

			maxRadius = (maxRadius == null || maxRadius < d.value ? d.value : maxRadius); 
			minRadius = (minRadius == null || minRadius > d.value ? d.value : minRadius);
			maxX = (maxX == null || maxX < d.distance ? d.distance : maxX);
			minX = (minX == null || minX > d.distance ? d.distance : minX);
			maxY = (maxY == null || maxY < absY ? absY : maxY);
		});
		grayBoundary = minX + (maxX - minX) * 0.2;
		
		// distance가 전부 0인 경우 maxX 값 보정
		if(maxX == 0) maxX = 1;
		
		var scaleRadius = d3.scale.linear().domain( [minRadius, maxRadius] ).range( [2*scale, 20*scale] );
		var scaleX = d3.scale.linear().domain( [maxX, minX] ).range( [x1 + 25, x2 - 30] );

		// Y구간(총10칸)
		// 0~20 4칸
		// 20~60 2칸
		// 60~100이상 1칸
		// 100~200이상 1칸 
		// 200이상 1칸∬
		// 1칸 여백
		scaleY = [];
		var preTerm = yTerm[0];
		var perRowSum = 0;
		for(var i = 1; i < yTerm.length; i++) {
			var rowSum = perRowSum + yTermRow[i];
			scaleY.push(d3.scale.linear().domain( [preTerm, yTerm[i]] ).range( [y1 + yGap * perRowSum, y1 + yGap * rowSum] ));
			preTerm = yTerm[i];
			perRowSum = rowSum;
		}
		if(maxY > yTerm[yTerm.length-1]) {
			scaleY.push(d3.scale.linear().domain( [preTerm, maxY] ).range( [y1 + yGap * perRowSum, y1 + yGap * 9] ));
		}

		nodeData.forEach(function(d, i) {
			// R : 키워드 빈도
			d.r = scaleRadius(d.value);
			// X값 : 연관성
			d.x = scaleX(d.distance);
			
			// Y값 : 증감율, 좌표상 위쪽이 마이너스
			//d.y = fn.getY(d.percent * (d.group < 2 ? 1 : -1));
			d.y = fn.getY(d.percent, d.keyword);
		});

		return nodeData;
	};

	// 원 컬러
	fn.circleColor = function(d, i) {
		//var t1 = Math.round(255 * t);
		//t1 = t1.toString(16);
		if(d.distance > grayBoundary) {
			return colors[d.group-1]; // + t1;
		} else {
			return "#CCCCCC"; // + t1;
		}
	};

	fn.strokeColor = function(d, i) {
		if(d.distance > grayBoundary) {
			return bcolors[d.group-1];
		} else {
			return "#CCCCCC";
		}
	};

	fn.fontColor = function(d, i) {
		if(d.distance > grayBoundary) {
			return fcolors[d.group-1];
		} else {
			return "#666666";
		}
	};

	fn.getY = function(d, k) {
		var _d = Math.abs(d);
		var y = -1;
		var i = 1;
		for(; i < yTerm.length; i++) {
			if(_d <= yTerm[i]) {
				y = scaleY[i-1](_d);
				break;
			}
		}
		
		// 감소 축 y좌표
		if(y < 0) {
			y = scaleY[i-1](_d);
		}

		// 위치가 반대
		if(d >= 0) y = -y;
		
		return y;
	};

	// 최초 그래프 생성
	fn.create = function() {
		svg.selectAll("*").remove();
		
		// 패턴생성
		var defs = svg.append("defs");

		// 그라데이션
		var xGradient = defs.append("linearGradient")
			.attr("id", "x-gradient");
		xGradient.append("stop")
			.attr("offset", "0%")
			.attr("stop-color", "#e04a6f");
		xGradient.append("stop")
			.attr("offset", "100%")
			.attr("stop-color", "#bbbbbb");

		var yGradient = defs.append("linearGradient")
			.attr("id", "y-gradient")
			.attr("x1", "0%")
			.attr("y1", "0%")
			.attr("x2", "0%")
			.attr("y2", "100%");

		yGradient.append("stop")
			.attr("offset", "0%")
			.attr("stop-color", "#f394af");
		yGradient.append("stop")
			.attr("offset", "100%")
			.attr("stop-color", "#88aad9");

		// 노드 포커스 백그라운드 / 투명 그라데이션
		var radialGradient = defs.append("radialGradient")
			.attr("id", "radial-gradient")
			.attr("cx", "50%")
			.attr("cy", "50%")
			.attr("r", "40%");

		radialGradient.append("stop")
			.attr("offset", "20%")
			.attr("stop-color", "#eeeeee")
			.attr("stop-opacity", .8);
		radialGradient.append("stop")
			.attr("offset", "100%")
			.attr("stop-color", "#eeeeee")
			.attr("stop-opacity", 0);

		defs.append("clipPath")
			.attr("id", "data-clip")
			.append("rect")
				.attr("x", yLabelWidth)
				.attr("y", -y2)
				.attr("width", x2-x1-1)
				.attr("height", height-4);

		// 백그라운드/라벨 등 표시
		var labelGroup = svg.append("g")
			.attr("transform", "translate(0, " + (height/2) + ")");
		
		// Y축 그리드/라벨
		var ypath = "";
		for(var u = -1; u < 2; u+=2) {
			for(var i = 0; i < 9; i++) {
				ypath += "M" + x1 + ", " + (Math.round(y1 + yGap*i) * u) + " L" + x2 + ", " + (Math.round(y1 + yGap*i) * u) + " ";
			}
		}
		ypath += "Z";
		labelGroup.append('path')
			.attr("d", ypath)
			.attr("stroke", "#dddddd")
			.attr("stroke-width", 1)
			.attr("fill", "none")
			.style("shape-rendering", "crispEdges");

		var yLabelBg = labelGroup.append('rect')
			.attr('x', 0)
			.attr('y', -(height/2))
			.attr('width', yLabelWidth)
			.attr('height', height)
			.attr('fill', "url(#y-gradient)");

		var ytLabel = ['▲','','이','슈','성','','높','음'];
		fn.createTspanVerticalAlign(labelGroup, ytLabel, 20, y2-15, -1, "middle");

		var ybLabel = ['이','슈','성','','낮','음','','▼'];
		fn.createTspanVerticalAlign(labelGroup, ybLabel, 20, y2-15, 1, "middle");

		for(var u = -1; u < 2; u+=2) {
			var rowSum = 1;
			for(var i = 0; i < yTerm.length; i++) {
				var yTickLabel = yTermLabel[i].split("\n");
				yTickLabel[0] = (u < 0 || i == 0 || isNaN(+yTickLabel[0]) ? '' : '-') + yTickLabel[0];

				rowSum += yTermRow[i];
				var y = Math.round(rowSum * yGap) + (u < 0 ? 3 : 1);

				y = y + (fsize*yTickLabel.length + fsize)/2;
				fn.createTspanVerticalAlign(labelGroup, yTickLabel, yLabelWidth-7, y, u, "end", fsizeSmall);
			}
		}

		// X축 그리드/라벨
		var xpath = "";
		for(var i = 1; i <= xLen; i++) {
			xpath += "M" + Math.round(x1 + xGap*i) + ", " + (-y2) + " L" + Math.round(x1 + xGap*i) + ", " + (y2) + " ";
		}
		xpath += "Z";
		labelGroup.append('path')
			.attr("d", xpath)
			.attr("stroke", "#dddddd")
			.attr("stroke-width", 1)
			.attr("fill", "none")
			.style("shape-rendering", "crispEdges");

		var c = xLabelHeight/2;
		var xLabelBg = labelGroup.append('path')
			.attr('d', "M0,0 L" + c + "," + c + " L" + (width - c) + "," + c + " L" + width + ",0 L" + (width - c) + "," + (-c) + " L" + c + "," + (-c) + " Z"
			)
			.attr('fill', "url(#x-gradient)");

		labelGroup.append("text")
			.attr("x", (width-yLabelWidth)/2 + yLabelWidth)
			.attr("y", 0)
			.attr("dy", ".35em")
			.attr("fill", "#FFFFFF")
			.attr("font-size", fsizeLarge)
			.attr("font-weight", "700")
			.attr("font-family", "NanumSquareRound")
			.style("text-anchor", "middle")
			.style("letter-spacing", "1")
			.text("안경산업 가시성");

		labelGroup.append("text")
			.attr("x", yLabelWidth)
			.attr("y", 0)
			.attr("dy", ".35em")
			.attr("fill", "#FFFFFF")
			.style("font-size", fsizeXSmall)
			.style("font-weight", "bold")
			.style("font-family", fontFamily)
			.style("text-anchor", "end")
			.text("");

		labelGroup.append("text")
			.attr("x", yLabelWidth - 20)
			.attr("y", 0)
			.attr("dy", ".35em")
			.attr("fill", "#FFFFFF")
			.style("font-size", fsizeSmall)
			.style("font-weight", "400")
			.style("font-family", "Nanum Gothic")
			.style("text-anchor", "start")
			.text("◀ 연관성 높음");

		labelGroup.append("text")
			.attr("x", width - 20)
			.attr("y", 0)
			.attr("dy", ".35em")
			.attr("fill", "#FFFFFF")
			.style("font-size", fsizeSmall)
			.style("font-weight", "400")
			.style("font-family", "Nanum Gothic")
			.style("text-anchor", "end")
			.text("연관성 낮음 ▶");

		// 상단/하단 보더
		labelGroup.append("line")
			.attr("x1", 0)
			.attr("y1", -y2-1)
			.attr("x2", width)
			.attr("y2", -y2-1)
			.attr("stroke", "#7D7D7D")
			.attr("stroke-width", 0)
			.style("shape-rendering", "crispEdges");
		labelGroup.append("line")
			.attr("x1", 0)
			.attr("y1", y2+1)
			.attr("x2", width)
			.attr("y2", y2+1)
			.attr("stroke", "#7D7D7D")
			.attr("stroke-width", 0)
			.style("shape-rendering", "crispEdges");

		// 데이터량 가이드 표시
		labelGroup.append('rect')
			.attr("fill", "#ffffff")
			.attr("x", x2 - xGap*2 +1)
			.attr("y", y2 - yGap + 1)
			.attr("width", xGap * 2 -2)
			.attr("height", yGap-2);

		labelGroup.append("text")
			.attr("x", x2 - xGap - 7)
			.attr("y", y2 - yGap*0.5)
			.attr("dy", ".35em")
			.attr("fill", "#BBBBBB")
			.style("font-size", fsizeXSmall)
			.style("font-weight", "800")
			.style("font-family", fontFamily)
			.style("text-anchor", "end")
			.text("정보량");

		var m = 7;
		for(var i = 0; i < 3; i++) {
			var r = 3 + 1.5*i;
			m += r;
			labelGroup.append('circle')
				.attr("fill", "#BBBBBB")
				.attr("cx", x2 - xGap + m)
				.attr("cy", y2 - yGap*0.5)
				.attr("r", r);

			m += r + 7;
		}

		// 데이터 표시 그룹
		dataGroup = svg.append("g")
			.attr("transform", "translate(0, " + (height/2) + ")")
			.attr("clip-path", "url(#data-clip)");

		// 포커스 그룹
		focusGroup = svg.append("g")
			.attr("transform", "translate(0, " + (height/2) + ")")
			.attr("clip-path", "url(#data-clip)");
		
		// 포커스 제거 / 바로 삭제시 마우스포인터에 가려서 잘 안보임
		$(targetId).bind("mouseleave", function() {
			focusGroup.selectAll("*").remove(); 
			dataGroup
				//.style("opacity", 1)
				.selectAll(".circle")
					.style("opacity", 1);
		});
	};

	// 업데이트
	fn.redraw = function(nodeData) {
		dataGroup.selectAll("*").remove();
		focusGroup.selectAll("*").remove(); 

		nodeData = fn.dataSet(nodeData);

		var node = dataGroup.selectAll(".circle")
			.data(nodeData);

		var g = node.enter()
			.append("g")
				.attr("class", "circle");

		g.append("circle")
			.attr("cx", function(d) { return d.x; })
			.attr("cy", function(d) { return d.y; })
			.attr("r", function(d) { return d.r; })
			.attr("fill", fn.circleColor)
			.attr("fill-opacity", .6)
			.style("stroke", fn.strokeColor)
			.style("stroke-width", Math.min(3 * scale, 3));


		g.append("text")
			.attr("x", function(d) { return d.x; })
			.attr("y", function(d) { return d.y + d.r + 10; })
			.attr("dy", ".35em")
			.attr("fill", "#7D7D7D")
			.style("font-size", fsizeSmall)
			.style("font-weight", "800")
			.style("font-family", fontFamily)
			.style("text-anchor", "middle")
			.style("text-shadow", "#FFFFFF -1px -1px 0px,"
					+ "#FFFFFF -1px 1px 0px,"
					+ "#FFFFFF 1px -1px 0px,"
					+ "#FFFFFF 1px 1px 0px,"
					+ "#FFFFFF -2px 0px 0px,"
					+ "#FFFFFF 2px 0px 0px")
			.style("cursor", "default")
			.text(function(d) { return d.keyword; })

		g.on("mouseover", function(d, i) {
			fn.focusNode(this, d, i);
		});

		node.exit();
	};

	fn.focusNode = function(g, d, i) {
		focusGroup.selectAll("*").remove();
		dataGroup
			//.style("opacity", 0.8)
			.selectAll(".circle")
				.style("opacity", 1);
		g = d3.select(g).style("opacity", 0);

		var fg = g.node().cloneNode(true);
		focusGroup.node().appendChild(fg);
		
		var gClone = d3.select(fg).style("opacity", 1);		
		var t = gClone.select("text")
			.style("font-size", fsizeSmall+2)
			.style("fill", fn.fontColor(d, i));
		gClone.select("circle")
			.attr("r",  d.r +1)
			//.attr("fill", fn.circleColor(d, i))
			//.attr("fill-opacity", .7))
			.style("stroke-width",  Math.min(4 * scale, 4));

		// 포커스된 주변만 흐리게 처리		
		gClone
			.on("mouseover", function() {
				var r = t.node().getBBox().width / 2;
				focusGroup.insert("circle", ":first-child")
					.attr("class", "focus-background")
					.attr("r", (d.r > r ? d.r : r) + 20 * scale)
					.attr("cx", d.x)
					.attr("cy", d.y)
					.attr('fill', "url(#radial-gradient)");
			})
			.on("mouseout", function() {
				focusGroup.select("circle.focus-background").remove();
				d3.event.stopPropagation();
			});	

		// 주변 투명도
		//gClone
		//	.on("mouseover", function() {
		//		dataGroup.style("opacity", 0.8);
		//	})
		//	.on("mouseout", function() {
		//		dataGroup.style("opacity", 1);
		//	});
	};

	// lenged
	fn.createTspanVerticalAlign = function(g, text, x, y, u, anchor, fs) {	
		if(fs == null) fs = fsizeSmall;

		var tmpText = g.append("text")
			.attr("fill", "#FFFFFF")
			.attr("font-size", fs)
			.attr("font-family", fontFamily)
			.style("text-anchor", anchor);

		if(u > 0) {
			text = text.reverse();
		}
		for(var i = 0; i < text.length; i++) {
			if(text[i] == "") {
				y = y - Math.floor(fs/2);
				continue;
			}
			
			var tspan = null;
			if(u > 0) {
				tspan = tmpText.insert("tspan", ":first-child");
			} else {
				tspan = tmpText.append("tspan");
			}
			tspan
				.attr("dy", ".35em")
				.attr("x", x)
				.attr("y", y * u)
				.text(text[i]);

			y = y - (fs + 1);
		}
	};

	fn.reset = function() {
		dataGroup.selectAll("*").remove();
		focusGroup.selectAll("*").remove(); 
	};

	// 시작
	!(function() {
		$(targetId).empty();

		svg = d3.select(targetId).append("svg")
			.attr("width", width) 
			.attr("height", height)
			.style("overflow", "hidden")
			.on("reload", function() {	
				load_graph(targetId, function(jsonData) {
					if(jsonData.nodes == null || jsonData.nodes.length <= 1) return false;

					fn.redraw(jsonData.nodes);
				}, afterFunc, function() { fn.reset() }, false);
			})
			.on("reset", function() {
				fn.reset();
			});
		fn.create();
		svg.on("reload")();
	})();

	this.targetId = targetId;
};

graph_trend_bubble.reset = function(targetId) {
	var svg = d3.select(targetId).select("svg");
	svg.on("reset")();
};

graph_trend_bubble.prototype.reset = function() {
	var svg = d3.select(this.targetId).select("svg");
	svg.on("reset")();
};

graph_trend_bubble.reload = function(targetId) {
	var svg = d3.select(targetId).select("svg");
	svg.on("reload")();
};

graph_trend_bubble.prototype.reload = function() {
	var svg = d3.select(this.targetId).select("svg");
	svg.on("reload")();
};