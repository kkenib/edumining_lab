<script>
    const agent = window.navigator.userAgent.toLowerCase()
    if (agent.indexOf("trident") > -1) {
        alert("원활한 서비스 이용을 위해 크롬 브라우저를 이용해주세요.")
    }
</script>
<?php
$data_no = $this->input->post_get('data_no', TRUE);
$user_id = $this->member->item('mem_userid');
?>
<section class="visualarea sub">
	<div class="wrapper">
			<div class="subvisual_bg">
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
				<a href="javascript:void(0);" class="on" onclick="move_page(1)">빈도 분석</a>
				<a href="javascript:void(0);" onclick="move_page(2)">추이 분석</a>
				<a href="javascript:void(0);" onclick="move_page(3)">연관어 분석</a>
				<a href="javascript:void(0);" onclick="move_page(4)">연결망 분석</a>
				<a href="javascript:void(0);" onclick="move_page(5)">감성 분석</a>
			</div>
			<div class="grey_contbox tabplus">
				<div class="wh_box">
					<div class="analy_check">
						<strong>분석 데이터</strong>
						<span class="chapter_inp">
						</span>
						<!-- <p>분석에 사용할 분석 데이터 정보에요.</p> -->
					</div>
				</div>
				<div class="btnarea">
					<button class="btn pink" id="analy_btn" onclick="get_frequency()">데이터 생성</button>
				</div>
				<div class="contour_line"></div>
				<div class="mt40">
					<div class="cont_stit">
						<h3>키워드 선택<em>데이터 시각화에 포함할 키워드를 선택해 보세요.</em></h3>
						<button class="btn grey" onclick="download_excel('빈도분석')">데이터 다운로드</button>
					</div>
					<div class="tablearea keyword">
						<table class="tbl01" id="mytable_header" summary="키워드 선택 정보입니다">
							<caption>키워드 선택</caption>
							<thead>
								<tr>
									<th class="w20p">단어(<button id="sort_by_word" style="width:20px; font-size: 12px;">▼▲</button>)</th>
									<th class="w20p">빈도수(건)</th>
									<th class="w20p">비율(%)</th>
									<th class="w20p">누적 비율(%)</th>
									<th class="w20p">
										<input type="checkbox" name="chk_all" id="check_all">
										<label for="check_all"></label>&nbsp;&nbsp;선택(<span id="checked_keyword_count">0/0</span>)
									</th>
								</tr>
							</thead>
						</table>
						<div class="scrollarea">
							<table class="tbl01" id="mytable" summary="키워드 선택 정보입니다">
								<tbody id="data_tbody">
									<tr>
										<td class="list_none" colspan="5">데이터가 없습니다.</td>
									</tr>
									<!-- <tr>
										<td>유나</td>
										<td>27</td>
										<td>12.56%</td>
										<td>12.56%</td>
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
				<div class="visgraph_area">
					<div class="visgraph_cont">
						<div class="tac" id="graph">
							<!-- <img src="/views/_layout/analysis/images/vis_img01_report.png"> -->
						</div>
						<div class="vis_set">
							<h4>색상 선택</h4>
							<div class="mt10 ml10">
								<div class="colorchoicearea" id="graph1-right-top">
									<div>
										<div class="choicebox3">
											<p class="mb5">글자색</p>
											<div>
    											<div class="choicebox choicebox-0" value="0">
        											<svg width="68" height="20">
            											<circle r="10" cx="10" cy="10" style="fill: #2883ff"></circle>
            											<circle r="10" cx="32" cy="10" style="fill: #63a4ff;"></circle>
            											<circle r="10" cx="54" cy="10" style="fill: #accfff;"></circle>
        											</svg>
    											</div>
    											<div class="choicebox choicebox-1" value="1">
        											<svg width="68" height="20">
            											<circle r="10" cx="10" cy="10" style="fill: #fe7d9d"></circle>
            											<circle r="10" cx="32" cy="10" style="fill: #bfd5f4;"></circle>
            											<circle r="10" cx="54" cy="10" style="fill: #fdc5b7;"></circle>
        											</svg>
    											</div>
    											<div class="choicebox choicebox-2" value="2">
        											<svg width="68" height="20">
            											<circle r="10" cx="10" cy="10" style="fill: #73e9c9"></circle>
            											<circle r="10" cx="32" cy="10" style="fill: #d2b3ef;"></circle>
            											<circle r="10" cx="54" cy="10" style="fill: #959595;"></circle>
        											</svg>
    											</div>
    											<div class="choicebox choicebox-3" value="3">
        											<svg width="68" height="20">
            											<circle r="10" cx="10" cy="10" style="fill: #959595"></circle>
            											<circle r="10" cx="32" cy="10" style="fill: #ababab;"></circle>
            											<circle r="10" cx="54" cy="10" style="fill: #bfbfbf;"></circle>
        											</svg>
    											</div>
											</div>
											<div class="choicebox addcolor choicebox-4" value="4">
												<span><i class="fas fa-fill-drip"></i></span>
    											<button class="jscolor {valueElement:'chosen-value1', onFineChange:'setTextColor(this, 1)'}">상</button>
    											<button class="jscolor {valueElement:'chosen-value2', onFineChange:'setTextColor(this, 2)'}">중</button>
    											<button class="jscolor {valueElement:'chosen-value3', onFineChange:'setTextColor(this, 3)'}">하</button>
    											<input type="hidden" id="chosen-value1" value="fe7093" autocomplete="off">
    											<input type="hidden" id="chosen-value2" value="89bafd" autocomplete="off">
    											<input type="hidden" id="chosen-value3" value="fdc5b7" autocomplete="off">
											</div>
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
					<div class="vislist_wrap"></div>
    			</div>
			</div>
		</div>
	</section>
