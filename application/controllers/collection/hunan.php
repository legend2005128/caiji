<?php
/**  湖南
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/20
 * Time: 14:30
 */
require_once (APPPATH.'/libraries/phpQuery/phpQuery.php');
class Hunan extends MY_Controller
{
    private $_base_url = 'http://gsxt.hnaic.gov.cn/notice/';
    private $_getdata_url = 'http://gsxt.hnaic.gov.cn/notice/search/ent_info_list';
    private $_verify_url = '';
    private $_checkcode_url = "http://gsxt.hnaic.gov.cn/notice/security/verify_captcha";
    protected $_cookies = 'hunan.cookie';
    function __construct()
    {
        parent::__construct();
        $this->cookie_jar .= $this->_cookies;
        $this->token = FCPATH.'cookie_f/henan.token';
        $this->_verify_url = 'http://gsxt.hnaic.gov.cn/notice/captcha?preset=&ra='.time();
    }

    /**  首页
     * User: Tmc
     * Date: 2016/9/21
     * Time: 10:30
     */
    public function index()
    {
        //获得token
        self::get_cookie();
        $con = curl_get($this->_base_url,array(),$this->_header, $this->cookie_jar);
        $content = phpquery_char('utf-8',$con);
        phpQuery::newDocumentHTML($content);
        $token = pq("input[name='session.token']")->attr('value');
        $data = array(
            'token' => $token,
        );
        $this->_render('hunan/index',$data);
    }

    /**  列表页
     * User: Tmc
     * Date: 2016/9/21
     * Time: 12:30
     */
    public function serchlist(){
        //post数据
        $token = $this->input->post('token');
        $code = $this->input->post('code');
        $name = $this->input->post('name');
        $post_ck_code = 'captcha='.$code.'&session.token='.$token;
        $ck_code = curl_post($this->_checkcode_url, $post_ck_code, $this->_header, $this->cookie_jar);
        //走验证码
        if( $ck_code !== '1' ){
            exit("<a href='" . site_url('collection/hunan') . "'>验证码不正确,请返回!</a>");
        }
        $post_data = 'searchType=1&captcha='.$code.'&session.token='.$token.'&condition.keyword='.$name;
        $content = curl_post($this->_getdata_url, $post_data, $this->_header, $this->cookie_jar);
        $content = phpquery_char('utf-8',$content);
        phpQuery::newDocumentHTML($content);
        $list = pq('.list-info')->find('.list-item');
        $date = array();
        if(!empty($list)){
            foreach ($list as $key => $val) {
                $info_url = pq($val)->find('a')->attr('href');
                $title = pq($val)->find('a')->text();
                $date[] = array(
                    'info_url' => $info_url,
                    'title' => $title,
                );
            }
        }
        if(empty($date)){
            exit("<a href='" . site_url('collection/hubei') . "'>验证码不正确,请返回!</a>");
        } else {
            $data['list'] = $date;
            $this->_render('hunan/serchlist',$data);
        }
    }

    public function detail()
    {
        $params = $this->input->get(null, true);
        $content_info = curl_get($params['info_url'],array(),$this->_header, $this->cookie_jar);
        $content = phpquery_char('utf-8',$content_info);
        phpQuery::newDocumentHTML($content);
        $title_info = pq('.title-bar')->find('li:eq(0)')->text();
        //基本信息
        $base_info = pq('.cont-r-b')->attr('rel', 'layout-01_01')->find('table:eq(4)')->html();
//        $isset = pq('.cont-r-b')->find('div:eq(12)')->find('table')->html();
//        if(!empty($isset)){
//            $base_info = pq('.cont-r-b')->find('div:eq(2)')->find('table')->html();
//        } else {
//            $base_info = pq('.cont-r-b')->find('div:eq(0)')->find('table')->html();
//        }
        //变更信息
        $bgxx_info = pq('#alterTable')->html();
        //行政处罚
        $xzcf_info = pq('#punishTable')->html();
        //经营异常
        $jyyc_info = pq('#exceptTable')->html();
        //严重处罚
        $yzwf_info = pq('#blackTable')->html();
        $data = array(
            'title_info' => $title_info,
            'base_info' => $base_info,
            'bgxx_info' => $bgxx_info,
            'xzcf_info' => $xzcf_info,
            'jyyc_info' => $jyyc_info,
            'yzwf_info' => $yzwf_info,
        );
        $this->_render('hunan/detail',$data);
        //保存html
        if(! get_one_item(trim($title_info))){
            $output = $this->output->get_output();//获取输出内容
            $ob_content = array(
                'province'=> 'hunan',
                'name' => encrypt(time().'zn').".html",
                'content' => $output
            );
            $path = $ob_content['province']."/".$ob_content['name'];
            if(!file_exists($path)){
//                make_html($path,iconv('utf-8','gb2312',$ob_content['content']));
                make_html($path,$ob_content['content']);
            }
            //保存sql
            save2db( array('id'=> get_uid(),'company_name'=>trim($title_info),'page_path'=>base_url("html/".$path),'province'=>'湖南省'));
        }
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