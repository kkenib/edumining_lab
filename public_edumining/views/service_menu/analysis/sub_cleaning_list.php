<section class="visualarea sub">
    <div class="wrapper">
        <div class="subvisual_bg choice">
            <div class="subvisual_box">
                <h2 class="bold">데이터 <b>선택</b></h2>
                <p>
                    텍스트 마이닝의 시작은 내가 분석할<br>
                    텍스트 데이터를 선택하는 것부터 시작해요.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="choice_list mt80 pb80">
    <div class="wrapper">
        <div id="data_tab" class="tab02 tac mb50">
            <!--			<a href="#" class="on" data-idx="0">제공 데이터</a>-->
            <!--			<a href="#" data-idx="1">수집 데이터</a>-->
            <!--			<a href="#" data-idx="2">보유 데이터</a>-->
        </div>
        <div class="grey_contbox">
            <div class="tar mb15">
                <div class="search_wrap w300">
                    <input type="text" id="search_keyword" placeholder="데이터명을 입력하세요.">
                    <a id="search" title="검색" onclick="get_rawdata_list()"><i class="fas fa-search"></i></a>
                </div>
            </div>


            <div class="wh_box tac">
                <div class="tblscroll">
                    <table class="tbl02" summary="데이터 목록 입니다">
                        <!-- <caption>제공 데이터</caption> -->
                        <thead id="provided_list_header">
                        <tr>
                            <th class="w8p">NO</th>
                            <th>데이터명</th>
                            <th class="w10p">저자</th>
                            <th class="w10p">장르</th>
                            <th class="w10p">업로드 날짜</th>
                            <th class="w10p">글자 수</th>
                            <th class="w10p">챕터 수</th>
                            <th class="w20p">단계</th>
                        </tr>
                        </thead>
                        <thead id="searched_list_header" style="display: none">
                        <tr>

                        <!-- [데이터명], [키워드], [검색기간], [등록일], [단계], [비고] 컬럼으로 구성 -->
                            <th class="w8p">NO</th>
                            <th>데이터명</th>
                            <th class="w10p">키워드</th>
                            <th class="w10p">기간</th>
                            <th class="w10p">등록일</th>
                            <th class="w20p">단계</th>
                            <th id="search_etc_cell" class="w20p">비고</th>
                        </tr>
                        </thead>
                        <thead id="collected_list_header" style="display: none">
                        <tr>
                            <th class="w8p">NO</th>
                            <th>데이터명</th>
                            <th class="w10p">키워드</th>
                            <th class="w10p">출처</th>
                            <th class="w15p">수집기간</th>
                            <th class="w10p">수집날짜</th>
                            <th class="w8p">수집상태</th>
                            <th class="w15p">단계</th>
                            <th id="collect_etc_cell" class="w10p">비고</th>
                        </tr>
                        </thead>
                        <thead id="uploaded_list_header" style="display: none">
                        <tr>
                            <th class="w8p">NO</th>
                            <th>데이터명</th>
                            <th class="w15p">업로드 날짜</th>
                            <th class="w15p">용량</th>
                            <!--<th class="w10p">수집상태</th>-->
                            <th class="w15p">단계</th>
                            <th id="provide_etc_cell" class="w10p etc_cell">비고</th>
                        </tr>
                        </thead>
                        <tbody id="data_tbody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <input type="hidden" id="page_num" name="page_num" value="<?=$this->input->get('page') ?>">
        <div class="paging mt20" id="paging">

            <div class="wr_page">
                <button id="first_page" class="first" title="처음 페이지 이동"><i
                            class="fas fa-angle-double-left"></i><em>처음 페이지 이동</em></button>
                <button id="prev_page" class="pre" title="이전 페이지 이동"><i
                            class="fas fa-angle-left"></i><em>이전 페이지 이동</em></button>
                <span id="page_navigation" class="num"></span>
                <button id="next_page" class="next" title="다음 페이지 이동"><i
                            class="fas fa-angle-right"></i><em>다음 페이지 이동</em></button>
                <button id="last_page" class="last" title="끝 페이지 이동"><i
                            class="fas fa-angle-double-right"></i><em>끝 페이지 이동</em></button>
            </div>

        </div>
    </div>
</section>

<form action="" id="moveFrm" name="moveFrm">
    <input type="hidden" name="data_no" value="">
</form>

