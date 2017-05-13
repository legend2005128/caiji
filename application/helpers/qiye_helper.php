<?php
/**
 * 模拟生成随机动态ip 头
 *
 */
if( !function_exists('fc_header_ips'))
{
    function fc_header_ips() 
    {
        $ip_random = array();
        $ip_add1 = rand(10,90);
        $ip_add2 = rand(10,90);
        $ip_add3 = rand(10,90);
        $ip_add4 = rand(10,230);
        $ip  = $ip_add1.".".$ip_add2.".".$ip_add3.".".$ip_add4;
        $ip_random = array(
            'CLIENT-IP:'.$ip,
            'X-FORWARDED-FOR:'.$ip,
        );
        $ip_client = rand_client();
        array_push($ip_random,$ip_client);
        return $ip_random;
    }
}
/**
 * 获得浏览器标识随机列表
 *
 */
if(!function_exists('rand_client'))
{
    function rand_client(){
        $arr = array(
                        'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.84 Safari/537.36',
                        'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0 FirePHP/0.7.4',
                        'User-Agent:Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0;',
                        'User-Agent:Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 5.1; 360SE)',
                        'User-Agent:Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 5.1; Maxthon 2.0)'
                    );
        $len = count($arr);
        $i = rand(0,($len-1));
        return $arr[$i];
    }
}
/***
 *  模拟post请求
 * param $url string
 * param $post
 *
 */
if(! function_exists('curl_post') ) {
    function curl_post( $url,$post,$header,$cookies ){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
        curl_setopt($ch, CURLOPT_POST, 1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }
}
/***
 *  模拟get请求
 * param $url string
 * param $post
 *
 */
if(! function_exists('curl_get') ) {
    function curl_get( $url,$get = array(),$header,$cookies ){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 2);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }
}
/**
 * 保存cookies
 *
 */
if(! function_exists('curl_set_cookies') ) {
    function curl_set_cookies( $url,$header,$cookies ){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 2);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //  curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1); 
      //  curl_setopt(curl, CURLOPT_MAXREDIRS, 0);
        $content = curl_exec($ch);
        curl_close($ch);
    }
}
/**
 * 加密方法encrypt
 * $str string
 */
if(! function_exists('encrypt') ) {
    function encrypt( $str  ){
        return $str?md5(sha1($str)):false;
    }
}
/**
 * make_html
 * 生成html
 * $path.省份+文件名
 */
if(! function_exists('make_html') ) {
    function make_html( $path ,$data,$mode=""  ){
        if( function_exists('write_file')){
            $path = FCPATH.'html/'.$path;
            $dirs = pathinfo($path);
            if(!is_dir($dirs['dirname'])){
                @mkdir($dirs['dirname']);
            }
            if(!file_exists($path)){
                return  write_file($path,$data);
            }
        }
        return false;
    }
}
/***
 * 查询公司的列表
 */
function get_company_list( $order_by = '', $offset = null, $limit = null, $search_param = array(),$count_flag = false){
    $CI = &get_instance();
    $db_name = 'company_evaluate';
    $CI->db->from( $db_name );
    if($search_param){
     foreach ($search_param as $key => $value)
        {
            switch ($key)
            {
                case 'company_name':
                     $CI->db->like('company_name', $value);
                     break;
                default:
                     $CI->db->where($key,$value);
                    break;
            }
        }
    }
     if($count_flag)
    {
          return $CI->db->get()->num_rows();
    }
   if ($order_by)
   {
       $CI->db->order_by($order_by);
    }
    //limit
   if ($limit && is_numeric($limit))
   {
       $CI->db->limit(intval($limit));
    }
    //offset
    if ($offset && is_numeric($offset))
    {
       $CI->db->offset(intval($offset));
    }     
    return $CI->db->get()->result_array();
    
}
/***
 * 查询是否已经有数据
 *
 */
