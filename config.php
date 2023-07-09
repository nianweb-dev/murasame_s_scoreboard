<?php
if ($run_in_phpfile === true ) {
// 图片基本属性
$image_width = 800;
$image_hight = 600;

// 文本参数与排版设置
$text_size = "20";

// 需要一个字体绘制图片，填写完整路径
$text_font = "";
$Leading = 30;
$zone_a_X = 400;
$zone_c_X = 190;
$zone_d_X = 400;


// osu设置
//
// 这里填写你的legacy API密钥
$osu_apikey = "";

$osu_apiserver = "https://osu.ppy.sh/api";
$osu_avatarserver = "https://a.ppy.sh";
$osu_b_server = "https://b.ppy.sh/thumb";
$osu_default_user = "Murasame_sama";
$osu_default_user_only = false;
$osu_default_mode = 0;

$osu_mode = "0";

// 禁用所有错误报告
error_reporting(0);

// 设置默认时区
date_default_timezone_set('Asia/Shanghai');

} else {
	header("HTTP/1.0 400 Bad Request");
}
?>

