<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "script" . DIRECTORY_SEPARATOR . "preload.php";

function item($object,$value,$type="text",$prefix="",$suffix="") {
	if (!empty($value)) {
		switch($type) {
		case "text":
			print("<tr><td>" . $object . "</td><td>" . $prefix . htmlspecialchars($value,ENT_QUOTES) . $suffix . "</td></tr>");
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
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- MDUI CSS -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/mdui@1.0.1/dist/css/mdui.min.css"
  integrity="sha384-cLRrMq39HOZdvE0j6yBojO4+1PrHfB7a9l5qLcmRm/fiWXYY+CndJPmyu5FV/9Tw"
  crossorigin="anonymous"
/>
<!-- MDUI JavaScript -->
<script
  src="https://cdn.jsdelivr.net/npm/mdui@1.0.1/dist/js/mdui.min.js"
  integrity="sha384-gCMZcshYKOGRX9r6wbDrvF+TcCCswSHFucUzUPwka+Gr+uHgjlYvkABr95TCOz3A"
  crossorigin="anonymous"
></script>
  <title><?php print($user_data["username"] . "的详细信息"); ?></title>
</head>
<body class="mdui-theme-primary-indigo mdui-theme-accent-pink">

  <div class="mdui-container">
   <p>一般信息</p>
    <table class="mdui-table mdui-table-hoverable">
      <thead>
        <tr>
          <th>项目</th>
          <th>值</th>
        </tr>
      </thead>
      <tbody>
	<?php 
		item("用户名",$user_data["username"]);
		item("用户id",$user_data["id"]);
		item("pp",$user_data["statistics"]["pp"],"text","","pp");
		item("pp_exp",$user_data["statistics"]["pp_exp"],"text","","pp");
		item("入榜总分",$user_data["statistics"]["ranked_score"]);
		item("总计总分",$user_data["statistics"]["total_score"]);
		item("总命中次数",$user_data["statistics"]["total_hits"]);
		item("最大连击",$user_data["statistics"]["maximum_combo"]);
		item("准确率",$user_data["statistics"]["hit_accuracy"],"text","","%");
		item("注册时间",convert_timezone($user_data["join_date"]));
		item("最后在线",convert_timezone($user_data["last_visit"]));
		item("网站",$user_data["website"],"url")


	?>
      </tbody>
    </table>
   <p>高级选项</p>
    <table class="mdui-table mdui-table-hoverable">
     <thead>
      <th>项目</th>
      <th>值</th>
     </thead>
     <tbody>

	<?php
		item("成为过supporter",$user_data["has_supported"]);
		item("最大好友数",$user_data["max_friends"]);
	//	item("个人页面(原始数据)",$user_data["page"]["raw"]);
		item('总计打出"300"的个数',$user_data["statistics"]["count_300"]);
		item('总计打出"100"的个数',$user_data["statistics"]["count_100"]);
		item('总计打出"50"的个数',$user_data["statistics"]["count_50"]);
		item('总计打出"miss"的个数',$user_data["statistics"]["count_miss"]);
		item("supporter等级",$user_data["support_level"]);



?>

     </tbody>
    </table>   
   <p>资源</p>
    <table class="mdui-table mdui-table-hoverable">
     <thead>
      <tr>
       <th>项目</th>
       <th>值</th>
      </tr>
     </thead>
     </tbody>
	<?php
		item("头像",$user_data["avatar_url"],"image");
		item("横幅",$user_data["cover_url"],"image");

       ?>
     </tbody>
    </table>

  </div>
  <script>
    // 初始化mdui
    mdui.mutation();
  </script>
</body>
</html>

