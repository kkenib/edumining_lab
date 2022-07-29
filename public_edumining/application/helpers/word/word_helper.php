<?php

function word_init($mem_id){
    $CI = get_instance();
    $CI->load->model('Member_model', 'MM', TRUE);
    $CI->load->model('word/User_ai_info', 'UA', TRUE);

    $CI->UA->user_state_update($mem_id, "wait");
    
    $ai_info = $CI->UA->get_ai_info($mem_id);
    $school = $ai_info[0]['school'];

    $CI->UA->user_login($mem_id);
    $school_lank = $CI->UA->ai_school_lanking($mem_id, $school);
    $total_lank = $CI->UA->ai_total_lanking($mem_id);
    //print_r($school_lank);
    
    $view = array();
    $view['view'] = array();
    
    $data = array();
    
    $data = array();
    $data['user_id'] = $ai_info[0]['id'];
    $data['id_code'] = $ai_info[0]['id_code'];
    $data['ai_name'] = $ai_info[0]['ai_name'];
    $data['school'] = $ai_info[0]['school'];
    $data['word_count'] = $ai_info[0]['word_count'];
    $data['learn_count'] = $ai_info[0]['learn_count'];
    $data['win_count'] = $ai_info[0]['win_count'];
    $data['lose_count'] = $ai_info[0]['lose_count'];
    $data['win_score'] = $ai_info[0]['win_score'];
    $data['state'] = 'online';
    $data['school_lank'] = $school_lank[0];
    $data['total_lank'] = $total_lank[0];
    
    $view['view']['data'] = $data;

    $CI->load->view("/_layout/word/include/header.php");
    $CI->load->view("/_layout/word/include/snb.php", $view);

    /*
    $query = $CI->db->query("SELECT * FROM w_user_ai_info JOIN t_currentvisitor ON t_currentvisitor.mem_id = w_user_ai_info.id_code WHERE t_currentvisitor.cur_page = '끝말잇기' AND w_user_ai_info.ai_name != '' AND w_user_ai_info.state != 'offline'");
    $current_result = $query->result_array();
    print_r($current_result);
    */
}

?>