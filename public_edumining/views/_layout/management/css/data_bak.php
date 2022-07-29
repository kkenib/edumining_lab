<script type="text/javascript" src="<?php echo base_url('assets/js/sheetjs/dist/xlsx.full.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/FileSaver/dist/FileSaver.min.js'); ?>"></script>
<section class="ptitarea add">
	<div class="wrapper">
		<div class="box_ptit_txt">
			<h1>데이터 관리</h1>
			<p>뉴스 데이터 및 보유 데이터 업로드를 통한 데이터 수집</p>
		</div>
	</div>
</section>
<section class="mt40 mb60">
	<div class="wrapper">


		<!--	뉴스 데이터 수집 on 상태일 때 나오는 문구		--> 
		<div id="news_data_desc" class="alarm_notice">
			<span><i class="fas fa-box-tissue"></i>원활한 수업진행을 위해 수업 전, <strong>데이터 수집을 진행</strong>하여 주십시오.</span>
		</div>

		<!--뉴스 데이터 수집 on 상태에서 데이터 수집버튼 클릭 시 나오는 문구		--> 
		<div id="finished_crawl_desc" class="alarm_notice" style="display:none;">
			<span><i class="fas fa-check"></i><strong>데이터 수집이 완료</strong>되었습니다.</span>
		</div>

		<!--보유 데이터 업로드 on 상태일 때 나오는 문구		-->
		<div id="my_data_desc" class="alarm_notice" style="display:none;">
			<span><i class="fas fa-box-tissue"></i>업로드 가능한 <strong>최대 데이터 용량은 500MB</strong>입니다.</span>
		</div>



        <div id="dataTab" class="tab01 num2">
            <a href="#" class="on">뉴스 데이터 수집 (웹 크롤링)</a>
            <a href="#">보유 데이터 업로드</a>
        </div>
        <input id="dataType" type="hidden" value="crawl">
        <!-- 뉴스 데이터 수집 : S -->
        <div id="crawlArea">
            <div class="box round_bottom">
                <dl class="list_dl">
                    <dt>수집키워드</dt>
                    <dd><input id="crawlKeyword" type="text" placeholder="수집키워드를 입력하세요." class="w100p"></dd>
                    <input id="crawlIdx" type="hidden">
                    <input id="crawl_filename" type="hidden">
                    <dt>수집단위</dt>
                    <dd>
                        <span id="selectDateUnit" class="rbtnbox vat mr10">
                            <input type="radio" id="unit01" name="unit" value="D" checked=""><label for="unit01">일</label>
                            <input type="radio" id="unit02" name="unit" value="M"><label for="unit02">월</label>
                        </span>
                        <span id="dayText">최대 10일 간의 데이터를 수집합니다.</span><!-- day txt -->
						<span id="monthText" style="display: none;">최대 10달 간의 데이터를 수집합니다.</span><!-- month txt -->
                    </dd>
                    <dt>수집기간</dt>
                    <dd>
                        <div class="termbox vat">
                            <!--
                            <i class="far fa-calendar"></i>
                            <input type="text" value="2021-12-20" readonly="">
                            <em>~</em>
                            <i class="far fa-calendar"></i>
                            <input type="text" value="2021-12-27" readonly="">
                            -->

                            <i class="far fa-calendar"></i>
                            <input type="text" name="date_start" id="date_start" readonly class="datepicker">
                            <a href="#" onClick="$(this).prev().focus()"><span class="ico_calendar"></span></a>
                            <em> ~ </em>
                            <i class="far fa-calendar"></i>
                            <input type="text" name="date_end" id="date_end" readonly class="datepicker">
                            <a href="#" onClick="$(this).prev().focus()"><span class="ico_calendar"></span></a>
                        </div>
                    </dd>
                </dl>
                <dl id="crawlTitleArea" class="list_dl mt40" style="display: none">
                    <dt>데이터 이름</dt>
                    <dd><input id="crawlTitle" type="text" placeholder="데이터 이름을 입력해주세요." class="w100p"></dd>
                </dl>
            </div>
        </div>
        <!-- 뉴스 데이터 수집 : E -->
		<!-- 보유 데이터 업로드 : S -->
        <div id="uploadArea" style="display: none;">
            <div class="box round_bottom">
            <!--<form name="upfrm" action="/edumining/class_management/fileUpload" method="post" enctype="multipart/form-data">-->
                <?php echo form_open_multipart("/edumining/data_management/fileUpload", 'name="upfrm"');?>
                    <dl class="list_dl">
                        <dt>파일선택</dt>
                        <dd id="fileSelectArea" class="userdata">
                            <div class="file_route">
                                <input type="text" readonly="readonly" id="fileupload1" placeholder="txt, xls, xlsx 데이터를 추가해주세요.">
                                <label>
                                    <i class="fas fa-folder-open mr5"></i>파일선택
                                    <input type="file" name="file_route[]" id="" onchange="javascript:document.getElementById('fileupload1').value = this.value.split('\\')[this.value.split('\\').length-1]">
                                </label>
                            </div>
                            <button type="button" id="appendFile" class="btn skyblue" title="파일추가"><i class="fas fa-plus"></i></button>
                            <!--
                            <button type="button" class="btn skyblue" title="파일추가"><i class="fas fa-plus"></i></button>

                            <div class="file_route">
                                <input type="text" readonly="readonly" id="file_route1" placeholder="txt, xls, xlsx, pdf 데이터를 추가해주세요.">
                                <label>
                                    <i class="fas fa-folder-open mr5"></i>파일선택
                                    <input type="file" name="" id="" onchange="javascript:document.getElementById('file_route1').value = this.value.split('\\')[this.value.split('\\').length-1]">
                                </label>
                            </div>
                            <div class="file_route">
                                <input type="text" readonly="readonly" id="file_route2" placeholder="txt, xls, xlsx, pdf 데이터를 추가해주세요.">
                                <label>
                                    <i class="fas fa-folder-open mr5"></i>파일선택
                                    <input type="file" name="" id="" onchange="javascript:document.getElementById('file_route2').value = this.value.split('\\')[this.value.split('\\').length-1]">
                                </label>
                            </div>
                            -->
                        </dd>
                    </dl>
                    <dl class="list_dl mt40">
                        <dt>데이터 이름</dt>
                        <dd><input type="text" name="uploadDataName" id="dataName" placeholder="데이터 이름을 입력해주세요." class="w100p"></dd>
                    </dl>
                </form>
            </div>
        </div>
		<!-- 보유 데이터 업로드 : E -->
        <!-- 버튼 -->
        <div class="tac mt24">
			<!--	뉴스 데이터 수집 on 상태일 때 나오는 문구 : S  	--> 
			<div id="news_data_desc_sub" class="alarm_desc">
				뉴스 데이터 수집 설정에 따라 데이터 양이 많을 경우, 데이터 수집에 시간이 소요될 수 있습니다.
			</div>
			<!--	뉴스 데이터 수집 on 상태일 때 나오는 문구 : E 		-->

            <button id="crawlSubmit" type="submit" class="btn blue xlg round">데이터 수집</button>
            <!--<button id="testButton" onclick="runCrawl()" type="submit" class="btn blue xlg round">test</button>-->
            <button id="dataCrawling" type="button" class="btn skyblue xlg round disable" style="display: none">수집중<i class="fas fa-spinner fa-spin ml5"></i></button>
            <!--
            <button id="dataPreview" type="button" class="btn skyblue xlg round mr5" style="display: none">미리보기</button>
            <button id="dataDownload" type="button" class="btn grey xlg round mr5" style="display: none">다운로드</button>
            <button id="dataDelete" type="submit" class="btn pink xlg round" style="display: none">삭제</button>
            -->
        </div>
        <div class="tac mt24">
			<!--뉴스 데이터 수집 on 상태에서 데이터 수집버튼 클릭 시 나오는 문구 : S		--> 
			<div id="finished_crawl_desc_sub" class="alarm_desc" style="display: none;">
				추가로 데이터를 수집하기 위해 수집된 데이터 <b>[업로드]</b> 또는 <b>[삭제]</b>를 진행하십시오. 업로드 시, 학생들이 해당 데이터를 텍스트 분석할 수 있습니다.
				<em>*수집한 데이터를 분석에 사용하지 않을 경우, 삭제 후 다른 데이터를 수집할 수 있습니다.</em>
			</div>
			<!--뉴스 데이터 수집 on 상태에서 데이터 수집버튼 클릭 시 나오는 문구	: E 	--> 

            <button id="dataPreview" type="button" class="btn grey lg w110 round mr5" style="display: none">미리보기</button>
            <button id="dataDownload" type="button" class="btn grey lg w110 round mr5" style="display: none">다운로드</button>
            <button id="dataDelete" type="submit" class="btn grey lg w110 round" style="display: none">삭제</button>
        </div>
        <div class="tac mt20">
            <button id="uploadSubmit" type="submit" class="btn blue xlg round" style="display: none">업로드</button>
            <button id="uploading" type="button" class="btn skyblue xlg round disable" style="display: none">업로드중<i class="fas fa-spinner fa-spin ml5"></i></button>
        </div>
	</div>
