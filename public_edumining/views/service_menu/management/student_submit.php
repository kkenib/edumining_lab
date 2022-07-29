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
						<li class="on"><a href="/home/management/student_submit.php"><i class="fas fa-calendar-check"></i> 제출 관리</a></li>
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
				<section class="ptit_area student">
					<div class="wrapper">
						<h1>제출 관리</h1>
						<p>나의 과제 현황 및 해야할 과제를 제출 해 보세요.</p>
					</div>
				</section>
				<section class="mt60">
					<div class="wrapper">
						<div class="cont_top">
							<h2>내 정보</h2>
						</div>
						<div class="mydata_area">
							<div class="mydata_box">
								<span><i class="fas fa-id-card"></i></span>
								<dl>
									<dt>1 - 1</dt>
									<dd>내 클래스</dd>
								</dl>
							</div>
							<div class="mydata_box">
								<span><i class="fas fa-id-card"></i></span>
								<dl>
									<dt>25</dt>
									<dd>내 번호</dd>
								</dl>
							</div>
							<div class="mydata_box">
								<span><i class="fas fa-id-card"></i></span>
								<dl>
									<dt>홍길동</dt>
									<dd>내 이름(계정)</dd>
								</dl>
							</div>
							<div class="mydata_box">
								<span><i class="fas fa-id-card"></i></span>
								<dl>
									<dt>25%</dt>
									<dd>과제 진행율(%)</dd>
								</dl>
							</div>
						</div>
					</div>
				</section>
				
				<section class="classlist_area mt60 mb60">
					<div class="wrapper">
						<div class="cont_top">
							<h2>클래스 목록</h2>
						</div>
						<div class="tbl_box">
							<table class="tbl01" summary="클래스 목록 입니다">
								<caption>클래스 목록</caption>
								<thead>
									<tr>
										<th>데이터명</th>
										<th class="w12p">분석단계</th>
										<th class="w12p">분석 명</th>
										<th class="w12p">제출 현황</th>
										<th class="w12p">제출일자</th>
										<th class="w12p">결과보기</th>
										<th class="w12p">분석 바로가기</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>도서 1</td>
										<td>데이터정제</td>
										<td>키워드 정제</td>
										<td class="txt_hpink">미제출</td>
										<td>-</td>
										<td><a href="#" class="btn_tbl pink">결과보기</a></td>
										<td><a href="#" class="btn_tbl sblue">바로가기</a></td>
									</tr>
									<tr>
										<td>도서 1</td>
										<td>데이터정제</td>
										<td>키워드 정제</td>
										<td class="txt_blue">제출</td>
										<td>2010.08.21</td>
										<td><a href="#" class="btn_tbl pink">결과보기</a></td>
										<td><a href="#" class="btn_tbl sblue">바로가기</a></td>
									</tr>
									<tr>
										<td>도서 1</td>
										<td>데이터정제</td>
										<td>키워드 정제</td>
										<td class="txt_hpink">미제출</td>
										<td>-</td>
										<td><a href="#" class="btn_tbl pink">결과보기</a></td>
										<td><a href="#" class="btn_tbl sblue">바로가기</a></td>
									</tr>
									<tr>
										<td>도서 1</td>
										<td>데이터정제</td>
										<td>키워드 정제</td>
										<td class="txt_blue">제출</td>
										<td>2010.08.21</td>
										<td><a href="#" class="btn_tbl pink">결과보기</a></td>
										<td><a href="#" class="btn_tbl sblue">바로가기</a></td>
									</tr>
									<tr>
										<td>도서 1</td>
										<td>데이터정제</td>
										<td>키워드 정제</td>
										<td class="txt_hpink">미제출</td>
										<td>-</td>
										<td><a href="#" class="btn_tbl pink">결과보기</a></td>
										<td><a href="#" class="btn_tbl sblue">바로가기</a></td>
									</tr>
								</tbody>
							</table>
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