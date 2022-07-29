<section class="visualarea sub">
    <div class="wrapper">
        <div class="subvisual_bg report">
            <div class="subvisual_box">
                <h2 class="bold">보고서 <b>작성</b></h2>
                <p>
                    시각화까지 완성된 분석 결과를 가지고<br>
                    한 편의 데이터 분석 보고서를 작성해 보세요.
                </p>
            </div>
        </div>
    </div>
</section>

<div class="wrapper">
    <section class="mt80">
        <div class="box_tit">
            <h2>데이터 시각화 목록</h2>
        </div>
        <div class="grey_contbox">

            <div class="vislist_area">
                <div class="vislist_scroll" id="vislist_scroll" >
                    <div class="vislist_wrap">
                        <!--
                        <div class="vis_box">
                            <span><input type="checkbox" id="chk3" name="vis_list_check"><label for="chk3"></label></span>
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
                        </div>

                        <div class="vis_box">
                            <span><input type="checkbox" id="chk4" name="vis_list_check"><label for="chk4"></label></span>
                            <div class="vgragh">
                                <img src="/views/_layout/analysis/images/vis_img02.png" alt="">
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
                        </div>

                        <div class="vis_box">
                            <span><input type="checkbox" id="chk5" name="vis_list_check"><label for="chk5"></label></span>
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
                        </div>

                        <div class="vis_box">
                            <span><input type="checkbox" id="chk6" name="vis_list_check"><label for="chk6"></label></span>
                            <div class="vgragh">
                                <img src="/views/_layout/analysis/images/vis_img02.png" alt="">
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
                        </div>

                        <div class="vis_box">
                            <span><input type="checkbox" id="chk7" name="vis_list_check"><label for="chk7"></label></span>
                            <div class="vgragh"></div>
                            <dl>
                                <dt></dt>
                                <dd></dd>
                                <dt></dt>
                                <dd></dd>
                            </dl>
                            <div class="overh mt10">
                                <button class="btn lightgrey">삭제</button>
                                <button class="floatr btn grey">다운로드</button>
                            </div>
                        </div>
                        -->
                    </div>
                </div>

            </div>
        </div>
    </section>

    <div class="tac mt20">
        <button class="mt12 btn navy lg round" id="add">추가하기</button>
    </div>

    <!--	<section class="mt80 pb80">-->
    <div class="mt80 pb80">
        <div class="box_tit">
            <h2>보고서 편집<em>분석한 결과로 보고서를 만들어 보세요.</em></h2>
            <div class="floatr">
                <!-- <button class="btn grey memo_add">글상자 추가</button> -->
                <button id="print_preview" class="btn lightgrey">PDF 미리보기</button>
            </div>
        </div>
        <div class="grey_contbox box_report wrap_cont day_edge" id="reportMainVisual">
           
            <div class="rpt_name mb5">
				<span class="wr_input">
					<input type="text" id="title" placeholder="보고서 제목을 입력해 주세요.">
					<a class="clearText"><i class="fas fa-times-circle"></i></a>
				</span>
            </div>
            <div id="rptObject">
                <!--
                            <section>
                            <div class="rpt_group">
                                <ul class="rpt_setl">
                                    <li title="상단으로 한칸 이동" class="up"><i class="fas fa-chevron-up"></i></li>
                                    <li title="하단으로 한칸 이동" class="down"><i class="fas fa-chevron-down"></i></li>
                                </ul>
                                <ul class="rpt_setr">
                                    <li title="페이지 나눔" class="page_break"><i class="fas fa-columns"></i></li>
                -->
                <!-- <li title="이미지 다운로드" class="graph_down"><i class="fas fa-download"></i></li> -->
                <!--
                                    <li title="삭제" class="delete"><i class="fas fa-trash-alt"></i></li>
                                </ul>
                                <div class="rpt_stit">
                                    <span class="wr_input">
                                        <input type="text" id="title_s" value="욕 좀 하는 이유나"  placeholder="제목을 입력해 주세요.">
                                        <a class="clearText"><i class="fas fa-times-circle"></i></a>
                                    </span>
                                </div>
                                <div class="rpt_body">
                                    <div class="tac" id="graph">
                -->
                <!--<img src="/views/_layout/analysis/images/vis_img01_report.png">-->
                <!--
                                    </div>
                                </div>
                            </div>
                            </section>
                            <section>
                            <div class="rpt_group">
                                <ul class="rpt_setl">
                                    <li title="상단으로 한칸 이동" class="up"><i class="fas fa-chevron-up"></i></li>
                                    <li title="하단으로 한칸 이동" class="down"><i class="fas fa-chevron-down"></i></li>
                                </ul>
                                <ul class="rpt_setr">
                                    <li title="삭제" class="delete"><i class="fas fa-trash-alt"></i></li>
                                </ul>

                                <div class="rpt_body">
                                    <textarea placeholder="내용을 입력하세요."></textarea>
                                </div>
                            </div>
                            </section>
                -->
                <!-- 데이터 끝-->
            </div>
            <div class="tac mt30" id="save_div">
				<button class="btn grey mid memo_add mr10">분석하기</button>
                <button class="btn mid blue" id="save">저장</button>
            </div>
        </div>

        <div class="tac mt40">
            <a href="/analysis/sub_report_list" class="btn grey round lg mr15">목록보기</a>
            <button type="button" class="btn navy round lg" id="new_rpt">새 보고서 만들기</button>
        </div>
    </div>
    <!--</section>-->
