$(function(){
    // 리뉴얼_분석 언어 선택
	$(".boxcont_input").find(":input[name='lang']").click(function() {
		$(this).parent().find("input[type='hidden']").val($(this).data("lang"));
		if($("#lang_state").val()=="KO"){
			if($("#refine_select").val() == "bot"){
				$("#tag_kind_ul").hide();
				$("#noun_chk").hide(); 
			}else{
				$("#tag_kind_ul").show();
				$("#detail").prop("disabled", false);
				$("#noun_chk").show(); 
				$("#sub_detail").show();
				$("#sub_simple").hide();
				$("#simple").prop("checked", false);
				$("#detail").prop("checked", true);
			}            
        }

        if($("#lang_state").val() == "EN"){
			if($("#refine_select").val() == "bot"){
				$("#tag_kind_ul").hide();
				$("#noun_chk").hide(); 
			}else{
				$("#tag_kind_ul").hide();
				$("#noun_chk").show();
				$("#sub_detail").hide();
				$("#sub_simple").show();
				$("#detail").prop("checked", false);
				$("#detail").css("checked", false);
				$("#simple").prop("checked", true);
				$("#detail").prop("disabled", true);
				$("#noun").prop("checked", true);
				$("#noun").prop("disabled", true);
				$("#preview").hide();
			}            
        }

        if($("#lang_state").val()=="CH"){
            $("#tag_kind_ul").hide();
            $("#noun_chk").hide();
        }
    });

	$(".boxcont_input").find(":input[name='selectRefine']").click(function() {
		$(this).parent().find("input[type='hidden']").val($(this).data("refine"));	
		if($("#refine_select").val() == "user"){
			$("#secondText").hide();
			$("#thirdText").hide();
			$("#firstText").show();

			$("#lang_state").val("KO");
			$("#kor").prop("checked", true);
            $("#seperateSection").show();
            $("#keywordSection").show();
			$("#duplicateSection").show();
			$("#windowSection").show();
			$("#languageSection").show();
			$("#tag_kind_ul").show();
			$("#languageSection").attr('class', 'boxcont tline');
			$("#morphemeAnalysis").show();
            $("#noun_chk").show(); 
            $("#dictionarySection").show();
			$("#dataRefine").show();
        }

        if($("#refine_select").val() == "bot" || $("#refine_select").val() == "pass" ){
			if($("#refine_select").val() == "bot"){
				$("#secondText").show();
				$("#thirdText").hide();
				$("#firstText").hide();
			}
			if($("#refine_select").val() == "pass"){
				$("#secondText").hide();
				$("#thirdText").show();
				$("#firstText").hide();
			}
			

            $("#seperateSection").hide();
            $("#keywordSection").hide();
			$("#duplicateSection").hide();
			$("#windowSection").hide();
			$("#languageSection").hide();
			$("#dataRefine").hide();
			/*
			if($("#refine_select").val() == "pass"){
				$("#languageSection").hide();
				$("#dataRefine").hide();
			}else{
				$("#dataRefine").show();
				$("#languageSection").show();
				$("#languageSection").attr('class', 'boxcont');
			}
			*/
			
			$("#tag_kind_ul").hide();			
			$("#morphemeAnalysis").hide();
            $("#noun_chk").hide(); 
            $("#dictionarySection").hide();
        }

    });
    
    // 리스트 선택제외 기능
	$("#except_chk").click(function() {
        var size = document.getElementsByName("choice_chk[]").length;               
        for(var i = 0; i < size; i++){                        
            if(document.getElementsByName("choice_chk[]")[i].checked == true){     
                console.log(i);           
                console.log(size);
                var box = $('[name="choice_chk[]"]').eq(i).attr('id');                 
                $("#box"+box).hide();
                $("#choice_all").prop("checked",false);                  
                 var change_chk = $('[name="choice_chk[]"]').eq(i).prop("checked",false);
                 var change_state = $('[name="choice_chk[]"]').eq(i).prop("disabled",true);
                 var change_name = $('[name="choice_chk[]"]').eq(i).prop("name","disable_chk[]"); 
                 var blank_name = $('[name="construct[]"]').eq(i).val("");
                 i = i - 1;                                                                                        
            }           
        }          
    });

    // 유료회원 수집단위 사용 | 미사용
	var value = $("#pay_result").val();
	var capacity_value = $("#large_capacity").val();
	if (value > 0 || capacity_value > 0){
		$(".boxcont_input").find(":input:radio[name='use1']").click(function() {		
			$(this).parent().find("input[type='hidden']").val($(this).data("unit"));

			if($("#unit_state").val() == "USE"){
				$("#unit_kind").show();
				$("#unit_explain").show();
				$(".disable_span").hide();
				$(".alone").hide();			
			}else{
				$("#unit_kind").hide();
				$("#unit_explain").hide();
				$(".disable_span").show();
				$(".alone").show();			
			}
		});
	}

	// 원문수집 체크시 수집단위 사용 x
	$(".boxcont_input").find(":input:radio[name='word_org']").click(function() {		
		$(this).parent().find("input[type='hidden']").val($(this).data("word"));
		if($("#word_state").val()== "Y"){
			$("#unit_collect").hide();			
		}else{
			$("#unit_collect").show();
		}
	});

	// 수집단위 유료회원, 무료회원 구분  , 무료회원 수집단위 사용, 미사용
	$("#unit_collect").change(function() {
		var value = $("#pay_result").val();
		var capacity_value = $("#large_capacity").val();
		var use = $("#use1").val();
		if (value <= 0 && capacity_value == 0){  
			$("input:radio[name='search_kind']").prop("disabled", true);
			$("#unit_state").val("NOT");
			$("#use1").val("N");
					
			if(use == "Y"){
				$("#unit_collect").addClass('user_disabled');				
				$("#unit_explain").text("데이터용량을 추가한 회원에 한하여 사용할 수 있는 기능입니다.");
				$("#unit_explain").append('<a href="/html_analysis/data_add2/data_add.php" class="btn grey sm ml5" target="_blank">데이터용량추가<i class="fas fa-angle-right ml5"></i></a>');
				$("#channel_collect").hide();				
				$("#unit_kind").show();
				$("#unit_explain").show();
			}
			if ($("input:radio[id='kind_N']").is(":checked") == true){
				$("#use1").val("Y");
				$("#unit_collect").removeClass('user_disabled');
				$("#channel_collect").show();
				$("#unit_kind").hide();
				$("#unit_explain").hide();
			}			
		}		
    });
    
    // 단순품사 Toggle
    $("#simple").click(function(){
        $("input[name=detail]").prop("checked", false);
        $("input[name=detail]").prop("disabled", false);
        $("#noun").prop("checked", true);
        $("#noun").prop("disabled", true);

        $(".detail").hide();
        $(".simple").show();
        $("#preview").hide();
    });

    // 상세품사 Toggle
    $("#detail").click(function(){        
        $("input[name=simple]").prop("checked", false);
        $("input[name=simple]").prop("disabled", false);
        $("#nng_chk").prop("checked", true);
        $("#nng_chk").prop("disabled", true);
        $("#nnp_chk").prop("checked", true);
        $("#nnp_chk").prop("disabled", true);
        $("#nnb_chk").prop("checked", true);
        $("#nnb_chk").prop("disabled", true);
        $("#nnbc_chk").prop("checked", true);
        $("#nnbc_chk").prop("disabled", true);
        $("#nr_chk").prop("checked", true);
        $("#nr_chk").prop("disabled", true);
        $("#np_chk").prop("checked", true);
        $("#np_chk").prop("disabled", true);
        
        $(".simple").hide();
        $(".detail").show();
        $("#preview").show();
    });

    // 리스트 통합생성 (readonly)
    $("#combine").click(function() {
        if ($("input:checkbox[id='combine']").is(":checked") == true){
            $(".boxchoice").find(":input[name='construct[]']").val($(this).data(""));
            $(".boxchoice").find(":input[name='construct[]']").prop("readonly",true);
            $(".mt30").find(":input[name='integrated[]']").prop("readonly",false);
        }else{
            $(".boxchoice").find(":input[name='construct[]']").prop("readonly",false);
            $(".mt30").find(":input[name='integrated[]']").prop("readonly",true);
        }        		
    });
        
    // 개별 분석리스트 사용
    $(".boxchoice").find(":input[name='construct[]']").change(function() {
        var value = $(this).val();
        if ( value.length > 0){
            $(".mt30").find(":input[name='integrated[]']").val($(this).data(""));
            $(".mt30").find(":input[name='integrated[]']").prop("readonly",true);
        }else{
            $(".mt30").find(":input[name='integrated[]']").prop("readonly",false);
        }        		
    });

    // 보유데이터 직접지정 
    $("#choice_column").find(":input[name='excel_column']").change(function() {
        var value = $(this).val();
        if (value.length > 0){
            $("#chk_column").find(":input[type='checkbox']").prop("checked",false);
            $("#chk_column").find(":input[type='checkbox']").prop("disabled",true);            
        }else{
            $("#chk_column").find(":input[type='checkbox']").prop("disabled",false);
        }        		
    });

    // 보유데이터 컬럼지정 
    $("#chk_column").find(":input[type='checkbox']").change(function() {       
        if ($("input:checkbox").is(":checked") == true){        
            $("#choice_column").find(":input[name='excel_column']").prop("disabled",true);            
        }else{
            $("#choice_column").find(":input[name='excel_column']").prop("disabled",false);
        }        		
    });


    // 사용자 사전 사용유무
    $(".boxcont_input").find(":input[name='use5']").click(function() {
		$(this).parent().find("input[type='hidden']").val($(this).data("dic"));
		if($("#dic").val()=="Y"){
			$("#dic_use").show();
			$("#seldict").show();
		}else{
			$("#dic_use").hide();
			$("#seldict").hide();
		}
    });
	
	//분석기 선택 
	$("#tag_kind1").click(function() {		
        $("#tag_kind").val("E");		
	});
	$("#tag_kind2").click(function() {
        $("#tag_kind").val("M");
    });

    // 중복제거
    $(".boxcont_input").find(":input[name='overlap']").click(function() {
		$(this).parent().find("input[type='hidden']").val($(this).data("over"));
		if($("#use_over").val()=="Y"){
            $("#duplicate").show();           
		}else{
            $("#duplicate").hide();
		}
    });
    // 키워드 필터링
    $(".boxcont_input").find(":input[name='filtering']").click(function() {
		$(this).parent().find("input[type='hidden']").val($(this).data("filter"));
		if($("#used_filter").val()=="Y"){
			$("#keyword_filtering").show();
		}else{
			$("#keyword_filtering").hide();
		}
    });
    // Window-Size
    $(".boxcont_input").find(":input[name='window']").click(function() {
		$(this).parent().find("input[type='hidden']").val($(this).data("size"));
		if($("#used_size").val()=="Y"){
			$("#window_size").show();
		}else{
			$("#window_size").hide();
		}
    });

	//네이버 전체 선택
	$(".select_naver_all").find("input:checkbox[name=naver_all]").change(function () {
		$(this).parent().find("input:checkbox").prop('checked', $(this).prop("checked"));
	});
	//다음 전체 선택
	$(".select_daum_all").find("input:checkbox[name=daum_all]").change(function () {
		$(this).parent().find("input:checkbox").prop('checked', $(this).prop("checked"));
	});
	//구글 전체 선택
	$(".select_google_all").find("input:checkbox[name=google_all]").change(function () {
		$(this).parent().find("input:checkbox").prop('checked', $(this).prop("checked"));
	});
	//바이두 전체 선택
	$(".select_baidu_all").find("input:checkbox[name=baidu_all]").change(function () {
		$(this).parent().find("input:checkbox").prop('checked', $(this).prop("checked"));
	});
	//clickReportDateSet('week_chk', 'W',1);

});

