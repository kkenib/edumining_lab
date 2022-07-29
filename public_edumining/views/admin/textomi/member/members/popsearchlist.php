<div class="box">
    <div class="box-table">
        <?php
        echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
        $attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
        echo form_open(current_full_url(), $attributes);
        ?>
            <div class="row">전체 : <?php echo element('total_rows', element('data', $view), 0); ?>건</div>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th><a href="<?php echo element('mem_id', element('sort', $view)); ?>">번호</a></th>
                            <th><a href="<?php echo element('mem_userid', element('sort', $view)); ?>">아이디</a></th>
                            <th><a href="<?php echo element('mem_username', element('sort', $view)); ?>">실명</a></th>
                            <th><a href="<?php echo element('mem_nickname', element('sort', $view)); ?>">닉네임</a></th>
                            <th><a href="<?php echo element('mem_email', element('sort', $view)); ?>">이메일</a></th>                            
                            <th><a href="<?php echo element('mem_register_datetime', element('sort', $view)); ?>">가입일</a></th>
                            <th><a href="<?php echo element('mem_lastlogin_datetime', element('sort', $view)); ?>">최근로그인</a></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (element('list', element('data', $view))) {
                        foreach (element('list', element('data', $view)) as $result) {
                    ?>
                        <tr>
                            <td><?php echo number_format(element('num', $result)); ?></td>
                            <td><a href="#" onclick="send_member_info('<?php echo html_escape(element('mem_id', $result)); ?>','<?php echo html_escape(element('mem_userid', $result)); ?>','<?php echo html_escape(element('mem_username', $result)); ?>'); return false;" class="label label-primary">선택</a> <?php echo html_escape(element('mem_userid', $result)); ?></td>
                            <td>
                                <span><?php echo html_escape(element('mem_username', $result)); ?></span>
                                <?php echo element('mem_is_admin', $result) ? '<span class="label label-primary">최고관리자</span>' : ''; ?>
                                <?php echo element('mem_denied', $result) ? '<span class="label label-danger">차단</span>' : ''; ?>
                            </td>
                            <td><?php echo element('display_name', $result); ?></td>
                            <td><?php echo html_escape(element('mem_email', $result)); ?></td>
                            <td><?php echo display_datetime(element('mem_register_datetime', $result), 'full'); ?></td>
                            <td><?php echo display_datetime(element('mem_lastlogin_datetime', $result), 'full'); ?></td>
                        </tr>
                    <?php
                        }
                    }
                    if ( ! element('list', element('data', $view))) {
                    ?>
                        <tr>
                            <td colspan="8" class="nopost">자료가 없습니다</td>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="box-info">
                <?php echo element('paging', $view); ?>
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
function send_member_info(mem_id, mem_userid, mem_username) {
    opener.document.fadminwrite.mem_id.value = mem_id;
    opener.document.fadminwrite.mem_userid.value = mem_userid;
    opener.document.fadminwrite.mem_username.value = mem_username;

    self.close();
}
</script>