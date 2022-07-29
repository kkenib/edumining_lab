/**
 * 에듀마이닝 - 텍스트 마이닝
 * 페이지에서 사용 되는 JS
 * @date	2021-12-22
 * @author	GEPARK
 */

/* 차트 이미지 다운로드 */
function download_mychart(idx) {

	let chartData = ''
	$.ajax({
		url: '/edumining/analysis/getVisualData',
		method: 'post',
		data: {
			"no" : idx,
		},
		dataType: 'json',
		async: false,
		success: function (json) {
			chartData = json.data
		}
	})
	// console.log("chartData")
	// console.log(chartData)

	$.ajax({
    	url: '/edumining/analysis/get_visual_one',
    	method: 'post',
    	data: {
    		"no" : idx,
			"chart_data": chartData
    	},
    	dataType: 'json',
    	success : function(json){
        	console.log(json);
        	var down_div = document.createElement('div');
        	down_div.setAttribute("id", "down_div");
        	down_div.style.visibility = 'hidden';
        	down_div.style.position = 'absolute';
        	down_div.style.top = '0px';
        	down_div.style.width = '900px';
        	down_div.style.height = '400px';
        	document.body.appendChild(down_div);

        	if (json.chart_info.visual_text_color) {
        		var text_color = json.chart_info.visual_text_color.split(",");
        	}
    		if (json.chart_info.visual_bg_color) {
    			var bg_color = json.chart_info.visual_bg_color.split(",");
    		}
    		var data_list = json.data;
    		var anal_type = json.chart_info.anal_type;
    		
    		if (anal_type == 0) {	// 빈도, 감성
    			var data = change_color_wordcloud(data_list, text_color[0], text_color[1], text_color[2]);
        		var chart = make_wordcloud("down_div", data);
			} else if (anal_type == 1) {	// 연관어
    			var data =  [{
    				"word": json.chart_info.center_word,
    				"children": data_list
    		    }];
    			
    			data = change_color_egoNetwork(data, bg_color[0], bg_color[1], bg_color[2], text_color[0], text_color[1], text_color[2])
    			var chart = make_egoNetworad("down_div", data);
    			
    		} else if (anal_type == 2) {	// 연결망
    			// 차트에 넣을 데이터
    			var tmp_data = {};
    			var tmp_list = [];
    			// 부모 노드 확인을 위한 dict
    			var total_dict = {};
    			// 선택된 키워드 리스트
    			var chart_dict = [];
    			// 차트 데이터
    			var data = [];
    			
    			data_list.forEach(function(row, idx){
    				chart_dict.push(row);

    				var word1 = row['word'];
    				var word2 = row['word2'];

    				if (word1 in total_dict) {
    					total_dict[word1] = total_dict[word1] + 1;
    				
    				} else {
    					total_dict[word1] = 1;
    				}

    				if (word2 in total_dict) {
    					total_dict[word2] = total_dict[word2] + 1;
    				
    				} else {
    					total_dict[word2] = 1;
    				}
    			});

    			// 부모리스트
    			var parents_dict = [];
    			for (var k in total_dict) {
    				
    				if (total_dict[k] > 1) {
    					parents_dict.push(k);

    					tmp_data[k] = {
    							'name': k,
    							'value': 0,
    							'linkWith': [],
    							'children': []
    					};
    				}
    			};
    			
    			chart_dict.forEach(function(row, idx){
    				var word1 = row['word'];
    				var word2 = row['word2'];
    				var count = Number(row['count']);

    				if ((parents_dict.includes(word1)) && (parents_dict.includes(word2))) {
    					tmp_data[word1]['value'] = tmp_data[word1]['value'] + count;
    					tmp_data[word2]['value'] = tmp_data[word2]['value'] + count;
    					
    					tmp_data[word1]['linkWith'].push(word2);
    					tmp_data[word2]['linkWith'].push(word1);
    					
    				} else if (parents_dict.includes(word1)) {
    					tmp_data[word1]['value'] = tmp_data[word1]['value'] + count;
    					tmp_data[word1]['children'].push({
    						'name': word2,
    						'value': count
    					});
    				}  else if (parents_dict.includes(word2)) {
    					tmp_data[word2]['value'] = tmp_data[word2]['value'] + count;
    					tmp_data[word2]['children'].push({
    						'name': word1,
    						'value': count
    					});
    				}
    			});
    			
    			for (var i in tmp_data) {
    				data.push(tmp_data[i]);
    			};

    			data = change_color_netword(data, bg_color[0], text_color[0])
    			var chart = make_networkChart("down_div", data);
    			
    		} else if (anal_type == 3) {	// 추이
    			var chart = make_lineChart("down_div", data_list);
    		} else if (anal_type == 4) {	// 감성

				var positive_chart = []
				var negative_chart = []
				for (var i=0; i<data_list.length; i++) {
					var data = data_list[i]
					var positive = Number(data_list[i]["positive"])
					var obj = {"word": data["word"], "count": data["count"]}

					if (positive === 1)
						positive_chart.push(obj)
					else
						negative_chart.push(obj)
				}

				var data = change_color_wordcloud_binary(positive_chart, negative_chart, text_color[0], text_color[1]);
				var chart = make_wordcloud("down_div", data);
			}

			// 차트 다운로드
    		var title = '';
    		if (anal_type == 0) {
    			title = "빈도분석_" + json.chart_info.title;
    		} else if (anal_type == 1) {
    			title = "연관어분석_" + json.chart_info.title;
    		} else if (anal_type == 2) {
    			title = "연결망분석_" + json.chart_info.title;
    		} else if (anal_type == 3) {
    			title = "추이분석_" + json.chart_info.title;
    		} else if (anal_type == 4) {
    			title = "감성분석_" + json.chart_info.title;
    		}
    		chart.exporting.filePrefix = title;

    		chart.exporting.export("png").then(function(imgData) {
    			var remove_div = document.getElementById('down_div');
            	remove_div.remove();
    		});

    	},
    	error: function(xhr, status, error) {
    		console.log(error);
		},
		complete: function() {
		} 
	});
}

