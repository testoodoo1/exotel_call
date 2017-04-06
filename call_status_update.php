<?php

$link = new mysqli('localhost','root','1projectK!','oodoo_current');
if(!$link){
	die('mysql could not connected: '.mysql_error());
}

$url = 'http://vcapi.oodoo.co.in/';
$username = 'oodoo';
$password = 'fFPh9n9Tqz4ybQtBPwj8wNhZB5sts8YE';


$sql = "SELECT call_id FROM call_info WHERE status = ''";
$result = mysqli_query($link, $sql);


if (!$result) {
    echo 'MySQL Error: ' . mysqli_error();
    exit;
}
while ($row = mysqli_fetch_assoc($result)) {
	$call_id = $row['call_id'];
	$post_data = array(
		'Method' => 'CallStatus',
		'CallID' => $call_id
	);
	$cha = curl_init();
	curl_setopt($cha, CURLOPT_VERBOSE, 1);
	curl_setopt($cha, CURLOPT_URL, $url);
	curl_setopt($cha, CURLOPT_POST, 1);
	curl_setopt($cha, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($cha, CURLOPT_FAILONERROR, 0);
	curl_setopt($cha, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($cha, CURLOPT_POSTFIELDS, http_build_query($post_data));
	curl_setopt($cha, CURLOPT_USERPWD, "$username:$password");

	$http_result = curl_exec($cha);
	var_dump($http_result); die;
	$error = curl_error($cha);
	$http_code = curl_getinfo($cha ,CURLINFO_HTTP_CODE);
	curl_close($cha);
	$decode = json_decode($http_result, true);
	var_dump($decode);
	$callid = $decode[0]['callid'];
	$phonenumber = $decode[0]['phonenumber'];
	$callstarttime = date("Y-m-d H:i:s", strtotime($decode[0]['callstarttime']));
	$answeredcallduration = $decode[0]['answeredcallduration'];
	$callanswertime = date("Y-m-d H:i:s", strtotime($decode[0]['callanswertime']));
	$callendtime = date("Y-m-d H:i:s", strtotime($decode[0]['callendtime']));
	$totalcallduration = $decode[0]['totalcallduration'];
	$status = $decode[0]['status']; 
	//var_dump($callstarttime, $callanswertime, $callendtime); die;
	$blegcallid = $decode[0]['blegcallid'];
	if(!array_key_exists('status', $decode)) {
		if($answeredcallduration != 0){
			$sql2 = "INSERT INTO calls_status (call_id, phone_number, call_start_time, call_answer_time, call_end_time, total_call_duration, answered_call_duration, status, bleg_call_id)
			VALUES ('$callid','$phonenumber','$callstarttime', '$callanswertime', '$callendtime','$totalcallduration','$answeredcallduration','$status','$blegcallid')";
			$insert_res = mysqli_query($link, $sql2) or die(mysqli_error($link));
		}else{
			$sql2 = "INSERT INTO calls_status (call_id, phone_number, call_start_time, call_end_time, total_call_duration, answered_call_duration, status, bleg_call_id)
			VALUES ('$callid','$phonenumber','$callstarttime', '$callendtime','$totalcallduration','$answeredcallduration','$status','$blegcallid')";
			$insert_res = mysqli_query($link, $sql2) or die(mysqli_error($link));	

			$sql3 = "UPDATE call_info SET status = '$status' WHERE call_id = '$callid'";
			mysqli_query($link, $sql3) or die(mysqli_error($link));
		}

	}else{
		echo "Call ID not found";
	}
}