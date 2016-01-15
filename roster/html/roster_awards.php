	<?php

class awards_html {

	function awards_show() {
	global $sql;

		// make the groups
		$awards_q = $sql->db_Select("roster_awards", "*", "roster_award_id!='0' ORDER BY roster_award_order ASC");
		$tables = "";
		while($row = $sql->db_Fetch(MYSQL_ASSOC)){
			$i++;
			$award = explode(",",$row['roster_award_name']);
			$history = $row['roster_award_history'];
			$requirements = $row['roster_award_requirements'];
			$tables .= "<table class=\"roster\" width=\"100%\">
					<tr>
						<td class=\"roster_main\" colspan=\"2\">{$award[0]}</td>
					</tr>
					<tr>
						<td class=\"roster_header\" width=\"25%\">{$award[0]}</td>
						<td class=\"roster_header\" width=\"75%\">".roster_LAN_AWARD_HISTORY."</td>
					</tr>
				";
			if($i%2==0){
				$tables .= "<tr>
						<td class=\"roster_row1\" rowspan=\"3\"><div align=\"center\"><img src=\"".e_PLUGIN."roster/images/awards/big/{$award[2]}.png\" border=\"0\" /></div></td>
						<td class=\"roster_row1\"><div align=\"center\">{$history}</div></td>
					</tr>
					<tr>
						<td class=\"roster_header\" width=\"75%\">".roster_LAN_AWARD_REQUIREMENTS."</td>
					</tr>
					<tr>
						<td class=\"roster_row1\"><div align=\"center\">{$requirements}</div></td>
					</tr>
					<tr>
						<td class=\"roster_header\" colspan=\"2\"></td>			
					</tr>";
			}else{
				$tables .= "<tr>
						<td class=\"roster_row1\" rowspan=\"3\"><div align=\"center\"><img src=\"".e_PLUGIN."roster/images/awards/big/{$award[2]}.png\" border=\"0\" /></div></td>
						<td class=\"roster_row1\"><div align=\"center\">{$history}</div></td>
					</tr>
					<tr>
						<td class=\"roster_header\" width=\"75%\">".roster_LAN_AWARD_REQUIREMENTS."</td>
					</tr>
					<tr>
						<td class=\"roster_row1\"><div align=\"center\">{$requirements}</div></td>
					</tr>
					<tr>
						<td class=\"roster_header\" colspan=\"2\"></td>
					</tr>";
				}
			$tables .= "</table><br /><br />";
		}

		return $tables;
	} // end function roster_show

}// end class roster_html
?>