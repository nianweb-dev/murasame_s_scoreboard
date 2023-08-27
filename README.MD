不知道怎么取名字，就暂定名字是murasame_s_scoreboard了

使用原生php编写，主要是我不会用框架........

## 开始使用

修改Murasame_sama为你自己的名字

个人资料+最近游玩，

https://mrsa.minato.eu.org/generate_image.php?user=Murasame_sama

对于之前创建的链接，为了保证兼容性，我会保持将profile.php 301重定向至generate_image.php，但是未来可能会被移除，请记得修改profile.php结尾的链接

## 自托管服务

需要搭建php环境，php版本推荐php7.4+

php需要带有gd与curl扩展

首次使用，请编辑config.php编辑第6，第7行。填写你的客户端ID和客户端密钥，例子如下面所示

````php
define("OSU_OAUTH2_CLIENT_ID", 你的客户端ID);
define("OSU_OAUTH2_CLIENT_SECRET","你的客户端密钥");
````

客户端ID与客户端密钥可以在
https://osu.ppy.sh/home/account/edit#oauth
获得





