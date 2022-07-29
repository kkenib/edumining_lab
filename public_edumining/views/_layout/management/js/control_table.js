/*
 * 테이블 컨트롤
 * 
 * @control_table.js Created on 2006. 2. 19.
 *
 * Copyright (c) 2005 GHLab.com All rights reserved.
 */

/*
 * 테이블 옵션
 * 옵션(속성) 설명( 괄호에 있는 값은 default 값 )
 * - 테이블 속성
 * titleRowCount(0) = 타이틀이 있을 경우 타이틀 부분의 tr 갯수
 * isUnderRow(false) = 데이터 밑에 밑줄이 있는 경우
 * deleteRowColor(#F5F5F5) = 삭제할 경우 지정 색상
 * checkBoxCellId(0) = 체크박스가 있는 td 위치
 * deletedObjName = 삭제했을 때 추가되는 오브젝트의 이름( 받을때 쓰임 )
 * sortedNumObjName = 위치 변경된 데이터만 알고자 할때(순서값) 쓰이는 오브젝트의 이름( 받을때 쓰임 )
 * varName = 객체 참조변수명( var tbl1 = new ControlTable("table1"); 에선 tbl1 이 값임 )
 * scrollLayer = 스크롤을 삽입할 경우 DIV 태그의 id 를 지정
 * 
 * - 스크롤 사용시 삽입하는 DIV 태그의 속성
 * limitRow = 갯수 제한을 걸 경우( 해당 값 이상 데이터가 존재할 경우 스크롤 생성 )
 * rowHeight = 한 row 의 높이값( 밑줄이 있을 경우 밑줄의 높이까지 더하여 지정 )
 * isAutoScroll(false) = 이동시에 스크롤을 따라게 할건지 여부
 */

/**
 * 객체 이름
 *
 * @param tableId Control 할 테이블의 id
 */
function ControlTable( tableId )
{
	// 이동 방향
	this.direction = null;

	// table id
	this.tableId = eval( tableId );

	// 타이틀 row 갯수
	this.titleRowCount = this.tableId.getAttribute("titleRowCount") ? parseInt( this.tableId.getAttribute("titleRowCount") ) : 0;

	// 변경 데이터 뒤에 라인이 있을 경우
	this.isUnderRow = this.tableId.getAttribute("isUnderRow") == "true" ? true : false;

	// 체크박스가 있는 셀의 순서
	this.checkBoxCellId = this.tableId.getAttribute("checkBoxCellId") ? parseInt( this.tableId.getAttribute("checkBoxCellId") ) : 0;

	// 총 데이터 수 추출
	this.count = this.tableId.rows.length;
	this.count -= this.titleRowCount;

	// 밑줄이 있다면 제외
	if( this.isUnderRow )
		this.count = this.count / 2;

	// 삭제 데이터 갯수
	this.deletedCount = 0;

	// 삭제시 색상 지정( 값을 지정하지 않았을 경우 #F5F5F5 로 기본 설정 )
	this.deleteRowColor = this.tableId.getAttribute("deleteRowColor") ? this.tableId.getAttribute("deleteRowColor") : "#F5F5F5";

	// 삭제시 INPUT 태그의 id
	this.deletedObjName = this.tableId.getAttribute("deletedObjName");

	// 스크롤 여부
	this.isScroll = this.tableId.getAttribute("scrollLayer") ? true : false;

	// 스크롤 출력
	if( this.isScroll )
	{
		this.scrollLayer = eval( this.tableId.getAttribute("scrollLayer") );

		// 자동으로 스크롤을 조절할 것인지...
		this.isAutoScroll = this.scrollLayer.getAttribute("isAutoScroll") == "true" ? true : false;

		this.limitRow = this.scrollLayer.getAttribute("limitRow") ? parseInt( this.scrollLayer.getAttribute("limitRow") ) : 0;

		// 갯수 제한으로 선택했을 경우
		if( this.limitRow > 0 )
		{
			this.rowHeight = this.scrollLayer.getAttribute("rowHeight") ? parseInt( this.scrollLayer.getAttribute("rowHeight") ) : 0;

			if( this.count > this.limitRow )
				this.scrollLayer.style.height = this.rowHeight * this.limitRow;
		}
	}

	// 위치 변동이 있던 객체를 저장할 ID
	this.sortedNumObjName = this.tableId.getAttribute("sortedNumObjName");

	// 테이블 정렬에 관련된 INPUT 객체 삽입
	if( this.sortedNumObjName )
	{
		var rowIndex = 0;

		for( var i = 0; i < this.count; i++ )
		{
			if( this.isUnderRow )
				rowIndex = ( i * 2 ) + this.titleRowCount
			else
				rowIndex = i + this.titleRowCount;

			var obj = this.tableId.rows[ rowIndex ];

			var sortIdObj = '<INPUT type = "hidden" name = "' + this.sortedNumObjName + '" value = "' + i + '">';

			obj.getElementsByTagName("td")[this.checkBoxCellId].innerHTML += sortIdObj;
		}
	}

	// method define
	this.move = ControlTable_move;
	this.swapRow = ControlTable_swapRow;
	this.deleteRow = ControlTable_deleteRow;
	this.undeleteRow = ControlTable_undeleteRow;

	this.getCount = ControlTable_getCount;
	this.getCheckCount = ControlTable_getCheckCount;
	this.getDeletedCount = ControlTable_getDeletedCount;

	this.getRowIndex = ControlTable_getRowIndex;
	this.getNextRowIndex = ControlTable_getNextRowIndex;
	this.getPrevRowIndex = ControlTable_getPrevRowIndex;
	this.getDeletedRowIndex = ControlTable_getDeletedRowIndex;

	this.arrange = ControlTable_arrange;
}

