<div class="box">
    <div class="box-table">
        <?php
        echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
        $attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
        echo form_open(current_full_url(), $attributes);
        ?>
        <div class="box-table-header">
            <?php
            ob_start();
            ?>
            <div class="btn-group pull-right" role="group" aria-label="...">
                <!--
                <a href="<?php echo element('download_member_url', $view); ?>" class="btn btn-outline btn-default btn-sm">목록 다운로드</a>
                -->
                <a href="<?php echo element('listall_url', $view); ?>" class="btn btn-outline btn-default btn-sm">전체목록</a>
                <a href="<?php echo element('listall_url_acc', $view); ?>" class="btn btn-outline btn-default btn-sm">승인목록</a>
                <a href="<?php echo element('listall_url_std', $view); ?>" class="btn btn-outline btn-default btn-sm">대기목록</a>
                <a href="<?php echo element('listall_url_rej', $view); ?>" class="btn btn-outline btn-default btn-sm">거절목록</a>
                <button type="button" class="btn btn-outline btn-default btn-sm btn-list-accept btn-list-selected disabled" data-list-accept-url = "<?php echo element('list_acc_url', $view); ?>" >승인</button>
                <button type="button" class="btn btn-outline btn-default btn-sm btn-list-reject btn-list-selected disabled" data-list-reject-url = "<?php echo element('list_rej_url', $view); ?>" >거절</button>
            </div>
            <?php
            $buttons = ob_get_contents();
            ob_end_flush();
            ?>
        </div>
        <div class="row">전체 : <?php echo element('total_rows', element('data', $view), 0); ?>건</div>
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered">
                <colgroup>
                    <col style="width: 5%;">
                    <col style="width: 5%;">
                    <col style="width: 10%;">
                    <col style="width: 20%;">
                    <col style="width: 10%;">
                    <col style="width: 25%;">
                    <col style="width: 10%;">
                    <col style="width: 10%;">
                    <col style="width: 5%;">
                </colgroup>
                <thead>
                <tr>
                    <th><input type="checkbox" name="chkall" id="chkall" /></th>
                    <th><a href="<?php echo element('no', element('sort', $view)); ?>">번호</a></th>
                    <th><a href="<?php echo element('mem_userid', element('sort', $view)); ?>">아이디</a></th>
                    <th><a href="<?php echo element('lecture_name', element('sort', $view)); ?>">수업명</a></th>
                    <th><a href="<?php echo element('school_name', element('sort', $view)); ?>">학교</a></th>
                    <th><a href="<?php echo element('title', element('sort', $view)); ?>">제목</a></th>
                    <th><a href="<?php echo element('permit_state', element('sort', $view)); ?>">승인여부</a></th>
                    <th><a href="<?php echo element('regdate', element('sort', $view)); ?>">등록일</a></th>
                    <th>보기</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (element('list', element('data', $view))) {
                    foreach (element('list', element('data', $view)) as $result) {
                        ?>
                        <tr>
                            <td><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /></td>
                            <td><?php echo number_format(element('num', $result)); ?></td>
                            <td><?php echo html_escape(element('mem_userid', $result)); ?></td>
                            <td><?php echo html_escape(element('lecture_name', $result)); ?></td>
                            <td><?php echo html_escape(element('school_name', $result)); ?></td>
                            <td><?php echo html_escape(element('title', $result)); ?></td>
                            <?php if( html_escape(element('permit_state', $result)) == "1"){ ?>
                                <td>승인대기중</td>
                            <?php } else if(html_escape(element('permit_state', $result)) == "2"){ ?>
                                <td>승인완료</td>
                            <?php } else{ ?>
                                <td>거절</td>
                            <?php } ?>
                            <td><?php echo display_datetime(element('update_date', $result), 'full'); ?></td>
                            <td><a href="" onclick="viewReport(<?php echo element(element('primary_key', $view), $result); ?>)" class="btn btn-outline btn-default btn-xs">보기</a></td>
                        </tr>
                        <?php
                    }
                }
                if ( ! element('list', element('data', $view))) {
                    ?>
                    <tr>
                        <td colspan="13" class="nopost">자료가 없습니다</td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
        <div class="box-info">
            <?php echo element('paging', $view); ?>
            <div class="pull-left ml20"><?php echo admin_listnum_selectbox();?></div>
            <?php echo $buttons; ?>
        </div>
        <?php echo form_close(); ?>
    </div>
    <form name="fsearch" id="fsearch" action="<?php echo current_full_url(); ?>" method="get">
        <div class="box-search">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <select class="form-control" name="sfield" >
                        <?php echo element('search_option', $view); ?>
                    </select>
                    <div class="input-group">
                        <input type="text" class="form-control" name="skeyword" value="<?php echo html_escape(element('skeyword', $view)); ?>" placeholder="Search for..." />
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-sm" name="search_submit" type="submit">검색!</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    function viewReport(reportNo) {
        localStorage.setItem("report_no", reportNo)
        window.open("/management/popup_view_report", "a", "width=1068, height=800, left=10, top=10");
    }
</script>