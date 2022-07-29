$(function() {
	$.ajaxSetup({
		contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
		headers: {'Action-Call-Type': 'AJAX'}
	});
});


// ajax 에러처리
function ajax_error(xhr, status, errorThrown) {
	if (status == "error") {
		var msg = "Sorry but there was an error: \n";
		alert(msg + xhr.status + " " + xhr.statusText);
	}
}
// ajax 에러처리
function load_error(response, status, xhr) {
	var res = response.replace(/^\s+|\s+$/gm,'');
	if (status == "error") {
		var msg = "Sorry but there was an error: \n";
		alert(msg + xhr.status + " " + xhr.statusText);
		return false;
	}
	else if(/stmt error/.test(response)) {
		alert("DB 오류가 발생하였습니다.");
		return false;
	}
	else if(response == "error") {
		alert("정상적으로 처리되지 않았습니다.");
		return false;
	}
	else if(response == "error_input") {
		alert("필요한 값이 정상적으로 입력되지 않았습니다.");
		return false;
	}
	else if(response == "error_delete") {
		alert("삭제할 수 없습니다. \n잠시후 다시 시도해 주십시오.");
		return false;
	}
	else if(response == "error_update") {
		alert("수정할 수 없습니다. \n잠시후 다시 시도해 주십시오.");
		return false;
	}
	else if(response == "error_nodata") {
		alert("선택된 데이터가 없습니다.");
		return false;
	}
	else if(response == "error_dupl") {
		alert("중복된 데이터입니다.");
		return false;
	}
	else if(response == "logout") {
		alert("로그인후 사용하세요.");
		top.document.location.href="/index.php";
		return false;
	}
	else if(response == "error_require") {
		alert("필수값이 누락되었습니다.");
		return false;
	}
	else if(response == "error_require_data") {
		alert("데이터가 삭제되었거나 찾을 수 없습니다.");
		return false;
	}
	return true;
}

// 삭제전 체크
function check_delete() {
	return confirm("정말 삭제하시겠습니까?");
}
function check_modify() {
	return confirm("수정하시겠습니까?");
}

function jq_all_check(name) {
	if(name == null) {
		name="chk[]";
	}
	name = name.replace(/\[/g, "\\[");
	name = name.replace(/\]/g, "\\]");
	$("input[type=checkbox][name=" + name + "]").prop('checked', true);

	return false;
}

function jq_un_check(name) {
	if(name == null) {
		name="chk[]";
	}
	name = name.replace(/\[/g, "\\[");
	name = name.replace(/\]/g, "\\]");
	$("input[type=checkbox][name=" + name + "]").prop('checked', false);

	return false;
}

function jq_o_check(o, name) {
	if(name == null) {
		name="chk[]";
	}
	name = name.replace(/\[/g, "\\[");
	name = name.replace(/\]/g, "\\]");

	$("input[type=checkbox][name=" + name + "]").prop('checked', $(o).is(":checked"));

	return false;
}

function jq_is_chk(name) {
	if(name == null) {
		name="chk[]";
	}
	name = name.replace(/\[/g, "\\[");
	name = name.replace(/\]/g, "\\]");

	if($("input[type=checkbox][name=" + name + "]:checked").length > 0) {
		return true;
	} else {
		alert("선택된 데이터가 없습니다.");
		return false;
	}
}

function jq_group_chk(name) {
	if(name == null) {
		name="group_chk[]";
	}
	name = name.replace(/\[/g, "\\[");
	name = name.replace(/\]/g, "\\]");

	if($("input[type=checkbox][name=" + name + "]:checked").length > 0) {
		return true;
	} else {
		alert("선택된 데이터가 없습니다.");
		return false;
	}
}

function jq_is_chk_form(form, name) {
	if(name == null) {
		name="chk[]";
	}
	name = name.replace(/\[/g, "\\[");
	name = name.replace(/\]/g, "\\]");

	if($("#" + $(form).attr("id") + " input[type=checkbox][name=" + name + "]:checked").length > 0) {
		return true;
	} else {
		alert("선택된 데이터가 없습니다.");
		return false;
	}
}

