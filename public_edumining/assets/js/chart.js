function Matrix(options) {
    var margin = {top: 8, right: 16, bottom: 56, left: 56};

    var widthLegend = 48;

    width_id = options.width_id;
    if(typeof width_id == "undefined") {
        var width = Number($(".grapharea").css("width").replace("px", "")) - widthLegend - 72;
    }else{
        var width = Number($(width_id).css("width").replace("px", "")) - widthLegend - 72;
    }

    height = width/1.5;
    data = options.data;
    container = options.container;
    if(typeof container == "undefined"){
        container = "#graph";
    }

    labelsData = options.labels;
    startColor = options.start_color;
    endColor = options.end_color;
    legend_id = options.legend;
    if(typeof legend_id == "undefined"){
        legend_id = "#legend";
    }
    console.log(container);
    console.log(legend_id);
    console.log(width_id);

    if(!data){
        throw new Error('Please pass data');
    }

    if(!Array.isArray(data) || !data.length || !Array.isArray(data[0])){
        throw new Error('It should be a 2-D array');
    }

    var maxValue = d3.max(data, function(layer) { return d3.max(layer, function(d) { return d; }); });
    var minValue = d3.min(data, function(layer) { return d3.min(layer, function(d) { return d; }); });

    var numrows = data.length;
    var numcols = data[0].length;

    var svg = d3.select(container).append("svg")
        .style("width", width + margin.left + margin.right + "px")
        .style("height", height + margin.top + margin.bottom + "px")
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    var background = svg.append("rect")
        .style("stroke", "black")
        .style("stroke-width", "1px")
        .style("width", width + "px")
        .style("height", height + "px");

    var x = d3.scale.ordinal()
        .domain(d3.range(numcols))
        .rangeBands([0, width]);

    var y = d3.scale.ordinal()
        .domain(d3.range(numrows))
        .rangeBands([0, height]);

    var colorMap = d3.scale.linear()
        .domain([minValue,maxValue])
        .range([startColor, endColor]);

    var row = svg.selectAll(".row")
        .data(data)
        .enter().append("g")
        .attr("class", "row")
        .attr("transform", function(d, i) { return "translate(0," + y(i) + ")"; });

    var cell = row.selectAll(".cell")
        .data(function(d) { return d; })
        .enter().append("g")
        .attr("class", "cell")
        .attr("transform", function(d, i) { return "translate(" + x(i) + ", 0)"; });

    cell.append('rect')
        .attr("width", x.rangeBand())
        .attr("height", y.rangeBand())
        .style("stroke-width", 0);

    cell.append("text")
        .attr("dy", "6px")
        .attr("x", x.rangeBand() / 2)
        .attr("y", y.rangeBand() / 2)
        .attr("text-anchor", "middle")
        .style("fill", function(d, i) { return d >= maxValue/2 ? 'white' : 'black'; })
        .text(function(d, i) { return d; });

    row.selectAll(".cell")
        .data(function(d, i) { return data[i]; })
        .style("fill", colorMap);

    var labels = svg.append('g')
        .attr('class', "labels");

    labels
        .append('text')
        .attr('class', 'axis-label')
        .attr('y', -32)
        .attr('x', -16)
        .attr('fill', 'black')
        .attr('transform', `rotate(-90)`)
        .attr('text-anchor', `middle`)
        .text("실제값");

    labels
        .append('text')
        .attr('class', 'axis-label')
        .attr('x', width - 18)
        .attr('y', height + 40)
        .attr('fill', 'black')
        .attr('text-anchor', `middle`)
        .text("예측값");

    var columnLabels = labels.selectAll(".column-label")
        .data(labelsData)
        .enter().append("g")
        .attr("class", "column-label")
        .attr("transform", function(d, i) { return "translate(" + x(i) + "," + height + ")"; });

    columnLabels.append("line")
        .style("stroke", "black")
        .style("stroke-width", "1px")
        .attr("x1", x.rangeBand() / 2)
        .attr("x2", x.rangeBand() / 2)
        .attr("y1", 0)
        .attr("y2", 5);

    columnLabels.append("text")
        .attr("x", x.rangeBand() / 2 + 3)
        .attr("y", 15)
        .attr("dy", "5px")
        .attr("text-anchor", "end")
        .attr("transform", "translate(0, 10) rotate(-45)")
        .text(function(d, i) { return d; });

    var rowLabels = labels.selectAll(".row-label")
        .data(labelsData)
        .enter().append("g")
        .attr("class", "row-label")
        .attr("transform", function(d, i) { return "translate(" + 0 + "," + y(i) + ")"; });

    rowLabels.append("line")
        .style("stroke", "black")
        .style("stroke-width", "1px")
        .attr("x1", 0)
        .attr("x2", -5)
        .attr("y1", y.rangeBand() / 2)
        .attr("y2", y.rangeBand() / 2);

    rowLabels.append("text")
        .attr("x", -10)
        .attr("y", y.rangeBand() / 2)
        .attr("dy", "6px")
        .attr("text-anchor", "end")
        .text(function(d, i) { return d; });

    var key = d3.select(legend_id)
        .append("svg")
        .attr("width", widthLegend)
        .attr("height", height + margin.bottom);

    var legend = key
        .append("defs")
        .append("svg:linearGradient")
        .attr("id", "gradient")
        .attr("x1", "100%")
        .attr("y1", "0%")
        .attr("x2", "100%")
        .attr("y2", "100%")
        .attr("spreadMethod", "pad");

    legend
        .append("stop")
        .attr("offset", "0%")
        .attr("stop-color", endColor)
        .attr("stop-opacity", 1);

    legend
        .append("stop")
        .attr("offset", "100%")
        .attr("stop-color", startColor)
        .attr("stop-opacity", 1);

    key.append("rect")
        .attr("width", widthLegend/2-12)
        .attr("height", height)
        .style("fill", "url(#gradient)")
        .attr("transform", "translate(0," + margin.top + ")");

    var y = d3.scale.linear()
        .range([height, 0])
        .domain([minValue, maxValue]);

    var yAxis = d3.svg.axis()
        .scale(y)
        .orient("right");

    key.append("g")
        .attr("class", "y axis")
        .attr("transform", "translate(12," + margin.top + ")")
        .call(yAxis);
}


