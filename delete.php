<?php

include '.fun.php';

$oldDate = strtotime("-7 days");

$read = "SELECT * FROM $table WHERE message_date < $oldDate";

$result = $connect -> query($read);

if ($result -> num_rows > 0) {
	while($row = $result -> fetch_assoc()) {
        $update_id = $row['update_id'] ?? NULL;
        $message_id = $row['message_id'] ?? NULL;
        $delete = "DELETE FROM $table WHERE update_id = $update_id";
        echo $message_id . " - ";
		echo $connect -> query($delete) ? "DELETED" : $connect -> error;
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