/* 그래프 로딩 */
function load_graph(id, chart_data, type) {
	// 기존 그래프 삭제
	am4core.disposeAllCharts();

	if (type == "wordcloud") {
		make_wordcloud(id, chart_data);
		
	} else if (type == "egoNetwork") {
		make_egoNetworad(id, chart_data);
		
	} else if (type == "network") {
		make_networkChart(id, chart_data);
		
	} else if (type == "trend") {
		make_lineChart(id, chart_data);
		
	}
}

/* 워드클라우드 */
function make_wordcloud(id, chart_data) {
	// 기존 그래프 삭제
	// am4core.disposeAllCharts();

	am4core.useTheme(am4themes_animated);
	
	var chart = am4core.create(id, am4plugins_wordCloud.WordCloud);

	var series = chart.series.push(new am4plugins_wordCloud.WordCloudSeries());
	series.randomness = 0.1;
	series.rotationThreshold = 0.5;
	console.log(chart_data);
	series.data = chart_data;
	series.dataFields.word = "word";
	series.dataFields.value = "count";
	series.colors = new am4core.ColorSet();
	series.colors.passOptions = {};
	series.labels.template.propertyFields.fill = "color";
	series.minFontSize = am4core.percent(7);
	series.maxFontSize = am4core.percent(20);
	
	series.labels.template.tooltipText = "{word}: {value}";

	// 마우스 오버시
	//var hoverState = series.labels.template.states.create("hover");
	//hoverState.properties.fill = am4core.color("#FF0000");
	return chart;
}

/* 워드클라우드 생성시 전체 색상 설정 (차트데이터, 상, 중, 하) */
function change_color_wordcloud(data, color1, color2, color3) {
	
	var cnt = parseInt(data.length/3);

	data.forEach(function(row, idx){
		if (idx < cnt) {
			row['color'] = am4core.color("#"+color1);
			
		} else if (idx < cnt*2) {
			row['color'] = am4core.color("#"+color2);
			
		} else {
			row['color'] = am4core.color("#"+color3);
		}
	});

	return data;
}

