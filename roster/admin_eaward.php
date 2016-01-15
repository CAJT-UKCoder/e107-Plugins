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
$pageid = 'editaward';

//set award upload path
$target_path = "images/awards/";

//admin html file
require_once("html/admin/roster_admineaward.php");
$html = new admineaward_html;

$text = $html->eaward_header();

switch($_GET['action']){
	case 'edit':
		$text .= $html->eaward_form($_GET['a_id']);
	break;
	case 'do_edit':
		if($_POST['do_order']){
			foreach($_POST['a_order'] as $a_id=>$order){
				$sql->db_Update("roster_awards", "roster_award_order='".$order."' WHERE roster_award_id='".$a_id."'");
			}
			$text .= $html->eaward_updated();
		}else if($_POST['do_edit']){
			$award_name = $tp->toDB($_POST['a_name']);
			$award_short = $tp->toDB(strtoupper($_POST['a_shortname']));
			$award_history = $tp->toDB($_POST['a_history']);
			$award_requirements = $tp->toDB($_POST['a_requirements']);
			$award_formatted = $award_name.",(".$award_short."),".$award_short;
			//get the old award before update..
			$award_q = $sql->db_Select("roster_awards", "roster_award_name", "roster_award_id='".$_GET['a_id']."'");
			$award_a = $sql->db_Fetch(MYSQL_ASSOC);
			$award = explode(",",$award_a['roster_award_name']);
			//rename the thumb and big image of the award, uploading a new one is not yet supported if anyone fixes it let me now :)		
			$oldname[] = $target_path."small/sml".$award[2].".png";
			$oldname[] = $target_path."big/".$award[2].".png";
			$newname[] = $target_path."small/sml".$award_short.".png";
			$newname[] = $target_path."big/".$award_short.".png";
			for ($i=0; $i<count($oldname); $i++) {
				rename($oldname[$i],$newname[$i]);
			};	
			$sql->db_Update("roster_awards", "roster_award_name='".$award_formatted."', roster_award_history='".$award_history."', roster_award_requirements='".$award_requirements."' WHERE roster_award_id='".$_GET['a_id']."'");
			$text .= $html->eaward_success($award_name);
		}else{
			$text .= $html->eaward_show();
		}
	break;
	case 'delete':
				//get the award picture to delete it..
				$award_q = $sql->db_Select("roster_awards", "roster_award_name", "roster_award_id='".$_GET['a_id']."'");
				$award_a = $sql->db_Fetch(MYSQL_ASSOC);
				$award = explode(",",$award_a['roster_award_name']);
				$oldname[] = $target_path."small/sml".$award[2].".png";
				$oldname[] = $target_path."big/".$award[2].".png";
				for ($i=0; $i<count($oldname); $i++) {
					unlink($oldname[$i]);
				};	
				$sql->db_Delete("roster_awards", "roster_award_id='".$_GET['a_id']."'");
				$text .= $html->eaward_deleted();
				break;
	default:
		$text .= $html->eaward_show();
}

$text .= $html->eaward_footer();

// output the text
$ns->tablerender(roster_LAN_ADMIN_EAWARD_TITLE, $text);

require_once(e_ADMIN."footer.php");

?>