//수집기간 초기화
function resetCalendar()
{
	$("#date_end").val("");
	$("#date_start").val("");
	//$("#week_chk").removeClass("last").addClass("first");
	//$("#month_chk").removeClass("last").addClass("first");
	//$("#year_chk").removeClass("last").addClass("first");
}
// 오늘 날짜 가져오기
function getToDay()
{
	var date = new Date();
	var year  = date.getFullYear();
	var month = date.getMonth() + 1; // 1월=0,12월=11이므로 1 더함
	var day   = date.getDate();

	if (("" + month).length == 1) { month = "0" + month; }
	if (("" + day).length   == 1) { day   = "0" + day;   }

	return ("" +year+"-"+month+"-"+day)
}

//날짜 0입력
function padZero(num) {
	return (num < 10)? '0' + num : num;
}

// 기간설정
function clickReportDateSet(type, type2, cnt) {
	resetCalendar();
	$("#date_end").val(getToDay());
	var date = $("#date_end").val();
	var y = parseInt(date.substring(0,4), 10);
	var m = parseInt(date.substring(5,7), 10) - 1; // 0 ~ 11
	var d = parseInt(date.substring(8,10), 10);
	if(type2 == "D") {
		date = new Date(y, m, d - cnt);
	} else if(type2 == "M") {
        date = new Date(y, m - cnt, d);
        $("input:radio[name='term']").prop("checked");
	} else if(type2 == "W") {
        date = new Date(y, m, d - (cnt * 7));
        $("input:radio[name='term']").prop("checked");
	} else if(type2 == "Y") {
        date = new Date(y - cnt, m, d);
        $("input:radio[name='term']").prop("checked");
	}
	$("#date_start").val(date.getFullYear() + "-" + padZero(date.getMonth()+1) + "-" + padZero(date.getDate()));
}

