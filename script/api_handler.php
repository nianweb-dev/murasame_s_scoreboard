<?php
if (isset($_GET["api"])) {
    if (isset($_GET["username"])) {
        $username = $_GET["username"];
    } else {
        $username = "Murasame_sama";
    }
    switch ($_GET["api"]) {
        case "@playerinfo":
            $image = new Draw_Player_Image($username);
            header('Content-Type: image/webp');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
            if (isset($_GET["download"])) {
                if ($_GET["download"] == true) {
                    header("Content-Disposition: attachment;filename=" . $username . "-" . time() . ".webp");
                }
            }
            $image->image_type_1();
            die();
            break;
        default:
            die("可接受的参数：@playerinfo");
    }
}