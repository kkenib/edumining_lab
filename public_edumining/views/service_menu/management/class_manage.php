<?php
    $data = $view['data'];
    //print_r($view);
    $teacherID = $data['mem_userid'];
    $teacher_idx = $data['mem_id'];
?>
<section class="ptitarea list">
	<div class="wrapper">
		<div class="box_ptit_txt">
			<h1>수업 관리</h1>
			<p>수업 수정, 삭제, 등록 등 원스탑 수업 관리</p>
		</div>
	</div>
</section>

<section class="mt40 mb60">
	<div class="wrapper">
		<div class="cont_top mb10">
			<h2>수업 추가</h2>
		</div>
		<div class="box pink round">
			<input type="hidden" id="teacherID" value="<?=$teacherID;?>">
			<input type="hidden" id="teacher_idx" value="<?=$teacher_idx;?>">
            <input type="hidden" id="page" value="<?=(((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;?>">
			<input type="text" id="classTitle" placeholder="수업명을 입력하세요." class="w100p tac">
			<dl class="list_dl02 column2 mt16">
				<div class="floatl">
					<dt>학급정보</dt>
					<dd><input type="text" id="classInfo" placeholder="학급정보를 입력하세요. 예)1학년 1반 1학기" class="w100p"></dd>
				</div>
				<div class="disinblock">
					<dt>수업주제</dt>
					<dd><input type="text" id="classTopic" placeholder="수업주제를 입력하세요."class="w100p"></dd>
				</div>
			</dl>
			<dl class="list_dl02 column2 mt16">
				<div class="floatl">
					<dt>학생수</dt>
					<dd><input type="text" id="studentCount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" placeholder="학생수를 입력하세요." class="w100p"></dd>
				</div>
				<div class="disinblock">
					<dt>수업코드
						<span class="tooltip_info set">
							<i class="fas fa-question-circle"></i>
							<span class="right">
								<div>
									수업코드+001부터 입력한 학생수만큼 계정이 자동으로 생성됩니다.<br>
									<em>Ex) 수업코드를 abc로 등록하고, 학생 수가 10명일 경우<br>abc001~abc010까지 학생 계정 생성되며 비밀번호는 아이디와 동일합니다.</em>
								</div>
							</span>
						</span>
					</dt>
					<dd class="classcode">
						<input type="text" id="classPrefix" placeholder="입력한 수업코드는 학생들 아이디로 사용됩니다.">
						<input id="classPrefixCheck" type="hidden" value="N">
						<button type="button" class="btn grey" onclick="checkClassPrefix()">중복확인</button>
						<!-- <div class="id_box">
							<input id="classPrefix" type="text" placeholder="계정 아이디를 입력하세요.">
							<input id="classPrefixCheck" type="hidden" value="N">
							<div class="cont_btn">
								<button type="button" class="btn_sm dgray_sq" onclick="checkClassPrefix()">중복확인</button>
							</div>
						</div> -->
					</dd>
				</div>
			</dl>
			<dl class="list_dl column2 mt8" style="display:none">
				<dt>학급정보</dt>
				<dd><input type="text" id="classInfo" placeholder="학급정보를 입력하세요. 예)1학년 1반 1학기" class="w100p"></dd>
				<dt>수업주제</dt>
				<dd><input type="text" id="classTopic" placeholder="수업주제를 입력하세요."class="w100p"></dd>
				<dt>학생수</dt>
				<dd><input type="text" id="studentCount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" placeholder="학생수를 입력하세요." class="w100p"></dd>
				<dt>수업코드
					<span class="tooltip_info set">
						<i class="fas fa-question-circle"></i>
						<span class="right">
							<div>
								수업코드+001부터 입력한 학생수만큼 계정이 자동으로 생성됩니다.<br>
								<em>Ex) 수업코드를 abc로 등록하고, 학생 수가 10명일 경우 abc001~abc010까지 학생 계정 생성되며 비밀번호는 아이디와 동일합니다.</em>
							</div>
						</span>
					</span>
				</dt>
				<dd class="classcode">
					<input type="text" id="classPrefix" placeholder="입력한 수업코드는 학생들 아이디로 사용됩니다.">
                    <input id="classPrefixCheck" type="hidden" value="N">
					<button type="button" class="btn grey" onclick="checkClassPrefix()">중복확인</button>
					<!-- <div class="id_box">
						<input id="classPrefix" type="text" placeholder="계정 아이디를 입력하세요.">
						<input id="classPrefixCheck" type="hidden" value="N">
						<div class="cont_btn">
							<button type="button" class="btn_sm dgray_sq" onclick="checkClassPrefix()">중복확인</button>
						</div>
					</div> -->
				</dd>
			</dl>
		</div>
		<div class="tac mt20">
			<button type="button" onclick="createClass()" class="btn blue xlg round">수업 추가</button>
		</div>

		<div class="cont_top mt40 mb10">
			<h2>수업 목록</h2>
			<span id="classCount" class="total ml20">내 클래스 : <em>3</em>개</span>
			<div class="floatr">
				<select id="searchSelect">
					<option>수업명</option>
					<option>학급정보</option>
					<option>수업코드</option>
				</select>
				<span class="searchbox">
					<input id="searchQuery" type="text" placeholder="검색어를 입력하세요.">
					<button type="button" onclick="searchClass()" title="검색"><i class="fas fa-search"></i></button>
				</span>
			</div>
		</div>

		<!-- <div class="tbl_scroll"> -->
			<table id="classTable" class="tbl01 tac" summary="수업 목록입니다">
				<caption>수업 목록</caption>
				<thead>
					<tr>
						<th class="w8p">NO</th>
						<th>수업명</th>
						<th class="w16p">학급정보</th>
						<th class="w12p">수업주제</th>
						<th class="w12p">수업코드</th>
						<th>학생수</th>
						<th class="w10p">관리</th>
					</tr>
				</thead>
				<tbody>
                    <?
                        if(count($view['subData']) > 0){
                            for($i = 0; $i < count($view['subData']); $i++){
                                $classData = $view['subData'][$i];
                                //print_r($classData);
                    ?>
                    <tr>
                        <td data-idx="<?=$classData['no'];?>"><?=$classData['classNo'];?></td>
                        <td><?=$classData['lecture_name'];?></td>
                        <td><?=$classData['lecture_overview'];?></td>
                        <td><?=$classData['lecture_subject'];?></td>
                        <td><?=$classData['prefix_id'];?></td>
                        <td><?=$classData['student_count'];?></td>
                        <td><button type='button' onclick='editClass(this)' class='btn skyblue sm round'>수정</button></td>
                    </tr>
                    <?
                            }
                        }else{
                    ?>
                    <tr>
                        <td colspan="7" class="nolist">생성된 수업이 없습니다.</td>
                    </tr>
                    <?
                        }
                    ?>
                    <!--
					<tr>
						<td>3</td>
						<td>신문기사로 학습하기</td>
						<td>1학년 1반 1학기</td>
						<td>텍스트분석</td>
						<td>abcdefghij</td>
						<td>20</td>
						<td><button type="button" class="btn skyblue sm round">수정</button></td>
					</tr>
					-->
					<!-- edit : S -->
                    <!--
					<tr>
						<td>2</td>
						<td><input type="text" value="신문기사로 학습하기"></td>
						<td><input type="text" value="1학년 1반 1학기"></td>
						<td><input type="text" value="텍스트분석"></td>
						<td>abc</td>
						<td>
							<span class="numctrbox vat">
								<button type="button" class="btn disable"><i class="fas fa-minus"></i></button>
								<input type="text" value="20">
								<button type="button" class="btn"><i class="fas fa-plus"></i></button>
							</span>
						</td>
						<td>
							<button type="button" class="btn blue sm round mt2 mb2">저장</button>
							<button type="button" class="btn pink sm round mt2 mb2">삭제</button>
						</td>
					</tr>
					-->
					<!-- edit : E -->
                    <!--
					<tr>
						<td>1</td>
						<td>신문기사로 학습하기</td>
						<td>1학년 1반 1학기</td>
						<td>텍스트분석</td>
						<td>abc</td>
						<td>20</td>
						<td><button type="button" class="btn sm skyblue round">수정</button></td>
					</tr>
					-->
					<!-- nolist : S-->
                    <!--
					<tr>
						<td colspan="7" class="nolist">생성된 수업이 없습니다.</td>
					</tr>
                    -->
					<!-- nolist : E-->
				</tbody>
			</table>
			<div class="sub_text">※ 수업명을 클릭하면 학생 목록을 확인할 수 있습니다.</div>
		<!-- </div> -->

        <div id="paging" class="paging mt20">
            <?php echo $view['paging'];?>
        </div>
	</div>