/* 워드클라우드 생성시 전체 색상 설정 (차트데이터, 긍정, 부정) */
function change_color_wordcloud_binary(positive_data, negative_data, color1, color2) {
	positive_data.forEach(function(row, idx) {
		row['color'] = am4core.color("#"+color1);
	})

	negative_data.forEach(function(row, idx) {
		row['color'] = am4core.color("#"+color2);
	})

	console.log(positive_data)
	console.log(negative_data)

	var data = []
	positive_data.forEach(function(row, idx) {
		data.push(row)
	})

	negative_data.forEach(function(row, idx) {
		data.push(row)
	})
	return data
}

/* 라인 차트 */
function make_lineChart(id, chart_data) {
	
	am4core.useTheme(am4themes_animated);

	var chart = am4core.create(id, am4charts.XYChart);
	chart.colors.step = 2;
	chart.data = chart_data;
	
	// X축 리스트
	var keyword_list = []
	if (chart_data.length > 0) {
		Object.keys(chart_data[0]).forEach(function(k){
			if (k != 'xvalue') {
				keyword_list.push(k);
			}
		});
	}

	// Create category axis
	var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
	categoryAxis.dataFields.category = "xvalue";
	categoryAxis.renderer.labels.template.adapter.add("textOutput", function(text) {
		  return text + " 챕터";
	});

	// Create value axis
	var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.renderer.line.strokeOpacity = 1;
    valueAxis.renderer.line.strokeWidth = 2;
	
    var color_list = ["#dc6788", "#67b7dc", "#dc8c67", "#dcd267", "#67dcbb", "#a367dc", "#6771dc", "#dc67ce", "#67dc75", "#a0dc67"];

	keyword_list.forEach(function(keyword, idx){
		
        var series = chart.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = keyword;
        series.dataFields.categoryX = "xvalue";
        series.strokeWidth = 2;
        series.name = keyword;
        series.tooltip.fontSize = 10;
        series.stroke = am4core.color(color_list[idx]);
        //series.tooltipText = "{name}: [bold]{valueY}[/]";

        var bullet = series.bullets.push(new am4charts.CircleBullet());
        bullet.circle.stroke = am4core.color(color_list[idx]);
        bullet.circle.fill = am4core.color(color_list[idx]);
        bullet.circle.strokeWidth = 2;
	});

	chart.tooltip.label.maxWidth = 150;
	
	chart.legend = new am4charts.Legend();

	chart.cursor = new am4charts.XYCursor();
	
	return chart;
}

/* 에고네트워크 */
function make_egoNetworad(id, chart_data) {
	// 기존 그래프 삭제
	// am4core.disposeAllCharts();
	
	// Themes begin
	am4core.useTheme(am4themes_animated);
	// Themes end

	var chart = am4core.create(id, am4plugins_forceDirected.ForceDirectedTree);

	var networkSeries = chart.series.push(new am4plugins_forceDirected.ForceDirectedSeries())
	networkSeries.data = chart_data;

	networkSeries.dataFields.linkWith = "linkWith";
	networkSeries.dataFields.name = "word";
	networkSeries.dataFields.id = "word";
	networkSeries.dataFields.value = "count";
	networkSeries.dataFields.children = "children";
	networkSeries.dataFields.color = "color";

	var nodeTemplate = networkSeries.nodes.template;
	nodeTemplate.label.propertyFields.fill = "font";		// 글자색
	nodeTemplate.tooltipText = "{word} : {value}";
	nodeTemplate.fillOpacity = 1;
	
	networkSeries.nodes.template.label.text = "{word}"
	networkSeries.fontSize = 13;
	networkSeries.maxLevels = 2;
	networkSeries.minRadius = am4core.percent(4);
	networkSeries.maxRadius = am4core.percent(10);
	networkSeries.manyBodyStrength = -16;
	
	return chart;
}

