<?php
/**  陕西
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/22
 * Time: 15:30
 */
require_once (APPPATH.'/libraries/phpQuery/phpQuery.php');
class Shanxi extends MY_Controller
{
    private $_info_url = 'http://xygs.snaic.gov.cn/ztxy.do';
    private $_getdata_url = '';
    private $_verify_url = '';
//    private $_checkcode_url = 'http://gsxt.ngsh.gov.cn/ECPS/qyxxgsAction_checkVerificationCode.action';
    protected $_cookies = 'shanxi.cookie';
    function __construct()
    {
        parent::__construct();
        $this->cookie_jar .= $this->_cookies;
        $this->_getdata_url = 'http://xygs.snaic.gov.cn/ztxy.do?method=list&djjg=&random=1474527820599&yourIp='.getIP();
        $this->_verify_url = 'http://xygs.snaic.gov.cn/ztxy.do?method=createYzm&dt=1474527953221&random='.time();
    }

    /**  首页
     * User: Tmc
     * Date: 2016/9/21
     * Time: 14:30
     */
    public function index()
    {

        $this->_render('shanxi/index');
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
        $code = iconv('utf-8','GBK',$code);//转码
        $name = iconv('utf-8','GBK',$name);//转码
        $post_data = 'currentPageNo=1&yzm='.$code.'&cxym=cxlist&maent.entname='.$name;
        $content = curl_post($this->_getdata_url, $post_data, $this->_header, $this->cookie_jar);
        $content = iconv('GBK','utf-8',$content);
        $content = phpquery_char('utf-8',$content);
        phpQuery::newDocumentHTML($content);
        $list = pq('.center-1')->find('div')->find('ul');
        $date = array();
        foreach ($list as $key => $val) {
            if($key <=1 ){
                continue;
            }
            $info_url = pq($val)->find('a')->attr('onclick');
            $title = pq($val)->find('a')->text();
            $date[] = array(
                'info_url' => $info_url,
                'title' => $title,
            );
        }
        $data['list'] = $date;
        $this->_render('shanxi/serchlist',$data);
    }

    /**  详细页
     * User: Tmc
     * Date: 2016/9/22
     * Time: 15:30
     */
    public function detail()
    {
        $params = $this->input->get(null,true);
        if(empty($params['info_url'])){
            exit("<a href='" . site_url('collection/shanxi') . "'>页面错误，请返回!</a>");
        }
        preg_match('#openView\(\'(.*)\',\'(.*)\',\'(.*)\'\)#',$params['info_url'],$preg_arr);

        //基本信息
        $base_post = 'method=qyInfo&djjg=&maent.pripid='.$preg_arr[1].'&maent.entbigtype='.$preg_arr[2].'&random='.time();
        $content_info = curl_post($this->_info_url,$base_post,$this->_header, $this->cookie_jar);
        $content_info = iconv('GBK','utf-8',$content_info);
        $content_info = phpquery_char('utf-8',$content_info);
        phpQuery::newDocumentHTML($content_info);
        $base_info = pq('#jibenxinxi')->find('table:eq(0)')->html();
        $bgxx_info = pq('#biangeng')->find('table')->html();
        //公司名
        $title = $params['title'];
        $title_info = pq('#details')->find('h2:eq(0)')->text();
        //行政处罚
        $xzcf_post = 'method=cfInfo&maent.pripid='.$preg_arr[1].'&czmk=czmk3&random='.time();
        $xzcf_con = curl_post($this->_info_url,$xzcf_post,$this->_header, $this->cookie_jar);
        $xzcf_con = iconv('GBK','utf-8',$xzcf_con);
        $xzcf_con = phpquery_char('utf-8',$xzcf_con);
        phpQuery::newDocumentHTML($xzcf_con);
        $xzcf_info = pq('#gsgsxx_xzcf')->find('table')->html();
        //经营异常
        $jyyc_post = 'method=jyycInfo&maent.pripid='.$preg_arr[1].'&czmk=czmk6&random='.time();
        $jyyc_con = curl_post($this->_info_url,$jyyc_post,$this->_header, $this->cookie_jar);
        $jyyc_con = iconv('GBK','utf-8',$jyyc_con);
        $jyyc_con = phpquery_char('utf-8',$jyyc_con);
        phpQuery::newDocumentHTML($jyyc_con);
        $jyyc_info = pq('#yichangminglu')->find('table')->html();
        //严重违法
        $yzwf_post = 'method=yzwfInfo&maent.pripid='.$preg_arr[1].'&czmk=czmk14&random='.time();
        $yzwf_con = curl_post($this->_info_url,$yzwf_post,$this->_header, $this->cookie_jar);
        $yzwf_con = iconv('GBK','utf-8',$yzwf_con);
        $yzwf_con = phpquery_char('utf-8',$yzwf_con);
        phpQuery::newDocumentHTML($yzwf_con);
        $yzwf_info = pq('#yanzhongweifa')->find('table')->html();

        $data = array(
            'title_info' => trim($title_info),
            'base_info' => $base_info,
            'bgxx_info' => $bgxx_info,
            'xzcf_info' => $xzcf_info,
            'jyyc_info' => $jyyc_info,
            'yzwf_info' => $yzwf_info,
        );
        $this->_render('sichuan/detail',$data);

        //保存html
        if(! get_one_item(trim($title))){
            $output = $this->output->get_output();//获取输出内容
            $ob_content = array(
                'province'=> 'shanxi',
                'name' => encrypt(time().'zn').".html",
                'content' => $output
            );
            $path = $ob_content['province']."/".$ob_content['name'];
            if(!file_exists($path)){
//                make_html($path,iconv('utf-8','gb2312',$ob_content['content']));
                make_html($path,$ob_content['content']);
            }
            //保存sql
            save2db( array('id'=> get_uid(),'company_name'=>trim($title),'page_path'=>base_url("html/".$path),'province'=>'陕西省'));
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