<?php

class Tianjin extends MY_Controller
{
    private $_origin = "http://tjcredit.gov.cn";
    private $_list_url;
    private $_yzm_url;
    protected $_name = '';//公司名
    protected $_vc = '';//验证码
    protected $_cookies = 'tianjin.cookie';

    function __construct()
    {
        parent::__construct();
        //验证码地址
        $this->_yzm_url = $this->_origin.'/verifycode?date='.time();
        //列表地址
        $this->_list_url = $this->_origin. '/platform/saic/search.ftl';
        //设置cookie
        $this->cookie_jar .= $this->_cookies;
        array_push($this->_header, 'Upgrade-Insecure-Requests:1','Referer:http://gsxt.saic.gov.cn/','Connection:keep-alive','Host:tjcredit.gov.cn','Cache-Control:max-age=0');
    }

    public function index()
    {
           //获取cookie
        self::get_cookie();
        $this->_render('tianjin/index');
    }

    public function vc()
    {
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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_list_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->_header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 2);
        curl_setopt($ch,CURLOPT_COOKIESESSION,TRUE);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_jar);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1); 
        //curl_setopt(curl, CURLOPT_MAXREDIRS, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
        $content = curl_exec($ch);
        curl_close($ch);
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
        $post = "matchCondition=1&searchContent=".$name."&vcode=" . $vc ;
        $this->_name = $name;
        $this->_vc = $vc;
        $url3 = $this->_list_url;
        $contents3 = curl_post($url3, $post, $this->_header, $this->cookie_jar);
        self::set_list_data($contents3 );
    }
    //获取列表数据
    public function set_list_data($content_list =false)
    {
        preg_match('#<div class="content">(.*)<div class="bottom-bg">#iUs',$content_list,$arr );
        if(!$arr)
        {
            exit('<a href="'.site_url('collection/tianjin/index').'"> 暂无数据，返回</a>');
        }
        preg_match_all('#<a href="(.*)"><span class="company-name">(.*)</a>#iUs' ,$arr[1],$arr_2);
        if($arr_2){
            $list_arr['title'] = $arr_2[2];
            $list_arr['url'] = $arr_2[1];
        }
        $data['list'] = $list_arr;
        $this->_render('tianjin/list',$data);
    }
    //获取详情页
    public function contents( ){
        $gets = $this->input->get(null,true);
        //采集页面
        $url = $gets['url'];
        $entid = $gets['entId'];
        $name = strip_tags($gets['name']);
        //1基本信息|股东人
        $base_url = $this->_origin.'/platform/saic/baseInfo.json?entId='.$entid.'&departmentId=scjgw&infoClassId=dj';
        $data['base_content'] = str_replace('详情','',file_get_contents($base_url));
      //  $content_base = curl_get( $base_url,array(),$this->_header,$this->cookie_jar );
        //2.行政处罚
        $chufa_url = $this->_origin.'/platform/saic/baseInfo.json?entId='.$entid.'&departmentId=scjgw&infoClassId=xzcf';
        $data['chufa_content'] = file_get_contents($chufa_url);
        //$content_xzcf = curl_get( $chufa_url,array(),$this->_header,$this->cookie_jar );
        //3.经营异常
        $jyyc_url = $this->_origin.'/platform/saic/baseInfo.json?entId='.$entid.'&departmentId=scjgw&infoClassId=qyjyycmlxx';
        $data['jyyc_content'] = file_get_contents($jyyc_url);
       // $content_jyyc = curl_get( $jyyc_url,array(),$this->_header,$this->cookie_jar );
        //4.严重违法
        $yzwf_url = $this->_origin.'/platform/saic/baseInfo.json?entId='.$entid.'&departmentId=scjgw&infoClassId=yzwfqyxx';
        $data['yzwf_content'] = file_get_contents($yzwf_url);
        //$content_yzwf = curl_get( $yzwf_url,array(),$this->_header,$this->cookie_jar );
        $this->_render( 'tianjin/template',$data);
        //保存html
        if(! get_one_item($name)) {
            $output = $this->output->get_output();//获取输出内容
            $ob_content = array(
                'province' => 'tianjin',
                'name' => encrypt(time() . 'zn') . ".html",
                'content' => $output
            );
            $path = $ob_content['province'] . "/" . $ob_content['name'];
            if (!file_exists($path)) {
                make_html($path,  $ob_content['content']);
            }
            //保存sql
            save2db(array('id' => get_uid(), 'company_name' => $name, 'page_path' => base_url("html/" . $path), 'province' => '天津市'));
        }
    }
}

?>