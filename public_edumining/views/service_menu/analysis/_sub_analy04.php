<?php 
$data_no = $this->input->post_get('data_no');
$user_id = $this->member->item('mem_userid');
?>
<!-- 연결망분석페이지 -->
<section class="visualarea sub">
	<div class="wrapper">
			<div class="subvisual_bg analy">
				<div class="subvisual_box">
					<h2 class="bold">데이터 <b>분석</b></h2>
					<p>
						다양한 분석 방법으로 데이터를 다뤄볼 수 있으며<br>
						데이터 시각화를 경험해 볼 수 있습니다.
					</p>
				</div>
			</div>
		</div>
</section>
<div class="wrapper">
	<!-- 데이터 정보 : S -->
	<section class="datainfo_area mt80">
			<div class="box_tit">
				<h2>데이터 정보</h2>
				<div class="floatr">
					<button class="btn grey floatr" onclick="location.href='/analysis/select_rawdata'">선택목록</button>
				</div>
			</div>
			<div class="grey_contbox">
				<input type="hidden" name="data_no" id="data_no" value="<?=$data_no?>">
				<div class="wh_box data_tit">
					<h3 id="data_name"></h3>
					<span id="data_type"><em></em></span>
				</div>
				<div class="data_cont">
					<div class="data_wrap">
						<dl>
							<dt>저자</dt>
							<dd id="author"></dd>
						</dl>
						<dl>
							<dt>수집 날짜</dt>
							<dd id="update_date"></dd>
						</dl>
					</div>
					<div class="data_wrap">
						<dl>
							<dt>장르</dt>
							<dd id="genre"></dd>
						</dl>
						<dl>
							<dt>챕터 수</dt>
							<dd id="chapter_count"></dd>
						</dl>
					</div>
					<div class="data_wrap">
						<dl>
							<dt>글자 수</dt>
							<dd id="text_count"></dd>
						</dl>
						<dl>
							<dt>수집 상태</dt>
							<dd id="collection_state"></dd>
						</dl>
					</div>
				</div>
			</div>
	</section>
	<!-- 데이터 정보 : E -->
	<!-- 분석 데이터 생성 : S -->
	<section class="analysis_area mt80">
		<div class="box_tit">
				<h2>분석 데이터 생성<em>다양한 방법으로 데이터를 분석해 보세요.</em></h2>
			</div>
			<div class="tab01 num05">
				<a href="javascript:void(0);" onclick="move_page(1)">빈도 분석</a>
				<a href="javascript:void(0);" onclick="move_page(2)">추이 분석</a>
				<a href="javascript:void(0);" onclick="move_page(3)">연관어 분석</a>
				<a href="javascript:void(0);" class="on" onclick="move_page(4)">연결망 분석</a>
				<a href="javascript:void(0);" onclick="move_page(5)">감성 분석</a>
			</div>
			<div class="grey_contbox tabplus">
				<div class="wh_box">
					<div class="analy_check">
						<strong>분석 데이터</strong>
						<span class="chapter_inp">
							<!-- <input type="checkbox" id="1챕터" name="data_analy"><label for="1챕터">1 장</label> -->
							<!-- <select name="" id=""><option value="">욕 좀 하는 이유나 / 1장</option></select> -->
						</span>
						<!-- <p>분석에 사용할 분석 데이터 정보에요.</p> -->
					 </div>
					  <div class="analy_check">
						<strong>동시 출현 범위</strong><span><input id="window_size" type="number" value="7"></span>
						<input type="hidden" id="window_size_hidden" value="">
						<!-- <p>최대 동시 몇 개까지 단어가 출현할 것인지를 정해주세요.</p> -->
					</div>
				</div>
				<div class="btnarea">
					<button class="btn pink" id="analy_btn" onclick="get_network()">데이터 생성</button>
				</div>
				<div class="contour_line"></div>
				<div class="mt40">
					<div class="cont_stit">
						<h3>키워드 선택<em>데이터 시각화에 포함할 키워드를 선택해 보세요.</em></h3>
						<button class="btn grey" onclick="download_excel('연결망분석')">데이터 다운로드</button>
					</div>
					<div class="tablearea keyword">
						<div class="scrollarea">
							<table class="tbl01" summary="키워드 선택 정보입니다">
								<caption>키워드 선택</caption>
								<thead>
									<tr>
                                        <th class="w20p">단어 1(<button id="sort_by_row_word" style="width:20px; font-size: 12px;">▼▲</button>)</th>
                                        <th class="w20p">단어 2(<button id="sort_by_column_word" style="width:20px; font-size: 12px;">▼▲</button>)</th>
										<th class="w20p">빈도수(건)</th>
										<th class="w20p">비율(%)</th>
                                        <th class="w20p">
                                            <input type="checkbox" name="check_all" id="check_all">
                                            <label for="check_all"></label>&nbsp;&nbsp;선택(<span id="checked_keyword_count">0/0</span>)
                                        </th>
									</tr>
								</thead>
								<tbody id="data_tbody">
									<tr>
										<td class="list_none" colspan="5">데이터가 없습니다.</td>
									</tr>
									<!-- <tr>
										<td>유나</td>
										<td>소미</td>
										<td>165</td>
										<td>3.57%</td>
										<td><span><input type="checkbox" id="chk1"><label for="chk1"></label></span></td>
									</tr> -->
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="btnarea mt20">
				<p>분석에 사용할 키워드 선택을 했다면 시각화 버튼을 눌러 진행해 주세요.</p>
				<button class="mt12 btn navy lg round" onclick="make_visualization()">시각화 하기</button>
			</div>
	</section>
	<!-- 분석 데이터 생성 : E -->
	<section class="datavis_area mt80 pb80">
		<div class="box_tit">
			<h2>데이터 시각화<em>시각화 된 분석 결과를 꾸며보고 목록에 저장하세요.</em></h2>
		</div>
		<div class="grey_contbox">

			<div class="vis_view">
				<div class="cont_stit">
					<h3>시각화 미리보기</h3>
				</div>
				<div class="visgraph_area"><!-- 시각화그래프영역 -->
					<div class="visgraph_cont">
						<div class="tac" id="graph"></div>
						<div class="vis_set">
							<h4>색상 선택</h4>
							<div class="mt10 ml10">
								<div class="colorchoicearea" id="graph1-right-top">
									<div>
										<div class="choicebox3">
											<span>배경색</span>
											<button class="jscolor {valueElement:'chosen-value1', onFineChange:'setBgColor1(this)'}">배경색</button>
											<input type="hidden" id="chosen-value1" value="fe7093" autocomplete="off">
										</div>
									</div>
									<div>
										<div class="choicebox3">
											<span>글자색</span>
											<button class="jscolor {valueElement:'chosen-value2', onFineChange:'setTextColor1(this)'}">글자색</button>
											<input type="hidden" id="chosen-value2" value="000000" autocomplete="off">
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
					<div class="visgraph_tit">
						<input type="text" id="chart_title" placeholder="등록할 시각화 제목을 입력해 주세요.">
						<button class="btn blue" onclick="save_myChart()">데이터 시각화 등록</button>
					</div>
				</div>
				
			</div>
			<div class="contour_line"></div>

			<div class="vislist_area">
				<div class="cont_stit">
					<h3>데이터 시각화 목록</h3>
				</div>
				<div class="vislist_scroll" id="vislist_scroll">
					<div class="vislist_wrap">
						<!-- <div class="vis_box">
							<div class="vis_boxwrap">
								<div class="disinblock">
									<button class="btn sm lightgrey"><i class="fas fa-trash"></i></button>
									<button class="btn sm grey"><i class="fas fa-download"></i></button>
								</div>
								<div class="floatr pt3">
									<span class="vis_tooltip set">
										<i class="fas fa-exclamation-circle"></i>
										<span class="">
											<ul>
												<li>연관어분석</li>
												<li>분석데이터: 욕 좀 하는 이유나 / 1장</li>
												<li>중심단어: 유나</li>
												<li>동시 출현 범위: 5</li>
											</dl>
										</span>
									</span>
								</div>
							</div>

							<div class="vgragh">
								<img src="/views/_layout/analysis/images/vis_img01.png" alt="">
							</div>
							<dl>
								<dt>제목</dt>
								<dd>욕 좀하는 이유나 / 워드클라우드</dd>
							</dl>
						</div> -->
					</div>
				</div>
			</div>

		</div>
	</section>
