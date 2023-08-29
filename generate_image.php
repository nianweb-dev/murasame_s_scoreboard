<?php
// 启动输入缓冲，以捕获可能输出的回车符，防止打乱图片的二进制数据

ob_start();
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "script" . DIRECTORY_SEPARATOR . "preload.php";
ob_end_clean();

$Leading = IMAGE_TEXT_LEADING;

// 对图像微调
$zone_a_X = 400;
$zone_c_X = 190;
$zone_d_X = 400;
$image_width = 800;
$image_hight = 600;

// 创建图像
$image = imagecreatetruecolor($image_width, $image_hight);

// 设置图像颜色模式为透明
imagealphablending($image, false);
imagesavealpha($image, true);

// 创建透明背景
$transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
imagefill($image, 0, 0, $transparent);

// 从本地加载并绘制头像
//$avatar = imagecreatefromjpeg('/web/api/avatar0721.jpg');
//imagecopy($image, $avatar, 0, 0, 0, 0, 350, 350);

// 从远程加载并绘制头像
// 下载图片
$avatar_data = file_get_contents($user_data["avatar_url"]);

// 加载图片
$avatar = imagecreatefromstring($avatar_data);

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
$text = "用户名:" . $user_data["username"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

$textY = $textY + $Leading;
$text = "全球排名:" . $user_data["statistics"]["global_rank"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

$textY = $textY + $Leading;
$text = "地区排名:" . $user_data["statistics"]["country_rank"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

$textY = $textY + $Leading;
$text = "PP:" . $user_data["statistics"]["pp"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

$textY = $textY + $Leading;
$text = "上榜总分:" . $user_data["statistics"]["ranked_score"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

$textY = $textY + $Leading;
$text = "完整总分:" . $user_data["statistics"]["total_score"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

$textY = $textY + $Leading;
$text = "精准度:" . $user_data["statistics"]["hit_accuracy"] . "%";
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

$textY = $textY + $Leading;
$text = "游玩次数:" . $user_data["statistics"]["play_count"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);


$textY = $textY + $Leading;
$text = "银SS:" . $user_data["statistics"]["grade_counts"]["ssh"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

$textX = $zone_a_X + 200;
$text = "金SS:" . $user_data["statistics"]["grade_counts"]["ss"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

$textX = $zone_a_X;
$textY = $textY + $Leading;
$text = "银S:" . $user_data["statistics"]["grade_counts"]["sh"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

$textX = $zone_a_X + 200;
$text = "金S:" . $user_data["statistics"]["grade_counts"]["s"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

$textY = $textY + $Leading;
$text = "A:" . $user_data["statistics"]["grade_counts"]["a"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);


$textX = $zone_a_X;
$textY = $textY + $Leading;
$text = "注册时间:" . convert_timezone($user_data["join_date"]);
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

// Free zone

$textX = 10;
$textY = $image_hight - 20;
$text = "更新时间 " . date('Y-m-d H:i:s');
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

$textX = $image_width - 200;
$textY = $image_hight - 20;
$text = "UID:" . $user_data["id"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

// 最近游玩数据

$recent_data = make_req("/users/" . $user_data["id"] . "/scores/recent?limit=1");

if (empty($recent_data)) {
    $textX = 270;
    $textY = 470;
    $text = "这个用户没有最近游玩";
    imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);
} else {

// Free zone

$textX = 10;
$textY = 390;
$text = "最近游玩:" . $recent_data[0]["beatmapset"]["title_unicode"] . " / " . $recent_data[0]["beatmap"]["version"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

// C zone

$textX = $zone_c_X;

$textY = 420;
$text = "成绩评级:" . $recent_data[0]["rank"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);


$textY = $textY + $Leading;
$text = "最大连击:" . $recent_data[0]["max_combo"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);


$textY = $textY + $Leading;
$text = "300:" . $recent_data[0]["statistics"]["count_300"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

$textY = $textY + $Leading;
$text = "100:" . $recent_data[0]["statistics"]["count_100"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

$textY = $textY + $Leading;
$text = "50:" . $recent_data[0]["statistics"]["count_50"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);


// D zone
//
$textX = $zone_d_X;

$textY = 420;

$text = "完成时间:" . convert_timezone($recent_data[0]["created_at"]);
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

$textY = $textY + $Leading;
$text = "得分:" . $recent_data[0]["score"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

$textY =$textY + $Leading;
$text = "激:" . $recent_data[0]["statistics"]["count_geki"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

$textY = $textY + $Leading;
$text = "喝:" . $recent_data[0]["statistics"]["count_katu"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

$textY = $textY + $Leading;
$text = "漏击:" . $recent_data[0]["statistics"]["count_miss"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);

if (!empty($recent_data[0]["pp"])) {

$textX = $image_width - 200;
$text = "PP:" . $recent_data[0]["pp"];
imagettftext($image, IMAGE_TEXT_DEFAULT_FONT_SIZE, 0, $textX, $textY, $text_color, IMAGE_TEXT_DEFAULT_FONT, $text);
 
}

// 下载最近游玩的谱面的封面图片
$beatmap_cover_data = file_get_contents($recent_data[0]["beatmapset"]["covers"]["card"]);

// 加载图片
$recent_beatmap_cover = imagecreatefromstring($beatmap_cover_data);

$imageX = 10;
$imageY = $image_hight - 190;
imagecopy($image, $recent_beatmap_cover, $imageX, $imageY, 0, 0, 160, 120);

imagedestroy($recent_beatmap_cover);

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