</section>



<div class="lpopup" style="display: none;">
	<div class="wrap_lpop">
		<div class="tit_pop">
			<h2>알림</h2>
			<span onclick="lpop_hide()" class="btn_pop_close" title="창닫기"><i class="fas fa-times"></i></span>
		</div>
		<div class="pop_body">
			<div class="cont_alert">
				<h3 id="popup_head_msg">
                    <!--
                    <strong class="blue">신문기사로 학습하기</strong> 수업을 수정 하시겠습니까?
                    -->
                </h3>
				<p id="popup_msg">
                    <!--
					수업코드 <em class="pink fw500">abc</em>로 <em class="pink fw500">1</em>개의 아이디가 추가 생성됩니다.
					-->
				</p>
			</div>
			<div class="tac mt20">
				<button type="submit" id="lpop_submit" class="btn blue mr5">확인</button>
				<button id="lpop_close" onclick="lpop_hide()" type="button" class="btn grey">취소</button>
			</div>
		</div>
	</div>
</div>



<script type="text/javascript">
    $(document).ready(function() {
        getClassList();
    });

    // 레이어 팝업 닫기
    function lpop_hide(){
        $(".lpopup").hide();
    }

    // 클래스 목록 가져오기
    function getClassList(kind, keyword=''){
        $("#paging").html("");
        $("#classTable > tbody").children().remove();

        $.ajax({
            type: "POST",
            url: "/edumining/class_management/getClassList",
            data: {kind : kind, keyword : keyword},
            dataType: "JSON",
            success: function(args){
                if(args['msg'] == 'success'){
                    // return 변수 지정
                    var paging = args['paging'];
                    var data = args['data'];

                    if(data.length > 0) {
                        $("#classCount").html("내 클래스 : <em>" + data.length + "</em>개");

                        // 페이징 소스 append
                        $("#paging").append(paging);

                        // 수업 리스트 append
                        var html = ''
                        for (var i = 0; i < data.length; i++) {
                            html += "<tr>";
                            html += "<td data-idx='" + data[i]['no'] + "' data-std_count='" + data[i]['student_count'] + "' data-className='" + data[i]['lecture_name'] + "' data-classInfo='" + data[i]['lecture_overview'] + "' data-classTopic=-'" + data[i]['lecture_subject'] + "' data-prefix='" + data[i]['prefix_id'] + "' data-stdCount='" + data[i]['student_count'] + "'>" + data[i]['classNo'] + "</td>";
                            html += `<td><a onclick="viewStudentList(${data[i]['no']})" title="수업명을 클릭하면 학생 목록을 확인할 수 있습니다">${data[i]['lecture_name']}</a></td>`
                            html += "<td>" + data[i]['lecture_overview'] + "</td>";
                            html += "<td>" + data[i]['lecture_subject'] + "</td>";
                            html += "<td>" + data[i]['prefix_id'] + "</td>";
                            html += "<td>" + data[i]['student_count'] + "</td>";
                            html += "<td>";
                            html += "<button type='button' onclick='editClass(this)' class='btn skyblue sm round'>수정</button>";
                            html += "</td>";
                            html += "</tr>";
                        }
                        $("#classTable > tbody").append(html);

                        pagination();

                        function pagination() {
                            $("#paging").find('a').on("click", function (event) {
                                var url = $(this).attr("href");
                                if (url == "http://edumining.textom.co.kr/edumining/class_management/getClassList") {
                                    url = "http://edumining.textom.co.kr/edumining/class_management/getClassList?&page=1";
                                }
                                if (typeof url == 'undefined' || url == "" || url == "none") {
                                    return false;
                                }

                                var page = $(this).data('ci-pagination-page');

                                $("#classTable > tbody").children().remove();

                                $.ajax({
                                    type: "POST",
                                    url: url,
                                    data: {kind: kind, keyword: keyword, page: page},
                                    dataType: "JSON",
                                    beforeSend: function () {
                                        $("#paging").html("");
                                    },
                                    success: function (args) {
                                        // return 변수 지정
                                        var paging = args['paging'];
                                        var data = args['data'];

                                        // 페이징 소스 append
                                        $("#paging").append(paging);

                                        // 수업 리스트 append
                                        for (var i = 0; i < data.length; i++) {
                                            var html = "<tr>";
                                            html += "<td data-idx='" + data[i]['no'] + "' data-std_count='" + data[i]['student_count'] + "' data-className='" + data[i]['lecture_name'] + "' data-classInfo='" + data[i]['lecture_overview'] + "' data-classTopic=-'" + data[i]['lecture_subject'] + "' data-prefix='" + data[i]['prefix_id'] + "' data-stdCount='" + data[i]['student_count'] + "'>" + data[i]['classNo'] + "</td>";
                                            html += `<td><a onclick="viewStudentList(${data[i]['no']})">${data[i]['lecture_name']}</a></td>`
                                            html += "<td>" + data[i]['lecture_overview'] + "</td>";
                                            html += "<td>" + data[i]['lecture_subject'] + "</td>";
                                            html += "<td>" + data[i]['prefix_id'] + "</td>";
                                            html += "<td>" + data[i]['student_count'] + "</td>";
                                            html += "<td>";
                                            html += "<button type='button' onclick='editClass(this)' class='btn skyblue sm round'>수정</button>";
                                            html += "</td>";
                                            html += "</tr>";

                                            $("#classTable > tbody").append(html);
                                        }
                                        pagination();
                                    }
                                });
                                return false;
                            });
                        }
                    }else{
                        $("#classCount").html("내 클래스 : <em>0</em>개");
                        html = "<tr>";
                        html += '<td colspan="7" class="nolist">생성된 수업이 없습니다.</td>';
                        html += '</tr>';
                        $("#classTable > tbody").append(html);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    }

    // 수업 검색
    function searchClass(){
        var kind = $("#searchSelect").val();
        var keyword = $("#searchQuery").val();
        getClassList(kind, keyword);
    }

    // 수업 수정
    function editClass(_this){
        var classList = $(_this).parent().parent()[0];
        var classIdx = $($(classList).children('td')[0]).data("idx");

        var classHTML = $($(classList).children('td')[1]).text();
        $($(classList).children('td')[1]).html("<input type='text' value='" + classHTML + "'>")

        var classHTML = $($(classList).children('td')[2]).html();
        $($(classList).children('td')[2]).html("<input type='text' value='" + classHTML + "'>")

        var classHTML = $($(classList).children('td')[3]).html();
        $($(classList).children('td')[3]).html("<input type='text' value='" + classHTML + "'>")

        var classHTML = $($(classList).children('td')[5]).html();

        var html = "<span class='numctrbox vat'>";
        html += "<button type='button' onclick='studentCountChange(this, \"down\")' class='btn'><i class='fas fa-minus'></i></button>"
        html += "<input type='text' value='" + classHTML + "'>";
        html += "<button type='button' onclick='studentCountChange(this, \"up\")' class='btn'><i class='fas fa-plus'></i></button>"
        html += "</span>";
        $($(classList).children('td')[5]).html(html);

        var html = "<button type='button' onclick='classSave(this)' class='btn sm blue round mg2'>저장</button>";
        html += "<button type='button' onclick='classDelete(this)' class='btn sm pink round mg2'>삭제</button>";
        $($(classList).children('td')[6]).html(html);
    }

    // 수업 수정 -> submit
    function classEditSubmit(data){
        $.ajax({
            type: "POST",
            url: "/edumining/class_management/classSave",
            data: {
                classIdx : data['classIdx'],
                classTitle : data['classTitle'],
                classInfo : data['classInfo'],
                classTopic : data['classTopic'],
                classPrefix : data['classPrefix'],
                studentCount : data['studentCount']
            },
            dataType: "JSON",
            success: function(args){
                if(args['msg'] == 'success'){
                    alert("수정하였습니다.");
                    location.reload();
                }else{
                    alert("수정에 실패하였습니다.");
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    }


    // 수업 목록 -> 수업 -> 수업 정보 수정 후 저장버튼 클릭
    function classSave(_this){
        var classList = $(_this).parent().parent()[0];
        var classIdx = $($(classList).children('td')[0]).data("idx");
        var classStdCount = $($(classList).children('td')[0]).data("std_count");

        var classTitle = $($(classList).children('td')[1]).children().val();
        var classInfo = $($(classList).children('td')[2]).children().val();
        var classTopic = $($(classList).children('td')[3]).children().val();
        var classPrefix = $($(classList).children('td')[4]).html();
        var studentCount = $($(classList).children('td')[5]).find('input').val();

        var editData = {
            'classIdx' : classIdx,
            'classTitle' : classTitle,
            'classInfo' : classInfo,
            'classTopic' : classTopic,
            'classPrefix' : classPrefix,
            'studentCount' : studentCount
        }

        if(classStdCount == studentCount){
            makeAddClassErrPopup();
            $("#popup_head_msg").append(`<strong class="blue">${classTitle}</strong> 수업을 수정하시겠습니까?`);
            $("#lpop_submit").attr("onclick", "classEditSubmit(" + JSON.stringify(editData) + ")");
            $("#lpop_close").show();
        } else if(classStdCount > studentCount){
            makeAddClassErrPopup();
            $("#popup_head_msg").append("최초 학생 수 보다 작은 학생 수를 지정할 수 없습니다.");
            return false;
        } else {
            makeAddClassErrPopup();
            $("#popup_head_msg").append(`<strong class="blue">${classTitle}</strong> 수업을 수정하시겠습니까?`);
            $("#popup_msg").append("수업코드 '" + classPrefix + "' 으로/로 " + (studentCount - classStdCount) + "개의 아이디가 추가 생성됩니다.");

            $("#lpop_submit").attr("onclick", "classEditSubmit(" + JSON.stringify(editData) + ")");
            $("#lpop_close").show();
        }

        /*
        var classList = $(_this).parent().parent()[0];
        var classIdx = $($(classList).children('td')[0]).data("idx");
        var classStdCount = $($(classList).children('td')[0]).data("std_count");

        var classTitle = $($(classList).children('td')[1]).children().val();
        var classInfo = $($(classList).children('td')[2]).children().val();
        var classTopic = $($(classList).children('td')[3]).children().val();
        var classPrefix = $($(classList).children('td')[4]).html();
        var studentCount = $($(classList).children('td')[5]).find('input').val();


        $.ajax({
            type: "POST",
            url: "/edumining/class_management/classSave",
            data: {
                classIdx : classIdx,
                classTitle : classTitle,
                classInfo : classInfo,
                classTopic : classTopic,
                classPrefix : classPrefix,
                studentCount : studentCount
            },
            dataType: "JSON",
            success: function(args){
                if(args['msg'] == 'success'){
                    alert("수정하였습니다.");
                    location.reload();
                }else{
                    alert("수정에 실패하였습니다.");
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
        */
    }

    // 수업 삭제 버튼 -> 팝업 생성
    function classDelete(_this){
        //<strong class="blue">신문기사로 학습하기</strong> 수업을 수정 하시겠습니까?
        $("#lpop_close").show();
        $("#popup_head_msg").children().remove();
        $("#popup_head_msg").text("");
        $("#popup_msg").text("");
        var classList = $(_this).parent().parent()[0];
        var className = $($(classList).children('td')[0]).data("classname");
        var classIdx = $($(classList).children('td')[0]).data("idx");

        //console.log(classList);
        var html = '<strong class="blue">';
        html += className + '</strong> 수업을 삭제하시겠습니까?';

        $("#popup_head_msg").append(html);
        $("#popup_msg").append("생성된 수업코드가 모두 삭제됩니다.");

        $("#lpop_submit").attr("onclick", "classDeleteSubmit(" + classIdx + ")")

        $(".lpopup").show();
    }

    // 수업 목록 -> 수업 -> 수업 정보 삭제버튼 클릭
    function classDeleteSubmit(classIdx){
        $.ajax({
            type: "POST",
            url: "/edumining/class_management/classDelete",
            data: {
                classIdx : classIdx,
            },
            dataType: "JSON",
            success: function(args){
                if(args['msg'] == 'success'){
                    alert("삭제하였습니다.");
                    location.reload();
                }else{
                    alert("삭제에 실패하였습니다.");
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    }

    // 수업 목록 -> 수정 -> 학생 수 증가, 감소 버튼 클릭
    function studentCountChange(_this, flag){
        var studentCount = $($(_this).parent().find('input')[0]).val();
        var classList = $(_this).parent().parent().parent()[0];
        var initCount = $($(classList).children('td')[0]).data("std_count");

        if (flag == 'up'){
            studentCount++;
        }else if (flag == 'down'){
            studentCount--;
            if(initCount > studentCount){
                makeAddClassErrPopup();
                $("#popup_head_msg").append("최초 학생 수 보다 작은 학생 수를 지정할 수 없습니다.");
                return false;
            }
        }

        $($(_this).parent().find('input')[0]).val(studentCount);
    }

    // 클래스 접두사 (학생 아이디) 중복확인
    function checkClassPrefix(){
        var classPrefix = $("#classPrefix").val();
        var checkStrVal = checkStr(classPrefix);
        makeAddClassErrPopup();
        if (classPrefix == ""){
            $("#popup_head_msg").append("수업 코드를 입력 해 주세요.");
            $("#classPrefixCheck").val('N');
            return false;
        }

        if (classPrefix.length < 4){
            $("#popup_head_msg").append("4글자 이상이 되어야 합니다.");
            $("#classPrefixCheck").val('N');
            return false;
        }

        if(checkStrVal == 1 || checkStrVal == 2){
            $("#popup_head_msg").append("한글은 입력이 불가능합니다.");
            $("#classPrefixCheck").val('N');
            return false;
        }
        else if(checkStrVal == 2){
            $("#popup_head_msg").append("공백을 포함할 수 없습니다.");
            $("#classPrefixCheck").val('N');
            return false;
        }
        else if(checkStrVal == 3){
            $("#popup_head_msg").append("'_'를 제외한 특수문자 사용이 불가능합니다..");
            $("#classPrefixCheck").val('N');
            return false;
        };

        // DB 중복확인 체크
        $.ajax({
            type: "POST",
            url: "/edumining/class_management/checkDuplicate",
            data: {text : classPrefix},
            dataType: "JSON",
            success: function(args){
                makeAddClassErrPopup();
                if(args == 1){
                    $("#popup_head_msg").append("입력하신 코드 <strong class='blue'>" + classPrefix + "</strong>는 사용할 수 없는 코드입니다.")
                    $("#popup_msg").append("다른 코드를 사용해주세요.");
                    $("#classPrefixCheck").val('N');
                    return false;
                }else{
                    $("#popup_head_msg").append("입력하신 코드 <strong class='blue'>" + classPrefix + "</strong>는 사용할 수 있는 코드입니다.")
                    $("#classPrefixCheck").val('Y');
                }
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    }


    // 확인 팝업 생성
    function makeAddClassErrPopup(){
        $("#lpop_close").show();
        $("#popup_head_msg").children().remove();
        $("#popup_head_msg").text("");
        $("#popup_msg").text("");
        $(".lpopup").show();
        $("#lpop_submit").attr("onclick", 'lpop_hide()');
        $("#lpop_close").hide();
    }

    // 수업 추가 -> 수업 생성 팝업
    function makeAddClassSubmitPopup(classTitle, classPrefix, studentCount){
        $("#lpop_close").show();
        $("#popup_head_msg").children().remove();
        $("#popup_head_msg").text("");
        $("#popup_msg").text("");
        $("#popup_head_msg").append('<strong class="blue">' + classTitle + '</strong> 수업을 등록하시겠습니까?');
        $("#popup_msg").append("입력한 수업코드 [" + classPrefix + "]로 " + studentCount + "개의 아이디가 생성됩니다.")
        $(".lpopup").show();
        $("#lpop_submit").attr("onclick", 'createClassSubmit()');
    }

    // 수업 추가 버튼
    function createClass(){
        var classPrefixCheck = $("#classPrefixCheck").val();
        var classTitle = $("#classTitle").val();
        var classInfo = $("#classInfo").val();
        var classTopic = $("#classTopic").val();
        var studentCount = $("#studentCount").val();
        var classPrefix = $("#classPrefix").val();

        if (classPrefixCheck == "N"){
            makeAddClassErrPopup();
            $("#popup_head_msg").append('수업코드 중복확인을 먼저 진행 해 주세요.');
            return false;
        }
        if (classTitle == ""){
            makeAddClassErrPopup();
            $("#popup_head_msg").append('수업명을 입력 해 주세요.');
            return false;
        }
        if (classInfo == ""){
            makeAddClassErrPopup();
            $("#popup_head_msg").append('학급 정보를 입력 해 주세요.');
            return false;
        }
        if (classTopic == ""){
            makeAddClassErrPopup();
            $("#popup_head_msg").append('수업 주제를 입력 해 주세요.');
            return false;
        }
        if (studentCount == ""){
            makeAddClassErrPopup();
            $("#popup_head_msg").append('학생 수를 입력 해 주세요.');
            return false;
        }
        if (classPrefix == ""){
            makeAddClassErrPopup();
            $("#popup_head_msg").append('학생 계정을 입력 해 주세요.');
            return false;
        }
        if (studentCount > 1000){
            makeAddClassErrPopup();
            $("#popup_head_msg").append('한 수업 당 학생수는 1000명을 넘을 수 없습니다.');
            return false;
        }

        makeAddClassSubmitPopup(classTitle, classPrefix, studentCount);
    }


    // 수업 추가 submit
    let creatingClass = false
    function createClassSubmit(){
        $("#lpop_submit").attr("onclick", undefined);
        if (creatingClass)
            return

        creatingClass = true
        var teacherID = $("#teacherID").val();
        var teacherIdx = $("#teacher_idx").val();
        var classTitle = $("#classTitle").val();
        var classInfo = $("#classInfo").val();
        var classTopic = $("#classTopic").val();
        var studentCount = $("#studentCount").val();
        var classPrefix = $("#classPrefix").val();

        $.ajax({
            type: "POST",
            url: "/edumining/class_management/createClass",
            data: {
                "teacherID": teacherID,
                "teacherIdx": teacherIdx,
                "classTitle": classTitle,
                "classInfo": classInfo,
                "classTopic": classTopic,
                "studentCount": studentCount,
                "classPrefix": classPrefix
            },
            dataType: "JSON",
            success: function (args) {
                creatingClass = false
                if (args["msg"] == "success") {
                    alert("클래스를 생성하였습니다.");
                    location.reload();
                } else {
                    alert("클래스 생성에 실패하였습니다.");
                    location.reload();
                }
            },
            error: function (xhr, status, error) {
                creatingClass = false
                alert("클래스 생성에 실패하였습니다.");
                location.reload();
            }
        });
    }


    //계정 생성 시 한글, 공백, 특수문자 포함 여부 체크
    function checkStr(text){
        var checkFlag = 0;
        var special_pattern = /[`~!@#$%^&*|\\\'\";:\/?]/gi;

        // 한글 체크
        for (var i = 0; i < text.length; i++) {
            if (escape(text.charAt(i)).length >= 4) {
                checkFlag = 1;
                return checkFlag;
            }
        }

        // 공백 체크
        if (text.search(/\s/) != -1) {
            checkFlag = 2;
        }

        // 특수문자 체크 ('_' 허용)
        if(special_pattern.test(text)){
            checkFlag = 3;
        }

        return checkFlag;
    }

    function viewStudentList(classNo) {
        localStorage.setItem("class_no", classNo)
        window.open("/management/popup_student_list", "a", "width=800, height=800, left=10, top=10");
    }
</script>