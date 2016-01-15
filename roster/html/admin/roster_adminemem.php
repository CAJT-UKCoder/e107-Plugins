<?php

class adminemem_html {

	function emem_header() {
		return '
			<center>
			<table class="roster" cellspacing="0" width="100%">
				<tr>
					<td class="aroster_main" colspan="5"><strong>'.roster_LAN_ADMIN_EMEM_TITLE.'</strong></td>
				</tr>
		';
	} // end function emem_header

	
	function emem_show() {
	global $sql, $sql2;

		//get the groups
		$groups_q = $sql->db_Select("roster_groups", "*", "roster_group_id!='0' ORDER BY roster_group_order ASC");
		$members = "</table>";
		while($row = $sql->db_Fetch(MYSQL_ASSOC)){
			$tables .= "<table class=\"roster\" width=\"100%\">
				<tr>
					<td class=\"roster_header\" colspan=\"5\">{$row['roster_group_name']}</td>
				</tr>
				<tr>
					<td class=\"aroster_header\" width=\"35%\">".roster_LAN_ADMIN_EMEM_NAME."</td>
					<td class=\"aroster_header\" width=\"35%\">".roster_LAN_ADMIN_EMEM_ENLISTED."</td>
					<td class=\"aroster_header\" width=\"35%\">".roster_LAN_ADMIN_EMEM_STATUS."</td>
					<td class=\"aroster_header\" width=\"35%\">".roster_LAN_ADMIN_EMEM_EDIT."</td>
				</tr>
				";

			// get the members
			$members_q = $sql2->db_Select("roster_members", "*", "roster_member_group='".$row['roster_group_id']."' ORDER BY roster_member_ranknum ASC, roster_member_name ASC");
			$count = 0;
			while($row2 = $sql2->db_Fetch(MYSQL_ASSOC)){
				$i++;
				if($row2['roster_member_status'] == "Retired"){
					$status = "<font color=\"#ff0000\">Retired</font>";
				}else if($row2['roster_member_status'] == "Reserve"){
					$status = "<font color=\"#d9c30a\">Reserve</font>";
				}else if($row2['roster_member_status'] == "On Leave"){
					$status = "<font color=\"#ff9c00\">On Leave</font>";
				}else{
					$status = "<font color=\"#48E702\">Active Duty</font>";
				}	
				$rank = explode(",", $row2['roster_member_rank']);
				$enlisted = date("dMY", $row2['roster_member_enlisted']);
				$enlisted = strtoupper($enlisted);
				$patterns[0] = "/JUN/";
				$patterns[1] = "/JUL/";
				$patterns[2] = "/SEP/";
				$replacements[0] = "JUNE";
				$replacements[1] = "JULY";
				$replacements[2] = "SEPT";
				$enlisted = preg_replace($patterns, $replacements, $enlisted);
				$tables .= "<tr>";
				if($i%2 == 0){
					$tables .= "<td class=\"aroster_row2\" width=\"35%\">{$rank[0]} {$row2['roster_member_name']}</td>";
					$tables .= "<td class=\"aroster_row2\" width=\"35%\">{$enlisted}</td>";
					$tables .= "<td class=\"aroster_row2\" width=\"35%\">{$status}</td>";
					$tables .= "<td class=\"aroster_row2\" width=\"10%\"><a href=\"admin_emem.php?action=edit&m_id={$row2['roster_member_id']}\">".roster_LAN_ADMIN_EMEM_EDIT."</a></td>";
				}else{
					$tables .= "<td class=\"aroster_row2\" width=\"35%\">{$rank[0]} {$row2['roster_member_name']}</td>";
					$tables .= "<td class=\"aroster_row2\" width=\"35%\">{$enlisted}</td>";
					$tables .= "<td class=\"aroster_row2\" width=\"35%\">{$status}</td>";
					$tables .= "<td class=\"aroster_row2\" width=\"10%\"><a href=\"admin_emem.php?action=edit&m_id={$row2['roster_member_id']}\">".roster_LAN_ADMIN_EMEM_EDIT."</a></td>";
				}
				$tables .= "</tr>";
			}
			$tables .= "</table><br />";
		}
		$tables .= "<table style=\"border-width : thin; border-style : solid; border-color: #000000; text-align: center;\" cellspacing=\"0\" width=\"80%\">";

		return $tables;
		
	} // end function emem_show
			