/**
 * row 교체
 *
 * @param x 교체 객체 rowIndex
 * @param y 교체 대상 객체 rowIndex
 */
function ControlTable_swapRow( x, y )
{
	// 교채 대상 object 추출
	var xObj = this.tableId.rows[x];
	var yObj = this.tableId.rows[y];

	// 위나 아래로 이동일 경우
	if( this.direction == 'U' || this.direction == 'D' )
	{
		xObj.swapNode( yObj );
	}
	else
	{
		var oRow = this.tableId.insertRow(y);
		
		oRow.swapNode( xObj );

		if( this.direction == 'B' )
			this.tableId.deleteRow( x );
		else
			this.tableId.deleteRow( x + 1 );
	}

	if( xObj.getElementsByTagName("td")[this.checkBoxCellId].getElementsByTagName("input")[0] )
		xObj.getElementsByTagName("td")[this.checkBoxCellId].getElementsByTagName("input")[0].checked = true;
}

/**
 * 클릭한 row 의 rowIndex 추출
 */
function ControlTable_getRowIndex()
{
	var rowLength = this.tableId.rows.length;

	for( var i = 0; i < rowLength; i++ )
	{
		var obj = this.tableId.rows[i];

		if( obj.getElementsByTagName("td")[this.checkBoxCellId].getElementsByTagName("input")[0] )
		{
			obj = obj.getElementsByTagName("td")[this.checkBoxCellId].getElementsByTagName("input")[0];

			if( obj.disabled == false )
			{
				if( obj.checked )
				{
					return i;
				}
			}
		}
	}	

	return -1;
}

/**
 * 클릭한 row 의 rowIndex 추출
 */
function ControlTable_getDeletedRowIndex()
{
	var rowLength = this.tableId.rows.length;

	for( var i = 0; i < rowLength; i++ )
	{
		var obj = this.tableId.rows[i];

		if( obj.getElementsByTagName("td")[this.checkBoxCellId].getElementsByTagName("input")[0] )
		{
			obj = obj.getElementsByTagName("td")[this.checkBoxCellId].getElementsByTagName("input")[0];

			if( obj.disabled == true )
			{
				return i;
			}
		}
	}	

	return -1;
}

/**
 * 클릭한 row 의 다음 rowIndex 추출
 */
function ControlTable_getNextRowIndex()
{
	var rowIndex = this.getRowIndex();

	if( rowIndex != -1 )
	{
		if( this.isUnderRow == true )
		{
			if( rowIndex + 2 >= this.tableId.rows.length )
				return -1;

			return rowIndex + 2;
		}
		else
		{
			if( rowIndex + 1 >= this.tableId.rows.length )
				return -1;

			return rowIndex + 1;
		}
	}

	return -1;
}

