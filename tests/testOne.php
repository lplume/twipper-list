<?php

if($argc < 3) {
	echo "tell me: ".$argv[0]." brand howMuchPages user:password\n";
	exit;
}


$user = $argv[1];
$howMany = $argv[2];
$auth = $argv[3];
$format = "xml";

$base_url = "http://api.twitter.com/1/";
$next = "-1";
$sanitized = array();
$cookie_file = tempnam ("/tmp", "COOKIE");
echo "Fetching page = ";
for($k = 0; $k < $howMany; $k++) {
	echo ($k+1)." ";
	$subscriber = $base_url ."$user/lists/memberships.xml?cursor=$next";

	$ch = curl_init($subscriber);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookie_file);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, "$auth");

	$xml = curl_exec($ch);
	curl_close($ch);
	$domDoc = DOMDocument::loadXML($xml);
	$xpath = new DOMXPath($domDoc);



	$result = $xpath->query("//list");
	$next = "".$xpath->query("//next_cursor")->item(0)->nodeValue;

	for ($i = 0; $i < $result->length; $i++) {
		$name = $result->item($i)->getElementsByTagName("name")->item(0)->nodeValue;
		$subs = $result->item($i)->getElementsByTagName("member_count")->item(0)->nodeValue;
//		$fname =  $result->item($i)->getElementsByTagName("full_name")->item(0)->nodeValue;
		$name = trim(strtolower($name));
		if(array_key_exists($name, $sanitized))
		$sanitized["$name"] += 1;
		else
		$sanitized["$name"] = 1;
	}
}

unlink($cookie_file);

echo "\n***Results***\n";

foreach ($sanitized as $list => $subs)
echo "$list :: $subs\n";