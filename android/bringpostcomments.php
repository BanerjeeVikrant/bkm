<?php
include "connect.php";

// Create connection
$conn = new mysqli($servername, $username1, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
include "../system/helpers.php";

$postid = $_POST['postid'];

$post = $conn->query("SELECT * FROM posts WHERE id='$postid'");
$get_post = $post->fetch_assoc();

$commentsid = $get_post["commentsid"];

$commentsid_array = explode(",", $commentsid);
echo '
{
    "comments": [';
    $i = 0;
$i = 0;
foreach ($commentsid_array as $value) {
	$comment = $conn->query("SELECT * FROM comments WHERE id='$value'");
	$get_comment = $comment->fetch_assoc();

	$body = $get_comment['comment'];
	$body = str_replace("&apos;","'",$body);
    $body = str_replace("&lt;","<",$body);
    $body = str_replace("&gt;",">",$body);
    
	$from_ = $get_comment['from'];

	$query = $conn->query("SELECT * FROM users WHERE id='$from_'");
	$row = $query->fetch_assoc();
	$from = $row['username'];

	if($i == 0){
		echo '
		{
			"body:""'.$body.'",
			"from:""'.$from.'"
		}
		';
		$i++;
	}else{
		echo '
		,{
			"body:""'.$body.'",
			"from:""'.$from.'"
		}
		';
	}
}
echo "
    ]}
";


?>