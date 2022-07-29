<?php
$teacherID = $this->member->item('mem_userid');
$teacher_idx = $this->member->item('mem_id');
?>

<section class="ptitarea task">
    <div class="wrapper">
        <div class="box_ptit_txt">
            <h1>프로젝트 관리</h1>
            <p>수업별 제출 보고서 및 학생별 제출현황 확인</p>
        </div>
    </div>
    <input type="hidden" id="teacherID" value="<?=$teacherID;?>">
    <input type="hidden" id="teacher_idx" value="<?=$teacher_idx;?>">
</section>

<section class="task_list mt40 mb60">
    <div class="wrapper">
        <div class="cont_top mb10">
            <h2>제출 현황</h2>
            <div class="floatr">
                <select id="filter_type" name="filter_type">
                    <option value="class_name">수업명</option>
                    <option value="class_info">학급정보</option>
                    <option value="class_code">수업코드</option>
                </select>
                <span class="searchbox">
                    <input id="search_text" name="search_text" type="text" placeholder="검색어를 입력하세요.">
                    <button type="button" title="검색" onclick="findSubmitStatus()"><i class="fas fa-search"></i></button>
                </span>
            </div>
        </div>

        <table id="submit_status_table" class="tbl01 tac" summary="과제 제출 리스트입니다.">
            <caption>과제 제출 리스트</caption>
            <thead>
            <tr>
                <th class="w10p">NO</th>
                <th>수업명</th>
                <th class="w16p">학급정보</th>
                <th class="w16p">수업코드</th>
                <th class="w10p">학생수</th>
                <th class="w24p">보고서제출</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
        <div class="paging mt20">
            <div class="wr_page">
                <button onclick="moveSubmitStatusFirstPage()" class="first" title="처음 페이지 이동"><i
                            class="fas fa-angle-double-left"></i><em>처음 페이지 이동</em></button>
                <button onclick="moveSubmitStatusPrevPage()" class="pre" title="이전 페이지 이동"><i
                            class="fas fa-angle-left"></i><em>이전 페이지 이동</em></button>
                <span id="submit_status_page_navigation" class="num">
                </span>
                <button onclick="moveSubmitStatusNextPage()" class="next" title="다음 페이지 이동"><i
                            class="fas fa-angle-right"></i><em>다음 페이지 이동</em></button>
                <button onclick="moveSubmitStatusLastPage()" class="last" title="끝 페이지 이동"><i
                            class="fas fa-angle-double-right"></i><em>끝 페이지 이동</em></button>
            </div>
        </div>

        <div id="student_submit_status_list">
            <div class="cont_top mt40 mb10">
                <h2>학생별 보고서 현황</h2>
                <div class="floatr">
                    <input type="checkbox" id="unsubmit_status"><label for="unsubmit_status" class="mt8">&nbsp;미제출
                        우선보기</label>
                </div>
            </div>
            <div class="tbl_box">
                <table id="student_work_status_table" class="tbl01 tac" summary="학생별 보고서 제출 리스트입니다.">
                    <caption>학생별 보고서 현황 리스트</caption>
                    <thead>
                    <tr>
                        <th class="w10p">
                            <input type="checkbox" id="chkall"><label for="chkall"></label>
                        </th>
                        <th class="w10p">NO</th>
                        <th>계정</th>
                        <th class="w14p">성명</th>
                        <th class="w16p">제출여부</th>
                        <th class="w16p">제출일자</th>
                        <th class="w10p">보고서</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="paging mt20">
                <div class="wr_page">
                    <button onclick="moveStudentWorkStatusFirstPage()" class="first" title="처음 페이지 이동"><i
                                class="fas fa-angle-double-left"></i><em>처음 페이지 이동</em></button>
                    <button onclick="moveStudentWorkStatusPrevPage()" class="pre" title="이전 페이지 이동"><i
                                class="fas fa-angle-left"></i><em>이전 페이지 이동</em></button>
                    <span id="student_work_status_page_navigation" class="num"></span>
                    <button onclick="moveStudentWorkStatusNextPage()" class="next" title="다음 페이지 이동"><i
                                class="fas fa-angle-right"></i><em>다음 페이지 이동</em></button>
                    <button onclick="moveStudentWorkStatusLastPage()" class="last" title="끝 페이지 이동"><i
                                class="fas fa-angle-double-right"></i><em>끝 페이지 이동</em></button>
                </div>
            </div>
            <div class="tac mt20">
                <button type="button" class="btn blue round mr5" onclick="showSubmitBestReportPopup()">뽐내기 제출</button>
                <button type="button" class="btn pink round" onclick="showReturnReportPopup()">제출 반려</button>
            </div>
        </div>
    </div>
</section>