function get_one_item( $company_name ){
    $CI = &get_instance();
    $db_name = 'company_evaluate';
    if($company_name){
        $row =  $CI->db->like( 'company_name',$company_name)->get($db_name)->row_array(1);
        if($row){
            return true;
        }
        return false;
    }
}
/***
 * 根据company id查询是否已经有数据
 *
 */
function get_detail_by_uid( $id ){
    $CI = &get_instance();
    $db_name = 'company_evaluate';
    if($id){
        return $CI->db->where( 'id',$id)->limit(1)->get($db_name)->row_array(1);
    }
    return false;
}
/***
 * 根据session-token查询，取最新的一条
 *
 */
function get_last_by_token( $session_token ){
    $CI = &get_instance();
    $db_name = 'company_evaluate';
    $db_related_name = 'comany_evaluate_related';
    if($session_token){
        $CI->db->select('company_name,eva.id as eid,eva.page_path');
        $CI->db->from($db_related_name.' related ');
        $CI->db->join($db_name. ' eva', 'eva.id = related.evaluate_id');
        $CI->db->order_by('related.updated','DESC');
      return  $CI->db->where( 'session_token',$session_token)->limit(1)->get()->row_array(1);
    }
    return false;
}
/**
 * 保存数据
 *
 */
function save2db( $data = array()){
    $CI = &get_instance();
    $db_name = 'company_evaluate';
    $db_related_name = 'comany_evaluate_related';
    if($data &&count($data)){
            $data['created'] = date('Y-m-d H:i:s');
            $insert_id = $CI->db->insert($db_name, $data);
            $data_related = array(
                'evaluate_id' => $data['id'],
                'session_token' => $CI->enterp_token,
                'created' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s')
                );
            $row = $CI->db->where( array('evaluate_id'=>$data['id'],'session_token'=>$CI->enterp_token))->get($db_related_name)->row_array(1);
            if(!$row){
            return $insert_id = $CI->db->insert($db_related_name, $data_related);
            }else{
              return $CI->db->update($db_related_name, array( 'updated' => date('Y-m-d H:i:s')), array('id' => $row['id']));
            }
            return true;
    }
    return false;
}
/**
 * 保存数据
 *
 */
function save_related2db( $data = array()){
    $CI = &get_instance();
    $db_related_name = 'comany_evaluate_related';
    if($data &&count($data)){
            $data['created'] = date('Y-m-d H:i:s');
            $data['updated'] = date('Y-m-d H:i:s');
            $row = $CI->db->where( array('evaluate_id'=>$data['evaluate_id'],'session_token'=>$data['session_token']))->get($db_related_name)->row_array(1);
            if(!$row){
                 return  $CI->db->insert($db_related_name, $data);
            }else{
              return $CI->db->update($db_related_name, array( 'updated' => date('Y-m-d H:i:s')), array('id' => $row['id']));
            }
            return true;
    }
    return false;
}
/**
 * 生成不重复数字
 *
 *
 */
function get_uid(){
    $key=md5(time().'zn');
    return $key;
}

function microtime_float(){
	   list($usec, $sec) = explode(" ", microtime());
	   return ((float)$usec + (float)$sec);
	}
/**
 * 获取重定向
 */
function get_redirect_url($url){
    $header = get_headers($url, 1);
       if (strpos($header[0], '301') !== false || strpos($header[0], '302') !== false) {
        if(is_array($header['Location'])) {
            return $header['Location'][count($header['Location'])-1];
        }else{
            return $header['Location'];
        }
    }else {
        return $url;
    }
 }
 /*
  * 对象转数组
  */
    function objectToArray($e){
            $e=(array)$e;
            foreach($e as $k=>$v){
                if( gettype($v)=='resource' ) return;
                if( gettype($v)=='object' || gettype($v)=='array' )
                    $e[$k]=(array)objectToArray($v);
            }
            return $e;
    }
//数组转对象
function arrayToObject($arr){
    if(is_array($arr)){
        return (object) array_map(__FUNCTION__, $arr);
    }else{
        return $arr;
    }
}