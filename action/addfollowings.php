<?php 
require "../system/connect.php"; 

session_start();
if (isset($_SESSION['user_login'])) {
	$username = $_SESSION['user_login'];
}
else{
	$username = "";
}

if (isset($_GET['addto'])) {
	$addto = $_GET['addto'];
}

$check = $conn->query("SELECT * FROM users WHERE username='$username'");
if ($check->num_rows == 1) {

	$get = $check->fetch_assoc();

	$fromId = $get['id'];
	$following = $get['following'];

}
$check = $conn->query("SELECT * FROM users WHERE username='$addto'");
if ($check->num_rows == 1) {

	$get = $check->fetch_assoc();

	$toId = $get['id'];
	$followers = $get['followers'];

}


if($following == "" || $following == NULL){
	$sqlcommand = $conn->query("UPDATE users SET following='$addto' WHERE username='$username'");
}
else{
	$addedList = $following . "," . $addto;
	$sqlcommand = $conn->query("UPDATE users SET following='$addedList' WHERE username='$username'");
}

if($followers == "" || $followers == NULL){
	$sqlcommand = $conn->query("UPDATE users SET followers='$username' WHERE username='$addto'");
}
else{
	$addedList = $followers . "," . $username;
	$sqlcommand = $conn->query("UPDATE users SET followers='$addedList' WHERE username='$addto'");
}
date_default_timezone_set("America/Los_Angeles");
$date_added = date("Y/m/d");
$time_added = time(); 

$check = $conn->query("SELECT * FROM notifications WHERE (toUser='$addto' AND fromUser='$username')");
if ($check->num_rows == 0) {
	$query = $conn->query("INSERT INTO notifications VALUES ('', '1', '$username', '$addto', '', '', '$time_added', '$date_added')");
}

?>