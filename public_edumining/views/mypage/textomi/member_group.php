<form id="dicEdit" name="dicEdit" action="/textomi/dicWrite" method="post" onsubmit="return false;">
	<input type="hidden" name="wd_idx" value=""/>
	<input type="hidden" name="editMode" value=""/>
</form>
	   
<div class="contents" style="min-width: 1200px;">
	<div class="wrapper">

		<?include __DIR__."/mypage_tab.php";?>

		<div>
			<h2 class="disinblock">그룹 리스트</h2>
			<div class="floatr">
				<input type="text" name="mem_group" id="mem_group" class="sm" placeholder="그룹명을 입력하세요">
				<a href="#" class="btn black sm vat" title="확인" onclick="add();"><i class="fas fa-plus mr5"></i>그룹추가</a>
			</div>
		</div>
		<div class="list_group" id="groupList">
			<span class="on">1조 <button title="삭제"><i class="fas fa-times-circle"></i></button></span>
			<span>8조 <button title="삭제"><i class="fas fa-times-circle"></i></button></span>
		</div>
		<!-- <div id="groupList"></div>
		<div>현재 선택된 그룹은 <strong id="select_group" class="red">saa</strong> 입니다. 
		<input type="hidden" name="group_idx" id="group_idx" title="그룹인덱스" value="">
		<a href="javascript:mem_group_del();" class="btn grey sm" id="btn_add"><i class="fas fa-trash-alt"></i> 그룹삭제</a>
		</div> -->
		<input type="hidden" name="group_idx" id="group_idx" title="그룹인덱스" value="">
		<input type="hidden" name="select_group" id="select_group" title="그룹인덱스" value="">
		<div class="grouparea mt40">
			<div>
				<div>
					<h2 class="disinblock">미지정 리스트</h2>
				</div>
				<div id="userList">
					<table class="tbl01 tac" summary="미지정 리스트입니다.">
						<caption>미지정 리스트</caption>
						<thead>
							<tr>
								<th><input type="checkbox" id="select_ckb_all" name=""><label for="select_ckb_all"><span></span></label></th>
								<th>아이디</th>
								<th>성명</th>
								<th>이메일</th>
								<th>전화번호</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>

			<div>
				<a href="javascript:mem_list_add();" class="btn mint sm" id="btn_add">넣기 <i class="fas fa-angle-right ml5"></i></a>
				<a href="javascript:mem_list_del();" class="btn grey sm" id="btn_delete"><i class="fas fa-angle-left mr5"></i> 빼기</a>
			</div>

			<div>
				<div>
					<h2 class="disinblock"><em id="group_name"></em> <span>(리스트 첫번째 아이디가 ‘데이터 매니저’ 역할을 수행합니다.)</span></h2>
				</div>
				<div id="selectList">
					<table class="tbl01 tac" summary="그룹 학생 리스트입니다.">
						<caption>그룹 학생 리스트</caption>
						<thead>
							<tr>
								<th><input type="checkbox" id="select_user_all" name=""><label for="select_user_all"><span></span></label></th>
								<th>아이디</th>
								<th>이름</th>
								<th>이메일</th>
								<th>전화번호</th>
								<!-- <th>데이터 매니저</th> -->
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>

		</div>

	</div>
</div>

<script type="text/javascript">

$(document).ready(function() {
	group_list();
	user_list();
	$("#select_ckb_all").on('click',function() {
		if($("#select_ckb_all").prop("checked")){
			$("input:checkbox[name='storage_chk[]']").prop("checked", true);
		}else{
			$("input:checkbox[name='storage_chk[]']").prop("checked", false);
		}
	});

	$("#select_user_all").on('click',function() {
		if($("#select_user_all").prop("checked")){
			$("input:checkbox[name='storage_user[]']").prop("checked", true);
		}else{
			$("input:checkbox[name='storage_user[]']").prop("checked", false);
		}
	});
});