	function emem_form($m_id) {
	global $sql;

		// get this member
		$member_q = $sql->db_Select("roster_members", "*", "roster_member_id='".$m_id."'");
		$member_a = $sql->db_Fetch(MYSQL_ASSOC);

		// get the groups
		$groups_q = $sql->db_Select("roster_groups", "*", "roster_group_id!='0' ORDER BY roster_group_id ASC");
		$groups = "<select class=\"tbox\" name=\"m_group\">";
		while($row = $sql->db_Fetch(MYSQL_ASSOC)){
			if($row['roster_group_id'] == $member_a['roster_member_group']){
				$groups .= "<option value=\"{$row['roster_group_id']}\" selected=\"selected\">{$row['roster_group_name']}</option>";
			}else{
				$groups .= "<option value=\"{$row['roster_group_id']}\">{$row['roster_group_name']}</option>";
			}
		}
		$groups .= "</select>";

		//get the ranks
		$ranks_q = $sql->db_Select("roster_ranks", "*", "roster_rank_id!='0' ORDER BY roster_rank_order ASC");
		while($row = $sql->db_Fetch(MYSQL_ASSOC)){
			$i+=1;
			$ranks[$i] = $row['roster_rank_name'].",".$row['roster_rank_order'];
		}


		$m_rank = "<select class=\"tbox\" name=\"m_rank\">";
		foreach($ranks as $rank){
			$specific = explode(",", $rank);
			if($rank == $member_a['roster_member_rank']){
				$m_rank .= "<option value=\"{$rank}\" selected=\"selected\">{$specific[0]} {$specific[1]}</option>";
			}else{
 				$m_rank .= "<option value=\"{$rank}\">{$specific[0]} {$specific[1]}</option>";
			}
		}
		$m_rank .= "</select>";


		// make the reports to
		$members_q = $sql->db_Select("roster_members", "*", "roster_member_id!='0' ORDER BY roster_member_ranknum DESC, roster_member_name ASC");
		$m_ureport = "<select class=\"tbox\" name=\"m_ureport\"><option value=\"\"> </option>";
		$m_dreport = "<select class=\"tbox\" name=\"m_dreport\"><option value=\"\"> </option>";
		while($row = $sql->db_Fetch(MYSQL_ASSOC)){
			$c_rank = explode(",", $row['roster_member_rank']);
			$c_name = $c_rank[2]."-".$row['roster_member_name'];

			if($row['roster_member_id'] == $member_a['roster_member_unitreport']){
				$m_ureport .= "<option value=\"{$row['roster_member_id']}\" selected=\"selected\">{$c_name}</option>";
			}else{
				$m_ureport .= "<option value=\"{$row['roster_member_id']}\">{$c_name}</option>";
			}

			if($row['roster_member_id'] == $member_a['roster_member_dutyreport']){
				$m_dreport .= "<option value=\"{$row['roster_member_id']}\" selected=\"selected\">{$c_name}</option>";
			}else{
				$m_dreport .= "<option value=\"{$row['roster_member_id']}\">{$c_name}</option>";
			}
		}
		$m_ureport .= "</select>";
		$m_dreport .= "</select>";

		$enlistdate = date("m/d/Y", $member_a['roster_member_enlisted']);
		$rankdate = date("m/d/Y", $member_a['roster_member_rankdate']);

		// status
		if($member_a['roster_member_status'] == "Retired"){
			$status = "<option value=\"Active Duty\">Active Duty</option> <option value=\"Retired\" selected=\"selected\">Retired</option> <option value=\"Reserve\">Reserve</option> <option value=\"On Leave\">On Leave</option>";
		}else if($member_a['roster_member_status'] == "Reserve"){
			$status = "<option value=\"Active Duty\">Active Duty</option> <option value=\"Retired\">Retired</option> <option value=\"Reserve\" selected=\"selected\">Reserve</option> <option value=\"On Leave\">On Leave</option>";
		}else if($member_a['roster_member_status'] == "On Leave"){
			$status = "<option value=\"Active Duty\">Active Duty</option> <option value=\"Retired\">Retired</option> <option value=\"Reserve\">Reserve</option> <option value=\"On Leave\" selected=\"selected\">On Leave</option>";
		}else{
			$status = "<option value=\"Active Duty\" selected=\"selected\">Active Duty</option> <option value=\"Retired\">Retired</option> <option value=\"Reserve\">Reserve</option> <option value=\"On Leave\">On Leave</option>";
		}

		return '
			<form action="admin_emem.php?action=do_edit&m_id='.$member_a['roster_member_id'].'" method="POST">
			<tr>
				<td class="aroster_row1" width="35%">'.roster_LAN_ADMIN_EMEM_GROUP.':</td>
				<td class="aroster_row2" width="65%">'.$groups.'</td>
			</tr>
			<tr>
				<td class="aroster_row1" width="35%">'.roster_LAN_ADMIN_EMEM_NAME.':</td>
				<td class="aroster_row2" width="65%"><input class="tbox" type="text" name="m_name" size="35" value="'.$member_a['roster_member_name'].'" /></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="35%">'.roster_LAN_ADMIN_EMEM_IMAGE.':</td>
				<td class="aroster_row2" width="65%"><input class="tbox" type="text" name="m_image" size="35" value="'.$member_a['roster_member_image'].'" /></td>
			</tr>			
			<tr>
				<td class="aroster_row1" width="35%">'.roster_LAN_ADMIN_EMEM_SERIAL.':</td>
				<td class="aroster_row2" width="65%"><input class="tbox" type="text" name="m_serial" size="35" value="'.$member_a['roster_member_serial'].'" /></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="35%">'.roster_LAN_ADMIN_EMEM_ENLISTED.':</td>
				<td class="aroster_row2" width="65%"><input class="tbox" type="text" name="m_enlisted" size="35" value="'.$enlistdate.'" /></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="35%">'.roster_LAN_ADMIN_EMEM_RANK.':</td>
				<td class="aroster_row2" width="65%">'.$m_rank.'</td>
			</tr>
			<tr>
				<td class="aroster_row1" width="35%">'.roster_LAN_ADMIN_EMEM_RANKDATE.':</td>
				<td class="aroster_row2" width="65%"><input class="tbox" type="text" name="m_rankdate" size="35" value="'.$rankdate.'" /></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="35%">'.roster_LAN_ADMIN_EMEM_UASSIGN.':</td>
				<td class="aroster_row2" width="65%"><input class="tbox" type="text" name="m_uassign" size="35" value="'.$member_a['roster_member_unit'].'" /></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="35%">'.roster_LAN_ADMIN_EMEM_REPORT.':</td>
				<td class="aroster_row2" width="65%">'.$m_ureport.'</td>
			</tr>
			<tr>
				<td class="aroster_row1" width="35%">'.roster_LAN_ADMIN_EMEM_DASSIGN.':</td>
				<td class="aroster_row2" width="65%"><input class="tbox" type="text" name="m_dassign" size="35" value="'.$member_a['roster_member_duty'].'" /></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="35%">'.roster_LAN_ADMIN_EMEM_REPORT.':</td>
				<td class="aroster_row2" width="65%">'.$m_dreport.'</td>
			</tr>
			<tr>
				<td class="aroster_row1" width="35%">'.roster_LAN_ADMIN_EMEM_STATUS.':</td>
				<td class="aroster_row2" width="65%"><select class="tbox" name="m_status">'.$status.'</select></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="35%">'.roster_LAN_ADMIN_EMEM_PFILE.':</td>
				<td class="aroster_row2" width="65%"><textarea class="tbox" name="m_pfile" cols="60" rows="15">'.$member_a['roster_member_pfile'].'</textarea><br /><br /></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="35%">'.roster_LAN_ADMIN_EMEM_AFILE.':</td>
				<td class="aroster_row2" width="65%"><textarea class="tbox" name="m_afile" cols="60" rows="15">'.$member_a['roster_member_afile'].'</textarea><br /><br /></td>
			</tr>
			<tr>
				<td class="aroster_row1" width="35%">'.roster_LAN_ADMIN_EMEM_LOCATION.':</td>
				<td class="aroster_row2" width="65%"><select class="tbox" name="m_loc"><option value="'.$member_a['roster_member_location'].'">'.$member_a['roster_member_location'].'</option>				
				<option value="Afghanistan">Afghanistan</option>
				<option value="Albania">Albania</option> 
				<option value="Algeria">Algeria</option> 
				<option value="Andorra">Andorra</option> 
				<option value="Angola">Angola</option> 
				<option value="Argentina">Argentina</option>
				<option value="Armenia">Armenia</option>
				<option value="Australia">Australia</option>				
				<option value="Austria">Austria</option>
				<option value="Azerbaijan">Azerbaijan</option> 
				<option value="Bahrain">Bahrain</option>
				<option value="Bangladesh">Bangladesh</option> 
				<option value="Benin">Benin</option>
				<option value="Belarus">Belarus</option>
				<option value="Belgium">Belgium</option> 				
				<option value="Bhutan">Bhutan</option>
				<option value="Bolivia">Bolivia</option>
				<option value="Bosnia and Herzigovina">Bosnia and Herzigovina</option> 
				<option value="Botswana">Botswana</option> 
				<option value="Brazil">Brazil</option>
				<option value="Brunei">Brunei</option>
				<option value="Bulgaria">Bulgaria</option> 
				<option value="Burkina Faso">Burkina Faso</option> 
				<option value="Burundi">Burundi</option>
				<option value="Cambodia">Cambodia</option> 				
				<option value="Cameroon">Cameroon</option> 
				<option value="Canada">Canada</option>
				<option value="Cape Verde">Cape Verde</option> 
				<option value="Central African Republic">Central African Republic</option> 
				<option value="Chad">Chad</option>
				<option value="Chile">Chile</option> 
				<option value="China">China</option> 
				<option value="Colombia">Colombia</option>
				<option value="Comoros">Comoros</option>
				<option value="Croatia">Croatia</option> 
				<option value="Cyprus">Cyprus</option>
				<option value="Czech Republic">Czech Republic</option> 
				<option value="Democratic Republic of Congo">Democratic Republic of Congo</option>
				<option value="Denmark">Denmark</option>
				<option value="Djibouti">Djibouti</option>
				<option value="East Timor">East Timor</option>
				<option value="Ecuador">Ecuador</option>
				<option value="Egypt">Egypt</option> 
				<option value="England">England</option>
				<option value="Equatorial Guinea">Equatorial Guinea</option> 
				<option value="Eritrea">Eritrea</option> 
				<option value="Estonia">Estonia</option>
				<option value="Ethiopia">Ethiopia</option>
				<option value="Finland">Finland</option> 
				<option value="France">France</option>
				<option value="Gabon">Gabon</option>
				<option value="Gambia">Gambia</option>
				<option value="Georgia">Georgia</option>
				<option value="Germany">Germany</option> 
				<option value="Ghana">Ghana</option> 
				<option value="Great Britain">Great Britain</option> 
				<option value="Greece">Greece</option>
				<option value="Guinea Bissau">Guinea Bissau</option> 
				<option value="Guinea">Guinea</option>
				<option value="Guyana">Guyana</option>
				<option value="Haiti">Haiti</option>				
				<option value="Honduras">Honduras</option>				
				<option value="Hungary">Hungary</option> 
				<option value="Iceland">Iceland</option> 
				<option value="India">India</option> 
				<option value="Indonesia">Indonesia</option> 
				<option value="Iran">Iran</option> 
				<option value="Iraq">Iraq</option>
				<option value="Ireland">Ireland</option>
				<option value="Israel">Israel</option>
				<option value="Italy">Italy</option>
				<option value="Ivory Coast">Ivory Coast</option>
				<option value="Japan">Japan</option>
				<option value="Jordan">Jordan</option>
				<option value="Khazakhstan">Khazakhstan</option>
				<option value="Kenya">Kenya</option>				
				<option value="Kosovo">Kosovo</option>
				<option value="Kuwait">Kuwait</option>
				<option value="Laos">Laos</option> 
				<option value="Latvia">Latvia</option>
				<option value="Lebanon">Lebanon</option> 
				<option value="Lesotho">Lesotho</option> 
				<option value="Liberia">Liberia</option> 
				<option value="Libya">Libya</option>
				<option value="Liechtenstein">Liechtenstein</option> 
				<option value="Lithuania">Lithuania</option> 
				<option value="Luxembourg">Luxembourg</option>
				<option value="Macedonia">Macedonia</option>
				<option value="Macau">Macau</option>
				<option value="Madagascar">Madagascar</option>
				<option value="Malawi">Malawi</option>
				<option value="Malaysia">Malaysia</option> 
				<option value="Maldives">Maldives</option>
				<option value="Mali">Mali</option>
				<option value="Malta">Malta</option>
				<option value="Mauritania">Mauritania</option>
				<option value="Mauritius">Mauritius</option>
				<option value="Mexico">Mexico</option>
				<option value="Moldova">Moldova</option> 
				<option value="Monaco">Monaco</option>
				<option value="Mongolia">Mongolia</option> 
				<option value="Montenegro">Montenegro</option>
				<option value="Morocco">Morocco</option>
				<option value="Mozambique">Mozambique</option>
				<option value="Myanmar">Myanmar</option>
				<option value="Namibia">Namibia</option>
				<option value="Nepal">Nepal</option> 
				<option value="Netherlands">Netherlands</option>
				<option value="New Zealand">New Zealand</option>
				<option value="Niger">Niger</option>
				<option value="Nigeria">Nigeria</option> 
				<option value="North Korea">North Korea</option>
				<option value="Norway">Norway</option> 
				<option value="Oman">Oman</option> 
				<option value="Pakistan">Pakistan</option>
				<option value="Paraguay">Paraguay</option>
				<option value="Peru">Peru</option>
				<option value="Poland">Poland</option>
				<option value="Portugal">Portugal</option>
				<option value="Qatar">Qatar</option>
				<option value="Republic of Congo">Republic of Congo</option>				
				<option value="Romania">Romania</option> 
				<option value="Russia">Russia</option>
				<option value="Rwanda">Rwanda</option>
				<option value="San Marino">San Marino</option>
				<option value="Sao Tome and Principe">Sao Tome and Principe</option>
				<option value="Saudi Arabia">Saudi Arabia</option> 
				<option value="Scotland">Scotland</option>
				<option value="Senegal">Senegal</option>
				<option value="Seychelles">Seychelles</option>
				<option value="Serbia">Serbia</option>
				<option value="Sierra Leone">Sierra Leone</option>
				<option value="Singapore">Singapore</option> 
				<option value="Slovakia">Slovakia</option> 
				<option value="Slovenia">Slovenia</option>
				<option value="Somalia">Somalia</option>
				<option value="South Africa">South Africa</option>
				<option value="South Korea">South Korea</option> 
				<option value="Spain">Spain</option>
				<option value="Sri Lanka">Sri Lanka</option>
				<option value="Sudan">Sudan</option>				
				<option value="Suriname">Suriname</option> 
				<option value="Swaziland">Swaziland</option>
				<option value="Sweden">Sweden</option> 
				<option value="Switzerland">Switzerland</option>
				<option value="Syria">Syria</option>
				<option value="Taiwan">Taiwan</option> 
				<option value="Tajikistan">Tajikistan</option>
				<option value="Tanzania">Tanzania</option>
				<option value="Thailand">Thailand</option>
				<option value="Togo">Togo</option> 
				<option value="Tunisia">Tunisia</option>
				<option value="Turkey">Turkey</option> 
				<option value="Turkmenistan">Turkmenistan</option>
				<option value="Uganda">Uganda</option>
				<option value="Ukraine">Ukraine</option>
				<option value="United Arab Emirates">United Arab Emirates</option>
				<option value="United States of America">United States of America</option>
				<option value="Uruguay">Uruguay</option> 
				<option value="Uzbekistan">Uzbekistan</option> 
				<option value="Vatican City">Vatican City</option>
				<option value="Venezuela">Venezuela</option>
				<option value="Vietnam">Vietnam</option> 
				<option value="Wales">Wales</option>
				<option value="Yemen">Yemen</option>	
 				<option value="Zambia">Zambia</option> 
				<option value="Zimbabwe">Zimbabwe</option>
				</td>
			<tr>
			<tr>
				<td class="aroster_row1" width="35%">'.roster_LAN_ADMIN_EMEM_XFIRE.':</td>
				<td class="aroster_row2" width="65%"><input class="tbox" type="text" name="m_xfire" size="35" value="'.$member_a['roster_member_xfire'].'" /></td>
			</tr>
			<tr>
				<td class="aroster_footer" colspan="2"><input type="checkbox" name="delete_member" value="delete_member" /> '.roster_LAN_ADMIN_EMEM_DELETE.'</td>
			<tr>
				<td class="aroster_footer" colspan="2"><input class="tbox" type="submit" name="submit" value="'.roster_LAN_ADMIN_EMEM_EDIT.'" /></td>
			</tr>

			</form>
		';
	} // end function emem_form


	function emem_footer() {
		return '
		
			</table>
			</center>
		';
	} // end function emem_footer


	function emem_edited(){
		return '
			<tr>
				<td class="center">'.roster_LAN_ADMIN_EMEM_EDITED.'.</td>
			</tr>
		';
	} // end function emem_edited


	function emem_deleted(){
		return '
			<tr>
				<td class="center">'.roster_LAN_ADMIN_EMEM_DELETED.'.</td>
			</tr>
		';
	} // end function emem_deleted

} // end class adminemem_html
?>