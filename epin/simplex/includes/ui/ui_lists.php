/**********************************************************************
    Copyright (C) SIMPLEX.
	@Laolu Olapegba
***********************************************************************/
$path_to_root="../..";
include_once($path_to_root . "/includes/banking.inc");
include_once($path_to_root . "/includes/types.inc");
include_once($path_to_root . "/includes/current_user.inc");
function conversion_list($name, $selected_id=null)
{
	$sql = "SELECT abbr, name FROM ".TB_PREF."item_units";
	combo_input($name, $selected_id, $sql, 'abbr', 'name', array());
}

function conversion_list_cells($label, $name, $selected_id=null)
{
	if ($label != null)
		echo "<td>$label</td>\n";
	echo "<td>";
	conversion_list($name, $selected_id);
	echo "</td>\n";
}

function conversion_list_row($label, $name, $selected_id=null)
{
	echo "<tr>\n";
	conversion_list_cells($label, $name, $selected_id);
	echo "</tr>\n";
}