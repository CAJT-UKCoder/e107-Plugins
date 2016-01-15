<?php

class adminnrank_html {

	function nrank_header() {
		return '
			<center>
			<table class="roster" cellspacing="0" width="100%">
				<tr>
					<td class="aroster_main" colspan="2"><strong>'.roster_LAN_ADMIN_NRANK_TITLE.'</strong></td>
				</tr>
		';
	} // end function nrank_header


	function nrank_form() {
	//<input type=\"file\" name=\"imagefile\"><input class="tbox" type="text" name="r_thumb" size="35" /></td>
		return '
			<form enctype="multipart/form-data" action="admin_nrank.php?action=do_add" method="POST">
			<tr>
				<td class="aroster_row1" width="30%">'.roster_LAN_ADMIN_NRANK_NAME.':</td>
				<td class="aroster_row2" width="70%"><input class="tbox" type="text" name="r_name" size="35" /></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="30%">'.roster_LAN_ADMIN_NRANK_SHORTNAME.':</td>
				<td class="aroster_row2" width="70%"><input class="tbox" type="text" name="r_shortname" size="35" /></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="30%">'.roster_LAN_ADMIN_NRANK_GRADE.':</td>
				<td class="aroster_row2" width="70%"><input class="tbox" type="text" name="r_grade" size="35" /></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="30%">'.roster_LAN_ADMIN_NRANK_INFO.':</td>
				<td class="aroster_row2" width="70%"><textarea class="tbox" name="r_info" cols="110" rows="25"></textarea></td>
			</tr>
			<tr>
				<td class="aroster_footer" colspan="2"><input class="tbox" type="submit" name="submit" value="'.roster_LAN_ADMIN_NRANK_ADD.'" /></td>
			</tr>
			</form>
		';
	} // end function nrank_form


	function nrank_footer() {
		return '
			</table>
			</center>
		';
	} // end function nrank_footer


	function nrank_success($r_name){
		return '
			<tr>
				<td class="center">'.roster_LAN_ADMIN_NRANK_SUCCESS.'.</td>
			</tr>
		';
	} // end function nrank_success
	function nrank_notsuccess($error){
		return '
			<tr>
				<td class="center">'.$error.'</td>
			</tr>
		';
	} // end function nrank_notsuccess

} // end class adminnrank_html
?>