/* 에고네트워크 생성시 전체 색상 설정 (차트데이터, 배경 상, 중, 하, 폰트 상, 중, 하) */
function change_color_egoNetwork(data, bg1, bg2, bg3, text1, text2, text3) {
	var children = data[0]['children'];
	var cnt = parseInt(children.length/3);

	
	data[0]['children'].forEach(function(row, idx){
		if (idx < cnt) {
			row['color'] = "#" + bg1;
			row['font'] = "#" + text1;

		} else if (idx < cnt*2) {
			row['color'] = "#" + bg2;
			row['font'] = "#" + text2;
			
		} else {
			row['color'] = "#" + bg3;
			row['font'] = "#" + text3;
		}
	});

	return data;
}

/* 네트워크 차트 */
function make_networkChart(id, chart_data) {
	// 기존 그래프 삭제
	//am4core.disposeAllCharts();
	
	// Themes begin
	am4core.useTheme(am4themes_animated);
	// Themes end

	var chart = am4core.create(id, am4plugins_forceDirected.ForceDirectedTree);
	
	var networkSeries = chart.series.push(new am4plugins_forceDirected.ForceDirectedSeries())
	networkSeries.data = chart_data;
	networkSeries.dataFields.linkWith = "linkWith";
	networkSeries.dataFields.name = "name";
	networkSeries.dataFields.id = "name";
	networkSeries.dataFields.value = "value";
	networkSeries.dataFields.children = "children";
	networkSeries.dataFields.color = "color";
	networkSeries.minRadius = 20
	networkSeries.maxRadius = 60

	networkSeries.nodes.template.label.text = "{name}"
	networkSeries.fontSize = 12;		// 폰트 사이즈
	networkSeries.linkWithStrength = 0;

	var nodeTemplate = networkSeries.nodes.template;
	nodeTemplate.tooltipText = "{name}";
	nodeTemplate.fillOpacity = 1;
	nodeTemplate.label.propertyFields.fill = "font";		// 글자색
	nodeTemplate.label.fill = "#0F0F0F";		// 글자색


	var linkTemplate = networkSeries.links.template;
	linkTemplate.propertyFields.strokeWidth = "linkWidth";
	// linkTemplate.strokeWidth = 2;		// 선 굵기

	// 마우스 오버시
	var linkHoverState = linkTemplate.states.create("hover");
	linkHoverState.properties.strokeOpacity = 1;
	linkHoverState.properties.strokeWidth = 2;

	nodeTemplate.events.on("over", function (event) {
	    var dataItem = event.target.dataItem;
	    dataItem.childLinks.each(function (link) {
	        link.isHover = true;
	    })
	})

	nodeTemplate.events.on("out", function (event) {
	    var dataItem = event.target.dataItem;
	    dataItem.childLinks.each(function (link) {
	        link.isHover = false;
	    })
	})
	
	return chart;
}

/* 네트워크 차트 생성시 전체 색상 설정 (차트데이터, 배경색, 글자색) */
function change_color_netword(data, color1, color2) {
	data.forEach(function(row, idx){
		row['color'] = am4core.color("#"+color1);
		row['font'] = am4core.color("#"+color2);
	});

	return data;
}

