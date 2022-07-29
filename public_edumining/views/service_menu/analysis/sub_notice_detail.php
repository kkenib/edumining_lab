<section class="visualarea sub">
    <div class="wrapper">
        <div class="subvisual_bg notice">
            <div class="subvisual_box">
                <h2 class="bold"><b>공지</b>사항</h2>
                <p>
                    부산에듀마이닝의 신규, 업데이트 및<br>
                    여러가지 소식을 알려드립니다.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="mt80 pb80">
    <div class="wrapper">
        <div id="article" class="grey_contbox brd_detail"></div>
        <div id="ctrl" class="tac mt40">
            <a href="/analysis/sub_notice" class="btn grey round lg mr15">목록보기</a>
        </div>
    </div>
</section>

<script type="text/javascript">

    let articleNo = localStorage.getItem("article_no")
    if (articleNo === null || articleNo === undefined ||
        articleNo === '' || Number(articleNo) < 1) {
        alert("게시글 정보를 불러올 수 없습니다.")
        location.href = "/analysis/sub_notice"
    }
    articleNo = Number(articleNo)

    function isValid(value) {
        return !(value === null || value === undefined || value === '')
    }

    let userLevel = "<?php echo $this->member->item('mem_level') ?>"
    userLevel = isValid(userLevel) ? Number(userLevel) : 0

    let userNo = "<?php echo $this->member->item('mem_id') ?>"
    userNo = isValid(userNo) ? Number(userNo) : 0

    let articleUserNo = localStorage.getItem("user_no")
    articleUserNo = isValid(articleUserNo) ? Number(articleUserNo) : 0

    if (userLevel > 2 ) {
        let html = `<button type="button" class="btn navy round lg mr15" onclick="writeArticle()">새 글쓰기</button>`
        if (userNo === articleUserNo) {
            html += `<button type="button" class="btn blue round lg mr15" onclick="modifyArticle()">수정하기</button>`
            html += `<button type="button" class="btn pink round lg" onclick="removeArticle()">삭제하기</button>`
        }
        $("#ctrl").append(html)
    }

    $.ajax({
        url: '/edumining/analysis/viewArticle',
        method: 'POST',
        data: {
            article_no: articleNo,
        },
        dataType: 'JSON',
        success: function (args) {
            if (args["msg"] === "success") {
                const article = args["article"]
                const userName = article["user_name"]
                const title = article["title"]
                const contents = article["contents"]
                const createDate = article["create_date"]
                const noticeStatus = article["notice_status"]

                localStorage.setItem("title", title)
                localStorage.setItem("contents", contents)
                localStorage.setItem("notice_status", noticeStatus)

                const titleSrc = `<h3 class="mb10"><i>${noticeStatus === 'Y' ? "공지": ""}</i>${title}</h3>`
                $("#article").append(titleSrc)

                const updateDateSrc = `<p class="mb10"><time datetime="${createDate}">${createDate}</time></p>`
                $("#article").append(updateDateSrc)

                const files = args["files"]
                console.log(files)
                for (let i = 0; i < files.length; i++) {
                    const file = files[i]
                    const fileSrc = `<p><a href="${file["file_url"]}" download="${file["file_name"]}">${file["file_name"]}</a></p>`
                    $("#article").append(fileSrc)

                    localStorage.setItem("file_no", file["no"])
                    localStorage.setItem("file_name", file["file_name"])
                    localStorage.setItem("file_url", file["file_url"])
                }

                const contentsSrc = `<div class="wh_box mt20">
                                       <pre style="font-family:'Nanum Gothic'; overflow:auto; white-space:pre-wrap; ">${contents}</pre>
                                     </div>`
                $("#article").append(contentsSrc)
            } else {
                console.log("게시글을 불러올 수 없습니다.")
            }
        },
        error: function (xhr, status, error) {
            alert("알 수 없는 오류가 발생하였습니다.")
        }
    });

    function writeArticle() {
        localStorage.setItem("target_article_no", '')
        localStorage.setItem("title", '')
        localStorage.setItem("contents", '')
        localStorage.setItem("notice_status", '')
        localStorage.setItem("file_no", '')
        localStorage.setItem("file_name", '')
        localStorage.setItem("file_url", '')
        location.href = '/analysis/sub_notice_write'
    }

    function modifyArticle() {
        localStorage.setItem("target_article_no", articleNo)
        location.href = '/analysis/sub_notice_write'
    }

    function removeArticle() {
        let noArr = [articleNo]
        if (confirm('선택한 글을 삭제하시겠습니까?')) {
            $.ajax({
                url: '/edumining/analysis/removeArticle',
                method: 'POST',
                data: { article_no: noArr },
                dataType: 'JSON',
                success : function(args){
                    if (args["msg"] === "success") {
                        alert("선택한 글이 삭제되었습니다.")
                        location.href = "/analysis/sub_notice"
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }
    }

</script>