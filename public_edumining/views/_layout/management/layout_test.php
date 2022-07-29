<!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>에듀마이닝 로그인</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/all.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/fontawesome_5.15.2/fontawesome.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/fontawesome_5.15.2/brands.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/fontawesome_5.15.2/solid.css" />
    <!-- <link rel="stylesheet" href="../css/fontawesome_5.15.2/fontawesome.css">
    <link rel="stylesheet" href="../css/fontawesome_5.15.2/brands.css">
    <link rel="stylesheet" href="../css/fontawesome_5.15.2/solid.css"> -->

    <script type="text/javascript">
    // 자바스크립트에서 사용하는 전역변수 선언
    var cb_url = "<?php echo trim(site_url(), '/'); ?>";
    var cb_cookie_domain = "<?php echo config_item('cookie_domain'); ?>";
    var cb_charset = "<?php echo config_item('charset'); ?>";
    var cb_time_ymd = "<?php echo cdate('Y-m-d'); ?>";
    var cb_time_ymdhis = "<?php echo cdate('Y-m-d H:i:s'); ?>";
    var layout_skin_path = "<?php echo element('layout_skin_path', $layout); ?>";
    var view_skin_path = "<?php echo element('view_skin_path', $layout); ?>";
    var is_member = "<?php echo $this->member->is_member() ? '1' : ''; ?>";
    var is_admin = "<?php echo $this->member->is_admin(); ?>";
    var cb_admin_url = <?php echo $this->member->is_admin() === 'super' ? 'cb_url + "/' . config_item('uri_segment_admin') . '"' : '""'; ?>;
    var cb_board = "<?php echo isset($view) ? element('board_key', $view) : ''; ?>";
    var cb_board_url = <?php echo ( isset($view) && element('board_key', $view)) ? 'cb_url + "/' . config_item('uri_segment_board') . '/' . element('board_key', $view) . '"' : '""'; ?>;
    var cb_device_type = "<?php echo $this->cbconfig->get_device_type() === 'mobile' ? 'mobile' : 'desktop' ?>";
    var cb_csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";
    var cookie_prefix = "<?php echo config_item('cookie_prefix'); ?>";
    </script>


    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery-ui.css'); ?>" />
    <script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/placeholders.min.js"></script>
    <script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/design.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.extension.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap-datepicker.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap-datepicker.kr.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/captcha.js'); ?>"></script>
</head>
<body>
<div id="wrap" class="fadein">
    <header>
        <div class="hd_left">
            <logo id="logo"><a href="http://edumining.textom.co.kr/management/class_manage"><strong>부산 <em>에듀</em>마이닝</strong> 학습관리시스템</a></logo>
            <div class="util">
                <span>에듀마이닝 님 <em><i class="fas fa-sign-out-alt"></i></em></span>
            </div>
        </div>
        <div class="analysis_link">
            <a href="http://edumining/textom.co.kr"><i class="fas fa-atom"></i> 분석하기<em>GO</em></a>
        </div>
        <div class="toggle-bt">
            <a class="menu-trigger" href="#">
                <span></span>
                <span></span>
                <span></span>
            </a>
        </div>
    </header>
    <!-- E : header -->


    <!-- S : container -->
    <div id="container">
        <?php if (isset($yield)) echo $yield; ?>
    </div>
    <!-- E : container -->


    <!-- S : footer -->
    <footer>
        <p>Copyright© 에듀마이닝. All Rights Reserved.</p>
    </footer>


    <!-- E : footer -->
</div>
<script>
    $(function() {

    });

</script>
</body>
</html>