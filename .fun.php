<?php

include '.config.php';

$table = DB_TABLE;

$connect = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

$connect -> set_charset("utf8mb4");

if ($connect -> connect_error) die("<br>ERROR! " . $connect -> connect_error . "<br>");

function sendData($end_point = "/", $data_json = array()) {
	$url = BASE_URL . BOT_TOKEN . $end_point;
	$data = json_encode($data_json); 
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_POST, TRUE);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data),
	));
	$exec = curl_exec($curl);
	$response = json_decode($exec, TRUE);
	echo $response["ok"] ? "DONE! " : "FAILED! " . $response["description"] . " ";
    curl_close($curl);
    return $response["result"];
}

function dataJson() {
	global $table;
	return array(
		"chat_id" => "@" . $table,
		"parse_mode" => "Markdown",
	);
}

function sendMessage($text, $reply) {
	$data_json = array(
		"text" => $text,
		"reply_to_message_id" => $reply,
	);
	sendData("/sendMessage", array_merge(
		$data_json,
		dataJson(),
	));
}

function sendPhoto($photo, $caption, $reply) {
 	$data_json = array(
		"photo" => $photo,
		"caption" => $caption,
		"reply_to_message_id" => $reply,
	);
	sendData("/sendPhoto", array_merge(
		$data_json,
		dataJson(),
	));
}

function sendVideo($video, $caption, $reply) {
 	$data_json = array(
		"video" => $video,
		"caption" => $caption,
		"reply_to_message_id" => $reply,
	);
	sendData("/sendVideo", array_merge(
		$data_json,
		dataJson(),
	));
}

function sendAnimation($animation, $caption, $reply) {
	$data_json = array(
		"animation" => $animation,
		"caption" => $caption,
		"reply_to_message_id" => $reply,
	);
	sendData("/sendAnimation", array_merge(
		$data_json,
		dataJson(),
	));
}

function sendAudio($audio, $caption, $reply) {
	$data_json = array(
		"audio" => $audio,
		"caption" => $caption,
		"reply_to_message_id" => $reply,
	);
	sendData("/sendAudio", array_merge(
		$data_json,
		dataJson(),
	));
}

?>
