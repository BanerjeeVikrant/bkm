<?php
include "connect.php";
// Create connection
$conn = new mysqli($servername, $username1, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$username = $_POST['u'];
$results = $conn->query("SELECT * FROM users WHERE username='$username'");
$rowget = $results->fetch_assoc();
$usernameid = $rowget['id'];
$usernamegrade = $rowget['grade'];

$post = $_POST['caption'];
$post = str_replace("'","&apos;",$post);
$post = str_replace("<","&lt;",$post);
$post = str_replace(">","&gt;",$post);

if(isset($_POST['image'])){
	$now = DateTime::createFromFormat('U.u', microtime(true));
	$id = $now->format('YmdHisu');
	date_default_timezone_set("America/Los_Angeles");
	$date_added = date("Y/m/d");
	$added_by = $username;
	$time_added = time();

	$upload_folder = "../userdata/pictures/$usernameid";
	if (!file_exists("../userdata/pictures/$usernameid")){
		mkdir("../userdata/pictures/$usernameid");
		mkdir("../userdata/pictures/$usernameid/thumbnail");
	}
	$path = "../userdata/pictures/$usernameid/$id.jpg";
	$image = $_POST['image'];

	$ext='jpeg';
    $data = base64_decode( $image );

    file_put_contents($path, $data );
	
	$sql = "INSERT INTO posts VALUES ('', '$post', '$date_added', '$time_added', '$usernameid', '0', '', '', '', 'userdata/pictures/$usernameid/$id.jpg', '', '$usernamegrade', '0', '', '', '0')";

	if ($conn->query($sql) === TRUE) {
		$response["success"] = true;  
		echo json_encode($response);
	}else{
		$response["success"] = false;  
		echo json_encode($response);
	}

}else{
	echo "image_not_in";
	exit;
}

?>
