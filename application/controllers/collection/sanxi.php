<?php
class Sanxi extends MY_Controller
{
    private $_origin = "http://gsxt.sxaic.gov.cn/";
    private $_originbase = "http://gsxt.sxaic.gov.cn/search.jspx";
    private $_list_url;
    private $_yzm_url;
    private $_yzm_ck_url;
    private $_entname_url = 'http://gsxt.sxaic.gov.cn/checkFilterKey.jspx';
    private $_content_url ='http://gsxt.sxaic.gov.cn/businessPublicity.jspx?id=';

    protected $_name = '';//公司名
    protected $_vc = '';//验证码
    protected $_cookies = 'sanxi.cookie';
    public $render_default = 'sanxi/';

    function __construct()
    {
        parent::__construct();
        //验证码地址
        $this->_yzm_url = $this->_origin . '/validateCode.jspx?type=0&id='.time();
        //验证码校验url
        $this->_yzm_ck_url = $this->_origin . '/checkCheckNo.jspx';

        //列表地址
        $this->_list_url = $this->_origin . '/searchList.jspx';
        //设置cookie
        $this->cookie_jar .= $this->_cookies;
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
        self::post_data($vc, $name);
    }

    public function get_cookie()
    {
        curl_set_cookies($this->_originbase, $this->_header, $this->cookie_jar);
    }

    public function yzm()
    {
        $contents = curl_get($this->_yzm_url, array(), $this->_header, $this->cookie_jar);
        ob_clean();
        header("Content-type:image/jpg");
        echo $contents;
    }

    //提交数据，获取列表
    protected function post_data($vc, $name)
    {
        //1后台校验entname
//        $post_ck_num = 'entName='.urlencode($name);
//        $ck_1 = curl_post($this->_entname_url, $post_ck_num, $this->_header, $this->cookie_jar);
        //2验证码校验
        $get_ck_code = "?checkNo=".$vc ;
        $ck_2 = curl_get($this->_yzm_ck_url.$get_ck_code, array(), $this->_header, $this->cookie_jar);
        //走验证码
        if( strpos($ck_2,'false')!==false ){
            exit("<a href='" . site_url('collection/sanxi') . "'>验证码问题,请返回!</a>");
        }
        //3查询数据
        $post = "entName=" . urlencode(trim($name)) . "&checkNo=" . $vc;
        $contents = curl_post($this->_list_url, $post, $this->_header, $this->cookie_jar);
        self::set_list_data($contents);
    }
    //获取列表数据
    public function set_list_data($contents)
    {
        $list = array();
        if ($contents && strlen($contents)) {
            $con = compress_html($contents);
            preg_match_all('#<div class="list">(.*)<\/div>#iUs', $con, $arr);
            if (!$arr || !@$arr[1]) {
                exit("<a href='" . site_url('collection/sanxi') . "'>暂无数据重新搜索</a>");
            }
            foreach ($arr[1] as $k => $v) {
                $arr2 = array();
                preg_match_all('#<li class="font16"><a href="\/businessPublicity\.jspx\?id=(.*)">(.*)<\/a><\/li>#iUs', $v, $arr2);
                $list[$k]['id'] = $arr2[1][0];
                $list[$k]['name'] = $arr2[2][0];
            }
        }
        $data['list'] = $list;
        $this->_render( $this->render_default.'list',$data);
    }

    //获取详情页
    public function contents()
    {
        $arr = $this->input->get(null, true);
        $post_arr = array(
            'id' => $arr['id'],
            'company_name' => $arr['name']
        );

        $base_url = $this->_content_url.$post_arr['id'];
        // $content = file_get_contents($base_url);
        $content = curl_get($base_url,array(),$this->_header,$this->cookie_jar);
        $content = str_replace( array('<br />','<br/>','</br>'),'',compress_html($content));
        //1基本信息
        preg_match('#<div id="jibenxinxi"(.*)>(.*)<\/div>#iUs',$content,$arr_base);
        $data['base'] = str_replace('详情','', preg_replace('/<div id="(.*)">/','', $arr_base[2]) );
        preg_match('#<div id="invDiv"(.*)>(.*)<\/div>#iUs',$content,$arr_base_gd);
        $data['base_gd'] = str_replace('详情','', $arr_base_gd[2]);
        //2.行政处罚
        preg_match('#<div id="xingzhengchufa"(.*)>(.*)<\/div>#iUs',$content,$arr_chufa );
        $data['chufa'] =preg_replace('/<div id="(.*)">/','', $arr_chufa[2] );
        //3.经营异常
        preg_match('#<div id="jingyingyichangminglu"(.*)>(.*)<\/div>#iUs',$content,$arr_jyyc );
        $data['jyyc'] = preg_replace('/<div id="excDiv">/','', $arr_jyyc[2] );
        //4.严重违法
        //  preg_match('##iUs',$content,$arr_yzwf );
        //  $data['yzwf'] = $arr_yzwf[1];
        $this->_render($this->render_default . 'template', $data);
        $output = $this->output->get_output();//获取输出内容
        //保存html
        if(! get_one_item($post_arr['company_name'])){
            $ob_content = array(
                'province'=> 'sanxi',
                'name' => encrypt(time().'zn').".html",
                'content' => $output
            );
            $path = $ob_content['province']."/".$ob_content['name'];
            if(!file_exists($path)){
                 make_html($path,$ob_content['content']);
            }
            //保存sql
            save2db( array('id'=> get_uid(),'company_name'=>$post_arr['company_name'],'page_path'=>base_url("html/".$path),'province'=>'山西省'));
        }
    }
}

?>