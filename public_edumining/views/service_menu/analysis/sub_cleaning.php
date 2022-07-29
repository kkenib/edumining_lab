<script>
    const agent = window.navigator.userAgent.toLowerCase()
    if (agent.indexOf("trident") > -1) {
        alert("원활한 서비스 이용을 위해 크롬 브라우저를 이용해주세요.")
    }
</script>
<?php
$data_no = $this->input->post_get('data_no', TRUE);
?>
<section class="visualarea sub">
	<div class="wrapper">
			<div class="subvisual_bg cleaning">
				<div class="subvisual_box">
					<h2 class="bold">데이터 <b>정제</b></h2>
					<p>
						불필요한 단어들을 제거하고 컴퓨터가 읽을 수 있는 데이터로 변환하는 단계예요.
						<br>
						데이터의 질을 높이기 위해서는 정제가 매우 중요해요.
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
				<input type="hidden" name="edit_step" id="edit_step" value="">
				
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
	<!-- 형태소 분석 : S -->
	<section class="analysis_area">
			<div class="box_tit mb25 mt80">
				<h2>형태소 분석<em>사람의 언어를 컴퓨터가 이해할 수 있도록 변환해요.</em></h2>
			</div>
			<div class="grey_contbox">
				<div class="wh_box">
					 <div class="analy_check">
						<span><input type="checkbox" id="chk0"><label for="chk0"></label></span><strong>명사</strong><p>사람, 사물, 장소나 눈에 보이지 않는 것 등의 이름을 가리키는 말</p>
					 </div>
					  <div class="analy_check">
						<span><input type="checkbox" id="chk1"><label for="chk1"></label></span><strong>형용사</strong><p>사람이나 사물의 성질이나 상태를 나태내는 말</p>
					</div>
					  <div class="analy_check">
						<span><input type="checkbox" id="chk2"><label for="chk2"></label></span><strong>동사</strong><p>사람이나 사물의 움직임 또는 작용을 나타내는 말</p>
					</div>
				</div>
				<div class="tac mt20">
					<button class="btn grey mr8" id="download_speech">다운로드</button>
					<button class="btn pink" id="pos_btn" onclick="get_text_pos()">형태소 분석</button>
				</div>
				<div class="contour_line"></div>
				<div class="original_box">
					<div class="original_l">
						<div class="cont_stit">
								<h3>원문 데이터<em>선택한 데이터를 확인해 보세요.</em></h3>
						</div>
						<div class="original_text">
							<div id="original_text">
								<!-- 1. 특별한 의뢰<br><br>

								유나가 발로 힘껏 찬 돌멩이는 제법 멀리 날아가, 운동장 구석 벤치에 앉아 있던 소미 무릎에 맞고 떨어졌다.<br>
								“아야!” 유나는 놀라서 소미에게 다가갔다. “미안. 일부러 그런 게 아니라……. 씨, 되는 일이 하나도 없네.”<br>
								유나는 가던 길로 다시 발길을 돌렸다. 그때 뒤에서 누군가 다가와 따라 걷기 시작했다. 유나는 걸음을 멈춰 고개를 돌렸다.<br>
								어느새 옆으로 다가온 소미가 따라 멈춰 섰다. 유나는 돌멩이 때문인가 해서 철렁했지만 아무렇지 않은 척했다.<br>
								얘가 집에 가서 뭐라고 이르면 곤란해지겠다 싶었다. 불과 며칠 전, 어떤 애 엄마가 유나 더러 ‘애가 드세다’라며 할머니에게 항의했다.<br>
								할머니는 그 애 엄마한테 화를 냈지만, 유나에게는 또 그런 소리를 듣게끔 행동하면 가만두지 않겠다고 눈을 부릅떴다.<br>
								유나는 자기한테 못생겼다고 놀리는 애한테 욕 몇 마디 해 준 게 전부였다. “왜? 사과했잖아.”“저기, 그게 아니라, 할 얘기가 있어.”<br>
								유나는 몸을 완전히 돌려 소미를 바라봤다.<br>
								평소 목소리가 어떤지도 기억이 나지 않을 만큼 말수가 적은 아이인데, 뭔가 잔뜩 할 말이 많은 얼굴이었다. -->
							</div>
						</div>
					</div>
					
					<div class="right_arrow"><i class="fas fa-arrow-right"></i></div>

					<div class="original_r">
						<div class="cont_stit">
							<h3>가정제 데이터<em>형태소 분석이 완료된 데이터를 원문과 비교해 보세요.</em></h3>
						</div>
						<div class="original_text">
							<div id="original_pos"></div>
						</div>
					</div>
				</div>
				
			</div>
	</section>
	<!-- 행태소 분석 : E -->
	<!-- 데이터 편집 : S -->
	<section class="data_edit_area">
		<div class="box_tit mt100">
			<h2>데이터 편집<em>형태소 분석기로 처리 되지 못한 단어들을 직접 편집해 보세요.</em></h2>
		</div>
		<div class="grey_contbox">
			<div class="wh_box mb20">
				<div class="edit_box">
					<input type="text" placeholder="변경할단어" class="mr10" id="before_word">
					<i class="fas fa-arrow-right pink mr10"></i>
					<input type="text" placeholder="수정단어" class="mr10" id="after_word">
					<button class="btn pink mid mr10" onclick="edit_words()">단어 변경</button>
				</div>
			</div>
			<div class="wordbox">
				<div class="word_tbbx">
					<p class="tit">추천단어</p>
					<div class="word_scroll">
						<table class="tbl01" summary="추천단어입니다.">
							<caption>추천단어</caption>
							<tbody id="recommend_tbody">
								<!-- <tr>
									<td>
										목 소리 <em class="word_num">2147</em>
										<i class="fas fa-caret-down"></i>
										목소리
									</td>
								</tr> -->
							</tbody>
						</table>
					</div>
				</div>
				<div class="original_text">
					<p id="replace_pos"></p>
				</div>
				<div class="word_tbbx">
					<p class="tit">수정내역</p>
					<div class="word_scroll">
						<table class="tbl01" summary="수정내역입니다."> 
							<caption>수정내역</caption>
							<tbody id="edit_tbody">
							   <!-- 	
								<tr>
									<td>
										목 소리
										<i class="fas fa-caret-down"></i>
										목소리
										<button type="button" onclick="refresh_word('목 소리', '목소리', this)"><i class="fas fa-undo-alt" title="되돌리기"></i></button>
									</td>
								</tr>
								 -->
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="tac mt20">
					<button class="btn mid blue" onclick="save_edit()">저장</button>
			</div>
		</div>
		<div class="btnarea mt40">
			<p class="mb20">형태소 분석과 데이터 편집을 통해 정제가 완료된 데이터를 생성해보세요.</p>
			<button type="button" class="btn grey round lg mr15 mb40" onclick="location.href='/analysis/select_rawdata'">선택목록보기</button>
			<button type="button" class="btn navy round lg mb40" onclick="goAnalysis()">데이터 생성하기</button>
		</div>
	</section>
	<!-- 데이터 편집 : E -->
