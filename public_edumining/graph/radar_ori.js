function graph_radar(id, data, options) {
	$(id).data("graph-name", "radar");
	
	var d = data;
	
	var cfg = {
		radius: 4,
		w: $(id).width(),
		h: $(id).height(),
		factor: 1,
		factorLegend: .85,
		levels: 5,
		maxValue: 100,
		radians: 2 * Math.PI,
		opacityArea: 0.5,
		ToRight: 5,
		TranslateX: 80,
		TranslateY: 30,
		ExtraWidthX: 200,
		ExtraWidthY: 200		
	};
	
    // user option update
	if('undefined' !== typeof options){
		for(var i in options){
			if('undefined' !== typeof options[i]){
				cfg[i] = options[i];
			}
		}
	}
	
	var scale = (cfg.w/2) / 360;
	cfg.w = Math.round(cfg.w < cfg.h) ? (cfg.h/2) * 1.2 : (cfg.h/2) * 1.6;
	cfg.h = cfg.w;
	
    //console.log(cfg.w, cfg.h, scale);
    
    var allAxis = (d[0].map(function(i, j){return i.keyword}));
    var total = allAxis.length;
    var radius = cfg.factor*Math.min(cfg.w/2, cfg.h/2);
    var marginLeft = (radius/2) * scale;
    var Format = d3.format('%');
    d3.select(id).select("svg").remove();
    
    var g = d3.select(id)
    	.append("svg")
    	.attr("width", cfg.w+cfg.ExtraWidthX)
    	.attr("height", cfg.h+cfg.ExtraWidthY)
    	.append("g")
    	.attr("transform", "translate(" + cfg.TranslateX + "," + cfg.TranslateY + ")");
	
    // draw level line
    for(var j=0; j<cfg.levels; j++){
    	var levelFactor = cfg.factor*radius*((j+1)/cfg.levels);
      
    	g.selectAll(".levels")
    		.data(allAxis)
    		.enter()
    		.append("line")
    		.attr("x1", function(d, i){return marginLeft + levelFactor*(1-cfg.factor*Math.sin(i*cfg.radians/total));})
    		.attr("y1", function(d, i){return levelFactor*(1-cfg.factor*Math.cos(i*cfg.radians/total));})
    		.attr("x2", function(d, i){return marginLeft + levelFactor*(1-cfg.factor*Math.sin((i+1)*cfg.radians/total));})
    		.attr("y2", function(d, i){return levelFactor*(1-cfg.factor*Math.cos((i+1)*cfg.radians/total));})
    		.attr("class", "line")
    		.style("stroke", "#d2d7dc")
    		.style("stroke-opacity", "0.75")
    		.style("stroke-width", "1px")
    		.attr("transform", "translate(" + (cfg.w/2-levelFactor) + ", " + (cfg.h/2-levelFactor) + ")");
    }

    // Text indicating at what % each level is
    for(var j=0; j<cfg.levels; j++){
    	var levelFactor = cfg.factor*radius*((j+1)/cfg.levels);
    	g.selectAll(".levels")
    		.data([1]) //dummy data
    		.enter()
    		.append("text")
    		.attr("x", function(d){return marginLeft + levelFactor*(1-cfg.factor*Math.sin(0));})
    		.attr("y", function(d){return levelFactor*(1-cfg.factor*Math.cos(0));})
    		.attr("class", "legend")
    		.style("font-family", "sans-serif")
    		.style("font-size", "10px")
    		.attr("transform", "translate(" + (cfg.w/2-levelFactor + cfg.ToRight) + ", " + (cfg.h/2-levelFactor) + ")")
    		.attr("fill", "#737373")
    		.text((j+1)*100/cfg.levels);
    }

    // multi series
    series = 0;

    var dataList = d[series];
    
    var axis = g.selectAll(".axis")
    	.data(allAxis)
    	.enter()
    	.append("g")
    	.attr("class", "axis");

    var center_x1 = marginLeft + cfg.w/2*(1-cfg.factor*Math.sin(1*cfg.radians/total));
    var center_x2 = marginLeft + cfg.w/2*(1-cfg.factor*Math.sin(5*cfg.radians/total));
    
    var areaUp_x1 = [], areaUp_y1 = [], areaUp_xy = [];
    var areaDown_x1 = [], areaDown_y1 = [], areaDown_xy = [];
    
    // draw keyword line
    axis.append("line")
    	.attr("x1", marginLeft + cfg.w/2)
    	.attr("y1", cfg.h/2)
    	.attr("x2", function(d, i){
    		var value = marginLeft + cfg.w/2*(1-cfg.factor*Math.sin(i*cfg.radians/total));
    		if(dataList[i].category === "긍정")
    			areaUp_x1.push(value);
		  
    		if(dataList[i].category === "부정")
    			areaDown_x1.push(value);
		  
    		return value;
    	})
    	.attr("y2", function(d, i){    	  
    		var value = cfg.h/2*(1-cfg.factor*Math.cos(i*cfg.radians/total));
    		if(dataList[i].category === "긍정")
    			areaUp_y1.push(value);
		  
    		if(dataList[i].category === "부정")
    			areaDown_y1.push(value);
		  
    		return value;
    	})
    	.attr("class", "line")
    	.style("stroke", "#d2d7dc")
    	.style("stroke-width", "1px");

    // center line
    axis.append("line")
    	.attr("x1", center_x1)
    	.attr("y1", cfg.h/2)
    	.attr("x2", center_x2)
    	.attr("y2", cfg.h/2)
    	.attr("class", "line")
    	.style("stroke", "#d2d7dc")
    	.style("stroke-width", "1px");
    
    // set xy in giude area UP
    areaUp_xy.push([center_x1, cfg.h/2]);
    for(var i=0; i<areaUp_x1.length; i++){
    	if(i == 0) areaUp_xy.push([areaUp_x1[i+1], areaUp_y1[i+1]]);
    	else if(i == 1) areaUp_xy.push([areaUp_x1[i-1], areaUp_y1[i-1]]);
    	else areaUp_xy.push([areaUp_x1[i], areaUp_y1[i]]);
    }
    areaUp_xy.push([center_x2, cfg.h/2]);
    
    // fill the bg in giude area UP
    g.selectAll(".areaUp")
    	.data([areaUp_xy])
    	.enter()
    	.append("polygon")
    	.attr("class", "radar-area-up")
    	.style("stroke-width", "0.1px")
    	.style("stroke", "#DCEBFF")
    	.attr("points",function(d) {     
    		var str = "";
    		for(var pti=0;pti<d.length;pti++){
    			str = str + d[pti][0] + "," + d[pti][1] + " ";
    		}
               
    		return str;
    	})
    	.style("fill", "#DCEBFF")
    	.style("fill-opacity", "0.3");
    
    // set xy in giude area UDOWN
    areaDown_xy.push([center_x1, cfg.h/2]);
    for(var i=0; i<areaDown_x1.length; i++){
    	areaDown_xy.push([areaDown_x1[i], areaDown_y1[i]]);
    }
    areaDown_xy.push([center_x2, cfg.h/2]);
    
    // fill the bg in giude area DOWN
    g.selectAll(".areaDown")
    	.data([areaDown_xy])
    	.enter()
    	.append("polygon")
    	.attr("class", "radar-area-down")
    	.style("stroke-width", "0.1px")
    	.style("stroke", "#FFAAAA")
    	.attr("points",function(d) {     
    		var str = "";
    		for(var pti=0;pti<d.length;pti++){
    			str = str + d[pti][0] + "," + d[pti][1] + " ";
    		}
           
    		return str;
    	})
    	.style("fill", "#FFAAAA")
    	.style("fill-opacity", "0.3");
    
    // set text
    axis.append("text")
    	.attr("class", "legend")
    	.text(function(d){return d})
    	.style("font-family", "sans-serif")
    	.style("font-size", "12px")
    	.style("font-weight", "bold")
    	.attr("text-anchor", "middle")
    	.attr("dy", "1.5em")
    	.style("fill", function(d, i) {
    		if(dataList[i].keyword === d) {
    			if(dataList[i].category === "긍정") return "#0078FF";
    			if(dataList[i].category === "부정") return "#FF5675";
    		}
    	})
    	.attr("transform", function(d, i){return "translate(0, -10)"})
    	.attr("x", function(d, i){return marginLeft + cfg.w/2*(1-cfg.factorLegend*Math.sin(i*cfg.radians/total))-60*Math.sin(i*cfg.radians/total);})
    	.attr("y", function(d, i){return cfg.h/2*(1-Math.cos(i*cfg.radians/total))-20*Math.cos(i*cfg.radians/total);});
    
    // set data area polygon
    d.forEach(function(y, x){
    	dataValues = [];
    	g.selectAll(".nodes")
    		.data(y, function(j, i){
    			dataValues.push([
    			                 marginLeft + cfg.w/2*(1-(parseFloat(Math.max(j.value, 0))/cfg.maxValue)*cfg.factor*Math.sin(i*cfg.radians/total)), 
    			                 cfg.h/2*(1-(parseFloat(Math.max(j.value, 0))/cfg.maxValue)*cfg.factor*Math.cos(i*cfg.radians/total))
    			               ]);
    		});
      
    	dataValues.push(dataValues[0]);
    	g.selectAll(".area")
    		.data([dataValues])
    		.enter()
    		.append("polygon")
    		.attr("class", "radar-chart-serie"+series)
    		.style("stroke-width", "2px")
    		.style("stroke", "#6495ED")
    		.attr("points",function(d) {      
    			var str = "";
    			for(var pti=0;pti<d.length;pti++){
    				str = str + d[pti][0] + "," + d[pti][1] + " ";
    			}
               
    			return str;
    		})
    		.style("fill", function(j, i){return "#96A5FF"})
    		.style("fill-opacity", cfg.opacityArea)
    		.on('mouseover', function (d){
    			z = "polygon."+d3.select(this).attr("class");
             
    			g.selectAll(z)
    				.transition(200)
    				.style("fill-opacity", .7);
    		})
    		.on('mouseout', function(d){
    			z = "polygon."+d3.select(this).attr("class");
            	 	
    			g.selectAll(z)
    				.transition(200)
    				.style("fill-opacity", cfg.opacityArea);
    		});
    	series++;
    });
    series=0;

    // tooltip
    var tooltip = d3.select("body").append("div").attr("class", "toolTip");
    
    d.forEach(function(y, x){
    	g.selectAll(".nodes")
    	.data(y).enter()
    	.append("circle")
    	.attr("class", "radar-chart-serie"+series)
    	.attr('r', cfg.radius)
    	.attr("alt", function(j){return Math.max(j.value, 0)})
    	.attr("cx", function(j, i){
    		dataValues.push([
    		                 marginLeft + cfg.w/2*(1-(parseFloat(Math.max(j.value, 0))/cfg.maxValue)*cfg.factor*Math.sin(i*cfg.radians/total)), 
    		                 cfg.h/2*(1-(parseFloat(Math.max(j.value, 0))/cfg.maxValue)*cfg.factor*Math.cos(i*cfg.radians/total))
    		               ]);
    		return marginLeft + cfg.w/2*(1-(Math.max(j.value, 0)/cfg.maxValue)*cfg.factor*Math.sin(i*cfg.radians/total));
    	})
    	.attr("cy", function(j, i){
    		return cfg.h/2*(1-(Math.max(j.value, 0)/cfg.maxValue)*cfg.factor*Math.cos(i*cfg.radians/total));
    	})
    	.attr("data-id", function(j){return j.keyword})
    	.style("fill", function(d,i) {
    		if(dataList[i].category === "긍정") return "#6495ED";
    		if(dataList[i].category === "부정") return "#FF7A85";
    	})
    	.style("stroke-width", "1px")
    	.style("fill-opacity", .8)
    	.style("stroke", function(d,i){
    		if(dataList[i].category === "긍정") return "#6495ED";
    		if(dataList[i].category === "부정") return "#FF7A85";
    	})
    	.on('mouseover', function (d){
    		tooltip
    			.style("left", d3.event.pageX - 40 + "px")
    			.style("top", d3.event.pageY - 80 + "px")
    			.style("display", "inline-block")
    			.html((d.keyword) + "&nbsp;&nbsp;" + "<span>" + (d.value) + "</span>" + "%");
    	})
    	.on("mouseout", function(d){ tooltip.style("display", "none");});

    	series++;
    });
};