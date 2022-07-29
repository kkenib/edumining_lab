<?php
define('ACCESS_AFTER_LOGIN', '1');
define('ACCESS_ADMIN', '5');

define('LIMIT_START_DATE', '2021-01-01');


/**
 * 데이터 파일 위치
 */
$config['sta_data_path'] = array();

/**
 * elasticsearch URL
 */
$config['elastic_addr'] = '221.157.125.34';
$config['elastic_port'] = '9200';
$config['elastic_url'] = 'http://'.$config['elastic_addr'].':'.$config['elastic_port'];
$config['elastic_index'] = 'edumining_data';
$config['elastic_doctype'] = '';