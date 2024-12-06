<?php
class Draw_Player_Image
{
    public $playerdata;
    public $player_mode;
    public $image_format;
    public $guest_session;

    public function __construct($username = "Murasame_sama", $mode = "osu", $format = "webp")
    {
        $endpoint = "/users/" . $username . "/" . $mode;

        $guest_session_start = new Guest_Session;
        $this->guest_session = $guest_session_start->session_data();
        $guest_session_data = $this->guest_session;

        $query_playerdata = new Access_API($endpoint, $guest_session_data["access_token"], $guest_session_data["token_type"]);

        $this->playerdata = $query_playerdata;
        $this->player_mode = $mode;
        $this->image_format = $format;
    }
    public function image_type_1()
    {
        $get_data = $this->playerdata;
        $user_data = $get_data->response_data();

        // 对图像微调
        $zone_a_X = 400;
        $zone_c_X = 190;
        $zone_d_X = 400;
        $image_width = 800;
        $image_hight = 600;

        ##########################
        #            a区锚点      #
        #   头像                  #
        #                        #
        #                        #
        #                        #
        #    c区锚点  d区锚点     #
        # 最近                   #
        # 游玩                   #
        #                        #
        ##########################

        // 毕竟我文化水平不高，这就一个练手项目，这个锚点画图真的要我老命了

        // 创建图像
        $image = imagecreatetruecolor($image_width, $image_hight);

        // 设置图像颜色模式为透明
        imagealphablending($image, false);
        imagesavealpha($image, true);

        // 创建透明背景
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $transparent);

        // 从本地加载并绘制头像
        //$avatar = imagecreatefromjpeg('/web/api/avatar0721.jpg');
        //imagecopy($image, $avatar, 0, 0, 0, 0, 350, 350);

        // 从远程加载并绘制头像
        $request = curl_init($user_data["avatar_url"]);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        $avatar = imagecreatefromstring(curl_exec($request));
        curl_close($request);

        // 从远程获取的图像并不一定是350x350
        // 所以这里先进行拉伸再执行imagecopy()
        // 创建一个新的图像资源，大小为350x350
        $resized_avatar = imagecreatetruecolor(350, 350);

        // 将原始图像拉伸到目标大小
        imagecopyresampled($resized_avatar, $avatar, 0, 0, 0, 0, 350, 350, imagesx($avatar), imagesy($avatar));
        $imageX = 10;
        $imageY = 10;
        imagecopy($image, $resized_avatar, $imageX, $imageY, 0, 0, 350, 350);

        // 接下来用不到头像资源了，销毁它来节省服务器宝贵的内存
        imagedestroy($avatar);
        imagedestroy($resized_avatar);

        // 定义文本颜色
        $GLOBALS["text_color"] = imagecolorallocate($image, 255, 255, 255);
        $GLOBALS["text_font"] = RUN_FONT_PATH . "SourceHanSansCN-Heavy.otf";
        $GLOBALS["text_size"] = "20";
        // 在图像上绘制文本

        function d($image, $textX, $textY, $text, $textC = null, $textS = null, $textF = null)
        {
            if (empty($textC)) {
                $textC = $GLOBALS["text_color"];
            }
            if (empty($textS)) {
                $textS = $GLOBALS["text_size"];
            }
            if (empty($textF)) {
                $textF = $GLOBALS["text_font"];
            }
            imagettftext($image, $textS, 0, $textX, $textY, $textC, $textF, $text);
        }
        // A zone
        // zone_a_X 用于确定这个段落的左右位置，也就是X轴上的位置
        // Leading为文字间的行距，在首行作为Y轴上的首行位置，随后自增固定值

        $Leading = 30;
        $textY = $Leading;
        d($image, $zone_a_X, $textY, "用户名:" . $user_data["username"]);
        d($image, $zone_a_X, $textY = $textY + $Leading, "全球排名:" . $user_data["statistics"]["global_rank"]);

        // 某些用户没有地区排名
        if (isset($user_data["statistics"]["country_rank"])) {
            d($image, $zone_a_X, $textY = $textY + $Leading, "地区排名:" . $user_data["statistics"]["country_rank"]);
        }

