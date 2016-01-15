<?php

class admineaward_html {

	function eaward_header() {
		return '
			<center>
			<table class="roster" cellspacing="0" width="100%">
				<tr>
					<td class="aroster_main" colspan="5"><strong>'.roster_LAN_ADMIN_EAWARD_TITLE.'</strong></td>
				</tr>
		';
	} // end function eaward_header


	function eaward_show() {
	global $sql;

		//get the awards
		$awards_q = $sql->db_Select("roster_awards", "*", "roster_award_id!='0' ORDER BY roster_award_order ASC");
		$awards = "";
		while($row = $sql->db_Fetch(MYSQL_ASSOC)){
			$i+=1;
			$award = explode(",",$row['roster_award_name']);
			$awardpicture = "<img src='images/awards/small/sml".$award[2].".png' />";
			if($i%2 == 0){
				$awards .= "<tr>";
				$awards .= "<td class=\"aroster_thumb1\" width=\"30\">{$awardpicture}</td>";
				$awards .= "<td class=\"aroster_row1\" width=\"80%\">{$award[0]}   {$award[1]}</td>";
				$awards .= "<td class=\"aroster_row1\" width=\"10%\"><a href=\"admin_eaward.php?action=edit&a_id={$row['roster_award_id']}\">".roster_LAN_ADMIN_EAWARD_EDIT."</a>    |     <a href=\"admin_eaward.php?action=delete&a_id={$row['roster_award_id']}\">".roster_LAN_ADMIN_EAWARD_DELETE."</a></td>";
				$awards .= "<td class=\"aroster_row1\" width=\"10%\"><input class=\"tbox\" type=\"text\" name=\"a_order[{$row['roster_award_id']}]\" value=\"{$row['roster_award_order']}\" size=\"2\" /></td>";
				$awards .= "</tr>";
			}else{
				$awards .= "<tr>";
				$awards .= "<td class=\"aroster_thumb2\" width=\"30\">{$awardpicture}</td>";
				$awards .= "<td class=\"aroster_row2\" width=\"80%\">{$award[0]}   {$award[1]}</td>";
				$awards .= "<td class=\"aroster_row2\" width=\"10%\"><a href=\"admin_eaward.php?action=edit&a_id={$row['roster_award_id']}\">".roster_LAN_ADMIN_EAWARD_EDIT."</a>    |     <a href=\"admin_eaward.php?action=delete&a_id={$row['roster_award_id']}\">".roster_LAN_ADMIN_EAWARD_DELETE."</a></td>";
				$awards .= "<td class=\"aroster_row2\" width=\"10%\"><input class=\"tbox\" type=\"text\" name=\"a_order[{$row['roster_award_id']}]\" value=\"{$row['roster_award_order']}\" size=\"2\" /></td>";
				$awards .= "</tr>";
			}
		}

		return '
			<form action="admin_eaward.php?action=do_edit" method="POST">
			<tr>
				<td height="20" class="aroster_header" width="30%">'.roster_LAN_ADMIN_EAWARD_THUMB.'</td>
				<td height="20" class="aroster_header" width="60%">'.roster_LAN_ADMIN_EAWARD_NAME.'</td>
				<td height="20" class="aroster_header" width="20%">'.roster_LAN_ADMIN_EAWARD_ACTION.'</td>
				<td height="20" class="aroster_header" width="20%">'.roster_LAN_ADMIN_EAWARD_ORDER.'</td>
			</tr>
			'.$awards.'
			<tr>
				<td class="aroster_footer" colspan="5"><input class="tbox" type="submit" name="do_order" value="'.roster_LAN_ADMIN_EAWARD_ORDER.'" /></td>
			</tr>
			</form>
		';
	} // end function eaward_show


	function eaward_form($a_id) {
	global $sql;

		// get the award
		$award_q = $sql->db_Select("roster_awards", "*", "roster_award_id='".$a_id."'");
		$award_a = $sql->db_Fetch(MYSQL_ASSOC);
		$award = explode(",",$award_a['roster_award_name']);
		$awardpicture = "<img src='images/awards/small/sml".$award[2].".png' />";
		
		return '
			<form action="admin_eaward.php?action=do_edit&a_id='.$a_id.'" method="POST">
			<tr>
				<td class="aroster_row1" width="30%">'.roster_LAN_ADMIN_EAWARD_THUMB.':</td>				
				<td class="aroster_row2" width="70%">'.$awardpicture.'</td> 
			</tr>
			<tr>
				<td class="aroster_row1" width="30%">'.roster_LAN_ADMIN_EAWARD_NAME.':</td>
				<td class="aroster_row2" width="70%"><input class="tbox" type="text" name="a_name" size="35" value="'.$award[0].'" /></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="30%">'.roster_LAN_ADMIN_EAWARD_SHORTNAME.':</td>
				<td class="aroster_row2" width="70%"><input class="tbox" type="text" name="a_shortname" size="35" value="'.$award[2].'" /></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="30%">'.roster_LAN_ADMIN_EAWARD_HISTORY.':</td>
				<td class="aroster_row2" width="70%"><textarea class="tbox" name="a_history" cols="110" rows="25">'.$award_a['roster_award_history'].'</textarea></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="30%">'.roster_LAN_ADMIN_EAWARD_REQUIREMENTS.':</td>
				<td class="aroster_row2" width="70%"><textarea class="tbox" name="a_requirements" cols="110" rows="25">'.$award_a['roster_award_requirements'].'</textarea></td>
			</tr>
			<tr>
				<td class="aroster_footer" colspan="3"><input class="tbox" type="submit" name="do_edit" value="'.roster_LAN_ADMIN_EAWARD_EDIT.'" /></td>
			</tr>
			</form>
		';

	} // end function naward_form


	function eaward_footer() {
		return '
			</table>
			</center>
		';
	} // end function eaward_footer


	function eaward_updated() {
		return '
			<tr>
				<td class="center" colspan="3">'.roster_LAN_ADMIN_EAWARD_UPDATED.'.</td>
			</tr>
		';
	} // end function eaward_updated
	
	function eaward_success($a_name) {
		return '
			<tr>
				<td class="center" colspan="2">'.roster_LAN_ADMIN_EAWARD_SUCCESS.'.</td>
			</tr>
		';
	} // end function eforms_success
	
	function eaward_deleted($a_name) {
		return '
			<tr>
				<td class="center" colspan="3">'.roster_LAN_ADMIN_EAWARD_DELETED.'.</td>
			</tr>
		';
	} // end function eaward_updated

} // end class admineaward_html
?>