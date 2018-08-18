<?php
error_reporting(E_ALL^E_NOTICE);

if (!isset($_GET["url"])) {
	die("参数错误！");
}

$SubURL = $_GET["url"];
$hk = isset($_GET["hk"]) ? $_GET["hk"] : 0;
$us = isset($_GET["us"]) ? $_GET["us"] : 0;
$jp = isset($_GET["jp"]) ? $_GET["jp"] : 0;
$kr = isset($_GET["kr"]) ? $_GET["kr"] : 0;
$sg = isset($_GET["sg"]) ? $_GET["sg"] : 0;
$eng = isset($_GET["eng"]) ? $_GET["eng"] : 0;

$SubText = GetSub($SubURL);
$SubText = BaseDecode($SubText);
$SubText = str_replace('ssr://', '', $SubText);
$Sub = explode("\n", $SubText);

$newSub = "";

foreach($Sub as &$str) {
	if ($str !== '') {
		$str = BaseDecode($str);
		$node = explode("?", $str);
		parse_str($node[1], $nodeInfo);
		$nodeName = BaseDecode($nodeInfo["remarks"]);
		$nodeInfo["group"] = trim(base64_encode("MoCloudPlus"), "=");

		$isAdd = false;
		if (strpos($nodeName, "香港") !== false && $hk == 1) { $isAdd = true; }
		if (strpos($nodeName, "美国") !== false && $us == 1) { $isAdd = true; }
		if (strpos($nodeName, "日本") !== false && $jp == 1) { $isAdd = true; }
		if (strpos($nodeName, "韩国") !== false && $kr == 1) { $isAdd = true; }
		if (strpos($nodeName, "新加坡") !== false && $sg == 1) { $isAdd = true; }

		if ($eng == 1) {
			$nodeName = str_replace("香港", "HK", $nodeName);
			$nodeName = str_replace("日本", "JP", $nodeName);
			$nodeName = str_replace("俄罗斯", "RU", $nodeName);
			$nodeName = str_replace("新加坡", "SG", $nodeName);
			$nodeName = str_replace("美国", "US", $nodeName);
			$nodeName = str_replace("韩国", "KR", $nodeName);
		}

		$nodeName = str_replace(" - 543 端口单端口多用户", "", $nodeName);
		$nodeName = str_replace(" - 587 端口单端口多用户", "", $nodeName);
		$nodeName = str_replace("清真", "", $nodeName);
		$nodeName = str_replace("等级1-", "", $nodeName);
		$nodeName = str_replace("等级2-", "", $nodeName);
		$nodeName = str_replace("等级3-", "", $nodeName);

		if ($isAdd) {
			$nodeInfo["remarks"] = trim(BaseEncode($nodeName), "=");
			$newStr = $node[0] . "?" . http_build_query($nodeInfo);
			$newStr = "ssr://" . trim(BaseEncode($newStr), "=");
			$newSub = $newSub . $newStr . "\n";
		}
	}
}

echo BaseEncode($newSub);

function GetSub($url){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
	$tmpInfo = curl_exec($curl);
	curl_close($curl);
	return $tmpInfo;
}

function BaseDecode($text){
	$text = strtr($text, '-', '+');
	$text = strtr($text, '_', '/');
	$text = strtr($text, ' ', '+');
	$text = base64_decode($text);
	return $text;
}

function BaseEncode($text){
	$text = base64_encode($text);
	$text = strtr($text, '+', '-');
	$text = strtr($text, '/', '_');
	return $text;
}

function GetParam($text){
	$result = array();
	$mr = preg_match_all('/(\?|&)(.+?)=([^&?]*)/i', $text, $matchs);
	if ($mr !== false) {
		for ($i = 0; $i < $mr; $i++) {
			$result[$matchs[2][$i]] = $matchs[3][$i];
		}
	}
	return $result;
}

?>