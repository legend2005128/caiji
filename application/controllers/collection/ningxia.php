<?php
/**  宁夏
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/21
 * Time: 16:30
 */
require_once (APPPATH.'/libraries/phpQuery/phpQuery.php');
class Ningxia extends MY_Controller
{
    private $_base_url = 'http://gsxt.ngsh.gov.cn/ECPS/';
    private $_getdata_url = 'http://gsxt.ngsh.gov.cn/ECPS/qyxxgsAction_queryXyxx.action';
    private $_verify_url = '';
    private $_checkcode_url = 'http://gsxt.ngsh.gov.cn/ECPS/qyxxgsAction_checkVerificationCode.action';
    protected $_cookies = 'ningxia.cookie';
    function __construct()
    {
        parent::__construct();
        $this->cookie_jar .= $this->_cookies;
        $this->_verify_url = 'http://gsxt.ngsh.gov.cn/ECPS/verificationCode.jsp?_='.time();
    }

    /**  首页
     * User: Tmc
     * Date: 2016/9/21
     * Time: 14:30
     */
    public function index()
    {
        $this->_render('ningxia/index');
    }

    /**  列表页
     * User: Tmc
     * Date: 2016/9/19
     * Time: 12:30
     */
    public function serchlist(){
        //post数据
        $code = $this->input->post('code');
        $name = $this->input->post('name');
        $post_ck_code = 'password='.$code;
        $ck_2 = curl_post($this->_checkcode_url, $post_ck_code, $this->_header, $this->cookie_jar);
        //走验证码
        if( strpos($ck_2,'ok')==false ){
            exit("<a href='" . site_url('collection/ningxia') . "'>验证码不正确,请返回!</a>");
        }
        $post_data = 'isEntRecord=&password='.$code.'&loginInfo.regno=&loginInfo.entname=&loginInfo.idNo=&loginInfo.mobile=&loginInfo.password=&loginInfo.verificationCode=&otherLoginInfo.name=&otherLoginInfo.password=&otherLoginInfo.verificationCode=&selectValue='.$name;
        $content = curl_post($this->_getdata_url, $post_data, $this->_header, $this->cookie_jar);
        $content = phpquery_char('utf-8',$content);
        phpQuery::newDocumentHTML($content);
        $list = pq('#qyList')->find('div');
        foreach ($list as $key => $val) {
            $info_url = pq($val)->find('a')->attr('href');
            $title = pq($val)->find('a')->text();
            $date[] = array(
                'info_url' => $info_url,
                'title' => $title,
            );
        }
        $date = array_slice($date,0,5);//截取前5个数据
        $data['list'] = $date;
        $this->_render('ningxia/serchlist',$data);
    }

    public function detail()
    {
        $info_url = str_replace('info_url=','',$_SERVER["QUERY_STRING"]);//获取url参数
        $info_url = $this->_base_url.$info_url;
        $content_info = curl_get($info_url,array(),$this->_header, $this->cookie_jar);
        $content_info = phpquery_char('utf-8',$content_info);
        phpQuery::newDocumentHTML($content_info);
        //公司名
        $title_info = pq('h2')->text();
        //基本信息
        $base_u1 = pq('#jibenxinxi')->find('iframe:eq(0)')->attr('src');
        $base_url1 = $this->_base_url.trim($base_u1);
        //变更信息
        $base_u2 = pq('#jibenxinxi')->find('iframe:eq(1)')->attr('src');
        $base_url2 = $this->_base_url.trim($base_u2);
        //行政处罚
        $xzcf_u = pq('#xzcfxx')->attr('src');
        $xzcf_url = $this->_base_url.trim($xzcf_u);
        //经营异常
        $jyyc_u = pq('#jyycxx')->attr('src');
        $jyyc_url = $this->_base_url.trim($jyyc_u);
        //严重违法
        $yzwf_u = pq('#yzwfxx')->attr('src');
        $yzwf_url = $this->_base_url.trim($yzwf_u);

        //基本信息
        $base_info1 = curl_get($base_url1,array(),$this->_header, $this->cookie_jar);
        $base_info1 = phpquery_char('utf-8',$base_info1);
        phpQuery::newDocumentHTML($base_info1);
        $base_info =  pq('.detailsList')->html();
        //公司名称
        $title = pq('.detailsList')->find('tr:eq(1)')->find('td:eq(1)')->text();
        //变更信息
        $base_info2 = curl_get($base_url2,array(),$this->_header, $this->cookie_jar);
        $base_info2 = iconv('gbk','utf-8',$base_info2);
        $base_info2 = phpquery_char('utf-8',$base_info2);
        phpQuery::newDocumentHTML($base_info2);
        $bgxx_info = pq('.detailsList')->html();
        //行政处罚
        $xzcf_con = curl_get($xzcf_url,array(),$this->_header, $this->cookie_jar);
//        $xzcf_con = iconv('gbk','utf-8',$xzcf_con);
        $xzcf_con = phpquery_char('utf-8',$xzcf_con);
        phpQuery::newDocumentHTML($xzcf_con);
        $xzcf_info =  pq('.detailsList')->html();
        //经营异常
        $jyyc_con = curl_get($jyyc_url,array(),$this->_header, $this->cookie_jar);
        $jyyc_con = iconv('gbk','utf-8',$jyyc_con);
        $jyyc_con = phpquery_char('utf-8',$jyyc_con);
        phpQuery::newDocumentHTML($jyyc_con);
        $jyyc_info =  pq('.detailsList')->html();
        //严重违法
        $yzwf_con = curl_get($yzwf_url,array(),$this->_header, $this->cookie_jar);
        $yzwf_con = iconv('gbk','utf-8',$yzwf_con);
        $yzwf_con = phpquery_char('utf-8',$yzwf_con);
        phpQuery::newDocumentHTML($yzwf_con);
        $yzwf_info =  pq('.detailsList')->html();

        $data = array(
            'title_info' => trim($title_info),
            'base_info' => $base_info,
            'bgxx_info' => $bgxx_info,
            'xzcf_info' => $xzcf_info,
            'jyyc_info' => $jyyc_info,
            'yzwf_info' => $yzwf_info,
        );
        $this->_render('ningxia/detail',$data);
        //保存html
        if(! get_one_item(trim($title))){
            $output = $this->output->get_output();//获取输出内容
            $ob_content = array(
                'province'=> 'ningxia',
                'name' => encrypt(time().'zn').".html",
                'content' => $output
            );
            $path = $ob_content['province']."/".$ob_content['name'];
            if(!file_exists($path)){
//                make_html($path,iconv('utf-8','gb2312',$ob_content['content']));
                make_html($path,$ob_content['content']);
            }
            //保存sql
            save2db( array('id'=> get_uid(),'company_name'=>trim($title),'page_path'=>base_url("html/".$path),'province'=>'宁夏'));
        }
    }

    public function verify()
    {
        //获取cookie
        self::get_cookie();
        //获取验证码
        self::yzm();
    }

    public function get_cookie()
    {
        curl_set_cookies($this->_getdata_url, $this->_header, $this->cookie_jar);
    }

    public function yzm()
    {
        $contents = curl_get($this->_verify_url, array(), $this->_header, $this->cookie_jar);
        ob_clean();
        echo $contents;
    }

}

?>