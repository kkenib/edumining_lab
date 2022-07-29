//아이디 저장 체크
function check_save_id(frm){
	if(frm == null) {
		frm = document.loginForm;
	}

	var chk = "N";
	var id = "";
	var pw = "";

	if(frm.save_login == null) return;

	if(localStorage != null) {
		chk = localStorage.getItem("login_save_chk");
		id = localStorage.getItem("login_save_id");
		pw = localStorage.getItem("login_save_pw");
	} else {
		chk = setCookie("login_save_chk", "Y", 31536000);
		id = setCookie("login_save_id", frm.user_id.value, 31536000);
	}
	if(chk == "Y") {
		 frm.save_login.checked = true;
		 frm.user_id.value = id;
		 frm.user_pwd.value = pw;
	}
}

// 아이디저장
function save_id(frm) {
	if(frm == null) {
		frm = document.loginForm;
	}
	
	if(frm.save_login == null) return;

	if(frm.save_login.checked == true) {
		if(frm.user_id.value != "") {
			if(localStorage != null) {
				localStorage.setItem("login_save_chk", "Y");
				localStorage.setItem("login_save_id", frm.user_id.value);
				localStorage.setItem("login_save_pw", frm.user_pwd.value);
			} else {
				setCookie("login_save_chk", "Y", 31536000);
				setCookie("login_save_id", frm.user_id.value, 31536000);
			}
		}
	} else {
		if(localStorage != null) {
			localStorage.setItem("login_save_chk", "N");
			localStorage.setItem("login_save_id", "");
			localStorage.setItem("login_save_pw", "");
		} else {
			setCookie("login_save_chk", "N", 0);
			setCookie("login_save_id", "", 0);
		}
	}
}
// 로그인
function member_submit_login(frm) {
	if(frm == null) {
		frm = document.loginForm;
	}

	if($.trim(frm.user_id.value)=="") {
		alert("아이디를 입력하세요.");
		frm.user_id.focus();
		return false;
	}
	if($.trim(frm.user_pwd.value)=="") {
		alert("비밀번호를 입력하세요.");
		frm.user_pwd.focus();
		return false;
	}
	frm.submit();
	return false;
}
// 로그인 체크
function check_member_submit_login(frm) {
	if(frm == null) {
		frm = document.loginForm;
	}

	if($.trim(frm.user_id.value)=="") {
		alert("아이디를 입력하세요.");
		frm.user_id.focus();
		return false;
	}
	if($.trim(frm.user_pwd.value)=="") {
		alert("비밀번호를 입력하세요.");
		frm.user_pwd.focus();
		return false;
	}
	
	save_id(frm);
	return true;
}

// 아이디 중복 체크
function open_popup_id() {
	var frm = document.frm;
	recheck_id();
	openPopupTarget("/member/pop_id.php?sword=" + frm.id.value, "pid", 330, 230, 0, 0);
	//url, target, w, h, x, y, opt
}

// 아이디 중복 체크
function check_member_ajax_id() {
	recheck_id();
	
	var frm = document.frm;
	if($.trim(frm.id.value)=="") {
		alert("사용할 아이디를 입력하세요.");
		frm.id.focus();
		return;
	}
	if(!form_check_id(frm.id.value)) {
		alert("아이디는 최소6자이상 20자이하의 영문, 숫자, -, _\n첫자리는 반드시 영문으로 작성해주세요.");
		frm.id.focus();
		return;
	}

	$.ajax({
		type: "POST",
		url: "../member/id_check.php",
		data: {"userId" : frm.id.value, "mode" : "idChk"},
		success:function(rs){
			rs = $.trim(rs);
			if ( rs == "userIdExist" ) {
				$("#dup_id_result_str").html("<span style=\"color:#ff0000\">이미 등록된 ID입니다. 다른 ID를 입력하세요.</span>");
				frm.id_dup.value = "N";
			} else if ( rs == "userIdOK" ) {
				$("#dup_id_result_str").html("<span style=\"color:#3300ff\">사용가능한 ID입니다.</span>");
				frm.id_dup.value = "Y";
			}
		},
		error:function(e){
			alert("에러가 발생하였습니다. 잠시후 다시 시도하여 주세요.");
			rm.id_dup.value = "N";
		},
		async:false
	});	
}
// 아이디 중복 체크(팝업)
function check_member_submit_id() {
	var frm = document.frm;

	if($.trim(frm.sword.value)=="") {
		alert("사용할 아이디를 입력하세요.");
		frm.sword.focus();
		return false;
	}
	if(!form_check_id(frm.sword.value)) {
		alert("아이디는 최소6자이상 20자이하의 영문, 숫자, -, _\n첫자리는 반드시 영문으로 작성해주세요.");
		frm.sword.focus();
		return false;
	}
}
function use_id(val) {
	opener.document.frm.id.value = val;
	opener.document.frm.id_dup.value = 'Y';
	window.close();
}
function recheck_id() {
	document.frm.id_dup.value = 'N';
}