// 사용자 사전 그룹 추가
function add_dic_group(){
	var html_add = "<tr><td><input type=\"text\" name=\"dic[]\" id=\"keyword"+cnt+"\" class=\"w50p\"></td></tr>";
}

// 데이터 정제 키워드 추가
function add_filter_keyword(){
	var cnt = $("#filterCnt").val();
	var html_add = "<div class=\"keyw_area\" id=\"addKey_"+cnt+"\"><input type=\"text\" name=\"keyword[]\" id=\"keyword"+cnt+"\" class=\"w50p\"></div>";
	$("#keywordList").append(html_add);
	cnt++;
	$("#filterCnt").val(cnt);
}
//데이터 정제 키워드 초기화
function delete_filter_keyword(){
	$("#keyword1").val("");
	var cnt = $("#filterCnt").val();
	for (var i=2;i<cnt ;i++ )
	{
		$("#addKey_"+i).remove();
	}
}

// 품사 선택 기능 초기화
function select_speech(){
    if($(".simple").css("display") != "none"){
        $("input[name=simple]").prop("checked", false);
        $("#noun").prop("checked", true);
    }
    if($(".detail").css("display") != "none"){
        $("input[name=detail]").prop("checked", false);
        $("#nng_chk").prop("checked", true);
        $("#nnp_chk").prop("checked", true);
        $("#nnb_chk").prop("checked", true);
        $("#nnbc_chk").prop("checked", true);
        $("#nr_chk").prop("checked", true);
        $("#np_chk").prop("checked", true);
    }

}