/**
 * 클릭한 row 의 이전 rowIndex 추출
 */
function ControlTable_getPrevRowIndex()
{
	var rowIndex = this.getRowIndex();

	if( rowIndex != -1 )
	{
		if( this.isUnderRow == true )
		{
			if( rowIndex - 2 < this.titleRowCount )
				return -1;

			return rowIndex - 2;
		}
		else
		{
			if( rowIndex - 1 < this.titleRowCount )
				return -1;

			return rowIndex - 1;
		}
	}

	return -1;
}

/**
 * 이동 row, 대상 row 의 인덱스를 추출하고 swapRow 호출
 *
 * @param direction 이동 방향( 맨위 T, 위 U, 아래 D, 맨아래 B )
 */
function ControlTable_move( direction )
{
	this.direction = direction;

	var rowIndex = this.getRowIndex();

	if( rowIndex == -1 )
	{
		alert("항목을 선택하여 주십시오.");
		return false;
	}
	else if( this.getCheckCount() > 1 )
	{
		alert("한개의 항목만 선택하여 주십시오.");
		return false;
	}
	else
	{
		// 자동 스크롤링 여부에 따른 스크롤 조정
		if( this.isAutoScroll )
		{
			if( direction == 'U' )
				this.scrollLayer.scrollTop = this.scrollLayer.scrollTop - this.rowHeight;
			else if( direction == 'D' )
				this.scrollLayer.scrollTop = this.scrollLayer.scrollTop + this.rowHeight;
			else if( direction == 'T' )
				this.scrollLayer.scrollTop = 0;
			else
				this.scrollLayer.scrollTop = this.scrollLayer.scrollHeight;
		}

		// 위로
		if( direction == 'U' )
		{
			var prevRowIndex = this.getPrevRowIndex();

			if( prevRowIndex != -1 )
			{
				this.swapRow( rowIndex, prevRowIndex );
			}
		}
		else if( direction == 'D' ) // 아래로
		{
			var nextRowIndex = this.getNextRowIndex();

			if( nextRowIndex != -1 )
			{
				this.swapRow( rowIndex, nextRowIndex );
			}
		}
		else if( direction == 'T' ) // 맨위로
		{
			var topRowIndex = this.titleRowCount;

			this.swapRow( rowIndex, topRowIndex );

			if (rowIndex !=1)
			{
				if( this.isUnderRow )
				this.swapRow( rowIndex, topRowIndex + 1 );
			}			
			
		}
		else if( direction == 'B' ) // 맨아래로
		{
			var bottomRowIndex = this.tableId.rows.length;

			this.swapRow( rowIndex, bottomRowIndex );
			if( this.isUnderRow )
				this.swapRow( rowIndex, bottomRowIndex );
			
		}
	}

	return false;
}

/**
 * 클릭한 row 들 삭제
 */
function ControlTable_deleteRow()
{
	// 삭제시 추가될 INPUT 태그의 id 을 지정하지 않았으면 에러
	if( !this.deletedObjName )
	{
		alert("deletedObjName 속성을 지정하여 주십시오.");
		return false;
	}

	// 생성 변수명
	this.varName = this.tableId.getAttribute("varName");

	if( !this.varName )
	{
		alert("varName 속성을 지정하여 주십시오.");
		return false;
	}

	if( this.getRowIndex == 1 )
	{
		alert("항목을 선택하여 주십시오.");
		return false;
	}
	else
	{
		while( true )
		{
			var deleteRowIndex = this.getRowIndex();

			if( deleteRowIndex == -1 )
			{
				break;
			}
			else
			{
				var deleteObj = this.tableId.rows[deleteRowIndex];

				// 이전 색상 저장
				deleteObj.setAttribute( "oldBgColor", deleteObj.bgColor );

				deleteObj.bgColor = this.deleteRowColor;

				// 더블 클릭시 이벤트 추가
				deleteObj.ondblclick = 
				function()
				{
					// 더블 클릭한 테이블의 id 추출
					var thisTableId = eval( this.parentNode.parentNode.getAttribute("id") );

					var varName = eval( thisTableId.getAttribute("varName") );

					// 해당 테이블의 rowIndex 부분을 취소시킴.
					varName.undeleteRow( this.rowIndex );
				};

				// 체크박스 체크 해제
				deleteObj.getElementsByTagName("td")[this.checkBoxCellId].getElementsByTagName("input")[0].checked = false;

				// 체크박스 disabled
				deleteObj.getElementsByTagName("td")[this.checkBoxCellId].getElementsByTagName("input")[0].disabled = true;

				var id = deleteObj.getElementsByTagName("td")[this.checkBoxCellId].getElementsByTagName("input")[1].getAttribute("value");

				var deleteIdObj = '<INPUT type = "hidden" name = "' + this.deletedObjName + '" value = "' + id + '">';
	
				deleteObj.getElementsByTagName("td")[this.checkBoxCellId].innerHTML += deleteIdObj;

				// 삭제 갯수 증가
				this.deletedCount++;
			}
		}
	}

	return false;
}

