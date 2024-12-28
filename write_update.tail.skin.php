<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(!$notice && $w != 'u') { //글수정이 아닐 때 작동

	// 순위 - wr_7
	$wr_7 = $chulsuk_rank + 1;

	// 출석포인트 - wr_8
	$wr_8 = $board['bo_write_point'];

	// 전날 구하기
    $yesterday = getdate(G5_SERVER_TIME - 86400);
    $seldate = $yesterday['year'].sprintf("%02d",$yesterday['mon']).sprintf("%02d",$yesterday['mday']);
 
    // 전날 출석
    if($is_member) { // 회원이면 mb_id로 체크
        $row = sql_fetch("select wr_id, wr_10 from $write_table where mb_id = '{$member['mb_id']}' and wr_is_comment = '0' and wr_1 = '{$seldate}' "); 
    } else { // 비회원이면 ip로 체크
        $row = sql_fetch("select wr_id, wr_10 from $write_table where wr_ip = '{$_SERVER['REMOTE_ADDR']}' and wr_is_comment = '0' and wr_1 = '{$seldate}' "); 
    }

	// 개근 - wr_10
	if($row['wr_id']) { //출석했을 경우
        $wr_10 = (int)$row['wr_10'] + 1; //전날 개근일수에 1일 더함
    } else { //아닐 경우 오늘 출석만 반영
        $wr_10 = 1; 
    }

	// 개근포인트 - wr_9
	$attend = array();
	$tmp = explode("\n", $boset['attend']);
	$tmp_cnt = count($tmp);
	$j = 0;
	for($i=0; $i < $tmp_cnt; $i++) {
		list($aday, $apoint, $abonus, $adice) = explode(",", $tmp[$i]);

		if(!(int)$aday || !(int)$apoint) continue;

		$attend[$j]['day'] = $aday;
		$attend[$j]['point'] = $apoint;
		$attend[$j]['bonus'] = $abonus;
		$attend[$j]['dice'] = $adice;
		$j++;
	}

	$wr_9 = 0;
	if($j) {
		$attend = apms_sort($attend, 'day', true);
		$attend_cnt = count($attend);
		for($i=0; $i < $attend_cnt; $i++) {
			if($wr_10 >= $attend[$i]['day']) {
				$wr_9 = $attend[$i]['point'] + apms_chulsuk_lucky($attend[$i]['bonus'], $attend[$i]['dice']);
				break;
			}
		}
	}

    // 개근 포인트 부여
    insert_point($member['mb_id'], $wr_9, "출석 개근 포인트", $bo_table, $wr_id, '개근');

	// 기록하기
    sql_query(" update $write_table set wr_7 = '{$wr_7}', wr_8 = '{$wr_8}', wr_9 = '{$wr_9}', wr_10 = '{$wr_10}' where wr_id = '$wr_id' ");
}

@include_once($write_skin_path.'/write_update.tail.skin.php');

// 목록으로 이동하기
if ($file_upload_msg)
	alert($file_upload_msg, G5_HTTP_BBS_URL.'/board.php?bo_table='.$bo_table.'&amp;year='.$selyear.'&amp;month='.$selmonth.'&amp;day='.$selday);
else
	goto_url(G5_HTTP_BBS_URL.'/board.php?bo_table='.$bo_table.'&amp;year='.$selyear.'&amp;month='.$selmonth.'&amp;day='.$selday);

?>