<?php

include '.fun.php';

$read = "SELECT * FROM $table WHERE done = 0 LIMIT 1";

$result = $connect -> query($read);

if ($result -> num_rows > 0) {
	while($row = $result -> fetch_assoc()) {
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
		echo $connect -> query($update) ? "($message_id)" : $connect -> error;
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
