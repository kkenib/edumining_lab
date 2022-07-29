<script>
    const agent = window.navigator.userAgent.toLowerCase()
    if (agent.indexOf("trident") > -1) {
        alert("원활한 서비스 이용을 위해 크롬 브라우저를 이용해주세요.")
    }
</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/sheetjs/dist/xlsx.full.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/FileSaver/dist/FileSaver.min.js'); ?>"></script>
<section class="visualarea sub">
    <div class="wrapper">
        <div class="subvisual_bg manage">
            <div class="subvisual_box">
                <h2 class="bold">데이터 <b>관리</b></h2>
				<p>
					뉴스 데이터 수집 또는 보유 데이터 업로드를 통해<br>
					학생들이 텍스트 마이닝에 사용할 데이터를 등록하세요.
				</p>
            </div>
        </div>
    </div>
</section>

<section class="mt80 pb80">
    <div class="wrapper">

		<section class="mt80 pb50">

			<div class="tac mb15">
				
				<!--	뉴스 데이터 수집 on 상태일 때 나오는 문구		-->
				<div id="news_data_desc" class="alarm_notice">
					<i class="fas fa-archive"></i>원활한 수업진행을 위해 수업 전, <strong>데이터 수집을 진행</strong>하여 주십시오.
				</div>

				<!--뉴스 데이터 수집 on 상태에서 데이터 수집버튼 클릭 시 나오는 문구		--> 
				<div id="finished_crawl_desc" class="alarm_notice" style="display: none;">
					<i class="fas fa-check"></i><strong>데이터 수집이 완료</strong>되었습니다.
				</div>

				<!--보유 데이터 업로드 on 상태일 때 나오는 문구		-->
				<div id="my_data_desc" class="alarm_notice" style="display: none;">
					<i class="fas fa-archive"></i>데이터를 분할하여 업로드 할 경우, 추이 분석이 가능합니다. <strong>+ 버튼을 클릭</strong>하여 데이터를 추가하세요.
