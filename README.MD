不知道怎么取名字，就暂定名字是murasame_s_scoreboard了

使用原生php编写，主要是我不会用框架........

## 我提供的API端点

将下面user=后面的名字替换成你自己的名字就能够动态生成用户信息了

https://mrsa.minato.eu.org/profile.php?user=Murasame_sama

## 使用方法

运行环境要求php环境，php版本推荐php7.4

php需要带有gd扩展

编辑config.php

第11行，填写一个ttf格式字体的路径。用于在图片上绘制文字

第21行，填写你的旧版api密钥，可以在 https://osu.ppy.sh/home/account/edit#legacy-api 获取，请务必妥善保管你的api密钥，不要告诉给他人

一切完成后，访问profile.php应该能看到生成的PNG图片
