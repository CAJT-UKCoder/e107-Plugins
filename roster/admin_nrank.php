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
$pageid = 'newrank';

//set rank upload path
$target_path = "images/ranks/";

//admin html file
require_once("html/admin/roster_adminnrank.php");

$html = new adminnrank_html;

$text = $html->nrank_header();


switch($_GET['action']){
	case 'do_add':
		if($_POST['submit']){
			$rank_name = $tp->toDB($_POST['r_name']);
			$rank_short = $tp->toDB(strtoupper($_POST['r_shortname']));
			$rank_grade = $tp->toDB($_POST['r_grade']);
			$rank_info = $tp->toDB($_POST['r_info']);
			$ranks_q = $sql->db_Select("roster_ranks", "*", "roster_rank_id!='0' ORDER BY roster_rank_order DESC LIMIT 1");
			$ranks_a = $sql->db_Fetch(MYSQL_ASSOC);
			$rank_order = $ranks_a['roster_rank_order'] + 1;
			//Put the rank in a good format for the database
			//Example format for Colonel:    Colonel,(COL),COL
			//                                                    rankname,(acronym),acronym
			$rank_formatted = $rank_name.",(".$rank_short."),".$rank_short;
			$sql->db_Insert("roster_ranks", "'', '".$rank_formatted."', '".$rank_grade."', '".$rank_info."','".$rank_order."'");
			{
						unlink($target);
						$text .= $html->nrank_success($rank_name);
					}					
				
			//End upload rank picture		
		}else{
			$text .= $html->nrank_form();
		}
	break;
	default:
		$text .= $html->nrank_form();
}
$text .= $html->nrank_footer();

// output the text
$ns->tablerender(roster_LAN_ADMIN_NRANK_TITLE, $text);

require_once(e_ADMIN."footer.php");

?>