<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SelfAnalysis model class
 */
class Data_manage_model extends IMC_Model
{
    function __construct() {
        parent::__construct();
    }

    public $user_table = "t_member";
    public $data_table = "edu_data_overview";

    public function insertCrawlData($data){
        $user_no = $this->member->item('mem_id');

        $keyword = $data['keyword'];
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $unit = $data['unit'];
        if($unit == "M"){
            $startDate = $startDate . "-01";
            $endDate = $endDate . "-01";
        }

        $insert_data = array(
            "user_no" => $user_no,
            "data_type" => "1",
            "update_date" => date('Y-m-d H:i:s'),
            "collection_start_date" => $startDate,
            "collection_end_date" => $endDate,
            "collection_channel1" => "naver",
            "collection_channel2" => "news",
            "collection_state" => "0",
            "collection_unit" => $unit,
            "collection_keyword" => $keyword
        );

        $result = $this->datadb->insert($this->data_table, $insert_data);
        $dataIdx = $this->datadb->insert_id();

        if ($result){
            $return_data = array(
                "msg" => "success",
                "dataIdx" => $dataIdx,
            );
        }else{
            $return_data = array(
                "msg" => "error"
            );
        }

        return $return_data;
    }

    public function crawlCheck($dataIdx){
        $user_no = $this->member->item('mem_id');

        $sql = "SELECT * FROM %s";
        $sql = sprintf($sql, $this->data_table);
        $sql .= " WHERE user_no = " . $user_no . " AND no = " . $dataIdx;

        $query = $this->datadb->query($sql);
        $result = $query->result_array();
        $finished = false;
        $updateDate = '';
        if(count($result) > 0) {
            $item = $result[0];
            $updateDate = $item["update_date"];
            $collectionState = $item["collection_state"];
            $finished = $collectionState == 1;
            $filePath = $item["file_path"];
            $dataSize = $item["data_size"];

            $lastText = '';
//            $handle = fopen($filePath, "r");
//            if ($handle) {
//                while (($line = fgets($handle)) !== false) {
//                    $lastText = $line;
//                }
//            }
        }
        return array(
            "finished" => $finished,
            "data_size" => $dataSize,
            "last_text" => $lastText,
            "update_date" => $updateDate
        );
    }

//    public function crawlCheck($dataIdx){
//        $user_no = $this->member->item('mem_id');
//
//        $sql = "SELECT * FROM %s";
//        $sql = sprintf($sql, $this->data_table);
//        $sql .= " WHERE user_no = " . $user_no . " AND no = " . $dataIdx . " AND collection_state = 1";
//
//        $query = $this->datadb->query($sql);
//        $result = $query->result_array();
//
//        $finished = count($result) > 0;
//        $data_size = $result[0]["data_size"];
//
//        return array(
//            "finished" => $finished,
//            "data_size" => $data_size
//        );
//    }

    public function checkCrawling($user_no){
        $sql = "SELECT * FROM %s";
        $sql = sprintf($sql, $this->data_table);
        $sql .= " WHERE user_no = " . $user_no . " AND data_type = 1 AND collection_state = 0";

        $query = $this->datadb->query($sql);
        $result = $query->result_array();

        if(count($result) > 0){
            $return_data = array(
                'msg' => 'crawling',
                'dataIdx' => $result[0]['no'],
                'keyword' => $result[0]['collection_keyword'],
                'unit' => $result[0]['collection_unit'],
                'startDate' => $result[0]['collection_start_date'],
                'endDate' => $result[0]['collection_end_date'],
                'dataSize' => $result[0]['data_size']
            );
        }else{
            $sql = "SELECT * FROM %s";
            $sql = sprintf($sql, $this->data_table);
            $sql .= " WHERE user_no = " . $user_no . " AND data_type = 1 AND data_name IS NULL ORDER BY no DESC";

            $query = $this->datadb->query($sql);
            $result = $query->result_array();

            if(count($result) > 0){
                $return_data = array(
                    'msg' => 'upload',
                    'dataIdx' => $result[0]['no'],
                    'keyword' => $result[0]['collection_keyword'],
                    'unit' => $result[0]['collection_unit'],
                    'startDate' => $result[0]['collection_start_date'],
                    'endDate' => $result[0]['collection_end_date'],
                    'dataSize' => $result[0]['data_size']
                );
            }else{
                $return_data = array(
                    'msg' => 'success'
                );
            }
        }

        return $return_data;
    }


    public function getCrawlData($idx){
        $sql = "SELECT * FROM %s";
        $sql = sprintf($sql, $this->data_table);
        $sql .= " WHERE no = " . $idx;

        $query = $this->datadb->query($sql);
        $result = $query->result_array();

        return $result[0];
    }

    public function deleteData($idx){
        $sql = "SELECT * FROM %s";
        $sql = sprintf($sql, $this->data_table);
        $sql .= " WHERE no = " . $idx;

        $query = $this->datadb->query($sql);
        $result = $query->result_array();

        $filePath = $result[0]['file_path'];
        $this->datadb->delete($this->data_table, array('no' => $idx));

//        if(!unlink($filePath)){
//            $result_data = array(
//                'msg' => 'failed'
//            );
//        }else{
//            $result_data = array(
//                'msg' => 'success'
//            );
//        }

        return array(
            'msg' => 'success',
            "file_path" => $filePath
        );
    }

    public function dataUpload($idx, $dataName){
        $updateData = array(
            'data_name' => $dataName
        );

        $this->datadb->where('no', $idx);
        $this->datadb->update($this->data_table, $updateData);

        $return_data = array(
            'msg' => 'success'
        );

        return $return_data;
    }

    public function insertUploadData($data){
        $result = $this->datadb->insert($this->data_table, $data);
        $dataIdx = $this->datadb->insert_id();

        return $dataIdx;
    }

    public function updateData($idx, $data){
        $this->datadb->where('no', $idx);
        $this->datadb->update($this->data_table, $data);

        $return_data = array(
            'msg' => 'success'
        );

        return $return_data;
    }
}
?>