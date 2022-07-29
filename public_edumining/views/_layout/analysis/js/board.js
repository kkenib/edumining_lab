/***
 * Requirement: jQuery
 * Created By ZHKim
 * Created At 2022.01.
 * Description: This is Board Object. Using it, implement easily the board.
 * Specification
 *  1. View Article List.
 *  2. Search, Write, Modify, Remove Article.
 *  3. Page Navigation: Last, Prev, Next, Last, Pick Page Number
 * **/

function Board() {}
Board.prototype.pageNo = 1
Board.prototype.lastPageNo = 1
Board.prototype.searchedKeyword = ''
Board.prototype.pageNoStyle = ''
Board.prototype.editWindowUrl = ''
Board.prototype.viewArticleUrl = ''
Board.prototype.getterUrl = ''
Board.prototype.removeUrl = ''
Board.prototype.setPageNo = function(pno) {this.pageNo = pno}
Board.prototype.getPageNo = function() { return this.pageNo }
Board.prototype.setLastPageNo = function(pno) {this.lastPageNo = pno}
Board.prototype.getLastPageNo = function() { return this.lastPageNo }
Board.prototype.setSearchKeyword = function(searchedKeyword) {this.searchedKeyword = searchedKeyword}
Board.prototype.getSearchKeyword = function() { return this.searchedKeyword }
Board.prototype.getter = function() {
    function scriptXSS(msg){
        try {
            msg = msg.replaceAll("&", "&");
            msg = msg.replaceAll("<", "<");
            msg = msg.replaceAll(">", ">");
            msg = msg.replaceAll("(", "&#040;");
            msg = msg.replaceAll(")", "&#041;");
            msg = msg.replaceAll("\"", '"');
            msg = msg.replaceAll("'", "&#x27;");
        } catch {
            
        }
        return msg;
    }
    $.ajax({
        url: Board.prototype.getterUrl,
        method: 'POST',
        data: {
            search_keyword: scriptXSS(Board.prototype.getSearchKeyword()),
            page : scriptXSS(Board.prototype.getPageNo())
        },
        dataType: 'JSON',
        success : function(args){
            if (args["msg"] === "success") {
                const items = args['data']
                Board.prototype.viewList(items)

                const currentPageNo = Number(args["page"])
                const totalPageCount = args["total_page_count"]
                Board.prototype.setLastPageNo(totalPageCount)

                $("#total_count").text(args["total_count"])
                $("#page_curr").html('')
                for (let i = 0; i < totalPageCount; i++) {
                    const pageNo = i + 1
                    const html = `<a onclick="Board.prototype.movePage(${pageNo})"
                                         style="${(pageNo === currentPageNo) ? Board.prototype.pageNoStyle : ''}"  >${pageNo}</a>`
                    $("#page_curr").append(html)
                }
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}
Board.prototype.refresh = function () {
    $("#search_keyword").val(Board.prototype.getSearchKeyword())
    Board.prototype.getter()
}
Board.prototype.createPageNavigation = function () {}
Board.prototype.noDataCase = function() {}
Board.prototype.haveDataCase = function(list) {}
Board.prototype.viewList = function (dataList) {
    let html = undefined
    if (dataList.length === 0) {
        html = Board.prototype.noDataCase()
    } else {
        html = Board.prototype.haveDataCase(dataList)
    }
    $("#articles").html(html)
}

Board.prototype.init = function () {
    Board.prototype.createPageNavigation()
    Board.prototype.getter()

    $("#page_first").click(function() {
        Board.prototype.setPageNo(1)
        Board.prototype.refresh()
    })
    $("#page_last").click(function() {
        const lastPageNo = Board.prototype.getLastPageNo()
        Board.prototype.setPageNo(lastPageNo)
        Board.prototype.refresh()
    })
    $("#page_prev").click(function() {
        let pageNo = Board.prototype.getPageNo()
        pageNo = (pageNo > 1) ? (pageNo -1) : 1
        Board.prototype.setPageNo(pageNo)
        Board.prototype.refresh()
    })
    $("#page_next").click(function() {
        let pageNo = Board.prototype.getPageNo()
        const lastPageNo = Board.prototype.getLastPageNo()
        pageNo = (lastPageNo > pageNo) ? (pageNo + 1) : lastPageNo
        Board.prototype.setPageNo(pageNo)
        Board.prototype.refresh()
    })
    $("#search_article").click(function() {
        Board.prototype.setPageNo(1)
        Board.prototype.setSearchKeyword($("#search_keyword").val())
        Board.prototype.getter()
    })
    $("#write_article").click(function() {
        localStorage.clear()
        location.href = Board.prototype.editWindowUrl
    })
    $("#remove_article").click(function() {
        let noArr = []
        $("input:checkbox[name=article_chk]:checked").each(function() {
            noArr.push(this.value)
        })

        if (noArr.length === 0) {
            alert("삭제할 글을 선택하세요.")
            return
        }

        if (confirm('선택한 글을 삭제하시겠습니까?')) {
            $.ajax({
                url: Board.prototype.removeUrl,
                method: 'POST',
                data: { article_no: noArr },
                dataType: 'JSON',
                success : function(args){
                    if (args["msg"] === "success") {
                        alert("선택한 글이 삭제되었습니다.")
                        location.reload()
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }
    })
    $("#check_article").change(function() {
        if ($("#check_article").is(":checked")) {
            $("input[name=article_chk]:checkbox:not(:disabled)").prop("checked", "checked");
        } else {
            $("input[name=article_chk]:checkbox").removeProp("checked");
        }
    })
}

Board.prototype.movePage = function(pno) {
    this.pageNo = pno
    Board.prototype.refresh()
}

Board.prototype.viewArticle = function (articleNo, userNo) {

    if (articleNo === null || articleNo === undefined ||
        articleNo === '' || Number(articleNo) < 1) {
        alert("게시글 정보를 불러올 수 없습니다.")
        window.history.back()
        return
    }

    localStorage.setItem("user_no", userNo)
    localStorage.setItem("article_no", articleNo)
    location.href = Board.prototype.viewArticleUrl
}