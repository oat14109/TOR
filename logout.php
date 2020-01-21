<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
include 'includes/function.php';

$ip=@$REMOTE_ADDR;
$tm=date("Y-m-d H:i:s");

$query="insert into tor.log(username,ip, log_name, log_date)
		values('".$_SESSION['USER_TOR']."','".$ip."','ออกจากระบบ','".$tm."')";
$result_query=mysql_query($query);

unset($_SESSION['USER_TOR']);
ob_end_flush();
// setcookie('username_hr');

header("Location:index.php");
?>