//수집 단어 추가
function addkeyword(){
	var cnt = $("#keywordCnt").val();
	var html_add = "<div class=\"keyw_area\" id=\"addKey_"+cnt+"\"><input type=\"text\" name=\"keyword[]\" id=\"keyword"+cnt+"\" class=\"keyw_input_news\"></div>";
	$("#keywordList").append(html_add);
	cnt++;
	$("#keywordCnt").val(cnt);
}

//수집 단어 초기화
function deleteKeyword(){
	$("#keyword1").val("");
	var cnt = $("#keywordCnt").val();
	for (var i = 2; i < cnt; i++ )
	{
		$("#addKey_"+i).remove();
	}
}

//뉴스 수집 단어 추가
function addkeyword_news(){
	var cnt = $("#keywordCnt").val();
	var html_add = "<div class=\"keyw_area\" id=\"addKey_"+cnt+"\"><input type=\"text\" name=\"keyword[]\" id=\"keyword"+cnt+"\" class=\"keyw_input_news\"></div>";
	$("#keywordList").append(html_add);
	cnt++;
	$("#keywordCnt").val(cnt);
}
//뉴스 수집 단어 초기화
function deleteKeyword_news(){
	$("#keyword1").val("");
	var cnt = $("#keywordCnt").val();
	for (var i=2;i<cnt ;i++ )
	{
		$("#addKey_"+i).remove();
	}
}