<!-- 					<i class="fas fa-archive"></i>업로드 가능한 <strong>최대 데이터 용량은 500MB</strong>입니다. -->
				</div>

			</div>

			<div id="dataTab" class="tab01 num02">
                <a id="search_tab" class="data_tab_item">뉴스 DB 검색</a>
				<a id="crawling_tab" class="data_tab_item">뉴스 데이터 수집 (웹 크롤링)</a>
				<a id="upload_tab" class="data_tab_item">보유 데이터 업로드</a>
			</div>
			<div class="grey_contbox tabplus">
				<input id="dataType" type="hidden" value="crawl">
				<div id="crawlArea">

                    <!-- 뉴스 DB 검색 : S -->
                    <div id="newsSearchArea">
                        <div class="wh_box">
                            <dl class="list_dl">
                                <dt>검색키워드</dt>
                                <dd><input id="searchKeyword" type="text" placeholder="검색키워드를 입력하세요." class="w100p"></dd>
                                <input id="searchIdx" type="hidden">
                                <input id="search_filename" type="hidden">
                                <dt>검색기간</dt>
                                <dd>
                                    <div class="termbox vat">

                                        <i class="far fa-calendar"></i>
                                        <input type="text" name="date_start" id="search_date_start" readonly class="datepicker">
                                        <a href="#" onClick="$(this).prev().focus()"><span class="ico_calendar"></span></a>
                                        <em> ~ </em>
                                        <i class="far fa-calendar"></i>
                                        <input type="text" name="date_end" id="search_date_end" readonly class="datepicker">
                                        <a href="#" onClick="$(this).prev().focus()"><span class="ico_calendar"></span></a>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    
					<!-- 뉴스 DB 검색 : E -->

					<!-- 뉴스 데이터 수집 : S -->
                    <div id="newsCrawlArea" style="display: none">
                        <div class="wh_box">
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
                                        <span id="dayText">최대 10일 간의 데이터를 수집합니다.</span>
                                        <span id="monthText" style="display: none;">최대 10달 간의 데이터를 수집합니다.</span>
                                    </dd>
                                    <dt>수집기간</dt>
                                    <dd>
                                        <div class="termbox vat">

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

                                <dl id="crawlTitleArea" class="list_dl mt40" style="display: none;"><!-- style="display:none;" -->
                                    <dt>데이터 이름</dt>
                                    <dd><input id="crawlTitle" type="text" placeholder="데이터 이름을 입력해주세요." class="w100p"></dd>
                                </dl>
                            </div>
                            <div class="sub_text">※ 네이버 뉴스 채널에서 제공하는 전체 언론사의 기사를 수집합니다.</div>
                        </div>
                    </div>
					<!-- 뉴스 데이터 수집 : E -->
                    
					<!-- 보유 데이터 업로드 : S -->
					<div id="uploadArea" style="display:none;">
						<div class="wh_box">
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
                                        <button type="button" id="appendFile" class="btn grey" style="padding: 7px 11px; border: 1px solid #999;" title="파일추가"><i class="fas fa-plus"></i></button>

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
						<div class="sub_text">※ 업로드 가능한 최대 데이터 용량은 500MB입니다.</div>
					</div>
					
				</div>

			</div>

			<div id="crawl_control_area" class="tac">
				<!-- 보유 데이터 업로드 : E -->
				<!-- 버튼 -->
				<div class="tac mt24">
                    <!--	뉴스 데이터 수집 on 상태일 때 나오는 문구 : S  	--> 
                    <div id="news_data_desc_sub" class="alarm_desc">
                        뉴스 데이터 수집 설정에 따라 데이터 양이 많을 경우, 데이터 수집에 시간이 소요될 수 있습니다.
                    </div>
                    <!--	뉴스 데이터 수집 on 상태일 때 나오는 문구 : E 		-->

                    <button id="crawlSubmit" type="submit" class="btn navy lg round">데이터 수집</button>
                    <!--<button id="testButton" onclick="runCrawl()" type="submit" class="btn blue xlg round">test</button>-->
                    <div id="dataSize" style="display: none;">수집된 데이터 크기 0.0 KB</div>
                    <div id="dataTime" style="display: none;">경과시간 00:00:00</div>
                    <div id="lastText" style="display: none;"></div>
                    <button id="dataCrawling" type="button" class="btn skyblue lg round disable mt10" style="display: none;">수집중<i class="fas fa-spinner fa-spin ml5"></i></button>
                    
                    <!--
                    <button id="dataPreview" type="button" class="btn blue lg round mr5" style="display: none">미리보기</button>
                    <button id="dataDownload" type="button" class="btn grey lg round mr5" style="display: none">다운로드</button>
                    <button id="dataDelete" type="submit" class="btn pink lg round" style="display: none">삭제</button>
                    -->
                </div>
                <div class="tac mt15">
                    <!--뉴스 데이터 수집 on 상태에서 데이터 수집버튼 클릭 시 나오는 문구 : S		--> 
                    <div id="finished_crawl_desc_sub" class="alarm_desc" style="display: none;">
                        추가로 데이터를 수집하기 위해 수집된 데이터 <b>[업로드]</b> 또는 <b>[삭제]</b>를 진행하십시오. 업로드 시, 학생들이 해당 데이터를 텍스트 분석할 수 있습니다.
                        <em>*수집한 데이터를 분석에 사용하지 않을 경우, 삭제 후 다른 데이터를 수집할 수 있습니다.</em>
                    </div>
                    <!--뉴스 데이터 수집 on 상태에서 데이터 수집버튼 클릭 시 나오는 문구	: E 	-->

                    <button id="dataPreview" type="button" class="btn grey mid w110 round mr5" style="display: none;">미리보기</button>
                    <button id="dataDownload" type="button" class="btn grey mid w110 round mr5" style="display: none;">다운로드</button>
                    <button id="dataDelete" type="submit" class="btn grey mid w110 round" style="display: none;">삭제</button>
                </div>
                <div class="tac mt10">
                    <button id="uploadSubmit" type="submit" class="btn navy lg w110 round" style="display: none;">업로드</button>
                    <button id="uploading" type="button" class="btn lg w110 round disable" style="display: none;">업로드중<i class="fas fa-spinner fa-spin ml5"></i></button>
                </div>
			</div>

            <div id="search_control_area" class="tac">

				<div id="search_box" class="tac mt15" style="display:none">
                    <div class="alarm_desc">검색어에 따라 데이터 검색에 많은 시간이 소요될 수 있습니다.</div>
                    <button id="searchSubmit" type="submit" class="btn navy lg round" >데이터 검색</button>
                </div>

                <div id="searching_box" class="tac mt10"  style="display:none">
                    <div class="alarm_desc">검색어에 따라 데이터 검색에 많은 시간이 소요될 수 있습니다.</div>
                    <button type="button" class="btn skyblue lg round disable mt10" >검색중<i class="fas fa-spinner fa-spin ml5"></i></button>
                </div>

                <div id="searched_box" class="tac mt15" style="display:none">

                    <div class="alarm_desc">
                        데이터를 다시 검색하기 위해 검색된 데이터 <b>[업로드]</b> 또는 <b>[삭제]</b>를 진행하십시오. 업로드 시, 학생들이 해당 데이터를 텍스트 분석할 수 있습니다.
                        <em>*검색된 데이터를 분석에 사용하지 않을 경우, 삭제 후 다른 데이터를 검색할 수 있습니다.</em>
                    </div>

                    <button id="searchDataPreview" type="button" class="btn grey mid w110 round mr5" >미리보기</button>
                    <button id="searchDataDownload" type="button" class="btn grey mid w110 round mr5" >다운로드</button>
                    <button id="searchDataDelete" type="submit" class="btn grey mid w110 round" >삭제</button>
                    
                    <div class="tac mt10">
                        <button id="searchedUploadSubmit" type="submit" class="btn navy lg w110 round">업로드</button>
                        <button id="searchedUploading" type="button" class="btn lg w110 round disable">업로드중<i class="fas fa-spinner fa-spin ml5"></i></button>
                    </div>
                    
                    
                </div>


		</section>


    </div>