// 이메일 중복 체크 ajax
function check_member_ajax_email() {
	recheck_email();

	var frm = document.frm;
	//if($.trim(frm.email.value)=="") {
	if($.trim(frm.email2.value)=="" || $.trim(frm.email2.value)=="") {
		alert("이메일을 입력하세요.");
		frm.email1.focus();
		return;
	}

	//if(!form_check_email(frm.email.value)) {
	if(!form_check_email1(frm.email1.value, frm.email2.value)) {
		alert("이메일주소가 유효하지 않습니다.");
		frm.email.focus();
		return;
	}

	$.ajax({
		type: "POST",
		url: "/member/check_email.ajax.php",
		data: "sword="+frm.email.value,
		success:function(rs){
			rs = $.trim(rs);
			if ( rs == "userEmailExist" ) {
				$("#dup_email_result_str").html("<span style=\"color:#ff0000\">이미 등록된 이메일입니다.</span>");
				frm.email_dup.value = "N";
			} else if ( rs == "userEmailOK" ) {
				$("#dup_email_result_str").html("<span style=\"color:#3300ff\">사용 가능한 이메일입니다.</span>");
				frm.email_dup.value = "Y";
			}
		},
		error:function(e){
			alert("에러가 발생하였습니다. 잠시후 다시 시도하여 주세요.");
			rm.email_dup.value = "N";
		},
		async:false
	});	
}

function recheck_email() {
	document.frm.email_dup.value = 'N';
}

function select_email(frm, eml) {
	frm.email2.value = eml;
}
function open_popup_addr(d) {
	var frm = document.frm;
	var a_uri = "";
	if(d) { a_uri = "?de=" + d; }
	openPopupTarget("/member/pop_addr.php" + a_uri, "paddr", 450, 300, 0, 0);
}
function check_member_submit_addr() {
	var frm = document.frm;

	if($.trim(frm.sword.value)=="") {
		alert("검색할 주소를 입력하세요.");
		frm.sword.focus();
		return false;
	}
}
function open_popup_addr_new(frm) {
	new daum.Postcode({
			oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
			// 우편번호와 주소 정보를 해당 필드에 넣고, 커서를 상세주소 필드로 이동한다.
			frm.zip1.value = data.postcode1;
			frm.zip2.value = data.postcode2;
			frm.zip_new.value = data.zonecode;
			frm.add1.value = data.address;
			frm.add2.focus();
		}
	}).open();
}

/*
function use_addr(d, zip1, zip2, addr1)
{
	if(!d) {
		opener.document.frm.zip1.value = zip1;
		opener.document.frm.zip2.value = zip2;
		opener.document.frm.add1.value = addr1;
		opener.document.frm.add2.value = "";
		opener.document.frm.add2.focus();
	} else {
		$(opener.document).find("input[name=" + d + "_zip1]").val(zip1);
		$(opener.document).find("input[name=" + d + "_zip2]").val(zip2);
		$(opener.document).find("input[name=" + d + "_addr1]").val(addr1);
		$(opener.document).find("input[name=" + d + "_addr2]").val("");
		$(opener.document).find("input[name=" + d + "_addr2]").focus();
	}
    window.close();
}
*/

function agree_all() {
	$("#agree1").prop('checked', true);
	$("#agree2").prop('checked', true);
}

