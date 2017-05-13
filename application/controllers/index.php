<?php
/**
 * 首页
 */
class Index extends MY_Controller
{
    protected $error_msg ;
    private $_page = 1;
    private $_rp = 10;
    private $_offset;
    private $_base_url;
    private $_company_name;
            
    function __construct()
    {
        parent::__construct();
        $this->_base_url = site_url('');
    }
      /**
     * init paging param 
     */
    private function _init_param()
    {
        if ($this->input->get('per_page'))
            $this->_page = $this->input->get('per_page');
        if( $this->_page > 1 ){
            $this->_offset = ($this->_page-1)*$this->_rp;
        }else{
            $this->_offset = 0;
        }
          if ($this->input->get('company_name'))
            $this->_company_name = $this->input->get('company_name');
    }
    public function index()
    {
        $this->config->load('province');
        $data['list'] =   $this->config->item('list');
        $this->load->view('index',$data);
    }
    /**
     * 新增公司查询接口
     * 提交接口 
     * param :session_token, 关键词
     */
    public function post_query( $enterp_token, $post_name ){
        //1.1检测操作
         if( !$enterp_token )
        {
            return json_encode( array('status'=>1001,'msg'=>'token required!') );
            $this->error_msg = 'Illegal request!';
            self::re_callback($this->error_msg,$this->_base_url);
        }
        $tl = strlen(trim($enterp_token));
        if( $tl < 1 )
        {
            $this->error_msg = 'Error params!';
            self::re_callback($this->error_msg,$this->_base_url);
        }
        $this->session->set_userdata('enterp_token',$enterp_token);
     
        //2.1 不是本次会话，再根据公司名检索数据库
        $post_name = urldecode(trim($post_name));
        if( !$post_name  )
        {
             $this->error_msg = '请输入公司名!';
             self::re_callback($this->error_msg,$this->_base_url);
         }
        if( strlen($post_name)<3 || $post_name =='有限公司' || $post_name == '有限责任公司'){
             $this->error_msg = '请输入准确的条件!';
             self::re_callback($this->error_msg,$this->_base_url );
        }
         //查询数据表
        $arr = get_one_item($post_name);
        if( !$arr )
        {
            redirect('');
        }
        $this->set_db_list($post_name );
    }
    /*
     * 
     * 获取企业列表
     * 
     */
    public function set_db_list($company = false)
    {
           $this->_init_param();
           if($this->_company_name){
               $company = $this->_company_name;
           }
           $search_param = array('company_name'=>$company);
           $counts = get_company_list($order_by = 'province desc',$this->_offset, $this->_rp, $search_param,1);//总条数
           $lists = get_company_list($order_by = 'province desc',$this->_offset, $this->_rp, $search_param,0);//总条数
           $this->load->library('pagination');
           $config['base_url'] = '/index/set_db_list?company_name='.$company;
           $config['use_page_numbers'] = TRUE;
           $config['total_rows'] = $counts>50?50:$counts;
           $config['page_query_string'] = TRUE;
           $config['per_page'] = $this->_rp;
           $this->pagination->initialize($config);
           $page = $this->pagination->create_links();
           $data['lists'] = $lists;
           $data['page'] =$page;
           $data['kw'] = $company;
           $data['backurl'] =  $this->_base_url;
           $this->_render('list',$data,1);
    }
   
