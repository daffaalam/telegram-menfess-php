<?php

include '.fun.php';

$parameter = array(
	"allowed_updates" => ["message"],
);

echo "<center>";

$data = sendData("/getUpdates", $parameter);

echo "</center>";

echo "<br>";

for ($index = 0; $index < count($data); $index++) {
	$update_id = $data[$index]['update_id'] ?? NULL;
	$message = $data[$index]['message'] ?? NULL;
	$message_date = $message['date'] ?? NULL;
	$message_id = $message['message_id'] ?? NULL;
	$text = $message['text'] ?? NULL;
	$photo = $message['photo'][0]['file_id'] ?? NULL;
	$video = $message['video']['file_id'] ?? NULL;
	$animation = $message['animation']['file_id'] ?? NULL;
	$audio = $message['audio']['file_id'] ?? NULL;
	$caption = $message['caption'] ?? NULL;
	$command = $message['entities'][0]['type'] ?? NULL;
	$bot = $command == 'bot_command';
	$reply_id = 0;
	$reply_key = '-reply!';
	if (!$bot && (isset($text) || isset($photo) || isset($video) || isset($animation) || isset($audio))) {
		$text = addslashes($text);
		if (strpos($text, $reply_key) !== false) {
			$reply = str_replace($table . '/', '', strstr($text, $table . '/'));
			$text = str_replace($reply_key, '', strstr($text, $reply_key));
			$reply = strstr($reply, $reply_key, true);
			$reply_id = intval($reply);
		}
		$insert = "INSERT INTO $table VALUES ($update_id, $message_id, $reply_id, $message_date, '$text', '$photo', '$video', '$animation', '$audio', '$caption', 0)";
		echo $connect -> query($insert) ? "DONE! ($message_id)" : $connect -> error;
		echo "<br>";
	}
}

$connect -> close();

echo "<center>";
echo "<br>";
echo date("Y/m/d");
echo "<br>";
echo date("H:i:s");
echo "</center>";

?>