</div>

<form action="" id="moveFrm" name="moveFrm">
	<input type="hidden" name="data_no" value="">
</form>

<script type="text/javascript">
    
    document.getElementById("download_speech").addEventListener("click", () => {
        const userNo = ~~("<?= $this->member->item('mem_id');?>")
        const userLevel = ~~("<?= $this->member->item('mem_level');?>")
        if (userLevel < 1 || userNo < 1) {
            alert("로그인 후 사용해주세요.")
            return
        }

        const dataNo = $("#data_no").val();
        if(dataNo < 1) {
            alert("품사 데이터를 받을 수 없습니다.")
            return
        }

        const userId = "<?= $this->member->item('mem_userid');?>"
        $.ajax({
            url: '/edumining/analysis/getSpeechFile',
            method: 'post',
            data: {
                "data_no": dataNo,
                "user_id": userId
            },
            dataType: 'json',
            success : function(data){
                console.log("getSpeechFile")
                console.log(data)
                if(!data.exist || ~~(data.file_size) === 0) {
                    alert("형태소 분석 결과가 존재하지 않습니다.")
                    return
                }

                const hiddenElement = document.createElement('a')
                hiddenElement.href = 'data:attachment/text,' + encodeURI(data.speech)
                hiddenElement.target = '_blank'

                const dataName = document.getElementById("data_name").innerText
                hiddenElement.download = `[형태소_품사]${dataName}.txt`
                hiddenElement.click()
            },
            error: function(xhr, status, error) {
                alert("형태소 분석 결과가 존재하지 않습니다.")
            }
        });
    })

