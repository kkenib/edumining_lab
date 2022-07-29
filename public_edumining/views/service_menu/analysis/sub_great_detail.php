<section class="visualarea sub">
    <div class="wrapper">
        <div class="subvisual_bg notice">
            <div class="subvisual_box">
                <h2 class="bold">보고서 <b>뽐내기</b></h2>
                <p>
                    텍스트 마이닝 결과로 작성한 보고서 중 <br>우수작품을 확인하세요.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- view -->
<section class="mt80 pb80">
    <div class="wrapper">
        <div class="grey_contbox brd_detail">
            <h3 id="title" class="mb10"></h3>
            <p id="class_info" class="mb10"></p>
            <p id="create_date"><time datetime="2021-12-14"></time></p>
            <div id="unit_list" class="wh_box mt20">
                <!--				<div class="grapharea">-->
                <!--					<h4>시각화 제목</h4>-->
                <!--					<div class="wrap_graph">-->
                <!--						<img src="/views/_layout/analysis/images/vis_img01_report.png">-->
                <!--					</div>-->
                <!--				</div>-->
                <!--				<div class="grapharea">-->
                <!--					<h4>시각화 제목</h4>-->
                <!--					<div class="wrap_graph">-->
                <!--						<img src="/views/_layout/analysis/images/vis_img01_report.png">-->
                <!--					</div>-->
                <!--				</div>-->
                <!--				<p class="box_analtxt">-->
                <!--					textarea에서 작성한 분석글 내용이 들어갑니다.<br>-->
                <!--					이름을 걱정도 옥 프랑시스 이름을 버리었습니다. 내 밤이 멀리 이런 남은 사람들의 봅니다. 다하지 잔디가 별들을 별 노루, 까닭입니다.<br>-->
                <!--					강아지, 아침이 가을로 헤는 별 부끄러운 불러 하나에 버리었습니다.-->
                <!--				</p>-->
            </div>
        </div>
        <div class="tac mt40">
            <a href="/analysis/sub_great" class="btn grey round lg mr15">목록보기</a>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function() {
        viewReport()
        function viewReport() {
            $.ajax({
                type: "POST",
                url: "/edumining/project_management/viewReport",
                data: {
                    report_no: localStorage.getItem("report_no"),
                },
                dataType: "JSON",
                success: function(args) {
                    
                    if (args['msg'] === 'success') {
                        const title = args["title"]
                        const userId = args["user_id"]
                        const createDate = args["create_date"]
                        const classInfo = args["class_info"]


                        // console.log(title + '/' + userId + '/' + createDate)
                        $("#title").text(title)
                        $("#class_info").text(`${classInfo} / ${userId}`)
                        $("#create_date").text(createDate)

                        const list = args["list"]
                        console.log(list)
                        for (let i=0; i<list.length; i++) {
                            const index = i + 1
                            const item = list[i]
                            const unitType = Number(item["unit_type"])
                            const contents = item["contents"]
                            const analType = Number(item["anal_type"])

                            let html = ''
                            if (unitType === 0) {   // 텍스트 타입.
                                html = `<p class="box_analtxt">${contents}</p>`
                            } else if (unitType === 1) {    // 시각화 타입.
                                const height = (analType === 3) ? "350px" : "300px"
                                html = `<div class="grapharea">
                                           <h4>${contents}</h4>
                                             <div class="wrap_graph">
                                               <div class="tac" id="graph${index}" style="height: ${height}"></div>
                                          </div>
                                        </div>`
                            }
                            $("#unit_list").append(html)
                            if (unitType === 1) {
                                loadChart(item, index)
                            }
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            })
        }

        function loadChart(item, index) {
            let data = item["data"]["data_list"]
            if (data === false) return
            data = data.trim().split("\n");

            let colorText = []
            let colorBg = []
            if(item["visual_text_color"]){
                colorText = item["visual_text_color"];
                colorText = colorText.trim().split(',');
            }
            if(item["visual_bg_color"]){
                colorBg = item["visual_bg_color"];
                colorBg = colorBg.trim().split(',');
            }

            let dataSet = []
            const graphId = "graph" + index
            const analType = Number(item["anal_type"])
            if ( analType === 0) {
                $.each(data, function (i, v) {
                    v = v.split(',');
                    dataSet.push({"word": v[0], "count": v[1]})
                })
                if(colorText.length > 0){
                    dataSet = change_color_wordcloud(dataSet, colorText[0], colorText[1], colorText[2])
                }
                make_wordcloud(graphId, dataSet)
            }
            else if (analType === 1) {
                let children = []
                const centerWord = item["center_word"]
                if (data.length > 0) {
                    $.each(data, function(i, v) {
                        v = v.split(',');
                        children.push({"word": v[0], "count": v[1]})
                    })
                    let dataSet = [{
                        "word": centerWord,
                        "children": children
                    }]

                    if(colorText.length > 0){
                        dataSet = change_color_egoNetwork(dataSet,
                            colorBg[0], colorBg[1], colorBg[2],
                            colorText[0], colorText[1], colorText[2])
                    }
                    make_egoNetworad(graphId, dataSet)
                }
            }

            else if (analType === 2){
                let tmp_data = {};
                let total_dict = {};

                // console.log(data)
                let tmp = data
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
                    
                $.each(data, function(i, v){
                    v = v.split(',');
                    const word1 = v[0];
                    const word2 = v[1];

                    total_dict[word1] = ( word1 in total_dict) ? ( total_dict[word1] + 1 ) : 0
                    total_dict[word2] = ( word2 in total_dict) ? ( total_dict[word2] + 1 ) : 0
                });

                // 부모리스트
                let parents_dict = [];
                for (let k in total_dict) {

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

                for (let i in tmp_data) {
                    dataSet.push(tmp_data[i]);
                }

                if(colorText.length > 0){
                    dataSet = change_color_netword(dataSet, colorBg, colorText);
                }
                make_networkChart(graphId, dataSet);
            }

            else if (analType === 3){
                let keyword_list = [];
                let tmp_array = {};
                let keyword = data[0].split(',')
                let data_tmp = []
                for (let i = 1; i < data.length ;i++ ){
                    tmp_array = {};
                    data_tmp = data[i].split(',')
                    for (let j = 0; j <keyword.length ;j++ ){
                        tmp_array[keyword[j]] = data_tmp[j];
                    }
                    dataSet.push(tmp_array);
                }
                $.each(keyword,function(idx, v){
                    if (idx > 0)
                        keyword_list.push(v);
                });
                make_lineChart(graphId, dataSet, keyword_list );
            }

            else if (analType === 4){
                var positive_chart = []
                var negative_chart = []
                $.each(data, function(i, v){
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

                if(colorText.length > 0){
                    dataSet = change_color_wordcloud_binary(positive_chart, negative_chart, colorText[0], colorText[1]);
                }
                make_wordcloud(graphId, dataSet);
            }
        }
    })
</script>




