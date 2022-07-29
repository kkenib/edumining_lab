<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SelfAnalysis model class
 */
class Analysis_model extends IMC_Model
{

    // ����Ű���� ��� �� ����
    public $report = "edu_report";
	public $data_artifact = "edu_data_artifact";
    public $report_unit = "edu_report_unit";

    function __construct() {
        parent::__construct();
    }

    /**
     * 원본 데이터 리스트 가져오기
     */
    public function get_rawdata_list($user_no, $data_type, $search_keyword, $page_num) {
        $mem_id = $this->member->item('mem_id');
        $mem_level = $this->member->item('mem_level');
        $qry = sprintf(
            "
            SELECT o.*, a.edit_step, a.user_no AS student_no
            FROM edu_data_overview o
            LEFT OUTER JOIN (
            		SELECT *
            		FROM edu_data_artifact
            		WHERE user_no = %d AND edit_step = 1
            ) a
            ON o.NO = a.origin_no
            WHERE o.delete_status = 'N' AND o.data_type = %s
        ", $user_no, $data_type);

        if ($search_keyword != "") {
            $qry .= " AND o.data_name LIKE CONCAT('%', '".$search_keyword."', '%')";
        }

        if ($data_type == 1 || $data_type == 2){
            if($mem_level == 1) {
                $qry .= "AND o.user_no = (SELECT mem_id FROM t_member WHERE mem_userid = (SELECT mem_parent FROM t_member WHERE mem_id = " . $mem_id . "))";
            }else if($mem_level == 2){
                $qry .= "AND o.user_no = " . $user_no;
            }
        }

        $qry .= " ORDER BY o.no DESC";

        $offset = ($page_num-1)*10;
        $qry .= sprintf(" LIMIT 10 OFFSET %d", $offset);
        //print_r($qry);
        $query = $this->datadb->query($qry);

        $result = $query->result_array();

        return $result;
    }

    /**
     * 원본 데이터 리스트 카운트
     */
    public function get_rawdata_list_count($user_no, $data_type, $search_keyword) {

        $qry = sprintf(
            "
            SELECT COUNT(*) as cnt
            FROM edu_data_overview o
            LEFT OUTER JOIN (
            		SELECT *
            		FROM edu_data_artifact
            		WHERE user_no = %d AND edit_step = 1
            ) a
            ON o.NO = a.origin_no
            WHERE o.delete_status = 'N' AND o.data_type = %s
        ", $user_no, $data_type);

        if ($search_keyword != "") {
            $qry .= " AND o.data_name LIKE CONCAT('%', '".$search_keyword."', '%')";

        }

        $mem_id = $this->member->item('mem_id');
        $mem_level = $this->member->item('mem_level');
        if ($data_type == 1 || $data_type == 2){
            if($mem_level == 1) {
                $qry .= "AND o.user_no = (SELECT mem_id FROM t_member WHERE mem_userid = (SELECT mem_parent FROM t_member WHERE mem_id = " . $mem_id . "))";
            }else if($mem_level == 2){
                $qry .= "AND o.user_no = " . $user_no;
            }
        }

        $query = $this->datadb->query($qry);

        $result = $query->row();

        return $result;
    }

    /**
     * 선택한 원본 데이터 정보 가져오기
     */
    public function get_rawdata_one($data_no) {

        $this->datadb->select("*");

        $this->datadb->where("no", $data_no);

        $query = $this->datadb->get("edu_data_overview");

        $result = $query->row();

        return $result;
    }

    /**
     * 편집 상태 불러오기
     */
    public function get_edit_status($data_no, $user_no) {

        $this->datadb->select("edit_step");

        $this->datadb->where("user_no", $user_no);
        $this->datadb->where("origin_no", $data_no);

        $query = $this->datadb->get("edu_data_artifact");

        $result = $query->row();

        return $result;
    }

    /**
     * 텍스트 마이닝 진행단계 저장
     */
    public function save_edit_step($param) {

        if ($param["edit_step"] == 0 || $param["edit_step"] == '') {

            $insert_data = array(
                "user_no" => $param["user_no"],
                "lecture_no" => $param["lecture_no"],
                "origin_no" =>  $param["data_no"],
                "anal_type" => 0,
                "edit_step" =>  1,
                "file_path" => ""
            );

            $result = $this->datadb->insert("edu_data_artifact", $insert_data);

        } else {

            $this->datadb->set('update_date', 'NOW()', FALSE);

            $where = array(
                "user_no" => $param["user_no"],
                "origin_no" =>  $param["data_no"],
                "edit_step" =>  1
            );

            $result = $this->datadb->update("edu_data_artifact", $update_data, $where);

        }

        return $result;
    }

    /**
     * 시각화 정보 저장
     */
    public function save_my_chart_info($param) {

        $insert_data = array(
            "user_no" => $param["user_no"],
            "lecture_no" => $param["lecture_no"],
            "origin_no" =>  $param["origin_no"],
            "chapter" => $param["chapter"],
            "center_word" => $param["center_word"],
            "window_size" => $param["window_size"],
            "edit_step" => 3,
            "anal_type" => $param["anal_type"],
            "file_path" => $param["file_path"],
            "title" => $param["title"],
            "visual_text_color" => $param["text_color"],
            "visual_bg_color" => $param["bg_color"],
        );

        $result = $this->datadb->insert("edu_data_artifact", $insert_data);

        return $result;
    }

    /**
     * 데이터 시각화 목록
     */
    function get_visual_list($user_no) {

        $this->datadb->select("o.data_name, a.no, a.anal_type, a.title, a.chapter, a.center_word, a.window_size");

        $this->datadb->join('edu_data_overview o', 'a.origin_no = o.no', 'inner');

        $this->datadb->where("a.edit_step", "3");
        $this->datadb->where("a.user_no", $user_no);

        $this->datadb->order_by("a.update_date", "DESC");

        $query = $this->datadb->get("edu_data_artifact a");

        $result = $query->result_array();

        return $result;
    }

    /**
     * 내 데이터 시각화 목록 카운트
     */
    function get_visual_list_count($user_no) {

        $this->datadb->select("COUNT(*) as cnt");

        $this->datadb->where("edit_step", "3");
        $this->datadb->where("user_no", $user_no);

        $query = $this->datadb->get("edu_data_artifact");

        $result = $query->row();

        return $result;
    }

    /**
     * 데이터 시각화 가져오기
     */
    function get_visual_one($no) {

        $this->datadb->select("*");

        $this->datadb->where("no", $no);

        $query = $this->datadb->get("edu_data_artifact");

        $result = $query->row_array();

        return $result;
    }

    /**
     * 데이터 시각화 삭제
     */
    function delete_visual_one($user_no, $no) {

	/*	$this->datadb->trans_start();
		$del_rptUnit = array(
			"artifact_no" => $no
		);

		$this->datadb->delete("edu_report_unit",$del_rptUnit);
	*/

        $delete_data = array(
            "user_no" => $user_no,
            "no" => $no
        );

        $result = $this->datadb->delete("edu_data_artifact", $delete_data);
    //  $this->datadb->trans_complete();
        return $result;
    }

    /**
     * 공지사항 리스트 가져오기
     */
    function getArticleList($article_type, $search_keyword, $page_num, $user_no=0, $parent='') {

        $offset = ($page_num-1)*10;
        $filter = '';
        if ($search_keyword != "") {
            $filter = " AND EBA.title LIKE CONCAT('%', '".$search_keyword."', '%') ";
        }

        $qry  = "SELECT RST.* ";
        $qry .= "FROM ( ";
        $qry .= "  SELECT  @ROWNUM:=@ROWNUM+1 AS item_no, RST_INN.*";
        $qry .= "  FROM (SELECT @ROWNUM := 0) R,";
        $qry .= "       (SELECT ";
        $qry .= "          EBA.no AS article_no, ";
        $qry .= "          EBA.user_no AS user_no, ";
        $qry .= "          EBA.title AS title, ";
        $qry .= "          EBA.contents AS contents, ";
        $qry .= "          EBA.create_date AS create_date, ";
        $qry .= "          EBA.update_date AS update_date, ";
        $qry .= "          EBA.view_count AS view_count, ";
        $qry .= "          EBA.notice_status AS notice_status, ";
        $qry .= "          TM.mem_username AS user_name ";
        $qry .= "        FROM edu_board_article AS EBA ";
        $qry .= "        JOIN t_member AS TM ON TM.mem_id = EBA.user_no ";
        $qry .= "        WHERE EBA.article_type = $article_type AND EBA.delete_status = 'N' ";

        if($article_type == 0)
            $qry .= "AND (TM.mem_userid = '$parent' OR EBA.user_no = $user_no) ";

        $qry .=          $filter;
        $qry .= "        ORDER BY EBA.notice_status ASC, EBA.create_date DESC, EBA.no DESC) AS RST_INN ";
        $qry .= ") AS RST ";
        $qry .= "LIMIT 10 OFFSET ". $offset;

        $query = $this->datadb->query($qry);
        $result = $query->result_array();

        $totalItemCount = $this->getArticleListCount($article_type, $search_keyword, $user_no, $parent)->cnt;
        for ($i=0; $i<count($result); $i++) {
            $item = $result[$i];
            $result[$i]["item_no"] = $totalItemCount - $item["item_no"] + 1; //번호 수정
        }
        return $result;
    }

    /**
     * 공지사항 카운트 가져오기
     */
    function getArticleListCount($article_type, $search_keyword, $userNo=0, $parent='') {

        $qry  = " SELECT COUNT(*) as cnt ";
        $qry .= " FROM edu_board_article AS EBA ";
        $qry .= " JOIN t_member AS TM ON TM.mem_id = EBA.user_no ";
        $qry .= " WHERE article_type = $article_type  AND EBA.delete_status = 'N' ";
        if($article_type == 0)
            $qry .= " AND (TM.mem_userid = '$parent' OR EBA.user_no = $userNo) ";
        if ($search_keyword != "")
            $qry .= " AND EBA.title LIKE CONCAT('%', '".$search_keyword."', '%')";

        $query = $this->datadb->query($qry);
        $result = $query->row();
        return $result;
    }

   /**
	*	리포트 목록 불러오기
	*/
	function getReportList($search_report,$user_no){
		$sql = "SELECT * FROM %s";
		$sql = sprintf($sql, $this->report);
		$sql .= " WHERE (user_no = %s)";
		$sql = sprintf($sql, $user_no);

		if(!empty($search_report)){
			$sql .= " AND title like '%".$search_report."%'";
		}

		$sql .= "ORDER BY no DESC";

		$query = $this->datadb->query($sql);
		$result = $query->result_array();

		return $result;
	}


  /**
	*	리포트 제출하기
	*/
	function submitReport($no, $user_no, $date){
        if($user_no > 0) {
            $this->datadb->trans_start();
            $sql = "UPDATE %s SET submit_state = 0 WHERE submit_state = 1 AND user_no = $user_no";
            $sql = sprintf($sql, $this->report);
            $this->datadb->query($sql);
            $sql = "UPDATE %s SET submit_state = 1, submit_date = ? WHERE no = ? AND user_no = $user_no";
            $sql = sprintf($sql, $this->report);
            $this->datadb->query($sql,array($date,$no));
            $this->datadb->trans_complete();
        }
	}

  /**
	*	리포트에 시각화 추가하기
	*/
	function getReportObject($no){
		$sql = "SELECT * FROM %s WHERE no = ?";
		$sql = sprintf($sql, $this->data_artifact);
		$query = $this->datadb->query($sql,$no);
		$result = $query->result_array();

		return $result[0];
	}

  /**
	*	리포트 수정 및 작성하기
	*/
	function insertOrUpdateReport($date,$title,$artifact_no,$contents,$unit_type,$unit_order,$user_no,$have_no){
		$this->datadb->trans_start();
		if(!empty($have_no)){
			$sql = "UPDATE edu_report SET title = ?,update_date = ? WHERE no = ?";
			$this->datadb->query($sql, array($title,$date,$have_no));
			$sql = "DELETE FROM edu_report_unit WHERE report_no = ?";
			$this->datadb->query($sql, $have_no);
			for($i = 0; $i < count($unit_order); $i++){
				$sql = "INSERT INTO edu_report_unit (report_no,artifact_no,contents,unit_type,unit_order) VALUES (?,?,?,?,?)";
				$this->datadb->query($sql, array($have_no,$artifact_no[$i],$contents[$i],$unit_type[$i],$unit_order[$i]));
			}
		}else{

            $sql = "SELECT lecture_no FROM edu_lecture_take WHERE user_no = ". $user_no;
            $query = $this->datadb->query($sql);
            $lecture_no = $query->result_array()[0];

            $sql = "INSERT INTO edu_report (user_no,lecture_no,title) VALUES (?, ?, ?)";
            $this->datadb->query($sql, array($user_no,$lecture_no,$title));
            $no = $this->datadb->insert_id();
            for($i = 0; $i < count($unit_order); $i++){
                $sql = "INSERT INTO edu_report_unit (report_no,artifact_no,contents,unit_type,unit_order) VALUES (?,?,?,?,?)";
                $this->datadb->query($sql, array($no,$artifact_no[$i],$contents[$i],$unit_type[$i],$unit_order[$i]));
            }
		}
		$this->datadb->trans_complete();
	}
	/*function insertReportItem($report_no,$artifact_no,$contents,$unit_type,$unit_order){

	}*/

  /**
	*	리포트 내용 불러오기
	*/
	function getReport($no){
		$sql = "SELECT A.*,B.title FROM edu_report_unit A LEFT JOIN edu_report B ON(A.report_no = B.no) WHERE report_no = ? ORDER BY unit_order";
		$query = $this->datadb->query($sql, $no);
		$result = $query->result_array();
		return $result;
	}

  /**
	*	차트 불러오기
	*/
	function getChart($no){
		$sql = "SELECT * FROM edu_data_artifact WHERE no = ?";
		$query = $this->datadb->query($sql, $no);
		$result = $query->result_array();
		return $result;
	}

    public function getGreatList($queryCondition) {

        $search_keyword = $queryCondition["search_keyword"];
        $filter = '';
        if ($search_keyword != "") {
            $filter = " AND ER.title LIKE CONCAT('%', '".$search_keyword."', '%') ";
        }

        $sql  = "SELECT COUNT(*) AS totalCount ";
        $sql .= "FROM edu_report AS ER ";
        $sql .= "JOIN t_member AS TM ON TM.mem_id = ER.user_no AND TM.mem_class_no > 0 ";
        $sql .= "JOIN edu_lecture AS EL ON EL.no = ER.lecture_no ";
        $sql .= "WHERE ER.permit_state = 2 AND ER.delete_Status  = 'N' ";
        $sql .= $filter;
        $query = $this->datadb->query($sql);
        $result = $query->row();

        $totalCount = intval($result->totalCount);
        if ($totalCount == 0)
            return array("msg" => "error");

        $pageNo = intval($queryCondition["page_no"]);
        $pageLimitCount = 6;
        $totalItemCount = $totalCount;
        $totalPageCount = intval(ceil($totalItemCount / $pageLimitCount));
        $offset = $pageLimitCount * ($pageNo -1);

        $sql  = "SELECT RST.* ";
        $sql .= "FROM ( ";
        $sql .= "  SELECT ";
        $sql .= "    @ROWNUM:=@ROWNUM+1 AS item_no, ";
        $sql .= "    ER.no AS report_no, ";
        $sql .= "    ER.user_no AS user_no, ";
        $sql .= "    ER.lecture_no AS lecture_no, ";
        $sql .= "    ER.title AS title, ";
        $sql .= "    ER.create_date AS create_date, ";
        $sql .= "    ER.update_date AS update_date,";
        $sql .= "    EL.lecture_overview AS lecture_overview,";
        $sql .= "    TM.mem_userid AS user_id";
        $sql .= "  FROM (SELECT @ROWNUM := 0) R, edu_report AS ER ";
        $sql .= "  JOIN t_member AS TM ON TM.mem_id = ER.user_no AND TM.mem_class_no > 0 ";
        $sql .= "  JOIN edu_lecture AS EL ON EL.no = ER.lecture_no ";
        $sql .= "  WHERE ER.permit_state = 2 AND ER.delete_Status  = 'N' ";
        $sql .= $filter;
        $sql .= "  ORDER BY ER.create_date DESC ";
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


    public function insertFile($queryCondition) {
        $fileName = $queryCondition["file_name"];
        $fileUrl = $queryCondition["file_url"];
        $fileOrder = $queryCondition["file_order"];
        $userNo = $queryCondition["user_no"];
        $sql  = "INSERT INTO edu_board_article_file(file_name, file_url, file_order, user_no) ";
        $sql .= "VALUES ('". $fileName . "','" . $fileUrl . "'," . $fileOrder . "," . $userNo .")";
        $query = $this->datadb->query($sql);
        $insertedNo = $query ? $this->datadb->insert_id() : 0;
        return $insertedNo;
    }

    public function insertArticle($queryCondition) {
        $title = $queryCondition["title"];
        $contents = $queryCondition["contents"];
        $fileNo = $queryCondition["file_no"];
        $userNo = $queryCondition["user_no"];
        $articleType = intval($queryCondition["article_type"]);
        $noticeStatus = $queryCondition["notice_status"];
        $sql  = "INSERT INTO edu_board_article(user_no, title, contents, article_type, files, create_date, update_date, notice_status) ";
        $sql .= "VALUES (?, ?, ?, ?, ?, NOW(), NOW(), ?)";

        $query = $this->datadb->query($sql, array($userNo, $title, $contents, $articleType, $fileNo, $noticeStatus));

        $msg = "failed";
        if($query) {
            $insertedNo = $this->datadb->insert_id();
            $msg = $insertedNo > 0 ? "success" : "failed";
        }

        $result = array("msg" => $msg);
        return $result;
    }

    public function updateArticle($queryCondition) {
        $articleNo = intval($queryCondition["article_no"]);
        $title = $queryCondition["title"];
        $contents = $queryCondition["contents"];
        $fileNo = $queryCondition["file_no"];
        $userNo = intval($queryCondition["user_no"]);
        $noticeStatus = $queryCondition["notice_status"];
        $sql  = "UPDATE edu_board_article ";
        $sql .= "SET ";
        $sql .= "title = '". addslashes($title) . "', ";
        $sql .= "contents = '". addslashes($contents) . "', ";
        $sql .= "files = '". $fileNo . "', ";
        $sql .= "update_date = NOW(), ";
        $sql .= "notice_status = '". $noticeStatus ."' " ;
        $sql .= "WHERE no = ". $articleNo;
        $query = $this->datadb->query($sql);

        $msg = $query ? "success" : "failed";
        $result = array("msg" => $msg);
        return $result;
    }


    public function viewArticle($queryCondition) {

        $articleNo = $queryCondition["article_no"];

        $sql = "UPDATE edu_board_article SET view_count = view_count + 1 WHERE no = ". $articleNo;
        $query = $this->datadb->query($sql);

        $sql  = "SELECT  ";
        $sql .= "  EBA.user_no AS user_no, ";
        $sql .= "  EBA.title AS title, ";
        $sql .= "  EBA.contents AS contents, ";
        $sql .= "  EBA.view_count AS view_count, ";
        $sql .= "  EBA.files AS files, ";
        $sql .= "  EBA.create_date AS create_date, ";
        $sql .= "  EBA.update_date AS update_date, ";
        $sql .= "  EBA.notice_status AS notice_status, ";
        $sql .= "  (SELECT mem_username FROM t_member WHERE mem_id  = EBA.user_no) AS user_name ";
        $sql .= "FROM edu_board_article AS EBA ";
        $sql .= "WHERE EBA.no = " .$articleNo. " AND EBA.delete_Status  = 'N' ";

        $query = $this->datadb->query($sql);
        $queryResult = $query->result_array();
        if (count($queryResult) < 1)
            return array("msg" => "failed");

        $result = array(
            "msg" => "success",
            "article" => $queryResult[0]
        );

        $article = $queryResult[0];
        $files = $article["files"];
        if (count($files) > 0) {
            $sql = "SELECT * FROM edu_board_article_file WHERE no IN (". $files .")";
            $query = $this->datadb->query($sql);
            $queryResult = $query->result_array();

            $files = array();
            for($i = 0; $i < count($queryResult); $i++) {
                $file = $queryResult[$i];
                $files[] = $file;
            }
            $result["files"] = $files;
        }

        return $result;
    }

    public function removeArticle($queryCondition) {
        $articleNo = $queryCondition["article_no"];
        $sql = "UPDATE edu_board_article SET delete_status = 'Y' WHERE no IN (". implode(',', $articleNo) .")";
//        $sql = "DELETE FROM edu_board_article WHERE no IN (". implode(',', $articleNo) .")";
        $query = $this->datadb->query($sql);
        $result = array("msg" =>  $query ? "success":"failed");
        return $result;
    }

    /**
     * 승인 대기 중인 리포트 개수
     */
    function getWaitingReportCount() {
        $qry = "SELECT COUNT(*) as cnt FROM edu_report AS ER 
                WHERE permit_state = 1  AND ER.delete_status = 'N'";
        $query = $this->datadb->query($qry);
        $result = $query->row();
        return $result->cnt;
    }

    public function removeRawData($queryCondition) {
        $dataNo = $queryCondition["data_no"];
        $sql = "UPDATE edu_data_overview SET delete_status = 'Y' WHERE no = $dataNo";
//        $sql = "DELETE FROM edu_board_article WHERE no IN (". implode(',', $articleNo) .")";
        $query = $this->datadb->query($sql);
        $result = array("msg" =>  $query ? "success":"failed");
        return $result;
    }

    public function updateNetworkAnalysisSchedule($queryCondition) {
        $userId = $queryCondition["user_id"];
        $dataNo = $queryCondition["data_no"];
        $chapter    = $queryCondition["chapter"];
        $windowSize = $queryCondition["window_size"];
        $query = "SELECT COUNT(*) as cnt FROM edu_network_analysis_schedule WHERE user_id= '$userId' AND data_no = $dataNo";
        $query = $this->datadb->query($query);
        $count = $query->row()->cnt;
        if ($count == 0) {

            $query  = "INSERT INTO edu_network_analysis_schedule(user_id, current_state, data_no, window_size, chapter, created_at, updated_at) ";
            $query .= "VALUES('$userId', 'WAIT', $dataNo, $windowSize, '$chapter', NOW(), NOW())";
            $query = $this->datadb->query($query);
            $insertedNo = $query ? $this->datadb->insert_id() : 0;
            $result = array("msg" =>  $insertedNo > 0 ? "success":"failed");
            return $result;
        } 
        else {
            $currentState = $queryCondition["current_state"];
            $query  = "UPDATE edu_network_analysis_schedule SET ";
            $query .= "current_state = '$currentState',";
            $query .= "window_size = $windowSize,"; 
            $query .= "chapter = '$chapter',";
            $query .= "updated_at = NOW() "; 
            $query .= "WHERE user_id = '$userId' AND data_no = $dataNo";
            $query = $this->datadb->query($query);
            $result = array("msg" =>  $query ? "success":"failed");
            return $result;
        }
        return array("msg" => "failed");
    }

    public function getNetworkAnalysisCurrentState($queryCondition) {
        $userId = $queryCondition["user_id"];
        $dataNo = $queryCondition["data_no"];
        $query = "SELECT current_state FROM edu_network_analysis_schedule WHERE user_id= '$userId' AND data_no = $dataNo";
        $query = $this->datadb->query($query);
        $queryResult = $query->result_array();
        $currentState = count($queryResult) > 0 ? $queryResult[0]["current_state"] : '';
        $result = array(
            "msg" =>  $currentState != '' ? "success" : "failed",
            "current_state" => $currentState,
            "query_result" => $queryResult
        );
        return $result;

        

        // $query = $this->datadb->query($query);
        // $count = $query->row()->cnt;
        // if ($count > 0) {

            
        //     $dataNo     = $queryCondition["data_no"];
        //     $chapter    = $queryCondition["chapter"];
        //     $windowSize = $queryCondition["window_size"];
        //     $query  = "INSERT INTO edu_network_analysis_schedule(user_id, current_state, data_no, window_size, chapter, created_at, updated_at) ";
        //     $query .= "VALUES('$userId', 'WAIT', $dataNo, $windowSize, '$chapter', NOW(), NOW())";
        //     $query = $this->datadb->query($query);
        //     $insertedNo = $query ? $this->datadb->insert_id() : 0;
        //     $result = array("msg" =>  $insertedNo > 0 ? "success":"failed");
        //     return $result;
        // } 
    //     // else {
    //     //     $currentState = $queryCondition["current_state"];
    //     //     $query = "UPDATE edu_network_analysis_schedule SET current_state = '$currentState' WHERE user_id = '$userId'";
    //     //     $query = $this->datadb->query($sql);
    //     //     $result = array("msg" =>  $query ? "success":"failed");
    //     //     return $result;
    //     // }
        // return array("msg" => "failed");
    }
}
?>