<?php 
$data_no = $this->input->post_get('data_no');
$user_id = $this->member->item('mem_userid');
?>
<!-- 추이분석페이지 -->
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
				<a href="javascript:void(0);" onclick="move_page(1)">빈도 분석</a>
				<a href="javascript:void(0);" class="on" onclick="move_page(2)">추이 분석</a>
				<a href="javascript:void(0);" onclick="move_page(3)">연관어 분석</a>
				<a href="javascript:void(0);" onclick="move_page(4)">연결망 분석</a>
				<a href="javascript:void(0);" onclick="move_page(5)">감성 분석</a>
			</div>
			<div class="grey_contbox tabplus">
				<div>
					<div class="cont_stit">
						<h3>키워드 선택<em>데이터 시각화에 포함할 키워드를 선택해 보세요.</em></h3>
						<button class="btn grey" onclick="download_excel('추이분석')">데이터 다운로드</button>
					</div>
					<div class="tablearea keyword">
						<table class="tbl01" summary="키워드 선택 정보입니다">
							<caption>키워드 선택</caption>
							<thead>
								<tr>
									<th class="w20p">단어(<button id="sort_by_word" style="width:20px; font-size: 12px;">▼▲</button>)</th>
									<th class="w20p">빈도수(건)</th>
									<th class="w20p">비율(%)</th>
									<th class="w20p">누적 비율(%)</th>
									<th class="w20p">
										<input type="checkbox" name="check_all" id="check_all">
										<label for="check_all"></label>&nbsp;&nbsp;선택(<span id="checked_keyword_count">0/0</span>)
									</th>
								</tr>
							</thead>
						</table>
						<div class="scrollarea">
							<table class="tbl01" summary="키워드 선택 정보입니다">
								<tbody id="data_tbody">
									<tr>
										<td class="list_none" colspan="5">데이터가 없습니다.</td>
									</tr>
									<!-- <tr>
										<td>유나</td>
										<td>164</td>
										<td>9.61%</td>
										<td>9.61%</td>
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
			<h2>데이터 시각화<em>시각화된 분석 결과를 꾸며보고 목록에 저장하세요.</em></h2>
		</div>
		<div class="grey_contbox">
			<div class="vis_view">
				<div class="cont_stit">
					<h3>시각화 미리보기</h3>
				</div>
				<div class="visgraph_area"><!-- 시각화그래프영역 -->
					<div class="visgraph_cont">
						<div class="tac w100p" id="graph">
							<!-- <img src="/views/_layout/analysis/images/vis_img01_report.png"> -->
						</div>
						<!-- 
						<div class="vis_set">
							<h4>색상 선택</h4>
							<div class="mt10 ml10">
								<div class="colorchoicearea" id="graph1-right-top">
									<div>
										<div class="choicebox3">
											<span>배경색</span><button class="jscolor {valueElement:'chosen-value1', onFineChange:'setTextColor1(this)'}" style="background-image: none; background-color: rgb(211, 236, 238); color: rgb(0, 0, 0);">상</button><button class="jscolor {valueElement:'chosen-value2', onFineChange:'setTextColor2(this)'} jscolor-active" style="background-image: none; background-color: rgb(225, 215, 235); color: rgb(0, 0, 0);">중상</button><button class="jscolor {valueElement:'chosen-value3', onFineChange:'setTextColor3(this)'}" style="background-image: none; background-color: rgb(220, 220, 220); color: rgb(0, 0, 0);">중</button>
											<input type="hidden" id="chosen-value1" value="D3ECEE" autocomplete="off">
											<input type="hidden" id="chosen-value2" value="E1D7EB" autocomplete="off">
											<input type="hidden" id="chosen-value3" value="DCDCDC" autocomplete="off">
											
										</div>
									</div>
									<div>
										<div class="choicebox3">
											<span>글자색</span><button class="jscolor {valueElement:'chosen-value6', onFineChange:'setTextColor6(this)'}" style="background-image: none; background-color: rgb(51, 51, 51); color: rgb(255, 255, 255);">상</button><button class="jscolor {valueElement:'chosen-value7', onFineChange:'setTextColor7(this)'}" style="background-image: none; background-color: rgb(51, 51, 51); color: rgb(255, 255, 255);">중상</button><button class="jscolor {valueElement:'chosen-value8', onFineChange:'setTextColor8(this)'}" style="background-image: none; background-color: rgb(51, 51, 51); color: rgb(255, 255, 255);">중</button><button class="jscolor {valueElement:'chosen-value9', onFineChange:'setTextColor9(this)'}" style="background-image: none; background-color: rgb(34, 34, 34); color: rgb(255, 255, 255);">중하</button><button class="jscolor {valueElement:'chosen-value10', onFineChange:'setTextColor10(this)'}" style="background-image: none; background-color: rgb(136, 136, 136); color: rgb(0, 0, 0);">하</button>
											<input type="hidden" id="chosen-value6" value="333333" autocomplete="off">
											<input type="hidden" id="chosen-value7" value="333333" autocomplete="off">
											<input type="hidden" id="chosen-value8" value="333333" autocomplete="off">
											<input type="hidden" id="chosen-value9" value="222222" autocomplete="off">
											<input type="hidden" id="chosen-value10" value="888888" autocomplete="off">
										</div>
									</div>

								</div>
							</div>
						</div>
						 -->
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
							<div class="vgragh">
								<img src="/views/_layout/analysis/images/vis_img01.png" alt="">
							</div>
							<dl>
								<dt>제목</dt>
								<dd>욕 좀 하는 이유나 / 워드클라우드</dd>
								<dt>저장일</dt>
								<dd>2021-05-31</dd>
							</dl>
							<div class="overh mt10">
								<button class="btn lightgrey">삭제</button>
								<button class="btn grey floatr">다운로드</button>
							</div>
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

    basicKeywordList.getInstance().attachEvent({
        sort_by_word: { event_type: "click", sort_data: "word" },
        check_all: { event_type: "change", chk_name: "chk" }
    })

