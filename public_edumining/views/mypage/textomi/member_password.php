<div class="contents">
	<div class="wrapper">

		<h2>개인정보 수정</h2>
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('name' => 'fconfirmpassword', 'id' => 'fconfirmpassword');
		echo form_open(current_url(), $attributes);
		?>
			
		<table class="member_tbl" summary="개인정보 확인 테이블입니다.">
			<caption>개인정보 확인 테이블</caption>
			<tbody>
				<tr>
					<th class="w20p">아이디</th>
					<td><strong><?php echo $this->member->item('mem_userid'); ?></strong></td>
				</tr>
				<tr>
					<th>비밀번호</th>
					<td>
						<input type="password" id="mem_password" name="mem_password" />
						<button class="btn navy mid" type="submit">확인</button>
						<p class="mt10">회원님의 개인정보 보호를 위한 본인 확인 절차이오니, 사용하시는 비밀번호를 입력해 주세요.</p>
					</td>
				</tr>
			</tbody>
		</table>
		<?php echo form_close(); ?>

		<?include __DIR__."/mypage_tab.php";?>

	</div>
</div>


<script type="text/javascript">
//<![CDATA[
$(function() {
    $('#fconfirmpassword').validate({
        rules: {
            mem_password : { required:true, minlength:6, maxlength:16 }
        }
    });
});
//]]>
</script>
