<?php


require_once(dirname(__FILE__).'/phirehose/lib/OauthPhirehose.php');
require_once(dirname(__FILE__)."/config.php");



class SampleConsumer extends OauthPhirehose {

	public $savepath;
	public function enqueueStatus($status)
	{
		$data = json_decode($status, true);
		if (is_array($data) && isset($data['user']['screen_name'])) {
			$arr = array("from" => $data['user']['screen_name'], "text" => urldecode($data['text']));
			file_put_contents($this->savepath."/".time(), json_encode($arr));
			print $data['lang'] . ': ' . $data['user']['screen_name'] . ': ' . urldecode($data['text']) . "\n";
		}
	}

}


$sc = new SampleConsumer(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_FILTER);
$sc->savepath=$spath;
$sc->setTrack(array('#openfest2016telefon'));
$sc->consume();
