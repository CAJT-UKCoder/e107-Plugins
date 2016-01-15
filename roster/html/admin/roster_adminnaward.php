<?php

class adminnaward_html {

	function naward_header() {
		return '
			<center>
			<table class="roster" cellspacing="0" width="100%">
				<tr>
					<td class="aroster_main" colspan="2"><strong>'.roster_LAN_ADMIN_NAWARD_TITLE.'</strong></td>
				</tr>
		';
	} // end function naward_header


	function naward_form() {
	//<input type=\"file\" name=\"imagefile\"><input class="tbox" type="text" name="a_thumb" size="35" /></td>
		return '
			<form enctype="multipart/form-data" action="admin_naward.php?action=do_add" method="POST">
			<tr>
				<td class="aroster_row1" width="30%">'.roster_LAN_ADMIN_NAWARD_NAME.':</td>
				<td class="aroster_row2" width="70%"><input class="tbox" type="text" name="a_name" size="35" /></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="30%">'.roster_LAN_ADMIN_NAWARD_SHORTNAME.':</td>
				<td class="aroster_row2" width="70%"><input class="tbox" type="text" name="a_shortname" size="35" /></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="30%">'.roster_LAN_ADMIN_NAWARD_HISTORY.':</td>
				<td class="aroster_row2" width="70%"><textarea class="tbox" name="a_history" cols="110" rows="25"></textarea></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="30%">'.roster_LAN_ADMIN_NAWARD_REQUIREMENTS.':</td>
				<td class="aroster_row2" width="70%"><textarea class="tbox" name="a_requirements" cols="110" rows="25"></textarea></td>
			</tr>
			<tr>
				<td class="aroster_footer" colspan="2"><input class="tbox" type="submit" name="submit" value="'.roster_LAN_ADMIN_NAWARD_ADD.'" /></td>
			</tr>
			</form>
		';
	} // end function naward_form

	function naward_footer() {
		return '
			</table>
			</center>
		';
	} // end function naward_footer


	function naward_success($a_name){
		return '
			<tr>
				<td class="center">'.roster_LAN_ADMIN_NAWARD_SUCCESS.'.</td>
			</tr>
		';
	} // end function naward_success

} // end class adminnaward_html
?>