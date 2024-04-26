<?php
class Authorization_Code_Grant
{
    var $access_token;
    var $expires_in;

    var $raw_json_data;

    function __construct()
    {
        $post_data = array(
            "grant_type" => "client_credentials",
            "client_id" => OSU_OAUTH2_CLIENT_ID,
            "client_secret" => OSU_OAUTH2_CLIENT_SECRET,
            "scope" => "public",
        );
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

        $response_json_data = json_decode($response, true);

        $this->access_token = $response_json_data["access_token"];
        $this->expires_in = $response_json_data["expires_in"];

        $this->raw_json_data = $response_json_data;
    }

    public function get_raw_json_data()
    {
        return $this->raw_json_data;
    }

    public function get_access_token()
    {
        return $this->access_token;
    }

    public function get_expires_in()
    {
        return $this->expires_in;
    }
}
