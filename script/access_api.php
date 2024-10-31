<?php
class Access_API
{
    public $raw_json_data;

    public function __construct($endpoint, $access_token, $token_type = "Bearer")
    {
        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: " . $token_type . " " . $access_token,
        );

        $request = curl_init();
        curl_setopt($request, CURLOPT_URL, OSU_API_EDNPOINT . "$endpoint");
        curl_setopt($request, CURLOPT_POST, false);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($request);
        curl_close($request);

        $this->raw_json_data = $response;

    }

    public function response_data($is_a_object = false)
    {
        $response_array = json_decode($this->raw_json_data, true);
        // 他妈的为什么返回的数组有概率为空？？？
        // 老子不管了，你返回空我就当作没报错
        if (!empty($response_array)) {
            if (array_key_exists("error", $response_array)) {
                http_response_code(400);
                if (session_status() == PHP_SESSION_ACTIVE) {
                    session_destroy();
                }
                if (DEBUG_MODE == true) {
                    die(htmlspecialchars(print_r($response_array), ENT_QUOTES));
                } else {
                    die("发生了一个错误，定义常量DEBUG_MODE为true查看详细报错信息");
                }
            }
        }
        if ($is_a_object == true) {
            return json_decode($this->raw_json_data);
        } else {
            return $response_array;
        }
    }
}