    /**
     * 根据token获取最新的查询的企业详情
     * param $token
     * type  string(32)
     * 1.0java接口根据token获取最新的查询公司详情
     * 
     */
    public function get_detail_token( $token = '' )
    {
        // $this->ck_ajax();
        if( !$token )
        {
            echo json_encode( array('status'=>1001,'msg'=>'token required!') );
            exit;
        }
        $tl = strlen(trim($token));
        if( $tl < 1 )
        {
            echo json_encode( array('status'=>1002,'msg'=>'token error!') );
             exit;
        }
        $arr = get_last_by_token($token);
        if(!$arr){
             echo json_encode( array('status'=>1003,'msg'=>'no  data!') ); exit;
        }
        $con = file_get_contents($arr['page_path']);
         $con = compress_html($con);
         //江苏
         preg_match('#<div aria-labelledby="home-tab"id="home"(.*)>(.*)</div>#iUs',$con,$arr1);
         preg_match_all('#<table(.*)>(.*)</table>#iUs',$arr1[1],$arr2);
         $base_arr = str_replace('<br/>', '', $arr1[2]);
         preg_match('#<div aria-labelledby="profile-tab"id="profile"(.*)>(.*)</div>#iUs',$con,$brr1);
         preg_match_all('#<table(.*)>(.*)<\/table>#iUs',$brr1[1],$brr2);
         $other_arr = $brr1[2];//$brr2[2] tr
         if($other_arr && $base_arr){
              $data = array(
                  'status' => 1000,
                  'base'=>$base_arr,
                  'other'=>$other_arr,
                  'c_id' => $arr['eid'],
                  'c_name'=> $arr['company_name']
                 );
              
            echo json_encode($data);
            exit;
          }else{
                $data = array(
                 'status' => 1004,
                  'msg' => 'data format error!'
                 );
                 echo json_encode($data);
                 exit;
          }
    }
    /**
     * 编辑页面获取信息接口
     * param uid
     * type  string(32)
     * 2.0java接口根据公司uid查询公司详情
     * 
     */
    public function get_detail_uid( $uid = '' )
    {
       // $this->ck_ajax();
        if( !$uid )
        {
            echo json_encode( array('status'=>1001,'msg'=>'Param required!') );
            exit;
        }
        $tl = strlen(trim($uid));
        if( $tl < 1 )
        {
            echo json_encode( array('status'=>1002,'msg'=>'Params error!') );
            exit;
        }
        $arr = get_detail_by_uid($uid);
        if(!$arr){
             echo json_encode( array('status'=>1003,'msg'=>'No  data!') );
             exit;
        }
       
        $con = file_get_contents($arr['page_path']);
        //$con =iconv('gb2312', 'utf-8', $con);
         $con = compress_html($con);
         preg_match('#<div aria-labelledby="home-tab"id="home"(.*)>(.*)</div>#iUs',$con,$arr1);
         $base_arr = $arr1[2];//$arr2[2] tr
         
          preg_match('#<div aria-labelledby="profile-tab"id="profile"(.*)>(.*)</div>#iUs',$con,$brr1);
         preg_match_all('#<table(.*)>(.*)<\/table>#iUs',$brr1[1],$brr2);
         $other_arr = $brr1[2];//$brr2[2] tr
         if($other_arr && $base_arr){
              $data = array(
                  'status' => 1000,
                  'base'=> $base_arr,
                  'other'=>$other_arr,
                   'c_id' => $arr['eid'],
                  'c_name'=> $arr['company_name']
                 );
            echo json_encode($data);
             exit;
          }
          $data = array(
                 'status' => 1004,
                  'msg' => 'data format error!'
                 );
        echo json_encode($data);
        exit;
    }
    /***
     * 数据库查询的详情页
     * 
     * 
     */
    public function show_detail(  ){
        $id = $this->input->get('id');
        if($id){
            $row = get_detail_by_uid($id);
            $url = $row['page_path'];
        $data = array(
            'evaluate_id' => $row['id'],
            'session_token' => $this->enterp_token
        );
        
        if($row['page_path']){
                if( save_related2db( $data )){
                     header("location:{$row['page_path']}");  
                }else{
                     redirect('');
                }
        }
        }
    }
    
    private function ck_ajax(){
       if(! $this->input->is_ajax_request()){
            $data = array(
                 'status' => 1005,
                  'msg' => 'Illegal request!'
                 );
        exit( json_encode($data));
       }
    }
}
?>