<?php
// include language file
include_lan(e_PLUGIN."roster/languages/admin/".e_LANGUAGE.".php");

global $pageid;

//Roster Management
$butname[]  = roster_LAN_ADMIN_ROSTER_MENU_1;
$butlink[]  = "admin_ngroup.php";
$butid[]    = "newgroup";

$butname[]  = roster_LAN_ADMIN_ROSTER_MENU_2;
$butlink[]  = "admin_egroup.php";
$butid[]    = "editgroup";

$butname[]  = roster_LAN_ADMIN_ROSTER_MENU_3;
$butlink[]  = "admin_nmem.php";
$butid[]    = "newmember";

$butname[]  = roster_LAN_ADMIN_ROSTER_MENU_4;
$butlink[]  = "admin_emem.php";
$butid[]    = "editmember";

for ($i=0; $i<count($butname); $i++) {
    $var[$butid[$i]]['text'] = $butname[$i];
    $var[$butid[$i]]['link'] = $butlink[$i];
};  
 
show_admin_menu(roster_LAN_ADMIN_MAIN_MENU_1, $pageid, $var);

unset($var);
unset($butname);
unset($butlink);
unset($butid);


//Ranks Management
$butname[]  = roster_LAN_ADMIN_RANKS_MENU_1;
$butlink[]  = "admin_nrank.php";
$butid[]    = "newrank";

$butname[]  = roster_LAN_ADMIN_RANKS_MENU_2;
$butlink[]  = "admin_erank.php";
$butid[]    = "editrank";

for ($i=0; $i<count($butname); $i++) {
    $var[$butid[$i]]['text'] = $butname[$i];
    $var[$butid[$i]]['link'] = $butlink[$i];
};

show_admin_menu(roster_LAN_ADMIN_MAIN_MENU_2, $pageid, $var);

unset($var);
unset($butname);
unset($butlink);
unset($butid);


//Awards Management
$butname[]  = roster_LAN_ADMIN_AWARDS_MENU_1;
$butlink[]  = "admin_naward.php";
$butid[]    = "newaward";

$butname[]  = roster_LAN_ADMIN_AWARDS_MENU_2;
$butlink[]  = "admin_eaward.php";
$butid[]    = "editaward";

for ($i=0; $i<count($butname); $i++) {
    $var[$butid[$i]]['text'] = $butname[$i];
    $var[$butid[$i]]['link'] = $butlink[$i];
};

show_admin_menu(roster_LAN_ADMIN_MAIN_MENU_3, $pageid, $var);
?>
