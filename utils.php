<?php
if ($run_in_phpfile === true ) {
// 加载外部插件
require_once dirname(__FILE__) . "/config.php";

function reset_timezone($originalTime){
	$originalTimeZone = new DateTimeZone('UTC');                      
	$dateTime = new DateTime($originalTime, $originalTimeZone);
	$targetTimeZone = new DateTimeZone(date_default_timezone_get());
	$dateTime->setTimezone($targetTimeZone);
	$convertedTime = $dateTime->format('Y-m-d H:i:s');
	return($convertedTime);
}

} else {
	header("HTTP/1.0 400 Bad Request");
}
?> 

