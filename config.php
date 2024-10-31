<?php
// oauth2客户端ID和密钥
// 可以在 https://osu.ppy.sh/home/account/edit#oauth 获得
// 请妥善保管不要让别人知道
define("OSU_OAUTH2_CLIENT_ID", "");
define("OSU_OAUTH2_CLIENT_SECRET", "");

// 回调链接，需要和https://osu.ppy.sh/home/account/edit#oauth里面填写的完全相同
// 如果部署为网页app，必须填写此选项为准确URI位置，否则无法正常弹出授权界面
// 如果只使用image drawer就不需要设置
define("BASIC_URI", "http://localhost:9004/");

define("DEBUG_MODE", true);

// 各个端点的设置，官方服务器就不需要修改
define("OSU_API_EDNPOINT", "https://osu.ppy.sh/api/v2");
define("OSU_OAUTH2_TOKEN_ENDPOINT", "https://osu.ppy.sh/oauth/token");
define("OSU_OAUTH2_AUTHORIZE_ENDPOINT", "https://osu.ppy.sh/oauth/authorize");
define("OSU_OAUTH2_SCOPE", "identify friends.read");

// 禁用所有错误报告
// 当你调试完毕确认无错误时取消注释下面这行，防止意外产生的错误弹出隐私数据
//error_reporting(0);


// 设置默认时区
date_default_timezone_set('Asia/Shanghai');