/* 원본 데이터 정보 가져오기 */
function get_data_info(idx) {
	$.ajax({
		url: '/edumining/analysis/get_rawdata_one',
		method: 'post',
		data: { 'data_no': idx },
		dataType: 'json',
		success : function(json){
			const data = json.data;
			localStorage.setItem("chapter_count", data.chapter_count)

			const dataType   = data['data_type'];
			const textCount  = data.text_count
			const startDate  = data.collection_start_date
			const endDate    = data.collection_end_date
			const collectionUnit = data.collection_unit
			const textCountValue    = (dataType === "수집 데이터") ? getCollectionDateText(startDate, endDate, collectionUnit) : getTextCountText(textCount)
			const chapterCountValue = (dataType === "제공 데이터") ? data.chapter_count : getDataSizeText(data.data_size)
			const authorValue       = (dataType === "수집 데이터") ? data.collection_keyword : getAuthorText(data.author)
			const genreValue        = (dataType === "수집 데이터") ? "네이버 뉴스"           : getAuthorText(data.genre)

			$("#data_name").text(data.data_name);
			$("#data_type").append(data.data_type);
			$("#update_date").text(data.update_date.substr(0, 10));
			$("#collection_state").text(data.collection_state);
			$("#author").text(authorValue);
			$("#genre").text(genreValue);
			$("#text_count").text(textCountValue);
			$("#chapter_count").text(chapterCountValue);

			if (dataType === "수집 데이터") {
				$("#author").parent().find('dt')[0].innerHTML = "키워드"
				$("#genre").parent().find('dt')[0].innerHTML = "채널"
				$("#text_count").parent().find('dt')[0].innerHTML = "수집 기간"
				$("#chapter_count").parent().find('dt')[0].innerHTML = "용량";
			} else if (dataType === "보유 데이터") {
				$("#author").parent().remove();
				$("#genre").parent().remove();
				$("#collection_state").parent().remove();
				$("#text_count").parent().remove();
				$("#chapter_count").parent().find('dt')[0].innerHTML = "용량";
			}

			// 챕터 체크박스 HTML 생성
			if (dataType === "수집 데이터") {
				set_chapter_checkbox_by_date(startDate, endDate);
			} else {
				set_chapter_checkbox(data.chapter_count);
			}

			function getAuthorText(author)       { return (author == null ? '' : author) }
			function getGenreText(genre)         { return (genre == null ? '' : genre) }
			function getTextCountText(textCount) { return textCount == null ? '0' : numberComma(textCount) }
			function getDataSizeText(dataSize)   {
				const orginDataSize = parseFloat(dataSize) / 1024
				dataSize = ((orginDataSize > 1024) ? (orginDataSize / 1024) : orginDataSize).toFixed(2)
				return dataSize + ((orginDataSize > 1024) ? " MB" : " KB")
			}
			function getCollectionDateText(startDate, endDate, collectionUnit) {
				const replacedStartDate = startDate.replaceAll("-", ".").substr(0, collectionUnit === 'D' ? 10 : 7)
				const replacedEndDate   = endDate.replaceAll("-", ".").substr(0, collectionUnit === 'D' ? 10 : 7)
				return replacedStartDate + " ~ " + replacedEndDate
			}
		},
		error: function(xhr, status, error) {
			console.log(error);
		}
	});
}

