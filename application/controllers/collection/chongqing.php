<?php
/**  重庆
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/18
 * Time: 11:20
 */

require APPPATH.'/libraries/phpQuery/phpQuery.php';
class Chongqing extends MY_controller {

    private $_getdata_url = "http://gsxt.cqgs.gov.cn/search_research.action";
    private $_verify_url = '';
    protected $_name = '';//公司名
    protected $_code = '';//验证码
    protected $_cookies = 'chongqing.cookie';
    function __construct()
    {
        parent::__construct();
        $this->cookie_jar .= $this->_cookies;
        $this->_verify_url = 'http://gsxt.cqgs.gov.cn/sc.action?width=130&height=40&fs=23&abc='.time();
        array_push($this->_header,'DNT:1','Host:gsxt.cqgs.gov.cn','Referer:http://gsxt.cqgs.gov.cn/search_tojyyc.action','');
    }

    public function index(){
         //获取cookie
        self::get_cookie();
        $this->_render('chongqing/index');
    }

    public function vc(){
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
        $code = $this->input->post('code');
        $name = $this->input->post('name');

        self::post_data($code,$name);
    }

    protected function post_data($code,$name){

        $post = 'stype=belong&key='.$name.'&code='.$code.'&outSearch=';
        $this->_name = $name;
        $this->_code = $code;
        $contents = curl_post( $this->_getdata_url,$post,$this->_header,$this->cookie_jar );
//        phpQuery::newDocument($contents);
    
        $contents = phpquery_char('utf-8',$contents);
        phpQuery::newDocumentHTML($contents);
        $list = pq('#result')->find('.item');

        foreach ($list as $key => $val) {
            $dataid = pq($val)->find('a')->attr('data-id');
            $datatype = pq($val)->find('a')->attr('data-type');
            $dataentid = pq($val)->find('a')->attr('data-entid');
            $title = pq($val)->find('a')->text();
            $date[] = array(
                'info_url' => 'dataid='.$dataid.'&datatype='.$datatype.'&dataentid='.$dataentid.'&name='.$title,
                'title' => $title,
            );
        }
        $data['list'] = $date;
        $this->_render('chongqing/serchlist',$data);

    }

    public function detail(){
        $params = $this->input->get(null,true);
        $post = 'id='.$params['dataid'].'&type='.$params['datatype'].'&name='.$params['name'].'&entId='.$params['dataentid'];
        $Info_url = 'http://gsxt.cqgs.gov.cn/search_ent';
        $content = curl_post( $Info_url,$post,$this->_header,$this->cookie_jar );
        $content = phpquery_char('utf-8',$content);
        phpQuery::newDocumentHTML($content);
        $type = pq('#left')->attr('data-type');
        //type  7:企业；16：个人；3：集体经济
        $Info_ajax = 'http://gsxt.cqgs.gov.cn/search_getEnt.action?_c1474184796950=_c753815&entId='.$params['dataentid'].'&id='.$params['dataid'].'&stype=SAIC&type='.$type;
        $getdata = curl_get( $Info_ajax,array(),$this->_header,$this->cookie_jar );
        $getdata = substr($getdata,6);
        $get_data = json_decode($getdata,true);
        $data = array(
            'info_data' => $get_data,
        );

        if($type == 7){ //企业
            $this->_render('chongqing/detail',$data);
        } elseif($type == 16) {
            $this->_render('chongqing/detail_16',$data);
        } else {
            $this->_render('chongqing/detail_3',$data);
        }

        //保存html
        if(! get_one_item($get_data['base']['entname'])){
            $output = $this->output->get_output();//获取输出内容
            $ob_content = array(
                'province'=> 'chongqing',
                'name' => encrypt(time().'zn').".html",
                'content' => $output
            );
            $path = $ob_content['province']."/".$ob_content['name'];
            if(!file_exists($path)){
//                make_html($path,iconv('utf-8','gb2312',$ob_content['content']));
                make_html($path,$ob_content['content']);
            }
            //保存sql
            save2db( array('id'=> get_uid(),'company_name'=>$get_data['base']['entname'],'page_path'=>base_url("html/".$path),'province'=>'重庆市'));
        }

    }

}