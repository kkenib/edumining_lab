<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SelfAnalysis model class
 */
class Project_manage_model extends IMC_Model
{
    function __construct() {
        parent::__construct();
    }

    public $userTable = "t_member";
    public $reportTable = "edu_report";
    public $lectureTable = "edu_lecture";
    public $lectureTakeTable = "edu_lecture_take";
    private $submitStatusListPageCount = 5;
    private $studentReportListPageCount = 10;

    private function filterSubmitStatusList($filterType, $searchText) {
        $sql = "";
        switch ($filterType) {
            case "class_name":
                $sql = " AND EL.lecture_name LIKE '%". $searchText . "%' ";
                break;
            case "class_info":
                $sql = " AND EL.lecture_overview LIKE '%". $searchText . "%' ";
                break;
            case "class_code":
                $sql = " AND EL.prefix_id LIKE '%". $searchText . "%' ";
                break;
        }
        return $sql;
    }

    public function getSubmitStatusList($queryCondition) {

        $teacherNo = intval($queryCondition["teacher_no"]);
        $pageNo = intval($queryCondition["page_no"]);
        $filterType = $queryCondition["filter_type"];
        $searchText = $queryCondition["search_text"];
        $filter = $this->filterSubmitStatusList($filterType, $searchText);

        $sql = "SELECT COUNT(*) AS totalCount ";
        $sql .= "FROM edu_lecture AS EL ";
        $sql .= "WHERE user_no = ". $teacherNo;
        $sql .= $filter;
        $sql .= " AND delete_Status  = 'N'";

        $query = $this->datadb->query($sql);
        $queryResult = $query->result_array();
        if (count($queryResult) == 0)
            return array("msg" => "error");

        $pageLimitCount = $this->submitStatusListPageCount;
        $totalItemCount = intval($queryResult[0]["totalCount"]);
        $totalPageCount = intval(ceil($totalItemCount / $pageLimitCount));
        $offset = $pageLimitCount * ($pageNo -1);

        $sql  = "SELECT RST.* ";
        $sql .= "FROM ( ";
        $sql .= "  SELECT  @ROWNUM:=@ROWNUM+1 AS item_no, RST_INN.*";
        $sql .= "  FROM (SELECT @ROWNUM := 0) R, ";
        $sql .= "    (SELECT ";
        $sql .= "      EL.no AS class_no, ";
        $sql .= "      EL.lecture_name AS lecture_name, ";
        $sql .= "      EL.lecture_overview AS lecture_overview, ";
        $sql .= "      EL.lecture_subject AS lecture_subject, ";
        $sql .= "      EL.prefix_id AS prefix_id, ";
        $sql .= "      EL.student_count AS student_count, ";
        $sql .= "      ( ";
        $sql .= "        SELECT COUNT(*) ";
        $sql .= "        FROM edu_report ";
        $sql .= "        WHERE lecture_no = class_no AND ";
        $sql .= "              submit_state = 1 AND ";
        $sql .= "              delete_status = 'N' ";
        $sql .= "      ) AS submitted_report_count ";
        $sql .= "      FROM ". $this->lectureTable . " AS EL ";
        $sql .= "      WHERE EL.user_no = ". $teacherNo ." AND delete_Status  = 'N' ";
        $sql .= $filter;
        $sql .= "      ORDER BY EL.no DESC) AS RST_INN ";
        $sql .= ") AS RST ";
        $sql .= "LIMIT ". $pageLimitCount ." OFFSET ". $offset;
        
        $query = $this->datadb->query($sql);
        $queryResult = $query->result_array();

        $result = array(
            "msg" => "success",
            "total_item_count" => $totalItemCount,
            "total_page_count" => $totalPageCount,
            "current_page_no" => $pageNo,
            "list" => $queryResult
        );

        return $result;
    }

