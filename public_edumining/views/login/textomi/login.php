<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<?php
include __DIR__.'/textomi_login.php';
?>

<script type="text/javascript">
//<![CDATA[
$(function() {
    $('#flogin').validate({
        rules: {
            mem_userid : { required:true, minlength:3 },
            mem_password : { required:true, minlength:6 }
        },
        messages: {
        	mem_userid: '아이디를 입력하세요.',
        	mem_password: '비밀번호를 입력하세요.'
    	}
    });
});

$(document).on('change', "input:checkbox[name='autologin']", function() {
    if (this.checked) {
        $('.autologinalert').show(300);
    } else {
        $('.autologinalert').hide(300);
    }
});
//]]>
</script>