function qq_plot(options){
    data = options.data,
    container = options.container;
    if(typeof container == "undefined"){
        container = "#graph";
    }

    legend_id = options.legend;
    if(typeof legend_id == "undefined"){
        legend_id = "#legend";
    }

    var margin = {top: 16, right: 16, bottom: 48, left: 48};

    width_id = options.width_id;
    if(typeof width_id == "undefined") {
        var width = Number($(".grapharea").css("width").replace("px","")) - 64;
    }else{
        var width = Number($(width_id).css("width").replace("px","")) - 64;
    }

    height = width/2;

    var svg = d3.select(container)
        .append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform",
            "translate(" + margin.left + "," + margin.top + ")");

    // Add X axis
    var x = d3.scale.linear()
        .domain([data.min_val, data.max_val])
        .range([0, width]);
    svg.append("g")
        .attr("fill", "#666666")
        .attr("transform", "translate(0," + height + ")")
        //.attr("transform", "translate(-6," + height + ")")
        .call(d3.svg.axis().scale(x).orient("bottom").outerTickSize(1).tickPadding(2));

    // Add Y axis
    var y = d3.scale.linear()
        .domain([data.min_val, data.max_val])
        .range([height, 0]);
    svg.append("g")
        .attr("fill", "#666666")
        //.attr("transform", "translate(0, 6)")
        .call(d3.svg.axis().scale(y).orient("left").outerTickSize(1).tickPadding(2));

    // Add dots
    svg.append('g')
        .selectAll("dot")
        .data(data.scatter_data)
        .enter()
        .append("circle")
        .attr("cx", function (d) {
            //console.log(d)
            return x(d.x);
        })
        .attr("cy", function (d) {
            return y(d.y);
        })
        .attr("r", 1.5)
        .style("fill", "#00bb9d");

    var line_data = [{'x' : data.min_val, 'y' : data.max_val}];

    svg.append("line")
        .style("stroke", "#8e6fff")
        .data(line_data)
        .attr("x1", function (d) {
            console.log(d);
            return x(d.x);
        })
        .attr("y1", function (d) {
            console.log(d);
            return y(d.x);
        })
        .attr("x2", function (d) {
            console.log(d);
            return x(d.y);
        })
        .attr("y2", function (d) {
            console.log(d);
            return y(d.y);
        })
}