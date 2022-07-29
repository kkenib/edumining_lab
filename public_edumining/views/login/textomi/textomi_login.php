<script>
	const message = "<?=htmlspecialchars($this->input->get_post('error', TRUE))?>"
	if (message === 'fail') {
		alert("사용자 정보를 찾을 수 없습니다.")
		location.href = "/login"
	}
</script>
<div class="contents">
	<!-- S : 로그인 -->
	<div class="box_member">
		<div class="tit_member">
			<span class="logo" title="부산에듀빅 Logo"><img src="<?php echo base_url('views/login/images/logo.svg'); ?>" alt=""></span>
			<h1>로그인을 하시면  다양한 서비스를 이용하실 수 있습니다.</h1>
			<!-- <p>수업을 진행하실 선생님께서 회원가입을 해주시면 <br>필요한 수 만큼의 학생 계정을 생성하여 전달드립니다.</p> -->
		</div>

		<?php
			echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
			echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			echo form_open(site_url('login/login_action'), array('name' => 'flogin', 'id' => 'flogin'));
		?>
             
		<input type="hidden" name="url" value="<?php echo html_escape($this->input->get_post('url')); ?>" />

		<dl>
			<dt>아이디</dt>
			<dd>
				<input type="text" name="mem_userid" id="mem_userid" title="아이디를 입력하세요." placeholder="아이디를 입력하세요." value="<?php echo set_value('mem_userid'); ?>" accesskey="L" />
			</dd>
			<dt>비밀번호</dt>
			<dd>
				<input type="password" name="mem_password" id="mem_password" title="비밀번호를 입력하세요." placeholder="비밀번호를 입력하세요."/>
			</dd>
		</dl>
		
		<input type="checkbox" name="save_login" id="save_login" value="Y" onclick="save_id()">
		<label for="save_login"><span></span>&nbsp;아이디 저장</label>

		<button class="btn pink round w100p lg mt30" type="submit">로그인</button>
		
		<div class="btn_idpw mt40">
			<a href="/findaccount" > ID/PW 찾기<i class="fas fa-arrow-right"></i></a>
			<a href="/register" >회원가입하기<i class="fas fa-arrow-right"></i></a>
		</div>
		<?php echo form_close(); ?>
		
		
	</div>
	<!-- E : 로그인 -->

</div>

<script type="text/javascript">
	//check_save_id(document.flogin);
</script>
