<?php
$mem_level = $this->member->item('mem_level');
?>
<script type="text/javascript">
    const board = new Board()
    Board.prototype.pageNoStyle = "font-weight: bold; color:#e84c88;"
    Board.prototype.editWindowUrl = "/analysis/sub_case_write"
    Board.prototype.viewArticleUrl = "/analysis/sub_case_detail"
    Board.prototype.getterUrl = "/edumining/analysis/get_case_list"
    Board.prototype.removeUrl = "/edumining/analysis/removeArticle"

    let userNo = "<?php echo $this->member->item('mem_id') ?>"
    userNo = isValid(userNo) ? Number(userNo) : 0
    function isValid(value) {
        return !(value === null || value === undefined || value === '')
    }

</script>

<section class="visualarea sub">
    <div class="wrapper">
        <div class="subvisual_bg notice">
            <div class="subvisual_box">
                <h2 class="bold"><b>수업</b>기록</h2>
                <p>
                   부산에듀빅을 활용한 다양한 수업사례를 확인하고 <br>
				   창의적인 교수학습법을 기록하고 공유하실 수 있습니다.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="choice_list mt80 pb80">
    <div class="wrapper">
        <div class="grey_contbox">
            <div class="tar mb15">
                <div class="floatl pt15">
                    전체 <strong class="pink fs18" id="total_count">0</strong>건
                </div>
                <div class="search_wrap w300">
                    <a id="search_article" class="search"><i class="fas fa-search"></i></a>
                    <input type="text" placeholder="제목을 입력하세요." id="search_keyword">
                </div>
            </div>


            <div class="wh_box tac">
                <div class="tblscroll">
                    <table class="tbl02" summary="수업사례 리스트입니다">
                        <caption>수업사례 리스트</caption>
                        <thead>
                        <tr>
                            <th class="w6p">
									<span>
										<input type="checkbox" id="check_article"><label for="check_article"></label>
									</span>
                            </th>
                            <th class="w10p">NO</th>
                            <th class="">제목</th>
                            <th class="w12p">작성자</th>
                            <th class="w12p">작성일</th>
                            <th class="w12p">조회수</th>
                        </tr>
                        </thead>
                        <tbody id="articles">
                        <script>
                            Board.prototype.noDataCase = function () {
                                return `<tr><td class="list_none" colspan="6">게시글이 존재하지 않습니다.</td></tr>`
                            }  // 공지사항이 없을 때

                            Board.prototype.haveDataCase = function (list) {
                                let html = ''
                                for (let i = 0; i < list.length; i++) {
                                    const item = list[i]
                                    const itemNo       = item.item_no
                                    const articleNo    = item.article_no
                                    const artUserNo    = item.user_no
                                    const artUserName  = item.user_name
                                    const createDate   = item.create_date.split(' ')[0]
                                    const viewCount    = item.view_count
                                    const title        = item.title
                                    const noticeStatus = item.notice_status  === 'Y' ? "공지" : ''
                                    const chkEnable = Number(item.user_no) === userNo ? '' : "disabled"

                                    html +=
                                        `<tr>
                                       <td>
                                         <span>
                                           <input type="checkbox" id="chk${item.article_no}"
                                                  name="article_chk" value="${item.article_no}" ${chkEnable}>
                                           <label for="chk${item.article_no}"></label>
                                         </span>
                                       </td>
                                       <td>${itemNo}</td>
                                       <td class="tit_notice">
                                         <i class="notice">${noticeStatus}</i>
                                         <a onclick="Board.prototype.viewArticle(${articleNo}, ${artUserNo})">
                                           ${item.title}
                                         </a>
                                       </td>
                                       <td>${artUserName}</td>
                                       <td>${createDate}</td>
                                       <td>${viewCount}</td>
                                    </tr>`
                                }
                                return html
                            }  // 공지사항이 존재할 때
                        </script>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="paging mt40" id="paging">
            <div id="page_navigation" class="wr_page">
                <script>
                    Board.prototype.createPageNavigation = function() {
                        $("#page_navigation").append(
                            `<button id="page_first" type="button" class="first mr5" title="처음 페이지 이동">
                            <i class="fas fa-angle-double-left"></i><em>처음 페이지 이동</em>
                         </button>
                         <button id="page_prev" type="button" class="pre" title="이전 페이지 이동">
                           <i class="fas fa-angle-left"></i><em>이전 페이지 이동</em>
                         </button>
                         <span id="page_curr" class="num"></span>
                         <button id="page_next" type="button" class="next mr5" title="다음 페이지 이동">
                           <i class="fas fa-angle-right"></i><em>다음 페이지 이동</em>
                         </button>
                         <button id="page_last" type="button" class="last" title="끝 페이지 이동">
                           <i class="fas fa-angle-double-right"></i><em>끝 페이지 이동</em>
                         </button>`
                        )}
                </script>
            </div>
        </div>

        <?php if ($mem_level >= 2) { ?>
            <div class="tac mt40">
                <button id="write_article" type="button" class="write btn navy round lg mr15">새 글쓰기</button>
                <button id="remove_article" type="button" class="remove btn pink round lg">삭제하기</button>
            </div>
        <?php } ?>
    </div>
</section>

<script>
    board.init()
</script>

