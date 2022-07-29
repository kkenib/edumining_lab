
<section class="ptit_area list">
	<div class="wrapper">
		<h1>클래스 상세보기</h1>
		<p>학생들을 다양한 클래스 별로 구성하여 더 간편하게 그리고 좀 더 효율적으로 사용 할 수 있습니다.</p>
	</div>
</section>
<section class="classedit_area mt60">
	<div class="wrapper">
		<div class="cont_top">
			<h2>클래스 상세정보</h2>
		</div>
		<div class="cardbox on mb60">
			<dl class="card_list">
				<dt>학기</dt>
				<dd><input type="text" placeholder="텍스트를 입력해 주세요."></dd>
				<dt>클라스</dt>
				<dd><input type="text" placeholder="텍스트를 입력해 주세요."></dd>
				<dt>학생 수</dt>
				<dd><select name="" id=""><option value="">선택해주세요.</option></select></dd>
				<dt>계정 아이디</dt>
				<dd><input type="text" placeholder="텍스트를 입력해 주세요."></dd>
			</dl>
			<dl class="card_list">
				<dt>담당선생님</dt>
				<dd><input type="text" placeholder="텍스트를 입력해 주세요."></dd>
				<dt>과목명</dt>
				<dd><input type="text" placeholder="텍스트를 입력해 주세요."></dd>
				<dt>강의 시간</dt>
				<dd><select name="" id=""><option value="">선택해주세요.</option></select></dd>
			</dl>
		</div>
		<div class="cont_top">
			<h2>학생 정보</h2>
			<div class="cont_btn">
				<button type="button" class="btn_sm pink mr10">추가</button>
				<button type="button" class="btn_sm lgray_d ">삭제</button>
			</div>
		</div>
		<div class="tbl_box">
			<table class="tbl01" summary="학생 정보입니다">
				<caption>학생정보</caption>
				<thead>
					<tr>
						<th class="w10p">
							<input type="checkbox" id="chk0">
							<label for="chk0"></label>
						</th>
						<th class="w10p">NO</th>
						<th>제출내용</th>
						<th>이름(계정)</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<input type="checkbox" id="chk1">
							<label for="chk1"></label>
						</td>
						<td>1</td>
						<td>
							<input type="text" placeholder="1-1">
						</td>
						<td>
							<input type="text" placeholder="A">
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" id="chk2">
							<label for="chk2"></label>
						</td>
						<td>2</td>
						<td>
							<input type="text" placeholder="2-1">
						</td>
						<td>
							<input type="text" placeholder="B">
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" id="chk3">
							<label for="chk3"></label>
						</td>
						<td>3</td>
						<td>
							<input type="text" placeholder="3-1">
						</td>
						<td>
							<input type="text" placeholder="C">
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" id="chk4">
							<label for="chk4"></label>
						</td>
						<td>4</td>
						<td>
							<input type="text" placeholder="4-1">
						</td>
						<td>
							<input type="text" placeholder="D">
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" id="chk5">
							<label for="chk5"></label>
						</td>
						<td>5</td>
						<td>
							<input type="text" placeholder="5-1">
						</td>
						<td>
							<input type="text" placeholder="E">
						</td>
					</tr>
					<tr>
						<td class="bbr_l">
							<input type="checkbox" id="chk6">
							<label for="chk6"></label>
						</td>
						<td>6</td>
						<td>
							<input type="text" placeholder="6-1">
						</td>
						<td class="bbr_r">
							<input type="text" placeholder="F">
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="btn_area">
			<button type="button" class="btn blue mr10">저장</button>
			<button type="button" class="btn lgay">취소</button>
		</div>
	</div>
</section>