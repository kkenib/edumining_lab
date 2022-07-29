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
						<li class="on"><a href="/home/management/class_list.php"><i class="fas fa-list"></i> 클래스 목록</a></li>
						<li><a href="/home/management/task_manage.php"><i class="fas fa-calendar-check"></i> 과제 관리</a></li>
					</ul>
				</nav>
			</gnb>
			<div class="contents">
				<section class="ptit_area list">
					<div class="wrapper">
						<h1>클래스 목록</h1>
						<p>학생들을 다양한 클래스 별로 구성하여 더 간편하게 그리고 좀 더 효율적으로 사용 할 수 있습니다.</p>
					</div>
				</section>
				<section class="classlist_area mt60">
					<div class="wrapper">
						<div class="cont_top">
							<h2>클래스 목록</h2>
							 <div class="cont_input">
								<span class="search_box">
									<input type="text" placeholder="공고명을 입력해 주세요.">
									<a href="#"><i class="fas fa-search"></i></a>
								</span>
								<button type="button" class="btn_sm dgray_sq">선택삭제</button>
							</div>
						</div>
						<div class="tbl_box">
							<table class="tbl01" summary="클래스 목록 입니다">
								<caption>클래스 목록</caption>
								<thead>
									<tr>
										<th class="w10p">
											<input type="checkbox" id="chk0">
											<label for="chk0"></label>
										</th>
										<th class="w10p">학기</th>
										<th>클래스</th>
										<th class="w10p">학생 수</th>
										<th class="w12p">담당 선생님</th>
										<th class="w11p">제출현황</th>
										<th class="w11p">강의시간</th>
										<th class="w12p">수정</th>
										<th class="w12p">상세보기</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<input type="checkbox" id="chk1">
											<label for="chk1"></label>
										</td>
										<td>2021-1</td>
										<td>1-3</td>
										<td>1</td>
										<td>A</td>
										<td>A</td>
										<td>1교시</td>
										<td><a href="/home/management/class_edit.php" class="btn_tbl sm pink">수정</a></td>
										<td><a href="#" class="btn_tbl sm sblue">보기</a></td>
									</tr>
									<tr>
										<td class="check_area">
											<input type="checkbox" id="chk2">
											<label for="chk2"></label>
										</td>
										<td>2021-1</td>
										<td>2-3</td>
										<td>2</td>
										<td>B</td>
										<td>B</td>
										<td>1교시</td>
										<td><a href="/home/management/class_edit.php" class="btn_tbl sm pink">수정</a></td>
										<td><a href="#" class="btn_tbl sm sblue">보기</a></td>
									</tr>
									<tr>
										<td class="check_area">
											<input type="checkbox" id="chk3">
											<label for="chk3"></label>
										</td>
										<td>2021-1</td>
										<td>3-3</td>
										<td>3</td>
										<td>C</td>
										<td>C</td>
										<td>1교시</td>
										<td><a href="/home/management/class_edit.php" class="btn_tbl sm pink">수정</a></td>
										<td><a href="#" class="btn_tbl sm sblue">보기</a></td>
									</tr>
									<tr>
										<td class="check_area">
											<input type="checkbox" id="chk4">
											<label for="chk4"></label>
										</td>
										<td>2021-1</td>
										<td>4-1</td>
										<td>11</td>
										<td>D</td>
										<td>D</td>
										<td>1교시</td>
										<td><a href="/home/management/class_edit.php" class="btn_tbl sm pink">수정</a></td>
										<td><a href="#" class="btn_tbl sm sblue">보기</a></td>
									</tr>
									<tr>
										<td>
											<input type="checkbox" id="chk5">
											<label for="chk5"></label>
										</td>
										<td>2021-1</td>
										<td>5-1</td>
										<td>10</td>
										<td>E</td>
										<td>E</td>
										<td>1교시</td>
										<td><a href="/home/management/class_edit.php" class="btn_tbl sm pink">수정</a></td>
										<td><a href="#" class="btn_tbl sm sblue">보기</a></td>
									</tr>
									<tr>
										<td class="bbr_l">
											<input type="checkbox" id="chk6">
											<label for="chk6"></label>
										</td>
										<td>2021-1</td>
										<td>6-1</td>
										<td>9</td>
										<td>F</td>
										<td>F</td>
										<td>1교시</td>
										<td><a href="/home/management/class_edit.php" class="btn_tbl sm pink">수정</a></td>
										<td class="bbr_r"><a href="#" class="btn_tbl sm sblue">보기</a></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="paging mt30 mb60">
							<div class="wr_page">
								<a href="" class="first disable" title="처음 페이지 이동"><i class="fas fa-angle-double-left"></i></a>
								<a href="" class="pre disable" title="이전 페이지 이동"><i class="fas fa-angle-left"></i></a>
								<span class="num">
									<a href=" " class="on">1</a>
									<a href=" ">2</a>
									<a href=" ">3</a>
									<a href=" ">4</a>
									<a href=" ">5</a>
								</span>
								<a href="" class="next " title="다음 페이지 이동"><i class="fas fa-angle-right"></i></a>
								<a href="" class="last " title="마지막 페이지 이동"><i class="fas fa-angle-double-right"></i></a>
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