</section>




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
        // pageInit();

        const doc = document.getElementById("news_data_desc")
        const userLevel = ~~("<?php echo $this->member->item('mem_level') ?>")
        if (userLevel === 1) {
            alert("학생 회원은 해당 기능을 이용할 수 없습니다.")
            doc.innerHTML = `<i class="fas fa-archive"></i>학생 회원은 해당 기능을 <strong>이용할 수 없습니다.</strong>`
        }

        if (userLevel === 0) {
            alert("로그인이 필요합니다.")
            doc.innerHTML = `<i class="fas fa-archive"></i>해당 기능을 이용하기 위해서는 먼저 <strong>로그인</strong>을 해주세요.`
        }

        initSearchTabPage()

    });

    // 뉴스 데이터 수집 -> 수집 단위 (일/월) 변경
    $("#selectDateUnit > label").click(function() {
        var unit = $(this).text();
        $(".datepicker").datepicker("option", {
            dateFormat: (unit === "일") ? "yy-mm-dd" :  "yy-mm",
            showOtherMonths: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
            dayNamesMin: ['일','월','화','수','목','금','토'],
            maxDate: 0,
            onClose: function (dateText, inst) {
                if (unit === "일") {
                    $(this).datepicker('setDate', dateText);
                } else {
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).datepicker("option", "defaultDate", new Date(year, month, 1));
                    $(this).datepicker('setDate', new Date(year, month, 1));
                }
            }
        });
        $('.datepicker').datepicker('setDate', 'today');
        document.getElementById("dayText").style["display"]   = (unit === '일') ?     '' : "none"
        document.getElementById("monthText").style["display"] = (unit === '일') ? "none" : ''
    })

    function addClassFromDom(dom, className) {
        if (dom !== null && !dom.classList.contains(className))
            dom.classList.add(className)
    }
    function removeClassFromDom(dom, className) {
        if (dom !== null && dom.classList.contains(className))
            dom.classList.remove(className)
    }
    function addClickListenerById(id, action) { document.getElementById(id).addEventListener("click", action) }
    function showElementsById (idList) { idList.forEach((id, index) => { document.getElementById(id).style["display"] = '' }) }
    function hideElementsById (idList) { idList.forEach((id, index) => { document.getElementById(id).style["display"] = "none" }) }
    function updateTabState(targetId) {
        const tabItems = document.getElementsByClassName("data_tab_item")
        for (const dom of tabItems) {
            removeClassFromDom(dom, "on")
        }
        const targetDom = document.getElementById(targetId)
        addClassFromDom(targetDom, "on")
    }

    function initSearchTabPage() {
        hideElementsById(["uploadArea", "uploadSubmit", "dataPreview", "dataDownload", "dataDelete", "my_data_desc", "finished_crawl_desc", "finished_crawl_desc_sub"])
        hideElementsById(["newsCrawlArea", "dataPreview", "dataDownload", "dataDelete", "crawlSubmit", "dataCrawling", "news_data_desc", "news_data_desc_sub",  "finished_crawl_desc", "finished_crawl_desc_sub", "dataSize"], )
        showElementsById(["newsSearchArea"])
        updateTabState("search_tab")
    }
    addClickListenerById("search_tab", () => {

            const userLevel = ~~("<?php echo $this->member->item('mem_level') ?>")
            if (userLevel === 1) {
                alert("학생 회원은 해당 기능을 이용할 수 없습니다.")
            }

            if (userLevel === 0) {
                alert("로그인이 필요합니다.")
            }

            initSearchTabPage()
        })

    addClickListenerById("crawling_tab", () => {

        const userLevel = ~~("<?php echo $this->member->item('mem_level') ?>")
        if (userLevel === 1) {
            alert("학생 회원은 해당 기능을 이용할 수 없습니다.")
        }

        if (userLevel === 0) {
            alert("로그인이 필요합니다.")
        }

        $("#dataType").val("crawl");
        showElementsById(["newsCrawlArea", "crawlSubmit", "news_data_desc", "news_data_desc_sub"])
        hideElementsById(["newsSearchArea", "uploadArea", "uploadSubmit", "dataPreview", "dataDownload", "dataDelete", "my_data_desc", "finished_crawl_desc", "finished_crawl_desc_sub"])
        updateTabState("crawling_tab")
        pageInit()
    })

    addClickListenerById("upload_tab", () => {

        const userLevel = ~~("<?php echo $this->member->item('mem_level') ?>")
        if (userLevel === 1) {
            alert("학생 회원은 해당 기능을 이용할 수 없습니다.")
        }

        if (userLevel === 0) {
            alert("로그인이 필요합니다.")
        }

        $("#dataType").val("upload");
        hideElementsById(["newsSearchArea", "newsCrawlArea", "dataPreview", "dataDownload", "dataDelete", "crawlSubmit", "dataCrawling", "news_data_desc", "news_data_desc_sub",  "finished_crawl_desc", "finished_crawl_desc_sub", "dataSize"], )
        showElementsById(["uploadArea", "uploadSubmit", "my_data_desc"])
        updateTabState("upload_tab")
    })

    // 뉴스 데이터 수집 -> 수집 등록
    $("#crawlSubmit").click(function() {

        const userNo = ~~("<?php echo $this->member->item('mem_id') ?>")
        const userLevel = ~~("<?php echo $this->member->item('mem_level') ?>")
        if (userLevel === 1) {
            alert("학생 회원은 해당 기능을 이용할 수 없습니다.")
            return
        }

        if (userNo === 0) {
            alert("로그인이 필요합니다.")
            const redirectUrl = encodeURIComponent(`${location.protocol}//${location.hostname}/analysis/data_manage`)
            location.href = `/login?url=${redirectUrl}`
            return
        }

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
                $("#crawlKeyword").attr("disabled", true)
                $("#crawlSubmit").hide();
                $("#dataCrawling").show();
                $("#crawlIdx").val(args['dataIdx']);
                $("#date_start").css("pointer-events", "none");
                $("#date_end").css("pointer-events", "none");
                $("#selectDateUnit > label").css("pointer-events", "none")
                crawlInterval(args['dataIdx']);
            }
        });
    })

    // 뉴스 데이터 수집 -> setInterval 선언
    function crawlInterval(dataIdx){
        checkCrawl_interval = setInterval(function(){checkCrawl(dataIdx);}, 5000);
    }

    function updateCrawlingResult(dataSize, updateDate) {
        $("#crawlTitleArea").css("display", dataSize > 0 ? '' : "none");
        $("#dataPreview").css("display", dataSize > 0 ? '' : "none");
        $("#dataDownload").css("display", dataSize > 0 ? '' : "none");
        $("#dataDelete").css("display", '');
        $("#uploadSubmit").css("display", dataSize > 0 ? '' : "none");
        $("#dataCrawling").css("display", "none");

        $("#news_data_desc").css("display", "none")
        $("#news_data_desc_sub").css("display", "none")
        $("#my_data_desc").css("display", "none")
        $("#finished_crawl_desc").css("display", '');
        $("#finished_crawl_desc_sub").css("display", '');

        if(dataSize === 0)
            $("#finished_crawl_desc_sub").html("해당 키워드로 수집된 데이터가 없습니다. 수집 키워드 및 기간을 확인한 후, <b>[삭제]</b> 후 다시 데이터 수집을 진행하세요.")
        else {
            updateDataSize(dataSize)
            updateCollectionTime(updateDate)
        }
    }
    function updateDataSize(dataSize) {
        dataSize = dataSize / 1024
        const dataUnit = (dataSize > 1024) ? "MB" : "KB"
        dataSize = ( (dataSize) > 1024 ? (dataSize / 1024) : dataSize ).toFixed(2)
        $("#dataSize").css("display", '')
        $("#dataSize").html(`수집된 데이터 크기 ${dataSize} ${dataUnit}`)
    }
    function updateCollectionTime(updateDate) {
        if(updateDate === undefined)
            return

        const dateTime = updateDate.split(' ')
        const date = dateTime[0].split('-')
        const year = ~~(date[0])
        const month = ~~(date[1]) - 1
        const day = ~~(date[2])

        const time = dateTime[1].split(':')
        const hour = ~~(time[0])
        const minute = ~~(time[1])
        const second = ~~(time[2])

        const updated = new Date(year, month, day, hour, minute, second)
        const current = new Date()
        const elapsed = ~~((current.getTime() - updated.getTime()) / 1000)

        // $("#dataTime").css("display", '')
        $("#dataTime").html(`경과시간 ${elapsed}`)
    }
    function updateLastText(lastText) {
        const lastTextDom = document.getElementById("lastText")
        lastTextDom.innerHTML = `${lastText.substr(0, 50)}`
        lastTextDom.style["display"] = 'none'
    }

    // 뉴스 데이터 수집 -> setInterval 실행 함수 (수집 완료 체크)
    function checkCrawl(dataIdx){
        $.ajax({
            type: "POST",
            url: "/edumining/data_management/checkCrawl",
            data: {dataIdx : dataIdx},
            dataType: "JSON",
            success: function(args){
                if(args['msg'] !== 'success')
                    return

                let dataSize = ~~args["data_size"]
                const updateDate = args["update_date"]
                if(args['check'] === 'complete') {
                    clearInterval(checkCrawl_interval);
                    updateCrawlingResult(dataSize, updateDate)

                    const lastTextDom = document.getElementById("lastText")
                    lastTextDom.style["display"] = 'none'

                } else {
                    updateLastText(args["last_text"])
                    updateDataSize(dataSize)
                    updateCollectionTime(updateDate)
                }
            }
        });
    }

    // 페이지 로딩 시, 수집중인 데이터 있는지 체크
    function pageInit(){
        $.ajax({
            type: "POST",
            url: "/edumining/data_management/pageInit",
            dataType: "JSON",
            success: function(args){
                console.log(args)
                if(args['msg'] == "crawling" || args['msg'] == "upload"){
                    $("#crawlIdx").val(args['dataIdx']);
                    $("#crawlKeyword").val(args['keyword']);

                    const unit = args["unit"]
                    const buttonId = { D: "unit01", M: "unit02" }
                    if( buttonId[unit] !== '') {
                        $('#' + buttonId[unit]).click();
                    }

                    const startDate = args['startDate']
                    $("#date_start").val(startDate.substring(0, unit === 'M' ? 7 : startDate.length));

                    const endDate = args["endDate"]
                    $("#date_end").val(endDate.substring(0, unit === 'M' ? 7 : endDate.length));
                    $("#date_start").css("pointer-events", "none");
                    $("#date_end").css("pointer-events", "none");
                    $("#selectDateUnit > label").css("pointer-events", "none")
                    $("#crawlKeyword").attr("disabled", true)
                    $("#crawlSubmit").hide();
                }

                if(args['msg'] == "crawling"){
                    $("#dataCrawling").show();
                    crawlInterval(args['dataIdx']);
                }

                if (args['msg'] == "upload")
                    updateCrawlingResult(~~args["dataSize"])
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
        if(!confirm("수집된 데이터를 삭제하시겠습니까?"))
            return
        deleteData()
        // $("#deletePop").show();
    });

    // 데이터 업로드
    let uploading = false
    $("#uploadSubmit").click(function(e) {

        const userLevel = ~~("<?php echo $this->member->item('mem_level') ?>")
        if (userLevel === 1) {
            alert("학생 회원은 해당 기능을 이용할 수 없습니다.")
            return
        }

        if (userLevel === 0) {
            alert("로그인이 필요합니다.")
            const redirectUrl = encodeURIComponent(`${location.protocol}//${location.hostname}/analysis/data_manage`)
            location.href = `/login?url=${redirectUrl}`
            return
        }

        function uploadAlert(message) {
            alert(message);
            uploading = false
        }

        var dataType = $("#dataType").val();

        if (dataType == "crawl"){
            var dataIdx = $("#crawlIdx").val();
            var dataName = $("#crawlTitle").val();


            if(dataName == ""){
                uploadAlert("데이터 이름을 입력 해 주세요.");
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
                uploadAlert("파일을 선택 해 주세요.");
                return false;
            }

            for(var i = 0; i < uploadFile.length; i++){
                var filename = $(uploadFile[i]).val();
                if(filename === '')
                    continue
                var fileExt = filename.split(".")[1];
                if(fileExt != "xls" && fileExt != "xlsx" && fileExt != "txt"){
                    uploadAlert("지원하지않는 파일형식입니다.");
                    return false;
                }
            }

            if(dataName == ""){
                uploadAlert("데이터 이름을 입력 해 주세요.");
                return false;
            }

            if(!confirm("선택한 파일을 업로드 하시겠습니까?"))
                return

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
                console.log(args.data)
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