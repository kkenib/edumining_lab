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
						<li><a href="/home/management/class_add.php"><i class="fas fa-plus-square"></i> 클래스 추가</a></li>
						<li><a href="/home/management/class_list.php"><i class="fas fa-list"></i> 클래스 목록</a></li>
						<li class="on"><a href="/home/management/task_manage.php"><i class="fas fa-calendar-check"></i> 과제 관리</a></li>
					</ul>
				</nav>
			</gnb>
			<div class="contents">
				<section class="ptit_area task">
					<div class="wrapper">
						<h1>과제 관리</h1>
						<p>학생들을 다양한 클래스 별로 구성하여 더 간편하게 그리고 좀 더 효율적으로 사용 할 수 있습니다.</p>
					</div>
				</section>
				<section class="task_list mt60">
					<div class="wrapper">
						<div class="cont_top">
							<h2>과제제출 현황</h2>
							 <div class="cont_input">
								<select name="" id="">
									<option value="" selected="selected">클래스 선택</option>
									<option value="">cooking</option>
									<option value="">software</option>
								</select>
								<span class="search_box">
									<input type="text" placeholder="공고명을 입력해 주세요.">
									<a href="#"><i class="fas fa-search"></i></a>
								</span>
								<button type="button" class="btn_sm hpink">우수과제 제출</button>
							</div>
						</div>
						<div class="tbl_box">
							<table class="tbl01" summary="과제 관리입니다.">
								<caption>과제 관리</caption>
								<thead>
									<tr>
										<th class="w8p">NO</th>
										<th class="w8p">클래스</th>
										<th class="w9p">이름(계정)</th>
										<th class="w9p">진행율(%)</th>
										<th class="w10p">제출현황</th>
										<th class="w10p">제출일자</th>
										<th >제출내용</th>
										<th class="w10p">우수과제</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td>1-3</td>
										<td>A</td>
										<td>60%</td>
										<td class="txt_hpink">미완료</td>
										<td>2010.08.21</td>
										<td class="btn_tbl_g">
											<button type="button" class="btn_tbl btn_line mr15">정제단어</button>
											<button type="button" class="btn_tbl btn_line mr15">분석결과</button>
											<button type="button" class="btn_tbl sblue mr15">워드</button>
											<button type="button" class="btn_tbl sblue mr15">에고</button>
											<button type="button" class="btn_tbl sblue">시계열</button>
										</td>
										<td class="check_area">
											<input type="checkbox" id="chk0">
											<label for="chk0"></label>
										</td>
									</tr>
									<tr>
										<td>2</td>
										<td>2-3</td>
										<td>B</td>
										<td>100%</td>
										<td class="txt_blue">완료</td>
										<td>2010.08.21</td>
										<td class="btn_tbl_g">
											<button type="button" class="btn_tbl sblue mr15">정제단어</button>
											<button type="button" class="btn_tbl sblue mr15">분석결과</button>
											<button type="button" class="btn_tbl sblue mr15">워드</button>
											<button type="button" class="btn_tbl sblue mr15">에고</button>
											<button type="button" class="btn_tbl sblue">시계열</button>
										</td>
										<td class="check_area">
											<input type="checkbox" id="chk1">
											<label for="chk1"></label>
										</td>
									</tr>
									<tr>
										<td>3</td>
										<td>3-3</td>
										<td>C</td>
										<td>70%</td>
										<td class="txt_hpink">미완료</td>
										<td>2010.08.21</td>
										<td class="btn_tbl_g">
											<button type="button" class="btn_tbl btn_line mr15">정제단어</button>
											<button type="button" class="btn_tbl btn_line mr15">분석결과</button>
											<button type="button" class="btn_tbl btn_line mr15">워드</button>
											<button type="button" class="btn_tbl btn_line mr15">에고</button>
											<button type="button" class="btn_tbl btn_line">시계열</button>
										</td>
										<td class="check_area">
											<input type="checkbox" id="chk2">
											<label for="chk2"></label>
										</td>
									</tr>
									<tr>
										<td>4</td>
										<td>4-1</td>
										<td>D</td>
										<td>40%</td>
										<td class="txt_hpink">미완료</td>
										<td>2010.08.21</td>
										<td class="btn_tbl_g">
											<button type="button" class="btn_tbl btn_line mr15">정제단어</button>
											<button type="button" class="btn_tbl btn_line mr15">분석결과</button>
											<button type="button" class="btn_tbl btn_line mr15">워드</button>
											<button type="button" class="btn_tbl btn_line mr15">에고</button>
											<button type="button" class="btn_tbl btn_line">시계열</button>
										</td>
										<td class="check_area">
											<input type="checkbox" id="chk3">
											<label for="chk3"></label>
										</td>
									</tr>
									<tr>
										<td>5</td>
										<td>5-1</td>
										<td>E</td>
										<td>100%</td>
										<td class="txt_blue">완료</td>
										<td>2010.08.21</td>
										<td class="btn_tbl_g">
											<button type="button" class="btn_tbl sblue mr15">정제단어</button>
											<button type="button" class="btn_tbl sblue mr15">분석결과</button>
											<button type="button" class="btn_tbl sblue mr15">워드</button>
											<button type="button" class="btn_tbl sblue mr15">에고</button>
											<button type="button" class="btn_tbl sblue">시계열</button>
										</td>
										<td class="check_area">
											<input type="checkbox" id="chk4">
											<label for="chk4"></label>
										</td>
									</tr>
									<tr>
										<td class="bbr_l">6</td>
										<td>6-1</td>
										<td>F</td>
										<td>100%</td>
										<td class="txt_blue">완료</td>
										<td>2010.08.21</td>
										<td class="btn_tbl_g">
											<button type="button" class="btn_tbl sblue mr15">정제단어</button>
											<button type="button" class="btn_tbl sblue mr15">분석결과</button>
											<button type="button" class="btn_tbl sblue mr15">워드</button>
											<button type="button" class="btn_tbl sblue mr15">에고</button>
											<button type="button" class="btn_tbl sblue">시계열</button>
										</td>
										<td class="bbr_r check_area">
											<input type="checkbox" id="chk5">
											<label for="chk5"></label>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="paging mt30 mb60">
							<div class="wr_page"></div>
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