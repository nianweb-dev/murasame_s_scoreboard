<?php
class UserData
{
    var $raw_json_data;
    function __construct($access_token, $user, $mode = "osu")
    {
        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Bearer " . $access_token
        );

        $request = curl_init();
        curl_setopt($request, CURLOPT_URL,  OSU_API_EDNPOINT . "/users/" . $user . "/" . $mode);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($request);

        curl_close($request);
        $response_json_data = json_decode($response, true);

        $this->raw_json_data = $response_json_data;
    }

    public function get_raw_json_data()
    {
        return $this->raw_json_data;
    }
}

class UserRecentScoresData
{
    var $raw_json_data;
    function __construct($access_token, $user_id, $mode = "osu", $limit = "1")
    {
        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Bearer " . $access_token
        );
        $query = array(
            "mode" => $mode,
            "limit" => $limit
        );
        $request = curl_init();
        curl_setopt($request, CURLOPT_URL,  OSU_API_EDNPOINT . "/users/" . $user_id . "/scores/recent" . "?" . http_build_query($query));
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($request);

        curl_close($request);
        $response_json_data = json_decode($response, true);

        $this->raw_json_data = $response_json_data;
    }

    public function get_raw_json_data()
    {
        return $this->raw_json_data;
    }
}