/* 데이터 시각화 목록 */
function get_visual_list(rpt_chk = 0) {
	//rpt_chk = 0 데이터분석쪽 , rpt_chk = 1 리포트쪽
	$.ajax({
    	url: '/edumining/analysis/get_visual_list',
    	method: 'post',
    	data: {
    	},
    	dataType: 'json',
    	success : function(json){
    		// console.log(json);
    		var data = json.data;

    		$("#vislist_scroll > div.vislist_wrap").html();
    		var html = '';
    		
    		data.forEach(function(li, idx){
    			var type = li.anal_type;
    			var type_nm;
    			var img_src;
    			
    			if (type == 0) {
    				img_src = '<img src="/views/_layout/analysis/images/vis_img01.png" alt="">';
	    			type_nm = "워드클라우드";
	    			
    			} else if (type == 1) {
    				img_src = '<img src="/views/_layout/analysis/images/vis_img02.png" alt="">';
    				type_nm = "연관어분석";
    				
    			} else if (type == 2) {
    				img_src = '<img src="/views/_layout/analysis/images/vis_img04.png" alt="">';
    				type_nm = "연결망분석";
    				
    			} else if (type == 3) {
    				img_src = '<img src="/views/_layout/analysis/images/vis_img03.png" alt="">';
    				type_nm = "추이분석";
    				
    			} else if (type == 4) {
    				img_src = '<img src="/views/_layout/analysis/images/vis_img01.png" alt="">';
    				type_nm = "감성분석";
    			}

	    		html += '<div class="vis_box">';
	    		
	    		html += '<div class="vis_boxwrap">';
				if(rpt_chk == 1){
					html += '<span><input type="checkbox" id="chk'+idx+'" name="vis_list_check" value="'+li.no+'"><label for="chk'+idx+'"></label></span>'
	    		}
				html += '<div class="disinblock">';
	    		html += 	'<button class="btn sm lightgrey" onclick="delete_visual_one(' + li.no + ','+rpt_chk+')"><i class="fas fa-trash"></i></button>';
	    		html += 	'<button class="btn sm grey" onclick="download_mychart(' + li.no + ')"><i class="fas fa-download"></i></button>';
	    		html += '</div>';
	    		
	    		html += '<div class="floatr pt3">';
	    		html += '<span class="vis_tooltip set">';
	    		html +=		'<i class="fas fa-exclamation-circle"></i>';
	    		html += 	'<span class="">';
	    		html += 	'<ul>';
	    		html += 	'<li>' + type_nm + '</li>';

	    		if (type == 3) {
	    			html += 	'<li>분석데이터: ' + li.data_name + '</li>';
	    		} else {
	    			html += 	'<li>분석데이터: ' + li.data_name + ' / ' + li.chapter + '장</li>';
	    		}
	    		
	    		if (type == 1) {
    	    		html += 	'<li>중심단어: ' + (li.center_word==null? '':li.center_word) + '</li>';
    	    		html += 	'<li>동시 출현 범위: ' + (li.window_size==null? '':li.window_size) + '</li>';
    				
    			} else if (type == 2) {
    	    		html += 	'<li>동시 출현 범위: ' + (li.window_size==null? '':li.window_size) + '</li>';
    				
    			} else if (type == 3) {
    	    		html += 	'<li>선택한 키워드: ' + li.center_word + '</li>';
    			} 
	    		
	    		html += 	'</ul>';
	    		html += 	'</span>';
	    		html += '</span>';
	    		html += '</div>';
	    		html += '</div>';
	    		
	    		html += '<div class="vgragh">';
	    		html += img_src;
	    		html += '</div>';
	    		html += '<dl>';
	    		html += 	'<dt>제목</dt>';
	    		html += 	'<dd>' + li.title + ' / ' + type_nm + '</dd>';
	    		html += '</dl>';
	    		html += '</div>';

    		});
    		
    		$("#vislist_scroll > div.vislist_wrap").html(html);
    	},
    	error: function(xhr, status, error) {
    		console.log(error);
    	}
	});
}

/* 데이터 시각화 삭제 */
function delete_visual_one(idx, rpt_chk) {
	if(!confirm("시각화를 정말 삭제하시겠습니까?")) {
		return;
	}

	let deleteResult = false
	$.ajax({
		url: '/edumining/analysis/deleteVisualData',
		method: 'post',
		data: {
			'no': idx
		},
		dataType: 'json',
		async: false,
		success: function (json) {
			deleteResult = true
		}
	})
	if (!deleteResult) {
		alert("데이터를 삭제할 수 없습니다.")
		return
	}

	$.ajax({
    	url: '/edumining/analysis/delete_visual_one',
    	method: 'post',
    	data: {
    		'no': idx
    	},
    	dataType: 'json',
    	success : function(json){
    		// console.log(json);
    		
    		if (json.data) {
    			alert("삭제되었습니다.")
    		} else {
    			alert("삭제를 실패했습니다.")
    		}

    		// 데이터 시각화 목록 reload
			get_visual_list(rpt_chk);
				
    	},
    	error: function(xhr, status, error) {
    		console.log(error);
    	}
	});
}

