<section class="visualarea sub">
	<div class="wrapper">
			<div class="subvisual_bg report">
				<div class="subvisual_box">
					<h2 class="bold">보고서 <b>목록</b></h2>
					<p>
						시각화를 활용해서 새로운 보고서를 만들어 보거나,<br>
						내가 작성한 보고서를 확인하고 보고서 제출을 완료하세요.
					</p>
				</div>
			</div>
		</div>
</section>

<div class="wrapper">
	<section class="choice_list mt80 pb80">
		<div class="grey_contbox">
			<div class="tar mb15">
				<div class="search_wrap w300">
					<input type="text" id="search_report" placeholder="제목을 입력하세요.">
					<a href="#" id="search_report_btn"><i class="fas fa-search"></i></a>
				</div>
			</div>

			<div class="wh_box tac mt15">
				<div class="tblscroll">
					<table class="tbl02" summary="보고서 목록 입니다">
						<caption>보고서 목록</caption>
						<thead>
							<tr>
								<th class="w10p">NO</th>
								<th>제목</th>
								<th class="w20p">작성일</th>
								<th class="w20p">보고서 제출</th>
							</tr>
						</thead>
						<tbody id="rpt_list">
						<!--<tbody>
							<tr>
								<td>3</td>
								<td class="tit_report"><a href="/analysis/sub_report">이상한 나라의 앨리스를 통해 분석한 에고 네트워크이상한 나라의 앨리스를 통해 분석한 에고 네트워크</a></td>
								<td>2021-12-11 16:12:32</td>
								<td>
									<button type="submit" class="btn blue" id="submit" value="1">제출하기</button>
								</td>
							</tr>
							<tr>
								<td>2</td>
								<td class="tit_report"><i class="refer">반려</i><a href="/analysis/sub_report">윤석열과 이재명 대선 행보</a></td>
								<td>2021-11-30 17:22:12</td>
								<td>
									<button type="submit" class="btn blue" id="submit" value="2">제출하기</button>
								</td>
							</tr>
							<tr>
								<td>1</td>
								<td><a href="/analysis/sub_report">다자구도를 통해 분석한 한국정치</a></td>
								<td class="tit_report">2021-11-11 10:42:17</td>
								<td>
									<span class="navy">제출완료</span>
								</td>
							</tr>
							-->
						</tbody>
					</table>
				</div>
			</div>

		</div>

		<div class="tac mt40">
			<button type="button" class="btn navy round lg" onClick="location.href='sub_report'">새 보고서 만들기</button>
		</div>

	</section>
</div>


<script type="text/javascript">
$(function(){
	getReportList();
	
	$("#search_report_btn").click(function(){
		getReportList($("#search_report").val());
	});

	$(document).on("click","#submit",function(){
		if(confirm("정말 제출하시겠습니까?")){
			submitReport($(this).attr('value'));
		}
	});
})

function submitReport(no){
	const userNo = ~~("<?php echo $this->member->item('mem_id') ?>")
	alert(userNo)
	if (userNo === 0) {
		alert("먼저 로그인을 해주세요.")
		return
	}


	var url = "/edumining/Analysis/submitReportData";
	var now = new Date(+new Date() + 3240 * 10000).toISOString().replace("T", " ").replace(/\..*/, '');
	$.ajax({
		type : "POST",
		url : url,
		data : {no : no, user_no: userNo, date : now},
		dataType : "json",
		success : function(json){
			alert(json.data.result);
			location.href = './sub_report_list';
		}
	});
}

function getReportList(search_report){
	var url = "/edumining/Analysis/reportListData";
	var html = "";
	$.ajax({
		type : "POST",
		url : url,
		data : {"search_report" : search_report},
		dataType : "json",
		success : function(json){
			lists = json.data;
			$("#rpt_list").html("");
			if (lists.length === 0) {
				$("#rpt_list").html("<tr><td colspan='4'>목록이 존재하지 않습니다.</td></tr>");	
				return
			}

			$.each(lists, function(i, v){
                const itemNo = lists.length - i
				if (v.submit_state == "0"){
					html += '<tr>';
					html += '<td>'+ itemNo +'</td>';
					html += '<td class="tit_report"><a href="/analysis/sub_report?report_no='+v.no+'">'+v.title+'</a></td>';
					html += '<td>'+v.create_date+'</td>';
					html += '<td><button type="submit" class="btn blue" id="submit" value="'+v.no+'">제출하기</button></td></tr>';
				}
				else if (v.submit_state == "1"){
					html += '<tr>';
					html += '<td>'+ itemNo +'</td>';
					html += '<td><a href="/analysis/sub_report?report_no='+v.no+'">'+v.title+'</a></td>';
					html += '<td class="tit_report">'+v.create_date+'</td>';
					html += '<td><span class="navy">제출완료</span></td></tr>';
				}
				else if (v.submit_state == "2"){
					html += '<tr>';
					html += '<td>'+ itemNo +'</td>';
					html += '<td class="tit_report"><i class="refer">반려</i><a href="/analysis/sub_report?report_no='+v.no+'">'+v.title+'</a></td>';
					html += '<td>'+v.create_date+'</td>';
					html += '<td><button type="submit" class="btn blue" id="submit" value='+v.no+'>제출하기</button></td></tr>';
				}
			});
			$("#rpt_list").html(html);
		}
	});
}

</script>