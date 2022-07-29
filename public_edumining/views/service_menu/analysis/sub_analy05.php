<?php 
$data_no = $this->input->post_get('data_no');
$user_id = $this->member->item('mem_userid');
?>
<!-- 감성분석페이지 -->
<div id="barGraphStyle">
</div>
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
				<a href="javascript:void(0);" onclick="move_page(4)">연결망 분석</a>
				<a href="javascript:void(0);" class="on" onclick="move_page(5)">감성 분석</a>
			</div>
			<div class="grey_contbox tabplus">
				<div class="wh_box">
					<div class="analy_check">
						<strong>분석 데이터</strong>
						<span class="chapter_inp">
							<!-- <input type="checkbox" id="1챕터" name="data_analy"><label for="1챕터">1 장</label> -->
						</span>
					</div>
				</div>
				<div class="btnarea">
					<button class="btn pink" id="analy_btn" onclick="get_sentiment()">데이터 생성</button>
				</div>

				<div class="contour_line"></div>

				<div class="mt40 columnbox">
					
					<p class="info_text">KNU 감성사전 기반으로 구축한 범용 감정 어휘 사전으로 긍정/부정 단어를 추출합니다</p>

					<div class="wh_box mb30">
						<div class="ratiogragh_area">
							<strong>감성 단어 비율</strong>
							<div class="ratiogragh_cont ">
								<div><span><em style="width: 77%;"></em></span></div>
							</div>
						</div>
					</div>

					<div class="column2">
						<div class="cont_stit">
							<h3>긍정키워드 선택</h3>
                            <button id="download_positive_keywords" class="btn grey">데이터 다운로드</button>
						</div>
						<div class="tablearea keyword">
							<table class="tbl01 positive_keywords" summary="긍정키워드 선택 정보입니다">
								<caption>긍정키워드 선택</caption>
								<thead>
									<tr>
										<th class="w20p">단어(<button id="sort_by_positive_word" style="width:20px; font-size: 12px;">▼▲</button>)</th>
										<th class="w20p">빈도수(건)</th>
										<th class="w20p">
											<input type="checkbox" name="chk_positive_all" id="check_positive_all">
											<label for="check_positive_all"></label>&nbsp;&nbsp;선택(<span id="checked_positive_keyword_count">0/0</span>)
										</th>
									</tr>
								</thead>
							</table>

							<div class="scrollarea">
								<table class="tbl01 positive_keywords" summary="긍정키워드 선택 정보입니다">
									<tbody id="data_tbody1">
										<tr>
											<td colspan="3" class="list_none">데이터가 없습니다.</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="column2">
						<div class="cont_stit">
							<h3>부정키워드 선택</h3>
                            <button id="download_negative_keywords" class="btn grey">데이터 다운로드</button>
						</div>
						<div class="tablearea keyword">
							
							<table class="tbl01 negative_keywords" summary="부정키워드 선택 정보입니다">
								<caption>부정키워드 선택</caption>
								<thead>
									<tr>
										<th class="w20p">단어(<button id="sort_by_negative_word" style="width:20px; font-size: 12px;">▼▲</button>)</th>
										<th class="w20p">빈도 수(건)</th>
										<th class="w20p">
											<input type="checkbox" name="chk_negative_all" id="check_negative_all">
											<label for="check_negative_all"></label>&nbsp;&nbsp;선택(<span id="checked_negative_keyword_count">0/0</span>)
										</th>
									</tr>
								</thead>
							</table>
							<div class="scrollarea">
								<table class="tbl01 negative_keywords" summary="부정키워드 선택 정보입니다">
									<tbody id="data_tbody2">
										<tr>
											<td colspan="3" class="list_none">데이터가 없습니다.</td>
										</tr>
									</tbody>
								</table>
							</div>
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
    											<button class="jscolor {valueElement:'chosen-value1', onFineChange:'setPositiveTextColor(this'}">긍정</button>
    											<button class="jscolor {valueElement:'chosen-value2', onFineChange:'setNegativeTextColor(this)'}">부정</button>
<!--    											<button class="jscolor {valueElement:'chosen-value3', onFineChange:'setTextColor(this, 3)'}">하</button>-->
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
					<div class="vislist_wrap">
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

    let positive_data_list = [];

    let negative_data_list = [];
    let positive_chart = [];
    let negative_chart = [];


    positiveSimpleKeywordList.getInstance().attachEvent({
        sort_by_positive_word: { event_type: "click", sort_data: "positive_word" },
        check_positive_all: { event_type: "change", chk_name: "chk_positive" },
    })
    negativeSimpleKeywordList.getInstance().attachEvent({
        sort_by_negative_word: { event_type: "click", sort_data: "negative_word" },
        check_negative_all: { event_type: "change", chk_name: "chk_negative" }
    })



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

