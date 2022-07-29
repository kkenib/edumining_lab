<div class="sub_tit">
    <div class="s_tit_wrap">
        <h1>본인인증</h1>
        <ul class="location">
            <li><i class="fa fa-home fa-lg"></i></li>
            <li><i class="fa fa-caret-right"></i>회원가입</li>
            <li><i class="fa fa-caret-right"></i>본인인증</li>
        </ul>
    </div>
</div>



<div class="box">
    
    <div class="mem_step">
        <div class="step stepBg2">
            <ul>
                <li class="step1 off"><span>STEP 01.</span>약관동의</li>
                <li class="step2 on"><span>STEP 02.</span>본인인증</li>
                <li class="step3 off"><span>STEP 03.</span>정보입력</li>
                <li class="step4 off"><span>STEP 04.</span>가입완료</li>
            </ul>
        </div>
    </div>


    <div class="mem_cont">
        <div class="join_box1">
            <!--h2>본인인증</h2-->
            
            <div class="join_check">
              <dl>
                  <dt>
                      <p><img src="<?php echo base_url('/images/common/phon.png')?>" alt="핸드폰 본인인증"></p>
                      휴대폰 인증
                  </dt>
                  <dd>본인 명의의 휴대폰으로 인증번호를 받은 후 가입하실 수 있습니다.</dd>
              </dl>
            </div> 
            <div class="agree_c">
				<a class="btn_memB_blue" href="javascript:fnPopup()">인증하기</a>
				<!--span></span>
				<a class="btn_memB_gray" href="#">취소</a-->
			</div>
        </div>
    </div>


</div>
<?php
echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
$attributes = array('class' => 'form-horizontal', 'name' => 'form_chk', 'id' => 'form_chk');
echo form_open(current_full_url(), $attributes);
?>
    <input type="hidden" name="m" value="checkplusSerivce">
    <input type="hidden" name="EncodeData" value="<?php echo element('enc_data', $view) ?>">
    <input type="hidden" name="param_r1" value="JOIN">
    <input type="hidden" name="param_r2" value="">
    <input type="hidden" name="param_r3" value="">	
<?php echo form_close();?>

<!-- 본인인증 성공시 데이터 전달 -->
<?php
$attributes = array('class' => 'form-horizontal', 'name' => 'return_form_chk', 'id' => 'return_form_chk');
echo form_open(current_full_url(), $attributes);
?>
<input type="hidden" name="chkplus_flag" value="" />
<input type="hidden" name="mem_id_name" value="" />
<input type="hidden" name="mem_id_num" value="" />
<input type="hidden" name="sReserved1" value="" />
<input type="hidden" name="sReserved2" value="" />
<?php echo form_close();?>


<script type="text/javascript">
	window.name ="checkplus_mobile_service";
	
	function fnPopup(){
		window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
		document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
		document.form_chk.target = "popupChk";
		document.form_chk.submit();
	}
</script>
