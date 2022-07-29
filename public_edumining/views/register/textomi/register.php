<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="contents">
	<!-- S : 회원가입 -->
	<div class="box_member">
		<div class="tit_member">
			<span class="logo" title="부산에듀빅 Logo"><img src="<?php echo base_url('views/login/images/logo.svg'); ?>" alt=""></span>
			<h1>회원가입을 환영합니다.</h1>
		</div>

		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fregisterform', 'id' => 'fregisterform');
		echo form_open(current_full_url(), $attributes);
		?>
		<input type="hidden" name="register" value="1" />

		
		<input type="checkbox" name="agree" id="agree_y" value="1">
		<label for="agree_y"><span></span>&nbsp;이용약관 동의</span> <em>(필수)</em></label>
		<div class="agree_wrap">
			<div class="agreement_box">
				<iframe class="ifr_agree" width="100%" height="180px" scrolling="auto" frameborder="0" title="개인정보취급방침" src="/register/policy1"></iframe>
			</div>
		</div>

		<input type="checkbox" name="agree2" id="agree2_y" value="1">
		<label for="agree2_y"><span></span>&nbsp;개인정보 수집 및 이용에 대한 안내</span> <em>(필수)</em></label>
		<div class="agree_wrap">
			<div class="agreement_box">
				<iframe class="ifr_agree" width="100%" height="180px" scrolling="auto" frameborder="0" title="개인정보취급방침" src="/register/policy2"></iframe>
			</div>
		</div>

<!--		<input type="checkbox" name="agree3" id="agree3_y" value="1">-->
<!--		<label for="agree3_y" style="display : none;"><span></span>&nbsp;데이터 수집 동의</span> <em>(필수)</em></label>-->
<!--		<div class="agree_wrap" style="display : none;">-->
<!--			<div class="agreement_box">-->
<!--				<iframe class="ifr_agree" width="100%" height="180px" scrolling="auto" frameborder="0" title="개인정보취급방침" src="/register/policy3"></iframe>-->
<!--			</div>-->
<!--		</div>-->

		<div class="tac">
			<button id="submit" class="btn navy round lg vat" type="submit">확인</button>
			<a class="btn pink round lg" href="javascript:history.back()">취소</a>
		</div>
		<?php echo form_close(); ?>
	</div>
	<!-- E : 회원가입 -->

</div>

<script>
	$('#submit').click(function() {
		$('#fregisterform').submit();
	});
</script>