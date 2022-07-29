<div class="box">
    <div class="box-table">
        <?php
        echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
        $attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
        echo form_open(current_full_url(), $attributes);
        ?>
            <div class="box-table-header">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="?" class="btn btn-sm <?php echo ( ! $this->input->get('mem_is_admin') && ! $this->input->get('mem_denied') && ! $this->input->get('mem_level')) ? 'btn-success' : 'btn-default'; ?>">전체회원</a>
                    <a href="?mem_is_admin=1" class="btn btn-sm <?php echo ($this->input->get('mem_is_admin')) ? 'btn-success' : 'btn-default'; ?>">최고관리자</a>
					<a href="?mem_level=2" class="btn btn-sm <?php echo ($this->input->get('mem_level')==2) ? 'btn-success' : 'btn-default'; ?>">선생님</a>
					<a href="?mem_level=1" class="btn btn-sm <?php echo ($this->input->get('mem_level')==1) ? 'btn-success' : 'btn-default'; ?>">학생</a>
                    <a href="?mem_denied=1" class="btn btn-sm <?php echo ($this->input->get('mem_denied')) ? 'btn-success' : 'btn-default'; ?>">차단회원</a>
                </div>
                <?php
                ob_start();
                ?>
                    <div class="btn-group pull-right" role="group" aria-label="...">
						<!-- <a href="<?php echo element('download_member_url', $view); ?>" class="btn btn-outline btn-default btn-sm">회원목록 다운로드</a> -->
                        <a href="<?php echo element('listall_url', $view); ?>" class="btn btn-outline btn-default btn-sm">전체목록</a>
                        <button type="button" class="btn btn-outline btn-default btn-sm btn-list-delete btn-list-selected disabled" data-list-delete-url = "<?php echo element('list_delete_url', $view); ?>" >선택삭제</button>
                        <a href="<?php echo element('write_url', $view); ?>" class="btn btn-outline btn-danger btn-sm">회원추가</a>
                    </div>
                <?php
                $buttons = ob_get_contents();
                ob_end_flush();
                ?>
            </div>
            <div class="row">전체 : <?php echo element('total_rows', element('data', $view), 0); ?>건</div>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th><a href="<?php echo element('mem_id', element('sort', $view)); ?>">번호</a></th>
							<?php if ($this->input->get('mem_level')==1) {?>
							<th><a href="<?php echo element('mem_parent', element('sort', $view)); ?>">선생님아이디</a></th>
							<?php }?>
                            <th><a href="<?php echo element('mem_userid', element('sort', $view)); ?>">아이디</a></th>
                            <th><a href="<?php echo element('mem_username', element('sort', $view)); ?>">실명</a></th>
                            <th><a href="<?php echo element('mem_nickname', element('sort', $view)); ?>">닉네임</a></th>
                            <th><a href="<?php echo element('mem_email', element('sort', $view)); ?>">이메일</a></th> 
							<th>전화번호</th>
							<th><a href="<?php echo element('mem_agency', element('sort', $view)); ?>">소속학교</a></th> 
                            <th><a href="<?php echo element('mem_register_datetime', element('sort', $view)); ?>">가입일</a></th>
                            <th><a href="<?php echo element('mem_lastlogin_datetime', element('sort', $view)); ?>">최근로그인</a></th>
                            <th><a href="<?php echo element('mem_level', element('sort', $view)); ?>">회원레벨</a></th>
                            
                            <th>수정</th>
                            <th><input type="checkbox" name="chkall" id="chkall" /></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (element('list', element('data', $view))) {
                        foreach (element('list', element('data', $view)) as $result) {
                    ?>
                        <tr>
                            <td><?php echo number_format(element('num', $result)); ?></td>
							<?php if ($this->input->get('mem_level')==1) {?>
								<td><?php echo html_escape(element('mem_parent', $result)); ?></td>
							<?php }?>
							<?php if ($this->input->get('mem_level')==2) {?>
								<td><a href="?mem_level=1&parent=<?php echo html_escape(element('mem_userid', $result)); ?>"><?php echo html_escape(element('mem_userid', $result)); ?></a></td>
							<?php } else {?>
								<td><?php echo html_escape(element('mem_userid', $result)); ?></td>
							<?php }?>
                            <td>
                                <span><?php echo html_escape(element('mem_username', $result)); ?></span>
                                <?php echo element('mem_is_admin', $result) ? '<span class="label label-primary">최고관리자</span>' : ''; ?>
                                <?php echo element('mem_denied', $result) ? '<span class="label label-danger">차단</span>' : ''; ?>
                            </td>
                            <td><?php echo element('display_name', $result); ?></td>
                            <td><?php echo html_escape(element('mem_email', $result)); ?></td>
							<td><?php echo element('mem_phone', $result); ?></td>
							<td><?php echo html_escape(element('mem_agency', $result)); ?></td>
                            <td><?php echo display_datetime(element('mem_register_datetime', $result), 'full'); ?></td>
                            <td><?php echo display_datetime(element('mem_lastlogin_datetime', $result), 'full'); ?></td>
                            <td class="text-right"><?php echo element('mem_level', $result); ?></td>							
                            <td><a href="<?php echo admin_url($this->pagedir); ?>/write/<?php echo element(element('primary_key', $view), $result); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-outline btn-default btn-xs">수정</a></td>
                            <td><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /></td>
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
