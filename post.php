<?php

date_default_timezone_set("Asia/Jakarta");

include 'config.php';

$connect = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$connect -> set_charset("utf8mb4");

if ($connect -> connect_error) die("<br>ERROR! " . $connect -> connect_error . "<br>");

$table = DB_TABLE;

$read = "SELECT * FROM $table WHERE done = 0 LIMIT 1";

$result = $connect -> query($read);

if ($result -> num_rows > 0) {
	while($row = $result -> fetch_assoc()) {
		$update_id = $row['update_id'] ?? NULL;
		$message_id = $row['message_id'] ?? NULL;
		$reply = $row['reply_id'] ?? NULL;
		$reply = $reply == 0 ? NULL : $reply;
		$text = $row['message_text'] ?? NULL;
		$photo = $row['message_photo'] ?? NULL;
		$video = $row['message_video'] ?? NULL;
		$animation = $row['message_animation'] ?? NULL;
		$audio = $row['message_audio'] ?? NULL;
		$caption = $row['message_caption'] ?? NULL;
		$text == "" ?: sendMessage($text, $reply);
		$photo == "" ?: sendPhoto($photo, $caption, $reply);
		$video == "" ?: sendVideo($video, $caption, $reply);
		$animation == "" ?: sendAnimation($animation, $caption, $reply);
		$audio == "" ?: sendAudio($audio, $caption, $reply);
		$update = "UPDATE $table SET done = 1 WHERE message_id = $message_id";
		echo $connect -> query($update) ? "($update_id - $message_id)" : $connect -> error;
		echo "<br>";
	}
}

$connect -> close();

function sendMessage($text, $reply) {
	global $table;
	$end_point = "/sendMessage";
	$data_json = array(
		"chat_id" => "@" . $table,
		"text" => $text,
		"parse_mode" => "Markdown",
		"reply_to_message_id" => $reply,
	);
	sendData($end_point, $data_json);
}

function sendPhoto($photo, $caption, $reply) {
	global $table;
	$end_point = "/sendPhoto";
	$data_json = array(
		"chat_id" => "@".$table,
		"photo" => $photo,
		"caption" => $caption,
		"parse_mode" => "Markdown",
		"reply_to_message_id" => $reply,
	);
	sendData($end_point, $data_json);
}

function sendVideo($video, $caption, $reply) {
	global $table;
	$end_point = "/sendVideo";
	$data_json = array(
		"chat_id" => "@".$table,
		"video" => $video,
		"caption" => $caption,
		"parse_mode" => "Markdown",
		"reply_to_message_id" => $reply,
	);
	sendData($end_point, $data_json);
}

function sendAnimation($animation, $caption, $reply) {
	global $table;
	$end_point = "/sendAnimation";
	$data_json = array(
		"chat_id" => "@".$table,
		"animation" => $animation,
		"caption" => $caption,
		"parse_mode" => "Markdown",
		"reply_to_message_id" => $reply,
	);
	sendData($end_point, $data_json);
}

function sendAudio($audio, $caption, $reply) {
	global $table;
	$end_point = "/sendAudio";
	$data_json = array(
		"chat_id" => "@".$table,
		"audio" => $audio,
		"caption" => $caption,
		"parse_mode" => "Markdown",
		"reply_to_message_id" => $reply,
	);
	sendData($end_point, $data_json);
}

function sendData($end_point, $data_json) {
	$url = BASE_URL . BOT_TOKEN . $end_point;
	$data = json_encode($data_json); 
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data))
	);
	$exec = curl_exec($curl);
	$response = json_decode($exec, TRUE);
	echo $response["ok"] ? "DONE! " : "FAILED! " . $response["description"] . " ";
	curl_close($curl);
}

echo "<center>";
echo "<br>";
echo date("Y/m/d");
echo "<br>";
echo date("H:i:s");
echo "</center>";

?>
