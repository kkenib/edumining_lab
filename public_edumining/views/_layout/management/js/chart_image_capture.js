// jquery v1.9 removed
jQuery.browser = {};
(function () {
	jQuery.browser.msie = false;
	jQuery.browser.version = 0;
	if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
		jQuery.browser.msie = true;
		jQuery.browser.version = RegExp.$1;
	}
})();


var ie11 = ((navigator.appName == 'Netscape' && navigator.userAgent.search('Trident') != -1) || ($.browser.msie == true && $.browser.version <= 11) || ($.browser.safari == true));


function setElementStyle(targetId) {
	if($(targetId + " svg").is("[set-element-style=true]")) return;

	var styleDefs = "";
	var sheets = document.styleSheets;
	for (var i = 0; i < sheets.length; i++) {
		if(!/(\/graph\/dc.css|\/graph_style.css)$/.test(sheets[i].href)) continue;

		var rules = sheets[i].cssRules;
		for (var j = 0; j < rules.length; j++) {
			var rule = rules[j];

			if (rule.style) {
				var selectorText = rule.selectorText;
				d3.selectAll(targetId + " " + selectorText).style(makeStyleObject(rule));
			}
		}
	}

	$(targetId + " svg").attr("set-element-style", "true");
}


function makeStyleObject(rule) {
	var styleDec = rule.style;
	var output = {};
	var s;

	for (s = 0; s < styleDec.length; s++) {
		output[styleDec[s]] = styleDec[styleDec[s]];
		if(styleDec[styleDec[s]] === undefined) {
			//firefox being firefoxy
			output[styleDec[s]] = styleDec.getPropertyValue(styleDec[s])
		}
	}

	return output;
}

function graphDownload(canvas, fileTitle) {
	try {
		document.downFrm.data.value = canvas.toDataURL("image/png");
		document.downFrm.fileName.value = fileTitle+".png";
		document.downFrm.type.value = "svg";
		document.downFrm.submit();
	} catch (e) {
		alert(e);
	}	
}

function graphDownloadIE11(data, fileTitle) {
	try {
		//document.downFrm.data.value = JSON.stringify(data);
		document.downFrm.data.value = data;
		document.downFrm.fileName.value = fileTitle+".svg";
		document.downFrm.type.value = "svg";
		document.downFrm.submit();
	} catch (e) {
		alert(e);
	}	
}

