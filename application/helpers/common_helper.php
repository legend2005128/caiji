<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo=true, $label=null, $strict=true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}

/*
 * Json转数组
 * @param $data String or Object
 * @return Array
 */
function toArray($json) {
    return json_decode($json, true);
}

/*
 * 数组转Json
 * @param $data Array or String
 * @return String
 */
function toJson($data) {
    return json_encode($data);
}

/**
 * @param $charset
 * @param $content
 * @return string
 * 解决phpquery无法获取到编码的问题
 * 如果编码格式获取不到，就会出现乱码，该方法能彻底解决，但是必须要提前获取编码信息
 */
function phpquery_char($charset,$content){

    $str = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

    //字符编码转义
    if($charset != 'utf-8'){
        $content = iconv($charset,'utf-8//IGNORE',$content);
    }

    $con = $str.$content;

    return $con;
}

/**
 * @param $info_url 需要替换的url
 * @param $base_url 基类url
 * 获取完整url
 */
function get_all_url($info_url,$base_url){
    $href = trim($info_url);
    if(stripos($href,'http') === 0){
        $list_arr[] = $href;
    }elseif(!empty($href)){
        if(stripos($href,'/') === 0){
            $info = parse_url($base_url);
            $href = $info['scheme'].'://'.$info['host'].$href;
        }else{
            $info = pathinfo($base_url);
            $href = $info['dirname'].'/'.$href;
        }
    }
    return $href;
}

/**
 * 简单对称加密算法之加密
 * @param String $string 需要加密的字串
 * @param String $skey 加密EKY
 * @update 2016-03-02 10:10
 * @return String
 */
function encode($string = '', $skey = 'cxphp') {
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key < $strCount && $strArr[$key].=$value;
    return str_replace(array('=', '+', '/'), array('', '', ''), join('', $strArr));
}
/**
 * 简单对称加密算法之解密
 * @param String $string 需要解密的字串
 * @param String $skey 解密KEY
 * @update 2016-03-02 10:10
 * @return String
 */
function decode($string = '', $skey = 'cxphp') {
    $strArr = str_split(str_replace(array('', '', ''), array('=', '+', '/'), $string), 2);
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key <= $strCount  && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    return base64_decode(join('', $strArr));
}

/*
 * 获取客户端IP地址
 * @param Null
 * @return String
 */
function getIP() {
    $ip_address = '0.0.0.0';
    if (!empty($_SERVER['HTTP_CDN_SRC_IP'])) {
        $ip_address = $_SERVER['HTTP_CDN_SRC_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['REMOTE_ADDR']) AND isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    if ($ip_address === 'Unknown') {
        $ip_address = '0.0.0.0';
        return $ip_address;
    }
    if (strpos($ip_address, ',') !== 'Unknown') {
        $x = explode(',', $ip_address);
        $ip_address = trim(end($x));
    }
    return $ip_address;
}