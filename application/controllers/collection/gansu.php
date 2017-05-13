<?php
/**  甘肃
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/20
 * Time: 14:30
 */
require_once (APPPATH.'/libraries/phpQuery/phpQuery.php');
class Gansu extends MY_Controller
{
    private $_info_url = 'http://xygs.gsaic.gov.cn/gsxygs/pub!view.do';
    private $_getdata_url = 'http://xygs.gsaic.gov.cn/gsxygs/pub!list.do';
    private $_verify_url = '';
    protected $_cookies = 'gansu.cookie';
    function __construct()
    {
        parent::__construct();
        $this->cookie_jar .= $this->_cookies;
        $this->_verify_url = 'http://xygs.gsaic.gov.cn/gsxygs/securitycode.jpg?v='.time();
    }

    /**  首页
     * User: Tmc
     * Date: 2016/9/20
     * Time: 14:30
     */
    public function index()
    {
        $this->_render('gansu/index');
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

        $post_data = 'queryVal='.$name.'&authCodeQuery='.$code;
        $content = curl_post($this->_getdata_url, $post_data, $this->_header, $this->cookie_jar);
        $content = phpquery_char('utf-8',$content);
        phpQuery::newDocumentHTML($content);
        $list = pq('.center-1')->find('.list');
        $date = array();
        foreach ($list as $key => $val) {
            $id = pq($val)->find('a')->attr('id');
            $entcate = pq($val)->find('a')->attr('_entcate');
            $title = pq($val)->find('a')->text();
            $date[] = array(
                'id' => $id,
                'entcate' => $entcate,
                'title' => $title,
            );
        }
        if(empty($date)){
            exit("<a href='" . site_url('collection/gansu') . "'>请您输入更精确的查询条件(公司全名)!</a>");
        } else {
            $data['list'] = $date;
            $this->_render('gansu/serchlist',$data);
        }
    }

    public function detail()
    {
        $params = $this->input->get(null, true);
        $post_base = 'regno='.$params['id'].'&entcate='.$params['entcate'];
        $content = curl_post($this->_info_url, $post_base, $this->_header, $this->cookie_jar);
        $content_info = phpquery_char('utf-8',$content);
        phpQuery::newDocumentHTML($content_info);
        $title = $params['title'];
        $title_info = pq('h2')->text();
        $base_info = pq('#jibenxinxi')->find('table:eq(0)')->html();
        $bgxx_info = pq('#changTab')->html();
        $xzcf_info = pq('#xingzhengchufa')->find('table')->html();
        $jyyc_info = pq('#jingyingyichangminglu')->find('table:eq(0)')->html();
        $yzwf_info = pq('#yanzhongweifaqiye')->find('table')->html();
        $data = array(
            'title_info' => trim($title_info),
            'base_info' => $base_info,
            'bgxx_info' => $bgxx_info,
            'xzcf_info' => $xzcf_info,
            'jyyc_info' => $jyyc_info,
            'yzwf_info' => $yzwf_info,
        );
        $this->_render('gansu/detail',$data);
        //保存html
        if(! get_one_item(trim($title))){
            $output = $this->output->get_output();//获取输出内容
            $ob_content = array(
                'province'=> 'gansu',
                'name' => encrypt(time().'zn').".html",
                'content' => $output
            );
            $path = $ob_content['province']."/".$ob_content['name'];
            if(!file_exists($path)){
                make_html($path,$ob_content['content']);
            }
            //保存sql
            save2db( array('id'=> get_uid(),'company_name'=>trim($title),'page_path'=>base_url("html/".$path),'province'=>'甘肃省'));
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