const sortByPositiveWordButton = document.getElementById("sort_by_positive_word")
sortByPositiveWordButton.addEventListener("click", (e) => {
    positiveSimpleKeywordList.getInstance().sort("positive_word")
    positiveSimpleKeywordList.getInstance().update()
})

const sortByNegativeWordButton = document.getElementById("sort_by_negative_word")
sortByNegativeWordButton.addEventListener("click", (e) => {
    negativeSimpleKeywordList.getInstance().sort("negative_word")
    negativeSimpleKeywordList.getInstance().update()
})

/* 배경색 셋 클릭시 */
function clickColorSet(_this) {

    if (positive_chart.length === 0 && negative_chart.length === 0) {
        return;
    }

    var positive_color = $(_this).children().find(":nth-child(1)").css("fill");
    var negative_color = $(_this).children().find(":nth-child(2)").css("fill");

    function rgbToHex(rgb) {
        function toHex(n){ //Hex코드로 변환
            if(n == null) return "00";

            n = parseInt(n);
            if(n === 0 || isNaN(n)) return "00"

            n = Math.max(0, n);
            n = Math.min(n, 255);
            n = Math.round(n);
            return "0123456789ABCDEF".charAt((n - n % 16) / 16) + "0123456789ABCDEF".charAt(n % 16);
        }
        rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        return toHex(rgb[1]) + toHex(rgb[2]) + toHex(rgb[3])
    }

    draw_sentiment_graph(rgbToHex(positive_color), rgbToHex(negative_color))
}

function barGraphRating(positiveRate, negativeRate){

    $(".ratiogragh_cont").removeClass("on");
    $("#barGraphStyle").children().remove();
    var html = "<style>.ratiogragh_area > div.on::before {content: '긍정 " + positiveRate + "%'; position: absolute; top: calc(50% + 2px); left: 0; color: #e84c88; font-size: 16px; font-weight: 600; line-height: 34px; }</style>";
    $("#barGraphStyle").append(html);
    var html = "<style>.ratiogragh_area > div.on::after { content: '부정 " + negativeRate + "%'; position: absolute; top: calc(50% + 2px); right: 0; color: #2883ff; font-size: 16px; line-height: 34px; }</style>"
    $("#barGraphStyle").append(html);
    $($(".ratiogragh_area").find("em")[0]).css("width", positiveRate + "%");
    $(".ratiogragh_cont").addClass("on");
}


/* Color Picker: 긍정 텍스트 색상 변경 */
function setPositiveTextColor(picker) {
    const positive_color = picker.toString()
    const negative_color = $("#chosen-value2").val();
    draw_sentiment_graph(positive_color, negative_color)
}

/* Color Picker: 부정 텍스트 색상 변경 */
function setNegativeTextColor(picker) {
    const positive_color = $("#chosen-value1").val();
    const negative_color = picker.toString()
    draw_sentiment_graph(positive_color, negative_color)
}

