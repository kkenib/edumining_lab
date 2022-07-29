function chartScreenShot(imgId) {

    $('#blackLayer').bPopup({
        modalClose: false,
        opacity: 0.6,
        positionStyle: 'absolute', //'fixed' or 'absolute'
        
        onOpen: function() { 
        },						
        follow: [false,true],
        scrollBar: true	
    });



    $("#"+imgId).html("");

    var html = d3.select("svg")
        .attr("version", 1.1)
        .attr("xmlns", "http://www.w3.org/2000/svg")
        .node().parentNode.innerHTML;
 
    //console.log(html);
    var imgsrc = 'data:image/svg+xml;base64,'+ btoa(unescape(encodeURIComponent(html)));
    var img = '<img src="'+imgsrc+'">';
    d3.select("#"+imgId).html(img);


    html2canvas($("#"+imgId), {
        background:'#fff',
        useCORS: true,
        proxy: '/etc/proxy_image',
        onrendered : function(canvas) {
            var img = canvas.toDataURL("image/png");

            $("#image").val(img);
            $("#downform").submit();

            $("#"+imgId).html("");
        }
    });
    
    $('#blackLayer').bPopup().close();

}


/*
 * params : chartId, imgId
 */
function chartScreenShotPm2(chartId, imgId) {

    $('#blackLayer').bPopup({
        modalClose: false,
        opacity: 0.6,
        positionStyle: 'absolute', //'fixed' or 'absolute'
        
        onOpen: function() { 
        },						
        follow: [false,true],
        scrollBar: true	
    });

    $("#"+imgId).html("");
    d3.selectAll("#"+chartId + " .line-path").attr("fill","none");
    /*
    var html = d3.select("#chart")
        .attr("version", 1.1)
        .attr("xmlns", "http://www.w3.org/2000/svg")
        .node().parentNode.innerHTML;*/
	var svgLength = $("#"+chartId+" svg").length;
    var html = $("#"+chartId+" svg:eq("+(svgLength-1)+")")
        .attr("version", 1.1)
        .attr("xmlns", "http://www.w3.org/2000/svg")
        .parent().html();

    var imgsrc = 'data:image/svg+xml;base64,'+ btoa(unescape(encodeURIComponent(html)));
    var img = '<img src="'+imgsrc+'">';
    d3.select("#"+imgId).html(img);
	
    html2canvas($("#"+imgId), {
        background:'#fff',
        useCORS: true,
        proxy: '/etc/proxy_image',
        onrendered : function(canvas) {
            var img = canvas.toDataURL("image/png");

            $("#image").val(img);
            $("#downform").submit();

            $("#"+imgId).html("");
        }
    });

    $('#blackLayer').bPopup().close();

}

function chartScreenShotCanvas(chartId){
	html2canvas($("#"+chartId), {
        background:'#fff',
        useCORS: true,
        proxy: '/etc/proxy_image',
        onrendered : function(canvas) {
            var img = canvas.toDataURL("image/png");

            $("#image").val(img);
            $("#downform").submit();
        }
    });
}

function chartScreenShotCanvas_test(chartId){
	d3.selectAll("#"+chartId + " .domain").attr("fill","none");
	d3.selectAll("#"+chartId + " .terms").attr("font-size","11px");
	html2canvas($("#"+chartId), {
        background:'#fff',
        useCORS: true,
        proxy: '/etc/proxy_image',
        onrendered : function(canvas) {
            var img = canvas.toDataURL("image/png");

            $("#image").val(img);
            $("#downform").submit();
        }
    });
}