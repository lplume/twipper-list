<?php
include_once "../myTweet/Twitter.class.php";
include_once "../myTweet/myTweetLab.class.php";

$tl = new myTweetLab("xxx", "xxx");
$temp = $tl->twitterListDensity("BlackBerry", 40);
echo $tl->twitterListDensityHTMLCloud($temp, 5, 12, 120);