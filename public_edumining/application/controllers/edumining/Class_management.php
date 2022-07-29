<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Management class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 
 */
class Class_management extends IMC_Controller
{
    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array');

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring'));
        $this->load->library(array('pagination','IMC_Pagination'));
        $this->load->model('edumining/Class_manage_model');
    }


    // 클래스 관리 - 계정 아이디 중복확인
    public function checkDuplicate(){
        $text = $this->input->post('text', TRUE);
        $duplicateCheck = $this->Class_manage_model->duplicateUserIdCheck($text);

        echo $duplicateCheck;
    }

    // 클래스 관리 - 클래스 목록 가져오기
    public function getClassList(){
        //$teacher_idx = $this->input->post('teacher_idx', TRUE);
        $teacherIdx = $this->member->item('mem_id');
        $page = (int) $this->input->post('page', TRUE);
        $kind = $this->input->post('kind', TRUE);
        $keyword = $this->input->post('keyword', TRUE);

        if(!$page){
            $page = 1;
        }

        $classList = $this->Class_manage_model->getClassList($teacherIdx, $kind, $keyword);
        $classList = array_reverse($classList);

        $param =& $this->querystring;
        $per_page = 10;
        $config['base_url'] = site_url() . '/edumining/class_management/getClassList?' . $param->replace('page');
        $config['num_links'] = 5;
        $config['full_tag_open'] = '<div class="wr_page">';
        $config['full_tag_close'] = '</div>';
        $config['first_tag_open'] = '';
        $config['first_tag_close'] = '';
        $config['last_tag_open'] = '';
        $config['last_tag_close'] = '';
        $config['cur_tag_open'] = '<strong>';
        $config['cur_tag_close'] = '</strong>';
        $config['next_tag_open'] = '';
        $config['next_tag_close'] = '';
        $config['prev_tag_open'] = '';
        $config['prev_tag_close'] = '';
        $config['num_tag_open'] = '';
        $config['num_tag_close'] = '';
        $config['total_rows'] = count($classList);
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $paging = $this->pagination->create_links();

        $return_json = array(
            'msg' => "success",
            'total' => count($classList),
            'paging' => $paging,
            'data' => array_slice($classList, ($page - 1) * $per_page, $per_page),
            'page' => $page
        );

        echo json_encode($return_json);
    }

    // 클래스 관리 - 클래스 추가
    public function createClass(){
        $msg = "success";
        $teacherID = $this->input->post('teacherID', TRUE);
        $teacherIdx = $this->input->post('teacherIdx', TRUE);
        $classTitle = $this->input->post('classTitle', TRUE);
        $classInfo = $this->input->post('classInfo', TRUE);
        $classTopic = $this->input->post('classTopic', TRUE);
        $studentCount = $this->input->post('studentCount', TRUE);
        $classPrefix = $this->input->post('classPrefix', TRUE);

        $classData = array(
            "teacherID" => $teacherID,
            "teacherIdx" => $teacherIdx,
            "classTitle" => $classTitle,
            "classInfo" => $classInfo,
            "classTopic" => $classTopic,
            "studentCount" => $studentCount,
            "classPrefix" => $classPrefix
        );

        $classReturn = $this->Class_manage_model->insertNewClass($classData);
        if ($classReturn['msg'] == "success"){
            $studentData = array(
                "teacherID" => $teacherID,
                "teacherIdx" => $teacherIdx,
                "studentCount" => $studentCount,
                "classIdx" => $classReturn['classIdx'],
                "classPrefix" => $classPrefix
            );

            $studentReturn = $this->Class_manage_model->createNewStudent($studentData);
            if ($studentReturn["msg"] == "error"){
                $msg = "error";
            }
        }else{
            $msg = "error";
        }


        $result = array(
            "msg" => $msg
        );
        echo json_encode($result);
    }

    // 수업 수정
    public function classSave(){
        $classIdx = $this->input->post('classIdx', TRUE);
        $classTitle = $this->input->post('classTitle', TRUE);
        $classInfo = $this->input->post('classInfo', TRUE);
        $classTopic = $this->input->post('classTopic', TRUE);
        $classPrefix = $this->input->post('classPrefix', TRUE);
        $studentCount = $this->input->post('studentCount', TRUE);

        $classData = array(
            "classIdx" => $classIdx,
            "classTitle" => $classTitle,
            "classInfo" => $classInfo,
            "classTopic" => $classTopic,
            "classPrefix" => $classPrefix,
            "studentCount" => $studentCount
        );

        $classSaveResult = $this->Class_manage_model->classEditSave($classData);

        if ($classSaveResult['msg'] == 'success'){
            $result = array(
                "msg" => "success"
            );
        }else{
            $result = array(
                "msg" => "error"
            );
        }

        echo json_encode($result);
    }

    // 수업 삭제
    public function classDelete(){
        $classIdx = $this->input->post('classIdx', TRUE);

        $classDeleteResult = $this->Class_manage_model->classEditDelete($classIdx);

        if ($classDeleteResult['msg'] == 'success'){
            $result = array(
                "msg" => "success"
            );
        }else{
            $result = array(
                "msg" => "error"
            );
        }

        echo json_encode($result);
    }
}