</div>

<form action="" id="moveFrm" name="moveFrm">
	<input type="hidden" name="data_no" value="">
</form>

<script type="text/javascript">

    basicKeywordList.getInstance().attachEvent({
        sort_by_word: { event_type: "click", sort_data: "word" },
        check_all: { event_type: "change", chk_name: "chk" }
    })

    var data_list = [];
var chart_data = [];

$(function() {
	var idx = $("#data_no").val();

	// 원본 데이터 정보 가져오기
	get_data_info(idx);

	// 데이터 시각화 목록 가져오기
	get_visual_list();

	// 배경섹 셋 클릭시
	$("div.choicebox3 > div > div.choicebox").click(function() {
		clickColorSet(this);
	});
})

/* 배경색 셋 클릭시 */
function clickColorSet(_this) {
	if (chart_data.length == 0) {
		return;
	}

	var cnt = parseInt(chart_data.length/3);
	var color1 = $(_this).children().find(":nth-child(1)").css("fill");
	var color2 = $(_this).children().find(":nth-child(2)").css("fill");
	var color3 = $(_this).children().find(":nth-child(3)").css("fill");

	chart_data.forEach(function(row, idx){
		if (idx < cnt) {
			row['color'] = color1;

		} else if (idx < cnt*2) {
			row['color'] = color2;
			
		} else {
			row['color'] = color3;
		}
	});

	load_graph("graph", chart_data, "wordcloud");
}

/* 글자색(1:상, 2:중, 3:하) */
function setTextColor(picker, type) {
	var color = "#" + picker.toString();
	var idx = parseInt(chart_data.length/3);

	if (type == 1) {
    	chart_data.slice(0, idx).forEach(function(row, idx){
    		row['color'] = am4core.color(color);
    	});

	} else if (type == 2) {
		chart_data.slice(idx*2).forEach(function(row, idx){
			row['color'] = am4core.color(color);
		});

	} else if (type == 3) {
		chart_data.slice(idx*2).forEach(function(row, idx){
			row['color'] = am4core.color(color);
		});
	}
	
	load_graph("graph", chart_data, "wordcloud");
}

/* 빈도 분석 */
function get_frequency() {
	var data_no = $("#data_no").val();
	var chapter_list = [];
	var user_id = "<?= $user_id ?>";
	
	$("input:checkbox[name=data_analy]:checked").each(function(){
		chapter_list.push($(this).val());
	});

	if (chapter_list.length == 0) {
		alert("분석 데이터를 선택해주세요.");
		return;
	}

	$("#analy_btn").addClass("disable_bg");
	$("#analy_btn").text("빈도 분석 중");
	
	$.ajax({
    	url: '/edumining/analysis/count',
    	method: 'POST',
    	data: {
    		"user_id" : user_id,
        	"data_no" : data_no,
        	"chapter" : chapter_list.join(" ")
    	},
    	dataType: 'json',
    	success : function(json){

            basicKeywordList.getInstance().clear()

            let sum = 0
            json.forEach(function(li, idx){
                sum += li[2];
                const keyword = basicKeywordList.getInstance().createComponent()
                keyword.setAttribute({
                    index    : idx,
                    word     : li[0],
                    frequency: li[1],
                    rate     : li[2].toFixed(2),
                    cumulative_rate: sum.toFixed(2),
                    check_view_id: idx,
                })
                basicKeywordList.getInstance().push(keyword)
            });

            data_list = [];
            basicKeywordList.getInstance().iterate((component) => {
                data_list.push({'word': component.attribute.word, 'count': component.attribute.frequency});
            })

            basicKeywordList.getInstance().update()

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

/* 시각화 하기 (워드클라우드) */
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
	
	chk_list.each(function(){
		var index = $(this).val();
		var row = data_list[index];

		chart_data.push(data_list[index]);
	});

	var chosen1 = $("#chosen-value1").val();
	var chosen2 = $("#chosen-value2").val();
	var chosen3 = $("#chosen-value3").val();
	
	chart_data = change_color_wordcloud(chart_data, chosen1, chosen2, chosen3);
	load_graph("graph", chart_data, "wordcloud");
}

/* 데이터 시각화 등록 */
function save_myChart() {
	var user_id = "<?= $user_id ?>";
	var data_no = $("#data_no").val();
	var chapter = $("#data_no").val();
	var chapter_list = [];
	var chart_title = $("#chart_title").val();
	var text_color1 = $("#chosen-value1").val();
	var text_color2 = $("#chosen-value2").val();
	var text_color3 = $("#chosen-value3").val();
	var file_data = [];

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

	chart_data.forEach(function(row, idx){
		file_data.push({"word": row['word'], "count": row['count']})
	});

    const param = {
        "title": chart_title,
        "data": file_data,
        "origin_no": data_no,
        "user_id": "<?= $this->member->item('mem_userid');?>",
        "chapter": chapter_list.join(","),
        "anal_type": 0,
        "text_color": [text_color1, text_color2, text_color3].join(","),
        "file_name": ''
    }

    save_mychart_data(param)
}


</script>
