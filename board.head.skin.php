<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 값정리
$sca_qstr = ($qstr) ? '&amp;'.$qstr : '';

$year = (isset($year) && $year) ? preg_replace('/[^0-9_]/i', '', trim($year)) : 0;
$month = (isset($month) && $month) ? preg_replace('/[^0-9_]/i', '', trim($month)) : 0;
$day = (isset($day) && $day) ? preg_replace('/[^0-9_]/i', '', trim($day)) : 0;

$today = getdate();
$b_year = $today['year'];
$b_mon = $today['mon'];
$b_day = $today['mday'];

if($year && $month && $day) {
	;
} else {
  $year = $b_year;
  $month = $b_mon;
  $day = $b_day;
}

// 날짜
$nowday = $b_year.sprintf("%02d",$b_mon).sprintf("%02d",$b_day);
$selday = $year.sprintf("%02d",$month).sprintf("%02d",$day);

$lastday=array(0,31,28,31,30,31,30,31,31,30,31,30,31);
if($year%4 == 0) $lastday[2] = 29;
$dayoftheweek = date("w", mktime(0,0,0,$month,1,$year));

if($month == 1) { 
	$year_prev = $year - 1;
	$month_prev = 12;
	$year_next = $year;
	$month_next = $month + 1;
} else if($month == 12) { 
	$year_prev = $year; 
	$month_prev = $month - 1;
	$year_next = $year + 1;
	$month_next = 1;
} else {
	$year_prev = $year; 
	$month_prev = $month - 1;
	$year_next = $year;
	$month_next = $month + 1;
}

$qstr .= '&amp;year='.$year.'&amp;month='.$month.'&amp;day='.$day;

// 출석제한
$is_chulsuk_limit = false;
$boset['stime'] = (isset($boset['stime'])) ? $boset['stime'] : 0;
$boset['etime'] = (isset($boset['etime'])) ? $boset['etime'] : 0;
if($boset['stime'] && $boset['etime']) {
	if($boset['stime'] <= $today['hours'] && $today['hours'] <= $boset['etime']) {
		;
	} else {
		$is_chulsuk_limit = true;
	}
}

// SQL 추가구문
$sql_apms_where .= "and wr_1 = '{$selday}'"; // 오늘날짜 글만 출력
if(isset($boset['asc']) && $boset['asc']) 
	$sql_apms_orderby .= "wr_id asc,"; // 역순으로 출력

if ($sca || $stx) { // 분류 또는 검색일 때는 통과
	;
} else {
	$row = sql_fetch(" select count(*) as cnt from $write_table where wr_is_comment = 0 and wr_1 = '{$selday}'");
	$board['bo_count_write'] = $row['cnt'];
}

// 버튼컬러
$btn1 = (isset($boset['btn1']) && $boset['btn1']) ? $boset['btn1'] : 'black';
$btn2 = (isset($boset['btn2']) && $boset['btn2']) ? $boset['btn2'] : 'color';

// 보드상단출력
$is_bo_content_head = false;

?>