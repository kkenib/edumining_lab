
<div class="contents">
	<div class="box_member">
		<div class="tit_member">
			<span class="logo" title="AITOM Logo"></span>
		</div>
		<div class="tac">
			<h2>이메일 인증</h2>
			<div class="msg_content">
                <?php echo element('message', $view); ?>
                <p class="btn_final">
                    <a href="<?php echo site_url(); ?>" class="btn btn-danger" title="<?php echo html_escape($this->cbconfig->item('site_title'));?>">홈페이지로 이동</a>
                </p>
            </div>
		</div>
	</div>
</div>




<!-- <h3>이메일 인증</h3>

<div class="final">
    <div class="table-box">
        <div class="table-heading">이메일 인증</div>
        <div class="table-body verifyemail">
            <div class="msg_content">
                <?php echo element('message', $view); ?>
                <p class="btn_final">
                    <a href="<?php echo site_url(); ?>" class="btn btn-danger" title="<?php echo html_escape($this->cbconfig->item('site_title'));?>">홈페이지로 이동</a>
                </p>
            </div>
        </div>
    </div>
</div> -->
