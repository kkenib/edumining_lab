<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="contents">
	<!-- S : 회원가입 -->
	<div class="box_member">
		<div class="tit_member">
			<span class="logo" title="부산에듀마이닝 Logo"><img src="http://edumining.textom.co.kr/views/login/images/logo.svg" alt=""></span>
			<h1>회원가입을 환영합니다.</h1>
		</div>

		<form method="POST" enctype="multipart/form-data" id="fregisterChk">
		<input type="hidden" name="register" value="1" />
		</form>

		
		<input type="checkbox" name="agree" id="agree_y" value="1">
		<label for="agree_y"><span></span> 이용약관 동의</span> <em>(필수)</em></label>
		<div class="agree_wrap">
			<div class="agreement_box">
				<iframe class="ifr_agree" width="100%" height="180px" scrolling="auto" frameborder="0" title="개인정보취급방침" src="/register/policy1"></iframe>
			</div>
		</div>

		<input type="checkbox" name="agree2" id="agree2_y" value="1">
		<label for="agree2_y"><span></span> 개인정보 수집 및 이용에 대한 안내</span> <em>(필수)</em></label>
		<div class="agree_wrap">
			<div class="agreement_box">
				<iframe class="ifr_agree" width="100%" height="180px" scrolling="auto" frameborder="0" title="개인정보취급방침" src="/register/policy2"></iframe>
			</div>
		</div>

<!--		<input type="checkbox" name="agree3" id="agree3_y" value="1">-->
<!--		<label for="agree3_y"><span></span> 데이터 수집 동의</span> <em>(필수)</em></label>-->
<!--		<div class="agree_wrap">-->
<!--			<div class="agreement_box">-->
<!--				<iframe class="ifr_agree" width="100%" height="180px" scrolling="auto" frameborder="0" title="개인정보취급방침" src="/register/policy3"></iframe>-->
<!--			</div>-->
<!--		</div>-->

		<div class="tac">
			<button id="submit" class="btn navy round lg w120 vat" type="submit">확인</button>
			<a class="btn pink round lg w120" href="/main">취소</a>
		</div>
		
	</div>
	<!-- E : 회원가입 -->

</div>

<script>
	$('#submit').click(function() {
		var chk1 = $("input:checkbox[id='agree_y']").is(":checked");
		var chk2 = $("input:checkbox[id='agree2_y']").is(":checked");
		// var chk3 = $("input:checkbox[id='agree3_y']").is(":checked");
		if(chk1==false){
			alert("이용약관 동의에 동의하셔야 합니다.");
			return false;
		}
		if(chk2==false){
			alert("개인정보 수집 및 이용에 동의하셔야 합니다.");
			return false;
		}
		// if(chk3==false){
		// 	alert("데이터 수집에 동의하셔야 합니다.");
		// 	return false;
		// }

		$.ajax({
			type: 'post',
			url: '/register/register_update_agree',
			async: false,
			dataType : 'json',
			success: function (data) {
				alert('개인정보 이용 약관에 동의하였습니다.zzzz');
				location.href="/membermodify/modify";
			},
			error: function (e) {
				alert('개인정보 이용 약관 동의에 실패하였습니다.');
			}
		});
	});
</script>