    public function getStudentReportStatusList($queryCondition) {

        $teacherNo = intval($queryCondition["teacher_no"]);
        $pageNo = intval($queryCondition["page_no"]);
        $classNo = intval($queryCondition["class_no"]);
        $unsubmitStatus = intval($queryCondition["unsubmit_status"]);
        $align = ($unsubmitStatus == 1) ? "ORDER BY submit_state ASC, user_no DESC " :
                                          "ORDER BY submit_state DESC, permit_state DESC, user_no DESC ";

        $sql  = " SELECT COUNT(*) AS totalCount ";
        $sql .= " FROM ";
        $sql .= " ( ";
        $sql .= "   SELECT T1.lecture_no, T1.user_no ";
        $sql .= "   FROM ". $this->lectureTakeTable ." AS T1 ";
        $sql .= "   JOIN ". $this->lectureTable ." AS T2 ";
        $sql .= "     ON T1.lecture_no = T2.no AND T2.delete_status = 'N'";
        $sql .= "   WHERE T1.user_no != ". $teacherNo ." AND T1.lecture_no = ". $classNo;
        $sql .= " ) AS ELT ";
        $sql .= " INNER JOIN ". $this->userTable ." AS M ";
        $sql .= "         ON M.mem_denied = 0 AND ";
        $sql .= "            M.mem_id = ELT.user_no ";
        $sql .= " LEFT JOIN ". $this->reportTable ." AS ER ";
        $sql .= "        ON ER.lecture_no = ELT.lecture_no AND ";
        $sql .= "           ER.user_no = ELT.user_no AND ";
        $sql .= "           ER.submit_state > 0 ";

        $query = $this->datadb->query($sql);
        $queryResult = $query->result_array();
        if (count($queryResult) == 0)
            return array("msg" => "error");

        $pageLimitCount = $this->studentReportListPageCount;
        $totalItemCount = intval($queryResult[0]["totalCount"]);
        $totalPageCount = intval(ceil($totalItemCount / $pageLimitCount));
        $offset = $pageLimitCount * ($pageNo -1);

        $sql  = "SELECT RST.* FROM ( ";
        $sql .= "SELECT ";
        $sql .= "  @ROWNUM:=@ROWNUM+1 AS item_no, ";
        $sql .= "  INNER_RST.* ";
        $sql .= "FROM ";
        $sql .= "  (SELECT @ROWNUM := 0) R, ";
        $sql .= "  ( ";
        $sql .= "    SELECT ";
        $sql .= "      ELT.lecture_no 	AS class_no, ";
        $sql .= "      ELT.user_no 		AS user_no, ";
        $sql .= "      IFNULL(ER.no, 0) AS report_no, ";
        $sql .= "      M.mem_userid     AS user_id, ";
        $sql .= "      M.mem_username 	AS user_name, ";
        $sql .= "      IFNULL(ER.submit_state, 0) AS submit_state, ";
        $sql .= "      IFNULL(ER.submit_date, '') AS submit_date, ";
        $sql .= "      IFNULL(ER.permit_state, 0) AS permit_state ";
        $sql .= "    FROM ";
        $sql .= "      ( ";
        $sql .= "        SELECT T1.lecture_no, T1.user_no ";
        $sql .= "        FROM ". $this->lectureTakeTable ." AS T1 ";
        $sql .= "        JOIN ". $this->lectureTable ." AS T2 ";
        $sql .= "          ON T1.lecture_no = T2.no AND T2.delete_status = 'N'";
        $sql .= "        WHERE T1.user_no != ". $teacherNo ." AND T1.lecture_no = ". $classNo;
        $sql .= "      ) AS ELT ";
        $sql .= "    INNER JOIN ". $this->userTable ." AS M ";
        $sql .= "            ON M.mem_denied = 0 AND ";
        $sql .= "               M.mem_id = ELT.user_no ";
        $sql .= "    LEFT JOIN ". $this->reportTable ." AS ER ";
        $sql .= "           ON ER.lecture_no = ELT.lecture_no AND ";
        $sql .= "              ER.user_no = ELT.user_no AND ";
        $sql .= "              ER.submit_state > 0 ";
        $sql .= $align;
        $sql .= "  ) AS INNER_RST ";
        $sql .= ") AS RST ";
        $sql .= "LIMIT ". $pageLimitCount ." OFFSET " . $offset;

        $query = $this->datadb->query($sql);
        $queryResult = $query->result_array();

        $result = array(
            "msg" => "success",
            "total_item_count" => $totalItemCount,
            "total_page_count" => $totalPageCount,
            "current_page_no" => $pageNo,
            "list" => $queryResult
        );

        return $result;
    }

