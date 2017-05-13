<?php

class Shandong extends MY_Controller
{
    private $_origin = "http://218.57.139.24/";
    private $_base_url ='http://218.57.139.24/';
    private $_list_url;
    private $_list_url2;
    private $_yzm_url;
    private $_detail_url;

    protected $_name = '';//公司名
    protected $_vc = '';//验证码
    protected $_cookies = 'shandong.cookie';
    public $render_default = 'shandong/';

    function __construct()
    {
        parent::__construct();
        //验证码地址
        $this->_yzm_url = $this->_origin . 'securitycode?'.time();
        //列表地址
        $this->_list_url = $this->_origin. 'pub/indsearch';
        $this->_list_url2 = $this->_origin. 'pub/search';
        //设置cookie
        $this->cookie_jar .= $this->_cookies;
        //详情页
        $this->_detail_url = $this->_origin.'pub/gsgsdetail/';
    }

    public function index()
    {
        $this->_render( $this->render_default.'index' );
    }
    public function vc()
    {
        //获取cookie
        self::get_cookie();
        //获取验证码
        self::yzm();
    }
    public function ps( )
    {
        //post数据
        $vc = $this->input->post('vc');
        $name = $this->input->post('name');
        $secode = $this->input->post('secode');
        self::post_data($vc, $name,$secode);
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
    public function post_data($vc, $name,$secode ,$page_no =1)
    {
        //获取token
        $con = curl_get($this->_base_url, array(), $this->_header, $this->cookie_jar);
        preg_match( '#<input type="hidden" name="_csrf" value="(.*)" />#iUs', $con, $arr );
        //获取参数csrf
        $post = 'kw='.$name.'&_csrf='.$arr[1].'&secode='.$secode;
        $arr1 = curl_post( $this->_list_url, $post, $this->_header, $this->cookie_jar);
        if(!$arr1){
            exit('<a href="'.site_url('collection/shandong/index').'"> 暂无数据，返回</a>');
        }
        preg_match("#var enckeyword='(.*)'#iUs", compress_html($arr1),$arr2);
        if(!$arr2){
            exit('<a href="'.site_url('collection/shandong/index').'"> 验证码错误或者token获取失败，请点击返回</a>');
        }
        $post2 = 'param='.$arr2[1]."&".$post;
        array_push($this->_header,"X-CSRF-TOKEN：{$arr[1]}");
        $arr_con = curl_post( $this->_list_url2, $post2, $this->_header, $this->cookie_jar);
        $list_arr = json_decode($arr_con);
        $data['list'] = $list_arr;
        $this->_render( $this->render_default.'list',$data);
    }

    //获取详情页
    public function contents( ){
        $arr = $this->input->get(null,true);
        $post_arr =array(
             'id'=> $arr['encrptpripid'],
             'name'=> $arr['entname'],
             'type'=> $arr['enttype'],
        );
        $this->_detail_url .= $post_arr['type'].'/'.$post_arr['id'];
        $con = curl_get($this->_detail_url,array(),$this->_header,$this->cookie_jar);
        $con = compress_html($con);
        preg_match('#<div id="jibenxinxi"(.*)>(.*)<\/div>#iUs',$con,$arr_info);//1基本信息|股东人
        //1.2股东信息
        preg_match("#var czxxliststr ='(.*)'\;#iUs",$con,$arr_gd);//1基本信息|股东人
        $data['base_gd'] = json_decode($arr_gd[1]);
        //1.3变更信息
        preg_match("#var bgsxliststr ='(.*)'\;#iUs",$con,$arr_bg);//1基本信息|股东人
        $data['base_bg'] = json_decode($arr_bg[1]);
        //2.1行政处罚
        preg_match( '#<meta name="_csrf"content="(.*)"/>#iUs', $con, $arr );
        $xzcf_url = 'http://218.57.139.24/pub/jyyc/'.$post_arr['type'];
        array_push($this->_header,"X-CSRF-TOKEN：{$arr[1]}");
        $arr_xzcf = curl_post( $xzcf_url,'encrpripid='.$post_arr['id'].'&_csrf='.$arr[1], $this->_header, $this->cookie_jar);
        $arr_xzcf = json_decode($arr_xzcf);
        $data['jyyc_con'] = $arr_xzcf;
        preg_match('#<div id="xingzhengchufa"(.*)>(.*)<\/div>#iUs',$con,$arr_chufa);//2行政处罚
        preg_match('#<div id="jingyingyichangminglu"(.*)>(.*)<\/div>#iUs',$con,$arr_jyyc);//3经营异常
        preg_match('#<div id="yanzhongweifaqiye"(.*)>(.*)<\/div>#iUs',$con,$arr_yzwf);//4严重违法
        //1.1基础信息
        $data['base']  = $arr_info[2];
        $data['chufa'] = $arr_chufa[2];
        $data['jyyc'] = $arr_jyyc[2];
        $data['yzwf'] = $arr_yzwf[2];

        $this->_render(  $this->render_default.'template',$data);
        //保存html
        if(! get_one_item($post_arr['name'])){
            $output = $this->output->get_output();//获取输出内容
            $ob_content = array(
                'province'=> 'shandong',
                'name' => encrypt(time().'zn').".html",
                'content' => $output
            );
            $path = $ob_content['province']."/".$ob_content['name'];
            if(!file_exists($path)){
                make_html($path,$ob_content['content']);
            }
            //保存sql
            save2db( array('id'=> get_uid(),'company_name'=>$post_arr['name'],'page_path'=>base_url("html/".$path),'province'=>'山东省'));
        }
    }
}

?>