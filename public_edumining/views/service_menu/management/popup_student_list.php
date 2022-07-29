<?php

echo "";

?>

<div class="tit_pop">
    <h2>학생 리스트</h2>
    <span class="btn_pop_close" title="창닫기" onclick="self.close();"><i class="fas fa-times"></i></span>
</div>

<div class="pop_body">
    <section class="mb30">
        <div id="student_submit_status_list">
            <div class="cont_top mt40 mb10">
                <h2>학생 리스트</h2>
            </div>
            <div class="tbl_box">
                <table id="student_work_status_table" class="tbl01 tac" summary="학생 리스트입니다.">
                    <caption>학생 리스트</caption>
                    <thead>
                    <tr>
                        <th class="w10p">NO</th>
                        <th>계정</th>
                        <th class="w14p">성명</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="paging mt20">
                <div class="wr_page">
                    <button onclick="movestudentFirstPage()" class="first" title="처음 페이지 이동"><i
                                class="fas fa-angle-double-left"></i><em>처음 페이지 이동</em></button>
                    <button onclick="movestudentPrevPage()" class="pre" title="이전 페이지 이동"><i
                                class="fas fa-angle-left"></i><em>이전 페이지 이동</em></button>
                    <span id="student_work_status_page_navigation" class="num"></span>
                    <button onclick="movestudentNextPage()" class="next" title="다음 페이지 이동"><i
                                class="fas fa-angle-right"></i><em>다음 페이지 이동</em></button>
                    <button onclick="movestudentLastPage()" class="last" title="끝 페이지 이동"><i
                                class="fas fa-angle-double-right"></i><em>끝 페이지 이동</em></button>
                </div>
            </div>
        </div>
    </section>

    <div class="lpopup" style="display: none">
        <div class="wrap_lpop">
            <div class="tit_pop">
                <h2>알림</h2>
                <span onclick="lpop_hide()" class="btn_pop_close" title="창닫기"><i class="fas fa-times"></i></span>
            </div>
            <div class="pop_body">
                <div class="cont_alert"></div>
                <div class="tac mt20">
                    <button id="submit_button" type="submit" class="btn blue mr5">확인</button>
                    <button type="button" class="btn grey" onclick="lpop_hide()">취소</button>
                </div>
            </div>
        </div>
    </div>


</div>

<script>

    // 레이어 팝업 닫기
    function lpop_hide(){
        $(".lpopup").hide();
    }

    let studentPageNo = 1
    let studentLastPageNo = 1
    const teacherNo = ~~("<? echo $this->member->item('mem_id') ?>")
    const currentClassNo = ~~(localStorage.getItem("class_no"))
    if (teacherNo === 0 || currentClassNo === 0) {
        alert("학생 리스트를 불러올 수 없습니다.\n먼저 로그인을 해주세요.")
        window.close()
    }

    movestudentFirstPage()

    function movestudentPage(pageNo) {
        studentPageNo = pageNo
        refreshstudentList()
    }

    function movestudentFirstPage() {
        studentPageNo = 1
        refreshstudentList()
    }

    function movestudentPrevPage() {
        studentPageNo = (studentPageNo > 1) ? (studentPageNo - 1) : 1
        refreshstudentList()
    }

    function movestudentLastPage() {
        studentPageNo = studentLastPageNo
        refreshstudentList()
    }

    function movestudentNextPage() {
        studentPageNo = (studentLastPageNo > studentPageNo) ?
            (studentPageNo + 1) : studentLastPageNo
        refreshstudentList()
    }

    function refreshstudentList() {
        $.ajax({
            type: "POST",
            url: "/edumining/project_management/getStudentReportStatusList",
            data: {
                teacher_no: teacherNo,
                class_no: currentClassNo,
                page_no: studentPageNo,
                unsubmit_status: 0,
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
                            const submitState = ~~(item['submit_state'])
                            const permitState = ~~(item['permit_state'])
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
                                          <td data-idx="${classNo}">${itemNo}</td>
                                          <td>${userId}
                                            <button type="button" class="btn sm lgrey ml5" title="비밀번호 초기화"
                                                    onclick="clearPassword(${userNo}, '${userId}')">
                                              <i class="fas fa-undo"></i>
                                            </button>
                                          </td>
                                          <td>${userName}</td>`


                            $("#student_work_status_table > tbody").append(html);
                        }

                        const currentPageNo = args['current_page_no']
                        const totalPageCount = args['total_page_count']
                        studentLastPageNo = totalPageCount
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
                        $("input:checkbox[id='chkall']").attr("checked", false)
                        function PageNavigation() {
                            let addedPageCount = 0
                            this.src = ''
                            this.addPage = (pageNo) => {
                                this.src += `<a onclick="movestudentPage(${pageNo})">${pageNo}</a>`
                                addedPageCount = addedPageCount + 1
                            }
                            this.isFull = () => addedPageCount === 2
                        }
                    } else {
                        var html = "<tr>";
                        html += "<td colspan='6' class='nolist'>학생 리스트가 존재하지 않습니다.</td>";
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

</script>

