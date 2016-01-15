	<?php

class ranks_html {

	function ranks_show() {
	global $sql, $sql2;

		// make the groups
		$ranks_q = $sql->db_Select("roster_ranks", "*", "roster_rank_id!='0' ORDER BY roster_rank_order ASC");
		$tables = "";
		while($row = $sql->db_Fetch(MYSQL_ASSOC)){
			$i++;
			$rank = explode(",",$row['roster_rank_name']);
			$grade = $row['roster_rank_grade'];
			$info = $row['roster_rank_info'];
			$grade_formatted = roster_LAN_RANK_GRADE." {$grade}, {$rank['2']}";
			$tables .= "<table class=\"roster\" width=\"100%\">
					<tr>
						<td class=\"roster_main\" colspan=\"2\">{$rank[0]}</td>
					</tr>
					<tr>
						<td class=\"roster_header\" width=\"25%\">{$rank[0]}</td>
						<td class=\"roster_header\" width=\"75%\">".roster_LAN_RANK_INFO."</td>
					</tr>
				";
			if($i%2==0){
				$tables .= "<tr>
						<td class=\"roster_row1\"><div align=\"center\"><img src=\"".e_PLUGIN."roster/images/ranks/big/{$rank[2]}.png\" border=\"0\" /></div></td>
						<td class=\"roster_row1\"><div align=\"center\">{$info}</div></td>
					</tr>
					<tr>
						<td class=\"roster_header\" width=\"25%\">{$grade_formatted}</td>
						<td class=\"roster_header\" width=\"75%\"></td>
					</tr>";
			}else{
				$tables .= "<tr>
						<td class=\"roster_row2\"><div align=\"center\"><img src=\"".e_PLUGIN."roster/images/ranks/big/{$rank[2]}.png\" border=\"0\" /></div></td>
						<td class=\"roster_row2\"><div align=\"center\">{$info}</div></td>
					</tr>
					<tr>
						<td class=\"roster_header\" width=\"25%\">{$grade_formatted}</td>
						<td class=\"roster_header\" width=\"75%\"></td>
					</tr>";
			}
			$tables .= "</table><br /><br />";
		}

		return $tables;
	} // end function roster_show

}// end class roster_html
?>