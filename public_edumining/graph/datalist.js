/**
 * data : [{}]
 * #graph1_tmpl 필수
 */
var graph_datalist = function(id, afterFunc, displayLoading, displayAlert) {
	$(id).data("graph-name", "datalist");

	var fn = {},
		targetId = id,
		displayLoading = displayLoading,
		displayAlert = displayAlert;
		resetContents = $(targetId).html();

	fn.reset = function() { 
		$(targetId).children().remove();
		if($.trim(resetContents) != "") {
			$(targetId).next(".graph_error").remove();
			$(targetId).append(resetContents);
		}
	};

	!(function() {
		$(targetId).unbind("reload").on("reload", function() {
			load_graph(targetId, function(jsonData) {
				if($(targetId + "_tmpl").is("script")) {
					$(targetId).children().remove();
					$(targetId).append($(targetId + "_tmpl").tmpl({data: jsonData}));
				}
			}, afterFunc, function() { fn.reset() }, displayLoading, displayAlert);
		})
		.unbind("reset").on("reset", function() {
			fn.reset();
		})
		.trigger("reload");
	})();

	this.targetId = targetId;
};

graph_datalist.reset = function(targetId) {
	$(targetId).trigger("reset");
};

graph_datalist.prototype.reset = function() {
	$(this.targetId).trigger("reset");
};

graph_datalist.reload = function(targetId) {
	$(targetId).trigger("reload");
};

graph_datalist.prototype.reload = function() {
	$(this.targetId).trigger("reload");
};
