<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>
<?php
//$this->managelayout->add_js('http://dmaps.daum.net/map_js_init/postcode.v2.js');
//$this->managelayout->add_js(base_url('assets/js/bootstrap-datepicker.js'));
//$this->managelayout->add_js(base_url('assets/js/bootstrap-datepicker.kr.js'));
$this->managelayout->add_js(base_url('assets/js/member_register.js'));
if ($this->cbconfig->item('use_recaptcha')) {
    $this->managelayout->add_js(base_url('assets/js/recaptcha.js'));
} else {
    $this->managelayout->add_js(base_url('assets/js/captcha.js'));
}
?>

<div class="contents">
    <!-- S : 회원가입 -->
    <div class="box_member">
        <div class="tit_member">
            <span class="logo" title="부산에듀빅 Logo"><img src="<?php echo base_url('views/login/images/logo.png'); ?>" alt="">부산에듀빅</span>
            <h1>회원가입을 환영합니다.</h1>
        </div>
        <?php
        echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
        echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
        $attributes = array('class' => 'form-horizontal', 'name' => 'fregisterform', 'id' => 'fregisterform');
        echo form_open_multipart(current_full_url(), $attributes);
        ?>
        <input type="hidden" name="mem_id_name" id="mem_id_name" value="<?php echo element('mem_id_name', $view)?>" />
        <input type="hidden" name="mem_id_num" id="mem_id_num" value="<?php echo element('mem_id_num', $view)?>" />


        <dl>
            <dt><em>*</em>아이디</dt>
            <dd class="id">
                <input type="text" name="mem_userid" id="mem_userid" title="아이디 입력" placeholder="영문/숫자 3자 이상 입력하세요."/>
                <button id="id_check" type="button">중복확인</button>
            </dd>
            <dt><em>*</em>비밀번호</dt>
            <dd>
                <input type="password" name="mem_password" id="mem_password" title="비밀번호 입력" placeholder="영문, 숫자를 조합하여 6~12자이내로 입력하세요."/>
            </dd>
            <dt><em>*</em>비밀번호 확인</dt>
            <dd>
                <input type="password" name="mem_password_re" id="mem_password_re" title="비밀번호 확인" placeholder="영문, 숫자를 조합하여 6~12자이내로 입력하세요."/>
            </dd>

            <dt><em>*</em>소속학교</dt>
            <dd class="school">
                <div>
                    <input type="text" name="mem_school_name" id="mem_school_name" title="소속학교 입력" placeholder="소속학교를 검색하세요." readonly/>
                    <button id="open_search_popup" type="button">학교검색</button>
                </div>
                <div class="mt7">
                    <input type="text" name="mem_school_code" id="mem_school_code" title="학교코드 입력" placeholder="학교코드를 입력하세요."/>
                    <button id="confirm_school_code" type="button">코드확인</button>
                </div>
            </dd>

            <dt><em>*</em>성명</dt>
            <dd>
                <input type="text" name="mem_username" id="mem_username" title="성명 입력"/>
            </dd>
            <dt><em>*</em>이메일</dt>
            <dd class="email">
				<span>
					<input type="text" name="mem_email_id" id="mem_email_id" title="이메일" value=""/>
					<i>@</i>
					<input type="text" name="mem_email_domain" id="mem_email_domain" title="이메일" value=""/>
				</span>
                <input type="hidden" name="mem_nickname" id="mem_nickname"/>
                <select name="email_address_type" id="email_address_type" class="mt7">
                    <option value="">선택하세요</option>
                    <option value="naver.com">naver.com</option>
                    <option value="hanmail.net">hanmail.net</option>
                    <option value="gmail.com">gmail.com</option>
                    <option value="hotmail.com">hotmail.com</option>
                    <option value="yahoo.co.kr">yahoo.co.kr</option>
                    <option value="lycos.co.kr">lycos.co.kr</option>
                    <option value="chollian.net">chollian.net</option>
                    <option value="freechal.com">freechal.com</option>
                    <option value="dreamwiz.com">dreamwiz.com</option>
                    <option value="empal.com">empal.com</option>
                    <option value="hanafos.com">hanafos.com</option>
                    <option value="hanmir.com">hanmir.com</option>
                    <option value="hitel.net">hitel.net</option>
                    <option value="korea.com">korea.com</option>
                    <option value="kornet.net">kornet.net</option>
                    <option value="netian.com">netian.com</option>
                    <option value="orgio.net">orgio.net</option>
                    <option value="unitel.co.kr">unitel.co.kr</option>
                    <option value="paran.com">paran.com</option>
                    <option value="direct">직접입력</option>
                </select>
                <input type="hidden" name="mem_email" id="mem_email" title="이메일"/>
            </dd>
