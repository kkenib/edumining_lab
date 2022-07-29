<?php if ($this->member->item('mem_level') === '2'){?>
	<div class="tab01 num3 mt12" style="border:0">
		<a href="<?php echo site_url('mypage');?>" <?php if(uri_string() == 'mypage' || uri_string() == 'mypage') { echo 'class="on btn grey lg"'; } ?> class="on btn lightgrey lg">개인정보 상세보기</a>
		<!--<a href="<?php echo site_url('mypage/group'); ?>" <?php if(uri_string() == 'mypage/group') { echo 'class="on"'; } ?>>그룹관리</a>-->
		<a href="<?php echo site_url('membermodify/memberleave'); ?>" <?php if(uri_string() == 'membermodify/memberleave') { echo 'class="on"'; } ?> class="floatr btn lightgrey lg" style="float: right">회원탈퇴</a>
	</div>
<?php } else {?>
	<!-- <div class="con_tab2 column big">
		<ul>    
			<li <?php if(uri_string() == 'membermodify' || uri_string() == 'membermodify/modify') { echo 'class="on"'; } ?>><a href="<?php echo site_url('membermodify');?>" title="개인정보 변경">개인정보 변경</a></li>
			<li <?php if(uri_string() == 'membermodify/memberleave') { echo 'class="on"'; } ?>><a href="<?php echo site_url('membermodify/memberleave'); ?>" title="회원탈퇴">회원탈퇴</a></li>
		</ul>
	</div> -->
<?}?>