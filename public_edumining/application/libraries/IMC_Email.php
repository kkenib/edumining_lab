<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__ . '/CB/CB_Email.php';

/**
 * CodeIgniter Email Class
 */
class IMC_Email extends CB_Email
{
    public function __construct()
    {
        parent::__construct();
    }

    // 회원가입시 사용할 email 뒷주소
    public function get_email_list() {
        $emails = array(
            '@naver.com',
            '@gmail.com',
            '@daum.net',
            '@hanmail.com',
            '@nate.com',
            '@lycos.co.kr',
            '@korea.com',
            '@hotmail.com',
            '@yahoo.co.kr',
            '@yahoo.com'
        );

        return $emails;
    }
}
