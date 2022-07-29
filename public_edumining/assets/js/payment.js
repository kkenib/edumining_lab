function virtual_pay_start() {
	var frm = document.ini;
	var senk_ok = false;
	
	if(frm.cash_input_name.value == "") {
		alert('입금자명을 입력하세요.');
	}

	send_ok = confirm('진행하시겠습니까?');
	
	if(send_ok) {
		frm.submit();
	}
}