</div>

<form action="" id="moveFrm" name="moveFrm">
	<input type="hidden" name="data_no" value="">
</form>

<script type="text/javascript">

    matrixKeywordList.getInstance().attachEvent({
        sort_by_row_word: { event_type: "click", sort_data: "row_word" },
        sort_by_column_word: { event_type: "click", sort_data: "column_word" },
        check_all: { event_type: "change", chk_name: "chk" }
    })

var data_list = [];
var chart_data = [];
// 시각화 저장을 위한 데이터
file_data = [];

$(function() {
	var idx = $("#data_no").val();

	// 원본 데이터 정보 가져오기
	get_data_info(idx);

	// 데이터 시각화 목록 가져오기
	get_visual_list();
})

const sortByRowWordButton = document.getElementById("sort_by_row_word")
sortByRowWordButton.addEventListener("click", (e) => {
    matrixKeywordList.getInstance().sort("row_word")
    matrixKeywordList.getInstance().update()
})

const sortByColumnWordButton = document.getElementById("sort_by_column_word")
sortByColumnWordButton.addEventListener("click", (e) => {
    matrixKeywordList.getInstance().sort("column_word")
    matrixKeywordList.getInstance().update()
})

/* 배경색 */
function setBgColor1(picker) {
	var color = "#" + picker.toString();
	chart_data.forEach(function(row, idx){
		row['color'] = am4core.color(color);
	});

	load_graph("graph", chart_data, "network");
}