var data_list = {};
var chart_data = [];
var save_chart_data = [];

$(function() {
	var idx = $("#data_no").val();

	// 원본 데이터 정보 가져오기
	get_data_info(idx);

	// 데이터 시각화 목록 가져오기
	get_visual_list();

	// 추이 분석 가져오기
	get_trends();
})

/* 추이 분석 */
function get_trends() {
	var data_no = $("#data_no").val();
	var user_id = "<?= $user_id ?>";
	
	$.ajax({
        url: '/edumining/analysis/transition',
    	method: 'post',
    	data: {
    		"user_id" : user_id,
        	"data_no" : data_no
    	},
    	dataType: 'json',
    	success : function(json){
            let table_list = json.table;
            let sum = 0;
            basicKeywordList.getInstance().clear()
            table_list.forEach(function(li, idx){
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
                keyword.attribute.anal_value_name = "word"
                basicKeywordList.getInstance().push(keyword)
            });
            basicKeywordList.getInstance().update()
            data_list = json.visual;
    	},
    	error: function(xhr, status, error) {
    		console.log(error);
	}
	});
}

/* 시각화 하기 (추이분석) */
function make_visualization() {
	var chk_list = $("input:checkbox[name=chk]:checked");
	var chk_cnt = chk_list.length;
	//var keyword_list = [];

	if (chk_cnt < 1 || chk_cnt > 10){
		alert("최소 1개, 최대 10개 까지 선택 가능합니다.");
		return;
	}

	data_list.forEach(function(li, idx) {
		var tmp_array = {};
		tmp_array['xvalue'] = li['xvalue'];

		chk_list.each(function(){
			var keyword = $(this).val();
			tmp_array[keyword] = li[keyword];
		});

		chart_data.push(tmp_array);
	});

	load_graph("graph", chart_data, "trend");
	save_chart_data = chart_data;
    chart_data = [];
}

/* 데이터 시각화 등록 */
function save_myChart() {
	var user_id = "<?= $user_id ?>";
	var data_no = $("#data_no").val();
	var chart_title = $("#chart_title").val();
	var text_color = "";
	var bg_color = "";
	var selected_keyword = [];

	if (user_id == "") {
		alert("로그인시 이용하실 수 있습니다.");
		return;
	}
	
	if (save_chart_data.length == 0) {
		alert("저장할 시각화를 먼저 선택해주세요.");
		return;
	}

	$("input:checkbox[name=chk]:checked").each(function(){
		var keyword = $(this).val();
		selected_keyword.push(keyword);
	});

    const param = {
        "title": chart_title,
        "data": JSON.stringify(save_chart_data),
        "user_id": "<?= $this->member->item('mem_userid');?>",
        "center_word": selected_keyword.join(","),
        "origin_no": data_no,
        "anal_type": 3,
        "text_color": text_color,
        "bg_color": bg_color,
        "file_name": ''
    }

    save_mychart_data(param)
}

</script>

