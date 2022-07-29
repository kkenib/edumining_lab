<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SelfAnalysis model class
 */
class Class_manage_model extends IMC_Model
{
    function __construct() {
        parent::__construct();
    }

    public $user_table = "t_member";
    public $lecture_table = "edu_lecture";

    // 수업 코드 중복체크
    public function duplicateUserIdCheck($text){
        $sql = "SELECT * FROM %s";
        $sql = sprintf($sql, $this->lecture_table);
        $sql .= " WHERE prefix_id = '" . $text . "'";

        $query = $this->datadb->query($sql);
        $result = $query->result_array();

        if(count($result) > 0){
            return 1;
        }else{
            return 0;
        }
    }

    // 수업 목록 쿼리
    public function getClassList($teacher_idx, $kind, $keyword){
        $sql = "SELECT @ROWNUM:=@ROWNUM+1 AS classNo, " . $this->lecture_table . ".*";
        $sql .= " FROM (SELECT @ROWNUM := 0) R, " . $this->lecture_table;
        $sql .= " WHERE edu_lecture.user_no = " . $teacher_idx;
        $sql .= " AND edu_lecture.delete_status = 'N'";

        if($keyword != ""){
            if($kind == "수업명"){
                $sql .= " AND edu_lecture.lecture_name like '%" . $keyword . "%'";
            }else if($kind == "학급정보"){
                $sql .= " AND edu_lecture.lecture_overview like '%" . $keyword . "%'";
            }else if($kind == "수업코드"){
                $sql .= " AND edu_lecture.prefix_id like '%" . $keyword . "%'";
            }
        }
        //print_r($sql);
        $query = $this->datadb->query($sql);
        $result = $query->result_array();

        return $result;
    }

    // 수업 생성 쿼리
    public function insertNewClass($classData){
        $insert_data = array(
            "lecture_name" => $classData['classTitle'],
            "prefix_id" => $classData['classPrefix'],
            "user_no" => $classData['teacherIdx'],
            "lecture_overview" => $classData['classInfo'],
            "lecture_subject" => $classData['classTopic'],
            "student_count" => $classData['studentCount']
        );

        $result = $this->datadb->insert("edu_lecture", $insert_data);
        $classIdx = $this->datadb->insert_id();

        if ($result){
            $return_data = array(
                "msg" => "success",
                "classIdx" => $classIdx,
            );
        }else{
            $return_data = array(
                "msg" => "error"
            );
        }

        return $return_data;
    }

    // 클래스 -> 학생 계정 생성 쿼리
    public function createNewStudent($studentData){
        $teacherID = $studentData['teacherID'];
        $teacherIdx = $studentData['teacherIdx'];
        $studentCount = $studentData['studentCount'];
        $classIdx = $studentData['classIdx'];
        $classPrefix = $studentData['classPrefix'];
        $schoolName = $this->member->item('mem_school_nm');
        $return_data = "error";
        for ($i = 1; $i <= $studentCount; $i++){
            if ($i < 10){
                $prefixNum = "000";
            }else if ($i >= 10 && $i < 100){
                $prefixNum = "00";
            }else if ($i >= 100){
                $prefixNum = "0";
            }
            $studentID = $classPrefix . $prefixNum . $i;

            $tmpdata = array(
                "mem_userid" => $studentID,
                "mem_password" => password_hash($studentID, PASSWORD_BCRYPT),
                "mem_username" => $studentID,
                "mem_nickname" => $studentID,
                "mem_level" => 1,
                "mem_class_no" => $classIdx,
                "mem_prefix" => $classPrefix,
                "mem_parent" => $teacherID,
                "mem_school_name" => $schoolName
            );
            $result = $this->datadb->insert("t_member", $tmpdata);
            if (!$result) return array("msg" => "error");

            $insertedNo = $this->datadb->insert_id();
            $tmpdata = array(
                "lecture_no" => $classIdx,
                "user_no" => $insertedNo,
            );
            $result = $this->datadb->insert("edu_lecture_take", $tmpdata);
            if (!$result) return array("msg" => "error");
        }

        // 교사 take lecture
        $tmpdata = array(
            "lecture_no" => $classIdx,
            "user_no" => $teacherIdx,
        );
        $result = $this->datadb->insert("edu_lecture_take", $tmpdata);
        if (!$result) return array("msg" => "error");

        return array("msg" => "success");
    }

//    public function createNewStudent($studentData){
//        $teacherID = $studentData['teacherID'];
//        $teacherIdx = $studentData['teacherIdx'];
//        $studentCount = $studentData['studentCount'];
//        $classIdx = $studentData['classIdx'];
//        $classPrefix = $studentData['classPrefix'];
//
//        $schoolName = $this->member->item('mem_school_nm');
//        $batch_data = array();
//
//        for ($i = 1; $i <= $studentCount; $i++){
//            if ($i < 10){
//                $prefixNum = "000";
//            }else if ($i >= 10 && $i < 100){
//                $prefixNum = "00";
//            }else if ($i >= 100){
//                $prefixNum = "0";
//            }
//            $studentID = $classPrefix . $prefixNum . $i;
//
//            $tmpdata = array(
//                "mem_userid" => $studentID,
//                "mem_password" => password_hash($studentID, PASSWORD_BCRYPT),
//                "mem_username" => $studentID,
//                "mem_nickname" => $studentID,
//                "mem_level" => 1,
//                "mem_class_no" => $classIdx,
//                "mem_prefix" => $classPrefix,
//                "mem_parent" => $teacherID,
//                "mem_school_name" => $schoolName
//            );
//            array_push($batch_data, $tmpdata);
//        }
//
//        $result = $this->datadb->insert_batch("t_member", $batch_data);
//        if ($result){
//            $return_data = array(
//                "msg" => "success"
//            );
//        }else{
//            $return_data = array(
//                "msg" => "error"
//            );
//        }
//        return $return_data;
//    }

