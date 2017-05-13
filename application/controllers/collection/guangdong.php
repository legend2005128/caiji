<?php
/**  广东
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/13
 * Time: 11:20
 */

require APPPATH.'/libraries/phpQuery/phpQuery.php';
class Guangdong extends MY_controller {

    private $_getdata_url = "http://gsxt.gdgs.gov.cn/aiccips/CheckEntContext/showInfo.html";
    private $_verify_url = "http://gsxt.gdgs.gov.cn/aiccips/verify.html?random=0.30732033412443116";
    private $_checkcode_url = "http://gsxt.gdgs.gov.cn/aiccips/CheckEntContext/checkCode.html";
    protected $_name = '';//公司名
    protected $_vc = '';//验证码
    protected $_cookies = 'guangdong.cookie';
    function __construct()
    {
//        require_once ('./ThinkPHP/Extend/Library/phpQuery/phpQuery.php');
        parent::__construct();
        $this->cookie_jar .= $this->_cookies;
    }

    public function index(){
        $this->_render('guangdong/index');
    }

    public function vc(){
        //获取cookie
        self::get_cookie();
        //获取验证码
        self::yzm();
    }

    public function get_cookie(){
        curl_set_cookies( $this->_getdata_url,$this->_header,$this->cookie_jar );
    }
    public function yzm(){
        $contents = curl_get( $this->_verify_url,array(),$this->_header,$this->cookie_jar );
        header("Content-type:image/jpg");
        ob_clean();
        echo $contents;
    }

    public function serchlist(){
        //post数据
        $vc = $this->input->post('vc');
        $name = $this->input->post('name');

        self::post_data($vc,$name);
    }

    protected function post_data($vc,$name){
        $post = "code=".$vc."&textfield=".$name;
        $this->_name = $name;
        $this->_vc = $vc;
        $contents = curl_post( $this->_checkcode_url,$post,$this->_header,$this->cookie_jar );
        $arr = json_decode($contents);

        $post2 = "textfield=".$arr->textfield."&code=".$vc;
        $contents2 = curl_post( $this->_getdata_url,$post2,$this->_header,$this->cookie_jar );

        $contents2 = phpquery_char('utf-8',$contents2);
        phpQuery::newDocumentHTML($contents2);
        $list = pq('.center-1')->find('.list');

        foreach ($list as $key => $val) {
            $info_url = pq($val)->find('a')->attr('href');
            $title = pq($val)->find('a')->text();
            $data[] = array(
                'info_url' => $info_url,
                'title' => $title,
            );
        }
        $data['list'] = $data;
        $this->_render('guangdong/serchlist',$data);
    }

    public function detail(){
        $base_url = 'http://gsxt.gdgs.gov.cn/aiccips/GSpublicity/GSpublicityList.html';
        $info_url = str_replace('info_url=','',$_SERVER["QUERY_STRING"]);//获取url参数
        $info_url = get_all_url($info_url,$base_url);//获取整个url
        $content = curl_get( $info_url,array(),$this->_header,$this->cookie_jar );

        phpQuery::newDocumentHTML($content);
        $aiccipsUrl = pq('#aiccipsUrl')->attr('value');
        $entNo = pq('#entNo')->attr('value');
        $entType = pq('#entType')->attr('value');
        $regOrg = pq('#regOrg')->attr('value');

        $company_name =  pq('h2')->text();
        $base_info =  pq('#jibenxinxi')->find('table')->html();
        $company =  pq('#jibenxinxi')->find('table')->find('tr:eq(2)')->find('td:eq(1)')->text();

        //参数
        $parame = 'entNo='.$entNo.'&entType='.$entType.'&regOrg='.$regOrg;
        $url_arr = array(
            'xzcf' => 'http://gsxt.gdgs.gov.cn/aiccips/GSpublicity/GSpublicityList.html?service=cipPenaltyInfo',
            'jyyc' => 'http://gsxt.gdgs.gov.cn/aiccips/GSpublicity/GSpublicityList.html?service=cipUnuDirInfo',
            'yzwfsx' => 'http://gsxt.gdgs.gov.cn/aiccips/GSpublicity/GSpublicityList.html?service=cipBlackInfo',
        );

        //行政处罚信息
        $content_xzcf = curl_post( $url_arr['xzcf'],$parame,$this->_header,$this->cookie_jar );
        phpQuery::newDocumentHTML($content_xzcf);
        $xzcf_info =  pq('#xingzhengchufa')->find('table')->html();

        //经营异常信息
        $content_jyyc = curl_post( $url_arr['jyyc'],$parame,$this->_header,$this->cookie_jar );
        phpQuery::newDocumentHTML($content_jyyc);
        $jyyc_info =  pq('#yichangminglu')->find('table')->html();

        //严重违法失信信息
        $content_yzwfsx = curl_post( $url_arr['yzwfsx'],$parame,$this->_header,$this->cookie_jar );
        phpQuery::newDocumentHTML($content_yzwfsx);
        $yzwf_info =  pq('#heimingdan')->find('table')->html();

        $data = array(
            'company_name' => trim($company_name),
            'base_info' => $base_info,
            'xzcf_info' => $xzcf_info,
            'jyyc_info' => $jyyc_info,
            'yzwf_info' => $yzwf_info,
        );
        $this->_render('guangdong/detail',$data);


        //保存html
        if(! get_one_item(trim($company))){
            $output = $this->output->get_output();//获取输出内容
            $ob_content = array(
                'province'=> 'guangdong',
                'name' => encrypt(time().'zn').".html",
                'content' => $output
            );
            $path = $ob_content['province']."/".$ob_content['name'];
            if(!file_exists($path)){
//                make_html($path,iconv('utf-8','gb2312',$ob_content['content']));
                make_html($path,$ob_content['content']);
            }
            //保存sql
            save2db( array('id'=> get_uid(),'company_name'=>trim($company),'page_path'=>base_url("html/".$path),'province'=>'广东省'));
        }
    }

}