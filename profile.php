<?php
$run_in_phpfile = true;
//加载外部插件
// 
ob_start();
require_once dirname(__FILE__) . "/config.php";
require_once dirname(__FILE__) . "/utils.php";
require_once dirname(__FILE__) . "/osu_utils.php";
ob_end_clean();

// 创建图像
$image = imagecreatetruecolor($image_width, $image_hight);

// 设置图像颜色模式为透明
imagealphablending($image, false);
imagesavealpha($image, true);

// 创建透明背景
$transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
imagefill($image, 0, 0, $transparent);

// 从本地加载并绘制头像
//$avatar = imagecreatefromjpeg('/web/api/avatar0621.jpg');
//imagecopy($image, $avatar, 0, 0, 0, 0, 350, 350);

// 从远程加载并绘制头像
// 下载图片
$imageUrl = "$osu_avatarserver/${user_id}";
$imageData = file_get_contents($imageUrl);

// 加载图片
$avatar = imagecreatefromstring($imageData);

// 从远程获取的图像并不一定是350x350
// 所以这里先进行拉伸再执行imagecopy()
// 创建一个新的图像资源，大小为350x350
$resized_avatar = imagecreatetruecolor(350, 350);

// 将原始图像拉伸到目标大小
imagecopyresampled($resized_avatar, $avatar, 0, 0, 0, 0, 350, 350, imagesx($avatar), imagesy($avatar));
$imageX = 10;
$imageY = 10;
imagecopy($image, $resized_avatar, $imageX, $imageY, 0, 0, 350, 350);

// 接下来用不到头像资源了，销毁它来节省服务器宝贵的内存
imagedestroy($avatar);
imagedestroy($resized_avatar);


// 定义文本颜色
$text_color = imagecolorallocate($image, 255, 255, 255);

// 在图像上绘制文本


// A zone

$textX = $zone_a_X;

$textY = $Leading;
$text = "用户名:$username";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textY = $textY + $Leading;
$text = "全球排名:$pp_rank";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textY = $textY + $Leading;
$text = "地区排名:$pp_country_rank";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textY = $textY + $Leading;
$text = "PP:$pp_raw";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textY = $textY + $Leading;
$text = "上榜总分:$ranked_score";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textY = $textY + $Leading;
$text = "完整总分:$total_score";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textY = $textY + $Leading;
$text = "精准度:$accuracy%";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textY = $textY + $Leading;
$text = "游玩次数:$playcount";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textY = $textY + $Leading;
$text = "银SS:$count_rank_ssh";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textX = $zone_a_X + 200;
$text = "金SS:$count_rank_ss";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textX = $zone_a_X;
$textY = $textY + $Leading;
$text = "银S:$count_rank_sh";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textX = $zone_a_X + 200;
$text = "金S:$count_rank_s";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textY = $textY + $Leading;
$text = "A:$count_rank_a";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textX = $zone_a_X;
$textY = $textY + $Leading;
$text = "注册时间:" . reset_timezone($join_date);
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

// Free zone

$textX = 10;
$textY = $image_hight - 20;
$text = "更新时间 " . date('Y-m-d H:i:s');
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textX = $image_width - 200;
$textY = $image_hight - 20;
$text = "UID:$user_id";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

/*
$textX = $image_width - 200;
$textY = $textY - $Leading;
//$text = "由PHP" . substr(PHP_VERSION,0,3) . "运行";
$text = ".";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);
 */

// 最近游玩数据

if ($recent_beatmap_id !== null) {

// Free zone

$textX = 10;
$textY = 390;
$text = "最近游玩:$recent_title / $recent_version";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

// C zone

$textX = $zone_c_X;

$textY = 420;
$text = "成绩评级:$recent_rank";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);


$textY = $textY + $Leading;
$text = "最大连击:$recent_maxcombo";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);


$textY = $textY + $Leading;
$text = "300:$recent_count300";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textY = $textY + $Leading;
$text = "100:$recent_count100";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textY = $textY + $Leading;
$text = "50:$recent_count50";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);


// D zone
//
$textX = $zone_d_X;

$textY = 420;

$text = "完成时间:" . reset_timezone($recent_date);
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textY = $textY + $Leading;
$text = "得分:$recent_score";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textY =$textY + $Leading;
$text = "激:$recent_countgeki";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textY = $textY + $Leading;
$text = "喝:$recent_countkatu";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);

$textY = $textY + $Leading;
$text = "漏击:$recent_countmiss";
imagettftext($image, $text_size, 0, $textX, $textY, $text_color, $text_font, $text);


// 下载图片
$imageUrl = "$osu_b_server/${recent_beatmapset_id}l.jpg";
$imageData = file_get_contents($imageUrl);

// 加载图片
$r_image = imagecreatefromstring($imageData);

$imageX = 10;
$imageY = $image_hight - 190;
imagecopy($image, $r_image, $imageX, $imageY, 0, 0, 160, 120);

imagedestroy($r_image);
} 

// 设置 HTTP 标头以输出图像
header('Content-Type: image/png');

// 禁止客户端缓存响应结果
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// 输出图像
imagepng($image);

// 释放图像资源
imagedestroy($image);
?>