//사용자 url 등록 관련 popup
function url_register(){
	var url = "/html_analysis/popup/url_write_pop.php";
	window.open(url,"pop","width=900,height=450");
}

//사용자 url 등록 저장
function urlregisterPop(){
	alert("등록되었습니다.");
	location.href="/html_analysis/main/url_main.php";
}
//사용자 URL 삭제
function url_delete()
	{
		if($("input:checkbox[name='chk[]']:checked").length == 0){
			alert("선택한 데이터가 없습니다.");
			return;
		}
		if (confirm("정말 삭제하시겠습니까??") == true){    //확인
			document.sfrm.method = "post";
			document.sfrm.action = "url_delete_proc.php";
			document.sfrm.submit();
		}else{   //취소
			return;
		}
	}

//index 페이지 검색 버튼 선택시 체크 및 저장
function checkSubmit1(type)
{
	var doc = document.keywordform;
	var total = 0;
	var key_count = document.getElementsByName("keyword[]").length;	
	if($("#unit_state").val() == "USE"){
		var kind = $("input[name=search_kind]:checked").val();
		if($("#word_org").is(":checked") == true){
			var kind = "N";
		}
	}else{
		var kind = $("input[name=use1]:checked").val();
	}

	console.log(kind);
    
	if(type == "channel"){
		checkboxChecked = false;
		if($("#date_start").val() > $("#date_end").val()){
			alert("기간설정을 확인하세요.");
			return false;			
		}

		if($("#keyword1").val() == "")
		{
			alert("키워드를 입력해 주세요.");
			return false;
		}
		else
		{
			var startDate = new Date($("#date_start").val());
			var endDate = new Date($("#date_end").val());
			var tot = 1;
			var org = 200;
			if ($("#date_start").val() == "")
			{
				if (kind =="D")
				{
					tot = 30;
				}else if(kind =="W"){
					tot = 4;
				}else if(kind =="M"){
					tot = 1;
				}else if(kind =="Y"){
					tot = 1;
				}
				
			}else{
				if (kind =="D")
				{
					tot = Math.ceil((endDate-startDate)/86400/1000);
				}else if(kind =="W"){
					tot = Math.ceil((endDate-startDate)/86400/1000/7);
				}else if(kind =="M"){
					tot = Math.ceil((endDate-startDate)/86400/1000/30);
				}else if(kind =="Y"){
					tot = Math.ceil((endDate-startDate)/86400/1000/365);
				}
			}

			if(doc.word_org.checked == true)
			{
				org = 1500;
			}

			if($("#keyword1").val() == ""){
				$("#keyword1").val(" ");
			}

			if($("#unit_state").val() == "USE"){

				doc.naver_academic.checked = false;
				doc.naver_web.checked = false;
				doc.daum_web.checked = false;
				doc.google_web.checked = false;
				doc.google_facebook.checked = false;
				//doc.facebook_all.checked = false;
				doc.twitter_all.checked = false;
				//doc.youtube_all.checked = false;
				doc.baidu_all.checked = false;

				if(doc.naver_blog.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				if(doc.naver_cafe.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				if(doc.naver_news.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				if(doc.naver_knowledge.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				if(doc.daum_blog.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				if(doc.daum_cafe.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				if(doc.daum_news.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				/*
				if(doc.daum_knowledge.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				*/
				if(doc.google_news.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
			}else{
				if(doc.naver_blog.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				if(doc.naver_cafe.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				if(doc.naver_news.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				if(doc.naver_web.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				if(doc.naver_knowledge.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				if(doc.naver_academic.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				if(doc.daum_blog.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				if(doc.daum_cafe.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				if(doc.daum_news.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				if(doc.daum_web.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				/*
				if(doc.daum_knowledge.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				*/
				if(doc.google_web.checked == true)
				{
					checkboxChecked = true;
					total +=1;
				}
				if(doc.google_news.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				if(doc.google_facebook.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				/*
				if(doc.facebook_all.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				*/
				if(doc.twitter_all.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				/*
				if(doc.youtube_all.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
				*/
				if(doc.baidu_all.checked == true)
				{
					checkboxChecked = true;
					total += 1;
				}
			}				

			var total_val = total * org * tot * key_count;
			if(checkboxChecked == true)
			{
				$("#collect_list").removeClass("btn_disable");
				if(doublSubmitCheck()) return;
				doc.method = "post";
                doc.action = "keyword_proc.php";                
                doc.submit();
				// if (confirm("예상 데이터량은 "+total_val+" KB입니다. \r\n‘확인’ 버튼을 누르시면 수집이 시작됩니다..\r\n진행하시겠습니까?\r\n*평균적인 데이터량으로 계산한 결과이며 실제 수집 시 차이가 있을 수 있습니다.")){
				// 	 doc.submit();
				// }
			} else {
				alert("채널을 하나 이상 선택해 주세요.");
				return;
			}
		}
	}else if(type == "url"){
		var doc_url = document.sfrm;
		if($("input:checkbox[name='chk[]']:checked").length>0){
			
			if($("#keyword").val() == ""){
				alert("키워드를 입력해주세요.");
				return;
			}else{
				if(doublSubmitCheck()) return;
				doc_url.method = "post";
				doc_url.action = "user_keyword_proc.php";
				doc_url.submit();
			} 
		}else{
			alert("수집할 URL을 선택하세요.");
			return;
		}
		
	}else if(type == "site"){
		var doc_url = document.sfrm;
		if($("#keyword").val() == ""){
			alert("사이트 URL을 입력해주세요.");
			return;
		}else if($("#site_name").val() == ""){
			alert("사이트명을 입력해주세요.");
			return;
		}else{
			if(doublSubmitCheck()) return;
			doc_url.method = "post";
			doc_url.action = "site_proc.php";
			doc_url.submit();
		} 			
	}
}

var doubleSubmitFlag = false;
function doublSubmitCheck(){
	if(doubleSubmitFlag){
		return doubleSubmitFlag;
	}else{
		doubleSubmitFlag = true;
		return false;

	}
}

// 텍스톰 3.5 정제/형태소분석
function checkSubmit_pretreat(){

    var doc = document.filterkeyword;
    var size = document.getElementsByName("choice_chk[]").length;    
    var construct = document.getElementsByName("construct[]").length;        
    var check_size = 0;
    var construct_size = 0;

    var speechList = new Array();
    
    // 단순품사 체킹 값
    $("input[name=simple]").each(function(index, item){
        if($(item).is(":checked") == true) speechList.push($(item).val());           
    });
    
    // 상세품사 체킹 값
    $("input[name=detail]").each(function(index, item){
        if($(item).is(":checked") == true) speechList.push($(item).val());           
    });        
    
    // on 값 삭제
    while(true){
        var search = speechList.indexOf("on");
        if(search != -1){
            speechList.splice(search,1);
        }else{
            break;
        }
    }
    var strList = speechList.join(',');
    document.getElementById("kind1").value = strList;    
    
    // 수집완료 리스트 체크 계산
    for( var i = 0; i< size; i++){
        if(document.getElementsByName("choice_chk[]")[i].checked == true){  
            check_size++;
        }
    }
    for( var j = 0; j < construct; j++){
        if($('[name="construct[]"]').eq(j).val()){  
            construct_size++;
        }        
    }
    
    if(document.getElementsByName("combine[]")[0].checked == true){
        if($("input:checkbox[name='choice_chk[]']:checked").length> 0){     
            doc.method = "post";
            doc.action = "pretreat_proc.php";        
			if($("#integrated").val() == ''){
				alert("분석리스트 데이터명을 입력해 주세요.");
			}
            else{
				if(confirm("분석리스트를 생성하시겠습니까? \n'확인'과 동시에 선택한 데이터의 용량만큼 보유용량이 삭감됩니다.") == true){					
					doc.submit();
				}else{
					return false;
				}				
            }   
        }else{
            alert("정제할 리스트를 선택해 주세요.");
            return false;
        } 
    }else{        
        if($('[name="integrated[]"]').eq(0).val()){
            alert("리스트통합생성 버튼을 체크해 주십시오.");
            return false;
        }else if($("input:checkbox[name='choice_chk[]']:checked").length > 0){			
			var checkLength = $("input:checkbox[name='choice_chk[]']:checked").length;

			for(var i=0; i<$("input:checkbox[name='choice_chk[]']").length; i++) {
				var confirm_check = $("input:checkbox[name='choice_chk[]']").eq(i).is(":checked");			
				var empty_construct = $.trim($("input:text[name='construct[]']").eq(i).val());
				if((empty_construct.length < 1) && (confirm_check == true)) {  
					var keyword = $("input:hidden[name='key_list[]']").eq(i).val();		
					keyword = keyword.replace( /\^+/g, "\"" );
					$("input:text[name='construct[]']").eq(i).val(keyword);
				}
			}
            doc.method = "post";
            doc.action = "pretreat_proc.php";
            if(confirm("분석리스트를 생성하시겠습니까? \n'확인'과 동시에 선택한 데이터의 용량만큼 보유용량이 삭감됩니다.") == true){
                doc.submit();
            }else{
				return false;
			}
        }else{
            alert("정제할 리스트를 선택해 주세요.");
            return false;
        }
    }    
}

//index 페이지 검색 버튼 선택시 체크 및 저장
function checkSubmit_news()
{
	var doc = document.keywordform;
	var key_count = document.getElementsByName("keyword[]").length;
	checkboxChecked = false;
	if($("#date_start").val() > $("#date_end").val()){
		alert("기간설정을 확인하세요.");
		return false;			
	}
	var total = 0;
	if($("#keyword1").val() == "")
	{
		alert("키워드를 입력해 주세요.");
		return false;
	}
	else
	{
		var startDate = new Date($("#date_start").val());
		var endDate = new Date($("#date_end").val());
		var tot = 1;
		var org = 40;
		if ($("#date_start").val() =="")
		{
			tot = 30;
		}else{
			tot = Math.ceil((endDate-startDate)/86400/1000);
		}
		if (tot > 92)
		{
			alert("뉴스 수집기간은 최대 3개월 입니다.");
			return false;
		}
		if(doc.chosun_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		if(doc.kbs_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		if(doc.mbc_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		if(doc.sbs_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		if(doc.hani_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		if(doc.joongang_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		if(doc.khan_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		if(doc.newsis_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		if(doc.nocut_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		if(doc.ohmynews_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		if(doc.mk_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		if(doc.hankyung_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		//if(doc.isplus_all.checked == true)
		//{
		//	checkboxChecked = true;
		//	total +=1;
		//}
		if(doc.zdnet_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		if(doc.ytn_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		if(doc.yonhapnews_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		if(doc.seoul_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		if(doc.news1_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		if(doc.hankookilbo_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		if(doc.etnews_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}
		if(doc.donga_all.checked == true)
		{
			checkboxChecked = true;
			total +=1;
		}

		var total_val = total * org * tot *key_count;
		if(checkboxChecked == true)
		{
			if(doublSubmitCheck()) return;
			doc.method = "post";
            doc.action = "news_keyword_proc.php";
            doc.submit();
			// if (confirm("예상 데이터량은 "+total_val+" KB입니다. \r\n‘확인’ 버튼을 누르시면 수집이 시작됩니다..\r\n진행하시겠습니까?\r\n*평균적인 데이터량으로 계산한 결과이며 실제 수집 시 차이가 있을 수 있습니다.")){
			// 	doc.submit();
			// }
		} else {
			alert("채널을 하나 이상 선택해 주세요.");
			return;
		}
	}
}

//index 페이지 검색 버튼 선택시 체크 및 저장
function checkSubmit2(type)
{
	var doc = document.keywordform;
	if(type == "channel"){
		checkboxChecked = false;
		if($("#date_start").val() > $("#date_end").val()){
			alert("기간설정을 확인하세요.");
			return false;			
		}

		if($("#keyword1").val() == "" && $("#key_eq1").val() =="")
		{
			alert("키워드를 입력해 주세요.");
			return false;
		}
		else
		{
			if($("#keyword1").val() == ""){
				$("#keyword1").val(" ");
			}
			if(doc.naver_blog.checked == true)
			{
				checkboxChecked = true;
			}
			if(doc.naver_cafe.checked == true)
			{
				checkboxChecked = true;
			}
			if(doc.naver_news.checked == true)
			{
				checkboxChecked = true;
			}
			if(doc.naver_web.checked == true)
			{
				checkboxChecked = true;
			}
			if(doc.naver_knowledge.checked == true)
			{
				checkboxChecked = true;
			}
			if(doc.naver_academic.checked == true)
			{
				checkboxChecked = true;
			}
			if(doc.daum_blog.checked == true)
			{
				checkboxChecked = true;
			}
			if(doc.daum_cafe.checked == true)
			{
				checkboxChecked = true;
			}
			if(doc.daum_news.checked == true)
			{
				checkboxChecked = true;
			}
			if(doc.daum_web.checked == true)
			{
				checkboxChecked = true;
			}
			/*
			if(doc.daum_knowledge.checked == true)
			{
				checkboxChecked = true;
			}
			*/
			if(doc.google_web.checked == true)
			{
				checkboxChecked = true;
			}
			if(doc.google_news.checked == true)
			{
				checkboxChecked = true;
			}
			if(doc.google_facebook.checked == true)
			{
				checkboxChecked = true;
			}
			/*
			if(doc.facebook_all.checked == true)
			{
				checkboxChecked = true;
			}
			*/
			if(doc.twitter_all.checked == true)
			{
				checkboxChecked = true;
			}
			/*
			if(doc.youtube_all.checked == true)
			{
				checkboxChecked = true;
			}
			*/
			if(doc.baidu_web.checked == true)
			{
				checkboxChecked = true;
			}
			if(doc.baidu_news.checked == true)
			{
				checkboxChecked = true;
			}
			if(doc.baidu_cafe.checked == true)
			{
				checkboxChecked = true;
			}
			if(doc.baidu_webio.checked == true)
			{
				checkboxChecked = true;
			}
			if(doc.baidu_blog.checked == true)
			{
				checkboxChecked = true;
			}
			if(doc.baidu_wechat.checked == true)
			{
				checkboxChecked = true;
			}

			if(checkboxChecked == true)
			{
				doc.method = "post";
				doc.action = "keyword_proc_test.php";
				doc.submit();
			} else {
				alert("채널을 하나 이상 선택해 주세요.");
				return;
			}
		}
	}else if(type == "url"){
		var doc_url = document.sfrm;
		if($("input:checkbox[name='chk[]']:checked").length>0){
			
			if($("#keyword").val() == ""){
				alert("키워드를 입력해주세요.");
				return;
			}else{
				doc_url.method = "post";
				doc_url.action = "user_keyword_proc.php";
				doc_url.submit();
			} 
		}else{
			alert("수집할 URL을 선택하세요.");
			return;
		}
		
	}else if(type == "custom"){
		var doc_url = document.keywordform;
		if($("input:checkbox[name='chk[]']:checked").length>0){
			if($("#keyword1").val() == ""){
				alert("키워드를 입력해주세요.");
				return;
			}else{
				doc_url.method = "post";
				doc_url.action = "customized_proc.php";
				doc_url.submit();
			}
		}else{
			alert("수집할 채널을 선택하세요.");
			return;
		}		
	}else if(type == "site"){
		var doc_url = document.sfrm;
		if($("#keyword").val() == ""){
			alert("사이트 URL을 입력해주세요.");
			return;
		}else if($("#site_name").val() == ""){
			alert("사이트명을 입력해주세요.");
			return;
		}else{
			doc_url.method = "post";
			doc_url.action = "site_proc.php";
			doc_url.submit();
		} 
	}
}
