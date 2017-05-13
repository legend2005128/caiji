<?php

class Liaoning extends MY_Controller
{
    private $_origin = "http://gsxt.lngs.gov.cn/";
    private $_list_url;
    private $_yzm_url;

    protected $_name = '';//公司名
    protected $_vc = '';//验证码
    protected $_cookies = 'liaoning.cookie';
    public $render_default = 'liaoning/';
    function __construct()
    {
        parent::__construct();
        //验证码地址
        $randnum = rand(9999,99999);
        $this->_yzm_url = $this->_origin.'saicpub/commonsSC/loginDC/securityCode.action?tdate='.$randnum;
        //列表地址
        $this->_list_url = $this->_origin. 'saicpub/entPublicitySC/entPublicityDC/lngsSearchListFpc.action';
        //设置cookie
        $this->cookie_jar .= $this->_cookies;
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
    }

    public function yzm()
    {
        $contents = curl_get($this->_yzm_url, array(), $this->_header, $this->cookie_jar);
        ob_clean();
        echo $contents;
    }
    //提交数据，获取列表
    protected function post_data($vc, $name)
    {
        $post = "solrCondition=".$name."&authCode=" . $vc ;
        $url3 = $this->_list_url;
        $contents3 = curl_post($url3, $post, $this->_header, $this->cookie_jar);
        preg_match('#searchList_paging\((.*)}\]#iUs',$contents3,$arr1);
        $arr2 = trim(str_replace(array('var',' '),'',$arr1[1].'}]' ));
        $list_arr = json_decode($arr2);

      self::set_list_data($list_arr );
    }
    //获取列表数据
    public function set_list_data($list_arr)
    {
        $data['list'] = $list_arr;
        $this->_render( $this->render_default.'list',$data);
    }
    //获取详情页
    public function contents( ){
        $arr = $this->input->get(null,true);
        $post_arr =array(
             'pripid'=> $arr['pripid'],
             'entname'=> $arr['entname'],
             'regno'=>$arr['regno'],
             'enttype'=>$arr['enttype'],
             'opstate'=>$arr['opstate'],
             'revdate'=>$arr['revdate']
        );


        //1基本信息|股东人
        $base_url = $this->_origin.'saicpub/entPublicitySC/entPublicityDC/getJbxxAction.action?pripid='.$post_arr['pripid'].'&type='.$post_arr['enttype'];
        $base_content = curl_get( $base_url,array(),$this->_header,$this->cookie_jar );
        preg_match('#<div id="jibenxinxi" >(.*)</div>#iUs',$base_content,$arrs);
        $data['base']  = $arrs[1];
            
        //2股东及出资变更信息
        $gds_url  = $this->_origin.'saicpub/entPublicitySC/entPublicityDC/getTzrxxAction.action?pripid='.$post_arr['pripid'].'&type='.$post_arr['enttype'];
        $gd_con = curl_get( $gds_url,array(),$this->_header,$this->cookie_jar );
        preg_match('#<body>(.*)</body>#iUs', $gd_con,$con_gds );        
        $data['base'] .= $con_gds[1]; 
        preg_match('#tzr_paging\((.*)\}\]#iUs', $gd_con,$gd_arr);
        $gd_arr = json_decode($gd_arr[1].'}]');
        $data['data_gd'] = $gd_arr;
       // $data['base_gd'] 
        //2.行政处罚
        $chufa_url = $this->_origin.'saicpub/entPublicitySC/entPublicityDC/getXzcfxxAction.action?pripid='.$post_arr['pripid'].'&type='.$post_arr['enttype'];
         $chufa_con = curl_get( $chufa_url,array(),$this->_header,$this->cookie_jar );
         preg_match('#<body>(.*)</body>#iUs', $chufa_con,$chufa_arr );        
        $data['chufa'] = $chufa_arr[1];
        //3.经营异常
        $jyyc_url = $this->_origin.'saicpub/entPublicitySC/entPublicityDC/getJyycxxAction.action?pripid='.$post_arr['pripid'].'&type='.$post_arr['enttype'];
        $jyyc_con = curl_get( $jyyc_url,array(),$this->_header,$this->cookie_jar );
        preg_match('#<body>(.*)</body>#iUs', $jyyc_con,$jyyc_arr );      
        preg_match('#jyyc_paging\((.*)\}\]#iUs', $jyyc_con,$jyyc_arr1);
        $jyyc_arr1 = json_decode($jyyc_arr1[1].'}]');
        $data['data_jyyc'] = $jyyc_arr1;
        $data['jyyc'] = $jyyc_arr[1];
        //4.严重违法
        $yzwf_url =$this->_origin.'saicpub/entPublicitySC/entPublicityDC/getYzwfxxAction.action?pripid='.$post_arr['pripid'].'&type='.$post_arr['enttype'];
        $yzwf_con = curl_get( $yzwf_url,array(),$this->_header,$this->cookie_jar );
         preg_match('#<body>(.*)</body>#iUs', $yzwf_con,$yzwf_arr );      
        preg_match('#yzwf_paging\((.*)\}\]#iUs', $yzwf_con,$yzwf_arr1);
        $yzwf_arr1 = json_decode($yzwf_arr1[1].'}]');
        $data['data_yzwf'] = $yzwf_arr1;
        $data['yzwf'] = $yzwf_arr[1];
        
        $this->_render(  $this->render_default.'template',$data);
        //保存html
        if(! get_one_item($post_arr['entname'])){
            $output = $this->output->get_output();//获取输出内容
            $ob_content = array(
                'province'=> 'liaoning',
                'name' => encrypt(time().'zn').".html",
                'content' => $output
            );
            $path = $ob_content['province']."/".$ob_content['name'];
            if(!file_exists($path)){
                make_html($path,$ob_content['content']);
            }
            //保存sql
            save2db( array('id'=> get_uid(),'company_name'=>$post_arr['entname'],'page_path'=>base_url("html/".$path),'province'=>'辽宁省'));
        }
    }
}

?>