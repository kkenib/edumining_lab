<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="contents">
	<!-- S : 아이디/비밀번호찾기 -->
	<div class="box_member">
		<div class="tit_member">
			<span class="logo" title="AITOM Logo"></span>
			<p class="lh26">아이디/비밀번호는 가입시 등록한 메일 주소로 알려드립니다.<br>회원가입시 기재하신 정보를 적어주세요.</p>
		</div>

		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('error_message', $view), '<div class="alert alert-dismissible alert-warning"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message(element('success_message', $view), '<div class="alert alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		if ( ! element('error_message', $view) && ! element('success_message', $view)) {
			echo show_alert_message(element('info', $view), '<div class="alert alert-info">', '</div>');
			$attributes = array('class' => 'form-horizontal', 'name' => 'fresetpw', 'id' => 'fresetpw');
			echo form_open(current_full_url(), $attributes);
		?>
		<dl>
			<dt>아이디</dt>
			<dd>
				<input type="text" value="<?php echo element('mem_userid', $view); ?>" title="이메일 입력" placeholder="이메일을 입력하세요" readonly/>
			</dd>
			<dt>새로운 비밀번호</dt>
			<dd>
				<input type="password" name="new_password" id="new_password" title="새 비밀번호 입력" placeholder="새 비밀번호를 입력하세요"/>
			</dd>
			<dt>새로운 비밀번호 확인</dt>
			<dd>
				<input type="password" name="new_password_re" id="new_password_re" title="새 비밀번호 입력확인" placeholder="새 비밀번호를 한 번 더 입력하세요"/>
			</dd>

		</dl>
			<button type="submit" class="btn black big w100p">비밀번호 변경</button>
		<?php 
			echo form_close();
		}
		?>


		<script type="text/javascript">
		//<![CDATA[
		$(function()  {
			  $("ul.tab_idpw li:first-child.tab a").addClass("active");
			  $(".cont:not("+$("ul.tab_idpw li.tab a").attr("href")+")").hide();
			  $("ul.tab_idpw li.tab a").click(function(){
				 $("ul.tab_idpw li.tab a").removeClass("active");
				 $(this).addClass("active");
				 $(".cont").hide();
				 $($(this).attr("href")).fadeIn();
				 return false;
			  });

			$('#fresetpw').validate({
				rules: {
					new_password : { required:true, minlength:<?php echo element('password_length', $view); ?> },
					new_password_re : { required:true, minlength:<?php echo element('password_length', $view); ?>, equalTo : '#new_password' }
				}
			});
			  
		});
		//]]>
		</script>
	</div>
	<!-- E : 아이디/비밀번호찾기 -->

</div>