/* 글자색 */
function setTextColor1(picker) {
	var color = "#" + picker.toString();

	chart_data.forEach(function(row, idx){
		// row['font'] = am4core.color(color);
		row['font'] = color
	});

	load_graph("graph", chart_data, "network");
}

const intervalID = 
	() => {

		var data_no = $("#data_no").val();
		var user_id = "<?= $user_id ?>";
		let currentState = ''

		$.ajax({
			url: '/edumining/analysis/getNetworkAnalysisCurrentState',
			method: 'post',
			data: {
				"user_id" : user_id,
				"data_no" : data_no
			},
			dataType: 'json',
			async: false,
			success : function(json){ 
				console.log(json) 

				if (json.msg === "success") {
					console.log("SUCCESS!!")

					if(json.current_state === "WAIT" || json.current_state === "PROC")
						$("#analy_btn").text("연결망 분석 중");
					if(json.current_state === "FIN") {
						currentState = json.current_state
						$("#analy_btn").removeClass("disable_bg");
						$("#analy_btn").text("데이터 생성");
					}
				} 

			},
			error: function(xhr, status, error) { console.log(error); } 
		});

		if(currentState === "FIN") {

			console.log(currentState)

			var window_size = $("#window_size").val();
			var chapter_list = [];

			$("input:checkbox[name=data_analy]:checked").each(function(){
				chapter_list.push($(this).val());
			});

			$.ajax({
			url: '/edumining/analysis/updateNetworkAnalysisSchedule',
			method: 'post',
			data: {
					"user_id" : user_id,
					"data_no" : data_no,
					"chapter" : chapter_list.join(" "),
					"window_size": window_size,
					"current_state": "NONE"
				},
				dataType: 'json',
				async: false,
				success : function(json){ 
					console.log(json) 
					if (json.msg === "success") {
						clearInterval(intervalID)
						console.log("FINISHED")
					} 
				},
				error: function(xhr, status, error) { console.log(error); } 
			});
		}
		

	}


/* 연결망 분석 */
function get_network() {
	var data_no = $("#data_no").val();
	var user_id = "<?= $user_id ?>";
	var window_size = $("#window_size").val();
	var chapter_list = [];

	$("input:checkbox[name=data_analy]:checked").each(function(){
		chapter_list.push($(this).val());
	});

	if (chapter_list.length == 0) {
		alert("분석 데이터를 선택해주세요.");
		return;
	}
	if (window_size < 2 || window_size > 10) {
		alert("최소 2개, 최대 10개로 선택 가능합니다.");
		return;
	}
	
	$("#analy_btn").addClass("disable_bg");
	// $.ajax({
	// 	url: '/edumining/analysis/updateNetworkAnalysisSchedule',
	// 	method: 'post',
	// 	data: {
	// 		"user_id" : user_id,
	// 		"data_no" : data_no,
	// 		"chapter" : chapter_list.join(" "),
	// 		"window_size": window_size,
	// 		"current_state": "WAIT"
	// 	},
	// 	dataType: 'json',
	// 	async: false,
	// 	success : function(json){ 
	// 		console.log(json) 
	// 		if (json.msg == "failed") {
	// 			alert("데이터를 생성할 수 없습니다.")
	// 		} else {
	// 			setInterval(intervalID, 3000)
	// 			$("#analy_btn").text("연결망 분석 중");
	// 		}
	// 	},
	// 	error: function(xhr, status, error) { console.log(error); } 
	// });


	
	$.ajax({
        url: '/edumining/analysis/connect',
    	method: 'post',
    	data: {
    		"user_id" : user_id,
        	"data_no" : data_no,
        	"chapter" : chapter_list.join(" "),
        	"window_size": window_size
    	},
    	dataType: 'json',
    	success : function(json){
            matrixKeywordList.getInstance().clear()

            let sum = 0
            json.forEach(function(li, idx){
                sum += li[2];
            });

            json.forEach(function(li, idx){
                const keyword = matrixKeywordList.getInstance().createComponent()
                keyword.setAttribute({
                    index      : idx,
                    row_word   : li[0],
                    column_word: li[1],
                    frequency  : li[2],
                    rate       : ((li[2] / sum) * 100).toFixed(2),
                    check_view_id: idx
                })
                matrixKeywordList.getInstance().push(keyword)
            });

            data_list = [];
            matrixKeywordList.getInstance().iterate((component) => {
                data_list.push({'word': component.attribute.row_word, 'word2': component.attribute.column_word, 'count': component.attribute.frequency});
            })

            matrixKeywordList.getInstance().update()
            if (json.length > 0) {
                $("#window_size_hidden").val(window_size);
            }

			// 임시
			// make_visualization();
    	},
    	error: function(xhr, status, error) {
    		console.log(error);
		},
		complete: function() {
			$("#analy_btn").removeClass("disable_bg");
			$("#analy_btn").text("데이터 생성");
		} 
	});

}