//그룹 지정되지 않은 사용자 리스트
function user_list()
{
	$.ajax({
		type: 'post',
		url: '/mypage/memListUser',
		async: false,
		dataType : 'json',
		success : function(data) {
			var html = "";		
			$.each(data, function(i, e){	
				html += '<tr>';
				html += '<td><a href="#" id="focus_'+e.mem_id+'"></a><input type="checkbox" id="'+e.mem_id+'" name="storage_chk[]" value="'+e.mem_id+'"><label for="'+e.mem_id+'"><span></span></label></td>';	
				html += '<td>'+e.mem_userid+'</td>';	
				html += '<td>'+e.mem_username+'</td>';
				html += '<td>'+e.mem_email+'</td>';
				html += '<td>'+e.mem_phone+'</td>';
				html += '</tr>';
			});
			
			$("#userList > table > tbody").empty();
			$("#userList > table > tbody").html(html);
		}
	});	
	return false;
}

//그룹 리스트
function group_list()
{
	var tmp_group ="";
	var tmp_idx ="";
	$.ajax({
		type: 'post',
		url: '/mypage/memListGroup',
		async: false,
		dataType : 'json',
		success : function(data) {
			var html = "";
			var cnt = 0;
			$.each(data, function(i, e){
				if(cnt ==0){
					tmp_group = e.group_name;
					$("#group_idx").val(e.group_idx);
					$("#group_name").text(e.group_name);
					html += '<span class="on" onclick="mem_list(\''+e.group_name+'\',\''+e.group_idx+'\');return false;" id="group_num'+e.group_idx+'">'+e.group_name+' <button title="삭제" onclick="mem_group_del(\''+e.group_name+'\',\''+e.group_idx+'\');"><i class="fas fa-times-circle"></i></button></span>';
				}else{
					html += '<span onclick="mem_list(\''+e.group_name+'\',\''+e.group_idx+'\');return false;" id="group_num'+e.group_idx+'">'+e.group_name+' <button title="삭제" onclick="mem_group_del(\''+e.group_name+'\',\''+e.group_idx+'\');"><i class="fas fa-times-circle"></i></button></span>'
					//html += '<a href="#" class="btn_Sgray" onclick="mem_list(\''+e.group_name+'\',\''+e.group_idx+'\');">'+e.group_name+'</a> &nbsp;&nbsp;&nbsp;';
				}
				cnt +=1
				
			});
			$("#groupList").empty();
			$("#groupList").html(html);
		}
	});
	$("#select_group").val(tmp_group);
	mem_list(tmp_group,$("#group_idx").val());
	return false;
}

//중복 그룹 체크
function memGroupChk(group_name)
{
	var group_var; 
	$.ajax({
		type: 'post',
		url: '/mypage/memGroupChk',
		data : {
			group_name: group_name
		},
		async: false,
		dataType : 'json',
		success : function(data) {
			if(data.length > 0){
				group_var = false;
			}else{
				group_var = true;
			}
		}
	});
	return group_var;
}

//그룹 추가
function add()
{
	var group_name = $("#mem_group").val();
	if (group_name =="")
	{
		alert("그룹 이름을 입력하세요.");
		return false;
	}
	if(memGroupChk(group_name)==false){
		alert("이미 존재하는 그룹입니다.");
		return false;
	}
	$.ajax({
		type: 'post',
		url: '/mypage/memCreateGroup',
		data : {
			group_name: group_name
		},
		async: false,
		dataType : 'json',
		success : function(data) {
			location.reload();
			/*
			var html = "";
			$.each(data, function(i, e){
				html += '<a href="#" class="btn_Sgray" onclick="mem_list(\''+e.group_name+'\',\''+e.group_idx+'\');">'+e.group_name+'</a>&nbsp;&nbsp;&nbsp;';
			});
			$("#groupList").empty();
			$("#groupList").html(html);
			*/
		}
	});	
	return false;
}