// 회원가입 step1
function check_member_submit_join1(frm) {
	if(frm == null) frm = document.frm;

	if(!frm.agree1.checked) {
		alert("이용약관에 동의하지 않으셨습니다.");
		frm.agree1.focus();
		return;
	}
	if(!frm.agree2.checked) {
		alert("개인정보보호정책에 동의하지 않으셨습니다.");
		frm.agree2.focus();
		return;
	}
	/*
	// 실명인증
	if(isNCheck == true) {
		open_namecheck();
		return false;
	} else {
		if($.trim(frm.name.value)=="") {
			alert("이름을 입력하세요.");
			frm.name.focus();
			return false;
		}
		if(!form_check_name(frm.name.value)) {
			alert("이름은 한글로 띄어쓰기 없이 입력하세요.");
			frm.name.focus();
			return false;
		}
	}
	*/

	frm.submit();
}

// 개인 회원가입 step2 chk
function check_member_submit_join2(frm) {
	if(frm == null) frm = document.frm;

	if($.trim(frm.name.value)=="") {
		alert("성명을 입력하세요.");
		frm.name.focus();
		return false;
	}
	
	if($.trim(frm.id.value)=="") {
		alert("아이디를 입력하세요.");
		frm.id.focus();
		return false;
	}
	if(!form_check_id(frm.id.value)) {
		alert("아이디는 최소6자이상 20자이하의 영문, 숫자, -, _\n첫자리는 반드시 영문으로 작성해주세요.");
		frm.id.focus();
		return false;
	}
	if($.trim(frm.id_dup.value)!="Y") {
		alert("아이디 중복확인을 하세요.");
		frm.id.focus();
		return false;
	}	

	if($.trim(frm.password.value)=="") {
		alert("비밀번호를 입력하세요.");
		frm.password.focus();
		return false;
	}
	
	if($.trim(frm.password.value)=="") {
		alert("비밀번호를 입력하세요.");
		frm.password.focus();
		return false;
	}
	if(!form_check_pass(frm.password.value)) {
		alert("비밀번호는 최소4자이상 20자이하의 영문, 숫자, 특수문자로 작성해주세요.");
		frm.password.focus();
		return false;
	}
	if($.trim(frm.pass_old.value) == $.trim(frm.password.value)) {
		alert("현재 비밀번호와 동일한 비밀번호는 사용할 수 없습니다.");
		frm.password.focus();
		return false;
	}

	if($.trim(frm.password.value)!=$.trim(frm.password1.value)) {
		alert("비밀번호와 비밀번호확인이 일치하지 않습니다.");
		frm.password1.focus();
		return false;
	}
	if($.trim(frm.password.value) == mem_id) {
		alert("아이디와 동일한 비밀번호는 사용할 수 없습니다.");
		frm.password.focus();
		return false;
	}

	/*
	if(!form_check_phone1(frm.tel1.value, frm.tel2.value, frm.tel3.value)) {
		alert("전화번호가 유효하지 않습니다.");
		frm.tel1.focus();
		return false;
	}
	*/
	if(!form_check_phone1(frm.phone1.value, frm.phone2.value, frm.phone3.value)) {
		alert("휴대전화번호가 유효하지 않습니다.");
		frm.phone1.focus();
		return false;
	}

	//if(!form_check_email(frm.email.value)) {
	if(!form_check_email1(frm.email1.value, frm.email2.value)) {
		alert("메일주소가 유효하지 않습니다.");
		frm.email1.focus();
		return false;
	}
	/*
	if($.trim(frm.email_dup.value) != "Y") {
		alert("이메일 중복확인을 하세요.");
		frm.email.focus();
		return false;
	}
	*/
	//if($.trim(frm.zip.value)=="" || $.trim(frm.add1.value)=="") {
	if($.trim(frm.zip1.value)=="" || $.trim(frm.zip2.value)=="" || $.trim(frm.add1.value)=="") {
		alert("주소를 입력하세요.");
		frm.add1.focus();
		return false;
	}

	// 가입목적
	if($.trim(frm.purpose.value)=="") {
		alert("가입 목적을 입력하세요.");
		frm.purpose.focus();
		return false;
	}

	if(!confirm("입력하신 정보로 회원가입을 진행하시겠습니까.?")) {
		return false;
	}
	
	frm.target = "act_ifr";
	frm.action = "/member/member.proc.php";
	frm.submit();
}

