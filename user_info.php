<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "script" . DIRECTORY_SEPARATOR . "preload.php";
if (isset($_GET["username"])){
  $username = input_filter($_GET["username"]);
} else { 
  $username = "Murasame_sama";
}

if (isset($_GET["mode"])){
  $mode = input_filter($_GET["mode"]);
} else { 
  $mode = "osu";
}

$endpoint = "/users/" . $username . "/" . $mode;
$guest_session_start = new Guest_Session;
$guest_session = $guest_session_start->session_data();
$query_playerdata = new Access_API($endpoint, $guest_session["access_token"], $guest_session["token_type"]);
$user_data = $query_playerdata->response_data();

function item($object, $value, $type = "text", $prefix = "", $suffix = "")
{
  if (!empty($value)) {
    switch ($type) {
      case "text":
        print("<tr><td>" . $object . "</td><td>" . $prefix . htmlspecialchars($value, ENT_QUOTES) . $suffix . "</td></tr>");
        break;
      case "url":
        print("<tr><td>" . $object . "</td><td>" . '<a href="' . $value . '">' . $value . '</a>' . "</td></tr>");
        break;
      case "image":
        print("<tr><td>" . $object . "</td><td>" . '<a href="' . $value . '"><img src="' . $value . '" alt="' . $object . '" class="mdui-img-fluid"></img></a>' . "</td></tr>");
        break;
    }
  }
}

?>
<!doctype html>
<html lang="zh-CN">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" />
  <meta name="renderer" content="webkit" />
  <link rel="stylesheet" href="https://unpkg.com/mdui@2/mdui.css">
  <link rel="stylesheet" href="https://fonts.googleapis.cn/icon?family=Material+Icons">
  <style>
    img {
      max-width : 100%;
    }
  </style>
  <script src="https://unpkg.com/mdui@2/mdui.global.js"></script>
  <title><?php print($user_data["username"] . "的详细信息"); ?></title>
</head>

<body>
  <mdui-layout>
    <!-- 顶部应用栏-->
    <mdui-top-app-bar id=bar>
      <mdui-top-app-bar-title id=title><?php print($user_data["username"] . "的详细信息"); ?></mdui-top-app-bar-title>
      <div style="flex-grow: 1"></div>
      <mdui-tooltip content="点击以切换用户！">
        <mdui-avatar src="<?php print($user_data["avatar_url"]); ?>"></mdui-avatar>
      </mdui-tooltip>

    </mdui-top-app-bar>
    <!-- 顶部应用栏-->
    <mdui-layout-main>
      <p>一般信息</p>
      <div class="mdui-table">
        <table>
          <thead>
            <tr>
              <th>项目</th>
              <th>值</th>
            </tr>
          </thead>
          <tbody><?php
                  item("用户名", $user_data["username"]);
                  item("用户id", $user_data["id"]);
                  item("pp", $user_data["statistics"]["pp"], "text", "", "pp");
                  item("pp_exp", $user_data["statistics"]["pp_exp"], "text", "", "pp");
                  item("入榜总分", $user_data["statistics"]["ranked_score"]);
                  item("总计总分", $user_data["statistics"]["total_score"]);
                  item("总命中次数", $user_data["statistics"]["total_hits"]);
                  item("最大连击", $user_data["statistics"]["maximum_combo"]);
                  item("准确率", $user_data["statistics"]["hit_accuracy"], "text", "", "%");
                  item("注册时间", convert_timezone($user_data["join_date"]));
                  item("最后在线", convert_timezone($user_data["last_visit"]));
                  item("网站", $user_data["website"], "url")
                  ?>
          </tbody>
        </table>
      </div>
      <p>高级选项</p>
      <div class="mdui-table">
        <table>
          <thead>
            <th>项目</th>
            <th>值</th>
          </thead>
          <tbody>
            <?php
            item("成为过supporter", $user_data["has_supported"]);
            item("最大好友数", $user_data["max_friends"]);
            //	item("个人页面(原始数据)",$user_data["page"]["raw"]);
            item('总计打出"300"的次数', $user_data["statistics"]["count_300"]);
            item('总计打出"100"的次数', $user_data["statistics"]["count_100"]);
            item('总计打出"50"的次数', $user_data["statistics"]["count_50"]);
            item('总计打出"miss"的次数', $user_data["statistics"]["count_miss"]);
            item("supporter等级", $user_data["support_level"]);
            ?>
          </tbody>
        </table>
      </div>
      <p>资源</p>
      <div class="mdui-table">
        <table>
          <thead>
            <tr>
              <th>项目</th>
              <th>值</th>
            </tr>
          </thead>
          </tbody>
          <?php
          item("头像", $user_data["avatar_url"], "image");
          item("横幅", $user_data["cover_url"], "image");
          ?>
          </tbody>
        </table>
      </div>
    </mdui-layout-main>
  </mdui-layout>
</body>

</html>