<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
<table>
<caption>목록스킨설정</caption>
<colgroup>
	<col class="grid_2">
	<col>
</colgroup>
<thead>
<tr>
	<th scope="col">구분</th>
	<th scope="col">설정</th>
</tr>
</thead>
<tbody>
<tr>
	<td align="center">목록수</td>
	<td>
		<input type="text" name="bo_page_rows" value="<?php echo $board['bo_page_rows'];?>" size="4" class="frm_input" > 개 - PC
		&nbsp;
		<input type="text" name="bo_mobile_page_rows" value="<?php echo $board['bo_mobile_page_rows'];?>" size="4" class="frm_input" > 개 - 모바일
	</td>
</tr>
<tr>
	<td align="center">제목길이</td>
	<td>
		<input type="text" name="bo_subject_len" value="<?php echo $board['bo_subject_len'];?>" size="4" class="frm_input" > 자 - PC
		&nbsp;
		<input type="text" name="bo_mobile_subject_len" value="<?php echo $board['bo_mobile_subject_len'];?>" size="4" class="frm_input" > 자 - 모바일
	</td>
</tr>
<tr>
	<td align="center">출석달력</td>
	<td>
		<label><input type="checkbox" name="boset[chulsuk]" value="1"<?php echo get_checked($boset['chulsuk'], "1");?>> 목록에 출석달력 출력하기</label>
	</td>
</tr>
<tr>
	<td align="center">아이콘색</td>
	<td>
		<select name="boset[icolor]">
			<?php echo apms_color_options($boset['icolor']);?>
		</select>
		&nbsp;
		<label><input type="checkbox" name="boset[ibg]" value="1"<?php echo get_checked($boset['ibg'], "1");?>> 배경색으로 적용</label>
	</td>
</tr>
<tr>
	<td align="center">알림컬러</td>
	<td>
		<select name="boset[nbg]">
			<?php echo apms_color_options($boset['nbg']);?>
		</select>
	</td>
</tr>
<tr>
	<td align="center">버튼컬러</td>
	<td>
		<select name="boset[btnc]">
			<?php echo apms_color_options($boset['btnc']);?>
		</select>
	</td>
</tr>
</tbody>
</table>