// 회원 정보 수정
function check_member_submit_modify(frm) {
	if(frm == null) frm = document.frm;

	if($.trim(frm.password.value)!="") {
		if(!form_check_pass(frm.password.value)) {
			alert("비밀번호는 최소4자이상 20자이하의 영문, 숫자, 특수문자로 작성해주세요.");
			frm.password.focus();
			return false;
		}

		if($.trim(frm.password.value)!=$.trim(frm.password1.value)) {
			alert("비밀번호와 비밀번호확인이 일치하지 않습니다.");
			frm.password1.focus();
			return false;
		}
		if($.trim(frm.password.value) == frm.user_id.value) {
			alert("아이디와 동일한 비밀번호는 사용할 수 없습니다.");
			frm.password.focus();
			return false;
		}	
	}

	if($.trim(frm.group.value) == '') {
		alert("소속을 입력하세요.");
		frm.group.focus();
		return false;
	}

	if(!form_check_phone1(frm.phone1.value, frm.phone2.value, frm.phone3.value)) {
		alert("휴대전화번호가 유효하지 않습니다.");
		frm.phone1.focus();
		return false;
	}

	//if(!form_check_email(frm.email.value)) {
	if(!form_check_email1(frm.email1.value, frm.email2.value)) {
		alert("메일주소가 유효하지 않습니다.");
		frm.email1.focus();
		return false;
	}

	//if($.trim(frm.zip.value)=="" || $.trim(frm.add1.value)=="") {
	if($.trim(frm.zip1.value)=="" || $.trim(frm.zip2.value)=="" || $.trim(frm.add1.value)=="") {
		alert("주소를 입력하세요.");
		frm.add1.focus();
		return false;
	}

	if(!confirm("회원정보를 수정하시겠습니까.?")) {
		return false;
	} else {
		return true;
	}
}

// 비밀번호 변경
function check_member_submit_modifypw(frm, mem_id) {
	if(frm == null) frm = document.frm;

	if($.trim(frm.pass_old.value)=="") {
		alert("현재 사용중인 비밀번호를 입력하세요.");
		frm.pass_old.focus();
		return false;
	}

	if($.trim(frm.password.value)=="") {
		alert("비밀번호를 입력하세요.");
		frm.password.focus();
		return false;
	}
	if(!form_check_pass(frm.password.value)) {
		alert("비밀번호는 최소4자이상 20자이하의 영문, 숫자, 특수문자로 작성해주세요.");
		frm.password.focus();
		return false;
	}
	if($.trim(frm.pass_old.value) == $.trim(frm.password.value)) {
		alert("현재 비밀번호와 동일한 비밀번호는 사용할 수 없습니다.");
		frm.password.focus();
		return false;
	}

	if($.trim(frm.password.value)!=$.trim(frm.password1.value)) {
		alert("비밀번호와 비밀번호확인이 일치하지 않습니다.");
		frm.password1.focus();
		return false;
	}
	if($.trim(frm.password.value) == mem_id) {
		alert("아이디와 동일한 비밀번호는 사용할 수 없습니다.");
		frm.password.focus();
		return false;
	}

	return true;
}
// 찾기화면에서 변경
function check_member_submit_modifypw2(frm) {
	if(frm == null) frm = document.frm;

	if(form_check_pass(frm) === false) return false;
}

// 탈퇴확인
function check_member_submit_drop(frm) {
	if(frm == null) frm = document.frm;
	
	if($.trim(frm.id.value)=="") {
		alert("아이디가 입력되지 않았습니다.\r\n\n정상적인 경로가 아닙니다. 다시확인 하여 주세요");
		history.back();
		return false;
	}	

	if($.trim(frm.password.value)=="") {
		alert("비밀번호를 입력하세요.");
		frm.password.focus();
		return false;
	}
	
	if($.trim(frm.leave_why.value)=="") {
		alert("탈퇴사유를 입력하세요.");
		frm.leave_why.focus();
		return false;
	}
	if(!confirm("탈퇴후에는 회원정보가 삭제되어 복구할수 없으며,\n일부 회원서비스가 제한됩니다.\n정말 탈퇴하시겠습니까.?")) {
		return false;
	} else {
		return true;
	}	
}

