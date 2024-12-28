<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 개근 포인트 삭제
if (!delete_point($row['mb_id'], $bo_table, $row['wr_id'], '개근'))
	insert_point($row['mb_id'], (int)$row['wr_9'] * (-1), "출석 개근 포인트 삭제");

?>