<?php 

require_once(dirname(__FILE__)."/config.php");

function saytweet($str, $from="")
{
	mb_regex_encoding("UTF-8");
	mb_internal_encoding("UTF-8");

	if (!mb_ereg_match(".*[абвгдежзийклмнопрстуфкцчшщъьюяАБВГДЕЖЗИЙКЛМНОПРСТУФКЦЧШЩЪЬЮЯ].*",$str)) { 
		$lang='en';
		$pref="Hello, this is a tweet from ".$from.", saying ";
	} else {
		$lang='bg';
		$pref="Здравейте, това е туиит от ".$from.", казващ ";
	}

	$pid = time();
	$inw = "/tmp/fn.".$pid.".wav";
	$finished = "/usr/share/asterisk/sounds/en_US_f_Allison/spb.".$pid.".wav";
	$sndname="spb.".$pid;

	$str=preg_replace("/#[a-zA-Z0-9-.абвгдежзийклмнопрстуфкцчшщъьюяАБВГДЕЖЗИЙКЛМНОПРСТУФКЦЧШЩЪЬЮЯ_]*/", "", $str);
	echo "modtext $str\n";

	$fp=popen("espeak --stdin -s 90 -w ".$inw." -v ".$lang, "w");
	fwrite($fp, $pref.$str);
	pclose($fp);
	system("sox ".$inw." -t wav -r 8000 -e signed-integer -c 1 ".$finished);

	/*
	Channel: Local/ll@twspeak
	Context: twspeak
	Extension: $$
	Priority: 1

	*/

	$callfile="Channel: Local/speak@speak\nContext: twspeak\nExtension: $pid\nPriority: 1\n";
	#$callfile="Channel: Local/ll@twspeak\nContext: twspeak\nExtension: $pid\nPriority: 1\n";

	file_put_contents("callfile.".$pid, $callfile);

	system("mv callfile.".$pid." /var/spool/asterisk/outgoing/");
}


while(42) {
	$files = scandir($spath);

	$newfile = false;
	foreach ( $files as $file) {
		if (in_array($file,  array( '.', '..' ) ) ) continue;
		$newfile=true;
		echo "Found $file\n";
		$js = file_get_contents($spath."/".$file);
		$data = json_decode($js);
		echo "saying from ".$data->from." that says ".html_entity_decode($data->text)."\n";
		saytweet(html_entity_decode($data->text), $data->from);
		unlink($spath."/".$file);
		break;
	}
	if (!$newfile) {
		sleep(10);
	} else {
		sleep(60);
	}

	
}

saytweet($argv[2], $argv[1]);