//수정내역 리스트
var edit_list = {};

$(function() {
	var idx = $("#data_no").val();

	// 원본 데이터 정보 가져오기
	get_data_info(idx);

	// 원문 데이터 불러오기
	get_rawdata_text(idx);

	// 편집 상태 불러오기
	get_edit_status(idx);

	// 추천 단어 클릭 이벤트
	$(document).on("click","#recommend_tbody tr", function(){
		edit_recommends_word(this);
		
	});
})

/* 추천 단어 적용하기 */
function edit_recommends_word(_this) {
	if (!confirm("수정하시겠습니까?")) {
		return;
	}
	var before = $(_this).children().find(":nth-child(1)").html();
	var after = $(_this).children().find(":nth-child(4)").html();
	var text = $("#replace_pos").text();
	
	var re = new RegExp(before, 'g');
	text = text.replace(re, after);

	$("#replace_pos").text(text);

	var html = '';
	html += '<tr>';
	html += '<td>';
	html += before;
	html += '<i class="fas fa-caret-down"></i>';
	html += after;
	html += '<button type="button" onclick="refresh_word(\''+before+'\', \''+after+'\', this)"><i class="fas fa-undo-alt" title="되돌리기"></i></button>';
	html += '</td>';
	html += '</tr>';
	
	$("#edit_tbody").append(html);

	// 텍스트 칸 비우기
	$("#before_word").val("");
	$("#after_word").val("");

	//수정내역 추가
	edit_list[before] = after;
}

/* 형태소 분석을 진행했을 때 기존 데이터 */
function get_my_cleaning_words(idx) {

	$.ajax({
        url: "/edumining/analysis/getCleaningData",
    	method: 'post',
    	data: {
    	    "data_no": idx,
            "user_id": "<?= $this->member->item('mem_userid');?>"
    	},
    	dataType: 'json',
    	success : function(json){

            console.log("getCleaningData");
            console.log(json);

    		// 가정제 데이터
    		$('#original_pos').text(json.raw_words);
			// 데이터 편집
    		$('#replace_pos').text(json.replace);

            var recommend = json.recommand;
            var html = '';
            $("#recommend_tbody").html();
            recommend.forEach(function(li, idx){
                if (li.before == "" || li.after == "") {
                    return;
                }

                html += '<tr>';
                html += '<td>';
                html += '<span>' + li.before + '</span><em class="word_num">' + li.count + '</em>';
                html += '<i class="fas fa-caret-down"></i>';
                html += '<span>' + li.after + '</span>';
                html += '</td>';
                html += '</tr>';
            });

            $("#recommend_tbody").html(html);

			// 추천 단어
    		// get_recommend_list(idx)

    	},
    	error: function(xhr, status, error) {
    		console.log(error);
	}
	});
}

/* 원문 데이터  불러오기 */
function get_rawdata_text(idx) {

	$.ajax({
        url: "/edumining/analysis/getRawText",
    	method: 'post',
    	data: {
    	    "data_no" : idx
    	},
    	dataType: 'json',
    	success : function(json){
			$("#original_text").html(json.data);
    	},
    	error: function(xhr, status, error) {
    		console.log(error);
	}
	});
}

