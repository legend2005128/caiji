<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class Jiangsu extends MY_Controller {

    private $_origin = "http://www.jsgsj.gov.cn:58888";
    private $_base_url = "http://www.jsgsj.gov.cn:58888/province/queryResultList.jsp";
    private $_yzm_url = "http://www.jsgsj.gov.cn:58888/province/rand_img.jsp?type=8";
    private $_result_url = "http://www.jsgsj.gov.cn:58888/province/infoQueryServlet.json?queryCinfo=true";
    protected $_name = '';//公司名
    protected $_vc = '';//验证码
    protected $_cookies = 'jiangsu.cookie';
    function __construct()
    {
        parent::__construct();
        $this->cookie_jar .= $this->_cookies;
    }
    public function index(){
         //获取cookie
        self::get_cookie();
        $this->_render('jiangsu/index');
    }
    public function vc(  $od)
    {
        //获取验证码
        self::yzm();
    }
    public function ps(){
        //post数据
        $vc = $this->input->post('vc');
        $name = $this->input->post('name');
        self::post_data($vc,$name);
    }
    public function get_cookie(){
      curl_set_cookies( $this->_base_url,$this->_header,$this->cookie_jar );
    }
    public function yzm(){
        $contents = curl_get( $this->_yzm_url,array(),$this->_header,$this->cookie_jar );
        ob_clean();
        header("Content-type:image/jpg");

        echo $contents;
    }
    protected function post_data($vc,$name){
        $post = "verifyCode=".$vc."&name=".$name;
        $this->_name = $name;
        $this->_vc = $vc;
        $url = $this->_result_url;
        $contents = curl_post( $url,$post,$this->_header,$this->cookie_jar );
        $arr = json_decode($contents);
        $strs = $arr[0]->TIPS?$arr[0]->TIPS:$arr[0]->INFO;
        if($arr[0]->TIPS){
            exit("<a href='" . site_url('jiangsu') . "'>".$arr[0]->TIPS."</a>");
            return;
        }else{
            //$arr[0]->INFO;
            self::set_info_list($strs,$post);
        }
    }
    //获取信息列表
    public function set_info_list( $strs,$post ){
        preg_match_all('#<dt>(.*)<\/dt>#iUs',$strs,$preg_arr_1);
        $temp_arr = $data = array();
        if($preg_arr_1 &&count($preg_arr_1)){
            foreach($preg_arr_1[1] as $p_k=>$p_v){
                preg_match('#(.*)onclick="queryInfor(.*)">(.*)#iUs',$p_v,$preg_arr);
                if($preg_arr && count($preg_arr)){
                    $post_data_str =  str_replace(array("(",")","'"),"",$preg_arr[2]);
                    $temp_arr = explode(',',$post_data_str);
                    $temp_name = strip_tags($p_v);
                    array_push($temp_arr,$temp_name);
                    array_push($temp_arr,$post);
                    $data[] = $temp_arr;
                }
            }
        }
        $data['list_js'] = $data;
        $this->_render('jiangsu/list',$data);
    }

    //处理列表页超链接
    public function do_info_list(){
        $temp_arr = $this->input->get(null,true);
        self::post_data_detail($temp_arr);
    }

    //提交获取详情页信息
    function post_data_detail( $temp_arr ){
        //1.登记信息
        $post_basic = 'id='.$temp_arr['corp_id'].'&org='.$temp_arr['corp_org'].'&seq_id='.$temp_arr['corp_seq_id'].'&specificQuery=basicInfo';
        $post_basic_url = "http://www.jsgsj.gov.cn:58888/ecipplatform/ciServlet.json?ciEnter=true";
        $strs_basic = curl_post( $post_basic_url,$post_basic,$this->_header,$this->cookie_jar );
        $strs_basic= json_decode($strs_basic);
        foreach($strs_basic[0] as $ks=>$vs){
            $str_arr[$ks] = $vs;
        }
       // $datas = json_encode($str_arr);
        $datas = arrayToObject($str_arr);
        $data['base_data'] =  $datas;
      
        
        //1.2股东发起人信息
        $post_gdfqr = 'CORP_ORG='.$temp_arr['corp_org'].'&CORP_ID='.$temp_arr['corp_id'].'&CORP_SEQ_ID='.$temp_arr['corp_seq_id'].'&specificQuery=investmentInfor&showRecordLine=1&pageNo=1
&pageSize=5';
        $post_gdfqr_url = "http://www.jsgsj.gov.cn:58888/ecipplatform/ciServlet.json?ciEnter=true";
        $strs_gdfqr = curl_post( $post_gdfqr_url,$post_gdfqr,$this->_header,$this->cookie_jar );
        $data['gd_data'] = self:: _format_echo($strs_gdfqr,'info');
        //1.3变更信息
        $post_bg = 'corp_org='.$temp_arr['corp_org'].'&corp_id='.$temp_arr['corp_id'].'&corp_seq_id='.$temp_arr['corp_seq_id'].'&specificQuery=commonQuery&showRecordLine=1&pageNo=1
&pageSize=5&propertiesName=biangeng';
        $post_bg_url = "http://www.jsgsj.gov.cn:58888/ecipplatform/commonServlet.json?commonEnter=true";
        $strs_bg = curl_post( $post_bg_url,$post_bg,$this->_header,$this->cookie_jar );
        $data['biangeng_data'] = self:: _format_echo($strs_bg,'info');
        //其他信息
        $post_common_url = 'http://www.jsgsj.gov.cn:58888/ecipplatform/commonServlet.json?commonEnter=true';
        //2.1行政处罚
        $post_xzcf = 'showRecordLine=1&specificQuery=commonQuery&propertiesName=chufa&corp_org='.$temp_arr['corp_org'].'&corp_id='.$temp_arr['corp_id'].'
&corp_seq_id='.$temp_arr['corp_seq_id'].'&pageNo=1&pageSize=5';
        $strs_xzcf =  curl_post( $post_common_url,$post_xzcf,$this->_header,$this->cookie_jar );
        $data['chufa_data'] = self:: _format_echo($strs_xzcf,'info');
        //2.2经营异常
        $post_jyyc = 'showRecordLine=1&specificQuery=commonQuery&propertiesName=abnormalInfor&corp_org='.$temp_arr['corp_org'].'&corp_id='.$temp_arr['corp_id'].'
&corp_seq_id='.$temp_arr['corp_seq_id'].'&pageNo=1&pageSize=5';
        $strs_jyyc =  curl_post( $post_common_url,$post_jyyc,$this->_header,$this->cookie_jar );
        $data['jyyc_data'] = self:: _format_echo($strs_jyyc,'info');
        //2.3严重违法信息
        $post_yzwf = 'showRecordLine=1&specificQuery=commonQuery&propertiesName=yzwfInfor&corp_org='.$temp_arr['corp_org'].'&corp_id='.$temp_arr['corp_id'].'
&corp_seq_id='.$temp_arr['corp_seq_id'].'&pageNo=1&pageSize=5';
        $strs_yzwf =  curl_post( $post_common_url,$post_yzwf,$this->_header,$this->cookie_jar );
        $data['yzwf_data'] = self:: _format_echo($strs_yzwf,'info');
        $this->_render('jiangsu/template',$data);
        //保存html
        if(! get_one_item($temp_arr['company_name'])){
            $output = $this->output->get_output();//获取输出内容
            $ob_content = array(
                'province'=> 'jiangsu',
                'name' => encrypt(time().'zn').".html",
                'content' => $output
            );
            $path = $ob_content['province']."/".$ob_content['name'];
            if(!file_exists($path)){
                make_html($path,$ob_content['content']);
            }
            //保存sql
            save2db( array('id'=> get_uid(),'company_name'=>$temp_arr['company_name'],'page_path'=>base_url("html/".$path),'province'=>'江苏省'));
        }
      //  echo "<br><a href='".site_url('collection/jiangsu')."'>重新搜索</a>";
    }

    private function _format_echo($strs_gdfqr,$key){
        $gdfqr_arr = array();
        $strs_gdfqr= json_decode($strs_gdfqr);
        foreach($strs_gdfqr->items as $ks=>$vs){
            $gdfqr_arr[$ks] = $vs;
        }
       // $gd_arr = json_encode(array($key=>$gdfqr_arr));
        return $gdfqr_arr;
    }
}
?>