/**
 * 삭제된 Row 더블 클릭시 복구
 *
 * @param deleteRowIndex 복구할 row Index
 */
function ControlTable_undeleteRow( deleteRowIndex )
{
	var deleteObj = this.tableId.rows[deleteRowIndex];

	// 기존 색상 복구
	deleteObj.bgColor = deleteObj.getAttribute("oldBgColor");

	// 체크박스 disabled 해제
	deleteObj.getElementsByTagName("td")[this.checkBoxCellId].getElementsByTagName("input")[0].disabled = false;

	// 체크 해제
	deleteObj.getElementsByTagName("td")[this.checkBoxCellId].getElementsByTagName("input")[0].checked = false;

	// 기존 추가된 Hidden input 태그 삭제
	var inputObj = deleteObj.getElementsByTagName("td")[this.checkBoxCellId].getElementsByTagName("input");

	deleteObj.getElementsByTagName("td")[this.checkBoxCellId].removeChild( inputObj[inputObj.length-1] );

	// 더블클릭 이벤트 삭제
	deleteObj.ondblclick = null;

	// 삭제 갯수 감소
	this.deletedCount--;

	return false;
}

/**
 * 테이블의 실제 데이터 수 리턴
 * ( 타이틀, 밑줄 제외한 Row 수 )
 */
function ControlTable_getCount()
{
	return this.count;
}

/**
 * 현재 클릭한 항목의 갯수 리턴
 */
function ControlTable_getCheckCount()
{
	var checkCount = 0;
	var rowLength = this.tableId.rows.length;

	for( var i = 0; i < rowLength; i++ )
	{
		var obj = this.tableId.rows[i];

		if( obj.getElementsByTagName("td")[this.checkBoxCellId].getElementsByTagName("input")[0] )
		{
			obj = obj.getElementsByTagName("td")[this.checkBoxCellId].getElementsByTagName("input")[0];

			if( obj.checked )
			{
				checkCount++;
			}
		}
	}

	return checkCount;
}

/**
 * 삭제된 데이터 갯수 리턴
 */
function ControlTable_getDeletedCount()
{
	return this.deletedCount;
}

/**
 * 데이터 전송 전에 정렬 순위에 변동이 있는 것만 정리.
 */
function ControlTable_arrange()
{
	if( this.sortedNumObjName )
	{
		var rowIndex = 0;
		
		for( var i = 0; i < this.getCount(); i++ )
		{
			if( this.isUnderRow )
				rowIndex = ( i * 2 ) + this.titleRowCount
			else
				rowIndex = i + this.titleRowCount;

			var obj = this.tableId.rows[ rowIndex ];

			obj = obj.getElementsByTagName("td")[this.checkBoxCellId].getElementsByTagName("input")[2];

			// 순서와 변동이 없는 경우 disabled 시킴
			if( i == obj.value )
			{
				this.tableId.rows[ rowIndex ].getElementsByTagName("td")[this.checkBoxCellId].getElementsByTagName("input")[1].disabled = true;

				obj.disabled = true;
			}
			else
			{
				obj.value = i;
			}
				
		}
	}
}
ControlTable.prototype = ControlTable;