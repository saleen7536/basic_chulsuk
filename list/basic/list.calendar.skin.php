<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

//날짜선택기
apms_script('datepicker');

?>

<div class="clearfix"></div>
<?php if($is_calendar) { //달력이 있으면 ?>
	<div class="pull-left hidden-xs">
		<h3 class="no-margin">
			<i class="fa fa-calendar-check-o"></i> 
			<?php echo $year;?>.<?php echo sprintf("%02d",$month);?>.<?php echo sprintf("%02d",$day);?>
			<?php if($total_count > 0) { ?>
				<span class="font-13 en orangered">+ <?php echo number_format($total_count);?></span>
				&nbsp;
			<?php } ?>
		</h3>
	</div>
	<div class="pull-right">
<?php } else { ?>
	<div class="text-center">
<?php } ?>
	<form class="form-inline no-margin">
		<span class="input-group input-group-sm date" id="chulsuk_datepicker">
			<span class="input-group-addon">
				<span class="fa fa-calendar-check-o fa-lg"></span>
			</span>
			<input type="text" class="form-control input-sm" id="chulsuk_datepicker2">
			<span class="input-group-btn">
				<a role="button" class="btn btn-gray btn-sm" href="<?php echo G5_BBS_URL;?>/board.php?bo_table=<?php echo $bo_table;?>&amp;year=<?php echo $year_prev;?>&amp;month=<?php echo $month_prev;?>&amp;day=1<?php echo $sca_qstr;?>">
						<i class="fa fa-angle-left fa-lg"></i>
				</a>
				<a role="button" class="btn btn-gray btn-sm" href="<?php echo G5_BBS_URL;?>/board.php?bo_table=<?php echo $bo_table;?>&amp;year=<?php echo $year_next;?>&amp;month=<?php echo $month_next;?>&amp;day=1<?php echo $sca_qstr;?>">
					<i class="fa fa-angle-right fa-lg"></i>
				</a>
				<?php if($is_calendar) { //달력이 있으면 ?>
					<a class="btn btn-gray btn-sm" data-toggle="modal" data-target="#chulsukModal">
						<i class="fa fa-registered fa-lg at-tip" data-original-title="<nobr>출석정책</nobr>" data-toggle="tooltip" data-placement="top" data-html="true"></i>
					</a>
				<?php } ?>
			</span>
		</span>
	</form>
	<div class="h15"></div>
	<script type="text/javascript">
		function chulsuk_list() {
			var url;
			var selDate = $("#chulsuk_datepicker2").val();
			var strDate = selDate.split('-');

			if(strDate[1].substr(0,1) == "0") {
				strDate[1] = strDate[1].substr(1,1);
			}

			if(strDate[2].substr(0,1) == "0") {
				strDate[2] = strDate[2].substr(1,1);
			}

			url = g5_bbs_url + '/board.php?bo_table=' + g5_bo_table + '&year=' + strDate[0] + '&month=' + strDate[1] + '&day=' + strDate[2];

			if(g5_sca) 
				url += '&amp;sca=' + encodeURIComponent(g5_sca);

			document.location.href = url;
		}

		$(function () {
			$('#chulsuk_datepicker').datetimepicker({
				dayViewHeaderFormat: "YYYY년 MMMM",
				defaultDate: "<?php echo $year;?>-<?php echo sprintf("%02d",$month);?>-<?php echo sprintf("%02d",$day);?>",
				format: 'YYYY-MM-DD',
				locale: 'ko'
			});

			$('#chulsuk_datepicker').on("dp.change",function (e) {
				chulsuk_list();
			});

		});
	</script>
</div>

<div class="clearfix"></div>

<div class="table-responsive">
	<table class="table table-bordered">
	<tr class="active">
		<th class="text-center red">일요일</th>
		<th class="text-center">월요일</th>
		<th class="text-center">화요일</th>
		<th class="text-center">수요일</th>
		<th class="text-center">목요일</th>
		<th class="text-center">금요일</th>
		<th class="text-center blue">토요일</th>
	</tr>
	<?php
		$cnday = array();
		$myday = array();
		$cday = 1;
		$sel_mon = sprintf("%02d",$month);
		$now_month = $year.$sel_mon;
		$sca_sql = ($sca) ? "and ca_name = '".$sca."'" : "";
		$result = sql_query("select * from $write_table where wr_is_comment = '0' and left(wr_1,6) = '{$now_month}' $sca_sql order by wr_id asc");
		while ($row = sql_fetch_array($result)) {

			$sday = (substr($row['wr_1'],0,6) <  $now_month) ? 1 : substr($row['wr_1'],6,2);
			$sday= (int)$sday;

			if(!$cnday[$sday]) $cnday[$sday] = 0;
			$cnday[$sday]++;

			if(!$myday[$sday]) $myday[$sday] = 0;
			if($is_member && $member['mb_id'] == $row['mb_id']) {
				$myday[$sday]++;
			}
		}

		$temp = 7 - (($lastday[$month]+$dayoftheweek)%7);

		if($temp == 7) $temp = 0;
			
		$lastcount = $lastday[$month]+$dayoftheweek + $temp;

		for ($iz = 1; $iz <= $lastcount; $iz++) {

			if($day) {
				$is_today = ($day == $cday) ? true : false;
			} else {
				$is_today = ($b_year == $year && $b_mon == $month && $b_day == $cday) ? true : false;
			}

			$daytext = ($is_today) ? '<b>'.$cday.'</b>' : $cday;

			$daycolor = '';
			if($iz%7 == 1) {
				echo '<tr>'.PHP_EOL;
				$daycolor = ' red';
			} else if($iz%7 == 0) {
				$daycolor = ' blue';
			} 

			if($dayoftheweek < $iz && $iz <= $lastday[$month]+$dayoftheweek) {

			?>
				<td class="en<?php echo ($is_today) ? ' active' : '';?>">
					<a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=<?php echo $bo_table;?>&amp;year=<?php echo $year;?>&amp;month=<?php echo $month;?>&amp;day=<?php echo $cday;?><?php echo $sca_qstr;?>&amp;page=1">
						<?php if($cnday[$cday]) { ?>
							<?php if($myday[$cday]) { ?>
								<span class="pull-right green">
									&nbsp; <?php echo number_format($cnday[$cday]);?>
								</span>
							<?php } else { ?>
								<span class="pull-right red">
									&nbsp; <?php echo number_format($cnday[$cday]);?>
								</span>
							<?php } ?>
						<?php } ?>
						<span class="en<?php echo $daycolor;?>">
							<?php echo $daytext;?>
						</span>
					</a>
				</td>
			<?php
				$cday++;
			} else { 
				echo '<td></td>'.PHP_EOL; 
			}

			if($iz%7 == 0) echo '</tr>'.PHP_EOL;
		}
	?>
	</table>
</div>