<!--            <dt><em>*</em>휴대폰</dt>-->
<!--            <dd>-->
<!--                <input type="text" name="mem_phone" id="mem_phone" title="휴대폰" placeholder="'-'를 포함하여 입력해주세요"/>-->
<!--            </dd>-->
            <!--			<dt><em>*</em>소속학교</dt>-->
            <!--			<dd>-->
            <!--				<select name="school_type" id="school_type">-->
            <!--					<option value=" ">선택하세요</option>-->
            <!--					<option value="">초등학교</option>-->
            <!--					<option value=" ">중학교</option>-->
            <!--					<option value=" ">고등학교</option>-->
            <!--				</select>-->
            <!--				<input type="text" name="mem_agency"  class="mt7" id="mem_agency" title="소속학교" placeholder="소속학교를 입력하세요." />-->
            <!--			</dd>-->

            <!-- <dt><em>*</em>학년</dt>
            <dd>
                <input type="text" name="mem_division_nm" id="mem_division_nm" title="학년" placeholder="학년을 입력하세요." maxlength="1" numberOnly/>
                <input type="hidden" name="mem_division_cd" id="mem_division_cd" value='1' title="학년코드"/>
            </dd> -->

            <!-- <dt><em>*</em>첨부파일</dt>
            <dd>
                <div class="file_route">
                    <input type="text" readonly="readonly" id="file_route">
                    <label>
                        <i class="fas fa-file-upload mr5"></i> 파일업로드
                        <input type="file" name="mem_photo" id="mem_photo" onchange="javascript:document.getElementById('file_route').value = this.value.split('\\')[this.value.split('\\').length-1]">
                    </label>
                </div>
                <ul class="box_bg w100p mt5">
                    <li>교사임을 확인 할 수 있는 파일을 업로드 바랍니다. <br>(ex. 재직증명서, 교사확인증, 명함 등)</li>
                    <li>교육대학원 이용자일 경우 학생증을 업로드 바랍니다. <br>문의 사항 : <span class="m_phone">070- 4269-3766</span></li>
                </ul>
            </dd> -->


            <!-- <dt><em>*</em>담당학생수</dt>
            <dd>
                <input type="text" name="mem_std_count" id="mem_std_count" title="담당학생수" placeholder="" maxlength="3" numberOnly/>
                <ul class="box_bg w100p mt5">
                    <li>수업 진행 시 필요한 학생 계정의 수를 입력바랍니다.</li>
                </ul>
            </dd> -->


            <!-- <dt><em>*</em>자동입력방지</dt>
            <dd class="security">
                <?php if ($this->cbconfig->item('use_recaptcha')) { ?>
                    <div class="form-text text-primary group captcha" id="recaptcha"><button type="button" id="captcha"></button></div>
                    <input type="hidden" name="recaptcha" />
                <?php } else { ?>
                    <figure><span><img src="<?php echo base_url('assets/images/preload.png'); ?>" width="100%" height="100%" id="captcha" alt="captcha" title="captcha"></figure>
                    <input type="text" name="captcha_key" id="captcha_key" class="w67p" value="" placeholder="자동입력방지문자를 입력하세요."/>
                <?php } ?>
            </dd> -->
        </dl>
        <div class="tac mt30">
            <button id="submit" class="btn navy round lg " type="submit">가입하기</button>
            <button class="btn pink round lg " href="<?php echo site_url();?>">취소</button>
        </div>
        <?php echo form_close(); ?>
    </div>
    <!-- E : 회원가입 -->

</div>


