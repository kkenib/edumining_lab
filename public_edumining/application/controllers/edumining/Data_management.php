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
class Data_management extends IMC_Controller
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
        $this->load->model('edumining/Data_manage_model');
    }

    public function alert_cont($msg, $url){
        $data = array();
        $data['msg'] = $msg;
        $data['url'] = $url;
        $this->load->view('service_menu/management/alert_redirect', $data);
    }


    // 페이지 초기화
    public function pageInit(){
        $user_no = $this->member->item('mem_id');

        $countCrawlData = $this->Data_manage_model->checkCrawling($user_no);

        echo json_encode($countCrawlData);
    }

    // 크롤링 실행
    public function submitCrawl(){
        $data = $this->input->post('data', TRUE);

        $insertResult = $this->Data_manage_model->insertCrawlData($data);

        if($insertResult['msg'] == "error"){
            $returnData = array(
                "msg" => "error"
            );
        }else{
            $returnData = array(
                "msg" => "success",
                "dataIdx" => $insertResult['dataIdx']
            );
        }

        echo json_encode($returnData);
    }

    // 뉴스 크롤링 완료 체크
    public function checkCrawl(){
        $dataIdx = $this->input->post('dataIdx', TRUE);

        $crawlCheck = $this->Data_manage_model->crawlCheck($dataIdx);
        $finished = $crawlCheck["finished"];
        $dataSize = $crawlCheck["data_size"];
        $lastText = $crawlCheck["last_text"];
        $updateDate = $crawlCheck["update_date"];

        $return_data = array(
            'msg' => 'success',
            'data_size' => $dataSize,
            'last_text' => $lastText,
            'update_date'=> $updateDate,
            'check' => $finished ? "complete" : "crawling"
        );
//
//        if($crawlCheck == 1){
//            $return_data = array(
//                'msg' => 'success',
//                'check' => 'complete'
//            );
//        }else if($crawlCheck == 0){
//            $return_data = array(
//                'msg' => 'success',
//                'check' => 'crawling'
//            );
//        }else{
//            $return_data = array(
//                'msg' => 'error'
//            );
//        }

        echo json_encode($return_data);
    }

    // 데이터 삭제 함수
    public function dataDelete()
    {
        $dataIdx = $this->input->post('dataIdx', TRUE);

        $deleteResult = $this->Data_manage_model->deleteData($dataIdx);

        if ($deleteResult['msg'] == 'success') {

            $url = $this->remoteHost."/deleteCrawlData";
            $data = array("file_path" => $deleteResult['file_path']);
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS,  json_encode($data));
            $response = curl_exec($curl);
            #$learn_word = json_decode($response);
            curl_close($curl);
            $return_data = array(
                'msg' => 'success'
            );
        }else{
            $return_data = array(
                "msg" => "failed"
            );
        }

        echo json_encode($return_data);
    }

    public function dataUpload(){
        $dataIdx = $this->input->post('dataIdx', TRUE);
        $dataName = $this->input->post('dataName', TRUE);

        $dataUploadResult = $this->Data_manage_model->dataUpload($dataIdx, $dataName);

        if($dataUploadResult['msg'] == 'success'){

            $data = array("idx" => $dataIdx);
            $result = $this->curl_post("/elasticUpdate", $data);

            // $url = $this->remoteHost."/elasticUpdate";
            
            // $curl = curl_init($url);
            // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($curl, CURLOPT_POST, true);
            // curl_setopt($curl, CURLOPT_POSTFIELDS,  json_encode($data));
            // $response = curl_exec($curl);
            #$learn_word = json_decode($response);
            // curl_close($curl);
            $return_data = array(
                'msg' => 'success'
            );
        }else{
            $return_data = array(
                'msg' => 'error'
            );
        }

        echo json_encode($return_data);
    }


    public function dataDownload($dataIdx){
        $crawlData = $this->Data_manage_model->getCrawlData($dataIdx);
        $filename = $crawlData['file_path'];


        $filePath = $this->input->post('file_path', TRUE);
        $url = $this->remoteHost."/getCrawlData";
        $data = array("file_path" => $filename);

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
       
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
        $response = curl_exec($ch);

        $targetData = (array) json_decode($response);
        $textData = $targetData["text"];
        // $slicedData = array_slice($targetData, 0, 4);
        // echo json_encode(json_decode($response));

        
        // $excel_data = array();

        // if($filename){
        //     $lineCount = 0;
        //     $fp = fopen($filename, 'r');
        //     while(!feof($fp))
        //     {
        //         $line_txt = fgets($fp);
        //         $strip_txt = explode("\t", $line_txt);
        //         array_push($excel_data, array_slice($strip_txt, 0, 4));
        //         //fputcsv($f, $strip_txt, ",");
        //     }
        // }

        $return_data = array(
            "msg" => "success",
            "data" => $textData //$excel_data
            // "debug" => $slicedData
        );

        echo json_encode($return_data);
    }

    public function fileUpload(){
        $config['allowed_types'] = 'txt|xls|xlsx|pdf|csv|jpg';
        $user_no = $this->member->item('mem_id');
        $dataName = $this->input->post('uploadDataName', TRUE);
        $uploadFiles	= $_FILES["file_route"]["name"];
        $tmpFiles	= $_FILES["file_route"]["tmp_name"];
        $fileSizes	= $_FILES["file_route"]["size"];

        $data = array(
            'user_no' => $user_no,
            'data_name' => $dataName,
            'data_type' => 2,
            'collection_state' => '0',
            'chapter_count' => count($uploadFiles),
        );

        $index = 0;
        $files = array();
        for($i = 0; $i < count($uploadFiles); $i++){
            $filename = $uploadFiles[$i];
            if (empty($filename)) continue;

            $tmpfile  = $tmpFiles[$i];
            $index = $index + 1;
            $key = "file_". $index;
            $handle = fopen($tmpfile, 'r');
            $fileData = fread($handle, filesize($tmpfile));
            $data[$key] = base64_encode($fileData);
            fclose($handle);
        }

        $data["chapter_count"] = $index;
        $response = $this->curl_post("/elasticUpdateMyData", $data);

        // $url = $this->remoteHost."/elasticUpdateMyData";
        // $curl = curl_init($url);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_POST, true);
        // curl_setopt($curl, CURLOPT_POSTFIELDS,  $data);
        // $response = curl_exec($curl);
        // curl_close($curl);

        // echo count($uploadFiles);
        // echo $url;
        // echo $response;

        // echo json_encode(count($uploadFiles));
//        $tmpUploadFiles = $uploadFiles; $uploadFiles = array();
//        $tmpTmpFiles = $tmpFiles; $tmpFiles = array();
//        $tmpFileSizes = $fileSizes; $fileSizes = array();
//        for($i = 0; $i < count($tmpUploadFiles); $i++){
//            $uploadFile = $tmpUploadFiles[$i];
//            if ($uploadFile != '') {
//                array_push($uploadFiles, $uploadFile);
//                array_push($tmpFiles, $tmpTmpFiles[$i]);
//                array_push($fileSizes, $tmpFileSizes[$i]);
//            }
//        }
//
//
//
//        $dataIdx = $this->Data_manage_model->insertUploadData($data);
//        $savePath = "/data3/edumining/upload". "/" . $dataIdx;
//        if(!file_exists($savePath)){
//            mkdir($savePath, 0777, true);
//        }
//
//        $metaData = '';
//        $totalSize = 0;
//        $metaFile = fopen($savePath ."/meta.txt", "w");
//
//        for($i = 0; $i < count($uploadFiles); $i++){
//            $uploadFile = $uploadFiles[$i];
//            $tmpFile = $tmpFiles[$i];
//            $fileSize = $fileSizes[$i];
//
//            $file_ext = substr(strrchr($uploadFile, "."), 1);
//
//            $ran = rand(0,1000);
//
//            $fileName = "chapter" . ($i < 10 ? '0' : '') . ($i+1);
//            $saveFile = $fileName . "." . $file_ext;
//            move_uploaded_file($tmpFile, $savePath . "/" . $saveFile);
//
//            $metaData .= ($i+1).','.$saveFile."\r\n";
//            $totalSize = $totalSize + $fileSize;
//        }
//        fwrite($metaFile, $metaData);
//        fclose($metaFile);
//
//        $updateData = array(
//            'data_size' => $totalSize,
//            'file_path' => $savePath
//        );
//        $return_data = $this->Data_manage_model->updateData($dataIdx, $updateData);
//
//        if($dataIdx > 0){
//            $url = $this->remoteHost."/elasticUpdateMyData";
//            $data = array("idx" => $dataIdx);
//            $curl = curl_init($url);
//            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($curl, CURLOPT_POST, true);
//            curl_setopt($curl, CURLOPT_POSTFIELDS,  json_encode($data));
//            $response = curl_exec($curl);
//            #$learn_word = json_decode($response);
//            curl_close($curl);
//            $return_data = array(
//                'msg' => 'success'
//            );
//        }else{
//            $return_data = array(
//                'msg' => 'error'
//            );
//        }

        $msg = "등록되었습니다.";
        $url = $this->localHost."/analysis/data_manage";
        $this->alert_cont($msg, $url);
    }
