<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     �Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvsroot/e107/e107_0.7/e107_admin/userclass2.php,v $
|     $Revision: 1.25 $
|     $Date: 2009/07/14 20:59:42 $
|     $Author: e107steved $
+----------------------------------------------------------------------------+
*/
require_once("../class2.php");
if (!getperms("4")) {
	header("location:".e_BASE."index.php");
	 exit;
}
$e_sub_cat = 'userclass';
require_once("auth.php");
require_once(e_HANDLER."userclass_class.php");
$uclass = new e_userclass;

function check_allowed($class_id)
{
	global $sql;
	if (!$sql->db_Select('userclass_classes', '*', "userclass_id = {$class_id}"))
	{
		header("location:".SITEURL);
		exit;
	}
	$row = $sql->db_Fetch();
	if (!getperms('0') && !check_class($row['userclass_editclass']))
	{
		header("location:".SITEURL);
		exit;
	}
}

if (strstr(e_QUERY, 'clear'))
{
	$tmp = explode('.', e_QUERY);
	$class_id = $tmp[1];
	check_allowed($class_id);
	if ($sql->db_Select('user', 'user_id, user_class', "user_class = '{$class_id}' OR user_class REGEXP('^{$class_id},') OR user_class REGEXP(',{$class_id},') OR user_class REGEXP(',{$class_id}$')"))
	{
		while ($row = $sql->db_Fetch())
		{
			$uidList[$row['user_id']] = $row['user_class'];
		}
		$uclass->class_remove($class_id, $uidList);
		$message = UCSLAN_1;
	}
}
elseif(e_QUERY)
{
	$tmp2 = explode('-', e_QUERY);
	$class_id = $tmp2[0];
	check_allowed($class_id);
	$message = UCSLAN_2;

	if ($sql->db_Select('user', 'user_id, user_class', "user_class = '{$class_id}' OR user_class REGEXP('^{$class_id},') OR user_class REGEXP(',{$class_id},') OR user_class REGEXP(',{$class_id}$')"))
	{
		while ($row = $sql->db_Fetch())
		{
			$uidList[$row['user_id']] = $row['user_class'];
		}
		$uclass->class_remove($class_id, $uidList);
	}
	unset($uidList);
	if ($sql->db_Select('user', 'user_id, user_class', "user_id IN({$tmp2[1]})"))
	{
		while ($row = $sql->db_Fetch())
		{
			$uidList[$row['user_id']] = $row['user_class'];
		}
		$uclass->class_add($class_id, $uidList);
	}
}

if (isset($_POST['delete']))
{
	$class_id = intval($_POST['existing']);
	check_allowed($class_id);
	if ($_POST['confirm']) 
	{
		$sql->db_Delete('userclass_classes', "userclass_id=".$class_id);
		if ($sql->db_Select('user', 'user_id, user_class', "user_class = '{$class_id}' OR user_class REGEXP('^{$class_id},') OR user_class REGEXP(',{$class_id},') OR user_class REGEXP(',{$class_id}$')"))
		{
			while ($row = $sql->db_Fetch())
			{
				$uidList[$row['user_id']] = $row['user_class'];
			}
			$uclass->class_remove($class_id, $uidList);
		}
		if (isset($pref['frontpage'][$class_id]))
		{
			unset($pref['frontpage'][$class_id]);
			save_prefs();
		}
		$message = UCSLAN_3;
	}
	else
	{
		$message = UCSLAN_4;
	}
}

if(isset($_POST['edit']))
{
	$class_id = intval($_POST['existing']);
	check_allowed($class_id);
	$sql->db_Select('userclass_classes', '*', "userclass_id=".$class_id);
	$row = $sql->db_Fetch();
	extract($row);
}

if (isset($_POST['updateclass']))
{
	$class_id = intval($_POST['userclass_id']);
	check_allowed($class_id);
	$_POST['userclass_name'] = $tp->toDB($_POST['userclass_name']);
	$_POST['userclass_description'] = $tp->toDB($_POST['userclass_description']);
	$_POST['userclass_editclass'] = intval($_POST['userclass_editclass']);
	$sql->db_Update('userclass_classes', "userclass_editclass={$_POST['userclass_editclass']}, userclass_name='".$_POST['userclass_name']."', userclass_description='".$_POST['userclass_description']."' WHERE userclass_id=".$class_id);
	$message = UCSLAN_5;
}

if (isset($_POST['createclass']))
{
	if($_POST['userclass_name'])
	{
		$_POST['userclass_name'] = $tp->toDB($_POST['userclass_name']);
		$_POST['userclass_description'] = $tp->toDB($_POST['userclass_description']);

		$editclass = intval(varset($_POST['userclass_editclass'], 0));
		if ($editclass && (getperms('0') || check_class($editclass))) 
		{
			$i = 1;
			while ($sql->db_Select('userclass_classes', '*', "userclass_id='".$i."' ") && $i < 255)
			{
				$i++;
			}
			if ($i < 245)
			{
				$sql->db_Insert("userclass_classes", $i.", '".strip_tags($_POST['userclass_name'])."', '".$_POST['userclass_description']."',{$editclass} ");
			}
			if (!isset($pref['frontpage'][$i]))
			{
				$pref['frontpage'][$i] = $pref['frontpage'][e_UC_GUEST];
				save_prefs();
			}
			$message = UCSLAN_6;
		}
		else
		{
			header("location:".SITEURL);
			exit;
		}
	}
}