<script type="text/javascript">
    const userLevel = ~~("<? echo $this->member->item('mem_level'); ?>")
    const tabComponent = new DataTabComponent(userLevel)
    let tabType = localStorage.getItem("tab_type")
    tabType = ~~(tabType)
    tabComponent.setTabType(tabType)

    // document.getElementsByClassName("etc").style["display"] = "none"
    // document.getElementsByClassName("remove").style["display"] = "none"


    function DataTabComponent(userLevel) {
        this.userLevel = ~~(userLevel)
        this.tabType = 0
        this.searchedKeyword = ''
        this.pageNo = 1
        this.lastPageNo = 1

        this.clear = () => {
            this.tabType = 0
            this.searchedKeyword = ''
            this.pageNo = 1
        }
        this.setTabType = (tabType) => {
            if(this.userLevel < 1 && tabType > 0) {
                alert("로그인 후 사용 가능합니다.")
                return
            }

            this.clear()
            this.tabType = tabType
            this.updateTab()
            this.updateDataList.call(this, tabType, '', 1)
            localStorage.setItem("tab_type", tabType)
        }
        this.updateTab = () => {
            const tab = document.getElementById("data_tab")
            tab.innerHTML = `
                <a id="tab_item_0" class="tab_item${this.tabType === 0 ? " on" : ''}">제공 데이터</a>
                <a id="tab_item_3" class="tab_item${this.tabType === 3 ? " on" : ''}">검색 데이터</a>
                <a id="tab_item_1" class="tab_item${this.tabType === 1 ? " on" : ''}">수집 데이터</a>
                <a id="tab_item_2" class="tab_item${this.tabType === 2 ? " on" : ''}">보유 데이터</a>  `

            setDisplay("provided_list_header", this.tabType === 0 ? '' : "none")
            setDisplay("provide_etc_cell", this.userLevel < 2 ? "none" : '')
            
            setDisplay("searched_list_header", this.tabType === 3 ? '' : "none")
            setDisplay("search_etc_cell", this.userLevel < 2 ? "none" : '')

            setDisplay("collected_list_header", this.tabType === 1 ? '' : "none")
            setDisplay("collect_etc_cell", this.userLevel < 2 ? "none" : '')

            setDisplay("uploaded_list_header", this.tabType === 2 ? '' : "none")
            
            
            document.getElementById("search_keyword").value = ''
            addClickListener("search", () => {
                this.pageNo = 1
                this.searchedKeyword = document.getElementById("search_keyword").value
                this.updateDataList(this.tabType, this.searchedKeyword, this.pageNo)
            })

            const items = tab.getElementsByClassName("tab_item")
            for (let i = 0; i < items.length; i++) {
                const item = document.getElementById(`tab_item_${i}`)
                item.addEventListener("click", this.setTabType.bind(this, i))
            }

            addClickListener("first_page", this.moveFirstPage)
            addClickListener("prev_page", this.movePrevPage)
            addClickListener("next_page", this.moveNextPage)
            addClickListener("last_page", this.moveLastPage)
        }
        this.updateDataList = (dataType, searchKeyword, pageNo) => {


            // [2022.07.13] 소스 옮길 때 내비게이션 동작 안 하는 데 체크하기

            function PageNavigation() {
                let addedPageCount = 0
                this.src = ''
                this.addPage = (pageNo) => {
                    this.src += `<a id="move_page_${pageNo}">${pageNo}</a>`
                    addedPageCount = addedPageCount + 1
                }
                this.isFull = () => addedPageCount === 2
            }

            const result = getDataList(dataType, searchKeyword, pageNo)
            const { list, total_item_count: totalItemCount, current_page_no: currentPageNo, total_page_count: totalPageCount } = result
            const tBody = document.getElementById("data_tbody")

            if (~~(totalItemCount) === 0) {
                tBody.innerHTML = getNoDataTemplate(dataType, this.userLevel)
                const paging = document.getElementById("page_navigation")
                paging.innerHTML = `<strong>1</strong>`
            } else {
                let htmlSrc = ''
                let componentList = []
                this.lastPageNo = totalPageCount

                list.forEach((item) => {
                    item.user_level = this.userLevel
                    const component = getDataComponent(dataType, item)
                    componentList.push(component)
                    htmlSrc += component.template()
                })
                tBody.innerHTML = htmlSrc

                for (const component of componentList) {
                    const itemNo = component.item.no
                    addClickListener("clear_button_" + itemNo, action_form.bind(this, itemNo, "edit"))
                    addClickListener("analysis_button_" + itemNo, action_form.bind(this, itemNo, "analysis"))
                    addClickListener("remove_button_" + itemNo, removeData.bind(this, itemNo))
                }

                let centerPagingSrc = `<strong>${currentPageNo}</strong>`
                const leftPageNavi = new PageNavigation()
                for (let pageNo = currentPageNo - 2; pageNo < currentPageNo; pageNo++) {
                    if (pageNo < 1) continue
                    if (leftPageNavi.isFull()) break
                    leftPageNavi.addPage(pageNo)
                }

                const rightPageNavi = new PageNavigation()
                for (let pageNo = ~~(currentPageNo) + 1; pageNo <= ~~(totalPageCount); pageNo++) {
                    if (rightPageNavi.isFull()) break
                    rightPageNavi.addPage(pageNo)
                }

                const paging = document.getElementById("page_navigation")
                paging.innerHTML = leftPageNavi.src + centerPagingSrc + rightPageNavi.src

                for (let i = 0; i < totalPageCount; i++) {
                    const pageNo = i + 1
                    addClickListener(`move_page_${pageNo}`, this.movePage.bind(this, pageNo))
                }
            }
        }

        this.refreshListByPageNavigation = () => {
            const searchedKeyword = document.getElementById("search_keyword").value
            this.updateDataList(this.tabType, searchedKeyword, this.pageNo)
        }
        this.movePage = (pno) => {
            this.pageNo = pno
            this.refreshListByPageNavigation()
        }
        this.moveFirstPage = () => {
            this.pageNo = 1
            this.refreshListByPageNavigation()
        }
        this.moveLastPage = () => {
            this.pageNo = this.lastPageNo
            this.refreshListByPageNavigation()
        }
        this.movePrevPage = () => {
            this.pageNo = (this.pageNo > 1) ? (this.pageNo - 1) : 1
            this.refreshListByPageNavigation()
        }
        this.moveNextPage = () => {
            this.pageNo = (this.lastPageNo > this.pageNo) ? (this.pageNo + 1) : this.lastPageNo
            this.refreshListByPageNavigation()
        }

        function Req() {
            this.url = undefined
            this.data = {}
            this.method = "POSt"
            this.responseType = "json"
            this.requestHeader = "application/x-www-form-urlencoded"
            this.request = new XMLHttpRequest()
            this.getQueryString = () => {
                return Object.entries(this.data).map(e => e.join('=')).join('&')
            }
            this.async = () => {
                if(!this.request) {
                    console.error("Can't make request.")
                    return false
                }

                this.request.open(this.method, this.url, false)
                this.request.setRequestHeader("Content-Type", this.requestHeader)

                let result = undefined
                this.request.addEventListener("readystatechange", function (event) {
                    const { target } = event;
                    if (target.readyState === XMLHttpRequest.DONE) {
                        const { status } = target;
                        if (status === 0 || (status >= 200 && status < 400)) { // 요청이 정상적으로 처리 된 경우
                            result = target.response
                        }
                    }
                })
                this.request.send(this.getQueryString())
                return JSON.parse(result)
            }
        }
        function getDataList (dataType, searchKeyword, pageNo) {
            const req = new Req()
            req.url = "/edumining/analysis/get_cleaning_list"
            req.data = { data_type: dataType, search_keyword: searchKeyword, page_no: pageNo }
            return req.async()
        }
        function removeData(dataNo) {
            if(confirm("선택한 데이터를 삭제하시겠습니까?")) {
                const req = new Req()
                req.url = "/edumining/analysis/removeRawData"
                req.data = {data_no: dataNo}
                const result = req.async()
                if(result.msg === "success") {
                    tabComponent.setTabType(this.tabType)
                } else {
                    alert("데이터를 삭제할 수 없습니다.")
                }
            }
        }
        function getNoDataTemplate(dataType, userLevel) {
            switch (dataType) {
                case 0:
                case 3:
                    return `<td class="list_none" colspan="${userLevel < 2 ? 6 : 7}">목록이 없습니다.</td>`
                case 1:
                    return `<td class="list_none" colspan="${userLevel < 2 ? 8 : 9}">목록이 없습니다.</td>`
                case 2:
                    return `<td class="list_none" colspan="${userLevel < 2 ? 5 : 6}">목록이 없습니다.</td>`
                default:
                    return ''
            }
        }
        function getDataComponent(dataType, item) {
            if(dataType === 0) return new ProvidedDataComponent(item)
            if(dataType === 3) return new SearchedDataComponent(item)
            if(dataType === 1) return new CollectedDataComponent(item)
            if(dataType === 2) return new UploadedDataComponent(item)
        }
        function addClickListener (domId, action) {
            const dom = document.getElementById(domId)
            if(dom !== null)
                dom.addEventListener("click", action)
        }
        function setDisplay(domId, state) {
            const dom = document.getElementById(domId)
            dom.style["display"] = state
        }
        ///* 페이지 이동 */
        function action_form(idx, type) {
            var url = "";
            if (type == "edit") {
                submit("/analysis/cleaning_rawdata")
            } else if (type == "analysis") {
                submit("/analysis/sub_analy01")
            }

            function submit(url) {
                var frm = document.moveFrm;
                frm.action = url;
                frm.method = "GET";
                frm.data_no.value = idx;
                frm.submit();
            }
        }
    }
    function ProvidedDataComponent(_item) {
        this.item = _item
        this.template = () => {
            const item = this.item
            const editStep = ~~(item.user_no) > 0 && ~~(item.edit_step) === 1 ? '' : "disable"
            return `
                <tr>
                    <td>${item.item_no}</td>
                    <td>${item.data_name}</td>
                    <td>${item.author}</td>
                    <td>${item.genre}</td>
                    <td>${item.update_date.split(' ')[0]}</td>
                    <td>${numberComma(item.text_count)}</td>
                    <td>${item.chapter_count}</td>
                    <td>
                        <button id="clear_button_${item.no}" type="button" class="btn_b line_b mr5">정제</button>
                        <button id="analysis_button_${item.no}" type="button" class="btn_o line_o ${editStep}">분석</button>
                    </td>
                </tr>  `
        }
    }
    function SearchedDataComponent(_item) {
        this.item = _item
        this.template = () => {
            const item = this.item
            const editStep = ~~(item.user_no) > 0 && ~~(item.edit_step) === 1 ? '' : "disable"
            const removeCell = item.user_level > 1 ?
                                `<td><button id="remove_button_${item.no}" class="btn_o line_o">삭제</button></td>` : '<td></td>'

            return `<tr>
                        <td>${item.item_no}</td>
                        <td>${item.data_name}</td>
                        <td>${item.search_keyword}</td>
                        <td>${item.search_start_date} ~ ${item.search_end_date}</td>
                        <td>${item.update_date.split(' ')[0]}</td>
                        <td>
                            <button id="clear_button_${item.no}" type="button" class="btn_b line_b mr5">정제</button>
                            <button id="analysis_button_${item.no}" type="button" class="btn_o line_o ${editStep}">분석</button>
                        </td>
                        ${removeCell}
                    </tr>`
        }
    }
    function CollectedDataComponent(_item) {
        this.item = _item
        this.template = () => {
            const item = this.item
            const collectionState = ~~(item.collection_state)
            const collectionFinished = (collectionState === 1 && item.data_name !== null)
            const dataName = collectionFinished ? item.data_name : item.collection_keyword
            const state = collectionFinished ? "수집완료" : "수집중"
            const refineState = collectionFinished ? "mr5" : "disable"
            const editStep = ~~(item.user_no) > 0 && ~~(item.edit_step) === 1 ? '' : "disable"
            const removeCell = item.user_level > 1 && collectionFinished ?
                                `<td><button id="remove_button_${item.no}" class="btn_o line_o">삭제</button></td>` : '<td></td>'

            return `<tr>
                        <td>${item.item_no}</td>
                        <td>${dataName}</td>
                        <td>${item.collection_keyword}</td>
                        <td>네이버 뉴스</td>
                        <td>${item.collection_start_date} ~ ${item.collection_end_date}</td>
                        <td>${item.update_date.split(' ')[0]}</td>
                        <td>${state}</td>
                        <td>
                            <button id="clear_button_${item.no}" type="button" class="btn_b line_b ${refineState}">정제</button>
                            <button id="analysis_button_${item.no}" type="button" class="btn_o line_o ${editStep}">분석</button>
                        </td>
                        ${removeCell}
                    </tr>`
        }
    }
    function UploadedDataComponent(_item) {
        this.item = _item
        this.template = () => {
            const item = this.item
            let filesize = parseFloat(item.data_size) / 1024

            const denominator = filesize > 1024 ? 1024 : 1
            const fileSizeMeasure = parseFloat(filesize / denominator).toFixed(2)
            const fileSizeText = `${fileSizeMeasure} ${filesize > 1024 ? "MB" : "KB"}`
            const editStep = ~~(item.user_no) > 0 && ~~(item.edit_step) === 1 ? '' : "disable"
            const removeCell = item.user_level > 1 ?
                `<td><button id="remove_button_${item.no}" class="btn_o line_o">삭제</button></td>` : ''

            return `<tr>
                        <td>${item.item_no}</td>
                        <td>${item.data_name}</td>
                        <td>${item.update_date.split(' ')[0]}</td>
                        <td>${fileSizeText}</td>
                        <td>
                            <button id="clear_button_${item.no}" type="button" class="btn_b line_b mr5">정제</button>
		            	    <button id="analysis_button_${item.no}" type="button" class="btn_o line_o ${editStep}">분석</button>
                        </td>
                        ${removeCell}
                    </tr>`
        }
    }
</script>