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


<section class="mt80 pb80">
    <div class="wrapper">

		<div class="grey_contbox great mb20 tar">
			<div class="floatl pt6">
				전체 <strong class="pink fs18" id="total_cnt">0</strong>건
			</div>
			<div class="search_wrap w300">
				<a class="search"><i class="fas fa-search"></i></a>
				<input type="text" placeholder="제목을 입력하세요." id="search_keyword">
			</div>
		</div>

        <div id="report_list" class="list_g01">
            <!--			<a href="/analysis/sub_great_detail">-->
            <!--				<img src="/views/_layout/analysis/images/notice_1.png" alt="">-->
            <!--				<ul>-->
            <!--					<li>1학년 2반 1학기 / abc_01</li>-->
            <!--					<li>보고서 제목</li>-->
            <!--					<li><time datetime="2021-12-14">2021.12.14</time></li>-->
            <!--				</ul>-->
            <!--			</a>-->
            <!--			<a href="/analysis/sub_great_detail">-->
            <!--				<div>-->
            <!--					<img src="/views/_layout/analysis/images/notice_2.png" alt="">-->
            <!--				</div>-->
            <!--				<ul>-->
            <!--					<li>수업명 / 학생아이디</li>-->
            <!--					<li>우리 동네에 혹등고래가 산다우리 동네에 혹등고래가 산다우리 동네에 혹등고래가 산다</li>-->
            <!--					<li><time datetime="2021-12-11">2021.12.11</time></li>-->
            <!--				</ul>-->
            <!--			</a>-->
            <!--			<a href="/analysis/sub_great_detail">-->
            <!--				<div class="glist_img">-->
            <!--					<img src="/views/_layout/analysis/images/notice_3.png" alt="">-->
            <!--				</div>-->
            <!--				<ul>-->
            <!--					<li>수업명 / 학생아이디</li>-->
            <!--					<li>이상한 나라의 앨리스</li>-->
            <!--					<li><time datetime="2021-12-10">2021.12.10</time></li>-->
            <!--				</ul>-->
            <!--			</a>-->
            <!--			<a href="/analysis/sub_great_detail">-->
            <!--				<div>-->
            <!--					<img src="/views/_layout/analysis/images/notice_2.png" alt="">-->
            <!--				</div>-->
            <!--				<ul>-->
            <!--					<li>수업명 / 학생아이디</li>-->
            <!--					<li>우리 동네에 혹등고래가 산다</li>-->
            <!--					<li><time datetime="2021-12-11">2021.12.11</time></li>-->
            <!--				</ul>-->
            <!--			</a>-->
            <!--			<a href="/analysis/sub_great_detail">-->
            <!--				<div class="glist_img">-->
            <!--					<img src="/views/_layout/analysis/images/notice_3.png" alt="">-->
            <!--				</div>-->
            <!--				<ul>-->
            <!--					<li>수업명 / 학생아이디</li>-->
            <!--					<li>이상한 나라의 앨리스</li>-->
            <!--					<li><time datetime="2021-12-10">2021.12.10</time></li>-->
            <!--				</ul>-->
            <!--			</a>-->
        </div>

        <div class="paging mt40">
            <div class="wr_page">
                <button type="button" class="first mr5" onclick="moveFirstPage()" title="처음 페이지 이동">
                    <i class="fas fa-angle-double-left"></i><em>처음 페이지 이동</em>
                </button>
                <button type="button" class="pre" onclick="movePrevPage()" title="이전 페이지 이동">
                    <i class="fas fa-angle-left"></i><em>이전 페이지 이동</em>
                </button>
                <span id="page_navigation" class="num"></span>
                <button type="button" class="next mr5" onclick="moveNextPage()" title="다음 페이지 이동">
                    <i class="fas fa-angle-right"></i><em>다음 페이지 이동</em>
                </button>
                <button type="button" class="last" onclick="moveLastPage()" title="끝 페이지 이동">
                    <i class="fas fa-angle-double-right"></i><em>끝 페이지 이동</em>
                </button>
            </div>
        </div>

        <!--		<div class="tac mt40">-->
        <!--			<a href="#" class="btn navy round lg" target="_blank" title="[새창]">보고서 선정하기</a>-->
        <!--		</div>-->
    </div>
</section>

<script type="text/javascript">

    let pageNo = 1
    let lastPageNo = 1
    let searchedKeyword = ''

    $(function() {
        getGreatList();
        $('.search').click(function() {
            pageNo = 1
            searchedKeyword = $("#search_keyword").val()
            getGreatList()
        })
    })

    function getGreatList() {
        $.ajax({
            url: '/edumining/analysis/getGreatList',
            method: 'POST',
            data: {
                search_keyword: searchedKeyword,
                page_no: pageNo
            },
            dataType: 'JSON',
            success : function(args){
                if (args["msg"] === "success") {
                    const items = args['list']
                    const totalItemCount = args['total_item_count']
                    $("#total_cnt").text(totalItemCount)
                    $("#report_list").html('')
                    for (let i = 0; i < items.length; i++) {
                        const item = items[i]
                        const reportNo = item["report_no"]
                        const userNo = item["user_no"]
                        const lectureNo = item["lecture_no"]
                        const title = item["title"]
                        let createDate = item["create_date"].split(' ')[0]
                        // if (createDate !== '' && createDate !== undefined && createDate !== null) {
                        //     createDate = createDate.split(' ')
                        //     createDate = (createDate.length > 0) ? createDate[0] : createDate
                        // }
                        const updateDate = item["update_date"]
                        const overview = item["lecture_overview"]
                        const id = item["user_id"]
                        // alert(totalItemCount)

                        const html = `<a onclick="viewReport(${reportNo})">
                                        <img src="/views/_layout/analysis/images/notice_1.png" alt="">
                                        <ul>
                                            <li>${overview} / ${id}</li>
                                            <li>${title}</li>
                                            <li><time datetime="${createDate}">${createDate}</time></li>
                                        </ul>
                                      </a>`

                        $("#report_list").append(html)
                    }

                    const currentPageNo = args["current_page_no"]
                    const totalPageCount = args["total_page_count"]
                    lastPageNo = totalPageCount

                    $("#page_navigation").html('')
                    for (let i = 0; i < totalPageCount; i++) {
                        const pageNo = i + 1
                        const html = `<a onclick="movePage(${pageNo})"
                                         style="${(pageNo === currentPageNo) ?
                            'font-weight: bold; color:#e84c88;' : ''}" >${pageNo}</a>`
                        $("#page_navigation").append(html)
                    }
                }

            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    }

    function viewReport(reportNo) {
        const memId = "<?php echo $this->member->item('mem_id'); ?>"
        if (memId === '') {
            alert("로그인이 필요합니다.");
            location.href = "/login";
            return
        }

        localStorage.setItem("report_no", reportNo)
        location.href="/analysis/sub_great_detail"
    }

    function refreshGreatListByPageNavigation() {
        $("#search_keyword").val(searchedKeyword)
        getGreatList()
    }

    function movePage(pno) {
        pageNo = pno
        refreshGreatListByPageNavigation()
    }

    function moveFirstPage() {
        pageNo = 1
        refreshGreatListByPageNavigation()
    }

    function moveLastPage() {
        pageNo = lastPageNo
        refreshGreatListByPageNavigation()
    }

    function movePrevPage() {
        pageNo = (pageNo > 1) ? (pageNo - 1) : 1
        refreshGreatListByPageNavigation()
    }

    function moveNextPage() {
        pageNo = (lastPageNo > pageNo) ? (pageNo + 1) : lastPageNo
        refreshGreatListByPageNavigation()
    }

</script>