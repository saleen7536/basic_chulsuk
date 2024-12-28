<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css" media="screen">', 0);

// 값정리
$boset['modal'] = (isset($boset['modal'])) ? $boset['modal'] : '';
$boset['list_skin'] = (isset($boset['list_skin']) && $boset['list_skin']) ? $boset['list_skin'] : 'basic';

//창열기
$is_modal_js = $is_link_target = '';
if($boset['modal'] == "1") { //모달
	$is_modal_js = apms_script('modal');
} else if($boset['modal'] == "2") { //링크#1
	$is_link_target = ' target="_blank"';
}

$list_skin_url = $board_skin_url.'/list/'.$boset['list_skin'];
$list_skin_path = $board_skin_path.'/list/'.$boset['list_skin'];
$list_cnt = count($list);

?>

<section class="board-list<?php echo (G5_IS_MOBILE) ? ' font-14' : '';?>"> 

	<?php @include_once($list_skin_path.'/list.head.skin.php'); // 헤드영역 ?>

	<div class="list-wrap">
		<form name="fboardlist" id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post" role="form" class="form">
			<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
			<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
			<input type="hidden" name="stx" value="<?php echo $stx ?>">
			<input type="hidden" name="spt" value="<?php echo $spt ?>">
			<input type="hidden" name="sca" value="<?php echo $sca ?>">
			<input type="hidden" name="sst" value="<?php echo $sst ?>">
			<input type="hidden" name="sod" value="<?php echo $sod ?>">
			<input type="hidden" name="page" value="<?php echo $page ?>">
			<input type="hidden" name="sw" value="">
			<?php 
				// 목록스킨
				if(is_file($list_skin_path.'/list.skin.php')) {
					include_once($list_skin_path.'/list.skin.php');
				} else {
					echo '<div class="well text-center"><i class="fa fa-bell red"></i> 설정하신 목록스킨('.$boset['list_skin'].')이 존재하지 않습니다.</div>';
				}
			?>
			<div class="list-page text-center">
				<ul class="pagination en no-margin">
					<?php if($prev_part_href) { ?>
						<li><a href="<?php echo $prev_part_href;?>">이전검색</a></li>
					<?php } ?>
					<?php echo apms_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, './board.php?bo_table='.$bo_table.$qstr.'&amp;page=');?>
					<?php if($next_part_href) { ?>
						<li><a href="<?php echo $next_part_href;?>">다음검색</a></li>
					<?php } ?>
				</ul>
			</div>

			<div class="clearfix"></div>

			<?php if ($is_checkbox || $setup_href || $admin_href) { ?>
				<div class="list-admin">
					<div class="btn-group" role="group">
						<?php if ($is_checkbox) { ?>
							<button type="button" class="btn-chkall btn btn-<?php echo $btn1;?> btn-sm"><i class="fa fa-check"></i><span class="hidden-xs"> 전체선택</span></button>
							<button type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-<?php echo $btn1;?> btn-sm"><i class="fa fa-times"></i><span class="hidden-xs"> 선택삭제</span></button>
							<button type="submit" name="btn_submit" value="선택복사" onclick="document.pressed=this.value" class="btn btn-<?php echo $btn1;?> btn-sm"><i class="fa fa-clipboard"></i><span class="hidden-xs"> 선택복사</span></button>
							<button type="submit" name="btn_submit" value="선택이동" onclick="document.pressed=this.value" class="btn btn-<?php echo $btn1;?> btn-sm"><i class="fa fa-arrows"></i><span class="hidden-xs"> 선택이동</span></button>
						<?php } ?>
						<?php if ($admin_href) { ?>
							<a role="button" href="<?php echo $write_href ?>" class="btn btn-<?php echo $btn2;?> btn-sm"><i class="fa fa-pencil"></i> 공지쓰기</a>
							<a role="button" href="<?php echo $admin_href; ?>" class="btn btn-<?php echo $btn1;?> btn-sm"><i class="fa fa-cog"></i><span class="hidden-xs"> 보드설정</span></a>
						<?php } ?>
						<?php if ($setup_href) { ?>
							<a role="button" href="<?php echo $setup_href; ?>" class="btn btn-<?php echo $btn2;?> btn-sm win_memo"><i class="fa fa-cogs"></i><span class="hidden-xs"> 추가설정</span></a>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</form>
	</div>
	<?php @include_once($list_skin_path.'/list.tail.skin.php'); // 테일영역 ?>
</section>

<?php if ($is_checkbox) { ?>
<noscript>
<p align="center">자바스크립트를 사용하지 않는 경우 별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>

<script>
function all_checked(sw) {
	var f = document.fboardlist;

	for (var i=0; i<f.length; i++) {
		if (f.elements[i].name == "chk_wr_id[]")
			f.elements[i].checked = sw;
	}
}
$(function(){
	$(".btn-chkall").click(function(){
		var clicked_checked = $(this);

		if(clicked_checked.hasClass('active')) {
			all_checked(false);
			clicked_checked.removeClass('active');
		} else {
			all_checked(true);
			clicked_checked.addClass('active');
		}
	});
});
function fboardlist_submit(f) {
	var chk_count = 0;

	for (var i=0; i<f.length; i++) {
		if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
			chk_count++;
	}

	if (!chk_count) {
		alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
		return false;
	}

	if(document.pressed == "선택복사") {
		select_copy("copy");
		return;
	}

	if(document.pressed == "선택이동") {
		select_copy("move");
		return;
	}

	if(document.pressed == "선택삭제") {
		if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
			return false;

		f.removeAttribute("target");
		f.action = "./board_list_update.php";
	}

	return true;
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
	var f = document.fboardlist;

	if (sw == "copy")
		str = "복사";
	else
		str = "이동";

	var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

	f.sw.value = sw;
	f.target = "move";
	f.action = "./move.php";
	f.submit();
}
</script>
<?php } ?>
<!-- } 게시판 목록 끝 -->

<div class="h20"></div>