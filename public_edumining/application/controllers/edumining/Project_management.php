<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_management extends IMC_Controller
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
        $this->load->model('edumining/Project_manage_model');
    }

    public function getSubmitStatusList() {
        $queryCondition = array(
            "teacher_no" => $this->input->post('teacher_no', TRUE),
            "page_no" => $this->input->post('page_no', TRUE),
            "filter_type" => $this->input->post('filter_type', TRUE),
            "search_text" => $this->input->post('search_text', TRUE),
        );

        $result = $this->Project_manage_model->getSubmitStatusList($queryCondition);

        $totalItemCount = $result["total_item_count"];
        $list = $result["list"];
        for ($i=0; $i<count($list); $i++) {
            $item = $result["list"][$i];
            $result["list"][$i]["item_no"] = $totalItemCount - $item["item_no"] + 1; //번호 수정

            $studentCount = $item["student_count"];
            $submittedReportCount = $item["submitted_report_count"];
            $submitRate = $studentCount == 0 ? 0 : ($submittedReportCount / $studentCount);
            $result["list"][$i]["submit_rate"] = floor($submitRate*100) / 100; //소수점 2자리까지만.
        }
        echo json_encode($result);
    }

    private function getSubmitStateText($submitState, $permitState) {
        $stateText = "";
        switch($permitState) {
            case 0:
                $stateText = $submitState == 0 ? "미제출" : (
                $submitState == 1 ? "제출완료"  : (
                $submitState == 2 ? "반려" : ""));
                break;
            case 1:
                $stateText = "승인대기";
                break;
            case 2:
                $stateText = "승인";
                break;
            case 3:
                $stateText = "승인불가";
                break;
        }
        return $stateText;
    }

    public function getStudentReportStatusList() {
        $queryCondition = array(
            "teacher_no" => $this->input->post('teacher_no', TRUE),
            "page_no" => $this->input->post('page_no', TRUE),
            "class_no" => $this->input->post('class_no', TRUE),
            "unsubmit_status" => $this->input->post('unsubmit_status', TRUE),
        );

        $result = $this->Project_manage_model->getStudentReportStatusList($queryCondition);
        $totalItemCount = $result["total_item_count"];
        $list = $result["list"];
        for ($i=0; $i<count($list); $i++) {
            $item = $result["list"][$i];
            $result["list"][$i]["item_no"] = $totalItemCount - $item["item_no"] + 1; //번호 수정

            $submitState = intval($result["list"][$i]["submit_state"]);
            $permitState = intval($result["list"][$i]["permit_state"]);
            $submitStateText = $this->getSubmitStateText($submitState, $permitState);
            $result["list"][$i]["submit_state_text"] = $submitStateText;
        }
        echo json_encode($result);
    }

    public function submitBestReport()
    {
        $queryCondition = array("report_no_list" => $this->input->post('report_no_list', TRUE));
        $result = $this->Project_manage_model->submitBestReport($queryCondition);
        echo json_encode($result);
    }

    public function returnReport()
    {
        $queryCondition = array("report_no_list" => $this->input->post('report_no_list', TRUE));
        $result = $this->Project_manage_model->returnReport($queryCondition);
        echo json_encode($result);
    }

    public function viewReport()
    {
        $queryCondition = array("report_no" => $this->input->post('report_no', TRUE));
        $result = $this->Project_manage_model->viewReport($queryCondition);
        for ($i=0; $i < count($result["list"]); $i++) {
            $item = $result["list"][$i];
            $data = array("no" => $item["artifact_no"]);
            $resp = $this->curl_post("/getChartData", $data);
            $result["list"][$i]["data"] = $resp;
        }
        echo json_encode($result);
    }

    public function clearPassword()
    {
        $queryCondition = array(
            "user_no" => $this->input->post('user_no', TRUE),
            "user_id" => $this->input->post('user_id', TRUE)
        );
        $result = $this->Project_manage_model->clearPassword($queryCondition);
        echo json_encode($result);
    }
}