// 아이디 찾기
function check_member_submit_searchid(frm, isNCheck) {
	if(isNCheck == true) {
		open_namecheck('ID');
		return false;
	} else {
		if(frm == null) frm = document.frm;

		if($.trim(frm.name.value)=="") {
			alert("이름을 입력하세요.");
			frm.name.focus();
			return false;
		}
		if(!form_check_name(frm.name.value)) {
			alert("이름은 한글로 띄어쓰기 없이 입력하세요.");
			frm.name.focus();
			return false;
		}
		if($.trim(frm.mobile1.value)=="" || $.trim(frm.mobile2.value)=="" || $.trim(frm.mobile3.value)=="") {
			alert("휴대폰번호를 입력하세요.");
			frm.mobile1.focus();
			return false;
		}
		if(!form_check_phone1(frm.mobile1.value, frm.mobile2.value, frm.mobile3.value)) {
			alert("휴대폰번호가 유효하지 않습니다.");
			frm.mobile1.focus();
			return false;
		}
	}
}
// 비밀번호 찾기
function check_member_submit_searchpw(frm, isNCheck) {
	if(frm == null) frm = document.frm;

	if(isNCheck == true) {
		if($.trim(frm.id.value)=="") {
			alert("아이디를 입력하세요.");
			frm.id.focus();
			return false;
		}
		open_namecheck('PW');
		return false;
	} else {
		if($.trim(frm.id.value)=="") {
			alert("아이디를 입력하세요.");
			frm.id.focus();
			return false;
		}
		if($.trim(frm.name.value)=="") {
			alert("이름을 입력하세요.");
			frm.name.focus();
			return false;
		}
		if(!form_check_name(frm.name.value)) {
			alert("이름은 한글로 띄어쓰기 없이 입력하세요.");
			frm.name.focus();
			return false;
		}
		if($.trim(frm.mobile1.value)=="" || $.trim(frm.mobile2.value)=="" || $.trim(frm.mobile3.value)=="") {
			alert("휴대폰번호를 입력하세요.");
			frm.mobile1.focus();
			return false;
		}
		if(!form_check_phone1(frm.mobile1.value, frm.mobile2.value, frm.mobile3.value)) {
			alert("휴대폰번호가 유효하지 않습니다.");
			frm.mobile1.focus();
			return false;
		}
	}
}
function open_namecheck(type) {
	var popup = null;
	var frm = document.nameCheckForm;
	
	// NICE신용평가정보 안심체크
	if(frm.module.value == "NICE") {
		var version = frm.moduleVersion.value;
		
		if(frm.returnMsg.value || !frm.encData.value) {
			alert(frm.returnMsg.value ? frm.returnMsg.value : "실명인증모듈 오류입니다. 새로고침후 다시 시도해주세요.");
			return false;
		} else {
			//V1.0
			if(version == 1) {
				popup = window.open('', 'popNameCheck','width=450, height=350, toolbar=no,directories=no,scrollbars=no,resizable=no,status=no,menubar=no,top=0,left=0');
				frm.target = "popNameCheck";
				frm.action = "https://cert.namecheck.co.kr/NiceID/certnc_input.asp";
			}
			//V2.0
			else if(version == 2) {
				popup = window.open('', 'popNameCheck','width=500, height=550, toolbar=no,directories=no,scrollbars=no,resizable=no,status=no,menubar=no,top=0,left=0');
				frm.target = "popNameCheck";
				frm.action = "https://cert.namecheck.co.kr/NiceID2/certpass_input.asp";
			}
		}
	}

	if(type == "ID") {
		document.frm.mode.value = "shid";
	} else if(type == "PW") { 
		document.frm.mode.value = "shpw";
		document.frm.user_id.value = document.findPwForm.user_id.value;
	}
	
	document.nameCheckForm.submit();
	if(popup != null) popup.focus();
}