<div id="list_popup" class="lpopup" style="display: none;">
    <div id="list_area" class="wrap_lpop">

        <div>
            <div class="tit_pop">
                <h2>학교 검색</h2>
                <span id="close_popup" class="btn_pop_close" title="창닫기"><i class="fas fa-times"></i></span>
            </div>
            <div class="pop_body">
                <div>
                    <div class="searchform">
                        <input id="search_keyword" type="text" placeholder="학교명을 입력하세요.">
                        <button id="search_school" type="button" title="검색" class="btn grey"><i class="fas fa-search"></i> 검색</button>
                    </div>
                </div>
                <!-- 업로드 내역이 없을 때 -->
                <p id="empty_list" class="box_empty mt16">
                    <span><i class="fas fa-exclamation-circle"></i> 검색된 학교가 없습니다.</span>
                </p>
                <!-- 업로드 리스트 : 리스트 최대 10개 -->
                <ul id="list" class="list02 yellow mt16">
                    <!--                    <li>-->
                    <!--                        <dt><strong>통돌이중학교</strong></dt>-->
                    <!--                        <dd>-->
                    <!--                            중학교-->
                    <!--                            <button class="btn sm grey round">선택</button>-->
                    <!--                        </dd>-->
                    <!--                    </li>-->
                </ul>
                <div id="list_paging" class="paging yellow mt8">
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    const selectedSchoolDetails = new SelectedSchoolDetails()
    $("#open_search_popup").click(function (e) {
        $("#search_keyword").val('')
        loadSchoolList(1)
        $(".lpopup").height($("body").prop("scrollHeight"))

        $("#list_popup").show();
        $("#list_area").show();
    })
    $("#close_popup").click(function (e) {
        $("#list_popup").hide();
        $("#list_area").hide();
    })
    $("#search_school").click(function (e) {
        loadSchoolList(1)
    })

    function loadSchoolList(pageNo) {
        const searchKeyword = $("#search_keyword").val()
        $.ajax({
            type: "POST",
            url: `/register/search_school?page=${pageNo}`,
            data: {
                search_keyword: searchKeyword, page: pageNo
            },
            dataType: "JSON",
            success: function(args){
                loadList(args)
                createPageLink(args)
            }
        })
    }
    function loadList (result) {
        const list = result["data"]
        const isEmpty = (list.length === 0)

        const emptyView = document.getElementById("empty_list")
        emptyView.style["display"] = (isEmpty ? '' : "none")

        const listView = document.getElementById("list")
        listView.style["display"] = (isEmpty ? "none" : '')

        const pagingView = document.getElementById("list_paging")
        pagingView.style["display"] = (isEmpty ? "none" : '')

        listView.innerHTML = ''
        const components = []
        for (let i = 0; i < list.length; i++) {
            const item = list[i]
            const component = new SchoolItemComponent(selectedSchoolDetails)
            component.schoolName = item["school_name"]
            component.schoolCode = item["school_code"]
            component.address = item["address"]
            component.attachView(listView)
            components.push(component)
        }
        for (let i = 0; i < components.length; i++) {
            const component = components[i]
            component.update = () => {
                const name = selectedSchoolDetails.schoolName
                $("#mem_school_name").val(name)
                $("#close_popup").click()
            }
            component.attachClickEvent(listView, i)
        }
    }
    function createPageLink(result) {
        const pageNo     = result["page"]
        const pageLink   = result["page_link"]
        const pagingView = document.getElementById("list_paging")
        pagingView.innerHTML = pageLink

        const items = pagingView.getElementsByTagName("a")
        for (let i = 0; i < items.length; i++) {
            const target = items.item(i)
            const url = target.getAttribute('href')
            const extractedPageNo = getPageNo(url)
            target.addEventListener("click", function (e) {
                e.preventDefault();
                loadSchoolList.call(this, extractedPageNo)
            })
        }

        function getPageNo(url) {
            let pageNo = 1
            if(url === null || !url.includes('&'))
                return pageNo

            const splitUrl = url.split('&')
            let lastToken = splitUrl[splitUrl.length-1]
            if (lastToken.includes("page")) {
                lastToken = lastToken.split('=')
                lastToken = lastToken[lastToken.length - 1]
                pageNo = Number(lastToken)
            }
            return pageNo
        }
        // const button = item.getElementsByTagName("button").item(0)
        // button.addEventListener("click", this.select)
    }
    function SelectedSchoolDetails() {
        this.schoolName = ''
        this.schoolCode = ''
    }
    function SchoolItemComponent(selectedDetails) {
        this.schoolName = ''
        this.schoolCode = ''
        this.address = ''
        this.template = () => {
            return `<li>
                        <dt>
                            <strong>${this.schoolName}</strong>
                            <button class="btn grey round">선택</button>
                        </dt>
                        <dd>
                            ${this.address}
                        </dd>
                    </li>`
        }
        this.update = undefined
        this.select = () => {
            selectedDetails.schoolName = this.schoolName
            selectedDetails.schoolCode = this.schoolCode
            selectedDetails.address = this.address
            this.update()
        }
        this.attachView = (target) => {
            target.innerHTML += this.template()
        }
        this.attachClickEvent = (target, index) => {
            const item = target.querySelectorAll("li")[index]
            const button = item.getElementsByTagName("button").item(0)
            button.addEventListener("click", this.select)
        }
    }
    function ActiveButtonComponent(id) {
        this.dom = document.getElementById(id)
        this.checked = false
        this.active = (name) => {
            this.dom.innerHTML = name
            this.dom.style["background-color"] = "#0e123e"
            this.dom.style["border"] = "1px solid #111;"
            this.dom.style["cursor"] = "pointer"
            this.dom.disabled = false
            this.checked = false
        }
        this.deactive = (name) => {
            this.dom.innerHTML = name
            this.dom.style["background-color"] = "#e84c88"
            this.dom.style["border"] = "0"
            this.dom.style["cursor"] = "default"
            this.dom.disabled = true
            this.checked = true
        }
    }

    const idCheckButtonComponent = new ActiveButtonComponent("id_check")
    const codeCheckButtonComponent = new ActiveButtonComponent("confirm_school_code")
    function activateIdCheckButton () {
        idCheckButtonComponent.active("중복확인")
    }
    function activateCodeCheckButton () {
        codeCheckButtonComponent.active("코드확인")
    }
    
    //<![CDATA[
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'kr',
        autoclose: true,
        todayHighlight: true
    });

    $(function() {
        jQuery.validator.addMethod("phone", function (phone_number, element) {
            phone_number = phone_number.replace(/\s+/g, "");
            return this.optional(element) || phone_number.length > 9 &&
                phone_number.match(/^01([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/);
        }, "010-1234-1234 형식으로 입력하세요.");

        $("input:text[numberOnly]").on("keyup", function() {
            $(this).val($(this).val().replace(/[^0-9]/g,""));
        });


        $('#fregisterform').validate({
            onkeyup: false,
            onclick: false,
            rules: {
                <?php if ($this->cbconfig->item('use_recaptcha')) { ?>
                recaptcha : {recaptchaKey:true}
                <?php } else { ?>
                captcha_key : {required: true, captchaKey:true}
                <?php } ?>
            },
            messages: {
                recaptcha: '',
                captcha_key: '자동등록방지용 코드가 올바르지 않습니다.'
            },
            invalidHandler: function(form, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {
                    alert("입력한 정보에 오류가 있습니다.\n다시 한번 확인해 주세요.");
                }
            }
        });

        $('#id_check').click(function() {
            var userid = $('#mem_userid').val();
            if(userid.trim().length <= 0) {
                alert("아이디를 입력 해 주세요.");
                return false;
            }

            $.ajax({
                url: "/register/mem_userid_check",
                async: true,
                type: 'GET',
                data: {id : userid},
                dataType: "json",
                success: function(data) {
                    alert(data.message);
                    if(data.status !== "success")
                        return

                    idCheckButtonComponent.deactive("사용가능")
                    const dom = document.getElementById("mem_userid")
                    dom.removeEventListener("change", activateIdCheckButton)
                    dom.addEventListener("change", activateIdCheckButton)
                }
            });
        });

        $("#confirm_school_code").click(function (e) {
            const writtenCode = $("#mem_school_code").val()
            const selectedCode = selectedSchoolDetails.schoolCode
            const askCode = writtenCode === '' || writtenCode === undefined || selectedCode === '' || selectedCode === undefined
            if (askCode) {
                alert("학교를 먼저 선택해주세요.")
                return
            }
            const wrongCode =  writtenCode !== selectedCode
            if (wrongCode) {
                alert("학교코드가 일치하지 않습니다.")
                return
            }

            alert("학교코드가 확인되었습니다.")
            codeCheckButtonComponent.deactive("인증완료")
            const dom = document.getElementById("mem_school_code")
            dom.removeEventListener("change", activateCodeCheckButton)
            dom.addEventListener("change", activateCodeCheckButton)
        })

        $('#submit').click(function() {
            var userid = $('#mem_userid').val();
            var password = $('#mem_password').val();
            var password_re = $('#mem_password_re').val();
            var username = $('#mem_username').val();
            var nickname = $('#mem_nickname').val($('#mem_username').val());

            var email_id = $('#mem_email_id').val();
            var email_domain = $('#mem_email_domain').val();
            $('#mem_email').val(email_id + "@" + email_domain);

            var mem_phone = $('#mem_phone').val();
            var captcha_key = $('#captcha_key').val();

            var agencyname = $('#mem_school_name').val();
            var	std_count = $('#mem_std_count').val();
            var divisionname = $('#mem_division_nm').val();
            var photo = $('#mem_photo').val();

            if(!idCheckButtonComponent.checked) {
                alert("아이디 중복 확인을 먼저 해주세요.");
                return false;
            }

            if(!codeCheckButtonComponent.checked) {
                alert("학교 코드를 확인해주세요.");
                return false;
            }

            if(userid.length <= 0) {
                alert("아이디를 입력 해 주세요.");
                return false;
            }

            if(password.length <= 0 || password_re.length <= 0) {
                alert("비밀번호를 입력 해 주세요.");
                return false;
            }

            if(username.length <= 0) {
                alert("이름을 입력 해 주세요.");
                return false;
            }

            if(email_id.length <= 0 || email_domain.length <= 0) {
                alert("이메일을 입력 해 주세요.");
                return false;
            }

            // if(mem_phone.length <= 0) {
            //     alert("휴대폰 번호를 입력 해 주세요.");
            //     return false;
            // }

            if(agencyname.length <= 0) {
                alert("소속학교를 입력 해 주세요.");
                return false;
            }

            if(std_count.length <= 0) {
                alert("담당학생수를 입력 해 주세요.");
                return false;
            }

            if(divisionname.length <= 0) {
                alert("학년을 입력 해 주세요.");
                return false;
            }

            if(photo.length <= 0) {
                alert("파일을 첨부하세요.");
                return false;
            }

            if(captcha_key.length <= 0) {
                alert("자동입력방지 문자를 입력 해 주세요.");
                return false;
            }
            $('#fregisterform').submit();
        });

        $('#email_address_type').change(function(){
            var sel_address_type = $(this).val();
            var mem_email_id = $('#mem_email_id').val();

            if(sel_address_type != '') {
                if(sel_address_type != 'direct') {
                    if(mem_email_id == '') {
                        alert('이메일주소 앞주소를 입력하세요.');

                        $('#mem_email_domain').val(sel_address_type);
                        $('#mem_email_id').focus();
                        $("#mem_email_domain").attr("readonly", true);
                        return;
                    } else {
                        var mem_email = mem_email_id + "@" + sel_address_type;
                        $('#mem_email_domain').val(sel_address_type);

                        $('#mem_email').val(mem_email);
                        $('#mem_phone').focus();
                    }
                    $("#mem_email_domain").attr("readonly", true);
                }
                else if(sel_address_type == 'direct') {
                    $('#mem_email_domain').val("");
                    $("#mem_email_domain").attr("readonly", false);
                    $('#mem_email_domain').focus();
                }
            }
        });

        if( $("#mem_username") ) {
            $("#mem_username").val($("#mem_id_name").val());
            //$("#mem_username").attr("readonly", true);
        }

        if($("#mem_nickname")) {
            $("#mem_nickname").val($("#mem_id_name").val());
        }

    });
    //]]>
</script>