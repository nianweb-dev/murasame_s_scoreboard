<?php
session_start();
if (isset($_GET["action"])) {
    switch ($_GET["action"]) {
        case "@new_session":
            $_SESSION["csrf_protection_token"] = base64_encode(openssl_random_pseudo_bytes(16));
            $query_string = array(
                "client_id" => OSU_OAUTH2_CLIENT_ID,
                "redirect_uri" => BASIC_URI,
                "scope" => OSU_OAUTH2_SCOPE,
                "state" => $_SESSION["csrf_protection_token"],
                "response_type" => "code",
            );
            http_response_code(302);
            header("Location: " . OSU_OAUTH2_AUTHORIZE_ENDPOINT . "?" . http_build_query($query_string));
            break;
        case "@destroy_session":
            session_destroy();
            http_response_code(302);
            header("Location: " . BASIC_URI);
            break;
    }
}

if (isset($_GET["code"])) {
    $code = $_GET["code"];
    if (!isset($_SESSION["csrf_protection_token"])) {
        die("验证失败：未发现CSRF令牌！");
    }
    if ($_SESSION["csrf_protection_token"] !== $_GET["state"]) {
        die("验证失败：不正确的CSRF令牌！");
    }
    $osu_session = new Oauth2_Connect_Session("authorization_code", $code);
    $oauth2_response = $osu_session->session_data();
    $_SESSION["access_token"] = $oauth2_response["access_token"];
    $_SESSION["refresh_token"] = $oauth2_response["refresh_token"];
    $_SESSION["token_type"] = $oauth2_response["token_type"];
    $_SESSION["expires_on"] = $oauth2_response["expires_in"] + time();
    $_SESSION["logined"] = true;
    // 验证结束后销毁CSRF令牌
    unset($_SESSION["csrf_protection_token"]);
    // 授权完成
    http_response_code(302);
    header("Location: " . BASIC_URI);
    die();
}

if (isset($_SESSION["logined"])) {
    if ($_SESSION["expires_on"] <= time()) {
        // 令牌已过期，尝试刷新
        $refresh_token = $_SESSION["refresh_token"];
        $osu_session = new Oauth2_Connect_Session("refresh_token", null, $refresh_token);
        $oauth2_response = $osu_session->session_data();
        $_SESSION["access_token"] = $oauth2_response["access_token"];
        $_SESSION["refresh_token"] = $oauth2_response["refresh_token"];
        $_SESSION["token_type"] = $oauth2_response["token_type"];
        $_SESSION["expires_on"] = $oauth2_response["expires_in"] + time();
        unset($osu_session);
    }
}