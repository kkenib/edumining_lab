<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<title>부산에듀빅 학습관리시스템</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/css.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery-ui.css'); ?>" />

	<script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/placeholders.min.js"></script>
	<script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/design.js"></script>
    <script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/calendar.js"></script>

    <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-ui.min.js'); ?>"></script>
</head>
<body>
	<div id="wrap">
		<header id="header">
			<logo id="logo">
				<a href="http://edumining.textom.co.kr/management/class_manage">부산에듀빅 학습관리시스템</a>
			</logo>
			<div class="utilarea">
				<!-- <i class="fas fa-user"></i> -->
				<span><?php echo $this->member->item('mem_username')?> 님 <button id="logout" type="button"><i class="fas fa-sign-out-alt"></i></button></span>
				<div class="linkarea">
					<a href="/analysis/select_rawdata" target="_blank"><i class="fas fa-atom"></i> 분석하기<em>GO</em></a>
				</div>
			</div>
		</header>
	<!-- E : header -->


    <!-- S : container -->
		<div id="container">
			<gnb id="gnb" >
				<nav>
					<ul>
						<li class="menu"><a href="/management/class_manage"><i class="fas fa-chalkboard-teacher"></i> 수업 관리</a></li>
						<li class="menu"><a href="/management/project_manage"><i class="fas fa-clipboard-check"></i> 프로젝트 관리</a></li>
<!--						<li class="menu"><a href="/management/data_collect"><i class="fas fa-server"></i> 데이터 관리</a></li>-->
					</ul>
				</nav>
			</gnb>
			<div id="btn_bar">
				<div class="ico_bar" title="menu">
					<span></span>
					<span></span>
					<span></span>
				</div>
			</div>
			<div class="contents">
				<?php if (isset($yield)) echo $yield; ?>
			
			</div>
		</div>
	<!-- E : container -->


	<!-- S : footer -->
		<footer id="footer">
			<p>Copyright ©부산광역시교육청 미래인재교육과. All Rights Reserved.</p>
		</footer>


	<!-- E : footer -->
	</div>
<script>
$(function() {
    const urlArr = window.location.href.split('/')
    const pageName = urlArr[urlArr.length-1]
    if (pageName === "class_manage")
        $("li:eq(0)").addClass("on")
    if (pageName === "project_manage")
        $("li:eq(1)").addClass("on")
    if (pageName === "data_collect")
        $("li:eq(2)").addClass("on")
});

$("#logout").click(function(){
    location.href = "/login/logout";
})

</script>
</body>
</html>