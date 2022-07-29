<div class="contents">
	<div class="wrapper tac">

		<?//include __DIR__."/mypage_tab.php";?>

		<h1>회원탈퇴 완료</h1>
		<p class="lh26">
			<!-- 안녕하세요 <strong><?php echo html_escape($this->member->item('mem_username')); ?></strong>님,<br> -->
			회원님의 탈퇴가 정상적으로 처리되었습니다.<br>
			그 동안 저희 사이트를 이용해주셔서 감사합니다.
		</p>
		
		<!-- <div class="mt20">
		<p class="memberbye">안녕하세요 <span class="text-primary"><?php echo html_escape($this->member->item('mem_username')); ?></span>님, <br />
				회원님의 탈퇴가 정상적으로 처리되었습니다.<br />
				 그 동안 저희 사이트를 이용해주셔서 감사합니다. </p>
		</div> -->

		<div class="tac mt20">
			<a href="<?php echo site_url('/main'); ?>" class="btn navy round lg" title="<?php echo html_escape($this->cbconfig->item('site_title'));?>"><i class="fas fa-home mr5"></i>홈으로 이동</a>
		</div>

	</div>
</div>

<script>
    $(".hd_right").css("display", "none")
</script>