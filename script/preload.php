<?php
define("ABS_PATH", dirname(__DIR__) . DIRECTORY_SEPARATOR);
define("RUN_SCRIPT_PATH", ABS_PATH . "script" . DIRECTORY_SEPARATOR);
define("RUN_FONT_PATH", ABS_PATH . "fonts" . DIRECTORY_SEPARATOR);
require_once ABS_PATH . "config.php";

if (!extension_loaded("gd") || !extension_loaded("curl") || !extension_loaded("openssl")) {
    die("程序依赖的全部php扩展未安装或者未加载。\r\n此程序需要安装并使用gd,curl,openssl扩展。");
}

if (!is_writeable(sys_get_temp_dir()) || !is_readable(sys_get_temp_dir())) {
    die("缓存目录不可读写!");
}

if (empty(OSU_OAUTH2_CLIENT_ID) || empty(OSU_OAUTH2_CLIENT_SECRET)) {
	die("客户端ID或者客户端密钥未设置");
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
require_once RUN_SCRIPT_PATH . "oauth2_client.php";
require_once RUN_SCRIPT_PATH . "access_api.php";
require_once RUN_SCRIPT_PATH . "image_drawer_gd.php";

// 保持为最后一行
require_once RUN_SCRIPT_PATH . "api_handler.php";