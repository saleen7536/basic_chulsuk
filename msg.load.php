<?php
include_once('./_common.php');

$q = isset($_REQUEST['q']) ? clean_xss_tags(trim($_REQUEST['q'])) : '';
$mtxt = '';
if($q) {
	$q = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", "", $q);
	if(is_file($board_skin_path.'/msg/'.$q)) {
		$msg = array();
		@include_once($board_skin_path.'/msg/'.$q);
		$msg_cnt = count($msg);

		if($msg_cnt) {
			shuffle($msg); // 섞기
			$mtxt .= $msg[0];
		}
	}
}

echo '{ "msg": "' . $mtxt . '" }';