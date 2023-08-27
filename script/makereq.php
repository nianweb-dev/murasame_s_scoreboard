<?php
function make_req($path){

$header = array(
    "Content-Type: application/json",
    "Accept: application/json",
    "Authorization: Bearer " . $GLOBALS["access_token"]
);

if (empty($path)) {
    http_response_code(400);
    die();
}

$req = curl_init();
curl_setopt($req, CURLOPT_URL,  OSU_API_EDNPOINT . "{$path}");
curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
curl_setopt($req, CURLOPT_HTTPHEADER, $header);
$response = curl_exec($req);
curl_close($req);
// print($response);
return json_decode($response, true);
}
?>