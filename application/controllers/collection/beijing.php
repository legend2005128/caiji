<?php
class Beijing extends MY_Controller
{
    private $_origin = "http://qyxy.baic.gov.cn";
    private $_list_url;
    private $_yzm_url;
    private $_ckyzm_url ;
    private $_literurl ;
    private $time_statmp;
    private $credit_ticket;

    protected $_name = '';//公司名
    protected $_vc = '';//验证码
    protected $_cookies = 'beijing.cookie';

    function __construct()
    {
        parent::__construct();
        //验证码地址
        //校验验证码地址
        $this->_ckyzm_url = $this->_origin.'/gjjbj/gjjQueryCreditAction!checkCode.dhtml';
        //列表地址
        $this->_list_url = $this->_origin. '/gjjbj/gjjQueryCreditAction!getBjQyList.dhtml';
        //限制词
        $this->_literurl = $this->_origin.'/gjjbj/gjjQueryCreditAction!findLiteralWord.dhtml';
        //设置cookie
        $this->cookie_jar .= $this->_cookies;
       
        array_push($this->_header, 'Upgrade-Insecure-Requests:1','Origin:http://qyxy.baic.gov.cn','Host:qyxy.baic.gov.cn','Referer:http://qyxy.baic.gov.cn/gjjbj/gjjQueryCreditAction!getBjQyList.dhtml');
    }
    
    public function index()
    {
         exit('No data');
        $this->_render('beijing/beijing');
    }
    public function vc()
    {
        //获取cookie
        self::get_cookie();
        //获取验证码
        self::yzm();
    }
    public function ps()
    {
        //post数据
        $vc = $this->input->post('vc');
        $name = $this->input->post('name');
        self::post_data($vc, $name);
    }
    public function get_cookie()
    {
        curl_set_cookies($this->_list_url, $this->_header, $this->cookie_jar);
//        $this->_yzm_url .= '/CheckCodeYunSuan?currentTimeMillis='.$this->time_statmp;
//        curl_get($this->_yzm_url, array(), $this->_header, $this->cookie_jar);
    }

    public function yzm()
    {
        $content_1 = curl_get($this->_list_url,array(),  $this->_header,  $this->cookie_jar);
        preg_match('#<input type="hidden"name="currentTimeMillis"id="currentTimeMillis"value="(.*)"/>#iUs', compress_html($content_1),$arr_1);
        preg_match('#<input type="hidden"name="credit_ticket"id="credit_ticket"value="(.*)"/>#iUs', compress_html($content_1),$arr_2);
        $this->time_statmp = $arr_1[1];
        $this->credit_ticket = $arr_2[1];
        $this->_yzm_url .= '/CheckCodeYunSuan?currentTimeMillis='.$this->time_statmp.'&num='.rand(9999,1000000);
        $contents = curl_get($this->_yzm_url, array(), $this->_header, $this->cookie_jar);
        header("Content-type:image/jpg");
        ob_clean();
        echo $contents;
    }

    protected function post_data($vc, $name)
    {
        $content_1 = curl_get($this->_list_url,array(),  $this->_header,  $this->cookie_jar);
        preg_match('#<input type="hidden"name="currentTimeMillis"id="currentTimeMillis"value="(.*)"/>#iUs', compress_html($content_1),$arr_1);
        preg_match('#<input type="hidden"name="credit_ticket"id="credit_ticket"value="(.*)"/>#iUs', compress_html($content_1),$arr_2);
        $this->time_statmp = $arr_1[1];
        $this->credit_ticket = $arr_2[1];
        $post ="currentTimeMillis=".$this->time_statmp."&checkcode=".$vc."&keyword=".$name."&credit_ticket=".$this->credit_ticket;
        //验证码
        $contents_code_ck = curl_post($this->_origin.'/gjjbj/gjjQueryCreditAction!checkCode.dhtml', $post, $this->_header, $this->cookie_jar);
        
        echo $contents_code_ck;
        var_dump($this->_origin.'/gjjbj/gjjQueryCreditAction!checkCode.dhtml', $post, $this->_header, $this->cookie_jar);
        exit  ;   
        
        if($contents_code_ck == 'fail'){
            exit("<a href='" . site_url('beijing') . "'>验证码错误,点击重新搜索!</a>");
        }
        if($contents_code_ck == 'tryCatch'){
            exit("<a href='" . site_url('beijing') . "'>程序错误,点击重新搜索!</a>");
        }
        //检测关键词
        $contents2 = curl_post($this->_literurl, $post, $this->_header, $this->cookie_jar);
        if( !$contents2 ){
            exit("<a href='".site_url('beijing')."'>输入关键词不符合系统标准,点击重新填写!</a>");
        }
        //列表页
        $contents3 = curl_post($this->_list_url, $post, $this->_header, $this->cookie_jar);
        echo $contents3;
    }
}

?>