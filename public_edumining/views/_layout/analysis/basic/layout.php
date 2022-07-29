<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<title>부산에듀빅</title>
	<link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/css.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/print_report.css" media="print" />
  	<link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/js/jquery-ui-1.11.4/jquery-ui.css" />
  	<link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/js/placeholders.min.js" />
  	<link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/js/jquery-ui-1.11.4/jquery-ui.js" />

  	
	<script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.extension.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.tmpl.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/js.cookie.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.bpopup.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/design.js"></script>
	<script type="text/javascript" src="<?php echo element('layout_skin_url', $layout); ?>/js/board.js"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/analysis.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jscolor.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('views/_layout/analysis/js/sort/KeywordComponent.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('views/_layout/analysis/js/sort/KeywordComponentList.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-ui.min.js'); ?>"></script>


    <!-- amChart4 -->
	<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/plugins/wordCloud.js"></script>
	<script src="https://cdn.amcharts.com/lib/4/plugins/forceDirected.js"></script>
    <script type="text/javascript">am4core.addLicense("CH307906479");</script>

    <!-- summernote -->
    <script src="/plugin/summernote/summernote-lite.js"></script>
    <script src="/plugin/summernote/lang/summernote-ko-KR.js"></script>
    <link rel="stylesheet" href="/plugin/summernote/summernote-lite.css">

</head>
<?php
$member_level = $this->member->item('mem_level');
$is_member = $this->member->is_member() ? '1' : '0';
$mem_agree = $this->member->item('mem_agree');
$request_uri = $_SERVER['REQUEST_URI'];
if($request_uri !="/register/register_chk" && $mem_agree==0 &&$is_member==1){
    header('Location: /register/register_chk');
}
?>
<body>
<div id="wrap">
	<header id="header">
		<div class="wrapper">
			<logo id="logo">
				<a href="/">
					<img src="/views/_layout/analysis/images/logo.svg" alt="">
				</a>
			</logo>
			<gnb id="gnb">
				<ul>
                    <li>
                        <a id="menu_data_manage" href="/analysis/data_manage">데이터 관리</a>
                    </li>
					<li>
						<a id="menu_textmining" onclick="openCleaning()">텍스트 마이닝</a>
						<!-- 
						사용 안함
						<ul class="smenu">
							<li><a href="/analysis/select_rawdata">데이터 선택</a></li>
							<li><a href="/analysis/cleaning_rawdata">데이터 정제</a></li>
							<li><a href="/analysis/sub_analy01">데이터 분석</a></li>
						</ul> -->
					</li>
					<li>
						<a id="menu_report" href="/analysis/sub_report_list">프로젝트</a>
						<ul class="smenu">
							 <li><a href="/analysis/sub_proc">활동과정</a></li>
							<li><a href="/analysis/sub_report_list">보고서 목록</a></li>
							<li><a href="/analysis/sub_report">보고서 작성</a></li>
						</ul>
					</li>
					<li>
						<a id="menu_board" href="/analysis/sub_notice">게시판</a>
						<ul class="smenu">
							<li><a href="/analysis/sub_notice">공지사항</a></li>
							<li><a href="/analysis/sub_great">보고서 뽐내기</a></li>
							 <li><a href="/analysis/sub_case">수업기록</a></li>
						</ul>
					</li>
				</ul>
			</gnb>
			<div id="btn_bar">
				<div class="ico_bar" title="menu">
					<span></span>
					<span></span>
					<span></span>
				</div>
			</div>
			<div class="util">
				
				<!-- 로그인 후 -->
				<?php if($this->member->is_member()) {?>
                    <span id="bell" class="bell_info" style="display: none"><i class="fas fa-bell"></i></span>
				                    <span class="user_name">
				                        <a href="/membermodify">
				                            <?php echo $this->member->item('mem_username')?>
				                        </a> 님
				                    </span>
				                    <button class="btn lightgrey sm" onclick="logout();"><i class="fas fa-sign-out-alt"></i></button>
					 <?php if($this->member->item('mem_level') == 2){?>
					    <button class="btn pink ml10" onclick="manage();"><i class="fas fa-address-book mr5"></i> 학습관리 GO</button>
				     <?}?>

                    <?php if($this->member->item('mem_level') == 5){?>
                        <button class="btn pink ml10" onclick="admin();"><i class="fas fa-address-book mr5"></i> 관리자 GO</button>
                    <?}?>

					<!-- <span>학습관리</span>
					<span>에듀마이닝 님 <em><i class="fas fa-sign-out-alt"></i></em></span> -->
				
				<?php } else { ?>
				<!-- 로그인 전  -->
				<a href="/login">로그인</a>
				<a href="/register">회원가입</a>
				
				<?php } ?>

				<!-- 최종관리자 -->
				<!-- <span class="bell_info"><i class="fas fa-bell"></i></span>
				 <span class="user_name">
					<a href="/membermodify">
						<?php echo $this->member->item('mem_username')?>
					</a> 님
				</span>
				<button class="btn lightgrey sm" onclick="logout();"><i class="fas fa-sign-out-alt"></i></button>
				<button class="btn pink ml20" onclick="">관리자 GO</button> -->


			</div>
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
		<div class="wrapper">
			<div class="f_txt">
				<span>부산광역시교육청</span>
				<address>
					[47119] 부산광역시 부산진구 화지로 12(양정동)
					<i class="dot"></i>TEL : 051-1396, 051-860-0114
					<i class="dot"></i>E-MAIL : jeonghunoh@korea.kr
				</address>
				<em>Copyright ©부산광역시교육청 미래인재교육과. All Rights Reserved.</em>
			</div>
			<div class="f_link fs15">
				<a href="/register/policy2" target='_blank'>개인정보처리방침</a>
				<!-- <i class="dot"></i> -->
				<!-- <a href="/register/policy1" target='_blank'>이용약관</a> -->
			</div>
		</div>
	</footer>
   <!-- E : footer -->
</div>


<script>
$(function() {
    const urlArr = window.location.href.split('/')
    const pageName = urlArr[urlArr.length-1]
    if (pageName === "data_manage")
        $("#menu_data_manage").addClass("on")
    if (pageName === "select_rawdata")
        $("#menu_textmining").addClass("on")
    if (pageName === "sub_report_list" || pageName === "sub_report" || pageName === "sub_proc")
        $("#menu_report").addClass("on")
    if (pageName === "sub_notice" || pageName === "sub_great")
        $("#menu_board").addClass("on")
});

function logout(){
    location.href = "/login/logout";
}

function manage(){
    window.open("/management/class_manage")
}
function admin(){
    window.open("/admin")
}

const memLevel = "<?php echo $this->member->item('mem_level') ?>"
// if(Number(memLevel) < 2)
//     document.getElementById("menu_data_manage").style["display"] = "none"

if(Number(memLevel) === 5) {
    $.ajax({
        type: "POST",
        url: "/edumining/analysis/getWaitingReportCount",
        dataType: "JSON",
        success: function (reportCount) {
            if (reportCount > 0) {
                document.getElementById("bell").style["display"] = Number(reportCount) > 0 ? '' : "none"

                const bellStyle = document.createElement("style");
                document.querySelector(".bell_info").appendChild(bellStyle);
                bellStyle.sheet.insertRule(`.util .bell_info::after { content: "${reportCount}" }`, 0);
            }
        }
    })
}

function openCleaning() {
    localStorage.setItem("tab_type", '0')
    location.href = "/analysis/select_rawdata"
}
</script>


</body>
</html>