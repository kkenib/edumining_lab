<div class="contents">
	<div class="wrapper">

		<//?include __DIR__."/mypage_tab.php";?>

		<h2>비밀번호 변경</h2>
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		//echo show_alert_message(element('info', $view), '<div class="alert alert-info">', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fchangepassword', 'id' => 'fchangepassword');
		echo form_open(current_url(), $attributes);
		?>

		<table class="member_tbl" summary="개인정보 변경 테이블입니다.">
			<caption>개인정보 변경 테이블</caption>
			<tbody>
				<tr>
					<th class="w20p">아이디</th>
					<td><?php echo $this->member->item('mem_userid'); ?></td>
				</tr>
				<tr>
					<th>현재 비밀번호</th>
					<td><input type="password" name="cur_password" id="cur_password" /></td>
				</tr>
				<tr>
					<th>새 비밀번호</th>
					<td><input type="password" name="new_password" id="new_password" /><i class="tip ml10">영문, 숫자를 조합하여 6~12자이내로 입력하여 주세요</i></td>
				</tr>
				<tr>
					<th>새 비밀번호확인</th>
					<td><input type="password" name="new_password_re" id="new_password_re" /><i class="tip ml10">비밀번호를 확인해주세요</i></td>
				</tr>
			</tbody>
		</table>
		
		<div class="tac mt20">
			<button class="btn navy round mid vat" type="submit">확인</button>
			<a class="btn pink round mid " href="<?php echo site_url('mypage')?>">취소</a>
		</div>
		
		<?php echo form_close(); ?>
    </div>
</div>



<script type="text/javascript">
//<![CDATA[
$(function() {
    $('#fchangepassword').validate({
        rules: {
            cur_password : { required:true },
            new_password : { required:true, minlength:<?php echo element('password_length', $view); ?>, maxlength:12 },
            new_password_re : { required:true, minlength:<?php echo element('password_length', $view); ?>, maxlength:12, equalTo: '#new_password' }
        }
    });
});
//]]>
</script>
