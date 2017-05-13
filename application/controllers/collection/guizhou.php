<?php
/**  贵州
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/19
 * Time: 10:30
 */

class Guizhou extends MY_controller {

    private $_getdata_url = 'http://gsxt.gzgs.gov.cn/list.jsp';
    private $_verify_url = '';
    private $_checkcode_url = "http://gsxt.gzgs.gov.cn/query!searchSczt.shtml";
    protected $_name = '';//公司名
    protected $_code = '';//验证码
    protected $_cookies = 'guizhou.cookie';
    function __construct()
    {
        parent::__construct();
        $this->cookie_jar .= $this->_cookies;
        $this->_verify_url = 'http://gsxt.gzgs.gov.cn/search!generateCode.shtml?validTag=searchImageCode&'.(time()*1000);
    }

    /**  首页
     * User: Tmc
     * Date: 2016/9/19
     * Time: 10:30
     */
    public function index(){
        $this->_render('guizhou/index');
    }

    public function verify(){
        //生成cookie保存文件
        self::get_cookie();
        //根据cookie获取验证码
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

    /**  列表页
     * User: Tmc
     * Date: 2016/9/19
     * Time: 12:30
     */
    public function serchlist(){
        //post数据
        $code = $this->input->post('code');
        $name = $this->input->post('name');
        $postdata = 'q='.$name.'&validCode='.$code;
        $contents = curl_post( $this->_checkcode_url,$postdata,$this->_header,$this->cookie_jar );
        $date = toArray($contents);
        $data['list'] = $date;
        $this->_render('guizhou/serchlist',$data);
    }

    /**  详细页
     * User: Tmc
     * Date: 2016/9/19
     * Time: 13:30
     */
    public function detail(){
        $params = $this->input->get(null,true);
        if($params['ztlx'] == 2){ //1=企业；2=个人
            $base_url = 'http://gsxt.gzgs.gov.cn/2016/query!searchData.shtml';
            $data_url = 'http://gsxt.gzgs.gov.cn/2016/gtgsh/query!searchData.shtml';
            $wfsx_url = 'http://gsxt.gzgs.gov.cn/2016/gtgsh/query!searchOldData.shtml';
            //照面信息
            $post_zm = 'c=1&t=1&nbxh='.$params['info_url'];
            //变更信息
            $post_bg = 'c=1&t=2&nbxh='.$params['info_url'];
        } else {
            $base_url = 'http://gsxt.gzgs.gov.cn/2016/query!searchData.shtml';
            $data_url = 'http://gsxt.gzgs.gov.cn/2016/nzqyfr/query!searchData.shtml';
            $wfsx_url = 'http://gsxt.gzgs.gov.cn/2016/nzqyfr/query!searchOldData.shtml';
            //照面信息
            $post_zm = 'c=0&t=5&nbxh='.$params['info_url'];
            //变更信息
            $post_bg = 'c=0&t=8&nbxh='.$params['info_url'];
        }
        //基本信息

        $post_base = 'c=0&t=57&nbxh='.$params['info_url'];
        $data_base = curl_post( $base_url,$post_base,$this->_header,$this->cookie_jar );

        //照面信息
        $data_zm = curl_post( $data_url,$post_zm,$this->_header,$this->cookie_jar );
        //变更信息
        $data_bg = curl_post( $data_url,$post_bg,$this->_header,$this->cookie_jar );
        //行政处罚信息
        $post_xzcf = 'c=0&t=33&nbxh='.$params['info_url'];
        $data_xzcf = curl_post( $data_url,$post_xzcf,$this->_header,$this->cookie_jar );
        //经营异常信息
        $post_jyyc = 'c=0&t=35&nbxh='.$params['info_url'];
        $data_jyyc = curl_post( $data_url,$post_jyyc,$this->_header,$this->cookie_jar );
        //严重违法失信信息
        $post_wfsx = 'c=0&t=60&nbxh='.$params['info_url'];
        $data_wfsx = curl_post( $wfsx_url,$post_wfsx,$this->_header,$this->cookie_jar );
        $data = array(
            'data_base' => toArray($data_base),
            'data_zm' => toArray($data_zm),
            'data_bg' => toArray($data_bg),
            'data_xzcf' => toArray($data_xzcf),
            'data_jyyc' => toArray($data_jyyc),
            'data_wfsx' => toArray($data_wfsx),
        );
        if($params['ztlx'] == 2) { //1=企业；2=个人
            $this->_render('guizhou/detail_geren',$data);
        } else {
            $this->_render('guizhou/detail_qiye',$data);
        }

        //保存html
        if(! get_one_item($data['data_base']['data'][0]['qymc'])){
            $output = $this->output->get_output();//获取输出内容
            $ob_content = array(
                'province'=> 'guizhou',
                'name' => encrypt(time().'zn').".html",
                'content' => $output
            );
            $path = $ob_content['province']."/".$ob_content['name'];
            if(!file_exists($path)){
                make_html($path,$ob_content['content']);
            }
            //保存sql
            save2db( array('id'=> get_uid(),'company_name'=>$data['data_base']['data'][0]['qymc'],'page_path'=>base_url("html/".$path),'province'=>'贵州省'));
        }

    }

}