<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Html class
 *
 * Copyright (c) The IMC <www.theimc.co.kr>
 *
 * @author The IMC
 */

/**
 * 페이지를 담당하는 controller 입니다.
 */
class Html extends IMC_Controller
{
    private $view_path = 'html';

    public function datalist()
    {
		$this->active_manu('datalist');

        // 페이지명, path, file
        $this->_ext_layout('DATA LIST', $this->view_path, 'datalist');
    }

    public function introduce()
    {
		$this->active_manu('aboutmisp', 'introduce');

        // 페이지명, path, file
        $this->_ext_layout('Introduce', $this->view_path, 'introduce');
    }
    
    public function monitoring()
    {
		$this->active_manu('aboutmisp', 'introduce');

        // 페이지명, path, file
        $this->_ext_layout('Feature', $this->view_path, 'monitoring');
    }
    
    public function analysis()
    {
		$this->active_manu('aboutmisp', 'introduce');

        // 페이지명, path, file
        $this->_ext_layout('Feature', $this->view_path, 'analysis');
    }

    public function sitemap()
    {
		$this->active_manu('aboutmisp', 'sitemap');

        // 페이지명, path, file
        $this->_ext_layout('사이트맵', $this->view_path, 'sitemap');
    }

    public function mymisp()
    {
		$this->active_manu('aboutmisp', 'mymisp');

        // 페이지명, path, file
        $this->_ext_layout('Feature', $this->view_path, 'mymisp');
    }

    public function prediction()
    {
		$this->active_manu('aboutmisp', 'prediciton');

        // 페이지명, path, file
        $this->_ext_layout('Feature', $this->view_path, 'prediciton');
    }


}
