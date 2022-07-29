<?php
// 사용안함
function _cb_autoload($classname) {
    if ( strpos( $classname, 'CI_' ) !== 0 ) {
        $file = APPPATH . 'core/CB/' . $classname . '.php';
        if ( file_exists( $file ) && is_file( $file ) ) {
            @include_once $file;
        }

        $file = APPPATH . 'libraries/CB/' . $classname . '.php';
        if ( file_exists( $file ) && is_file( $file ) ) {
            @include_once $file;
        }

        $file = APPPATH . 'helpers/CB/' . $classname . '.php';
        if ( file_exists( $file ) && is_file( $file ) ) {
            @include_once $file;
        }
    }
}
spl_autoload_register('_cb_autoload'); 
