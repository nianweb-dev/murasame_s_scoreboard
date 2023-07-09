<?php
if ($run_in_phpfile === true ) {
// 加载外部插件
require_once dirname(__FILE__) . "/config.php";

if ($osu_default_user_only !== true) {
// 如果URL带有name参数就直接覆盖这里配置的默认uid
	if (isset($_GET['user']) && !empty($_GET['user'])) {
		// 过滤和验证 user 参数
		$osu_user = filter_var($_GET['user'], FILTER_SANITIZE_STRING);
	} else {
		// 有参数但是为空时
		$osu_user = $osu_default_user;
	}
} else {
	// 当开启仅默认用户模式时，忽略URL参数
	$osu_user = $osu_default_user;
} 


// 个人信息部分
//
// API请求的URL
$url = "$osu_apiserver/get_user?k=$osu_apikey&u=$osu_user&m=$osu_mode";

// 发起API请求
$response = file_get_contents($url);

// 解析JSON响应
$data = json_decode($response, true);

// 检查是否成功解析JSON
if ($data !== null) {
    // 遍历数组中的每个对象
    foreach ($data as $item) {
        // 获取所需的变量值
	$user_id = $item['user_id'];    
	$username = $item['username'];
	$join_date = $item['join_date'];	
	$count300 = $item['count300'];
        $count100 = $item['count100'];
	$count50 = $item['count50'];
	$playcount = $item['playcount'];
	$ranked_score = $item['ranked_score'];
	$total_score = $item['total_score'];
	$pp_rank = $item['pp_rank'];
	$level = $item['level'];
	$pp_raw = $item['pp_raw'];
	$accuracy = $item['accuracy'];
	$count_rank_ss = $item['count_rank_ss'];
	$count_rank_ssh = $item['count_rank_ssh'];
	$count_rank_s = $item['count_rank_s'];
	$count_rank_sh = $item['count_rank_sh'];
	$count_rank_a = $item['count_rank_a'];
	$country = $item['country'];
	$total_seconds_played = $item['total_seconds_played'];
	$pp_country_rank = $item['pp_country_rank'];
    }
} else {
    // JSON解析失败
    $username = "无法访问API服务器";
}

if ($username == null) {
	$username = "  用户不存在";
	$pp_rank = "     或者是API响应错误";
} else {

// 最近游玩部分
//
// 对于错误或者不存在的用户，不读取最近游玩数据，降低API服务器负载
//
// API请求的URL
$url = "$osu_apiserver/get_user_recent?k=$osu_apikey&u=$osu_user&m=$osu_mode&limit=1";

// 发起API请求
$response = file_get_contents($url);

// 解析JSON响应
$data = json_decode($response, true);

// 检查是否成功解析JSON
if ($data !== null) { 
	// 获取所需的变量值
	$recent_beatmap_id = $data[0]['beatmap_id'];
	$recent_score = $data[0]['score'];
	$recent_maxcombo = $data[0]['maxcombo'];
	$recent_count50 = $data[0]['count50'];
	$recent_count100 = $data[0]['count100'];
	$recent_count300 = $data[0]['count300'];
	$recent_countmiss = $data[0]['countmiss'];
	$recent_countkatu = $data[0]['countkatu'];
	$recent_countgeki = $data[0]['countgeki'];
	$recent_perfect = $data[0]['perfect'];
	$recent_enabled_mods = $data[0]['enabled_mods'];
	$recent_user_id = $data[0]['user_id'];
	$recent_date = $data[0]['date'];
	$recent_rank = $data[0]['rank'];
	$recent_score_id = $data[0]['score_id'];
    
} else {
    // JSON解析失败
    $recent_score = "无法访问API服务器";
}
}

// 解析最近游玩的谱面数据
if ($recent_beatmap_id !== null) {
	$url = "$osu_apiserver/get_beatmaps?k=$osu_apikey&b=$recent_beatmap_id";
	$response = file_get_contents($url);
	$data = json_decode($response, true);
	$recent_beatmapset_id = $data[0]['beatmapset_id'];
	$recent_title = $data[0]['title'];
	$recent_version = $data[0]['version'];
}

} else {
	header("HTTP/1.0 400 Bad Request");
}
?> 

