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
require_once("html/roster_awards.php");
$html = new awards_html;

switch($_GET['action']){
	default:
		$text .= $html->awards_show();
}

$ns->tablerender(roster_LAN_AWARD_TITLE, $text);

//site footer
require_once(FOOTERF);

?>