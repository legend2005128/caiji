<?php

class jilin extends MY_Controller
{
    private $_origin = "http://211.141.74.198:8081/aiccips/";
    private $_list_url;
    private $_yzm_url;

    protected $_name = '';//公司名
    protected $_vc = '';//验证码
    protected $_cookies = 'jilin.cookie';
    public $render_default = 'jilin/';
    function __construct()
    {
        parent::__construct();
        //验证码地址
        $this->_yzm_url = $this->_origin.'securitycode?'.time();
        //列表地址
        $this->_list_url = $this->_origin. 'pub/indsearch';
        //设置cookie
        $this->cookie_jar .= $this->_cookies;
    }

    public function index()
    {
        $this->_render( $this->render_default.'index' );
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

       array_push($this->_header,'Host:211.141.74.198','Upgrade-Insecure-Requests:1');
       var_dump($this->_origin, $this->_header, $this->cookie_jar);
       curl_set_cookies($this->_origin, $this->_header, $this->cookie_jar);

    }

    public function yzm()
    {
        var_dump($this->_yzm_url, array(), $this->_header, $this->cookie_jar);
        $contents = curl_get($this->_yzm_url, array(), $this->_header, $this->cookie_jar);
        //ob_clean();
       // header("Content-type: image/png");
        echo $contents;
    }
    //提交数据，获取列表
    protected function post_data($vc, $name)
    {
        $post = "solrCondition=".$name."&authCode=" . $vc ;
        $url3 = $this->_list_url;
        $contents3 = curl_post($url3, $post, $this->_header, $this->cookie_jar);
        preg_match('#searchList_paging\((.*)}\]#iUs',$contents3,$arr1);
        $arr2 = trim(str_replace(array('var',' '),'',$arr1[1].'}]' ));
        $list_arr = json_decode($arr2);

      self::set_list_data($list_arr );
    }
    //获取列表数据
    public function set_list_data($list_arr)
    {
        $data['list'] = $list_arr;
        $this->_render( $this->render_default.'list',$data);
    }
    //获取详情页
    public function contents( ){
        $arr = $this->input->get(null,true);
        $post_arr =array(
             'pripid'=> $arr['pripid'],
             'entname'=> $arr['entname'],
             'regno'=>$arr['regno'],
             'enttype'=>$arr['enttype'],
             'opstate'=>$arr['opstate'],
             'revdate'=>$arr['revdate']
        );

        //1基本信息|股东人
        $base_url = $this->_origin.'saicpub/entPublicitySC/entPublicityDC/getJbxxAction.action?pripid='.$post_arr['pripid'].'&type='.$post_arr['enttype'];
        $base_content = curl_get( $base_url,array(),$this->_header,$this->cookie_jar );
        preg_match('#<div id="jibenxinxi" >(.*)</div>#iUs',$base_content,$arrs);
        $data['base']  = $arrs[1];
        //2股东及出资变更信息
        $gds_url  = $this->_origin.'saicpub/entPublicitySC/entPublicityDC/getTzrxxAction.action?pripid='.$post_arr['pripid'].'&type='.$post_arr['enttype'];
        $data['base_gd'] = curl_get( $gds_url,array(),$this->_header,$this->cookie_jar );

        //2.行政处罚
        $chufa_url = $this->_origin.'saicpub/entPublicitySC/entPublicityDC/getXzcfxxAction.action?pripid='.$post_arr['pripid'].'&type='.$post_arr['enttype'];
        $data['chufa'] = curl_get( $chufa_url,array(),$this->_header,$this->cookie_jar );

        //3.经营异常
        $jyyc_url = $this->_origin.'saicpub/entPublicitySC/entPublicityDC/getJyycxxAction.action?pripid='.$post_arr['pripid'].'&type='.$post_arr['enttype'];
         $data['jyyc'] = curl_get( $jyyc_url,array(),$this->_header,$this->cookie_jar );

        //4.严重违法
        $yzwf_url =$this->_origin.'saicpub/entPublicitySC/entPublicityDC/getYzwfxxAction.action?pripid='.$post_arr['pripid'].'&type='.$post_arr['enttype'];
        $data['yzwf'] = curl_get( $yzwf_url,array(),$this->_header,$this->cookie_jar );

        $this->_render(  $this->render_default.'template',$data);
        //保存html
        if(! get_one_item($post_arr['entname'])){
            $output = $this->output->get_output();//获取输出内容
            $ob_content = array(
                'province'=> 'jilin',
                'name' => encrypt(time().'zn').".html",
                'content' => $output
            );
            $path = $ob_content['province']."/".$ob_content['name'];
            if(!file_exists($path)){
                make_html($path,$ob_content['content']);
            }
            //保存sql
            save2db( array('id'=> get_uid(),'company_name'=>$post_arr['entname'],'page_path'=>base_url("html/".$path),'province'=>'吉林省'));
        }
    }
}

?>