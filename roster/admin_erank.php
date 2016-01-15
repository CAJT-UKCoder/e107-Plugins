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
$pageid = 'editrank';

//set rank upload path
$target_path = "images/ranks/";

//admin html file
require_once("html/admin/roster_adminerank.php");
$html = new adminerank_html;

$text = $html->erank_header();

switch($_GET['action']){
	case 'edit':
		$text .= $html->erank_form($_GET['r_id']);
	break;
	case 'do_edit':
		if($_POST['do_order']){
			foreach($_POST['r_order'] as $r_id=>$order){
				$sql->db_Update("roster_ranks", "roster_rank_order='".$order."' WHERE roster_rank_id='".$r_id."'");
			}
			$text .= $html->erank_updated();
		}else if($_POST['do_edit']){
			$rank_name = $tp->toDB($_POST['r_name']);
			$rank_short = $tp->toDB(strtoupper($_POST['r_shortname']));
			$rank_grade = $tp->toDB($_POST['r_grade']);
			$rank_info = $tp->toDB($_POST['r_info']);
			$rank_formatted = $rank_name.",(".$rank_short."),".$rank_short;
			//get the old rank before update..
			$rank_q = $sql->db_Select("roster_ranks", "roster_rank_name", "roster_rank_id='".$_GET['r_id']."'");
			$rank_a = $sql->db_Fetch(MYSQL_ASSOC);
			$rank = explode(",",$rank_a['roster_rank_name']);
			//rename the thumb and big image of the rank, uploading a new one is not yet supported if anyone fixes it let me now :)		
			$oldname[] = $target_path."small/sml".$rank[2].".png";
			$oldname[] = $target_path."big/".$rank[2].".png";
			$newname[] = $target_path."small/sml".$rank_short.".png";
			$newname[] = $target_path."big/".$rank_short.".png";
			for ($i=0; $i<count($oldname); $i++) {
				rename($oldname[$i],$newname[$i]);
			};	
			$sql->db_Update("roster_ranks", "roster_rank_name='".$rank_formatted."', roster_rank_grade='".$rank_grade."', roster_rank_info='".$rank_info."' WHERE roster_rank_id='".$_GET['r_id']."'");
	
			$text .= $html->erank_success($rank_name);
		}else{
			$text .= $html->erank_show();
		}
	break;
	case 'delete':
				//get the rank picture to delete it..
				$rank_q = $sql->db_Select("roster_ranks", "roster_rank_name", "roster_rank_id='".$_GET['r_id']."'");
				$rank_a = $sql->db_Fetch(MYSQL_ASSOC);
				$rank = explode(",",$rank_a['roster_rank_name']);
				$oldname[] = $target_path."small/sml".$rank[2].".png";
				$oldname[] = $target_path."big/".$rank[2].".png";
				for ($i=0; $i<count($oldname); $i++) {
					unlink($oldname[$i]);
				};	
				$sql->db_Delete("roster_ranks", "roster_rank_id='".$_GET['r_id']."'");
				$text .= $html->erank_deleted();
				break;
	default:
		$text .= $html->erank_show();
}

$text .= $html->erank_footer();

// output the text
$ns->tablerender(roster_LAN_ADMIN_ERANK_TITLE, $text);

require_once(e_ADMIN."footer.php");

?>