<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
function page_footer($no_menu=false, $is_index=false, $hide_back_link=false)
{
	global $path_to_root, $js_lib, $Validate, $Editors, $Ajax;

  	if (!$is_index && !$hide_back_link && function_exists('hyperlink_back'))
		hyperlink_back();
	include_once($path_to_root."/themes/".user_theme()."/renderer.php");
	$rend = new renderer();
	$rend->menu_footer($no_menu, $is_index);

	$edits = "editors = ".$Ajax->php2js($Editors).";";
	$Ajax->addScript('editors', $edits);

	echo "<script>
		_focus = '" . get_post('_focus') . "';
		_validate = " . $Ajax->php2js($Validate).";
		var $edits
	</script>";

   	add_user_js_data();
	echo "\n<script type=\"text/javascript\"><!--\n";

	foreach($js_lib as $text)
	  	echo $text;
	echo "\n--></script>\n";
    echo "</body></html>\n";
}

?>