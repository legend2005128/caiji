<?php

class shanghai extends MY_Controller
{
    private $_origin = "https://www.sgs.gov.cn/";
    private $_list_url ;
    private $_yzm_url;
    private  $_base_url = 'https://www.sgs.gov.cn/notice/home';

    protected $_name = '';//公司名
    protected $_vc = '';//验证码
    protected $_cookies = 'shanghai.cookie';
    public $render_default = 'shanghai/';
    function __construct()
    {
        parent::__construct();
        //验证码地址
        $this->_yzm_url = $this->_origin.'notice/captcha?preset=cpt&ra='.time();
        //列表地址
        $this->_list_url = $this->_origin. 'notice/search/ent_info_list';
        //设置cookie
        $this->cookie_jar .= $this->_cookies;
    }

    public function index()
    {
      
        $this->_render( $this->render_default.'index' );
    }
    public function vc()
    {   //获取cookie
        self::get_cookie();
        //获取验证码
        self::yzm();
    }
    public function ps( )
    {
        //post数据
        $vc = $this->input->post('vc');
        $name = $this->input->post('name');
        self::post_data($vc, $name);
    }
    //设置cookie
    public function get_cookie()
    {
        curl_set_cookies($this->_base_url, $this->_header, $this->cookie_jar);
    }
    //生成验证码
    public function yzm()
    {
        $contents = curl_get($this->_yzm_url, array(), $this->_header, $this->cookie_jar);
        header("Content-type: image/png");
        echo $contents;
    }
    //提交数据，获取列表
    public function post_data($vc, $name,$page_no =1)
    {
        $content = curl_get($this->_base_url,array(), $this->_header, $this->cookie_jar);
        preg_match('#<input type="hidden" name="session.token" value="(.*)" />#iUs',$content,$arr);
        $session_token = $arr[1];
        //1.ip黑名单
        $ip_v =  $this->_origin. 'notice/security/verify_ip';
        $arr1 = curl_get($ip_v,array(), $this->_header, $this->cookie_jar);
        //2.关键词合法
        $keywords_v =  $this->_origin. 'notice/security/verify_keyword?keyword=.'.$name;
        $arr2 = curl_get($ip_v,array(), $this->_header, $this->cookie_jar);
        //3.请求列表
        $vc_v = $this->_origin. '/notice/security/verify_captcha';
        $post_vc = 'captcha='.$vc.'&session.token='.$session_token;
        $arr3 = curl_post($vc_v,$post_vc, $this->_header, $this->cookie_jar);
        if( !$arr1 || !$arr2 || !$arr3 )
        {
            $word_notice = !$arr2?'关键词违法':(!$arr3?'验证码错误':'ip请求非法');
            exit("<a href='" . site_url('collection/shanghai') . "'>{$word_notice},点击重新搜索!</a>");
        }
        $post = 'captcha='.$vc.'&session.token='.$session_token.'&condition.keyword='.urlencode($name).'&condition.insType=&condition.pageNo='.$page_no;
        $lists = curl_post( $this->_list_url, $post, $this->_header, $this->cookie_jar);
        if($lists){
            preg_match_all('#<div class="link"><a href="(.*)"target="_blank">(.*)<\/a><\/div>#iUs',compress_html($lists),$list_arrs);
            if(!$list_arrs){
                exit("<a href='" . site_url('collection/shanghai') . "'>暂无数据,点击重新搜索!</a>");
            }
           foreach($list_arrs[2] as $k=>$v){
               $list_arr[$k]['name'] = $v;
               $list_arr[$k]['url'] = $list_arrs[1][$k];
           }
        }
      $data['list'] = $list_arr;
     $this->_render( $this->render_default.'list',$data);
    }

    //获取详情页
    public function contents( ){

        $arr = $this->input->get(null,true);
        $post_arr =array(
             'name'=> $arr['name'],
             'url'=> $arr['url'],
        );
        $detail_url  = $post_arr['url'];
        $content = curl_get( $detail_url,array(),  $this->_header,  $this->_cookies );
        $con = compress_html($content);
        //1基本信息|股东人
        preg_match('#<div rel="layout-01_01"class="hide">(.*)<\/div>#iUs',$con,$arr_info);//基本信息
        //1.2股东信息
        preg_match('#<table id="investorTable"(.*)>(.*)<\/table>#iUs',$con,$arr_gd);//股东人
         //1.3变更信息
        preg_match('#<table id="alterTable"(.*)>(.*)<\/table>#iUs',$con,$arr_bg);//变更信息
 
        preg_match('#<table id="punishTable"(.*)>(.*)<\/table>#iUs',$con,$arr_chufa);//2行政处罚
        preg_match('#<table id="exceptTable"(.*)>(.*)<\/table>#iUs',$con,$arr_jyyc);//3经营异常
        $data['base']  = $arr_info[1].$arr_gd[0].$arr_bg[0];
        $data['chufa'] = $arr_chufa[0] ;
        $data['jyyc'] = $arr_jyyc[0];

      
        
        $this->_render(  $this->render_default.'template',$data);
        //保存html
        if(! get_one_item($post_arr['name'])){
            $output = $this->output->get_output();//获取输出内容
            $ob_content = array(
                'province'=> 'shanghai',
                'name' => encrypt(time().'zn').".html",
                'content' => $output
            );
            $path = $ob_content['province']."/".$ob_content['name'];
            if(!file_exists($path)){
                make_html($path,$ob_content['content']);
            }
            //保存sql
            save2db( array('id'=> get_uid(),'company_name'=>$post_arr['name'],'page_path'=>base_url("html/".$path),'province'=>'上海市'));
        }
    }
}

?>