<?php

class Jiangxi extends MY_Controller
{
    private $_origin = "http://gsxt.jxaic.gov.cn/";
    private $_base_url = 'http://gsxt.jxaic.gov.cn/';
    private $_list_url;
    private $_yzm_url;
    private $_yzm_ck_url;
    private $_index_pg;
    private $_detail_url = 'http://gsxt.jxaic.gov.cn/stateinfopages/grdzqy/companyinfo.jsp';
   
    protected $_name = '';//公司名
    protected $_vc = '';//验证码
    protected $_cookies = 'jiangxi.cookie';
    public $render_default = 'jiangxi/';

    function __construct()
    {
        parent::__construct();
        //验证码地址
        $this->_yzm_url = $this->_origin . 'warningetp/reqyzm.do?r='.time();
        $this->_yzm_ck_url = $this->_origin.'warningetp/yzm.do';
        $this->_index_pg = $this->_origin."index.jsp";
        //列表地址
        $this->_list_url = $this->_origin . 'search/queryenterpriseinfoindex.do';
         //设置cookie
        $this->cookie_jar .= $this->_cookies;
        array_push($this->_header, 'Referer:http://gsxt.saic.gov.cn/');
    }

    public function index()
    {
        $this->_render($this->render_default . 'index');
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
        $ename = $this->input->post('ename');
        self::post_data($vc, $name,$ename);
    }

    public function get_cookie()
    {
        curl_set_cookies($this->_origin, $this->_header, $this->cookie_jar);
    }

    public function yzm()
    {
        $contents = curl_get($this->_yzm_url, array(), $this->_header, $this->cookie_jar);
         ob_clean();
        //header("Content-type:image/jpg");
        echo $contents;
    }

    //提交数据，获取列表
    protected function post_data($vc, $name,$ename)
    {
        //1验证码校验
        $post_yzm  = array( 'inputvalue'=>$vc );
        $ck_1 = curl_post( $this->_yzm_ck_url, $post_yzm, $this->_header, $this->cookie_jar);
        if( !$ck_1 ){
            exit("<a href='" . site_url('collection/jiangxi') . "'>验证码问题,请返回!</a>");
        }
        //2查询数据
        $post_search = "ename=".$ename."&liketype=qyxy&pageIndex=0&pageSize=10";
        $contents = curl_post($this->_list_url, $post_search, $this->_header, $this->cookie_jar);
        self::set_list_data($contents);
    }

    //获取列表数据
    public function set_list_data($contents)
    {
        $list = $data = array();
        if ($contents && strlen($contents)) {
           $arr = json_decode($contents);
           $data['list'] = $arr->data;
        }
        $this->_render( $this->render_default.'list',$data);
    }

    //获取详情页
    public function contents()
    {
        $arr = $this->input->get(null, true);
        $post_arr = array(
            'pripid' => $arr['pripid'],
            'zchregno' => $arr['zchregno'],
            'regno'  => $arr['regno'],
                'company_name'=>$arr['name']
        );

        //1基本信息
        $base_info_url = 'http://gsxt.jxaic.gov.cn/baseinfo/queryenterpriseinfoByRegnore.do?pripid='.$post_arr['pripid'].'&_='.time();
        //$base_info = file_get_contents($base_info_url);
        
        $base_info = curl_get($base_info_url,array(),$this->_header,$this->cookie_jar);
//        $base_info = json_decode('['.$base_info.']') ;
//        var_dump($base_info);exit;
         //1.2股东
        $gd_info_url = 'http://gsxt.jxaic.gov.cn/einvperson/getqueryeInvPersonService.do?pripid='.$post_arr['pripid'].'&pageIndex=0&pageSize=5&_='.time();
        $gd_info = curl_get($gd_info_url,array(),  $this->_header,  $this->cookie_jar);
        //2.行政处罚
        $xzcf_url = 'http://gsxt.jxaic.gov.cn/casepubbaseinfo/queryCasepubbaseinfo.do?pripid='.$post_arr['pripid'].'&pageIndex=0&pageSize=5&_='.time();
        $czcf_info =curl_get($xzcf_url,array(),  $this->_header,  $this->cookie_jar);
        //3.经营异常
        $jyyc_url = 'http://gsxt.jxaic.gov.cn/opadetail/getqueryabnoperationinfo.do?pripid='.$post_arr['pripid'].'&pageIndex=0&pageSize=5&_='.time();
        $jyyc_info =curl_get($jyyc_url,array(),  $this->_header,  $this->cookie_jar);
        //4.严重违法
        $yzwf_url = 'http://gsxt.jxaic.gov.cn/eliilldishonestye/getqueryeliilldishonestye.do?pripid='.$post_arr['pripid'].'&pageIndex=0&pageSize=5&_='.time();
        $yzwf_info =curl_get($yzwf_url,array(),  $this->_header,  $this->cookie_jar);
        
        $base_info = json_encode(objectToArray(json_decode($base_info)));
        $strs_basic= json_decode($base_info);
        foreach($strs_basic as $ks=>$vs){
            $str_arr[$ks] = $vs;
        }
        $datas = arrayToObject($str_arr);
        $data['base_data'] =  $datas;
        
        
        $gd_info = json_decode($gd_info);
        if($gd_info->data){
            
            $data['gd'] = $gd_info->data;
        }
        $czcf_info = json_decode($czcf_info);
        if($czcf_info->data){
            $data['chufa'] = $czcf_info->data;
        }
        $jyyc_info = json_decode($jyyc_info);
        if($jyyc_info->data){
            $data['jyyc'] = $jyyc_info->data;
        }
        $yzwf_info = json_decode($yzwf_info);
        if($yzwf_info->data){
            $data['yzwf'] = $yzwf_info->data;
        }
        $this->_render($this->render_default . 'template', $data);
        $output = $this->output->get_output();//获取输出内容
        //保存html
        if(! get_one_item($post_arr['company_name'])){
            $ob_content = array(
                'province'=> 'jiangxi',
                'name' => encrypt(time().'zn').".html",
                'content' => $output
            );
            $path = $ob_content['province']."/".$ob_content['name'];
            if(!file_exists($path)){
                 make_html($path,$ob_content['content']);
            }
            //保存sql
            save2db( array('id'=> get_uid(),'company_name'=>$post_arr['company_name'],'page_path'=>base_url("html/".$path),'province'=>'江西省'));
        }
    }
    
    private function _format_echo($strs_gdfqr,$key){
        $gdfqr_arr = array();
        $strs_gdfqr= json_decode($strs_gdfqr);
        foreach($strs_gdfqr->items as $ks=>$vs){
            $gdfqr_arr[$ks] = $vs;
        }
       // $gd_arr = json_encode(array($key=>$gdfqr_arr));
        return $gdfqr_arr;
    }
}

?>