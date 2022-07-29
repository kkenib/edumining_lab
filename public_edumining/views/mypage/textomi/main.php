<div class="contents">
	<div class="wrapper">



			<h2>개인정보</h2>
			<table class="member_tbl" summary="개인정보 테이블입니다.">
				<caption>개인정보 테이블</caption>
				<tbody>
					<input type="hidden" name="mem_userid" id="mem_userid" title="내아이디" value="<?php echo $mem_pt = $this->member->item('mem_userid');?>">
					<input type="hidden" name="mem_id" id="mem_id" title="idx" value="<?php echo $mem_pt = $this->member->item('mem_id');?>">
					<tr>
					<?php if ($this->member->item('mem_group') === 'kakao' || $this->member->item('mem_group') === 'naver') { ?>
						<th class="w20p">SNS연동 코드</th>
					<?php } else {?>
						<th class="w20p">아이디</th>
					<?php } ?>
						<td><?php echo html_escape($this->member->item('mem_userid')); ?></td>
					</tr>
					<?php if (element('use', element('mem_username', element('memberform', $view)))) { ?>
					<tr>
						<th>성명</th>
						<?php if ($this->member->item('mem_group') === 'kakao') { ?>
							<td><img src="/views/_layout/textomi/images/kakaolink_small.png" width="20" height="20" class="mr5"><?php echo html_escape($this->member->item('mem_username')); ?></td>
						<?php } else if($this->member->item('mem_group') === 'naver') {?>
							<td><img src="/views/_layout/textomi/images/naverlink_small.png" width="20" height="20" class="mr5"><?php echo html_escape($this->member->item('mem_username')); ?></td>
						<?php } else {?>
							<td><?php echo html_escape($this->member->item('mem_username')); ?></td>
						 <?php } ?>
					</tr>
					<?php } ?>
<!--					<tr>-->
<!--						<th>닉네임</th>-->
<!--						<td>--><?php //echo html_escape($this->member->item('mem_nickname')); ?><!--</td>-->
<!--					</tr>-->

                    <?php if ($this->member->item('mem_level') === '2'){?>
                    <tr>
                        <th>소속학교/학교코드</th>
                        <td>
                            <strong>
                                <?php echo $this->member->item('mem_school_name'); ?> / <?php echo $this->member->item('mem_school_code'); ?>
                            </strong>
                        </td>
                    </tr>
                    <?php }?>

                    <?php if ($this->member->item('mem_level') === '1'){?>
					<tr>
						<th>학년</th>
						<td><?php echo html_escape($this->member->item('mem_division_nm')); ?></td>
					</tr>
                    <?php }?>

<!--					--><?php //if ($this->member->item('mem_level') === '2'){?>
<!--					<tr>-->
<!--						<th>담당학생수</th>-->
<!--						<td>--><?php //echo html_escape($this->member->item('mem_std_count')); ?><!--명</td>-->
<!--					</tr>-->
<!--					--><?php //}?>
                    
					<tr>
						<th>이메일 주소</th>
						<td><?php echo html_escape($this->member->item('mem_email')); ?></td>
					</tr>
<!--					--><?php //if (element('use', element('mem_phone', element('memberform', $view)))) { ?>
<!--					<tr>-->
<!--						<th>전화번호</th>-->
<!--						<td>--><?php //echo html_escape($this->member->item('mem_phone')); ?><!--</td>-->
<!--					</tr>-->
<!--					--><?php //} ?>
					<tr>
						<th>가입일</th>
						<td><?php echo $this->member->item('mem_register_datetime'); ?></td>
					</tr>
					<tr>
						<th>최근 로그인</th>
						<td><?php echo $this->member->item('mem_lastlogin_datetime'); ?></td>
					</tr>
                    <!--
					<tr>
						<th>AI야, 누구게 조</th>
						<td><?php echo html_escape($this->member->item('mem_group_voice'), 'full'); ?></td>
					</tr>
                    -->
				</tbody>
			</table>
			
			<?include __DIR__."/mypage_tab.php";?>

			<div class="tac mt20">
				<a href="<?php echo site_url('membermodify');?>" class="btn navy round lg"><i class="fas fa-edit"></i> 수정</a>
			</div>
			
			
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		word_list();
		voice_list();
	});


	//끝말잇기 리스트 리스트
	function word_list()
	{
		var mem_id = $("#mem_id").val();
		$.ajax({
            type: "POST",
            url: "/word/Main/get_user_info",
            data: {mem_id : mem_id},
            dataType: "JSON",
            success: function(args){
				
				var html ='';
				/*
				html += '<tr>';
				html += '<th>AI끝말잇기 닉네임</th>';
				if(args.ai_name==null){
					html += '<td>AI의 이름을 지어주세요.</td>';
				}else{
					html += '<td>'+args.ai_name+'</td>';
				}
				html += '</tr>';
				*/
				html += '<tr>';
				html += '<th>AI끝말잇기 랭킹 (학교 / 전국)</th>';
				if(args.school_lank==null){
					html += '<td>AI의 이름을 지으면 랭킹이 표시됩니다.</td>';
				}else{
					html += '<td>'+args.school_lank+' / '+args.total_lank+'</td>';
				}
				html += '</tr>';

				html += '<tr>';
				html += '<th>AI끝말잇기 성능 (단어수 / 경기수)</th>';
				if(args.word_count==null){
					html += '<td>AI의 이름을 지으면 성능이 표시됩니다.</td>';
				}else{
					html += '<td>'+args.word_count+' / '+args.learn_count+'</td>';
				}
				html += '</tr>';

				$(".tbl02 >tbody").append(html);
            }
        });
		return false;
	}


	//누구게 리스트
	function voice_list()
	{
		var userid= $("#mem_userid").val();
		$.ajax({
			type: 'post',
			url: '/voice/main/memListTime',
			async: false,
			dataType : 'json',
			success : function(data) {
				var html='';
				$.each(data, function(i, e){
					if(e.mem_userid ==userid){
						html += '<tr>';
						html += '<th>AI야, 누구게 성능</th>';
						html += '<td>'+Math.round(e.vt_train1)+'%</td>';
						html += '</tr>';

						html += '<tr>';
						html += '<th>AI야, 누구게 전체성능</th>';
						html += '<td>'+Math.round(e.vt_train2)+'%</td>';
						html += '</tr>';
					}
				});
				if(html ==""){
					html += '<tr>';
					html += '<th>AI야, 누구게 성능</th>';
					html += '<td>0%</td>';
					html += '</tr>';

					html += '<tr>';
					html += '<th>AI야, 누구게 전체성능</th>';
					html += '<td>0%</td>';
					html += '</tr>';
				}
				$(".tbl02 >tbody").append(html);				
			}
		});	
		return false;
	}

</script>