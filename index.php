<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "script" . DIRECTORY_SEPARATOR . "preload.php";
require_once RUN_SCRIPT_PATH . "session_setup.php";
if (isset($_SESSION["logined"])) {
    $get_me_data = new Access_API("/me", $_SESSION["access_token"], $_SESSION["token_type"]);
    $user_data = $get_me_data->response_data();
}
if (isset($_GET["action"])) {
    switch ($_GET["action"]) {
        case "@query":
            if (isset($_SESSION["logined"])) {
                if (!isset($_GET["query_method"])) {
                    $query_method = "bbcode";
                } else {
                    $query_method = $_GET["query_method"];
                }
                if (isset($query_method)) {
                    $access_api = new Access_API("/friends", $_SESSION["access_token"], $_SESSION["token_type"]);
                    $response_object = $access_api->response_data(true);
                    switch ($query_method) {
                        case "bbcode":
                            ob_start();
                            foreach ($response_object as $friend) {
                                $username = $friend->username;
                                $id = $friend->id;
                                $profile_colour = $friend->profile_colour;
                                // 如果检测到profile_colour参数不为空则使用另外一个带有color标签的模板，这里的profile_colour是用户的名字颜色而非个人资料颜色，通常管理员用户组组等才能拥有用户名称颜色参数
                                // 虽然说ppy更新了supporter的个人资料颜色支持，但是没有api可以读取
                                // 什么时候更新什么时候加
                                if ($profile_colour === null) {
                                    echo "[url=https://osu.ppy.sh/u/{$id}]{$username}[/url]\n";
                                } else {
                                    echo "[color={$profile_colour}][url=https://osu.ppy.sh/u/{$id}]{$username}[/url][/color]\n";
                                }
                            }
                            $friends_list = htmlspecialchars(ob_get_clean(), ENT_QUOTES);
                            break;
                        case "plaintext":
                            ob_start();
                            foreach ($response_object as $friend) {
                                $username = $friend->username;
                                print($username . " ");
                            }
                            $friends_list = htmlspecialchars(ob_get_clean(), ENT_QUOTES);
                            break;
                        case "csv":
                            header("Content-Type: text/csv");
                            header("Content-Disposition: attachment;filename=" . "export-" . time() . ".csv");
                            ob_start();
                            printf('"' . "id" . '","' . "username" . '","' . "play_count" . '","' . "play_time" . '","' . "pp" . '","' . "global_rank" . '","' . "ranked_score" . '","' . "total_score" . '"' . "\r\n");
                            foreach ($response_object as $friend) {
                                $username = $friend->username;
                                $id = $friend->id;
                                $statistics = $friend->statistics;
                                $pp = $statistics->pp;
                                $play_count = $statistics->play_count;
                                $play_time = $statistics->play_time;
                                $global_rank = $statistics->global_rank;
                                $ranked_score = $statistics->ranked_score;
                                $total_score = $statistics->total_score;
                                printf('"' . $id . '","' . $username . '","' . $play_count . '","' . $play_time . '","' . $pp . '","' . $global_rank . '","' . $ranked_score . '","' . $total_score . '"' . "\r\n");
                            }
                            ob_end_flush();
                            die();
                            break;
                        default:
                            // 你要是触发这个分支你也是无敌了，前面的switch都拦截了未知方法
                            die("未知方法");
                    }
                }
            } else {
                die("你没登录");
            }
            break;
        default:
            die("此路不通！");
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" />
    <meta name="renderer" content="webkit" />
    <link rel="stylesheet" href="https://unpkg.com/mdui@2/mdui.css">
    <link rel="stylesheet" href="https://fonts.googleapis.cn/icon?family=Material+Icons">
    <script src="https://unpkg.com/mdui@2/mdui.global.js"></script>
</head>

<body>
    <!-- 顶部应用栏-->
    <mdui-top-app-bar id=bar>
        <mdui-top-app-bar-title id=title>这是一个什么程序？</mdui-top-app-bar-title>
        <div style="flex-grow: 1"></div>
        <mdui-tooltip content="点击查看用户选项！">
            <mdui-avatar id="avatar" src="<?php print($user_data["avatar_url"]); ?>"></mdui-avatar>
        </mdui-tooltip>
    </mdui-top-app-bar>
    <!-- 顶部应用栏-->
    <mdui-dialog close-on-overlay-click id="avatar-dialog">
        用户选项
        <br></br>
        <mdui-button id="command-login">以当前以登录用户的身份继续</mdui-button>
        <mdui-button id="command-logout">登出（结束本次会话）</mdui-button>
        <mdui-button id="oauth2">管理授权</mdui-button>
    </mdui-dialog>
    <div class="mdui-prose" id="readme">
        <h1>介绍</h1>
        <p>这是一个研究和学习目的的php小程序，由php编写，包含满满的bug和特性</p>
        <p>使用它可以统计当前你的账户关注了哪些好友，并且生成一长串的BBCode代码以放在你的个人主页</p>
        <p>当然也可以生成空格分割的纯文本字符串，或者下载CSV表格</p>
        <p>先点击右上方圆形头像登录来取得列表</p>
    </div>
    <mdui-text-field autosize readonly variant="outlined" id="response" name="textfield"
        value="<?php if (isset($query_method)) { printf($friends_list); }?>"></mdui-text-field>
    <br></br>
    <mdui-button id="command-copy" icon="content_copy" loading disabled>复制</mdui-button>
    <mdui-button id="command-query-bbcode">以BBCode形式列出</mdui-button>
    <mdui-button id="command-query-plaintext">以纯文本形式列出</mdui-button>
    <mdui-button id="command-query-csv">CSV</mdui-button>
    <script>
    const text_field = document.querySelector('#response');
    const cmd_copy = document.querySelector('#command-copy');
    cmd_copy.addEventListener('click', () => {
        text_field.select();
        document.execCommand('copy');
        mdui.snackbar({
            message: '已复制到剪贴板',
            position: 'right-bottom'
        });
    });
    const cmd_bbcode = document.querySelector('#command-query-bbcode');
    cmd_bbcode.addEventListener('click', () => {
        window.location.href = "/?action=@query&query_method=bbcode";
    });
    const cmd_plaintext = document.querySelector('#command-query-plaintext');
    cmd_plaintext.addEventListener('click', () => {
        window.location.href = "/?action=@query&query_method=plaintext";
    });
    const cmd_csv = document.querySelector('#command-query-csv');
    cmd_csv.addEventListener('click', () => {
        mdui.snackbar({
            message: '下载已经开始，请注意浏览器提示',
            position: 'right-bottom'
        });
        window.location.href = "/?action=@query&query_method=csv";
    });
    const cmd_login = document.querySelector('#command-login');
    cmd_login.addEventListener('click', () => {
        window.location.href = "/?action=@new_session";
    });
    const cmd_logout = document.querySelector('#command-logout');
    cmd_logout.addEventListener('click', () => {
        window.location.href = "/?action=@destroy_session";
    });
    const avatar = document.querySelector('#avatar');
    const avatar_dialog = document.querySelector('#avatar-dialog');
    avatar.addEventListener('click', () => {
        avatar_dialog.open = "true";
    });

    document.addEventListener('DOMContentLoaded', function() {
        cmd_copy.setAttribute('loading', 'false');
        cmd_copy.setAttribute('disabled', 'false');
        cmd_bbcode.setAttribute('variant', 'tonal');
        cmd_plaintext.setAttribute('variant', 'tonal');
        cmd_csv.setAttribute('variant', 'tonal');
        if (text_field.value === "") {
            text_field.style.display = "none";
            cmd_copy.style.display = "none";
        } else {
            const readme_text = document.querySelector('#readme');
            readme.style.display = "none";
        }
    });
    </script>
</body>

</html>