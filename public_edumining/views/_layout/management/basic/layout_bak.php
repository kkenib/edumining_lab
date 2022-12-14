<!DOCTYPE html>
<html lang="<?php echo LANG; ?>" servicename="AITOM">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5SJVWZ5');</script>
    <!-- End Google Tag Manager -->


	<title><?php echo html_escape(element('page_title', $layout)); ?></title>
	<?php if (element('meta_description', $layout)) { ?><meta name="description" content="<?php echo html_escape(element('meta_description', $layout)); ?>"><?php } ?>
	<?php if (element('meta_keywords', $layout)) { ?><meta name="keywords" content="<?php echo html_escape(element('meta_keywords', $layout)); ?>"><?php } ?>
	<?php if (element('meta_author', $layout)) { ?><meta name="author" content="<?php echo html_escape(element('meta_author', $layout)); ?>"><?php } ?>
	<!-- <?php if (element('favicon', $layout)) { ?><link rel="shortcut icon" type="image/x-icon" href="<?php echo element('favicon', $layout); ?>" /><?php } ?> -->
	<link rel="icon" type="image/png" href="<?php echo base_url('/views/_layout/voice/images/favicon.png'); ?>">
	<?php if (element('canonical', $view)) { ?><link rel="canonical" href="<?php echo element('canonical', $view); ?>" /><?php } ?>
	<!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" /> -->
	<!-- <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" /> -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery-ui.css'); ?>" />

	<link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/css.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/common.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/all.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/main.css" />
	<!-- link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/print.css" media="print" />-->
	
	<?php echo $this->managelayout->display_css(); ?>

	<script type="text/javascript">
	// ???????????????????????? ???????????? ???????????? ??????
	var cb_url = "<?php echo trim(site_url(), '/'); ?>";
	var cb_cookie_domain = "<?php echo config_item('cookie_domain'); ?>";
	var cb_charset = "<?php echo config_item('charset'); ?>";
	var cb_time_ymd = "<?php echo cdate('Y-m-d'); ?>";
	var cb_time_ymdhis = "<?php echo cdate('Y-m-d H:i:s'); ?>";
	var layout_skin_path = "<?php echo element('layout_skin_path', $layout); ?>";
	var view_skin_path = "<?php echo element('view_skin_path', $layout); ?>";
	var is_member = "<?php echo $this->member->is_member() ? '1' : '0'; ?>";
	var is_admin = "<?php echo $this->member->is_admin(); ?>";
	var cb_admin_url = <?php echo $this->member->is_admin() === 'super' ? 'cb_url + "/' . config_item('uri_segment_admin') . '"' : '""'; ?>;
	var cb_board = "<?php echo isset($view) ? element('board_key', $view) : ''; ?>";
	var cb_board_url = <?php echo ( isset($view) && element('board_key', $view)) ? 'cb_url + "/' . config_item('uri_segment_board') . '/' . element('board_key', $view) . '"' : '""'; ?>;
	var cb_device_type = "<?php echo $this->cbconfig->get_device_type() === 'mobile' ? 'mobile' : 'desktop' ?>";
	var cb_csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";
	var cookie_prefix = "<?php echo config_item('cookie_prefix'); ?>";
	</script>
	
	<script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/jquery-ui.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/common.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.extension.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.tmpl.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/sideview.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/js.cookie.js'); ?>"></script>
	
	<!-- service modified -->
	<script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/util.js"></script>
	<script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/layout.js"></script>
	<script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/placeholders.min.js"></script>
	<script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/design.js"></script>
	
	<?php echo $this->managelayout->display_js(); ?>
    <meta name="naver-site-verification" content="0dfd2b0e8c029ff9ca0c05da9847c3764446af17" />
    <meta name="google-site-verification" content="hNZfS_47qgbPjN7IATQoSSIGQlsc8H7Qeeqfrpgpz38" />

</head>
<body <?php echo isset($view) ? element('body_script', $view) : ''; ?>>
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5SJVWZ5" height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
<?php
$title_name = element('title_name', element('data', $view));
$is_member = $this->member->is_member() ? '1' : '0';
$is_admin = $this->member->is_admin();
$member_level = $this->member->item('mem_level');
$mem_agree = $this->member->item('mem_agree');
$group_voice = $this->member->item('mem_group_voice');
$request_uri = $_SERVER['REQUEST_URI'];
if($request_uri !="/register/register_chk" && $mem_agree==0 &&$is_member==1){
	header('Location: /register/register_chk');
}
?>