    public function viewReport($queryCondition) {
        $targetNo = $queryCondition["report_no"];
        $result = array(
            "msg" => "error",
            "report_no" => $targetNo,
            "title" => '',
            "create_date" => '',
            "update_date" => '',
            "submit_date" => '',
            "submit_state" => 0,
            "permit_state" => 0,
            "user_id" => 0,
            "class_info" => '',
            "list" => array()
        );

        $sql  = " SELECT ";
        $sql .= "   title, ";
        $sql .= "   IFNULL(create_date, '') AS create_date, ";
        $sql .= "   IFNULL(update_date, '') AS update_date, ";
        $sql .= "   submit_state, ";
        $sql .= "   IFNULL(submit_date, '') AS submit_date, ";
        $sql .= "   permit_state, ";
        $sql .= "   (SELECT mem_userid FROM t_member WHERE mem_id= ER.user_no) AS user_id,";
        $sql .= "   (SELECT lecture_overview FROM edu_lecture WHERE no = ER.lecture_no) AS class_info";
        $sql .= " FROM edu_report AS ER ";
        $sql .= " WHERE no = ". $targetNo;
        $query = $this->datadb->query($sql);
        $queryResult = $query->result_array();

        if (count($queryResult) == 0)
            return $result;

        $result["msg"] = "success";
        $result["title"] = $queryResult[0]["title"];
        $result["create_date"] = $queryResult[0]["create_date"];
        $result["update_date"] = $queryResult[0]["update_date"];
        $result["submit_date"] = $queryResult[0]["submit_date"];
        $result["submit_state"] = $queryResult[0]["submit_state"];
        $result["permit_state"] = $queryResult[0]["permit_state"];
        $result["user_id"] = $queryResult[0]["user_id"];
        $result["class_info"] = $queryResult[0]["class_info"];

        $sql  = " SELECT ";
        $sql .= "   ERU.* , ";
        $sql .= "   EDA.anal_type AS anal_type , ";
        $sql .= "   EDA.chapter AS chapter, ";
        $sql .= "   EDA.center_word AS center_word, ";
        $sql .= "   EDA.window_size AS window_size, ";
        $sql .= "   EDA.visual_text_color AS visual_text_color, ";
        $sql .= "   EDA.visual_bg_color AS visual_bg_color, ";
        $sql .= "   EDA.file_path AS file_path ";
        $sql .= " FROM ( ";
        $sql .= "   SELECT " ;
        $sql .= "     artifact_no, ";
        $sql .= "     contents, ";
        $sql .= "     unit_type, ";
        $sql .= "     unit_order ";
        $sql .= "   FROM edu_report_unit ";
        $sql .= "   WHERE report_no= ". $targetNo;
        $sql .= " ) AS ERU ";
        $sql .= " LEFT JOIN edu_data_artifact AS EDA ";
        $sql .= "   ON EDA.no = ERU.artifact_no ";
        $sql .= " ORDER BY ERU.unit_order ASC; ";
        $query = $this->datadb->query($sql);
        $queryResult = $query->result_array();
        if (count($queryResult) > 0) {
            $result["list"] = $queryResult;
        }

        return $result;
    }


    public function submitBestReport($queryCondition) {
        $targetNoList = $queryCondition["report_no_list"];
        $noQuery = "";
        if (count($targetNoList) > 0) {
            for ($i = 0; $i < count($targetNoList); $i++) {
                $no = $targetNoList[$i];
                $noQuery .= $no . ($i == count($targetNoList) - 1 ? '' : ',');
            }
            $noQuery = "WHERE no IN (" . $noQuery . ")";
        }

        $sql  = " UPDATE ". $this->reportTable;
        $sql .= " SET ";
        $sql .= " submit_state = 1, ";
        $sql .= " permit_state = 1, ";
        $sql .= " submit_date  = NOW() ";
        $sql .= $noQuery;

        $query = $this->datadb->query($sql);
        $result = array("msg" => ($query == true) ? "success" : "error");
        return $result;
    }

    public function returnReport($queryCondition) {
        $targetNoList = $queryCondition["report_no_list"];
        $noQuery = "";
        if (count($targetNoList) > 0) {
            for ($i = 0; $i < count($targetNoList); $i++) {
                $no = $targetNoList[$i];
                $noQuery .= $no . ($i == count($targetNoList) - 1 ? '' : ',');
            }
            $noQuery = "WHERE no IN (" . $noQuery . ")";
        }

        $sql  = " UPDATE ". $this->reportTable;
        $sql .= " SET ";
        $sql .= " submit_state = 2, ";
        $sql .= " permit_state = 0, ";
        $sql .= " submit_date  = NOW() ";
        $sql .= $noQuery;

        $query = $this->datadb->query($sql);
        $result = array("msg" => ($query == true) ? "success" : "error");
        return $result;

    }

    function clearPassword($queryCondition) {
        $targetNo = $queryCondition["user_no"];
        $targetId = $queryCondition["user_id"];
        $cryptedPass = password_hash($targetId, PASSWORD_BCRYPT);

        $sql  = " UPDATE ". $this->userTable;
        $sql .= " SET ";
        $sql .= " mem_password = '". $cryptedPass ."'";
        $sql .= " WHERE mem_id = ". $targetNo;

        $query = $this->datadb->query($sql);
        $result = array("msg" => ($query == true) ? "success" : "error");
        return $result;
    }

}