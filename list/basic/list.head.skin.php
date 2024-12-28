<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

//내아이콘
$fa_photo = (isset($boset['ficon']) && $boset['ficon']) ? apms_fa($boset['ficon']) : '<i class="fa fa-user"></i>';		
$myicon = apms_photo_url($member['mb_id']);
$myicon = ($myicon) ? '<img src="'.$myicon.'">' : $fa_photo;

if(!$boset['icolor']) $boset['icolor'] = 'green';
if(!$boset['nbg']) $boset['nbg'] = 'orangered';

$is_calendar = (isset($boset['chulsuk']) && $boset['chulsuk']) ? true : false;

?>

<div class="modal fade" id="chulsukModal" tabindex="-1" role="dialog" aria-labelledby="chulsukModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				
				<?php if(!$is_calendar) include_once($list_skin_path.'/list.calendar.skin.php'); //출석달력 ?>

				<div class="table-responsive">
					<table class="table table-bordered">
					<tr class="active">
						<th>구분</th>
						<th colspan="3">주요내용</th>
					</tr>
					<tr>
						<td>출석시간</td>
						<td colspan="3">
							<?php if($boset['stime'] && $boset['etime']) { // 출석제한시 ?>
								<?php echo $boset['stime'];?>시부터 <?php echo $boset['etime'];?>시까지
							<?php } else { ?>
								24시간 가능
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>출석점수</td>
						<td colspan="3">
							<?php if($boset['point'] > 0 && $boset['minus'] > 0) { ?>
								출석순으로 <?php echo number_format($boset['point']);?><?php echo AS_MP;?>부터 
								<?php echo number_format($boset['minus']);?><?php echo AS_MP;?>씩 차감 지급 (기본 <?php echo number_format($board['bo_write_point']);?><?php echo AS_MP;?>)
							<?php } else if($boset['point'] > $board['bo_write_point']) { ?>
								출석시 <?php echo number_format($boset['point']);?><?php echo AS_MP;?> 지급
							<?php } else { ?>
								출석시 <?php echo number_format($board['bo_write_point']);?><?php echo AS_MP;?> 지급
							<?php } ?>
						</td>
					</tr>
					<?php if(isset($boset['bpoint']) && $boset['bpoint'] > 0 && isset($boset['bdice']) && $boset['bdice'] > 0) { ?>
						<tr>
							<td>랜덤점수</td>
							<td colspan="3">
								<?php if($boset['bdice'] > 1) { ?>
									<?php echo $boset['bdice'];?>면체 주사위를 이용하여 같은 숫자가 나오면 
								<?php } ?>	
								<?php echo number_format($boset['bpoint']);?><?php echo AS_MP;?> 내에서 랜덤 지급 (<?php echo $boset['bdice'];?>면체 주사위)
							</td>
						</tr>
					<?php } ?>
					<?php
						$attend = array();
						$tmp = explode("\n", $boset['attend']);
						$tmp_cnt = count($tmp);
						$j = 0;
						for($i=0; $i < $tmp_cnt; $i++) {
							list($aday, $apoint, $abonus, $adice) = explode(",", $tmp[$i]);

							if(!(int)$aday || !(int)$apoint) continue;

							$attend[$j]['day'] = $aday;
							$attend[$j]['point'] = $apoint;
							if($abonus > 0 && $adice > 0) {
								$attend[$j]['bonus'] = '1 ~ '.number_format($abonus).' 랜덤 '.AS_MP;
								$attend[$j]['dice'] = number_format($adice).'면체 주사위';
							} else {
								$attend[$j]['bonus'] = '-';
								$attend[$j]['dice'] = '-';
							}
							$j++;
						}

						if($j) {
					?>
							<tr>
								<td>개근점수</td>
								<td colspan="3">
									개근일수에 따라 기본 + 보너스 개근 <?php echo AS_MP;?>를 지급
								</td>
							</tr>
							<tr class="active">
								<th>개근일수</th>
								<th>개근 <?php echo AS_MP;?></th>
								<th>보너스 <?php echo AS_MP;?></th>
								<th>보너스 주사위</th>
							</tr>
						<?php
							$attend = apms_sort($attend, 'day');
							$attend_cnt = count($attend);
							for($i=0; $i < $attend_cnt; $i++) {
						?>
								<tr>
									<td>개근 <?php echo number_format($attend[$i]['day']);?> 일부터</td>
									<td><?php echo number_format($attend[$i]['point']);?> <?php echo AS_MP;?></td>
									<td><?php echo $attend[$i]['bonus'];?></td>
									<td><?php echo $attend[$i]['dice'];?></td>
								</tr>
						<?php } ?>
					<?php } ?>
					</table>
				</div>
				<div class="text-center">
					<button type="button" class="btn btn-lightgray btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> 닫기</button>
				</div>
			</div>
		</div>
	</div>
</div>

<?php if(!$is_calendar) { // 달력미출력시 ?>
	<div style="margin-bottom:10px;">
		<div class="pull-left">
			<h3 class="div-title-underline-thin no-margin cursor" data-toggle="modal" data-target="#chulsukModal">
				<i class="fa fa-calendar-check-o"></i> 
				<?php echo $year;?>.<?php echo sprintf("%02d",$month);?>.<?php echo sprintf("%02d",$day);?>
				<?php if($total_count > 0) { ?>
					<span class="font-13 en orangered">+ <?php echo number_format($total_count);?></span>
					&nbsp;
				<?php } ?>
			</h3>
		</div>
		<div class="pull-right">
			<span class="chulsuk-book cursor bg-<?php echo (isset($boset['btnc']) && $boset['btnc']) ? $boset['btnc'] : 'color';?>" data-toggle="modal" data-target="#chulsukModal">
				출석달력/출석정책
			</span>
		</div>
		<div class="clearfix"></div>
	</div>
<?php } ?>

<?php if($is_calendar) include_once($list_skin_path.'/list.calendar.skin.php'); //출석달력 ?>

<style>
	.board-list .talker-photo i { 
		<?php echo ($boset['ibg']) ? 'background:'.apms_color($boset['icolor']).'; color:#fff' : 'color:'.apms_color($boset['icolor']);?>;
	}
	.board-list .talker-photo i.notice { 
		background:<?php echo apms_color($boset['nbg']);?>; color:#fff;
	}
</style>

<?php if($nowday == $selday) { //오늘일 경우에만 출력 

	// 메시지 출력
	$msg = array();
	$mlist = array();
	$mtxt = '';
	@include_once($board_skin_path.'/msg.list.php');
	$mlist_cnt = count($mlist);
	if($mlist_cnt) {
		$mdb = $mlist;
		shuffle($mdb); // 섞기
		@include_once($board_skin_path.'/msg/'.$mdb[0][1]); //첫번째 메시지 파일을 연다.
		$msg_cnt = count($msg);
		if($msg_cnt) {
			shuffle($msg); // 섞기
			$mtxt .= $msg[0];
		}
	}
?>
	<div class="talk-box-wrap">
		<span id="ticon" class="talker-photo pull-left"><?php echo $myicon;?></span>
		<div class="talk-box talk-right">
			<div class="talk-bubble">
				<?php if($is_chulsuk_limit) { // 출석제한시 ?>
					<i class="fa fa-smile-o fa-lg orangered"></i>
					매일 <?php echo $boset['stime'];?>시부터 <?php echo $boset['etime'];?>시까지만 출석이 가능합니다.
					<div class="chulsuk-msg bg-white">
						<?php echo conv_content($mtxt, 0);?>
					</div>
				<?php } else {

					// 글자수 제한 설정값
					if ($is_admin) {
						$write_min = $write_max = 0;
					} else {
						$write_min = (int)$board['bo_write_min'];
						$write_max = (int)$board['bo_write_max'];
					}

					$is_link = false;
					if ($member['mb_level'] >= $board['bo_link_level']) {
						$is_link = true;
					}

					$is_file = false;
					if ($member['mb_level'] >= $board['bo_upload_level']) {
						$is_file = true;
					}

					$is_file_content = false;
					if ($board['bo_use_file_content']) {
						$is_file_content = true;
					}

					$file_count = (int)$board['bo_upload_count'];

					$category_option = '';
					if ($board['bo_use_category']) {
						$ca_name = ($sca) ? $sca : '';
						$category_option = get_category_option($bo_table, $ca_name);
					}

					$action_url = https_url(G5_BBS_DIR)."/write_update.php";
				?>

				<form name="fchulsuk" id="fchulsuk" action="<?php echo $action_url ?>" onsubmit="return fchulsuk_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" role="form" class="form">
				<input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
				<input type="hidden" name="w" value="">
				<input type="hidden" name="is_direct" value="1">
				<input type="hidden" name="is_subject" value="1">
				<input type="hidden" name="wr_1" value="<?php echo $selday;?>">
				<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
				<input type="hidden" name="wr_subject" value="1">
				<input type="hidden" name="as_icon" value="" id="picon">

				<?php if ($is_category) { ?>
					<div class="form-group pull-left">
						<select name="ca_name" id="ca_name" required class="form-control input-sm">
							<option value="">분류선택</option>
							<?php echo $category_option ?>
						</select>
					</div>
					<div class="clearfix"></div>
				<?php } ?>

				<div id="msg_content" class="msg-content">
					<div class="msg-cell">
						<?php if($write_min || $write_max) { ?><strong id="char_cnt" style="display:none;"><span id="char_count"></span></strong><?php } ?>
						<textarea id="wr_content" name="wr_content" class="form-control input-sm" rows="4" required maxlength="65536"
						<?php if ($write_min || $write_max) { ?>onkeyup="check_byte('wr_content', 'char_count');"<?php } ?>><?php echo $mtxt;?></textarea>
						<?php if ($write_min || $write_max) { ?><script> check_byte('wr_content', 'char_count'); </script><?php } ?>
						<script>
						$("textarea#wr_content[maxlength]").live("keyup change", function() {
							var str = $(this).val()
							var mx = parseInt($(this).attr("maxlength"))
							if (str.length > mx) {
								$(this).val(str.substr(0, mx));
								return false;
							}
						});
						</script>
					</div>
					<div tabindex="14" class="msg-cell msg-submit" onclick="apms_chulsuk_submit();" onKeyDown="apms_chulsuk_onKeyDown();" id="talk_submit">
						출석
					</div>
				</div>

				<div class="msg-btn">
					<span class="pull-left">
						<span data-toggle="buttons">
							<?php 
								//메시지 아이콘
								if($mlist_cnt) {
									$mpopover = "<div class='text-center msg-icon'>";
									for($i=0; $i < $mlist_cnt; $i++) {
										$mpopover .= "<a href=javascript:apms_msg('".$mlist[$i][1]."'); >";
										$mpopover .= "<i class='fa ".$mlist[$i][2]." circle large bg-".$mlist[$i][3]."'></i>";
										$mpopover .= "<p class='text-muted'>".$mlist[$i][0]."</p></a>";
									}
									$mpopover .= "</div>";
								}
							?>
							<?php if($mlist_cnt) { ?>
								<label id="msgPopover" class="btn chk-photo" data-container="body" data-html="true" data-toggle="popover" data-placement="bottom" data-title="Random Message" data-content="<?php echo $mpopover;?>">
									<i class="fa fa-quote-right fa-lg"></i><span class="hidden-xs"> 메시지</span>
								</label>
							<?php } ?>
							<label class="btn chk-photo" onclick="apms_emoticon('picon', 'ticon');">
								<input type="radio" name="select_icon" id="select_icon1">
								<i class="fa fa-smile-o fa-lg"></i><span class="hidden-xs"> 이모티콘</span>
							</label>
							<label class="btn chk-photo" onclick="win_scrap('<?php echo G5_BBS_URL;?>/ficon.php?fid=picon&sid=ticon');">
								<input type="radio" name="select_icon" id="select_icon2">
								<i class="fa fa-info-circle fa-lg"></i><span class="hidden-xs"> 아이콘</span>
							</label>
							<label class="btn chk-photo" onclick="apms_myicon();">
								<input type="radio" name="select_icon" id="select_icon3">
								<i class="fa fa-user fa-lg"></i><span class="hidden-xs"> 내사진</span>
							</label>
						</span>
					</span>
					<span class="pull-right">
						<?php if($is_link) { ?>
							<span class="btn chk-photo" title="동영상" onclick="apms_write_opt('listVideo');">
								<i class="fa fa-video-camera fa-lg"></i><span class="sound_only"> 동영상</span>
							</span>
						<?php } ?>
						<?php if($is_file) { ?>
							<span class="btn chk-photo" title="포토" onclick="apms_write_opt('listPhoto');">
								<i class="fa fa-camera fa-lg"></i><span class="sound_only"> 포토</span>
							</span>
						<?php } ?>
						<span class="btn chk-photo" title="지도" onclick="win_scrap('<?php echo G5_BBS_URL;?>/helper.php?act=map');">
							<i class="fa fa-compass fa-lg"></i><span class="sound_only"> 지도</span>
						</span>
						<span class="btn chk-photo" title="늘이기" onclick="apms_textarea('wr_content','down');">
							<i class="fa fa-plus-circle fa-lg"></i><span class="sound_only"> 입력창 늘이기</span>
						</span>
						<span class="btn chk-photo" title="줄이기" onclick="apms_textarea('wr_content','up');" style="margin-right:0px !important;">
							<i class="fa fa-minus-circle fa-lg"></i><span class="sound_only"> 입력창 줄이기</span>
						</span>
					</span>
					<div class="clearfix"></div>
				</div>

				<div class="clearfix"></div>

				<?php if($is_link) { ?>
					<div class="anc-write" id="listVideo">
						<div class="h10"></div>
						<input type="text" name="wr_link1" value="<?php if($w=="u"){ echo $write['wr_link1']; } ?>" id="wr_link1" class="form-control input-sm" size="50" placeholder="유튜브, 비메오 등 동영상 공유주소 등록">
					</div>
				<?php } ?>
				<?php if($is_file) { ?>
					<div class="anc-write" id="listPhoto">
						<?php for ($i=0; $is_file && $i < 1; $i++) { ?>
							<div class="h10"></div>
							<input type="file" name="bf_file[]" title="파일첨부 <?php echo $i+1 ?> : 용량 <?php echo number_format($board['bo_upload_size']);?> 바이트 이하만 업로드 가능">
						<?php } ?>
					</div>
				<?php } ?>
				</form>
				<script>
					<?php if($write_min || $write_max) { ?>
					// 글자수 제한
					var char_min = parseInt(<?php echo $write_min; ?>); // 최소
					var char_max = parseInt(<?php echo $write_max; ?>); // 최대
					check_byte("wr_content", "char_count");

					$(function() {
						$("#wr_content").on("keyup", function() {
							check_byte("wr_content", "char_count");
						});
					});
					<?php } ?>
					function apms_msg(q) {
						var url = '<?php echo $board_skin_url;?>/msg.load.php?bo_table=<?php echo $bo_table;?>&q=' + encodeURI(q);
						$.get(url, function(data) {
							$('#msg_content textarea').val(data.msg);
						}, "json");
					}
					function apms_write_opt(id) {
						$(".anc-write").hide();
						$("#" + id).show();
					}
					function apms_myicon() {

						document.getElementById("picon").value = '';
						document.getElementById("ticon").innerHTML = '<?php echo str_replace("'","\"", $myicon);?>';

						return true;
					}
					function fchulsuk_submit(f) {
						if(!g5_is_member) {
							alert("로그인한 회원만 출석할 수 있습니다.");
							return false;
						}

						var content = "";
						$.ajax({
							url: g5_bbs_url+"/ajax.filter.php",
							type: "POST",
							data: {
								"content": f.wr_content.value
							},
							dataType: "json",
							async: false,
							cache: false,
							success: function(data, textStatus) {
								content = data.content;
							}
						});

						if (content) {
							alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
							if (typeof(ed_wr_content) != "undefined")
								ed_wr_content.returnFalse();
							else
								f.wr_content.focus();
							return false;
						}

						if (document.getElementById("char_count")) {
							if (char_min > 0 || char_max > 0) {
								var cnt = parseInt(check_byte("wr_content", "char_count"));
								if (char_min > 0 && char_min > cnt) {
									alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
									return false;
								}
								else if (char_max > 0 && char_max < cnt) {
									alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
									return false;
								}
							}
						}
						
						set_write_token(f);

						//document.getElementById("talk_submit").disabled = "disabled";

						return true;
					}

					function apms_chulsuk_submit() {
						var f = document.getElementById("fchulsuk");
						if (fchulsuk_submit(f)) {
							$("#fchulsuk").submit();
						}
						return false;
					}

					function apms_chulsuk_onKeyDown() {
						  if(event.keyCode == 13) {
							apms_chulsuk_submit();
						 }
					}

					$(function() {
						$('#msgPopover').popover();
					});
				</script>
				<?php } // 출석제한 ?>
			</div>
		</div>
	</div>
<?php } ?>

