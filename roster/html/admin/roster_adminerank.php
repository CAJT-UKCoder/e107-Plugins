<?php

class adminerank_html {

	function erank_header() {
		return '
			<center>
			<table class="roster" cellspacing="0" width="100%">
				<tr>
					<td class="aroster_main" colspan="5"><strong>'.roster_LAN_ADMIN_ERANK_TITLE.'</strong></td>
				</tr>
		';
	} // end function erank_header


	function erank_show() {
	global $sql;

		//get the ranks
		$ranks_q = $sql->db_Select("roster_ranks", "*", "roster_rank_id!='0' ORDER BY roster_rank_order ASC");
		$ranks = "";
		while($row = $sql->db_Fetch(MYSQL_ASSOC)){
			$i+=1;
			$rank = explode(",",$row['roster_rank_name']);
			$rankpicture = "<img src='images/ranks/small/sml".$rank[2].".png' />";
			if($i%2 == 0){
				$ranks .= "<tr>";
				$ranks .= "<td class=\"aroster_thumb1\" width=\"30\">{$rankpicture}</td>";
				$ranks .= "<td class=\"aroster_row1\" width=\"60%\">{$rank[0]}   {$rank[1]}</td>";
				$ranks .= "<td class=\"aroster_row1\" width=\"20%\">{$row['roster_rank_grade']}</td>";
				$ranks .= "<td class=\"aroster_row1\" width=\"10%\"><a href=\"admin_erank.php?action=edit&r_id={$row['roster_rank_id']}\">".roster_LAN_ADMIN_ERANK_EDIT."</a>    |     <a href=\"admin_erank.php?action=delete&r_id={$row['roster_rank_id']}\">".roster_LAN_ADMIN_ERANK_DELETE."</a></td>";
				$ranks .= "<td class=\"aroster_row1\" width=\"10%\"><input class=\"tbox\" type=\"text\" name=\"r_order[{$row['roster_rank_id']}]\" value=\"{$row['roster_rank_order']}\" size=\"2\" /></td>";
				$ranks .= "</tr>";
			}else{
				$ranks .= "<tr>";
				$ranks .= "<td class=\"aroster_thumb2\" width=\"30\">{$rankpicture}</td>";
				$ranks .= "<td class=\"aroster_row2\" width=\"60%\">{$rank[0]}   {$rank[1]}</td>";
				$ranks .= "<td class=\"aroster_row2\" width=\"20%\">{$row['roster_rank_grade']}</td>";
				$ranks .= "<td class=\"aroster_row2\" width=\"10%\"><a href=\"admin_erank.php?action=edit&r_id={$row['roster_rank_id']}\">".roster_LAN_ADMIN_ERANK_EDIT."</a>    |     <a href=\"admin_erank.php?action=delete&r_id={$row['roster_rank_id']}\">".roster_LAN_ADMIN_ERANK_DELETE."</a></td>";
				$ranks .= "<td class=\"aroster_row2\" width=\"10%\"><input class=\"tbox\" type=\"text\" name=\"r_order[{$row['roster_rank_id']}]\" value=\"{$row['roster_rank_order']}\" size=\"2\" /></td>";
				$ranks .= "</tr>";
			}
		}

		return '
			<form action="admin_erank.php?action=do_edit" method="POST">
			<tr>
				<td height="20" class="aroster_header" width="30">'.roster_LAN_ADMIN_ERANK_THUMB.'</td>
				<td height="20" class="aroster_header" width="40%">'.roster_LAN_ADMIN_ERANK_NAME.'</td>
				<td height="20" class="aroster_header" width="20%">'.roster_LAN_ADMIN_ERANK_GRADE.'</td>
				<td height="20" class="aroster_header" width="20%">'.roster_LAN_ADMIN_ERANK_ACTION.'</td>
				<td height="20" class="aroster_header" width="20%">'.roster_LAN_ADMIN_ERANK_ORDER.'</td>
			</tr>
			'.$ranks.'
			<tr>
				<td class="aroster_footer" colspan="5"><input class="tbox" type="submit" name="do_order" value="'.roster_LAN_ADMIN_ERANK_ORDER.'" /></td>
			</tr>
			</form>
		';
	} // end function erank_show


	function erank_form($r_id) {
	global $sql;

		// get the rank
		$rank_q = $sql->db_Select("roster_ranks", "*", "roster_rank_id='".$r_id."'");
		$rank_a = $sql->db_Fetch(MYSQL_ASSOC);
		$rank = explode(",",$rank_a['roster_rank_name']);
		$rankpicture = "<img src='images/ranks/small/sml".$rank[2].".png' />";
		
		return '
			<form action="admin_erank.php?action=do_edit&r_id='.$r_id.'" method="POST">
			<tr>
				<td class="aroster_row1" width="30%">'.roster_LAN_ADMIN_ERANK_THUMB.':</td>				
				<td class="aroster_row2" width="70%">'.$rankpicture.'</td> 
			</tr>
			<tr>
				<td class="aroster_row1" width="30%">'.roster_LAN_ADMIN_ERANK_NAME.':</td>
				<td class="aroster_row2" width="70%"><input class="tbox" type="text" name="r_name" size="35" value="'.$rank[0].'" /></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="30%">'.roster_LAN_ADMIN_ERANK_SHORTNAME.':</td>
				<td class="aroster_row2" width="70%"><input class="tbox" type="text" name="r_shortname" size="35" value="'.$rank[2].'" /></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="30%">'.roster_LAN_ADMIN_ERANK_GRADE.':</td>
				<td class="aroster_row2" width="70%"><input class="tbox" type="text" name="r_grade" size="35" value="'.$rank_a['roster_rank_grade'].'" /></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="30%">'.roster_LAN_ADMIN_ERANK_INFO.':</td>
				<td class="aroster_row2" width="70%"><textarea class="tbox" name="r_info" cols="110" rows="25">'.$rank_a['roster_rank_info'].'</textarea></td>
			</tr>
			<tr>
				<td class="aroster_footer" colspan="3"><input class="tbox" type="submit" name="do_edit" value="'.roster_LAN_ADMIN_ERANK_EDIT.'" /></td>
			</tr>
			</form>
		';

	} // end function nrank_form


	function erank_footer() {
		return '
			</table>
			</center>
		';
	} // end function erank_footer


	function erank_updated() {
		return '
			<tr>
				<td class="center" colspan="3">'.roster_LAN_ADMIN_ERANK_UPDATED.'.</td>
			</tr>
		';
	} // end function erank_updated
	
	function erank_success($r_name) {
		return '
			<tr>
				<td class="center" colspan="2">'.roster_LAN_ADMIN_ERANK_SUCCESS.'.</td>
			</tr>
		';
	} // end function eforms_success
	
	function erank_deleted($r_name) {
		return '
			<tr>
				<td class="center" colspan="3">'.roster_LAN_ADMIN_ERANK_DELETED.'.</td>
			</tr>
		';
	} // end function erank_updated

} // end class adminerank_html
?>