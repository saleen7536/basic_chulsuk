<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 출석 럭키포인트
function apms_chulsuk_lucky($point, $dice){

	if($point > 0) {
		if($dice > 1) {
			$dice1 = rand(1, $dice);
			$dice2 = rand(1, $dice);
			if($dice1 == $dice2) {
				$point = rand(1, $point);
			} else {
				$point = 0;
			}
		} else {
			$point = rand(1, $point);
		}
	} else {
		$point = 0;
	}

	return $point;
}

// 값정리
$wr_1 = (isset($wr_1) && $wr_1) ? $wr_1 : '';
$notice = (isset($notice) && $notice) ? $notice : '';
$is_subject = (isset($is_subject) && $is_subject) ? $is_subject : '';
$boset['stime'] = (isset($boset['stime']) && $boset['stime']) ? $boset['stime'] : 0;
$boset['etime'] = (isset($boset['etime']) && $boset['etime']) ? $boset['etime'] : 0;
$boset['point'] = (isset($boset['point']) && $boset['point']) ? $boset['point'] : 0;
$boset['minus'] = (isset($boset['minus']) && $boset['minus']) ? $boset['minus'] : 0;
$boset['newgul'] = (isset($boset['newgul']) && $boset['newgul']) ? $boset['newgul'] : 0;

$today = getdate();
$nowday = $today['year'].sprintf("%02d",$today['mon']).sprintf("%02d",$today['mday']);

if(!$is_admin && $w == '' && $nowday != $wr_1) {
	alert('출석할 수 없는 날입니다.');
}

$seldate = ($wr_1) ? $wr_1 : $nowday;
$selyear = (int)substr($seldate,0,4);
$selmonth = (int)substr($seldate,4,2);
$selday = (int)substr($seldate,6,2);

$chulsuk_rank = 0;
if(!$notice && $w != 'u') { //공지가 아니고 글수정이 아니면
	// 등록여부 체크
    if($is_member) { // 회원이면 mb_id로 체크
        $row = sql_fetch("select count(*) as cnt from $write_table where mb_id = '{$member['mb_id']}' and wr_is_comment = '0' and wr_1 = '{$wr_1}' "); 
    } else { // 비회원이면 ip로 체크
        $row = sql_fetch("select count(*) as cnt from $write_table where wr_ip = '{$_SERVER['REMOTE_ADDR']}' and wr_is_comment = '0' and wr_1 = '{$wr_1}' "); 
    }

	if($row['cnt'])
		alert('이미 출석하셨습니다.');

	if($boset['stime'] && $boset['etime']) {
		if($boset['stime'] <= $today['hours'] && $today['hours'] <= $boset['etime']) {
			;
		} else {
			alert('출석은 매일 '.$boset['stime'].'시부터 '.$boset['etime'].'시까지 가능합니다.');
		}
	}

	// 포인트 지급
	$board['bo_notice'] = trim($board['bo_notice']);
	$notice_query = ($board['bo_notice']) ? "and find_in_set(wr_id, '{$board['bo_notice']}')=0" : ""; //공지글 제외
	$row = sql_fetch("select count(*) as cnt from $write_table where wr_is_comment = '0' and wr_1 = '{$wr_1}' $notice_query ");
	$chulsuk_rank = $row['cnt'];
	$chk_point = (int)$boset['point'] - ((int)$boset['minus'] * $chulsuk_rank);
	$board['bo_write_point'] = ($chk_point > $board['bo_write_point']) ? $chk_point : $board['bo_write_point'];
	if(isset($boset['bpoint']) && $boset['bpoint'] > 0 && isset($boset['bdice']) && $boset['bdice'] > 0) {
		$board['bo_write_point'] = $board['bo_write_point'] + apms_chulsuk_lucky($boset['bpoint'], $boset['bdice']);
	}
}

// 새글등록 체크
if(!$notice && $boset['newgul']) {
	$is_new = false;
}

$boset['write_skin'] = (isset($boset['write_skin']) && $boset['write_skin']) ? $boset['write_skin'] : 'basic';
$write_skin_url = $board_skin_url.'/write/'.$boset['write_skin'];
$write_skin_path = $board_skin_path.'/write/'.$boset['write_skin'];

// 간단쓰기 제목처리
if($w == '' && isset($is_subject) && $is_subject) {
	$wr_subject = apms_cut_text($wr_content, 30); // 글내용 30자 자르기
}

// 글수정시
if($w == 'u') {
	$wr_1 = $wr['wr_1'];
	$wr_7 = $wr['wr_7'];
	$wr_8 = $wr['wr_8'];
	$wr_9 = $wr['wr_9'];
	if($is_admin && isset($modify_gaegun) && $modify_gaegun) {
		; //개근일 수정
	} else {
		$wr_10 = $wr['wr_10'];
	}
}

@include_once($write_skin_path.'/write_update.head.skin.php');
?>