<?php
$token_file = $GLOBALS["cache_path"] . DIRECTORY_SEPARATOR . "token";
$token_ttl_file = $GLOBALS["cache_path"] . DIRECTORY_SEPARATOR . "token.ttl";

function oauth2_token() {
function gen_access_token() {
$post_data = array(
    "grant_type" => "client_credentials",
    "client_id" => OSU_OAUTH2_CLIENT_ID,
    "client_secret" => OSU_OAUTH2_CLIENT_SECRET,
    "scope" => "public",
);

$headers = array(
    "Accept: application/json",
    "Content-Type: application/x-www-form-urlencoded",
);

$req = curl_init();
curl_setopt($req, CURLOPT_URL, OSU_OAUTH2_TOKEN_ENDPOINT);
curl_setopt($req, CURLOPT_POST, 1);
curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($post_data));
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
curl_setopt($req, CURLOPT_HTTPHEADER, $headers); 

$response = curl_exec($req);

// 记得关闭curl
curl_close($req);

$token_data = json_decode($response, true);

$access_token = $token_data["access_token"];
$expires_in = $token_data["expires_in"];

// 生成过期时间
$expires_timestamp = time() + $expires_in;
file_put_contents($GLOBALS["token_file"], $access_token);
file_put_contents($GLOBALS["token_ttl_file"], $expires_timestamp);

// 生成token完成，返回token
return($access_token);
}

if (file_exists($GLOBALS["token_ttl_file"])) {
    // 存在，从文件加载时间戳
    $timestamp = (int) file_get_contents($GLOBALS["token_ttl_file"]);
	// 检查时间戳是否小于当前时间
	if ($timestamp < time()) {
        // 执行重新生成token的函数
        $access_token = gen_access_token();
	} else {
		// 缓存成功命中
		$access_token = file_get_contents($GLOBALS["token_file"]); 
	}
} else {
    // 如果文件不存在，则重新生成token
	$access_token = gen_access_token();
}
return $access_token;
}
?>
