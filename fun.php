<?php

function sendData($end_point = "", $data_json = array()) {
	$url = BASE_URL . BOT_TOKEN . $end_point;
	$data = json_encode($data_json); 
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data)),
	);
	$exec = curl_exec($curl);
	$response = json_decode($exec, TRUE);
	echo $response["ok"] ? "DONE! " : "FAILED! " . $response["description"] . " ";
    curl_close($curl);
    return $response["result"];
}

?>
