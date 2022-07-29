var _pageSize = 25;
var _blockSize = 10;
function tablePaging(tableId, pagingNum, pagingId)
{
	var viewListNum = 10;
	var firstList = (pagingNum-1)*viewListNum;
	var lastList = pagingNum*viewListNum;
	var pagingTotal;
	$("div", $("#"+tableId)).each(function(column){
		$("#"+tableId).find("div:eq("+(column+1)+")").show();
		if(!(column >= firstList && column < lastList)){
			$("#"+tableId).find("div:eq("+(column+1)+")").hide();
		}
		pagingTotal = column;
	});
	drawCustomPaging(pagingId, pagingTotal , "tablePaging", tableId, firstList, viewListNum);
	
	if(pagingTotal < viewListNum ){
		$("#"+pagingId).hide();
	}else{
		$("#"+pagingId).show();
	}

}
function toInt(str) {
	var n = null;
	try {
		n = parseInt(str, 10);	
	} catch (e) {}
	return n;
}

function drawCustomPaging(targetId, totCnt, searchFunc, tableId, offset, limit) {
	pageNo		= toInt(offset) / toInt(limit) + 1;
	pageSize	= toInt(limit);

	var totPageCnt = toInt(totCnt / pageSize) + (totCnt % pageSize > 0 ? 1 : 0);
	var totBlockCnt = toInt(totPageCnt / _blockSize) + (totPageCnt % _blockSize > 0 ? 1 : 0);
	var blockNo = toInt(pageNo / _blockSize) + (pageNo % _blockSize > 0 ? 1 : 0);
	var startPageNo = (blockNo - 1) * _blockSize + 1;
	var endPageNo = blockNo * _blockSize;

	//alert(totPageCnt + " / " + totBlockCnt + " / " + blockNo + " / " + startPageNo + " / " + endPageNo);

	if (endPageNo > totPageCnt) {
		endPageNo = totPageCnt;
	}
	var prevBlockPageNo = (blockNo - 1) * _blockSize;
	var nextBlockPageNo = blockNo * _blockSize + 1;

	var strHTML = "";
	//strHTML += "<span class=\"num\">";
	if(pageNo != 1){
	
		if (totPageCnt > 1 && pageNo != 1) {
			strHTML += "<a href='javascript:"+searchFunc+"(\""+tableId+"\",1,\""+targetId+"\");' class=\"first\"><span class='skip'></span></a>";
		} else {
			strHTML += "<a href='javascript:;' class=\"first\"><span class='skip'></span></a>";
		}
	
		//strHTML += "&nbsp;&nbsp;&nbsp;";
		if (pageNo > 1) {
			strHTML += "<a href='javascript:"+searchFunc+"(\""+tableId+"\"," + (pageNo-1) + ",\""+targetId+"\");' class=\"pre\"><i class=\"arrowl_grey\"></i>이전<span class='skip'>이전 페이지 이동</a>";
		} else {
			strHTML += "<a href='javascript:;' class=\"pre\"><i class=\"arrowl_grey\"></i>이전<span class='skip'>이전 페이지 이동</a>";
		}
		
	}
	strHTML += "<span class=\"num\">";
	for (var i = startPageNo; i <= endPageNo; i++) {
		if(i == pageNo){
			strHTML += "<strong> "+i+ "</strong>";
		}else{
			strHTML += "<a href='javascript:"+searchFunc+"(\""+tableId+"\"," + i + ",\""+targetId+"\");'>" + i + "</a>";
		}
		
		//strHTML += "&nbsp;&nbsp;&nbsp;";
	}
	strHTML += "</span>";
	if (totCnt == 0) {
		strHTML += "<a href='javascript:"+searchFunc+"(\""+tableId+"\",1,\""+targetId+"\");' >1</a>";
		//strHTML += "&nbsp;&nbsp;&nbsp;";
	}
	if(pageNo != totPageCnt){
		if (pageNo < totPageCnt) {
			strHTML += "<a href='javascript:"+searchFunc+"(\""+tableId+"\"," + (pageNo+1) + ",\""+targetId+"\");' class=\"next\">다음<i class=\"arrowr_grey\"></i><span class='skip'>다음 페이지 이동</a>";
		} else {
			strHTML += "<a href='javascript:;' class=\"next\"><span class='next'></a>";
		}
	
	//strHTML += "&nbsp;&nbsp;&nbsp;";
	
		if (totPageCnt > 1 && pageNo != totPageCnt) {
			strHTML += "<a href='javascript:"+searchFunc+"(\""+tableId+"\"," + totPageCnt + ",\""+targetId+"\");' class=\"last\"><span class='skip'>끝 페이지 이동</a>";
		} else {
			strHTML += "<a href='javascript:;' class=\"last\"><span class='skip'>끝 페이지 이동</span></a>";
		}
	}
	//strHTML += "</span>";
	$('#'+targetId).html(strHTML);
	
}