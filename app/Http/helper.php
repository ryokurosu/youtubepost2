<?php 

date_default_timezone_set('Asia/Tokyo');
define('WEBHOOK','https://discordapp.com/api/webhooks/446408879463727114/d4vi8pLe7WHA_h0wHbdDa45CoSnvObtjQO31U0DxFHEfiTg1rHG-rdj8OdTuzbBrwX6f');

define('HOOKNAME','Captain Hook');

function postToDiscord($e)
{
	$content = "@everyone ". PHP_EOL.date("Y/m/d H:i:s"). PHP_EOL .\Config::get('app.name').' '.\Config::get('app.url').  PHP_EOL . "```";

	if(isset($_SERVER['REQUEST_URI'])){
		$content .= "[URL]    ".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] . PHP_EOL;
	}else{

	}

	$content .= "[Where]  ".$e->getFile()."：".$e->getLine() . PHP_EOL;

	if(preg_match("/Routing|Builder/", $e->getFile())){
		return false;
	}

	if(isset($_SERVER['HTTP_REFERER'])){
		$content .= "[From]   ".$_SERVER['HTTP_REFERER'] . PHP_EOL;
	}else{

	}

	
	
	$content .= "[Code]   ".$e->getCode() . PHP_EOL;
	$content .= "[Message]".$e->getMessage() . PHP_EOL;
	$content .= "```" . PHP_EOL;
	$data = array("content" => $content, "username" => HOOKNAME);
	$curl = curl_init(WEBHOOK);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_exec($curl);

	if($e->getPrevious()){
		postToDiscord($e->getPrevious());
	}
	return true;
}

function noticeDiscord($content){
	$data = array("content" => date("Y/m/d H:i:s"). PHP_EOL .\Config::get('app.name').' '.\Config::get('app.url').  PHP_EOL ."``` {$content}". PHP_EOL."```", "username" => HOOKNAME);
	$curl = curl_init(WEBHOOK);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	return curl_exec($curl);
}


function makeRandStr($length) {
	$str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
	$r_str = null;
	for ($i = 0; $i < $length; $i++) {
		$r_str .= $str[rand(0, count($str) - 1)];
	}
	return $r_str;
}

?>