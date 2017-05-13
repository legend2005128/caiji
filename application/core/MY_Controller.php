<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/9 0009
 * Time: 14:13
 */
class MY_controller extends CI_Controller {

    //设置cookie地址
    protected $cookie_jar = "";
    protected $_header;
    public $uri_back;
    public $enterp_token;

    function __construct() {
        header('Access-Control-Allow-Origin:*');
        header("Content-type: text/html; charset=utf-8");
        error_reporting(0);
        set_time_limit(180);
        parent::__construct();
        $this->load->library('session');
        $this->enterp_token = $this->session->userdata('enterp_token');
        //设置
        if (!$this->_header) {
            $this->_header = fc_header_ips(); //生成随机ip头
        }
        //设置返回按钮
        $this->set_uri_back();
        $this->cookie_jar = FCPATH . "cookie_f/";
    }

    /*
     * 模板引入
     */

    protected function _render($view, $data = array(), $flag = false) {
        $data['uri_back'] = $this->uri_back;
        $this->load->view('common/head');
        if ($flag) {
            $this->load->view($view, $data);
        } else {
            $this->load->view("qiye/" . $view, $data);
        }
        $this->load->view('common/bottom');
    }

    /**
     * 返回搜索
     */
    protected function re_callback($words, $routes = '') {
        exit($words . '&nbsp;&nbsp;<a href="' . $routes . '">返回搜索</a>');
    }

    /**
     * 设置uri
     */
    protected function set_uri_back() {
        $uri = $this->uri->segment(1, 0);
        if ($uri) {
            if ($uri == 'collection') {
                $uri = $this->uri->segment(2, 0);
            }
            $this->uri_back = '<p  class="active text-left" style="background: #f5f5f5;"><a href="' . site_url($uri) . '">重新搜索</a></p>';
        } else {
            $this->uri_back = '<p  class="active text-left" style="background: #f5f5f5;"><a href="' . site_url('') . '">重新搜索</a></p>';
        }
    }

}
