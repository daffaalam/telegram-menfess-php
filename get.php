<?php

date_default_timezone_set("Asia/Jakarta");

include 'config.php';

$curl = curl_init();

$get_updates = BASE_URL . BOT_TOKEN . "/getUpdates";

curl_setopt($curl, CURLOPT_URL, $get_updates);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

$exec = curl_exec($curl);

$response = json_decode($exec, TRUE);

curl_close($curl);

$data = $response["result"];

$connect = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$connect -> set_charset("utf8mb4");

if ($connect -> connect_error) die("<br>ERROR! " . $connect -> connect_error . "<br>");

$table = DB_TABLE;
$table_user = DB_TABLE_USER;

for ($index = 0; $index < count($data); $index++) {
	$update_id = $data[$index]['update_id'] ?? NULL;
	$message_id = $data[$index]['message']['message_id'] ?? NULL;
	$text = $data[$index]['message']['text'] ?? NULL;
	$photo = $data[$index]['message']['photo'][0]['file_id'] ?? NULL;
	$video = $data[$index]['message']['video']['file_id'] ?? NULL;
	$animation = $data[$index]['message']['animation']['file_id'] ?? NULL;
	$audio = $data[$index]['message']['audio']['file_id'] ?? NULL;
	$caption = $data[$index]['message']['caption'] ?? NULL;
	$command = $data[$index]['message']['entities'][0]['type'] ?? NULL;
	$bot = $command == 'bot_command';
	/*
	$from_id = $data[$index]['message']['from']['id'] ?? NULL;
	$from_bot = $data[$index]['message']['from']['is_bot'] ?? NULL;
	$from_username = "@" . $data[$index]['message']['from']['username'] ?? NULL;
	*/
	$reply_id = 0;
	$reply_key = '-reply!';
	if (!$bot && (isset($text) || isset($photo) || isset($video) || isset($animation) || isset($audio))) {
		$text = addslashes($text);
		if (strpos($text, $reply_key) !== false) {
			$reply = strstr($text, $table . '/');
			$reply = str_replace($table . '/', '', $reply);
			$text = str_replace($reply_key, '', strstr($text, $reply_key));
			$reply = strstr($reply, $reply_key, true);
			$reply_id = intval($reply);
		}
		$insert = "INSERT INTO $table VALUES ($update_id, $message_id, $reply_id, '$text', '$photo', '$video', '$animation', '$audio', '$caption', 0)";
		echo $connect -> query($insert) ? "DONE! ($message_id)" : $connect -> error;
		echo "<br>";
	}
	/*
	} else if ($bot && $text == '/start' && !$from_bot) {
		$insert = "INSERT INTO $table_user VALUES ($from_id, '$from_username')";
		echo $connect -> query($insert) ? "DONE! ($message_id)" : $connect -> error;
		echo "<br>";
	}
	*/
}

$connect -> close();

echo "<center>";
echo "<br>";
echo date("Y/m/d");
echo "<br>";
echo date("H:i:s");
echo "</center>";

?>
