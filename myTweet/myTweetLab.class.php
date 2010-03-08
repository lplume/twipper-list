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
		$sanitized = array();
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
	
	public function twitterListDensityHTMLCloud($twitterListDensityArray, $skip = 1, $minFontSize = 12, $maxFontSize = 24) {
		$min = $max = $twitterListDensityArray[0];
		$size = $minFontSize;
		$htmlCloudStr = "";
		foreach($twitterListDensityArray as $list) {
			$min = $list <= $min ? $list : $min;
			$max = $list >= $max ? $list : $max;
		}
		foreach($twitterListDensityArray as $key => $value) {
			if($value <= $skip) continue;
			$tmpDim = ceil( $maxFontSize * ($value - $min) / ($max - $min));
			$size = $tmpDim < $minFontSize ? $minFontSize : $tmpDim;
			$htmlCloudStr .= "<span style=\"font-size:".$size."px\">$key</span> ";
		}
		return $htmlCloudStr;
	}
}