/* 추천단어 불러오기 */
// function get_recommend_list(idx) {
//
// 	$.ajax({
//     	url: '/edumining/analysis/get_recommend_list',
//     	method: 'get',
//     	data: {
//     	    "data_no" : idx
//     	},
//     	dataType: 'json',
//     	success : function(json){
//     		// console.log("get_recommend_list");
//     		// console.log(json);
//
// 			var recommend = json.data;
// 			var html = '';
// 			$("#recommend_tbody").html();
//
//
// 			recommend.forEach(function(li, idx){
// 				if (li.before == "" || li.after == "") {
// 					return;
// 				}
//
// 				html += '<tr>';
// 				html += '<td>';
// 				html += '<span>' + li.before + '</span><em class="word_num">' + li.count + '</em>';
// 				html += '<i class="fas fa-caret-down"></i>';
// 				html += '<span>' + li.after + '</span>';
// 				html += '</td>';
// 				html += '</tr>';
// 			});
//
// 			$("#recommend_tbody").html(html);
//
//     	},
//     	error: function(xhr, status, error) {
//     		console.log(error);
// 	}
// 	});
// }

/* 데이터 편집 */
function edit_words() {
	var before = $("#before_word").val();
	var after = $("#after_word").val();
	var text = $("#replace_pos").text();

	if (before == "") {
		alert("변경할 단어를 입력해주세요.");
		return;
	}
	
	var re = new RegExp(before, 'g');
	text = text.replace(re, after);

	$("#replace_pos").text(text);

	var html = '';
	html += '<tr>';
	html += '<td>';
	html += before;
	html += '<i class="fas fa-caret-down"></i>';
	html += after;
	html += '<button type="button" onclick="refresh_word(\''+before+'\', \''+after+'\', this)"><i class="fas fa-undo-alt" title="되돌리기"></i></button>';
	html += '</td>';
	html += '</tr>';
	
	$("#edit_tbody").append(html);

	// 텍스트 칸 비우기
	$("#before_word").val("");
	$("#after_word").val("");

	//수정내역 추가
	edit_list[before] = after;
}

/* 수정내역 되돌리기 */
function refresh_word(before, after, _this) {
	var text = $("#replace_pos").text();
	
	var re = new RegExp(after, 'g');
	text = text.replace(re, before);

	$("#replace_pos").text(text);

	// 수정내역 html 삭제
	var tr = $(_this).parent().parent();
	tr.remove();

	//수정내역 삭제
	delete edit_list[before]
}