</section>


<div id="deletePop" class="lpopup" style="display: none">
	<div class="wrap_lpop">
		<div class="tit_pop">
			<h2>알림</h2>
			<span onclick="popClose()" class="btn_pop_close" title="창닫기"><i class="fas fa-times"></i></span>
		</div>
		<div class="pop_body">
			<div class="cont_alert">
				<h3><strong class="blue">삭제</strong>하시겠습니까?</h3>
			</div>
			<div class="tac mt20">
				<button onclick="deleteData()" type="submit" class="btn blue mr5">확인</button>
				<button onclick="popClose()" type="button" class="btn grey">취소</button>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
    var checkCrawl_interval = null;

    $(function() {
        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd",
            showOtherMonths: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
            dayNamesMin: ['일','월','화','수','목','금','토'],
            maxDate: 0
        });
        $('.datepicker').datepicker('setDate', 'today');
    });

    $(document).ready(function() {
        pageInit();
    });

    // 뉴스 데이터 수집 -> 수집 단위 (일/월) 변경
    $("#selectDateUnit > label").click(function() {
        var unit = $(this).text();

        if (unit == "일"){
            $(".datepicker").datepicker("option", {
                dateFormat: "yy-mm-dd",
                showOtherMonths: true,
                changeYear: true,
                changeMonth: true,
                monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
                dayNamesMin: ['일','월','화','수','목','금','토'],
                maxDate: 0,
                onClose: function (dateText, inst) {
                    $(this).datepicker('setDate', dateText);
                }
            });
            $('.datepicker').datepicker('setDate', 'today');
            $("#dayText").show();
            $("#monthText").hide();
        }else if (unit == "월"){
            $(".datepicker").datepicker("option", {
                dateFormat: "yy-mm",
                showOtherMonths: true,
                changeYear: true,
                changeMonth: true,
                monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
                dayNamesMin: ['일','월','화','수','목','금','토'],
                maxDate: 0,
                onClose: function (dateText, inst) {
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).datepicker( "option", "defaultDate", new Date(year, month, 1) );
                    $(this).datepicker('setDate', new Date(year, month, 1));
                }
            });
            $("#dayText").hide();
            $("#monthText").show();
        }
    })

    // 뉴스 데이터 수집 / 보유 데이터 업로드 탭 변경
    $("#dataTab").children().click(function() {
        var clickTab = $(this).text();
        $("#dataTab").children().removeClass("on");
        $(this).addClass("on");

        if(clickTab == "뉴스 데이터 수집 (웹 크롤링)"){
            $("#dataType").val("crawl");
            $("#crawlArea").show();
            $("#uploadArea").hide();

            $("#crawlSubmit").show();
            $("#uploadSubmit").hide();

            $("#dataPreview").hide();
            $("#dataDownload").hide();
            $("#dataDelete").hide();

            $("#news_data_desc").css("display", '');
            $("#my_data_desc").css("display", "none");
            $("#finished_crawl_desc").css("display", "none");

            pageInit();
        }else if(clickTab == "보유 데이터 업로드"){
            $("#dataType").val("upload");
            $("#crawlArea").hide();
            $("#uploadArea").show();

            $("#dataPreview").hide();
            $("#dataDownload").hide();
            $("#dataDelete").hide();

            $("#crawlSubmit").hide();
            $("#dataCrawling").hide();
            $("#uploadSubmit").show();

            $("#news_data_desc").css("display", "none");
            $("#my_data_desc").css("display", '');
            $("#finished_crawl_desc").css("display", "none");
        }
    });

    // 뉴스 데이터 수집 -> 수집 등록
    $("#crawlSubmit").click(function() {
        var keyword = $("#crawlKeyword").val();
        var startDate = $("#date_start").val();
        var endDate = $("#date_end").val();
        var unit = $("input[name='unit']:checked").val();

        if(keyword == ""){
            alert("수집 키워드를 입력 해 주세요.");
            return false;
        }
        if(crawlTitle == ""){
            alert("데이터 이름을 입력 해 주세요.");
            return false;
        }
        if(new Date(startDate) > new Date(endDate)){
            alert("수집 기간을 잘못 선택하셨습니다. 다시 선택 해 주세요.");
            return false;
        }

        var dt1 = to_date(startDate);
        var dt2 = to_date(endDate);
        if(unit == "D" && ceilDate(dt1, dt2) > 10){
            alert("일별 수집은 최대 10일까지 지정이 가능합니다.");
            return false;
        }
        if(unit == "M" && ceilDate(dt1, dt2) > 300){
            alert("월별 수집은 최대 10달까지 지정이 가능합니다.");
            return false;
        }

        var postData = {
            "keyword" : keyword,
            "startDate" : startDate,
            "endDate" : endDate,
            "unit" : unit,
        }

        $.ajax({
            type: "POST",
            url: "/edumining/data_management/submitCrawl",
            data: {data : postData},
            dataType: "JSON",
            success: function(args){
                alert("등록되었습니다.");
                $("#crawlSubmit").hide();
                $("#dataCrawling").show();
                $("#crawlIdx").val(args['dataIdx']);
                $("#date_start").css("pointer-events", "none");
                $("#date_end").css("pointer-events", "none");
                $("#selectDateUnit > label").css("pointer-events", "none")
                //runCrawl();
                crawlInterval(args['dataIdx']);
            }
        });
    })

    // 뉴스 데이터 수집 -> setInterval 선언
    function crawlInterval(dataIdx){
        checkCrawl_interval = setInterval(function(){checkCrawl(dataIdx);}, 10000);
    }

    // 뉴스 데이터 수집 -> setInterval 실행 함수 (수집 완료 체크)
    function checkCrawl(dataIdx){
        $.ajax({
            type: "POST",
            url: "/edumining/data_management/checkCrawl",
            data: {dataIdx : dataIdx},
            dataType: "JSON",
            success: function(args){
                if(args['msg'] == 'success'){
                    if(args['check'] == 'complete'){
                        clearInterval(checkCrawl_interval);
                        $("#crawlTitleArea").show();
                        $("#dataPreview").show();
                        $("#dataDownload").show();
                        $("#dataDelete").show();
                        $("#uploadSubmit").show();
                        $("#dataCrawling").hide();

                        $("#news_data_desc").css("display", "none");
                        $("#my_data_desc").css("display", "none");
                        $("#finished_crawl_desc").css("display", '');
                    }
                }
            }
        });
    }

    // flask 수집 요청
    function runCrawl(){
        var dataIdx = $("#crawlIdx").val();

        $.ajax({
            type: "POST",
            url: "http://edumining.textom.co.kr:2408/runCrawl",
            data: {dataID : dataIdx},
            dataType: "JSON",
            success: function(args){
                //$("#crawl_filename").val(args['fileName']);
            }
        })
    }

    // 페이지 로딩 시, 수집중인 데이터 있는지 체크
    function pageInit(){
        $.ajax({
            type: "POST",
            url: "/edumining/data_management/pageInit",
            dataType: "JSON",
            success: function(args){
                console.log(args)

                if(args['msg'] == "crawling"){
                    $("#crawlIdx").val(args['dataIdx']);
                    $("#crawlKeyword").val(args['keyword']);

                    if(args["unit"] == "D"){
                        $("#unit01").click();
                        $("#date_start").val(args['startDate']);
                        $("#date_end").val(args['endDate']);
                    }else if(args["unit"] == "M"){
                        $("#unit02").click();
                        $("#date_start").val(args['startDate'].substring(0, 7));
                        $("#date_end").val(args['endDate'].substring(0, 7));
                    }
                    $("#date_start").css("pointer-events", "none");
                    $("#date_end").css("pointer-events", "none");
                    $("#selectDateUnit > label").css("pointer-events", "none")

                    $("#crawlSubmit").hide();
                    $("#dataCrawling").show();

                    crawlInterval(args['dataIdx']);
                }else if (args['msg'] == "upload"){
                    $("#crawlIdx").val(args['dataIdx']);
                    $("#crawlKeyword").val(args['keyword']);

                    if(args["unit"] == "D"){
                        $("#unit01").click();
                        $("#date_start").val(args['startDate']);
                        $("#date_end").val(args['endDate']);
                    }else if(args["unit"] == "M"){
                        $("#unit02").click();
                        $("#date_start").val(args['startDate'].substring(0, 7));
                        $("#date_end").val(args['endDate'].substring(0, 7));
                    }
                    $("#date_start").css("pointer-events", "none");
                    $("#date_end").css("pointer-events", "none");
                    $("#selectDateUnit > label").css("pointer-events", "none")

                    $("#crawlSubmit").hide();
                    $("#dataPreview").show();
                    $("#dataDownload").show();
                    $("#dataDelete").show();
                    $("#uploadSubmit").show();
                    $("#crawlTitleArea").show();


                    $("#news_data_desc").css("display", "none");
                    $("#my_data_desc").css("display", "none");
                    $("#finished_crawl_desc").css("display", '');
                }
            }
        })
    }

    // 수집 데이터 삭제
    function deleteData(){
        var dataIdx = $("#crawlIdx").val();

        $.ajax({
            type: "POST",
            url: "/edumining/data_management/dataDelete",
            data: {dataIdx: dataIdx},
            dataType: "JSON",
            success: function(args){
                if(args['msg'] == "success") {
                    alert("삭제하였습니다.");
                    location.reload();
                }
            }
        })
    }

    // 수집 데이터 삭제 팝업 닫기
    function popClose(){
        $("#deletePop").hide()
    }

    // 미리보기 팝업
    $("#dataPreview").click(function (){
        var dataIdx = $("#crawlIdx").val();
        var option = "width = 1000, height = 900, top = 10"
        window.open("/management/pop_view_crawl/" + dataIdx, "팝업 테스트", option);
    })

    // 수집 데이터 삭제 팝업 Show
    $("#dataDelete").click(function(){
        $("#deletePop").show();
    });

    // 데이터 업로드
    let uploading = false
    $("#uploadSubmit").click(function(e) {

        if (uploading) return
        else uploading = true

       var dataType = $("#dataType").val();

       if (dataType == "crawl"){
           var dataIdx = $("#crawlIdx").val();
           var dataName = $("#crawlTitle").val();

           if(dataName == ""){
               alert("데이터 이름을 입력 해 주세요.");
               return false;
           }

           $("#uploading").show();
           $("#uploadSubmit").hide();

           $.ajax({
               type: "POST",
               url: "/edumining/data_management/dataUpload",
               data: {
                   dataIdx: dataIdx,
                   dataName : dataName
               },
               dataType: "JSON",
               success: function(args){
                   alert("업로드하였습니다.");
                   location.reload();
               }
           })
       }else if (dataType == "upload"){
           var dataName = $("#dataName").val();
           var uploadFile = $("#fileSelectArea > div.file_route > input");

           let noFileCount = 0
           for(var i = 0; i < uploadFile.length; i++){
               var filename = $(uploadFile[i]).val();
               noFileCount = noFileCount + (filename == "" ? 1 : 0)
           }

           if (noFileCount === uploadFile.length) {
               alert("파일을 선택 해 주세요.");
               return false;
           }

           for(var i = 0; i < uploadFile.length; i++){
               var filename = $(uploadFile[i]).val();
               if(filename === '')
                   continue
               var fileExt = filename.split(".")[1];
               if(fileExt != "xls" && fileExt != "xlsx" && fileExt != "txt"){
                   alert("지원하지않는 파일형식입니다.");
                   return false;
               }
           }

           if(dataName == ""){
               alert("데이터 이름을 입력 해 주세요.");
               return false;
           }

           document.upfrm.submit();
       }
    });

    // 수집 데이터 다운로드
    $("#dataDownload").click(function() {
        var dataIdx = $("#crawlIdx").val();
        //location.href = "/edumining/data_management/dataDownload/" + dataIdx;

        $.ajax({
            type: "GET",
            url: "/edumining/data_management/dataDownload/" + dataIdx,
            dataType: "JSON",
            success: function(args){
                if(args["msg"] == "success"){
                    var wb = XLSX.utils.book_new();
                    wb.SheetNames.push("sheet 1");

                    var wsData = args['data'];
                    var ws = XLSX.utils.aoa_to_sheet(wsData);
                    wb.Sheets["sheet 1"] = ws;
                    var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});
                    saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'export_data.xlsx');
                }
            }
        })
    });

    // 보유데이터 업로드 -> 파일 추가
    $("#appendFile").click(function() {
        var id_count = $("#fileSelectArea > div.file_route").length;
        var fileuploadId = "'fileupload" + (parseInt(id_count)+1) + "'";
        var char = "'\\\\'";

        var html = '<div class="file_route">';
        html += '<input type="text" readonly="readonly" id="fileupload' + (parseInt(id_count)+1) + '" placeholder="txt, xls, xlsx 데이터를 추가해주세요.">';
        html += '<label>';
        html += '<i class="fas fa-folder-open mr5"></i>파일선택';
        html += '<input type="file" name="file_route[]" id="" onchange="javascript:document.getElementById(' + fileuploadId + ').value = this.value.split(' + char + ')[this.value.split('+ char + ').length-1]">';
        html += '</label>';
        html += '</div>';

        $("#fileSelectArea").append(html);
    });

    // 문자형 날짜 -> Date 형식으로 변환
    function to_date(date_str) {
        var yyyyMMdd = String(date_str);
        var sYear = yyyyMMdd.substring(0,4);
        var sMonth = yyyyMMdd.substring(5,7);
        var sDate = yyyyMMdd.substring(8,10);

        //alert("sYear :"+sYear +"   sMonth :"+sMonth + "   sDate :"+sDate);
        return new Date(Number(sYear), Number(sMonth)-1, Number(sDate));
    }

    // 날짜 차이 계산
    function ceilDate(date1, date2){
        return Math.ceil((date2.getTime()-date1.getTime())/(1000*3600*24));
    }

    // ArrayBuffer 만들어주는 함수
    function s2ab(s) {
        var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
        var view = new Uint8Array(buf);  //create uint8array as viewer
        for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
        return buf;
    }
</script>





















