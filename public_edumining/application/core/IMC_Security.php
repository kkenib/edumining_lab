<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__ . '/CB/CB_Security.php';

/**
 * Security Class
 */
class IMC_Security extends CB_Security {

    public function __construct() {
        parent::__construct();
    }
}
