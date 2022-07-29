<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<title>에듀마이닝LMS</title>
	<link rel="stylesheet" type="text/css" href="../css/all.css">
	<link rel="stylesheet" href="../css/fontawesome_5.15.2/fontawesome.css">
	<link rel="stylesheet" href="../css/fontawesome_5.15.2/brands.css">
	<link rel="stylesheet" href="../css/fontawesome_5.15.2/solid.css">
	<link rel="stylesheet" type="text/css" href="../js/jquery-ui-1.11.4/jquery-ui.css"/>
	<script type="text/javascript" src="../js/jquery/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="../js/placeholders.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.11.4/jquery-ui.js"></script>
</head>
<body>
	<div id="wrap" class="fadein">
		<header>
			<div class="hd_left">
				<logo id="logo"><strong><em>에듀</em>마이닝</strong> 마이페이지</logo>
				<div class="util">
					<span>에듀마이닝 님 <em><i class="fas fa-sign-out-alt"></i></em></span>
				</div>
			</div>
			<div class="analysis_link">
				<a href="#"><i class="fas fa-atom"></i> 분석하기<em>GO</em></a>
			</div>
		</header>
		<div id="container">
			<gnb id="gnb" >
				<nav>
					<ul>
						<li class="on"><a href="/home/management/class_add.php"><i class="fas fa-plus-square"></i> 클래스 추가</a></li>
						<li><a href="/home/management/class_list.php"><i class="fas fa-list"></i> 클래스 목록</a></li>
						<li><a href="/home/management/task_manage.php"><i class="fas fa-calendar-check"></i> 과제 관리</a></li>
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
				<section class="ptit_area add">
					<div class="wrapper">
						<h1>클래스 추가</h1>
						<p>학생들을 다양한 클래스 별로 구성하여 더 간편하게 그리고 좀 더 효율적으로 사용 할 수 있습니다.</p>
					</div>
				</section>
				<section class="classadd_area mt60">
					<div class="wrapper">
						<div class="classadd_cont">
							<div class="ccard_area">
								<div class="class_card">
									<div class="box_tit	">
										<input type="text" placeholder="제목을 입력해 주세요.">
										<span><i class="fas fa-times"></i></span>
									</div>
									<div class="cardbox on">
										<dl>
											<dt>학기</dt>
											<dd><input type="text" placeholder="텍스트를 입력해 주세요."></dd>
											<dt>클라스</dt>
											<dd><input type="text" placeholder="텍스트를 입력해 주세요."></dd>
											<dt>학생 수</dt>
											<dd><select name="" id=""><option value="">선택해주세요.</option></select></dd>
											<dt>계정 아이디</dt>
											<dd><input type="text" placeholder="텍스트를 입력해 주세요."></dd>
											<dt>담당선생님</dt>
											<dd><input type="text" placeholder="텍스트를 입력해 주세요."></dd>
											<dt>과목명</dt>
											<dd><input type="text" placeholder="텍스트를 입력해 주세요."></dd>
											<dt>강의 시간</dt>
											<dd><select name="" id=""><option value="">선택해주세요.</option></select></dd>
										</dl>
									</div>
								</div>
								<div class="class_card">
									<div class="box_tit	">
										<input type="text"  placeholder="제목을 입력해 주세요.">
										<span><i class="fas fa-times"></i></span>
									</div>
									<div class="cardbox">
										<dl>
											<dt>학기</dt>
											<dd><input type="text" placeholder="텍스트를 입력해 주세요."></dd>
											<dt>클라스</dt>
											<dd><input type="text" placeholder="텍스트를 입력해 주세요."></dd>
											<dt>학생 수</dt>
											<dd><select name="" id=""><option value="">선택해주세요.</option></select></dd>
											<dt>계정 아이디</dt>
											<dd><input type="text" placeholder="텍스트를 입력해 주세요."></dd>
											<dt>담당선생님</dt>
											<dd><input type="text" placeholder="텍스트를 입력해 주세요."></dd>
											<dt>과목명</dt>
											<dd><input type="text" placeholder="텍스트를 입력해 주세요."></dd>
											<dt>강의 시간</dt>
											<dd><select name="" id=""><option value="">선택해주세요.</option></select></dd>
										</dl>
									</div>
								</div>
							</div>

							<div class="add_box mt30">
								<a href=""><span><i class="fas fa-plus"></i></span>Add Class</a>
							</div>
							<div class="btn_area">
								<button type="button" class="btn blue">저장</button>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
		<footer>
			<p>Copyright© 에듀마이닝. All Rights Reserved.</p>
		</footer>
	</div>
</body>
</html>