/* 시각화 하기 (연결망 분석) */
function make_visualization() {
	var chk_list = $("input:checkbox[name=chk]:checked");
	var chk_cnt = chk_list.length;
	chart_data = [];

	if (data_list.length >= 10) {
    	if (chk_cnt < 10 || chk_cnt > 30){
    		alert("최소 10개, 최대 30개 까지 선택 가능합니다.");
    		return;
    	}
	}

	// 차트에 넣을 데이터
	var tmp_data = {};
	var tmp_list = [];
	file_data = [];

	// 부모 노드 확인을 위한 dict
	var total_dict = {};
	// 선택된 키워드 리스트
	var chart_dict = [];
	
	chk_list.each(function(){
		var index = $(this).val();
		var row = data_list[index];
		chart_dict.push(row);
		file_data.push(row);
		
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

	// console.log(chart_dict);
    // 	console.log(total_dict);
    // 	console.log(parents_dict);


    let totalCount = 0
    chart_dict.forEach(function(row, idx){
        totalCount += row['count'];
    })

    const tuneValue = 100
	chart_dict.forEach(function(row, idx){
		var word1 = row['word'];
		var word2 = row['word2'];
		var count = row['count'];

		if ((parents_dict.includes(word1)) && (parents_dict.includes(word2))) {
			tmp_data[word1]['value'] = tmp_data[word1]['value'] + count;
			tmp_data[word2]['value'] = tmp_data[word2]['value'] + count;

			tmp_data[word1]['linkWith'].push(word2);
			tmp_data[word2]['linkWith'].push(word1);

            tmp_data[word1]['children'].push({
                'name': word2,
                'value': count,
                'linkWidth': (count / totalCount) * tuneValue
            });

            tmp_data[word2]['children'].push({
                'name': word1,
                'value': count,
                'linkWidth': (count / totalCount) * tuneValue
            });
			
		} else if (parents_dict.includes(word1)) {
			tmp_data[word1]['value'] = tmp_data[word1]['value'] + count;
			tmp_data[word1]['children'].push({
				'name': word2,
				'value': count,
                'linkWidth': (count / totalCount) * tuneValue
			});
		}  else if (parents_dict.includes(word2)) {
			tmp_data[word2]['value'] = tmp_data[word2]['value'] + count;
			tmp_data[word2]['children'].push({
				'name': word1,
				'value': count,
                'linkWidth': (count / totalCount) * tuneValue
			});
		}
	});
	
	for (var i in tmp_data) {
		chart_data.push(tmp_data[i]);
	};

	var bg1 = $("#chosen-value1").val();
	var text1 = $("#chosen-value2").val();

	chart_data = change_color_netword(chart_data, bg1, text1)
	load_graph("graph", chart_data, "network");
}

/* 데이터 시각화 등록 */
function save_myChart() {
	var user_id = "<?= $user_id ?>";
	var data_no = $("#data_no").val();
	var chart_title = $("#chart_title").val();
	var chapter_list = [];
	var window_size = $("#window_size_hidden").val();
	var bg_color = $("#chosen-value1").val();
	var text_color = $("#chosen-value2").val();
	
	if (user_id == "") {
		alert("로그인시 이용하실 수 있습니다.");
		return;
	}
	
	if ($.trim(chart_title) == "") {
		alert("등록할 시각화 제목을 입력해 주세요.");
		return;
	}

	$("input:checkbox[name=data_analy]:checked").each(function(){
		chapter_list.push($(this).val());
	});

    const param = {
        "title": chart_title,
        "data": JSON.stringify(file_data),
        "origin_no": data_no,
        "user_id": "<?= $this->member->item('mem_userid');?>",
        "chapter": chapter_list.join(","),
        "window_size": window_size,
        "anal_type": 2,
        "text_color": text_color,
        "bg_color": bg_color,
        "file_name": ''
    }
    save_mychart_data(param)
    
}


</script>

