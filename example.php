<?php
require "thumb.php";

$arr = Thumb('teste.jpg', 300, 300, 1);

//success
if($arr["status"] == 'success'){
	echo '<img src="'.$arr['src'].'" />';
}
//error
else {
	echo $arr['msg'];
}
?>