        d($image, $zone_a_X, $textY = $textY + $Leading, "PP:" . $user_data["statistics"]["pp"]);
        d($image, $zone_a_X, $textY = $textY + $Leading, "上榜总分:" . $user_data["statistics"]["ranked_score"]);
        d($image, $zone_a_X, $textY = $textY + $Leading, "完整总分:" . $user_data["statistics"]["total_score"]);
        d($image, $zone_a_X, $textY = $textY + $Leading, "精准度:" . $user_data["statistics"]["hit_accuracy"] . "%");
        d($image, $zone_a_X, $textY = $textY + $Leading, "游玩次数:" . $user_data["statistics"]["play_count"]);

        d($image, $zone_a_X, $textY = $textY + $Leading, "银SS:" . $user_data["statistics"]["grade_counts"]["ssh"]);
        d($image, $zone_a_X + 200, $textY, "金SS:" . $user_data["statistics"]["grade_counts"]["ss"]);
        d($image, $zone_a_X, $textY = $textY + $Leading, "银S:" . $user_data["statistics"]["grade_counts"]["sh"]);
        d($image, $zone_a_X + 200, $textY, "金S:" . $user_data["statistics"]["grade_counts"]["s"]);
        d($image, $zone_a_X, $textY = $textY + $Leading, "A:" . $user_data["statistics"]["grade_counts"]["a"]);

        d($image, $zone_a_X, $textY = $textY + $Leading, "注册时间:" . convert_timezone($user_data["join_date"]));

        // Free zone

        d($image, "10", $image_hight - 20, "更新时间 " . date('Y-m-d H:i:s'));
        d($image, $image_width - 200, $image_hight - 20, "UID:" . $user_data["id"]);

        $guest_session_data = $this->guest_session;

        $query_string = array(
            "mode" => $this->player_mode,
            "limit" => 1,
        );
        $endpoint = "/users/" . $user_data["id"] . "/scores/recent?" . http_build_query($query_string);
        $new_recent_data = new Access_API($endpoint, $guest_session_data["access_token"], $guest_session_data["token_type"]);
        $recent_data = $new_recent_data->response_data();

        if (empty($recent_data)) {
            d($image, 270, 470, "这个用户没有最近游玩");
        } else {

            // Free zone
            d($image, 10, 390, "最近游玩:" . $recent_data[0]["beatmapset"]["title_unicode"] . " / " . $recent_data[0]["beatmap"]["version"]);
            // C zone
            d($image, $zone_c_X, $textY = 420, "成绩评级:" . $recent_data[0]["rank"]);
            d($image, $zone_c_X, $textY = $textY + $Leading, "最大连击:" . $recent_data[0]["max_combo"]);
            d($image, $zone_c_X, $textY = $textY + $Leading, "300:" . $recent_data[0]["statistics"]["count_300"]);
            d($image, $zone_c_X, $textY = $textY + $Leading, "100:" . $recent_data[0]["statistics"]["count_100"]);
            d($image, $zone_c_X, $textY = $textY + $Leading, "50:" . $recent_data[0]["statistics"]["count_50"]);
            // D zone
            //

            d($image, $zone_d_X, $textY = 420, "完成时间:" . convert_timezone($recent_data[0]["created_at"]));
            d($image, $zone_d_X, $textY = $textY + $Leading, "得分:" . $recent_data[0]["score"]);

            if (isset($recent_data[0]["statistics"]["count_geki"])) {
                d($image, $zone_d_X, $textY = $textY + $Leading, "激:" . $recent_data[0]["statistics"]["count_geki"]);
            }

            if (isset($recent_data[0]["statistics"]["count_katu"])) {
                d($image, $zone_d_X, $textY = $textY + $Leading, "喝:" . $recent_data[0]["statistics"]["count_katu"]);
            }

            d($image, $zone_d_X, $textY = $textY + $Leading, "漏击:" . $recent_data[0]["statistics"]["count_miss"]);

            if (!empty($recent_data[0]["pp"])) {
                d($image, $image_width - 200, $textY, "PP:" . $recent_data[0]["pp"]);
            }

            // 下载最近游玩的谱面的封面图片
            $request = curl_init($recent_data[0]["beatmapset"]["covers"]["card"]);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            $recent_beatmap_cover = imagecreatefromstring(curl_exec($request));
            curl_close($request);

            $imageX = 10;
            $imageY = $image_hight - 190;
            imagecopy($image, $recent_beatmap_cover, $imageX, $imageY, 0, 0, 160, 120);

            imagedestroy($recent_beatmap_cover);
        }

        // 输出图像
        switch ($this->image_format) {
            case "webp":
                imagewebp($image);
                break;
            case "png":
                imagepng($image);
                break;
        }
        imagedestroy($image);
    }
}