function jq_multi_delete(name, action, target) {
	if(!jq_is_chk(name)) return false;
    if(!check_delete()) return false; 

	_action_delete(action, target);
}
function jq_multi_delete_form(form, name, action, target) {
	if(!jq_is_chk_form(form, name)) return false;
    if(!check_delete()) return false; 

	_action_delete_form(form, action, target);
}
function jq_multi_delete_pop(name, action, target) {
	if(!jq_is_chk(name)) return false;
    if(!check_delete()) return false; 

	_action_delete(action, target, 'pop');
}
function jq_multi_delete_check(name) {
	if(!jq_is_chk(name)) return false;
    if(!check_delete()) return false; 
	return true;
}
function jq_multi_sms(name, mode) {
	if(!jq_is_chk(name)) return false;

	var frm = document.frm;
	frm.mode.value = mode;

	var old_action = frm.action;
	var old_method = frm.mothod;
	var old_target = frm.target;
	var old_onsubmit_func = frm.onsubmit;

	frm.action = "/mng/sms.php";
	frm.mothod = "post";
	frm.target = "_self";
	frm.onsubmit = null;
	frm.submit(); 

	frm.action = old_action;
	frm.mothod = old_method;
	frm.target = old_target;
	frm.onsubmit = old_onsubmit_func;
}

function _action_delete(action, target, pos) {	
	action_submit(action, "mdel", target, pos);
}
function _action_delete_form(form, action, target, pos) {
	action_submit_form(form, action, "mdel", target, pos);
}
function action_submit(action, mode, target, pos) {
	var frm = document.frm;
	frm.mode.value = mode;

	var old_action = frm.action;
	var old_method = frm.mothod;
	var old_target = frm.target;
	var old_onsubmit_func = frm.onsubmit;

	if(target == null) target = (pos == "pop" ? "act_pifr" : "act_ifr");
	if(action == null) action = old_action;

	frm.action = action;
	frm.mothod = "post";
	frm.target = target;
	frm.onsubmit = null;
	frm.submit(); 

	frm.action = old_action;
	frm.mothod = old_method;
	frm.target = old_target;
	frm.onsubmit = old_onsubmit_func;
}
function action_submit_form(form, action, mode, target, pos) {
	var frm = form;
	frm.mode.value = mode;

	var old_action = frm.action;
	var old_method = frm.mothod;
	var old_target = frm.target;
	var old_onsubmit_func = frm.onsubmit;

	if(target == null) target = (pos == "pop" ? "act_pifr" : "act_ifr");
	if(action == null) action = old_action;

	frm.action = action;
	frm.mothod = "post";
	frm.target = target;
	frm.onsubmit = null;
	frm.submit(); 

	frm.action = old_action;
	frm.mothod = old_method;
	frm.target = old_target;
	frm.onsubmit = old_onsubmit_func;
}



//// form check
function form_check_name(val) {
	return /^[가-힣]*$/i.test(val);
}
function form_check_phone(val) {
	val = val.split('-');
	if(val.length != 3) return false;
	if(!/^[0][0-9]{1,3}$/.test(val[0])) return false;
	if(!/^[0-9]{3,4}$/.test(val[1])) return false;
	if(!/^[0-9]{4}$/.test(val[2])) return false;
	return true;
}
function form_check_phone1(val1, val2, val3) {
	if(!/^[0][0-9]{1,3}$/.test(val1)) return false;
	if(!/^[0-9]{3,4}$/.test(val2)) return false;
	if(!/^[0-9]{4}$/.test(val3)) return false;
	return true;
}
function form_check_mobile(val) {
	val = val.replace(/[ .-]*/g, '');
	return (/^[0][1][0-9]{8,9}$/.test(val));
}
function form_check_id(val) {
	return /^[a-zA-Z][a-zA-Z0-9_-]{5,19}$/.test(val);
}

function form_check_pass(val) {
	return /^.{4,20}$/.test(val);
}
function form_check_email(v) {
	var filter = /^[a-zA-z_-]+[0-9a-zA-Z._-]+@[0-9a-zA-Z.-]+\.[a-zA-Z]{2,3}$/;
	return (filter.test(v));
}
function form_check_email1(v1, v2) {
	var filter1 = /^[a-zA-z_-]+[0-9a-zA-Z._-]+$/;
	var filter2 = /^[0-9a-zA-Z.-]+\.[a-zA-Z]{2,3}$/;
	return (filter1.test(v1) && filter2.test(v2));
}
