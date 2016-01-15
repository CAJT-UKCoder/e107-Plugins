<?php
// incude e107 class file, it's required
require_once("../../class2.php");
//style for our table
$eplug_css = "html/style.css";
// check if user is admin, if not redirect to home page
if (!getperms("P")) {
	header("location:".e_HTTP."index.php");
	exit;
}
// include language file
include_lan(e_PLUGIN."roster/languages/admin/".e_LANGUAGE.".php");
// include admin header
require_once(e_ADMIN."auth.php");
// Set the active menu option for admin_menu.php
$pageid = 'newaward';
//set award upload path
$target_path = "images/awards/";
//admin html file
require_once("html/admin/roster_adminnaward.php");
$html = new adminnaward_html;
$text = $html->naward_header();
switch($_GET['action']){
	case 'do_add':
		if($_POST['submit']){
			$award_name = $tp->toDB($_POST['a_name']);
			$award_short = $tp->toDB(strtoupper($_POST['a_shortname']));
			$award_history = $tp->toDB($_POST['a_history']);
			$award_requirements = $tp->toDB($_POST['a_requirements']);
			$awards_q = $sql->db_Select("roster_awards", "*", "roster_award_id!='0' ORDER BY roster_award_order DESC LIMIT 1");
			$awards_a = $sql->db_Fetch(MYSQL_ASSOC);
			$award_order = $awards_a['roster_award_order'] + 1;
			//Put the award in a good format for the database
			//Example format for Colonel:    Colonel,(COL),COL
			//                                                    awardname,(acronym),acronym
			$award_formatted = $award_name.",(".$award_short."),".$award_short;
			$sql->db_Insert("roster_awards", "'', '".$award_formatted."', '".$award_history."', '".$award_requirements."','".$award_order."'");{
					unlink($target);
				}
			}
			$text .= $html->naward_success($award_name);
			//End upload award picture		
	break;
	default:
		$text .= $html->naward_form();
}
$text .= $html->naward_footer();
// output the text
$ns->tablerender(roster_LAN_ADMIN_NAWARD_TITLE, $text);
require_once(e_ADMIN."footer.php");
?>