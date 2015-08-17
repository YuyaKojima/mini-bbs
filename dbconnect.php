<?php
$db = mysqli_connect('localhost','root','root','mini-bbs') or
die(mysql_connect_error());
mysqli_set_charset($db, 'utf8') or die(mysqli_error($db));
 ?>