function download_sentiment_excel(data_list, table_name, file_name) {
	if (data_list.length == 0) {
		alert("다운받을 데이터가 없습니다. 데이터를 생성해주세요.");
		return;
	}

	var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
	tab_text += '<head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">';
	tab_text += '<xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>'
	tab_text += '<x:Name>Sheet</x:Name>';
	tab_text += '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
	tab_text += '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';

	//tab_text += "<table border='1px'>";
	tab_text += "<table>";
	tab_text += "<tr>";

	var table_columns = $(`table.${table_name} > thead > tr`).find("th").slice(0, -1);
	table_columns.each(function(){
		var text = $(this).html();
		// console.log(text);
		tab_text += "<td>" + text + "</td>";
	});
	tab_text += "</tr>";

	var table_data = $(`table.${table_name} > tbody > tr`);
	table_data.each(function(){
		var row = $(this).find("td").slice(0, -1);

		tab_text += "<tr>";
		row.each(function(){
			var text = $(this).html();
			// console.log(text);
			tab_text += "<td>" + text + "</td>";
		});
		tab_text += "</tr>";
	});

	tab_text += '</table></body></html>';

	var data_type = 'data:application/vnd.ms-excel';
	var ua = window.navigator.userAgent;
	var msie = ua.indexOf("MSIE");

	var fileName = file_name + '.xls';

	// IE 환경에서 다운로드
	if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
		if (window.navigator.msSaveBlob) {
			var blob = new Blob([tab_text], {
				type: "application/csv;charset=utf-8;"
			});
			navigator.msSaveBlob(blob, fileName);
		}
	} else {
		var blob2 = new Blob([tab_text], {
			type: "application/csv;charset=utf-8;"
		});

		var elem = window.document.createElement('a');
		elem.href = window.URL.createObjectURL(blob2);
		elem.download = fileName;
		document.body.appendChild(elem);
		elem.click();
		document.body.removeChild(elem);
	}
}

/* 데이터 다운로드 */
function download_excel(file_name) {
	
	if (data_list.length == 0) {
		alert("다운받을 데이터가 없습니다. 데이터를 생성해주세요.");
		return;
	}
	
    var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
    tab_text += '<head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">';
    tab_text += '<xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>'
    tab_text += '<x:Name>Sheet</x:Name>';
    tab_text += '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
    tab_text += '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';

    //tab_text += "<table border='1px'>";
    tab_text += "<table>";
    tab_text += "<tr>";
    
    var table_columns = $("table.tbl01 > thead > tr").find("th").slice(0, -1);
    table_columns.each(function(){
		var text = $(this).html();
		// console.log(text);
		tab_text += "<td>" + text + "</td>";
	});
    tab_text += "</tr>";
	
    var table_data = $("table.tbl01 > tbody > tr");
    table_data.each(function(){
		var row = $(this).find("td").slice(0, -1);

		tab_text += "<tr>";
		row.each(function(){
			var text = $(this).html();
			// console.log(text);
			tab_text += "<td>" + text + "</td>";
		});
		tab_text += "</tr>";
	});

    tab_text += '</table></body></html>';
    
    var data_type = 'data:application/vnd.ms-excel';
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE");
    
    var fileName = file_name + '.xls';
    
    // IE 환경에서 다운로드
    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
        if (window.navigator.msSaveBlob) {
            var blob = new Blob([tab_text], {
            type: "application/csv;charset=utf-8;"
        });
        	navigator.msSaveBlob(blob, fileName);
        }
    } else {
        var blob2 = new Blob([tab_text], {
			type: "application/csv;charset=utf-8;"
    	});
    
     var elem = window.document.createElement('a');
     elem.href = window.URL.createObjectURL(blob2);
     elem.download = fileName;
     document.body.appendChild(elem);
     elem.click();
     document.body.removeChild(elem);
    }
}