/* 감성 분석 */
function get_sentiment() {

    var data_no = $("#data_no").val();
    var user_id = "<?= $user_id ?>";
    var chapter_list = [];

    $("input:checkbox[name=data_analy]:checked").each(function(){
        chapter_list.push($(this).val());
    });

    if (chapter_list.length == 0) {
        alert("분석 데이터를 선택해주세요.");
        return;
    }

    $("#analy_btn").addClass("disable_bg");
    $("#analy_btn").text("감성 분석 중");

    $.ajax({
        url: '/edumining/analysis/sentiment',
        method: 'post',
        data: {
            "user_id" : user_id,
            "data_no" : data_no,
            "chapter" : chapter_list.join(" "),
        },
        dataType: 'json',
        success : function(json){
            //console.log(json);
            positive_data_list = [];
            negative_data_list = [];

            var positive = json.positive_table;
            var negative = json.negative_table;
            var p_html = '';
            var n_html = '';

            var positive_total = 0;
            var negative_total = 0;
            for(var i = 0; i < positive.length; i++){
                positive_total = positive_total + positive[i][1];
            }
            for(var i = 0; i < negative.length; i++){
                negative_total = negative_total + negative[i][1];
            }
            var keyword_total = positive_total + negative_total;
            const positiveRate = keyword_total === 0 ? 0 : ~~(positive_total / keyword_total * 100)
            const negativeRate = keyword_total === 0 ? 0 : (100 - positiveRate)
            barGraphRating(positiveRate, negativeRate);

            createList(positive, positiveSimpleKeywordList, "data_tbody1", "positive_word", "chk_positive")
            createList(negative, negativeSimpleKeywordList, "data_tbody2", "negative_word", "chk_negative")
            function createList(list, simpleKeywordList, listViewId, attrName, chkName) {
                simpleKeywordList.getInstance().setListViewId(listViewId)
                simpleKeywordList.getInstance().clear()
                let chkIndex = 0
                list.forEach(function(li, idx){
                    const keyword = simpleKeywordList.getInstance().createComponent()
                    keyword.setAttribute({
                        index: idx,
                        frequency: li[1],
                        check_view_id: `${chkName}${idx}`
                    })
                    keyword.attribute[attrName] = li[0]
                    keyword.attribute["word_attr_name"] = attrName
                    keyword.attribute["chk_name"]       = chkName
                    keyword.attribute["chk_index"]      = chkIndex
                    chkIndex = chkIndex + 1
                    simpleKeywordList.getInstance().push(keyword)
                });
                simpleKeywordList.getInstance().update()
            }

            downloadPositiveEventListener.setEvent(positive_data_list, "positive")
            downloadNegativeEventListener.setEvent(negative_data_list, "negative")

            appendData(positiveSimpleKeywordList, positive_data_list)
            appendData(negativeSimpleKeywordList, negative_data_list)
            function appendData(simpleKeywordList, dataList) {
                simpleKeywordList.getInstance().iterate((component) => {
                    const wordAttrName = component.attribute.word_attr_name
                    dataList.push({'word': component.attribute[wordAttrName], 'count': component.attribute.frequency});
                })
            }
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

function DownloadExcelEventListener() {
    this.event = (dataList, tableName, fileName) => {
        download_sentiment_excel(dataList, tableName, fileName)
    }
    this.setEvent = function (dataList, keywordType) {
        const button = document.getElementById(`download_${keywordType}_keywords`)
        const tableName = `${keywordType}_keywords`
        const fileName = `감성분석(${keywordType === "positive" ? "긍정":"부정"}키워드)`
        const event = this.event.bind(this, dataList, tableName, fileName)
        button.removeEventListener("click", this.prevEvent)
        button.addEventListener("click", event)
        this.prevEvent = event
    }
}

const downloadPositiveEventListener = new DownloadExcelEventListener()
const downloadNegativeEventListener = new DownloadExcelEventListener()

/* 시각화 하기 (연결망 분석) */
function make_visualization() {

    var chk_positive_list = $("input:checkbox[name=chk_positive]:checked");
    var chk_negative_list = $("input:checkbox[name=chk_negative]:checked");

    var chk_cnt = chk_positive_list.length + chk_negative_list.length
    positive_chart = []
    negative_chart = []

    if ((positive_data_list.length + negative_data_list.length) >= 10) {
        if (chk_cnt < 10 || chk_cnt > 30) {
            alert("최소 10개, 최대 30개 까지 선택 가능합니다.");
            return;
        }
    }

    chk_positive_list.each(function(){
        var index = $(this).val();
        positive_chart.push(positive_data_list[index]);
    });

    chk_negative_list.each(function(){
        var index = $(this).val();
        negative_chart.push(negative_data_list[index]);
    });

    const positive_color = $("#chosen-value1").val();
    const negative_color = $("#chosen-value2").val();
    draw_sentiment_graph(positive_color, negative_color)
}

function draw_sentiment_graph(positive_color, negative_color) {
    console.log(positive_color)
    console.log(negative_color)
    change_color_wordcloud_binary(positive_chart, negative_chart, positive_color, negative_color);

    let chart = []
    positive_chart.forEach(function (row, idx) {
        row['color'] = am4core.color("#" + positive_color);
        chart.push(row)
    })

    negative_chart.forEach(function (row, idx) {
        row['color'] = am4core.color("#" + negative_color);
        chart.push(row)
    })
    load_graph("graph", chart, "wordcloud");
}

/* 데이터 시각화 등록 */
function save_myChart() {
	var user_id = "<?= $user_id ?>";
	var data_no = $("#data_no").val();
	var chart_title = $("#chart_title").val();
	var text_color1 = $("#chosen-value1").val();
	var text_color2 = $("#chosen-value2").val();
	// var text_color3 = $("#chosen-value3").val();
	var chapter_list = [];
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

    positive_chart.forEach(function(row, idx){
        file_data.push({"word": row['word'], "count": row['count'], "positive": 1})
    })

    negative_chart.forEach(function(row, idx){
        file_data.push({"word": row['word'], "count": row['count'], "positive": 0})
    });


    console.log(file_data)

    const param = {
        "title": chart_title,
        "data": file_data,
        "user_id": "<?= $this->member->item('mem_userid');?>",
        "origin_no": data_no,
        "chapter": chapter_list.join(","),
        "anal_type": 4,
        "text_color": [text_color1, text_color2].join(","),
        "file_name": ''
    }
    save_mychart_data(param)
}
</script>