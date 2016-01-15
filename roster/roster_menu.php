<?php
//reset $text variable
$text = "";

//put in $bullet the bullet image for menus
$bullet = "<img src='".THEME_ABS."images/bullet2.gif' alt='' style='border:0;' />";

//make an array of all the menu links
$url = array();
$url[] = e_PLUGIN."roster/roster.php";
$url[] = e_PLUGIN."roster/ranks.php";
$url[] = e_PLUGIN."roster/awards.php";

//create the menu
$text = $bullet." <a href='".$url[0]."'>".roster_LAN_MENU_ROSTER."</a><br />";
$text .= $bullet." <a href='".$url[1]."'>".roster_LAN_MENU_RANK."</a><br />";
$text .= $bullet." <a href='".$url[2]."'>".roster_LAN_MENU_AWARD."</a><br />";

//show the menu
$ns->tablerender(roster_LAN_MENU_TITLE, $text);
?>