//그룹 지정된 사용자 리스트
function mem_list(name, idx)
{
	if(name ==""){
		return false;
	}
	$("#groupList > span").removeClass();
	$("#group_num"+idx).addClass("on");
	$("#group_name").text(name);
	$("#select_group").val(name);
	$("#group_idx").val(idx);
	$.ajax({
		type: 'post',
		url: '/mypage/memGroupList',
		data : {
			group_name: name
		},
		async: false,
		dataType : 'json',
		success : function(data) {
			var html = "";		
			$.each(data, function(i, e){	
				html += '<tr>';
				html += '<td><a href="#" id="focus_'+e.mem_id+'"></a><input type="checkbox" id="'+e.mem_id+'" name="storage_user[]" value="'+e.mem_id+'"><label for="'+e.mem_id+'"><span></span></label></td>';
				if(e.mem_group_top==e.mem_id){
					html += '<td>'+e.mem_userid+'<br><i>데이터 매니저</i></td>';
				}else{
					html += '<td>'+e.mem_userid+'</td>';
				}
				html += '<td>'+e.mem_username+'</td>';
				html += '<td>'+e.mem_email+'</td>';
				html += '<td>'+e.mem_phone+'</td>';
				html += '</tr>';
			});
			
			$("#selectList > table > tbody").empty();
			$("#selectList > table > tbody").html(html);
		}
	});	
	return false;	

}

//그룹에 사용자 추가
function mem_list_add()
{
	var group_name = $("#select_group").val();
	if(group_name ==""){
		alert("그룹을 추가해 주세요.!!");
		return false;
	}
	var group_idx = $("#group_idx").val();
	if($("input:checkbox[name='storage_chk[]']:checked").length == 0){
		alert("선택한 학생이 없습니다.");
		return;
	}
	if($("input:checkbox[name='storage_chk[]']:checked").length > 6){
		alert("한 그룹에 6명을 초과할수 없습니다.");
		return;
	}
	var checkboxValues = [];
    $("input[name='storage_chk[]']:checked").each(function(i) {
        checkboxValues.push($(this).val());
    });
	$("#select_ckb_all").prop("checked",false);
	$.ajax({
		type: 'post',
		url: '/mypage/memGroupAdd',
		data : {
			group_name: group_name,
			group_idx: group_idx,
			checkboxValues : checkboxValues,
			chk_stat : "add"
		},
		async: false,
		dataType : 'json',
		success : function(data) {
			alert("그룹에 추가하였습니다.");
		}
	});	
	user_list();
	mem_list(group_name,$("#group_idx").val());
	
	return false;

}

//그룹에서 사용자 제거
function mem_list_del()
{
	var group_name = $("#select_group").val();
	if($("input:checkbox[name='storage_user[]']:checked").length == 0){
		alert("선택한 학생이 없습니다.");
		return;
	}
	var checkboxValues = [];
    $("input[name='storage_user[]']:checked").each(function(i) {
        checkboxValues.push($(this).val());
    });
	$("#select_user_all").prop("checked",false);
	$.ajax({
		type: 'post',
		url: '/mypage/memGroupAdd',
		data : {
			group_name: group_name,
			checkboxValues : checkboxValues,
			chk_stat : "del"
		},
		async: false,
		dataType : 'json',
		success : function(data) {
			alert("그룹에서 삭제하였습니다.");
		}
	});	
	mem_list(group_name,$("#group_idx").val());
	user_list();
	return false;
}

//그룹 삭제
function mem_group_del(group_name,group_idx)
{
	if(confirm("삭제하시겠습니까?")){
		$.ajax({
			type: 'post',
			url: '/mypage/memGroupDelete',
			data : {
				group_name: group_name,
				group_idx : group_idx
			},
			async: false,
			dataType : 'json',
			success : function(data) {
				alert("그룹에서 삭제하였습니다.");
				location.reload();
			}
		});	
	}

	return false;
}

</script>