<?php if($is_category) include_once($board_skin_path.'/category.skin.php'); // 카테고리 ?>

<?php if($notice_count > 0) { //공지사항 ?>
	<div class="talk-box-wrap chulsuk-notice">
		<div class="pull-left text-muted text-center">
			<span class="talker-photo"><i class="fa fa-bell notice"></i></span>
		</div>
		<div class="talk-box talk-right">
			<div class="talk-bubble">
				<ul>
				<?php for ($i=0; $i < $notice_count; $i++) { 
						if(!$list[$i]['is_notice']) break; //공지가 아니면 끝냄 
				?>
					 <li>
						<a href="<?php echo $list[$i]['href'];?>" class="ellipsis"<?php echo $is_modal_js;?>>
							<span class="hidden-xs pull-right text-muted">
								<i class="fa fa-clock-o"></i> <?php echo date("Y.m.d", $list[$i]['date']);?>
							</span>
							[알림] <?php echo $list[$i]['subject'];?>
							<?php if($list[$i]['wr_comment']) { ?>
								<span class="count red"><?php echo $list[$i]['wr_comment'];?></span>
							<?php } ?>
						</a>
					</li>
				<?php } ?>
				</ul>
			</div>
		</div>
	</div>
<?php } ?>

<div class="talk-box-wrap">
	<div class="pull-left text-muted text-center">
		<span class="talker-photo"><i class="fa fa-flag notice"></i></span>
	</div>
	<div class="talk-box talk-right">
		<div class="talk-bubble">
			<?php if($nowday == $selday) { //오늘이면 ?>
				<?php if($total_count > 0) { ?>
					현재 <b class="red"><?php echo number_format($total_count);?></b>명 출석!
				<?php } ?>
				<?php if($boset['point'] > 0 && $boset['minus'] > 0) { ?>
					출석순으로 <b class="red"><?php echo number_format($boset['point']);?></b><?php echo AS_MP;?>부터 
					<?php echo number_format($boset['minus']);?><?php echo AS_MP;?>씩 차감 지급됩니다.(기본 <?php echo number_format($board['bo_write_point']);?><?php echo AS_MP;?>)
				<?php } else if($boset['point'] > $board['bo_write_point']) { ?>
					출석시 <b class="red"><?php echo number_format($boset['point']);?></b><?php echo AS_MP;?> 지급합니다.
				<?php } else { ?>
					출석시 <b class="red"><?php echo number_format($board['bo_write_point']);?></b><?php echo AS_MP;?> 지급합니다.
				<?php } ?>
			<?php } else { ?>
				<?php if($total_count > 0) { ?>
					금일 출석자는 총 <b class="red"><?php echo number_format($total_count);?></b>명입니다.
				<?php } else { ?>
					<?php if($selday > $nowday) { ?>
						출석가능한 날이 아닙니다.
					<?php } else { ?>
						금일은 출석자가 없습니다.
					<?php } ?>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>