<div id="wrap">
	<!-- S : header -->
	<header id="header" class="transp">
		<div class="wr_left">
			<logo id="logo"><a href="/voice"></a></logo>
			<gnb id="gnb">
				<nav>
					<div class="menu on"><a href="/voice" title="????????? ?????????" id="_nav_2">????????? ?????????</a></div>
					<div class="menu"><a href="/voice/findVoice" title="????????? ??????" id="_nav_3">????????? ??????</a></div>
				</nav>
			</gnb>
		</div>
		<div class="util">		
			<?php if($is_member) { ?>
				<span><em><?php echo html_escape($this->member->item('mem_username')); ?></em> ??? 

				<?php if($group_voice !=''){ echo "(".$group_voice.")";}else{ echo "";} ?>
				</span> <a href="<?php echo site_url('login/logout?url=' . urlencode(current_full_url())); ?>" class="btn purple_light round sm ml10"><i class="fas fa-sign-out-alt"></i> ????????????</a>
			<?php } else { ?>
				<a href="/register" class="btn ivory sm mr5">??????????????????</a>
				<a href="<?php echo site_url('login?url=' . urlencode(current_full_url())); ?>" class="btn orange sm">?????????</a>
			<?php } ?>
		</div>
		<div id="btn_bar">
			<div class="ico_bar" title="menu">
				<span></span>
				<span></span>
				<span></span>
			</div>
		</div>
		<!-- <?php if($is_member) { ?>
		<div class="pop_util">
			<div class="info">
				<span>
					<strong><?php echo html_escape($this->member->item('mem_username')); ?></strong> ???<br>
					<?php echo html_escape($this->member->item('mem_group_voice')); ?>
				</span>
				<a href="<?php echo site_url('login/logout?url=' . urlencode(current_full_url())); ?>" class="btn sm">????????????</a>
			</div>
			<ul class="list_util">
				<li>
					<a href="<?php echo site_url('mypage'); ?>"><i class="fas fa-user"></i>????????????</a>
				</li>
				<?php if ($is_admin === 'super') { ?>
				<li>
					<a href="<?php echo site_url('currentvisitor'); ?>" title="???????????????">??????????????? <span class="badge"><?php echo element('current_visitor_num', $layout); ?></span></a>
				</li>
				<li>
					<a href="<?php echo site_url(config_item('uri_segment_admin')); ?>" target="_blank" title="????????? ???????????? ??????"><i class="fas fa-cog"></i>?????????</a>
				</li>
				<?php } ?>
			</ul>
		</div>
		<?php } ?> -->
	</header>
	<!-- E : header -->
    

    <!-- S : container -->
    <div id="container">
        <?php if (isset($yield)) echo $yield; ?>
	</div>
	<!-- E : container -->


	<!-- S : footer -->
	<footer id="footer">
		<div class="wrapper">
			<div class="disinblock">
				<a href="javascript:()" class="mb10" onClick="window.open('/register/policy1_pop','policy1_pop','width=520,height=560,left=10,top=10,resizable=no,toolbar=no,menubar=no')">????????????</a><i class="line"></i><a href="javascript:()" class="mb10" onClick="window.open('/register/policy2_pop','policy2_pop','width=520,height=560,left=10,top=10,resizable=no,toolbar=no,menubar=no')">???????????? ????????????</a><i class="line"></i><a href="javascript:()" class="mb10" onClick="window.open('/register/policy3_pop','policy3_pop','width=520,height=560,left=10,top=10,resizable=no,toolbar=no,menubar=no')">????????? ????????????</a><br>
				(???)???????????????<i class="line"></i>?????? : ?????????<i class="line"></i>????????????????????? : 502-81-72988<!-- <i class="line"></i>?????????????????? : 2020-???????????????-0079 --><br>
				??????????????? ????????? ????????????1??? 35??? 17<i class="line"></i>??????????????? ?????? ?????????50, 20???
				<p class="mt15">Copyright ??? <a href="http://theimc.co.kr" target="_blank" title="??????">The IMC.</a> All Rights Reserved.</p>
			</div>
			<div class="floatr">
				<a href="/main"><i class="fas fa-home"></i> AITOM ???</a><i class="line"></i><!-- <a href="/main/main"><i class="fas fa-house-user"></i> ??????????????? ???</a><i class="line"></i> --><a href="<?php echo site_url('mypage'); ?>"><i class="fas fa-edit"></i> ????????????</a>
			</div>
		</div>
	</footer>
	<!-- E : footer -->
</div>
<script>
$(function() {
	/* ?????? ?????? ??????(?????? ??? ?????? ??? ??????) */
	var current_page = window.location.pathname.split("/");
    var cValue = getCookie("notiPopup");
	
    // ?????? ??????
    $(document).on("click", ".popup_layer_close", function() {
        var wrapperId = $(this).data("wrapper-id");
        $(".dim-layer").hide();
    });

	// ?????? ???????????? ?????? ??????
    $(document).on("click", ".popup_layer_reject", function() {
    	var wrapperId = $(this).data("wrapper-id");
    	
    	$(".dim-layer").hide();
    	setCookie(wrapperId, 1, 1);
    });

	/********************** ?????? ?????? ????????? ?????? ************************/
});

</script>
</body>
</html>