<?php

// incude e107 class file, it's required
require_once("../../class2.php");

//style for our table
$eplug_css="html/style.css";

//site header
require_once(HEADERF);

// include language file
include_lan(e_PLUGIN."roster/languages/".e_LANGUAGE.".php");


//html file
require_once("html/roster_ranks.php");
$html = new ranks_html;

switch($_GET['action']){
	default:
		$text .= $html->ranks_show();
}

$ns->tablerender(roster_LAN_RANK_TITLE, $text);

//site footer
require_once(FOOTERF);

?>