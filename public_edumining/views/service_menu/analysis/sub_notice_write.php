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


<!-- wright -->
<section class="mt80 pb80">
    <div class="wrapper">
        <div class="grey_contbox brd_detail">
            <dl>
                <dt>제목</dt>
                <dd class="tit_notice">
                    <input id="article_title" type="text" placeholder="제목을 입력하세요.">
                    <span>
						<input type="checkbox" id="notice_status"><label for="notice_status">&nbsp;공지글 등록</label>
					</span>
                </dd>
                <dt>파일첨부</dt>
                <dd>
                    <div class="file_route">
                        <a id="file_url" href="#">
                            <input type="text"
                                   readonly="readonly"
                                   id="file_route"
                                   style="cursor:pointer" />
                        </a>
                        <label>
                            <i class="fas fa-file-upload mr5"></i>파일업로드
                            <input type="file" name="" id="fileUploader">
                        </label>
                    </div>
                    <!-- <div class="file_route mt5">
                        <input type="text" readonly="readonly" id="file_route2">
                        <label>
                            <i class="fas fa-file-upload mr5"></i>파일업로드
                            <input type="file" name="" id="" onchange="javascript:document.getElementById('file_route2').value = this.value.split('\\')[this.value.split('\\').length-1]">
                        </label>
                    </div> -->
                </dd>
            </dl>
            <div style="background: white">
                <textarea id="article_contents" class="wh_box mt20" style="width: 100%; height: 100%; resize: none;
                font-family:'Nanum Gothic';" placeholder="내용을 입력하세요."></textarea>
            </div>
        </div>
        <div class="tac mt40">
            <a href="/analysis/sub_notice" class="btn grey round lg mr15">목록보기</a>
            <button type="button" class="save btn blue round lg mr15">저장하기</button>
        </div>
    </div>
</section>

<!-- view
<section class="mt80 pb80">
	<div class="wrapper">
		<div class="grey_contbox brd_detail">
			<h3 class="mb10"><i>공지</i>공지사항 제목공지사항 제목공지사항 제목공지사항 제목공지사항 제목공지사항 제목공지사항 제목공지사항 제목공지사항 제목공지사항 제목공지사항 제목공지사항 제목공지사항 제목공지사항 제목공지사항 제목공지사항 제목</h3>
			<p class="mb10"><time datetime="2021-12-14">2021.12.14</time></p>
			<p><a href="#">파일제목.jpg</a></p>
			<p><a href="#">파일제목2.jpg</a></p>
			<div class="wh_box mt20">
				공지사항 작성 글 내용이 들어갑니다.
			</div>
		</div>
		<div class="tac mt40">
			<a href="/analysis/sub_notice" class="btn grey round lg mr15">목록보기</a>
			<button type="button" class="btn navy round lg mr15">새 글쓰기</button>
			<button type="button" class="btn blue round lg mr15">수정하기</button>
			<button type="button" class="btn pink round lg">삭제하기</button>
		</div>
	</div>
</section> -->

<script type="text/javascript">
    let currentArticleNo = Number(localStorage.getItem("target_article_no"))
    let uploadedFileNo = 0

    if (currentArticleNo > 0) {
        const title = localStorage.getItem("title")
        const contents = localStorage.getItem("contents")
        const noticeStatus = localStorage.getItem("notice_status")
        const fileName = localStorage.getItem("file_name")
        const fileUrl = localStorage.getItem("file_url")
        uploadedFileNo = localStorage.getItem("file_no")

        $("#article_title").val(title)
        $("#article_contents").val(contents)
        $('#notice_status').prop('checked', noticeStatus === 'Y')
        $("#file_url").attr("download", fileName).attr("href", fileUrl)
        $("#file_route").val(fileName)
    }

    $('#article_contents').summernote({
        height: 650,
        minHeight: null,
        maxHeight: null,
        focus: true,
        codeviewFilter: false,
        codeviewIframeFilter: true,
        lang: "ko-KR",
        callbacks: {
            onImageUpload : function(files) {
                uploadImage(files[0],this);
            },
            onPaste: function (e) {
                var clipboardData = e.originalEvent.clipboardData;
                if (clipboardData && clipboardData.items && clipboardData.items.length) {
                    var item = clipboardData.items[0];
                    if (item.kind === 'file' && item.type.indexOf('image/') !== -1) {
                        e.preventDefault();
                    }
                }
            }
        }
    });

    function uploadImage(file, editor){
        const data = new FormData();
        data.append("file", file);

        $.ajax({
            data : data,
            type : "POST",
            url : "/edumining/analysis/uploadImage",
            contentType : false,
            processData : false,
            dataType: "JSON",
            success : function(data) {
				console.log(data);
                $(editor).summernote('insertImage', data.file_url);
            }
        });
    }
    
    // 파일 변화 감지
    $("#fileUploader").change(function () {
        const fileName = this.value.split('\\')[this.value.split('\\').length - 1]
        $('#file_route').val(fileName)
    })

    $(".save").click(function () {
        const userNo = getUserNo()
        const title = $("#article_title").val()
        if (title.length > 100) {
            alert("제목은 100자이하로 작성해주세요.")
            return
        }

        const contents = $('#article_contents').summernote('code');
        if (contents.length > 10000) {
            alert("내용은 10000자이하로 작성해주세요.")
            return
        }

        const articleNo = currentArticleNo
        const noticeStatus = $("#notice_status").is(":checked") ? 'Y':'N'
        const fileData = $("#fileUploader").prop("files")[0]
        let formData = new FormData()
        formData.append("file", fileData === undefined ? null : fileData)
        formData.append("article_no", String(articleNo))
        formData.append("title", title)
        formData.append("contents", contents)
        formData.append("user_no", String(userNo))
        formData.append("notice_status", noticeStatus)
        formData.append("file_no", uploadedFileNo)

        if (confirm(`작성한 글을 ${articleNo > 0 ? "수정": "등록"}하시겠습니까?`)) {
            $.ajax({
                type: "POST",
                url: "/edumining/analysis/saveArticle",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function(args) {
                    if (args['msg'] === 'success') {
                        alert(`작성한 글이 정상적으로 ${articleNo > 0 ? "수정": "등록"}되었습니다.`)
                        location.href = "/analysis/sub_notice"
                    }
                },
                error: function(xhr, status, error) {
                    alert("알 수 없는 오류가 발생했습니다.")
                }
            })
        }
    })

    function getUserNo() {
        let userNo = "<?php echo $this->member->item('mem_id') ?>"
        if(userNo === '' || Number(userNo) < 1) {
            alert("사용자 정보를 불러올 수 없습니다.")
            location.back()
            return
        }
        return Number(userNo)
    }
</script>