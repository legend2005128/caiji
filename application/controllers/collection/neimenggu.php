<?php

class neimenggu extends MY_Controller
{
    private $_origin = "http://www.nmgs.gov.cn:7001/aiccips/";
    private $_list_url;
    private $_yzm_url;
    private $_yzm_ck ;
    private $_content_url;
    protected $_name = '';//公司名
    protected $_vc = '';//验证码
    protected $_cookies = 'neimenggu.cookie';
    public $render_default = 'neimenggu/';
    function __construct()
    {
        parent::__construct();
        //验证码地址
        $this->_yzm_url = $this->_origin.'verify.html?random='.time();
        $this->_yzm_ck =  $this->_origin.'CheckEntContext/checkCode.html';
        //列表地址
        $this->_list_url = $this->_origin. 'CheckEntContext/showInfo.html';
        //详情页
        $this->_content_url = 'http://www.nmgs.gov.cn:7001/aiccips/GSpublicity/GSpublicityList.html?service=entInfo_';
        //设置cookie
        $this->cookie_jar .= $this->_cookies;
        array_push($this->_header, 'Host:www.nmgs.gov.cn:7001');
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
       curl_set_cookies($this->_origin, $this->_header, $this->cookie_jar);
    }

    public function yzm()
    {
        $contents = curl_get($this->_yzm_url, array(), $this->_header, $this->cookie_jar);
        ob_clean();
        header("Content-type: image/png");
        echo $contents;
    }
    //提交数据，获取列表
    protected function post_data($vc, $name)
    {
        $post = 'textfield='.trim($name).'&code='.trim($vc) ;
        //校验码
        $contents2 = curl_post($this->_yzm_ck, $post, $this->_header, $this->cookie_jar);
        $ck_re = json_decode($contents2);
        if($ck_re->flag != 1){
             exit("<a href='" . site_url('/neimenggu') . "'>验证码问题,请返回!</a>");
        }
        //内容页
        $post = 'textfield='.$ck_re->textfield.'&code='.trim($vc) ;
        $contents3 = curl_post($this->_list_url, $post, $this->_header, $this->cookie_jar);
        self::set_list_data($contents3 );
    }
    //获取列表数据
    public function set_list_data($contents)
    {
        $list = array();
        if ($contents && strlen($contents)) {
            $con = compress_html($contents);
            preg_match_all('#<div class="list">(.*)<\/div>#iUs', $con, $arr);
            if (!$arr || !@$arr[1]) {
                exit("<a href='" . site_url('collection/neimenggu') . "'>暂无数据重新搜索</a>");
            }
            foreach ($arr[1] as $k => $v) {
                $arr2 = array();
                preg_match_all('#<li class="font16"><a href="(.*)html\?service=entInfo_(.*)"target=_blank class=\'font16\'>(.*)<\/a><\/li>#iUs', $v, $arr2);
                $list[$k]['name'] = $arr2[3][0];
                $list[$k]['url'] = $arr2[2][0];
            }
        }
        $data['list'] = $list;
        $this->_render( $this->render_default.'list',$data);
    }
    //获取详情页
    public function contents( ){
        $arr = $this->input->get(null,true);
        $post_arr =array(
             'url'=> $arr['url'],
             'name'=> $arr['name']
        );
        array_push($this->_header, 'Referer:http://www.nmgs.gov.cn:7001/aiccips/CheckEntContext/showInfo.html');
          
        $url = $this->_content_url.$post_arr['url'];
        $url = str_replace('@@','+',$url);
         $con = curl_get($url,array(),$this->_header,$this->cookie_jar);
        $con = compress_html($con);
        //1基本信息|股东人        
        preg_match('#<table cellspacing="0"cellpadding="0"class="detailsList"id="baseinfo">(.*)<\/table>#iUs',$con,$arrs);
        $data['base']  = $arrs[0];
        //2股东及出资变更信息
        preg_match('#<table cellpadding="0"cellspacing="0"class="detailsList"id="touzirentop"style="">(.*)<\/table>#iUs',$con,$arrs1_1);
        $data['base'] .= $arrs1_1[0];
        preg_match('#<table cellpadding="0"cellspacing="0"class="detailsList"id="touziren"style="">(.*)<\/table>#iUs',$con,$arrs1_2);
        $data['base'] .= $arrs1_2[0];
        preg_match('#<div id="biangeng"(.*)>(.*)<\/div>#iUs',$con,$arrs1_3);
        $data['base'] .= '<table  cellpadding="0" cellspacing="0" class="detailsList">
    <tr width="95%"><th colspan="4" style="text-align:center;">变更信息</th></tr>
    <tr width="95%">
        <th width="15%" style="text-align:center;"> 变更事项</th>
        <th width="35%" style="text-align:center;"> 变更前内容</th>
        <th width="35%" style="text-align:center;"> 变更后内容</th>
        <th width="15%" style="text-align:center;"> 变更日期</th>
    </tr>
</table>' .$arrs1_3[2];
         
          //获取信息，进行其他信息采集
        
        $post_other_url ='';
          preg_match('#<input type="hidden"id="entNo"name="entNo"value="(.*)">#iUs',$con, $other_base_1);
          preg_match('#<input type="hidden"id="entType"name="entType"value="(.*)">#iUs',$con, $other_base_2);
          preg_match('#<input type="hidden"id="regOrg"name="regOrg"value="(.*)">#iUs',$con, $other_base_3);
          $entno = $other_base_1[1];
          $entType = $other_base_2[1];
          $regOrg = $other_base_3[1];
         $post_other_url = 'entNo='.$entno.'&entType='.$entType.'&regOrg='.$regOrg;
    
           //2.行政处罚
          $url_cf = 'http://www.nmgs.gov.cn:7001/aiccips/GSpublicity/GSpublicityList.html?service=cipPenaltyInfo';
          $con_cf= curl_post( $url_cf,$post_other_url,$this->_header,$this->cookie_jar );  
          preg_match('#<div id="xingzhengchufa"style="height:840px;overflow:auto;">(.*)<\/div>#iUs',  compress_html($con_cf),$arr_cf );
          $data['chufa'] = $arr_cf[1];
          //3.经营异常
          $url_jyyc = 'http://www.nmgs.gov.cn:7001/aiccips/GSpublicity/GSpublicityList.html?service=cipUnuDirInfo';
          $con_jyyc = curl_post( $url_jyyc,$post_other_url,$this->_header,$this->cookie_jar );
          preg_match('#<div id="yichangminglu"style="height:840px;overflow:auto;">(.*)<\/div>#iUs',compress_html($con_jyyc),$arr_jyyc );
          $data['jyyc'] = $arr_jyyc[1];

         //4.严重违法 || 原网站无法打开，此处代码
         $url_yzwf = 'http://www.nmgs.gov.cn:7001/aiccips/GSpublicity/GSpublicityList.html?service=cipBlackInfo';
         $con_yzwf = curl_post( $url_yzwf,$post_other_url,$this->_header,$this->cookie_jar );
         preg_match('#<div id="yanzhongweifa"style="height:840px;overflow:auto;">(.*)<\/div>#iUs',compress_html($con_yzwf),$arr_yzwf );
         $data['yzwf'] = $arr_yzwf[1];
          
         $this->_render(  $this->render_default.'template',$data);
         //保存html
         if(! get_one_item($post_arr['name'])){
            $output = $this->output->get_output();//获取输出内容
            $ob_content = array(
                'province'=> 'neimenggu',
                'name' => encrypt(time().'zn').".html",
                'content' => $output
            );
            $path = $ob_content['province']."/".$ob_content['name'];
            if(!file_exists($path)){
                make_html($path,$ob_content['content']);
            }
            //保存sql
            save2db( array('id'=> get_uid(),'company_name'=>$post_arr['name'],'page_path'=>base_url("html/".$path),'province'=>'内蒙古'));
        }
    }
}

?>