<?php

$con = new mysqli('localhost','root','1projectK!','oodoo_current');
if(!$con){
	die('mysql could not connected: '.mysql_error());
}

$url = 'http://vcapi.oodoo.co.in/';
$username = 'oodoo';
$password = 'fFPh9n9Tqz4ybQtBPwj8wNhZB5sts8YE';
$method = 'ConnectAudio';
$from = '8072738664'; //customer phone number
//$from = '9551904996';
$to = 'http://oodoo.co.in/Oodoo_IVR.mp3';
$call_type = 'trans';

$post_data = array(
	'Method' => $method,
	'From' => $from,
	'To' => $to,
	'CallType' => $call_type
);
$start = microtime(true);
ob_start();
$ch = curl_init();
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FAILONERROR, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
 
$http_result = curl_exec($ch);
$error = curl_error($ch);
$http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);
curl_close($ch);
ob_flush();
$decode = json_decode($http_result, true);
$call_id = $decode['CallID'];
$job_id = $decode['JobID'];	

$sql = "INSERT INTO caller_det (phone, audio_url)
VALUES ('$from', '$to')";
if($con->query($sql) === true){
	echo 'Call Detail Added :'.$call_id;
	echo '\n';
}else{
	echo 'Mysql Error : '.$con->error;
	echo '\n';
}

$sql2 = "INSERT INTO call_info (call_id, job_id, status)
VALUES ('$call_id', '$job_id','')";
if($con->query($sql2) === true){
	echo 'Call Info Added :'.$call_id;
	echo "\r\n";
}else{
	echo 'Mysql Error : '.$con->error;
	echo "\r\n";
}

$time_elapsed_secs = microtime(true) - $start;
var_dump($time_elapsed_secs);

?>