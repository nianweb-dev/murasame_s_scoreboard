<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "config.php";

if (empty(OSU_OAUTH2_CLIENT_ID) || empty(OSU_OAUTH2_CLIENT_SECRET)) {
	die("客户端ID或者客户端密钥未设置");
}

if (!extension_loaded("gd") || !extension_loaded("curl")) {
	die("程序依赖的全部php扩展未安装或者未加载。\r\n此程序需要安装并使用gd和curl扩展。");
}

function convert_timezone($original_time)
{
	if (!empty($original_time)) {
		$original_timezone = new DateTimeZone('UTC');
		$dateTime = new DateTime($original_time, $original_timezone);
		//默认使用系统时区
		$target_timezone = new DateTimeZone(date_default_timezone_get());
		$dateTime->setTimezone($target_timezone);
		$converted_time = $dateTime->format('Y-m-d H:i:s');
		return ($converted_time);
	}
}


function input_filter($input)
{
	$filtered_input = preg_replace('/[^a-zA-Z0-9_\-\[\]]/', '', $input);
	return $filtered_input;
}



// 加载其他组件
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "oauth2token.php";;
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "userdata.php";

// 取得用户数据

if (isset($_GET['user']) && !empty($_GET['user'])) {
	$user = input_filter($_GET['user']);
} else {
	$user = OSU_DEFAULT_USER;
}

// 首先应该生成令牌

$new_access_token = new Authorization_Code_Grant;

$access_token = $new_access_token->get_access_token();

$new_user_data = new UserData($access_token, $user);

$user_data = $new_user_data->get_raw_json_data();

if (array_key_exists("error", $user_data)) {
	die("找不到指定的用户！");
}