if (isset($message))
{
	$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

$class_total = $sql->db_Select("userclass_classes", "*", "ORDER BY userclass_name", "nowhere");

$text = "<div style='text-align:center'>
	<form method='post' action='".e_SELF."' id='classForm'>
	<table class='fborder' style='".ADMIN_WIDTH."'>
	<tr>
	<td class='fcaption' style='text-align:center' colspan='2'>";

if ($class_total == "0")
{
	$text .= UCSLAN_7;
}
else
{
	$text .= "<span class='defaulttext'>".UCSLAN_8.":</span>
		<select name='existing' class='tbox'>";
	while ($row = $sql->db_Fetch())
	{
		if (check_class($row['userclass_editclass']) || getperms("0"))
		{
			$text .= "<option value='{$row['userclass_id']}'>{$row['userclass_name']}</option>";
		}
	}
	$text .= "</select>
		<input class='button' type='submit' name='edit' value='".LAN_EDIT."' />
		<input class='button' type='submit' name='delete' value='".LAN_DELETE."' />
		<input type='checkbox' name='confirm' value='1' /><span class='smalltext'> ".UCSLAN_11."</span>
		</td>
		</tr>";
}

$text .= "
	<tr>
	<td class='forumheader3' style='width:30%'>".UCSLAN_12."</td>
	<td class='forumheader3' style='width:70%'>
	<input class='tbox' type='text' size='30' maxlength='25' name='userclass_name' value='$userclass_name' /></td>
	</tr>
	<tr>
	<td class='forumheader3'>".UCSLAN_13."</td>
	<td class='forumheader3' style='width:70%'><input class='tbox' type='text' size='60' maxlength='85' name='userclass_description' value='$userclass_description' /></td>
	</tr>
	";

	if(!isset($userclass_editclass))
	{
		$userclass_editclass = e_UC_ADMIN;
	}

$text .= "
	<tr>
	<td class='forumheader3'>".UCSLAN_24."</td>
	<td class='forumheader3'>".r_userclass("userclass_editclass", $userclass_editclass, "off", "main,admin,classes,matchclass,public,nobody")."</td>
	</tr>
	";

$text .= "
	<tr><td colspan='2' style='text-align:center' class='forumheader'>";

if(isset($_POST['edit']))
{
	$text .= "<input class='button' type='submit' name='updateclass' value='".UCSLAN_14."' />
		<input type='hidden' name='userclass_id' value='$userclass_id' />";
}
else
{
	$text .= "<input class='button' type='submit' name='createclass' value='".UCSLAN_15."' />";
}

$text .= "</td></tr></table>";

if(isset($_POST['edit']))
{
	$sql->db_Select("user", "user_id, user_name, user_class, user_login", "user_ban != 1 ORDER BY user_name ");
	$c = 0;
	$d = 0;
	while ($row = $sql->db_Fetch())
	{
		extract($row);
		if (check_class($userclass_id, $user_class))
		{
			$in_userid[$c] = $user_id;
			$in_username[$c] = $user_name;
			$in_userlogin[$c] = $user_login ? "(".$user_login.")" : "";
			$c++;
		}
		else
		{
			$out_userid[$d] = $user_id;
			$out_username[$d] = $user_name;
			$out_userlogin[$d] = $user_login ? "(".$user_login.")" : "";
			$d++;
		}
	}

	$text .= "<br /><table class='fborder' style='".ADMIN_WIDTH."'>
		<tr>
		<td class='fcaption' style='text-align:center;width:30%'>".UCSLAN_16."</td></tr>
		<tr>
		<td class='forumheader3' style='width:70%; text-align:center'>

		<table style='width:90%'>
		<tr>
		<td style='width:45%; vertical-align:top'>
		".UCSLAN_22."<br />
		<select class='tbox' id='assignclass1' name='assignclass1' size='10' style='width:220px' multiple='multiple' onchange='moveOver();'>";

	for ($a = 0; $a <= ($d-1); $a++)
	{
		$text .= "<option value=".$out_userid[$a].">".$out_username[$a]." ".$out_userlogin[$a]."</option>";
	}

	$text .= "</select>
		</td>
		<td style='width:45%; vertical-align:top'>
		".UCSLAN_23."<br />
		<select class='tbox' id='assignclass2' name='assignclass2' size='10' style='width:220px' multiple='multiple'>";
	for($a = 0; $a <= ($c-1); $a++)
	{
		$text .= "<option value=".$in_userid[$a].">".$in_username[$a]." ".$in_userlogin[$a]."</option>";
	}
	$text .= "</select><br /><br />
		<input class='button' type='button' value='".UCSLAN_17."' onclick='removeMe();' />
		<input class='button' type='button' value='".UCSLAN_18."' onclick='clearMe($userclass_id);' />
		<input type='hidden' name='class_id' value='$userclass_id' />

		</td></tr></table>
		</td></tr>
		<tr><td colspan='2' style='text-align:center' class='forumheader'>
		<input class='button' type='button' value='".UCSLAN_19." ".$userclass_name." ".UCSLAN_20."' onclick='saveMe($userclass_id);' />
		</td>
		</tr>
		</table>";

}

$text .= "</form>
	</div>";

//
// Show a table of all userclasses and who can manage them
//
// lazy get list again
$class_total = $sql->db_Select("userclass_classes", "*", "ORDER BY userclass_name", "nowhere");

$text .= "<br /><div style='text-align:center'>
	<table class='fborder' style='".ADMIN_WIDTH."'>
	<tr>
	<td class='fcaption'>".UCSLAN_12."</td>
	<td class='fcaption'>".UCSLAN_24."</td>
	<td class='fcaption'>".UCSLAN_13."</td>
	</tr>\n";
	
if ($class_total == "0")
{
	$text .= "<tr><td colspan='3'>".UCSLAN_7."</td></tr>";
}
else
{
	while ($row = $sql->db_Fetch())
	{
		$rEditClass = $row['userclass_editclass'];
		if (check_class($rEditClass) || getperms("0"))
		{
			if(!isset($rEditClass))
			{
				$rEditClass = e_UC_ADMIN;
			}

			$text .= "<tr>
			<td class='forumheader3'>{$row['userclass_name']}</td>
			<td class='forumheader3'>".r_userclass_name($rEditClass)."</td>
			<td class='forumheader3'>{$row['userclass_description']}</td>\n";
		}
	}
}
$text .="</table>";

$ns->tablerender(UCSLAN_21, $text);

require_once("footer.php");
function headerjs()
{

	$script_js = "<script type=\"text/javascript\">
		//<![CDATA[
		// Adapted from original:  Kathi O'Shea (Kathi.O'Shea@internet.com)
		function moveOver() {
		var boxLength = document.getElementById('assignclass2').length;
		var selectedItem = document.getElementById('assignclass1').selectedIndex;
		var selectedText = document.getElementById('assignclass1').options[selectedItem].text;
		var selectedValue = document.getElementById('assignclass1').options[selectedItem].value;
		var i;
		var isNew = true;
		if (boxLength != 0) {
		for (i = 0; i < boxLength; i++) {
		thisitem = document.getElementById('assignclass2').options[i].text;
		if (thisitem == selectedText) {
		isNew = false;
		break;
		}
		}
		}
		if (isNew) {
		newoption = new Option(selectedText, selectedValue, false, false);
		document.getElementById('assignclass2').options[boxLength] = newoption;
		document.getElementById('assignclass1').options[selectedItem].text = '';
		}
		document.getElementById('assignclass1').selectedIndex=-1;
		}


		function removeMe() {
		var boxLength = document.getElementById('assignclass2').length;
		var boxLength2 = document.getElementById('assignclass1').length;
		arrSelected = new Array();
		var count = 0;
		for (i = 0; i < boxLength; i++) {
		if (document.getElementById('assignclass2').options[i].selected) {
		arrSelected[count] = document.getElementById('assignclass2').options[i].value;
		var valname = document.getElementById('assignclass2').options[i].text;
		for (j = 0; j < boxLength2; j++) {
		if (document.getElementById('assignclass1').options[j].value == arrSelected[count]){
		document.getElementById('assignclass1').options[j].text = valname;
		}
		}

		// document.getElementById('assignclass1').options[i].text = valname;
		}
		count++;
		}
		var x;
		for (i = 0; i < boxLength; i++) {
		for (x = 0; x < arrSelected.length; x++) {
		if (document.getElementById('assignclass2').options[i].value == arrSelected[x]) {
		document.getElementById('assignclass2').options[i] = null;
		}
		}
		boxLength = document.getElementById('assignclass2').length;
		}
		}

		function clearMe(clid) {
		location.href = document.location + \"?clear.\" + clid;
		}

		function saveMe(clid) {
		var strValues = \"\";
		var boxLength = document.getElementById('assignclass2').length;
		var count = 0;
		if (boxLength != 0) {
		for (i = 0; i < boxLength; i++) {
		if (count == 0) {
		strValues = document.getElementById('assignclass2').options[i].value;
		} else {
		strValues = strValues + \",\" + document.getElementById('assignclass2').options[i].value;
		}
		count++;
		}
		}
		if (strValues.length == 0) {
		//alert(\"You have not made any selections\");
		}
		else {
		location.href = document.location + \"?\" + clid + \"-\" + strValues;
		}
		}
		//]]>
		</script>\n";
	return $script_js;
}

?>