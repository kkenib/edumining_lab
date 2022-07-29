<style>
    .alert { padding: 12px; margin-bottom: 8px; border: 1px solid transparent; border-radius: 1px; }
    .alert-dismissible,
    .alert-dismissible { padding-right: 35px; }
    .alert-dismissible .close,
    .alert-dismissible .close { position: relative; top: -2px; right: -21px; color: inherit; }
    .alert-info { color: #31708f; background-color: #eff4fb; border-color: #c6d9f1; }
    .alert-info hr { border-top-color: #a6e1ec; }
    .alert-info { color: #245269; }
</style>

<div class="contents">
    <div class="wrapper">

        <//?include __DIR__."/mypage_tab.php";?>

        <?php
        //		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
        //        		echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-warning"><button type="button" class="close alertclose" >&times;</button>', '</div>');
        ?>
        <h2>부산에듀빅 회원 탈퇴 안내</h2>
        <?php if ($this->member->item('mem_group') === 'kakao' || $this->member->item('mem_group') === 'naver') { ?>
            <p class="lh22">SNS연동 계정에 대해서는 회원 탈퇴가 진행되지 않습니다.</p>
        <?php } else {?>
            <p class="lh22 mb20">
                회원 탈퇴 후 재가입을 할 경우 이전에 사용한 아이디는 사용이 불가능합니다.<br>
                <!-- <li>이용권을 구매하여 사용한 회원이 탈퇴 할 경우 취소 및 환불이 불가능합니다. </li>
                <li>회원 탈퇴와 함께 구매하신 이용권도 자동 소멸됩니다. </li>
                <li>탈퇴 시 타회원에게 이용권을 양도할 수 없습니다. </li>
                <li>패스워드를 입력하시면 회원탈퇴가 정상적으로 진행됩니다.</li> -->
                탈퇴한 회원정보는 복구할 수 없으므로, 신중히 선택해주시기 바랍니다.
                <!-- SNS연동 계정에 대해서는 회원 탈퇴가 진행되지 않으니 참고 바랍니다. -->
            </p>

            <?php
            $attributes = array('class' => 'form-horizontal', 'name' => 'fconfirmpassword', 'id' => 'fconfirmpassword', 'onsubmit' => 'return confirmLeave()');
            echo form_open(current_url(), $attributes);
            ?>

            <table class="member_tbl mt10" summary="개인정보 변경 테이블입니다.">
                <caption>개인정보 변경 테이블</caption>
                <tbody>
                <tr>
                    <th class="w20p">아이디</th>
                    <td><?php echo $this->member->item('mem_userid'); ?></td>
                </tr>
                <tr>
                    <th>비밀번호</th>
                    <td>
                        <input type="password" name="mem_password" id="mem_password"/>
                        <!--                        <button class="btn navy vat" type="submit" onclick="checkPassword()">확인</button>-->
                        <a class="btn grey" href="javascript:checkPassword()">확인</a>
<!--                                                <div><i class="tip red fw700 ml10">비밀번호를 입력하시면 탈퇴가 완료됩니다</i>-->
                    </td>
                </tr>
                </tbody>
            </table>

        <?php } ?>

        <div id="leave_message_box" style="text-align: center; display: none">
            <div id="leave_message" class="alert alert-dismissible alert-info" style="display: inline-block; text-align: left; margin-top:28px">
            </div>
            <div>
                <input type="checkbox" id="leave_agree">
                <label for="leave_agree" class="mt8">&nbsp;&nbsp;모든 정보를 삭제하는 것에 동의합니다.</label>
            </div>
        </div>

        <div class="tac mt20">
            <?php if ($this->member->item('mem_group') === 'kakao' || $this->member->item('mem_group') === 'naver') { ?>
                <a class="btn pink round mid" href="javascript:history.back()">취소</a>
            <?php } else {?>
                <button id="leave_button" class="btn navy round mid vat" type="submit" style="display: none" disabled>탈퇴하기</button>
                <a class="btn pink round mid" href="javascript:history.back()">취소</a>
            <?php } ?>
        </div>
        <?php echo form_close(); ?>

    </div>
</div>


<script type="text/javascript">
    //<![CDATA[
    // $(function() {
    //     $('#fconfirmpassword').validate({
    //         rules: {
    //             mem_password : { required:true, minlength:6, maxlength:12 }
    //         }
    //     });
    // });

    document.addEventListener('keydown', function(e) {
        if (e.keyCode === 13) {
            e.preventDefault()
        }
    }, true);

    let dom = document.getElementById("mem_password")
    dom.addEventListener("keydown", function (e) {
        if (e.keyCode === 13) {
            checkPassword()
        }
    })

    dom = document.getElementById("leave_agree")
    dom.addEventListener("change", function (e) {
        const checked     = document.getElementById("leave_agree").checked
        const leaveButton = document.getElementById("leave_button")
        leaveButton.disabled = !checked
        leaveButton.classList.remove(checked ? "grey" : "navy")
        leaveButton.classList.add(checked ? "navy" : "grey")
    })

    function confirmLeave() { return confirm('탈퇴를 진행하시겠습니까?') }

    const userId = "<?php echo $this->member->item('mem_userid'); ?>"
    function checkPassword() {
        const password = $("#mem_password").val()
        $.ajax({
            type: 'post',
            url: '/membermodify/check_password',
            data : { password: password },
            async: false,
            dataType : 'json',
            success : function(result) {
                if(!result) {
                    alert("비밀번호가 일치하지 않습니다.")
                    let leaveAgree = document.getElementById("leave_agree")
                    leaveAgree.checked = false

                    const leaveButton = document.getElementById("leave_button")
                    leaveButton.disabled = true
                    leaveButton.classList.remove("navy")
                    leaveButton.classList.add("grey")
                }
                else {
                    const message = $("#leave_message").html()
                    $("#leave_message").html(`
                        ${userId} 회원님. 탈퇴를 진행합니다.<br><br>
                        회원을 탈퇴할 경우, 수집 및 보유 데이터 분석 결과 리포트 등 모든 데이터가 삭제됩니다.<br><br>
                        회원님의 데이터는 탈퇴 즉시 삭제되며, 재가입을 하여도 복구되지 않습니다.<br><br>
                        중요한 데이터는 회원 탈퇴 전 반드시 저장해주시기 바랍니다.`)
                }

                $("#leave_button").css("display", result ? '':"none")
                $("#leave_message_box").css("display", result ? '':"none")
            }
        })
    }

    //]]>
</script>