</div>


<script type="text/javascript">

    var selectedNo = new HashMap();
    var report_outerWidth = $("#reportMainVisual").outerWidth();
    var report_width = $("#reportMainVisual").width();
    var report_resizeOuterWidth = "974";	// 프린드 미리보기
    var report_resizeWidth = "880";			// 프린트 미리보기
    var report_printPreview = false;

    $(function(){

        reportMainVisualHandler(); // 보고서 기능 조작하기

        get_visual_list(1); // 데이터 시각화 목록 가져오기

        if($.urlParam('report_no') != ""){
            loadReport($.urlParam('report_no'));
        }

        $('#add').click(function() {
            report_printPreview = false;
            var checkLength = 0;


            const keys = selectedNo.getKeys()
            for (let i=(keys.length-1); i>=0; i--) {
                const no = keys[i]
                var checked = selectedNo.get(no);
                if(checked){
                    checkLength++;
                    loadReportObject(no);
                }
            }
            // $(selectedNo.getKeys()).each(function(i, no){
            //     var checked = selectedNo.get(no);
            //     if(checked){
            //         checkLength++;
            //         loadReportObject(no);
            //     }
            // });

            $('input[name="vis_list_check"]').each(function(idx){
                var no = $("#chk"+idx).val();
                $("input:checkbox[id=chk"+ idx +"]").prop('checked',false);
                selectedNo.put(no, false);
            });
            if(checkLength == 0)
                alert("선택된 그래프가 없습니다.");
        });

        $('.memo_add').click(function(){
            var objectIdx = $('#rptObject > section').length;
            var html = '';
            html += '<section data-style="0" data-order="'+objectIdx+'">';
            html += '<div class="rpt_group">';
            html +=	'<ul class="rpt_setl">';
            html += '<li title="상단으로 한칸 이동" class="up">';
            html += '<i class="fas fa-chevron-up"></i></li>';
            html += '<li title="하단으로 한칸 이동" class="down">';
            html += '<i class="fas fa-chevron-down"></i></li></ul>'
            html += '<ul class="rpt_setr">';
            html += '<li title="삭제" class="delete">';
            html += '<i class="fas fa-trash-alt"></i></li></ul>';
            html += '<div class="rpt_body">';
            html += '<textarea id="title_s_'+objectIdx+'" placeholder="내용을 입력하세요." style="height:100%; width:100%; resize: none; font-family: \'Nanum Gothic\'"></textarea></div></div></section>';

            $("#rptObject").append(html);
        });

        $('#save').click(function(){
            insertOrUpdateReport();
        });

        $('#new_rpt').click(function(){
            if(confirm("작성 중인 보고서가 있습니다. 신규 보고서 생성 시 작성 중인 보고서는 삭제됩니다.")){
                location.href='./sub_report';
            }
        });
        $('#print_preview').click(function(){
            if(report_printPreview)	return;

            report_graphCount = $('#rptObject > section').length;

            if((report_graphCount) == 0) {
                alert("시각화를 선택해 주세요.");
                report_resizeCount = 0;
                return;
            }

            alert("현재 보고서 사이즈를 PDF/인쇄 크기에 맞게 조정합니다.\n사이즈 조정이 완료되면 미리보기 화면이 보여집니다.");

            // resize - 그래프 재로딩
            report_printPreview = true;
            var resizeWidth = {"outerWidth": report_resizeOuterWidth, "width": report_resizeWidth};

            report_resizing(resizeWidth);

        });

        $(document).on("click",".vislist_wrap > div > div > span > input:checkbox",function(){
            var no = $(this).val();
            var checked = $(this).is(":checked");
            selectedNo.put(no, checked);
        });

        var reportResize = setInterval(function() {
            // 보고서 resize 완료 후 미리보기 실행
            if(report_printPreview) {
                if(report_graphCount > 0) {
                    setTimeout(function(){
                        report_print_preview();
                        report_graphCount = 0;
                        report_printPreview = false;

                        // 보고서 사이즈 원복
                        var resizeWidth = {"outerWidth": report_outerWidth , "width": report_width};
                        report_resizing(resizeWidth);
                    }, 500);
                }
            }
        }, 1500);

    });


    // 현재 보고서 resize 함수
    function report_resizing(resizeWidth) {
        (resizeWidth["width"] == report_width)? $("#reportMainVisual").removeClass("wid900") : $("#reportMainVisual").addClass("wid900");

        $("#reportMainVisual").outerWidth(resizeWidth["outerWidth"]);
        $("#reportMainVisual").width(resizeWidth["width"]);
    }

    //차트 불러오기
    function loadChart(no,objectIdx){
        var url = "/edumining/analysis/getChartData";
        var param = {'no' : no};
        $.ajax({
            type : "post",
            url : url,
            data : param,
            dataType : "json",
            async: false,
            success : function(json){
                const dataList = json.data_list
                const chartInfo = json.chart_info

                var data_list = [];
                var anal_type = chartInfo['anal_type'];
                var str = dataList;
                str = str.trim().split("\n");
                if(chartInfo['visual_text_color']){
                    var color_text = chartInfo['visual_text_color'];
                    color_text = color_text.trim().split(",");
                }
                if(chartInfo['visual_bg_color']){
                    var color_bg = chartInfo['visual_bg_color'];
                    color_bg = color_bg.trim().split(",");
                }

                //분석(시각화) 유형 / 0: 빈도분석, 1: 연관어분석, 2: 연결망분석, 3: 추이분석 4: 감성분석
                if (anal_type == 0){
                    $.each(str, function(i, v){
                        v = v.split(',');
                        data_list.push({'word': v[0], 'count': v[1]});
                    });
                    if(color_text){
                        data_list = change_color_wordcloud(data_list,color_text[0],color_text[1],color_text[2]);
                    }
                    make_wordcloud("graph"+objectIdx, data_list);
                }

                else if (anal_type == 1){
                    var cnt_word = chartInfo['center_word'];
                    var children = [];
                    var tmp = {}
                    $.each(str, function(i, v){
                        v = v.split(',');
                        children.push({'word': v[0], 'count': v[1]});
                    });

                    data_list =  [{
                        "word": cnt_word,
                        "children": children
                    }];

                    if(color_text){
                        data_list = change_color_egoNetwork(data_list, color_bg[0], color_bg[1], color_bg[2], color_text[0], color_text[1], color_text[2]);
                    }
                    make_egoNetworad("graph"+objectIdx, data_list);
                }

                else if (anal_type == 2){
                    var tmp_data = {};
                    var total_dict = {};

                    let tmp = dataList.split('\n') 
                    const chart_dict = []
                    for(let i=0; i<tmp.length; i++) {
                        const tmpStr = tmp[i]
                        if(tmpStr === '') continue

                        const wordCountArr = tmp[i].split(',')
                        chart_dict.push({
                            word : wordCountArr.length > 0 ? wordCountArr[0] : '',
                            word2: wordCountArr.length > 1 ? wordCountArr[1] : '',
                            count: ~~(wordCountArr.length > 2 ? wordCountArr[2] : 0)
                        })
                    }

                    $.each(str, function(i, v){
                        v = v.split(',');
                        var word1 = v[0];
                        total_dict[word1] = ( word1 in total_dict) ? ( total_dict[word1] + 1 ) : 0

                        var word2 = v[1];
                        total_dict[word2] = ( word2 in total_dict) ? ( total_dict[word2] + 1 ) : 0
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
                        data_list.push(tmp_data[i]);
                    };

                    if(color_text){
                        console.log("color_bg: " + color_bg)
                        console.log("color_text: " + color_text)
                        data_list = change_color_netword(data_list, color_bg, color_text);
                    }

                    make_networkChart("graph"+objectIdx, data_list);
                }

                else if (anal_type == 3){
                    
                    var keyword_list = [];
                    var tmp_array = {};
                    var keyword = str[0].split(',')
                    var str_tmp = []
                    for (var i = 1; i < str.length ;i++ ){
                        tmp_array = {};
                        str_tmp = str[i].split(',')
                        for (var j = 0; j <keyword.length ;j++ ){
                            tmp_array[keyword[j]] = str_tmp[j];
                        }
                        data_list.push(tmp_array);
                    }
                    $.each(keyword,function(idx, v){
                        if (idx > 0)
                            keyword_list.push(v);
                    });
                    make_lineChart("graph"+objectIdx, data_list, keyword_list );
                }

                else if (anal_type == 4){
                    var positive_chart = []
                    var negative_chart = []
                    $.each(str, function(i, v){
                        v = v.split(',');
                        var word = v[0]
                        var count = v[1]
                        var positive = Number(v[2])
                        var obj = {"word": v[0], "count": v[1]}
                        if (positive === 1)
                            positive_chart.push(obj)
                        else
                            negative_chart.push(obj)
                    })

                    if(color_text){
                        data_list = change_color_wordcloud_binary(positive_chart, negative_chart, color_text[0], color_text[1]);
                    }
                    make_wordcloud("graph"+objectIdx, data_list);
                }

            }
        });
    }

    //보고서 불러오기
    function loadReport(no){
        var url = "/edumining/Analysis/getReport";
        var param = {'no' : no};

        $.ajax({
            type : "POST",
            url : url,
            data : param,
            dataType : "json",
            success : function(json){
                lists = json.data;
                $("#title").val(lists[0].title);
                $("#rptObject").html("");
                $.each(lists, function(i, v){
                    var html ='';
                    var objectIdx = $('#rptObject > section').length;
                    if (v.unit_type == 0)
                    {
                        html += '<section data-style="0" data-order="'+objectIdx+'">';
                        html += '<div class="rpt_group">';
                        html += '<ul class="rpt_setl">';
                        html += '<li title="상단으로 한칸 이동" class="up"><i class="fas fa-chevron-up"></i></li>';
                        html += '<li title="하단으로 한칸 이동" class="down"><i class="fas fa-chevron-down"></i></li></ul>';
                        html += '<ul class="rpt_setr">';
                        html += '<li title="삭제" class="delete"><i class="fas fa-trash-alt"></i></li></ul>';
                        html += '<div class="rpt_body">';
                        html += '<textarea id="title_s_'+objectIdx+'" placeholder="내용을 입력하세요." style="height:100%; width:100%; resize: none;">'+v.contents+'</textarea>';
                        html += '</div></div></section>';

                        $("#rptObject").append(html);
                    }
                    else{
                        html += '<section id="section_'+v.artifact_no+'" data-no="'+v.artifact_no+'" data-order="'+objectIdx+'" data-style="1">';
                        html += '<div class="rpt_group">';
                        html += '<ul class="rpt_setl">';
                        html += '<li title="상단으로 한칸 이동" class="up"><i class="fas fa-chevron-up"></i></li>';
                        html += '<li title="하단으로 한칸 이동" class="down"><i class="fas fa-chevron-down"></i></li></ul>';
                        html += '<ul class="rpt_setr" data-graph="'+v.artifact_no+'">';
                        html += '<li title="페이지 나눔" class="page_break"><i class="fas fa-columns"></i></li>';
                        html += '<li title="삭제" class="delete"><i class="fas fa-trash-alt"></i></li></ul>';
                        html += '<div class="rpt_stit">';
                        html += '<span class="wr_input">';
                        html += '<input type="text" id="title_s_'+objectIdx+'" value="'+v.contents+'"  placeholder="제목을 입력해 주세요.">';
                        html += '<a class="clearText"><i class="fas fa-times-circle"></i></a></span></div>';
                        html += '<div class="rpt_body">';
                        html += '<div class="tac" id="graph'+objectIdx+'"></div></div></div></section>';

                        $("#rptObject").append(html);
                        loadChart(v.artifact_no,objectIdx);
                    }

                });
            }
        });
    }

    //시각화 불러오기
    function loadReportObject(idx){
        var url = "/edumining/Analysis/getReportObject";
        var param = {'no' : idx};

        $.ajax({
            type : "POST",
            url : url,
            data : param,
            dataType : "json",
            async:false, 
            success : function(json){

                console.log("json")
                console.log(json)
                var html = '';
                var objectIdx = $('#rptObject > section').length;
                
                html += '<section id="section_'+json.data.no+'" data-no="'+json.data.no+'" data-order="'+objectIdx+'" data-style="1">';
                html += '<div class="rpt_group">';
                html += '<ul class="rpt_setl">';
                html += '<li title="상단으로 한칸 이동" class="up"><i class="fas fa-chevron-up"></i></li>';
                html += '<li title="하단으로 한칸 이동" class="down"><i class="fas fa-chevron-down"></i></li></ul>'
                html += '<ul class="rpt_setr" data-graph="'+json.data.no+'">';
                html += '<li title="페이지 나눔" class="page_break"><i class="fas fa-columns"></i></li>';
                html += '<li title="삭제" class="delete"><i class="fas fa-trash-alt"></i></li></ul>';
                html += '<div class="rpt_stit">';
                html += '<span class="wr_input">';
                html += '<input type="text" id="title_s_'+objectIdx+'" value="' + json.data.title + '"  placeholder="제목을 입력해 주세요.">';
                html += '<a class="clearText"><i class="fas fa-times-circle"></i></a></span></div>';
                html += '<div class="rpt_body">';
                html += '<div class="tac" id="graph'+objectIdx+'"></div></div></div></section>';

                $("#rptObject").append(html);
                loadChart(idx,objectIdx);
                
            }
        });
    }


    //그래프 기능
    function reportMainVisualHandler(){
        $(document).on("click", ".clearText", function() {
            var id = $(this).prev().attr("id");
            $("#" + id).val("");
        });

        $(document).on("click",".rpt_setl > li ", function(){
            var rpt_setl = $('.rpt_setl');
            var className = $(this).attr('class');
            var index = rpt_setl.index($(this).parent('.rpt_setl'));
            var max_length = $('section').length;
            var section;
            if(className == 'up') {
                if(index == 0){
                    alert("첫번째 보고서입니다.");
                    return false;
                }
                $(".pagebreak").remove();
                if(index > 0) {
                    section = $('section').eq(index+2);
                    section.after(section.prev());
                }
            }else {
                if((max_length - index) == 3) {
                    alert("마지막 보고서 입니다.");
                    return false;
                }

                $(".pagebreak").remove();
                if(index >= 0 && index < max_length) {
                    section = $('section').eq(index+2);
                    section.before(section.next());
                }
            }
            pagebreak_reset();

            return false;
        });

        $(document).on("click", ".rpt_setr > li", function(){
            var rpt_setr = $('.rpt_setr');
            var className = $(this).attr('class');
            var index = rpt_setr.index($(this).parent('.rpt_setr'));

            // 페이지 나눔
            if(className == 'page_break' && index >= 0) {
                var graphId = $(this).parent().data("graph");
                if($("#section_" + graphId).hasClass("print_page_break")) {
                    $(this).attr("title", "페이지 나눔");
                    $("#section_" + graphId).removeClass("print_page_break");
                } else {
                    $(this).attr("title", "페이지 나눔 제거");
                    $("#section_" + graphId).addClass("print_page_break");
                }

                pagebreak_reset();

                return false;
            }


            // 시각화그래프 보고서 컨텐츠 삭제
            if(className == 'delete' && index >= 0) {
                var remove_alert = confirm("삭제 하시겠습니까?");
                if(remove_alert) {
                    $('section').eq(index+2).remove();
                    pagebreak_reset();
                    return;
                }
            }
        });
    }

    function pagebreak_reset() {
        $(".pagebreak").remove();
        $(".print_page_break").before('<span class="pagebreak"></span>');
    }
    //보고서 작성 및 수정
    function insertOrUpdateReport(){
        var now = new Date(+new Date() + 3240 * 10000).toISOString().replace("T", " ").replace(/\..*/, '');
        var have_no = $.urlParam('report_no');
        var url = "/edumining/Analysis/insertOrUpdateReport";
        var idx = 0;
        var contents = [];
        var artifact_no = [];
        var unit_type = [];
        var unit_order = [];
        var title = $('#title').val();
        if(!title){
            alert('보고서 제목을 입력해 주세요');
            return;
        }

        $('#rptObject > section').each(function(){
            var no = $(this).data('no');
            var objectIdx = $(this).data('order');
            var style = $(this).data('style');
            unit_type[idx] = style;
            contents[idx] = $("#title_s_"+objectIdx).val();
            if (style == 0)
                artifact_no[idx] = "0";
            else
                artifact_no[idx] = no;
            unit_order[idx] = idx + 1;
            idx++;
        });

        if(!unit_order.length){
            alert('내용을 입력해 주세요');
            return;
        }

        var param = { date:now, have_no:have_no, title:title, artifact_no : artifact_no, contents : contents, unit_type : unit_type, unit_order : unit_order };
        $.ajax({
            type : "POST",
            url : url,
            data : param,
            dataType : "json",
        }).success(function(result){
            alert(result.data.result);
        }).done(function(result){
            location.href = './sub_report_list';
        });
    }
    /*
    function callprint(id){
         var inbody = document.body.innerHTML;

         window.onbeforeprint = function(){
            document.body.innerHTML = document.getElementById(id).innerHTML;
         }
         window.onafterprint = function(){
            window.location.reload();
         }
         window.print();
    }
    */
    function report_print_preview() {

        $("body").addClass("print_preview").append("<div class=\"backdrop\"></div>");
        create_download_document();

        $("<div class=\"print_preview_action\"><a href=\"#\" class=\"btn_basic btn_print_start\"><i class=\"fa fa-print\"></i> PDF/인쇄</a> <a href=\"#\" class=\"btn_basic btn_print_close\"><i class=\"fa fa-close\"></i> 닫기</a></div>")
            .appendTo("#downloadPDFHiddenDiv")
            .clone()
            .prependTo("#downloadPDFHiddenDiv");

        $(".print_preview_action > .btn_print_start").click(function() {
            $(".print_preview_action").remove();
            //callprint('docDownCopy0');
            window.print();
            clear_download_document();
            return false;
        });

        $(".print_preview_action > .btn_print_close").click(function() {
            clear_download_document();
            return false;
        });
    }

    function clear_download_document() {
        $("#downloadPDFHiddenDiv").remove();
        $(".print_preview .backdrop").remove();
        $("body").removeClass("print_preview");
    }

    function create_download_document() {
        $("#downloadPDFHiddenDiv").remove();

        $("<div></div>").attr("id", "downloadPDFHiddenDiv").appendTo(document.body);
        $("<div></div>").attr("id", "downloadPDFHiddenTempDiv").appendTo(document.body);
        var objectIdx = $('#rptObject > section').length;
        var targetId = "#reportMainVisual";
        var	fileTitle = $("#title").val();
        var fileTitle_s = []
        for (var i = 0; i < objectIdx ; i++ )
        {
            fileTitle_s[i] = $("#title_s_"+i).val();

        }
        var	width = $(targetId).outerWidth();
        var height = $(targetId).outerHeight();
        var copyId = "docDownCopy";

        // HTML 카피
        var copyDiv = $("<div></div>").attr("id", copyId + "0")
            .width(width)
            .attr("class", $(targetId).attr("class"))
            .css("border","0px")
            .appendTo("#downloadPDFHiddenDiv");

        copyDiv.html($(targetId).html());
        // HTML 요소에 따라 변경 필요
        copyDiv.find("#title").attr("placeholder", fileTitle);
        for (var i = 0; i < objectIdx ; i++ )
        {
            copyDiv.find("#title_s_"+i).attr("value",null);
            copyDiv.find("#title_s_"+i).attr("placeholder", fileTitle_s[i]);
        }
        copyDiv.find(".clearText").remove();
        copyDiv.find(".refresh").remove();
        copyDiv.find("input").attr("disabled", true);
        copyDiv.find("textarea").attr("disabled", true);
        copyDiv.find("textarea").css("background-color","#fff");
        //copyDiv.find("textarea").css("border","1px solid #ccc");
        copyDiv.find(".rpt_body").css("border","2px solid #ccc");
        copyDiv.find(".rpt_stit").css("border","1px solid #ccc");
        copyDiv.find(".rpt_setl").remove();
        copyDiv.find(".rpt_setr").remove();
        copyDiv.find("form").remove();
        copyDiv.find("#save").remove();
        copyDiv.find("input").css("border","0px");
        copyDiv.find("#title").css("border","1px solid #000");

        // hover 효과 제거
        $("#downloadPDFHiddenDiv .rpt_group").hover(function(){
            $(this).css("border", "1px solid #ddd");
            $(this).css("margin", "10");
        });


        // SVG 대체
        $("#downloadPDFHiddenDiv").find("._g").each(function() {
            var xid = $(this).attr("id") + "_copy";
            $(this).attr("id", xid);
            svg_to_image(xid);
        });

        return false;
    }
</script>