<?php
class Oauth2_Connect_Session
{
    public $raw_json_data;

    public function __construct($grant_type, $code = null, $refresh_token = null)
    {
        switch ($grant_type) {
            case "client_credentials":
                $post_data = array(
                    "grant_type" => "client_credentials",
                    "client_id" => OSU_OAUTH2_CLIENT_ID,
                    "client_secret" => OSU_OAUTH2_CLIENT_SECRET,
                    "scope" => "public",
                );
                break;
            case "authorization_code":
                $post_data = array(
                    "grant_type" => "authorization_code",
                    "client_id" => OSU_OAUTH2_CLIENT_ID,
                    "client_secret" => OSU_OAUTH2_CLIENT_SECRET,
                    "redirect_uri" => BASIC_URI,
                    "code" => $code,
                );
                break;
            case "refresh_token":
                $post_data = array(
                    "grant_type" => "refresh_token",
                    "client_id" => OSU_OAUTH2_CLIENT_ID,
                    "client_secret" => OSU_OAUTH2_CLIENT_SECRET,
                    "refresh_token" => $refresh_token,
                    "scope" => OSU_OAUTH2_SCOPE,
                );
                break;
        }
        $headers = array(
            "Accept: application/json",
            "Content-Type: application/x-www-form-urlencoded",
        );
        # 初始化cURL实例
        $request = curl_init();
        curl_setopt($request, CURLOPT_URL, OSU_OAUTH2_TOKEN_ENDPOINT);
        curl_setopt($request, CURLOPT_POST, true);
        curl_setopt($request, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($request);
        curl_close($request);

        $this->raw_json_data = $response;

    }

    public function session_data($is_a_object = false)
    {
        $response_array = json_decode($this->raw_json_data, true);
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
        if ($is_a_object == true) {
            return json_decode($this->raw_json_data);
        } else {
            return $response_array;
        }
    }
}

class Guest_Session
{
    public $guest_session;

    public function __construct()
    {
        $cached_data_file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "data-" . gethostname() . ".json";
        // 检查缓存文件是否存在
        if (file_exists($cached_data_file_path)) {
            $cached_array = json_decode(file_get_contents($cached_data_file_path), true);
            // 文件存在，检查是否在有效时间内
            if ($cached_array["expires_on"] <= time()) {
                // 过期，刷新
                $refresh_session_array = $this->refresh_guest_session();
                $refresh_session_json = json_encode($refresh_session_array);
                file_put_contents($cached_data_file_path, $refresh_session_json);
                $this->guest_session = $refresh_session_array;
            } else {
                // 没过期，直接返回
                $this->guest_session = $cached_array;
            }
        } else {
            //文件不存在，刷新再返回
            $new_session_array = $this->refresh_guest_session();
            $new_session_json = json_encode($new_session_array);
            file_put_contents($cached_data_file_path, $new_session_json);
            $this->guest_session = $new_session_array;
        }

    }
    public function session_data()
    {
        return $this->guest_session;
    }
    private function refresh_guest_session()
    {
        $create_a_guest_session = new Oauth2_Connect_Session("client_credentials");
        $guest_session_array = $create_a_guest_session->session_data();
        $return_guest_session_array = array(
            "token_type" => $guest_session_array["token_type"],
            "expires_on" => $guest_session_array["expires_in"] + time(),
            "expires_in" => $guest_session_array["expires_in"],
            "access_token" => $guest_session_array["access_token"],
        );
        return $return_guest_session_array;
    }
}