//    public function fileUpload(){
//        $config['allowed_types'] = 'txt|xls|xlsx|pdf|csv|jpg';
//        $user_no = $this->member->item('mem_id');
//        $dataName = $this->input->post('uploadDataName', TRUE);
//        //$this->load->library('upload', $config);
//        //$this->upload->initialize($config);
//        //print_r($_POST);
//
//        $uploadFiles	= $_FILES["file_route"]["name"];
//        $tmpFiles	= $_FILES["file_route"]["tmp_name"];
//        $fileSizes	= $_FILES["file_route"]["size"];
//
//        $tmpUploadFiles = $uploadFiles; $uploadFiles = array();
//        $tmpTmpFiles = $tmpFiles; $tmpFiles = array();
//        $tmpFileSizes = $fileSizes; $fileSizes = array();
//        for($i = 0; $i < count($tmpUploadFiles); $i++){
//            $uploadFile = $tmpUploadFiles[$i];
//            if ($uploadFile != '') {
//                array_push($uploadFiles, $uploadFile);
//                array_push($tmpFiles, $tmpTmpFiles[$i]);
//                array_push($fileSizes, $tmpFileSizes[$i]);
//            }
//        }
//
//        $data = array(
//            'user_no' => $user_no,
//            'data_name' => $dataName,
//            'data_type' => 2,
//            'update_date' => date("Y-m-d"),
//            'collection_state' => '0',
//            'chapter_count' => count($uploadFiles),
//        );
//
//        $dataIdx = $this->Data_manage_model->insertUploadData($data);
//        $savePath = "/data3/edumining/upload". "/" . $dataIdx;
//        if(!file_exists($savePath)){
//            mkdir($savePath, 0777, true);
//        }
//
//        $metaData = '';
//        $totalSize = 0;
//        $metaFile = fopen($savePath ."/meta.txt", "w");
//
//        for($i = 0; $i < count($uploadFiles); $i++){
//            $uploadFile = $uploadFiles[$i];
//            $tmpFile = $tmpFiles[$i];
//            $fileSize = $fileSizes[$i];
//
//            $file_ext = substr(strrchr($uploadFile, "."), 1);
//
//            $ran = rand(0,1000);
//
//            $fileName = "chapter" . ($i < 10 ? '0' : '') . ($i+1);
//            $saveFile = $fileName . "." . $file_ext;
//            move_uploaded_file($tmpFile, $savePath . "/" . $saveFile);
//
//            $metaData .= ($i+1).','.$saveFile."\r\n";
//            $totalSize = $totalSize + $fileSize;
//        }
//        fwrite($metaFile, $metaData);
//        fclose($metaFile);
//
//        $updateData = array(
//            'data_size' => $totalSize,
//            'file_path' => $savePath
//        );
//        $return_data = $this->Data_manage_model->updateData($dataIdx, $updateData);
//
//        if($dataIdx > 0){
//            $url = $this->remoteHost."/elasticUpdateMyData";
//            $data = array("idx" => $dataIdx);
//            $curl = curl_init($url);
//            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($curl, CURLOPT_POST, true);
//            curl_setopt($curl, CURLOPT_POSTFIELDS,  json_encode($data));
//            $response = curl_exec($curl);
//            #$learn_word = json_decode($response);
//            curl_close($curl);
//            $return_data = array(
//                'msg' => 'success'
//            );
//        }else{
//            $return_data = array(
//                'msg' => 'error'
//            );
//        }
//
//        $msg = "등록되었습니다.";
//        $url = $this->localHost."/analysis/data_manage";
//        $this->alert_cont($msg, $url);
//    }
//}
//    public function fileUpload(){
//        $config['allowed_types'] = 'txt|xls|xlsx|pdf|csv|jpg';
//        $user_no = $this->member->item('mem_id');
//        $dataName = $this->input->post('uploadDataName', TRUE);
//        //$this->load->library('upload', $config);
//        //$this->upload->initialize($config);
//        //print_r($_POST);
//
//        $uploadFiles	= $_FILES["file_route"]["name"];
//        $tmpFiles	= $_FILES["file_route"]["tmp_name"];
//        $fileSizes	= $_FILES["file_route"]["size"];
//
//        $savePath = "/data3/edumining/upload";
//
//        $data = array(
//            'user_no' => $user_no,
//            'data_name' => $dataName,
//            'data_type' => 2,
//            'update_date' => date("Y-m-d"),
//            'collection_state' => '0',
//            'chapter_count' => count($uploadFiles)
//        );
//
//        $insertIdx = $this->Data_manage_model->insertUploadData($data);
//        $savePath = $savePath . "/" . $insertIdx;
//
//        if(!file_exists($savePath)){
//            mkdir($savePath, 0777, true);
//        }
//
//        $totalSize = 0;
//        for($i = 0; $i < count($uploadFiles); $i++){
//            $uploadFile = $uploadFiles[$i];
//            $tmpFile = $tmpFiles[$i];
//            $fileSize = $fileSizes[$i];
//
//            $file_ext = substr(strrchr($uploadFile, "."), 1);
//
//            $ran = rand(0,1000);
//
//            if($i < 10){
//                $saveFile = "chapter0" . ($i+1) . "." . $file_ext;
//            }else{
//                $saveFile = "chapter" . ($i+1) . "." . $file_ext;
//            }
//            move_uploaded_file($tmpFile, $savePath . "/" . $saveFile);
//            $totalSize = $totalSize + $fileSize;
//        }
//
//        $updateData = array(
//            'data_size' => $totalSize
//        );
//        $return_data = $this->Data_manage_model->updateData($insertIdx, $updateData);
//
//        $msg = "등록되었습니다.";
//        $url = "http://edumining.textom.co.kr/management/data_collect";
//        $this->alert_cont($msg, $url);
//    }


    public function getTextData()
    {
        $filePath = $this->input->post('file_path', TRUE);
        $url = $this->remoteHost."/getTextData";
        $data = array("file_path" => $filePath);

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
       
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
        $response = curl_exec($ch);
        echo json_encode(json_decode($response));
    }
}