    // 수업 정보 수정 쿼리
    public function classEditSave($classData){
        $teacherIdx = $this->member->item('mem_id');
        $classIdx = $classData['classIdx'];
        $classTitle = $classData['classTitle'];
        $classInfo = $classData['classInfo'];
        $classTopic = $classData['classTopic'];
        $studentCount = $classData['studentCount'];
        $classPrefix = $classData['classPrefix'];

        $sql = "SELECT * FROM %s";
        $sql = sprintf($sql, $this->lecture_table);
        $sql .= " WHERE no = '" . $classIdx . "'";
        $query = $this->datadb->query($sql);
        $result = $query->result_array()[0];

        $bStdCount = $result['student_count'];

        $sql = "SELECT * FROM %s";
        $sql = sprintf($sql, $this->user_table);
        $sql .= " WHERE mem_id = '" . $teacherIdx . "'";
        $query = $this->datadb->query($sql);
        $teacherInfo = $query->result_array()[0];
        $teacherID = $teacherInfo['mem_userid'];
        $schoolName = $teacherInfo['mem_school_name'];

        if ($bStdCount != $studentCount){
            if ($bStdCount < $studentCount){
                $batch_data = array();

                for ($i = ($bStdCount+1); $i <= $studentCount; $i++){
                    if ($i < 10){
                        $prefixNum = "000";
                    }else if ($i >= 10 && $i < 100){
                        $prefixNum = "00";
                    }else if ($i >= 100){
                        $prefixNum = "0";
                    }
                    $studentID = $classPrefix . $prefixNum . $i;

                    $tmpdata = array(
                        "mem_userid" => $studentID,
                        "mem_password" => password_hash($studentID, PASSWORD_BCRYPT),
                        "mem_username" => $studentID,
                        "mem_nickname" => $studentID,
                        "mem_level" => 1,
                        "mem_class_no" => $classIdx,
                        "mem_prefix" => $classPrefix,
                        "mem_parent" => $teacherID,
                        "mem_school_name" => $schoolName
                    );

                    $result = $this->datadb->insert("t_member", $tmpdata);
                    if ($result) {
                        $insertedNo = $this->datadb->insert_id();
                        $tmpdata = array(
                            "lecture_no" => $classIdx,
                            "user_no" => $insertedNo,
                        );
                        $this->datadb->insert("edu_lecture_take", $tmpdata);
                    }
                }
            }
        }

        $updateData = array(
            'lecture_name' => $classTitle,
            'lecture_overview' => $classInfo,
            'lecture_subject' => $classTopic,
            'student_count' => $studentCount
        );
        $this->datadb->where('no', $classIdx);
        $result = $this->datadb->update($this->lecture_table, $updateData);

        if ($result){
            $return_data = array(
                "msg" => "success"
            );
        }else{
            $return_data = array(
                "msg" => "error"
            );
        }
        return $return_data;
    }

    // 수업 삭제 쿼리
    public function classEditDelete($classIdx){
        $teacherID = $this->member->item('mem_userid');

        // 수업 코드 가져오기
        $this->datadb->select("prefix_id");
        $this->datadb->where("no", $classIdx);
        $query = $this->datadb->get($this->lecture_table);
        $prefix_id = $query->row()->prefix_id;

        // 수업 코드를 이용해 수강 학생 계정 가져오기기
        $this->datadb->select("*");
        $this->datadb->where("mem_parent", $teacherID);
        $this->datadb->where("mem_prefix", $prefix_id);
        $query = $this->datadb->get($this->user_table);

        $std_idx_arr = array();
        foreach($query->result() as $row){
            array_push($std_idx_arr, $row->mem_id);
        }

        $data = array(
            'delete_status' => 'Y'
        );
        $this->datadb->where('no', $classIdx);
        $this->datadb->update($this->lecture_table, $data);

        $data = array(
            'mem_denied' => 1
        );
        $this->datadb->where_in('mem_id', $std_idx_arr);
        $this->datadb->update($this->user_table, $data);

        //$this->datadb->where('no', $classIdx);
        //$this->datadb->delete($this->lecture_table);

        $return = array(
            'msg' => 'success'
        );

        return $return;
    }
}
?>