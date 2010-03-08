<?php

class myTweetLab extends Twitter {

	public function __construct($username, $password) {
		parent::__construct($username, $password);
	}

	public function pbakausAwesomeTwitterRatio($user) {
		$xml = $this->usersShow($user);
		$sxe = new SimpleXMLElement($xml);
		return $sxe->followers_count / $sxe->statuses_count;
	}

	public function twitterListDensity($user, $howMuch) {
		$xml = null;
		for($c = 0; $c < $howMuch; $c++) {
			if($xml == null)
				$cursor = "-1";
			$xml = $this->getListMemberships($user, "cursor=$cursor");
			$domDoc = DOMDocument::loadXML($xml);
			$xpath = new DOMXPath($domDoc);

			$result = $xpath->query("//list");
			$next = "".$xpath->query("//next_cursor")->item(0)->nodeValue;
			for ($i = 0; $i < $result->length; $i++) {
				$name = $result->item($i)->getElementsByTagName("name")->item(0)->nodeValue;
//				$subs = $result->item($i)->getElementsByTagName("member_count")->item(0)->nodeValue;
				$name = trim(strtolower($name));
				if(array_key_exists($name, $sanitized))
					$sanitized["$name"] += 1;
				else
					$sanitized["$name"] = 1;
			}
			$cursor = $next;
		}
		return $sanitized;
	}
	
	public function twitterListDensityHTMLCloud($twitterListDensityArray, $minFontSize = 12, $maxFontSize = 24) {
		$min = $max = $twitterListDensityArray[0];
		$size = $minFontSize;
		$tmpArray = shuffle($twitterListDensityArray);
		$htmlCloud = "";
		foreach($tmpArray as $list) {
			$min = $list <= $min ? $list : $min;
			$max = $list >= $max ? $list : $max;
		}
		foreach($tmpArray as $key => $value) {
			$size = ($tmpDim = ceil( $maxFontSize * ($value - $min) / ($max - $min)) < $minFontSize) ? $minFontSize : $tmpMin;
			$htmlCloud .= "<span style=\"font-size:$size\">$key</span>";
		}
		return $htmlCloud;
	}
}