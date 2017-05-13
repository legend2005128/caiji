<?php
/**  海南
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/20
 * Time: 14:30
 */
require_once (APPPATH.'/libraries/phpQuery/phpQuery.php');
class Hainan extends MY_Controller
{
    private $_base_url = 'http://aic.hainan.gov.cn:1888';
    private $_getdata_url = 'http://aic.hainan.gov.cn:1888/searchList.jspx';
    private $_verify_url = '';
    private $_checkcode_url = "http://aic.hainan.gov.cn:1888/checkCheckNo.jspx";
    protected $_name = '';//公司名
    protected $_code = '';//验证码
    protected $_cookies = 'hainan.cookie';
    function __construct()
    {
        parent::__construct();
        $this->cookie_jar .= $this->_cookies;
        $this->_verify_url = 'http://aic.hainan.gov.cn:1888/validateCode.jspx?type=0&id='.time();
    }

    /**  首页
     * User: Tmc
     * Date: 2016/9/20
     * Time: 14:30
     */
    public function index()
    {
        $this->_render('hainan/index');
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
        $post_ck_code = 'checkNo='.urlencode(urlencode(trim($code))) ;
        $ck_2 = curl_post($this->_checkcode_url, $post_ck_code, $this->_header, $this->cookie_jar);
        //走验证码
        if( strpos($ck_2,'false')!==false ){
            exit("<a href='" . site_url('collection/hainan') . "'>验证码不正确,请返回!</a>");
        }
        $post_data = array(
            'checkNo' => $code,
            'entName' => $name,
        );
        $content = curl_post($this->_getdata_url, $post_data, $this->_header, $this->cookie_jar);
        $content = phpquery_char('utf-8',$content);
        phpQuery::newDocumentHTML($content);
        $list = pq('.center-1')->find('.list');
        foreach ($list as $key => $val) {
            $info_url = pq($val)->find('a')->attr('href');
            $title = pq($val)->find('a')->text();
            $date[] = array(
                'info_url' => $info_url,
                'title' => $title,
            );
        }
        $data['list'] = $date;
        $this->_render('hainan/serchlist',$data);
    }

    public function detail()
    {
        $params = $this->input->get(null, true);
        $info_url = $this->_base_url.$params['info_url'];
        $content_info = curl_get($info_url,array(),$this->_header, $this->cookie_jar);
        $content_info = phpquery_char('utf-8',$content_info);
        phpQuery::newDocumentHTML($content_info);
        $title = $params['title'];
        $title_info = pq('h2')->text();
        $base_info = pq('#jibenxinxi')->find('table:eq(0)')->html();
        $bgxx_info = pq('#jibenxinxi')->find('table:eq(1)')->html();
        $xzcf_info = pq('#xingzhengchufa')->find('table')->html();
        $jyyc_info = pq('#jingyingyichangminglu')->find('table:eq(0)')->html();
        $yzwf_info = pq('#yanzhongweifaqiye')->find('table:eq(0)')->html();
        $data = array(
            'title_info' => $title_info,
            'base_info' => $base_info,
            'bgxx_info' => $bgxx_info,
            'xzcf_info' => $xzcf_info,
            'jyyc_info' => $jyyc_info,
            'yzwf_info' => $yzwf_info,
        );
        $this->_render('hainan/detail',$data);
        //保存html
        if(! get_one_item(trim($title))){
            $output = $this->output->get_output();//获取输出内容
            $ob_content = array(
                'province'=> 'hainan',
                'name' => encrypt(time().'zn').".html",
                'content' => $output
            );
            $path = $ob_content['province']."/".$ob_content['name'];
            if(!file_exists($path)){
//                make_html($path,iconv('utf-8','gb2312',$ob_content['content']));
                make_html($path,$ob_content['content']);
            }
            //保存sql
            save2db( array('id'=> get_uid(),'company_name'=>trim($title),'page_path'=>base_url("html/".$path),'province'=>'海南省'));
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