<div class="lpopup" style="display: none">
    <div class="wrap_lpop">
        <div class="tit_pop">
            <h2>알림</h2>
            <span onclick="hideSubmitPopup();" class="btn_pop_close" title="창닫기"><i class="fas fa-times"></i></span>
        </div>
        <div class="pop_body">
            <div class="cont_alert"></div>
            <div class="tac mt20">
                <button id="submit_button" type="submit" class="btn blue mr5">확인</button>
                <button type="button" class="btn grey" onclick="hideSubmitPopup()">취소</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    let filterType = "class_name"
    let searchText = ''
    let submitStatusPageNo = 1
    let submitStatusLastPageNo = 1
    let studentWorkStatusPageNo = 1
    let studentWorkStatusLastPageNo = 1
    let unsubmitStatus = 0
    let currentClassNo = 0

    $(document).ready(function() {
        $("#student_submit_status_list").hide()
        getSubmitStatusList();

        $("#unsubmit_status").change(function() {
            if ($("#unsubmit_status").is(":checked")) {
                unsubmitStatus = 1
            } else {
                unsubmitStatus = 0
            }
            moveStudentWorkStatusFirstPage()
        })

        $("#chkall").change(function() {
            if ($("#chkall").is(":checked")) {
                $("input[name=student_work_status_chk]:checkbox:not(:disabled)").prop("checked", "checked");
            } else {
                $("input[name=student_work_status_chk]:checkbox").removeProp("checked");
            }
        })
    });

    function findSubmitStatus() {
        submitStatusPageNo = 1
        filterType = $('#filter_type').val()
        searchText = $('#search_text').val()
        getSubmitStatusList()
    }

    function refreshSubmitStatusListByPageNavigation() {
        $("#filter_type").val(filterType).prop("selected", true)
        $("#search_text").val(searchText)
        getSubmitStatusList()
    }

    function moveSubmitStatusPage(pageNo) {
        submitStatusPageNo = pageNo
        refreshSubmitStatusListByPageNavigation()
    }

    function moveSubmitStatusFirstPage() {
        submitStatusPageNo = 1
        refreshSubmitStatusListByPageNavigation()
    }

    function moveSubmitStatusLastPage() {
        submitStatusPageNo = submitStatusLastPageNo
        refreshSubmitStatusListByPageNavigation()
    }

    function moveSubmitStatusPrevPage() {
        submitStatusPageNo = (submitStatusPageNo > 1) ? (submitStatusPageNo - 1) : 1
        refreshSubmitStatusListByPageNavigation()
    }

    function moveSubmitStatusNextPage() {
        submitStatusPageNo = (submitStatusLastPageNo > submitStatusPageNo) ? (submitStatusPageNo + 1) :
            submitStatusLastPageNo
        refreshSubmitStatusListByPageNavigation()
    }

    function moveStudentWorkStatusPage(pageNo) {
        studentWorkStatusPageNo = pageNo
        refreshStudentWorkStatusList()
    }

    function moveStudentWorkStatusFirstPage() {
        studentWorkStatusPageNo = 1
        refreshStudentWorkStatusList()
    }

    function moveStudentWorkStatusPrevPage() {
        studentWorkStatusPageNo = (studentWorkStatusPageNo > 1) ? (studentWorkStatusPageNo - 1) : 1
        refreshStudentWorkStatusList()
    }

    function moveStudentWorkStatusLastPage() {
        studentWorkStatusPageNo = studentWorkStatusLastPageNo
        refreshStudentWorkStatusList()
    }

    function moveStudentWorkStatusNextPage() {
        studentWorkStatusPageNo = (studentWorkStatusLastPageNo > studentWorkStatusPageNo) ?
            (studentWorkStatusPageNo + 1) : studentWorkStatusLastPageNo
        refreshStudentWorkStatusList()
    }

    function getSubmitStatusList() {
        const teacherNo = $("#teacher_idx").val();
        $.ajax({
            type: "POST",
            url: "/edumining/project_management/getSubmitStatusList",
            data: {
                teacher_no: teacherNo,
                page_no: submitStatusPageNo,
                filter_type: filterType,
                search_text: searchText
            },
            dataType: "JSON",
            success: function(args) {
                if (args['msg'] === 'success') {
                    const items = args['list'];
                    $("#submit_status_table > tbody").html('')
                    if (items.length > 0) {
                        for (let i = 0; i < items.length; i++) {
                            const item            = items[i]
                            const classIdx        = item['class_no'];
                            const submitRate      = item['submit_rate'] * 100
                            const itemNo          = item["item_no"]
                            const lectureName     = item["lecture_name"]
                            const lectureOverview = item["lecture_overview"]
                            const prefixId        = item["prefix_id"]
                            const studentCount    = item["student_count"]
                            let html = `<tr>;
                                          <td data-idx="${classIdx}">${itemNo}</td>
                                          <td><a onclick="getStudentWorkStatusList(${classIdx})">${lectureName}</a></td>
                                          <td>${lectureOverview}</td>
                                          <td>${prefixId}</td>
                                          <td>${studentCount}명</td>
                                          <td>
                                            <ul class="bar_pct vat">
                                              <li><span><em style="width: ${submitRate}%"></em></span></li>
                                              <li>${submitRate}%</li>
                                            </ul>
                                          </td>
                                        </tr>`
                            // console.log(item)
                            $("#submit_status_table > tbody").append(html);
                        }

                        const currentPageNo = args['current_page_no']
                        const totalPageCount = args['total_page_count']
                        submitStatusLastPageNo = totalPageCount

                        $("#submit_status_page_navigation").html('')
                        for (let i = 0; i < totalPageCount; i++) {
                            const pageNo = i + 1
                            const isCurrentPage = pageNo === currentPageNo
                            const html = `<a onclick="moveSubmitStatusPage(${pageNo})"
                                             style="${isCurrentPage ? 'font-weight: bold; color:#e84c88;' : ''}">
                                            ${pageNo}
                                          </a>`
                            $("#submit_status_page_navigation").append(html)
                        }
                    } else {
                        var html = "<tr>";
                        html += "<td colspan='6' class='nolist'>생성된 수업이 없습니다.</td>";
                        html += "</tr>";
                        $("#submit_status_table > tbody").append(html);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    }

    function getStudentWorkStatusList(classNo) {
        currentClassNo = classNo
        studentWorkStatusPageNo = 1
        studentWorkStatusLastPageNo = 1
        refreshStudentWorkStatusList()
        $("#student_submit_status_list").show()
    }

    function refreshStudentWorkStatusList() {
        var teacherNo = $("#teacher_idx").val();
        $.ajax({
            type: "POST",
            url: "/edumining/project_management/getStudentReportStatusList",
            data: {
                teacher_no: teacherNo,
                class_no: currentClassNo,
                page_no: studentWorkStatusPageNo,
                unsubmit_status: unsubmitStatus,
            },
            dataType: "JSON",
            success: function(args) {
                if (args['msg'] == 'success') {
                    const items = args['list'];
                    $("#student_work_status_table > tbody").html('')
                    if (items.length > 0) {
                        for (let i = 0; i < items.length; i++) {
                            const item = items[i]
                            const classNo = item['class_no'];
                            const user = {
                                no: item['user_no'],
                                id: item['user_id'],
                                name: item['user_name']
                            }
                            const submitRate = item['submit_rate'] * 100
                            const submitState = Number(item['submit_state'])
                            const permitState = Number(item['permit_state'])
                            const submitDate = item['submit_date'] === '' ? '-' : item['submit_date']
                            const reportNo = item['report_no']
                            const chkId = "chk" + String(i)
                            const itemNo = item['item_no']
                            const userNo = user['no']
                            const userId = user['id']
                            const userName = user['name']

                            // 제출완료(submitState=1) and (우수과제로 제출되지 않은 상태(permitState=0) or 승인불가 상태(permitState=3)
                            const chkEnableState = submitState === 1 && (permitState === 0 || permitState === 3)
                            const chkEnable = chkEnableState ? '' : "disabled"

                            let html = `<tr>
                                          <td class='check_area'>
                                            <input type="checkbox" id="${chkId}"
                                                   name="student_work_status_chk"
                                                   value="${reportNo}" ${chkEnable}/>
                                              <label for="${chkId}"></label>
                                          </td>
                                          <td data-idx="${classNo}">${itemNo}</td>
                                          <td>${userId}
                                            <button type="button" class="btn sm lgrey ml5" title="비밀번호 초기화"
                                                    onclick="clearPassword(${userNo}, '${userId}')">
                                              <i class="fas fa-undo"></i>
                                            </button>
                                          </td>
                                          <td>${userName}</td>`

                            let em = "<em>"
                            if (Number(item['submit_state']) === 0)
                                em = "<em class='blue fw500'>"
                            else if (Number(item['submit_state']) === 2)
                                em = "<em class='pink fw500'>"

                            html += `  <td>${em}${item["submit_state_text"]}</em></td>
                                       <td>${submitDate}</td>
                                       <td>`
                            if (submitState !== 0) {
                                html +=
                                    `    <button type="button" class="btn sm grey round" onclick="viewReport(${reportNo})">보기</button>`
                            } else {
                                html += '-'
                            }
                            html += `  </td>
                                     </tr>`

                            $("#student_work_status_table > tbody").append(html);
                        }

                        const currentPageNo = args['current_page_no']
                        const totalPageCount = args['total_page_count']
                        studentWorkStatusLastPageNo = totalPageCount
                        let centerPagingSrc = `<strong>${currentPageNo}</strong>`
                        const leftPageNavi = new PageNavigation()
                        for (let pageNo = currentPageNo - 2; pageNo < currentPageNo; pageNo++) {
                            if (pageNo < 1) continue
                            if (leftPageNavi.isFull()) break
                            leftPageNavi.addPage(pageNo)
                        }
                        const rightPageNavi = new PageNavigation()
                        for (let pageNo = currentPageNo + 1; pageNo <= totalPageCount; pageNo++) {
                            if (rightPageNavi.isFull()) break
                            rightPageNavi.addPage(pageNo)
                        }
                        $("#student_work_status_page_navigation").html(leftPageNavi.src + centerPagingSrc + rightPageNavi.src)

                        function PageNavigation() {
                            let addedPageCount = 0
                            this.src = ''
                            this.addPage = (pageNo) => {
                                this.src += `<a onclick="moveStudentWorkStatusPage(${pageNo})">${pageNo}</a>`
                                addedPageCount = addedPageCount + 1
                            }
                            this.isFull = () => addedPageCount === 2
                        }

                        $("input:checkbox[id='chkall']").attr("checked", false)

                    } else {
                        var html = "<tr>";
                        html += "<td colspan='6' class='nolist'>생성된 과제가 없습니다.</td>";
                        html += "</tr>";
                        $("#student_work_status_table > tbody").append(html);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    }

    function showSubmitPopup() {
        let noArr = []
        $("input:checkbox[name=student_work_status_chk]:checked").each(function() {
            noArr.push(this.value)
        })

        if (noArr.length === 0) {
            alert("제출할 과제를 선택하세요.")
            return
        }

        $(".lpopup").show()
    }

    function hideSubmitPopup() {
        $(".lpopup").hide()
    }

    function showSubmitBestReportPopup() {

        let noArr = []
        $("input:checkbox[name=student_work_status_chk]:checked").each(function() {
            noArr.push(this.value)
        })

        if (noArr.length === 0) {
            alert("제출할 과제를 선택하세요.")
            return
        }

        const html = "<h3>선택한 <strong class='blue'>우수과제</strong>를 제출합니다.</h3>" +
            "<p>최종 관리자 승인 후 과제 뽐내기 게시판으로 이동합니다.</p>"
        $(".cont_alert").html(html)

        $("#submit_button").off("click")
        $("#submit_button").click(function() {

            $.ajax({
                type: "POST",
                url: "/edumining/project_management/submitBestReport",
                data: {
                    report_no_list: noArr,
                },
                dataType: "JSON",
                success: function(args) {
                    if (args['msg'] === 'success') {
                        refreshStudentWorkStatusList()
                    }
                    $(".lpopup").hide()
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        })

        $(".lpopup").show()
    }

    function showReturnReportPopup() {

        let noArr = []
        $("input:checkbox[name=student_work_status_chk]:checked").each(function() {
            noArr.push(this.value)
        })

        if (noArr.length === 0) {
            alert("반려할 과제를 선택하세요.")
            return
        }

        const html = "<h3>선택한 과제를 <strong class='pink'>반려</strong> 하시겠습니까?</h3>"
        $(".cont_alert").html(html)

        $("#submit_button").off("click")
        $("#submit_button").click(function() {

            $.ajax({
                type: "POST",
                url: "/edumining/project_management/returnReport",
                data: {
                    report_no_list: noArr,
                },
                dataType: "JSON",
                success: function(args) {
                    if (args['msg'] === 'success') {
                        refreshStudentWorkStatusList()
                    }
                    $(".lpopup").hide()
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        })

        $(".lpopup").show()
    }

    function clearPassword(userNo, userId) {

        const html = `<h3>선택한 계정(<strong class='blue'>${userId}</strong>)의 비밀번호를 초기화합니다.</h3>
                      <p>비밀번호는 계정과 동일하게 변경됩니다.</p>`
        $(".cont_alert").html(html)

        $("#submit_button").off("click")
        $("#submit_button").click(function() {
            $.ajax({
                type: "POST",
                url: "/edumining/project_management/clearPassword",
                data: {
                    user_no: userNo,
                    user_id: userId
                },
                dataType: "JSON",
                success: function (args) {
                    if (args['msg'] === 'success') {
                        alert("비밀번호가 변경되었습니다.")
                    }
                    $(".lpopup").hide()
                },
                error: function (xhr, status, error) {
                    console.log(error);
                }
            });
        })
        $(".lpopup").show()
    }

    function viewReport(reportNo) {
        localStorage.setItem("report_no", reportNo)
        window.open("/management/popup_view_report", "a", "width=1068, height=800, left=10, top=10");
    }
</script>