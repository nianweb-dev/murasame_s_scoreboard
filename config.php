<?php

// oauth2客户端ID和密钥
// 可以在 https://osu.ppy.sh/home/account/edit#oauth 获得
// 请妥善保管不要让别人知道
define("OSU_OAUTH2_CLIENT_ID", "");
define("OSU_OAUTH2_CLIENT_SECRET","");

// 如果查询时不指定用户，则使用这里配置的默认用户
define("OSU_DEFAULT_USER","Murasame_sama");
define("OSU_DEFAULT_USER_ONLY", false);

// 如果不是为私服搭建就不需要修改
define("OSU_API_EDNPOINT","https://osu.ppy.sh/api/v2");
define("OSU_OAUTH2_TOKEN_ENDPOINT","https://osu.ppy.sh/oauth/token");

// 绘制图片的相关设置
define("IMAGE_TEXT_DEFAULT_FONT", dirname(__FILE__) . DIRECTORY_SEPARATOR . "fonts" . DIRECTORY_SEPARATOR . "SourceHanSansCN-Heavy.otf");
define("IMAGE_TEXT_DEFAULT_FONT_SIZE", 20);
define("IMAGE_TEXT_LEADING", 30);

// 禁用所有错误报告
// 当你调试完毕确认无错误时取消注释下面这行，防止意外产生的错误弹出隐私数据
//error_reporting(0);


// 设置默认时区
date_default_timezone_set('Asia/Shanghai');

?>

