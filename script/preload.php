<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "config.php";

if (empty(OSU_OAUTH2_CLIENT_ID) || empty(OSU_OAUTH2_CLIENT_SECRET)) {
    die("客户端ID或者客户端密钥未设置");
}

if (!extension_loaded("gd") || !extension_loaded("curl")) {
	die("程序依赖的全部php扩展未安装或者未加载");
}

function convert_timezone($original_time){
	if (!empty($original_time)) {
	$original_timezone = new DateTimeZone('UTC');                      
	$dateTime = new DateTime($original_time, $original_timezone);
    //默认使用系统时区
	$target_timezone = new DateTimeZone(date_default_timezone_get());
	$dateTime->setTimezone($target_timezone);
	$converted_time = $dateTime->format('Y-m-d H:i:s');
	return($converted_time);
	}
}


function input_filter($input) {
    $filtered_input = preg_replace('/[^a-zA-Z0-9_-]/', '', $input);
        return $filtered_input;
}


$cache_path = $sys_cache_path . DIRECTORY_SEPARATOR . "mrsa_cache";
if (!is_dir($cache_path)) {
	mkdir($cache_path);
}

// 加载其他函数
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "oauth2token.php";
$access_token = oauth2_token();
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "makereq.php";
?>