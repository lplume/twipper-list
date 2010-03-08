<?php
include_once "../myTweet/Twitter.class.php";
include_once "../myTweet/myTweetLab.class.php";

$tl = new myTweetLab("lplume", "@moebius85");

$temp = $tl->twitterListDensity("BlackBerry", 20);
echo $tl->twitterListDensityHTMLCloud($temp);