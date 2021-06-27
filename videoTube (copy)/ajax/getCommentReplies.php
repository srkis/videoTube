<?php
require_once("../includes/config.php");
require_once("../includes/classes/Comment.php");
require_once("../includes/classes/User.php");

$username = isset($_SESSION["userLoggedIn"]) ? $_SESSION["userLoggedIn"] : null;
$videoId =  $_POST['videoId'];
$commentId =  $_POST['commentId'];

$userLoggedInObj = new User($con, $username);
$comment = new Comment($con, $commentId, $userLoggedInObj, $videoId);

echo $comment->getReplies();
