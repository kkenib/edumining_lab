<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="contents">
    <!-- S : 아이디/비밀번호찾기 -->
    <div class="box_member">
        <div class="tit_member">
            <span class="logo" title="부산에듀마이닝 Logo"><img src="<?php echo base_url('views/login/images/logo.svg'); ?>" alt=""></span>
            <h1>아이디/비밀번호찾기</h1>
            <p>회원가입시 기재하신 성명과 이메일 정보를 적어주세요. <br>회원님의 아이디와 초기화된 비밀번호를 알려 드립니다.</p>
        </div>

        <div id="validation_box"></div>
        <input type="hidden" name="findtype" value="findidpw" />

        <dl>
            <dt>이름</dt>
            <dd>
                <input type="text" name="idpw_name" id="idpw_name" title="이름 입력" placeholder="이름을 입력하세요"/>
            </dd>
            <dt>이메일</dt>
            <dd class="email">
				<span>
					<input type="text" name="idpw_email" id="idpw_email" title="이메일 입력" placeholder="이메일을 입력하세요"/>
					<i>@</i>
					<input type="text" name="" id="idpw_email_addr" title="이메일" value="">
				</span>
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
            </dd>
        </dl>
        <button id="find_idpw" type="button" onclick="checkContents()" class="btn navy round lg w100p">아이디/비밀번호 찾기</button>

        <?php
        // 이부분은 숨겨저 있던 부분이라서 다시 요청해야 함.
        if ($this->cbconfig->item('use_register_email_auth')) {
            $attributes = array('name' => 'verifyemailform', 'id' => 'verifyemailform');
            echo form_open(current_full_url(), $attributes);
            ?>
            <input type="hidden" name="findtype" value="verifyemail" />
            <div class="tit_member">
                <h1>인증메일 재발송</h1>
                <p>회원가입이나, 이메일주소 변경 후 인증 메일을 받지 못한 경우 다시 받을 수 있습니다.</p>
            </div>
            <dl>
                <dt class="skip">인증메일 재발송</dt>
                <dd class="id">
                    <input type="email" name="verify_email" id="verify_email" placeholder="이메일을 입력하세요" />
                    <button type="submit">재발송</button>
                </dd>
            </dl>
            <?php
            echo form_close();
            $attributes = array('name' => 'changeemailform', 'id' => 'changeemailform');
            echo form_open(current_full_url(), $attributes);
            ?>
            <input type="hidden" name="findtype" value="changeemail" />
            <div class="tit_member">
                <h1>이메일 주소 변경</h1>
                <p>인증메일이 도착하지 않아 어려움을 겪고 계시다면, 다른 이메일 주소로 변경해 인증해보세요.</p>
            </div>
            <dl>
                <dt>아이디</dt>
                <dd>
                    <input type="text" name="change_userid" id="change_userid" class="input" placeholder="아이디를 입력하세요" />
                </dd>
                <dt>비밀번호</dt>
                <dd>
                    <input type="password" name="change_password" id="change_password" class="input" placeholder="비밀번호를 입력하세요" />
                </dd>
                <dt>새로운 이메일 주소</dt>
                <dd>
                    <input type="email" name="change_email" id="change_email" class="input" placeholder="이메일을 입력하세요" />
                </dd>
            </dl>
            <button type="submit" class="btn navy round lg w100p">새로운 이메일주소로 인증메일 재발송</button>
            <?php
            echo form_close();
        }
        ?>
    </div>
    <!-- E : 아이디/비밀번호찾기 -->


</div>


<script type="text/javascript">

    function Req() {
        this.url = undefined
        this.data = {}
        this.method = "POSt"
        this.responseType = "json"
        this.requestHeader = "application/x-www-form-urlencoded"
        this.request = new XMLHttpRequest()
        this.getQueryString = () => {
            return Object.entries(this.data).map(e => e.join('=')).join('&')
        }
        this.async = () => {
            if(!this.request) {
                console.error("Can't make request.")
                return false
            }

            this.request.open(this.method, this.url, false)
            this.request.setRequestHeader("Content-Type", this.requestHeader)

            let result = undefined
            this.request.addEventListener("readystatechange", function (event) {
                const { target } = event;
                if (target.readyState === XMLHttpRequest.DONE) {
                    const { status } = target;
                    if (status === 0 || (status >= 200 && status < 400)) { // 요청이 정상적으로 처리 된 경우
                        result = target.response
                    }
                }
            })
            this.request.send(this.getQueryString())
            return JSON.parse(result)
        }
    }
    const validationBox = document.getElementById("validation_box")

    function checkContents() {
        const name = document.getElementById("idpw_name")
        if(name.value === '') {
            validationBox.innerHTML = '<div class="alert alert-warning" role="alert">이름을 입력해주세요.</div>';
            return
        }

        const emailPrefix = document.getElementById("idpw_email")
        const emailSuffix = document.getElementById("idpw_email_addr")
        if(emailPrefix.value === '' || emailSuffix.value === '') {
            const validationBox = document.getElementById("validation_box")
            validationBox.innerHTML = '<div class="alert alert-warning" role="alert">이메일 주소를 입력해주세요.</div>';
            return
        }

        const email = emailPrefix.value + '@' + emailSuffix.value
        modifyPassword(name.value, email)
    }

    function modifyPassword(name, email) {

        const req = new Req()
        req.url = "/findaccount/check_name_email"
        req.data = { name: name, email: email }

        const result = req.async()
        let message = "회원 정보가 존재하지 않습니다."
        if(result.is_success) {
            message = `
             회원님의 비밀번호를 초기화 하였습니다. <br/><br/> &emsp;
             - 아이디 : ${result.user_id} <br /> &emsp;
             - 비밀번호 : ${result.new_password}`
        }

        validationBox.innerHTML = `<div class="alert alert-dismissible alert-info">${message}</div>`
            // <button type="button" class="close alertclose">&times;</button>
    }

    const selector = document.getElementById("email_address_type")
    selector.addEventListener("change", selectEmail)
    function selectEmail() {
        const selector = document.getElementById("email_address_type")
        for (let i=0; i<selector.options.length; i++) {
            if(selector.options[i].selected) {
                const emailAddr = document.getElementById("idpw_email_addr")
                emailAddr.value = selector.options[i].value
                break
            }
        }
    }
</script>