/* 데이터 편집 저장하기 */
function save_edit() {
	var data_no = $("#data_no").val();
	var text = $("#replace_pos").text();
	var edit_step = $("#edit_step").val();
	edit_step = edit_step == ''? '0' : edit_step
    const chapter_count = localStorage.getItem("chapter_count")
    // if($("#data_type").text() != "제공 데이터"){
    //     var chapter_count = 1;
    // }else{
    //     var chapter_count = $("#chapter_count").text();
    // }


	var user_no = "<?=$_SESSION['mem_id']?>";
	if (user_no == "") {
		alert("로그인시 저장하실 수 있습니다.");
		return;
	}
	
	if (text == "") {
		alert("형태소 분석 후 저장해주세요.");
		return;
	}

    let replaceResult = false
    $.ajax({
        url: "/edumining/analysis/replace",
        method: 'post',
        data: {
            "data_no": data_no,
            "user_id": "<?= $this->member->item('mem_userid');?>",
            "replace_word_dict": JSON.stringify(edit_list),
            "chapter_count": chapter_count
        },
        dataType: 'json',
        async: false,
        success : function(json){
            if (json.data)
                replaceResult = true

        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });

    if (!replaceResult) {
        alert("저장에 실패하였습니다.");
        return
    }

	$.ajax({
        url: "/edumining/analysis/copyToChildren",
        method: 'post',
        data: {
            "data_no": data_no,
            "user_id": "<?= $this->member->item('mem_userid');?>",
        },
        dataType: 'json',
        async: false,
        success : function(data){
            // if (!data.is_success)
                // alert(data.error_message)
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });

	$.ajax({
    	url: '/edumining/analysis/save_cleaning_words',
    	method: 'get',
    	data: {
    	    "data_no": data_no,
    	    "edit_step": edit_step,
    	    "replace_word_dict": JSON.stringify(edit_list),
    	    "chapter_count": chapter_count
    	},
    	dataType: 'json',
    	success : function(json){
            // console.log("/edumining/analysis/save_cleaning_words")
    		// console.log(json);
			if (json.data) {
				alert("저장되었습니다.");
				window.location.reload();
			} else {
				alert("저장에 실패하였습니다.");
			}
    	},
    	error: function(xhr, status, error) {
    		console.log(error);
	}
	});
}

/* 편집 상태 불러오기 */
function get_edit_status(data_no) {
	$.ajax({
    	url: '/edumining/analysis/get_edit_status',
    	method: 'get',
    	data: {
    	    "data_no": data_no
    	},
    	dataType: 'json',
    	success : function(json){
			var edit_step = json.data;
			
			if (edit_step == null || edit_step == '') {
				edit_step = 0;

			} else if (edit_step == 1){
				get_my_cleaning_words(data_no);
			}
			
    		$("#edit_step").val(edit_step);
    	},
    	error: function(xhr, status, error) {
    		console.log(error);
	}
	});
}

/* 형태소 분석 */
function get_text_pos() {
	var user_id = "<?= $this->member->item('mem_userid');?>";
	user_id = user_id == ""? "guest" : user_id;

	var data_no = $("#data_no").val();
	if($("#data_type").text() == "제공 데이터"){
        var chapter_count = $("#chapter_count").text();
    }else{
        var chapter_count = 1;
    }
    
	var noun = $("#chk0").is(':checked');
	var adj = $("#chk1").is(':checked');
	var verb = $("#chk2").is(':checked');

	if (!noun && !adj && !verb) {
		alert("품사 중 하나를 선택해주세요.");
		return;
	}

	$("#pos_btn").addClass("disable_bg");
	$("#pos_btn").text("형태소 분석 중");

    const chapterCount = localStorage.getItem("chapter_count")
	$.ajax({
    	url: '/edumining/analysis/pos',
    	method: 'post',
    	data: {
    		"user_id" : user_id,
            "data_no" : data_no,
            "chapter_count" : chapterCount,
            "tag_n_flag" : noun,
            "tag_a_flag" : adj,
            "tag_v_flag" : verb
    	},
    	dataType: 'json',
    	success : function(json){
        	console.log(json);
    		var text = json.data;

			// 가정제 데이터
    		$('#original_pos').text(text);
			// 데이터 편집
    		$('#replace_pos').text(text);

    		// 추천 단어
    		var recommend = json.recommend_list;
			var html = '';
			$("#recommend_tbody").html();

			recommend.forEach(function(li, idx){
				if (li.before == "" || li.after == "") {
					return;
				}
				
				html += '<tr>';
				html += '<td>';
				html += '<span>' + li.before_words + '</span><em class="word_num">' + li.count + '</em>';
				html += '<i class="fas fa-caret-down"></i>';
				html += '<span>' + li.after_word + '</span>';
				html += '</td>';
				html += '</tr>';
			});

			$("#recommend_tbody").html(html);

    	},
    	error: function(xhr, status, error) {
    		console.log(error);
	}
	});

	$("#pos_btn").removeClass("disable_bg");
	$("#pos_btn").text("형태소 분석");
}

/* 분석페이지로 이동 */
function goAnalysis() {
	var user_id = "<?= $this->member->item('mem_userid');?>";
	var edit_step = $("#edit_step").val();

	if (user_id == "") {
		alert("비로그인시 데이터 편집이 저장되지 않습니다.\n분석의 일부 기능을 사용하실 수 없습니다.");
		move_page(1);
	} else {
		if (edit_step != 1) {
			alert("형태소 분석을 먼저 저장해주세요.");
		} else {
			move_page(1);
		}
	}

}

</script>