/* 천단위 콤마 */
function numberComma(number) {
	return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

/* 챕터 체크박스 HTML 생성 */
function set_chapter_checkbox_by_date(start_date, end_date){

	function getDateRangeData(param1, param2){  //param1은 시작일, param2는 종료일이다.
		var res_day = [];
		var ss_day = new Date(param1);
		var ee_day = new Date(param2);
		while(ss_day.getTime() <= ee_day.getTime()){
			var _mon_ = (ss_day.getMonth()+1);
			_mon_ = _mon_ < 10 ? '0'+_mon_ : _mon_;
			var _day_ = ss_day.getDate();
			_day_ = _day_ < 10 ? '0'+_day_ : _day_;
			res_day.push(ss_day.getFullYear() + '-' + _mon_ + '-' +  _day_);
			ss_day.setDate(ss_day.getDate() + 1);
		}
		return res_day;
	}

	const dates = getDateRangeData(start_date, end_date)

	var html = '';

	html += '<input type="checkbox" id="allChapter" value="전체" name="data_total" onclick="allCheckBnt()"><label for="allChapter">전체</label>';

	for (var i=0; i < dates.length; i++) {
		const date = dates[i]
		var id = date
		var index = i + 1
		html += '<input type="checkbox" id="' + id + '" value="'+ index +'" name="data_analy"><label for="'+ id +'">'+ date + '</label>';
	}

	$("div.analy_check > span.chapter_inp").html(html);
}


/* 챕터 체크박스 HTML 생성 */
function set_chapter_checkbox(chapter_count){
	var html = '';
	
	html += '<input type="checkbox" id="allChapter" value="전체" name="data_total" onclick="allCheckBnt()"><label for="allChapter">전체</label>';
	
	for (var i=1; i <= chapter_count; i++) {
		var id = i + "챕터";
		html += '<input type="checkbox" id="' + id + '" value="'+ i +'" name="data_analy"><label for="'+ id +'">'+ i + '장</label>';
	}

	$("div.analy_check > span.chapter_inp").html(html);
}

/* 챕터 전체 선택 체크 박스 */
function allCheckBnt() {
	var chk_yn = $("input:checkbox[name=data_total]").prop("checked");
	
	if (chk_yn) {
		$("input:checkbox[name=data_analy]").prop("checked", true);
	} else {
		$("input:checkbox[name=data_analy]").prop("checked", false);
	}
}

/* 페이지 이동 */
function move_page(type) {
	var idx = $("#data_no").val();
	console.log(idx);
	
	var url = "";
	if (type == "1") {
		url = "/analysis/sub_analy01";
	} else if (type == "2") {
		url = "/analysis/sub_analy02";
	} else if (type == "3") {
		url = "/analysis/sub_analy03";
	} else if (type == "4") {
		url = "/analysis/sub_analy04";
	} else if (type == "5") {
		url = "/analysis/sub_analy05";
	}
	
	var frm = document.moveFrm;
	frm.action = url;
	frm.method = "GET";
	frm.data_no.value = idx;
	frm.submit();
}

HashMap = function(){  
    this.map = new Array();
};
HashMap.prototype = {  
    put : function(key, value){  
        this.map[key] = value;
    },  
    get : function(key){  
        return this.map[key];
    },  
    getAll : function(){  
        return this.map;
    },  
    clear : function(){  
        this.map = new Array();
    },  
    getKeys : function(){  
        var keys = new Array();  
        for(i in this.map){  
            keys.push(i);
        }  
        return keys;
    }
};

$.urlParam = function(name){
    var results = new RegExp('[\?&amp;]' + name + '=([^&amp;#]*)').exec(window.location.href);

	if(results != null) {
		return results[1] || 0;
	}

	return 0;
}

function save_mychart_data(param) {
	let saveFilename = ''
	let encodeChartData = ''
	$.ajax({
		url: '/edumining/analysis/encode_mychart_data',
		method: 'post',
		data: param,
		dataType: 'json',
		async: false,
		success : function(json){
			console.log(json);
			encodeChartData = json.data
			saveFilename = json.file_name
		},
		error: function(xhr, status, error) {
			console.log(error);
		}
	});

	let saveResult = false
	$.ajax({
		url: '/edumining/analysis/saveChartData',
		method: 'post',
		data: {
			user_id: param.user_id,
			chart_data: encodeChartData,
			file_name: saveFilename
		},
		dataType: 'json',
		async: false,
		success : function(json){
			saveResult = json.result
		}
	});

	if (!saveResult) {
		alert("저장할 수 없습니다.")
		return
	}
	
	param.file_name = saveFilename
	$.ajax({
		url: '/edumining/analysis/save_mychart_data',
		method: 'post',
		data: param,
		dataType: 'json',
		success : function(json){
			console.log(json);

			if (json.result == 'success') {
				alert("저장되었습니다.");
				$("#chart_title").val("");

			} else {
				alert(json.result);
			}

			// 데이터 시각화 목록 reload
			get_visual_list();
		},
		error: function(xhr, status, error) {
			console.log(error);
		}
	});
}