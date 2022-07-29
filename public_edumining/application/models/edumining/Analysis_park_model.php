<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  model class
 *  
 *  박가은 작업 파일(추후 삭제)
 */
class Analysis_park_model extends IMC_Model
{

    function __construct() {
        parent::__construct();
    }
    
    /**
     * 원본 데이터 리스트 가져오기
     */
    public function get_rawdata_list($user_no, $data_type, $search_keyword, $page_num) {
        
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
            WHERE o.data_type = %s
        ", $user_no, $data_type);
        
        if ($search_keyword != "") {
            $qry .= " AND o.data_name LIKE CONCAT('%', '".$search_keyword."', '%')";
        }
        
        $qry .= " ORDER BY o.update_date, o.no DESC";
        
        $offset = ($page_num-1)*10;
        $qry .= sprintf(" LIMIT 10 OFFSET %d", $offset);
        
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
            WHERE o.data_type = %s
        ", $user_no, $data_type);
	    
	    if ($search_keyword != "") {
	        $qry .= " AND o.data_name LIKE CONCAT('%', '".$search_keyword."', '%')";
	        
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
	    
	    $delete_data = array(
	        "user_no" => $user_no,
	        "no" => $no
	    );
	    
	    $result = $this->datadb->delete("edu_data_artifact", $delete_data);
	    
	    return $result;
	}
	
	/**
	 * 공지사항 리스트 가져오기
	 */
	function get_notice_list($search_keyword, $page_num) {
	    
	    $qry = "
                SELECT *
                FROM edu_board_article
                WHERE article_type = 0";
	    
	    if ($search_keyword != "") {
	        $qry .= " AND title LIKE CONCAT('%', '".$search_keyword."', '%')";
	    }
	    
	    $qry .= " ORDER BY create_date, no DESC";
	    
	    $offset = ($page_num-1)*10;
	    $qry .= sprintf(" LIMIT 10 OFFSET %d", $offset);
	    
	    $query = $this->datadb->query($qry);
	    
	    $result = $query->result_array();
	    
	    return $result;
	}
	
	/**
	 * 공지사항 카운트 가져오기
	 */
	function get_notice_list_count($search_keyword) {
	    
	    $qry = "
                SELECT COUNT(*) as cnt
                FROM edu_board_article
                WHERE article_type = 0";
	    
	    if ($search_keyword != "") {
	        $qry .= " AND title LIKE CONCAT('%', '".$search_keyword."', '%')";
	    }
	    
	    $query = $this->datadb->query($qry);
	    
	    $result = $query->row();
	    
	    return $result;
	}
    

}
?>