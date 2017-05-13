<?php

class Zhejiang extends MY_Controller
{
    private $_origin = 'http://gsxt.zjaic.gov.cn/';
    private $_base_url = 'http://gsxt.zjaic.gov.cn/zhejiang.jsp';
    private $_list_url;
    private $_yzm_url = "common/captcha/doReadKaptcha.do";

    protected $_name = '';//公司名
    protected $_vc = '';//验证码
    protected $_cookies = 'zhejiang.cookie';
    public $render_default = 'zhejiang/';

    function __construct()
    {
        parent::__construct();
        //验证码地址
        $this->_yzm_url = $this->_origin.'common/captcha/doReadKaptcha.do?'.rand(0,100);
        //列表地址
        $this->_list_url = $this->_origin."/search/doGetAppSearchResult.do";
        //设置cookie
        $this->cookie_jar .= $this->_cookies;
         array_push($this->_header,'Referer:http://gsxt.zjaic.gov.cn/zhejiang.jsp');
    }
    //首页
    public function index()
    {
        $this->_render( $this->render_default.'index' );
    }
    //验证码
    public function vc()
    {
        //获取cookie
        self::get_cookie();
        //获取验证码
        self::yzm();
    }
    //post data
    public function ps()
    {
        //post数据
        $vc = $this->input->post('vc');
        $name = $this->input->post('name');
        self::post_data($vc, $name);
    }
    //set cookie
    public function get_cookie()
    {
        curl_set_cookies($this->_base_url, $this->_header, $this->cookie_jar);
    }
    //验证码
    public function yzm()
    {
        $content_cookie = curl_get( $this->_base_url, array(), $this->_header, $this->cookie_jar );
        preg_match( '#<input type="hidden" name="struts.token.name" value="(.*)" \/>#iUs',$content_cookie,$arr1 );
        preg_match( '#<input type="hidden" name="'.$arr1[1].'" value="(.*)" \/> #iUs',$content_cookie,$arr2);
        ob_clean();
        $contents = curl_get( $this->_yzm_url."&".$arr1[1].'='.$arr2[1], array(), $this->_header, $this->cookie_jar );
        header("Content-type: image/png");
        echo $contents;
    }
    //提交数据，获取列表
    protected function post_data($vc, $name)
    {
        $post = "clickType=1&verifyCode=".$vc."&name=".$name;
        $url3 = $this->_list_url;
        $contents3 = curl_post($url3, $post, $this->_header, $this->cookie_jar);
        preg_match_all('#<dt class="font16"><ahref="(.*)"class="entLink"entName="(.*)"target=_blank>(.*)<\/a><\/dt>#iUs',  compress_html($contents3),$arr1);
        foreach($arr1[1] as $key=>$v){
           $list_arr[$key]['url'] = $v;
           $list_arr[$key]['name'] = $arr1[2][$key];
        }
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
             'corpid'=> $arr['corpid'],
             'name'=> $arr['name']
        );
        $ks = array_search('Referer:http://gsxt.zjaic.gov.cn/zhejiang.jsp',$this->_header);
        unset($this->_header[$ks]);
        array_push($this->_header,'Host:gsxt.zjaic.gov.cn','Referer:http://gsxt.zjaic.gov.cn/appbasicinfo/doViewAppBasicInfo.do?corpid='.$post_arr['corpid']) ;
        $content_url = $this->_origin.'appbasicinfo/doReadAppBasicInfo.do?corpid='.$post_arr['corpid'];
        $contents = curl_get( $content_url,array(),$this->_header,$this->cookie_jar );
        $base_content = compress_html($contents);
        
        //1基本信息|股东人
        preg_match('#<table cellspacing="0"cellpadding="0"class="detailsList"id="baseinfo">(.*)</table>#iUs',$base_content,$arrs);
        $data['base']  = $arrs[1];
        //2股东及出资变更信息
        preg_match_all('#<table cellpadding="0"cellspacing="0"class="detailsList">(.*)</table>#iUs',$base_content,$arrs1);
        $data['base_gd']  = str_replace('更多','',$arrs1[0][0].$arrs1[0][1]);

        //2.行政处罚
        $chufa_url = $this->_origin.'punishment/doViewPunishmentFromPV.do?corpid='.$post_arr['corpid'];
        $chufa_content= curl_get( $chufa_url,array(),$this->_header,$this->cookie_jar );
        preg_match('#<table cellpadding="0"cellspacing="0"class="detailsList">(.*)</table>#iUs',compress_html($chufa_content),$arrs2);
        $data['chufa']  = str_replace('href="/punishment', 'target="_blank" href="http://gsxt.zjaic.gov.cn/punishment', $arrs2[1]);
        //3.经营异常
        $jyyc_url = $this->_origin.'catalogapply/doReadCatalogApplyList.do?corpid='.$post_arr['corpid'];
        $jyyc_content= curl_get( $jyyc_url,array(),$this->_header,$this->cookie_jar );
        preg_match('#<table cellpadding="0"cellspacing="0"class="detailsList">(.*)</table>#iUs',compress_html($jyyc_content),$arrs3);
        $data['jyyc']  = $arrs3[1];
        //4.严重违法
        $this->_render(  $this->render_default.'template',$data);
        //保存html
        if(! get_one_item($post_arr['name'])){
            $output = $this->output->get_output();//获取输出内容
            $ob_content = array(
                'province'=> 'zhejiang',
                'name' => encrypt(time().'zn').".html",
                'content' => $output
            );
            $path = $ob_content['province']."/".$ob_content['name'];
            if(!file_exists($path)){
                make_html($path,$ob_content['content']);
            }
            //保存sql
            save2db( array('id'=> get_uid(),'company_name'=>$post_arr['name'],'page_path'=>base_url("html/".$path),'province'=>'浙江省'));
        }
    }
}

?>