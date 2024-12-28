<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$list_skin_url.'/list.css" media="screen">', 0);

$is_asc = (isset($boset['asc']) && $boset['asc']) ? true : false;
$z = 0;
$j = 1;
for ($i=0; $i < $list_cnt; $i++) { 

	if($notice_count > 0 && in_array($list[$i]['wr_id'], $arr_notice)) continue; //공지는 통과

	$z++;

	// 포인트
	$wr_point = number_format((int)$list[$i]['wr_8'] + (int)$list[$i]['wr_9']);

	// 개근
	$wr_attend = (int)$list[$i]['wr_10'];
	$wr_attend = ($wr_attend) ? number_format($wr_attend) : 1;

	// 출석시간
	if($is_asc) {
		$list[$i]['num'] = ($page - 1) * $page_rows + $j;
		$j++;
	} 

	// 포토
	$img = apms_wr_thumbnail($bo_table, $list[$i], 50, 50, false, true); // 썸네일
	if($img['src']) {
		$wr_photo = '<img src="'.$img['src'].'">';
	} else if($list[$i]['as_icon']) {
		$wr_photo = apms_fa(apms_emo($list[$i]['as_icon']));
	} else {
		$mb_photo = apms_photo_url($list[$i]['mb_id']);
		$wr_photo = ($mb_photo) ? '<img src="'.$mb_photo.'">' : $fa_photo;
	}
?>
	<div class="talk-box-wrap">
		<div class="pull-left text-muted text-center">
			<span class="talker-photo"><?php echo $wr_photo;?></span>
			<p style="margin:5px 0px 0px;"><?php echo $list[$i]['num'];?>등</p>
			<?php if ($is_checkbox) { ?>
				<input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
			<?php } ?>
		</div>
		<div class="talk-box talk-right">
			<div class="talk-bubble">
				<div class="info text-muted ellipsis">
					<span class="sp"><b><?php echo $list[$i]['name'] ?></b></span>
					<span class="sp sp-none"><i class="fa fa-clock-o"></i> <?php echo date("H:i", $list[$i]['date']);?></span>
					<?php if($list[$i]['wr_comment']) { ?>
						<span class="sp"><i class="fa fa-comment"></i> <?php echo $list[$i]['wr_comment'];?></span>
					<?php } ?>
					<span class="sp"><i class="fa fa-gift"></i> <b class="orangered"><?php echo $wr_point;?></b></span>
					<span class="sp sp-none"><i class="fa fa-trophy"></i> <?php echo $wr_attend;?>일</span>
				</div>

				<div class="talk">
					<a href="<?php echo $list[$i]['href'];?>"<?php echo $is_modal_js;?>>
						<?php echo apms_get_text($list[$i]['wr_content']);?>
					</a>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
