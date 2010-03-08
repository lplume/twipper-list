<?php
include_once "../myTweet/Twitter.class.php";

$t = new Twitter("xxx", "xxx");

$xml = null;
$sanitized = array();
//echo "Fetching page : ";

$rls = $t->rateLimitStatus();

echo $rls."\n\n";

for($c = 0; $c < $argv[1]; $c++) {
	//echo " *".($c+1);
	if($xml == null)
		$cursor = "-1";
	$xml = $t->getListMemberships("BlackBerry", "cursor=$cursor");
	$domDoc = DOMDocument::loadXML($xml);
	$xpath = new DOMXPath($domDoc);

	$result = $xpath->query("//list");
	$next = "".$xpath->query("//next_cursor")->item(0)->nodeValue;
for ($i = 0; $i < $result->length; $i++) {
		$name = $result->item($i)->getElementsByTagName("name")->item(0)->nodeValue;
		$subs = $result->item($i)->getElementsByTagName("member_count")->item(0)->nodeValue;
		$name = trim(strtolower($name));
		if(array_key_exists($name, $sanitized))
			$sanitized["$name"] += 1;
		else
			$sanitized["$name"] = 1;
	}
	$cursor = $next;
}

//echo "\n***Results***\n";

foreach ($sanitized as $list => $subs)
echo "$list :: $subs\n";
