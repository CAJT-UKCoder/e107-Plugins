<?php

class userinfo_html {

	function uinfo_show($m_id) {
	global $sql;

		// get the member
		$member_q = $sql->db_Select("roster_members", "*", "roster_member_id='".$m_id."'");
		$member_a = $sql->db_Fetch(MYSQL_ASSOC);
		$rank = explode(",", $member_a['roster_member_rank']);
      		$enlisted = date("dMY", $member_a['roster_member_enlisted']);
      		$enlisted = strtoupper($enlisted);
      		$patterns[0] = "/JUN/";
      		$patterns[1] = "/JUL/";
      		$patterns[2] = "/SEP/";
      		$replacements[0] = "JUNE";
      		$replacements[1] = "JULY";
      		$replacements[2] = "SEPT";
      		$enlisted = preg_replace($patterns, $replacements, $enlisted);
		$time = time();
		$timeinservice = ceil(($time - $member_a['roster_member_enlisted'])/(24*60*60));
		if($timeinservice == 1){
			$timeinservice = $timeinservice." day";
		}else{
			$timeinservice = $timeinservice." days";
		}

		$ureport_q = $sql->db_Select("roster_members", "*", "roster_member_id='".$member_a['roster_member_unitreport']."'");
		$ureport_a = $sql->db_Fetch(MYSQL_ASSOC);
		$ureport_rank = explode(",", $ureport_a['roster_member_rank']);
		$ureport = $ureport_rank[2]."-".$ureport_a['roster_member_name'];

		$dreport_q = $sql->db_Select("roster_members", "*", "roster_member_id='".$member_a['roster_member_dutyreport']."'");
		$dreport_a = $sql->db_Fetch(MYSQL_ASSOC);
		$dreport_rank = explode(",", $dreport_a['roster_member_rank']);
		$dreport = $dreport_rank[2]."-".$dreport_a['roster_member_name'];

		$rankdate = date("dMY", $member_a['roster_member_rankdate']);
      		$rankdate = strtoupper($rankdate);
      		$patterns[0] = "/JUN/";
      		$patterns[1] = "/JUL/";
      		$patterns[2] = "/SEP/";
      		$replacements[0] = "JUNE";
      		$replacements[1] = "JULY";
      		$replacements[2] = "SEPT";
      		$rankdate = preg_replace($patterns, $replacements, $rankdate);

		$time = time();
		$timeingrade = ceil(($time - $member_a['roster_member_rankdate'])/(24*60*60));
		if($timeingrade == 1){
			$timeingrade = $timeingrade." day";
		}
		else{
			$timeingrade = $timeingrade." days";
		}

		$pfile = nl2br($member_a['roster_member_pfile']);
		$xfireprofile = "<img src=http://miniprofile.xfire.com/bg/bg/type/0/".$member_a['roster_member_xfire'].".png border='0'/>";
		$profile = "<img src=/e107_plugins/roster/images/profilepicture/".$member_a['roster_member_image'].".jpg border='0' />";
		return '
			<table class="roster" width="100%">
				<tr>
					<td class="roster_main">'.$rank[0].' '.$member_a['roster_member_name'].'</td>
				</tr>
				<tr>
					<td class="roster_uniform" >'.$profile.'</td>
				</tr>
			</table>
			<table class="roster" width="100%">
				<tr>
					<td class="roster_main" colspan="5">'.roster_LAN_UINFO_PJACKET.'</td>
				</tr>
				<tr>
					<td rowspan="10" width="1%"><div align="center"><img src="'.e_PLUGIN.'roster/images/ranks/big/'.$rank[2].'.png" border="0" /></div></td>	
					<td width="25%"><div align="center">'.roster_LAN_UINFO_LOCATION.':</DIV></td>
					<td width="74%"><div align="center">'.$member_a['roster_member_location'].' - <img src="'.e_PLUGIN.'roster/images/flags/'.$member_a['roster_member_location'].'.GIF" border="0" /> </DIV></td>
				</tr>
				<tr>	
					<td width="25%"><div align="center">'.roster_LAN_UINFO_SERIAL.':</div></td>
					<td width="74%"><div align="center">'.$member_a['roster_member_serial'].'</div></td>
				</tr>
				<tr>
					<td width="25%"><div align="center">'.roster_LAN_UINFO_ENLISTED.':</div></td>
					<td width="74%"><div align="center">'.$enlisted.'</div></td>
				</tr>
				<tr>	
					<td width="25%"><div align="center">'.roster_LAN_UINFO_TIMEINSERVICE.':</div></td>
					<td width="74%"><div align="center">'.$timeinservice.'</div></td>
				</tr>
				<tr>
					<td width="25%"><div align="center">'.roster_LAN_UINFO_UASSIGN.':</div></td>
					<td width="74%"><div align="center">'.$member_a['roster_member_unit'].'</div></td>
				</tr>
				<tr>	
					<td width="25%"><div align="center">'.roster_LAN_UINFO_REPORT.':</div></td>
					<td width="74%"><div align="center">'.$ureport.'</div></td>
				</tr>
				<tr>
					<td width="25%"><div align="center">'.roster_LAN_UINFO_DASSIGN.':</div></td>
					<td width="74%"><div align="center">'.$member_a['roster_member_duty'].'</div></td>
				</tr>
				<tr>
					<td width="25%"><div align="center">'.roster_LAN_UINFO_REPORT.':</div></td>
					<td width="74%"><div align="center">'.$dreport.'</div></td>
				</tr>
				<tr>
					<td width="25%"><div align="center">'.roster_LAN_UINFO_RANKDATE.':</div></td>
					<td width="74%"><div align="center">'.$rankdate.'</div></td>
				</tr>
				<tr>	
					<td width="25%"><div align="center">'.roster_LAN_UINFO_TIMEINGRADE.':</div></td>
					<td width="74%"><div align="center">'.$timeingrade.'</div></td>
				</tr>
			</table>
			<table class="roster" width="100%">
				<tr>
					<td class="roster_main"><div align="center">'.roster_LAN_UINFO_PFILE.'</div></td>
				</tr>
				<tr>
					<td><div align="center">'.$pfile.'</div></td>
				</tr>
				<tr>
					<td class="roster_main"><div align="center">'.roster_LAN_UINFO_AFILE.'</div></td>
				</tr>
				<tr>
					<td><div align="center">'.$member_a['roster_member_afile'].'</div></td>
				</tr>
			</table>
			<table class="roster" width="100%">
				<tr>
					<td class="roster_main">'.roster_LAN_UINFO_XFIREPROFILE.'</td>
				</tr>
				<tr>
					<td class="roster_xfireprofile">'.$xfireprofile.'</td>
				</tr>
			</table>
		';
	} // end function uinfo_show()

} // end class userinfo
?>