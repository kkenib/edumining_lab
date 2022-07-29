<div class="contents">
	<div class="wrapper">

		<//?include __DIR__."/mypage_tab.php";?>
		
		<h2>개인정보 수정</h2>
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('name' => 'fregisterform', 'id' => 'fregisterform');
		echo form_open_multipart(current_url(), $attributes);
		?>


		<table class="member_tbl" summary="개인정보 수정 테이블입니다.">
			<caption>개인정보 수정 테이블</caption>
			<tbody>
				<?php if ($this->member->item('mem_group') === 'kakao' || $this->member->item('mem_group') === 'naver') { ?>
				<tr>
					<th class="w20p">SNS연동 코드</th>
					<td><strong><?php echo $this->member->item('mem_userid'); ?></strong></td>
				</tr>
				<?php } else {?>
				<tr>
					<th class="w20p">아이디</th>
					<td><strong><?php echo $this->member->item('mem_userid'); ?></strong></td>
				</tr>
				<tr>
					<th>패스워드</th>
					<td><a href="<?php echo site_url('membermodify/password_modify'); ?>" class="btn grey sm" title="패스워드 변경">패스워드 변경</a></td>
				</tr>
                <tr>
                    <th>소속학교/학교코드</th>
                    <td>
                        <strong>
                            <?php echo $this->member->item('mem_school_name'); ?>
                            <?php
                                $schoolCode = $this->member->item('mem_school_code');
                                $schoolCode = $schoolCode == '' ? '' : ' / '.$schoolCode;
                                echo $schoolCode
                            ?>
                        </strong>
                    </td>
                </tr>
<!-- 				<tr> -->
<!-- 					<th>닉네임</th> -->
<!-- 					<td><input type="text" name="mem_nickname" id="mem_nickname" title="닉네임 입력" value="<?php echo $this->member->item('mem_nickname'); ?>"/></td> -->
<!-- 				</tr> -->
				<?php } ?>
				<?php foreach (element('html_content', $view) as $key => $value) { ?>
					<?php if ($this->member->item('mem_level') === '2'){?>
						<tr>
							<th><?php echo element('display_name', $value); ?></th>
							<td>
							<?php echo element('input', $value); ?>
							<?php if (element('description', $value)) { ?>
								<span class="mem_sTxt"><?php echo element('description', $value); ?></span>
							<?php } ?>
							</td>
						</tr>
					<?php } else {?>
						<?php if (element('display_name', $value) != '담당 학생 수'){?>
						<tr>
							<th><?php echo element('display_name', $value); ?></th>
							<td>
							<?php echo element('input', $value); ?>
							<?php if (element('description', $value)) { ?>
								<span class="mem_sTxt"><?php echo element('description', $value); ?></span>
							<?php } ?>
							</td>
						</tr>
						<?php } ?>
					 <?php } ?>
				<?php } ?>
			</tbody>
		</table>

		<div class="tac mt20">
			<button class="btn navy round mid vat" type="submit"><i class="fas fa-check"></i> 확인</button>
			<a class="btn pink round mid vat" href="<?php echo site_url('mypage')?>">취소</a>
		</div>

    	<?php echo form_close(); ?>
	</div>
</div>

<?php
$this->managelayout->add_css(base_url('assets/css/datepicker3.css'));
$this->managelayout->add_js('http://dmaps.daum.net/map_js_init/postcode.v2.js');
$this->managelayout->add_js(base_url('assets/js/bootstrap-datepicker.js'));
$this->managelayout->add_js(base_url('assets/js/bootstrap-datepicker.kr.js'));
?>

<script type="text/javascript">
//<![CDATA[
$('.datepicker').datepicker({
    format: 'yyyy-mm-dd',
    language: 'kr',
    autoclose: true,
    todayHighlight: true
});
$(function() {
    // jQuery.validator.addMethod("phone", function (phone_number, element) {
	// 	phone_number = phone_number.replace(/\s+/g, "");
    //     return this.optional(element) || phone_number.length > 9 &&
	// 	phone_number.match(/^0([1|7])([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/);
    // }, "010-1234-1234 형식으로 입력하세요.");

    $('#fregisterform').validate({
        onkeyup: false,
        onclick: false,
        rules: {
            mem_userid: {required :true, minlength:3, maxlength:20},
            mem_email: {required :true, email:true},
            mem_password: {required :true, minlength:6, maxlength:12, is_password_available:true},
            mem_password_re : {required: true, minlength:6, maxlength:12, equalTo : '#mem_password' },
			// mem_phone: {required: false, phone: true}
        },
        messages: {
            mem_email: '이메일 주소가 유효하지 않습니다.'
        },
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			console.log(errors);
			if (errors) {
				alert("입력한 정보에 오류가 있습니다.\n다시 한번 확인해 주세요.");
			}
		}
    });
});
//]]>
</script>
