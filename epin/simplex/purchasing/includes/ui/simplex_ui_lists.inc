<?php
/**********************************************************************
    Copyright (C) SIMPLEX
    @author laolu olapegba
***********************************************************************/
include_once($path_to_root . "/includes/banking.inc");
include_once($path_to_root . "/includes/types.inc");
include_once($path_to_root . "/includes/current_user.inc");

$_search_button = "<input %s type='submit' class='combo_submit' style='border:0;background:url($path_to_root/themes/"
	."%s/images/locate.png) no-repeat;%s' aspect='fallback' name='%s' value=' ' title='"._("Set filter")."'> ";

$_select_button = "<input %s type='submit' class='combo_select' style='border:0;background:url($path_to_root/themes/"
	."%s/images/button_ok.png) no-repeat;%s' aspect='fallback' name='%s' value=' ' title='"._("Select")."'> ";

$all_items = ALL_TEXT;

//----------------------------------------------------------------------------
//	Universal sql combo generator
//	$sql must return selector values and selector texts in columns 0 & 1
//	Options are merged with defaults.

function combo_input($name, $selected_id, $sql, $valfield, $namefield,
	$options=null)
{
global $Ajax;

$opts = array(		// default options
	'where'=> array(),		// additional constraints
	'order' => $namefield,	// list sort order
		// special option parameters
	'spec_option'=>false, 	// option text or false
	'spec_id' => 0,		// option id
		// submit on select parameters
	'default' => '', // default value when $_POST is not set
	'multi' => false,	// multiple select
	'select_submit' => false, //submit on select: true/false
	'async' => true,	// select update via ajax (true) vs _page_body reload
		// search box parameters
	'sel_hint' => null,
	'search_box' => false, 	// name or true/false
	'type' => 0,	// type of extended selector:
		// 0 - with (optional) visible search box, search by id
		// 1 - with hidden search box, search by option text
		// 2 - TODO reverse: box with hidden selector available via enter; this
		// would be convenient for optional ad hoc adding of new item
	'search_submit' => true, //search submit button: true/false
	'size' => 8,	// size and max of box tag
	'max' => 50,
	'height' => false,	// number of lines in select box
	'cells' => false,	// combo displayed as 2 <td></td> cells
	'search' => array(), // sql field names to search
	'format' => null, 	 // format functions for regular options
	'disabled' => false,
	'box_hint' => null, // box/selectors hints; null = std see below
	'category' => false, // category column name or false
	'show_inactive' => false // show inactive records. 
);
// ------ merge options with defaults ----------
	if($options != null)
		$opts = array_merge($opts, $options);
	if (!is_array($opts['where']))  $opts['where'] = array($opts['where']);

	$search_box = $opts['search_box']===true ? '_'.$name.'_edit' : $opts['search_box'];
	$search_submit = $opts['search_submit']===true ? '_'.$name.'_button' : $opts['search_submit'];
	$select_submit =  $opts['select_submit'];
	$spec_id = $opts['spec_id'];
	$spec_option = $opts['spec_option'];
	$by_id = ($opts['type'] == 0);
	$class = $by_id ? 'combo':'combo2';
	$disabled = $opts['disabled'] ? "disabled" : '';
	$multi = $opts['multi'];
	
	if(!count($opts['search'])) {
		$opts['search'] = array($by_id ? $valfield : $namefield);
	}
	if ($opts['sel_hint'] === null) 
		$opts['sel_hint'] = $by_id || $search_box==false ?
			'' : _('Press Space tab for search pattern entry');

	if ($opts['box_hint'] === null)
		$opts['box_hint'] = $search_box && $search_submit != false ?
			($by_id ? _('Enter code fragment to search or * for all')
			: _('Enter description fragment to search or * for all')) :'';

	if ($selected_id == null) {
		$selected_id = get_post($name, $opts['default']);
	}
	if(!is_array($selected_id))
		$selected_id = array($selected_id); // code is generalized for multiple selection support

	$txt = get_post($search_box);
	$rel = '';
	$limit = '';

	if (isset($_POST['_'.$name.'_update'])) {
		if ($by_id) $txt = $_POST[$name];

		if (!$opts['async'])
			$Ajax->activate('_page_body');
		else
			$Ajax->activate($name);
	}
	if ($search_box) {
		// search related sql modifications

	$rel = "rel='$search_box'"; // set relation to list
   if ($opts['search_submit']) {
	if (isset($_POST[$search_submit])) {
		$selected_id = array(); // ignore selected_id while search
		if (!$opts['async'])
			$Ajax->activate('_page_body');
		else
			$Ajax->activate($name);
	}
	if ($txt == '') {
		if ($spec_option === false && $selected_id == array())
		  $limit = ' LIMIT 1';
		else
		  $opts['where'][] = $valfield . "='". get_post($name, $spec_id)."'";
	}
	else
		if ($txt != '*') {

		foreach($opts['search'] as $i=> $s)
			$opts['search'][$i] = $s . " LIKE '%{$txt}%'";
		$opts['where'][] = '('. implode($opts['search'], ' OR ') . ')';
		}
	}
   }
	// sql completion
	if (count($opts['where'])) {
		$where = strpos($sql, 'WHERE')==false ? ' WHERE ':' AND ';
		$where .= '('. implode($opts['where'], ' AND ') . ')';
		$group_pos = strpos($sql, 'GROUP BY');
		if ($group_pos) {
			$group = substr($sql, $group_pos);
			$sql = substr($sql, 0, $group_pos) . $where.' '.$group;
		} else {
			$sql .= $where;
		}
	}
	if ($opts['order'] != false) {
		if (!is_array($opts['order']))
			$opts['order'] = array($opts['order']);
		$sql .= ' ORDER BY '.implode(',',$opts['order']);
	}

	$sql .= $limit;
	// ------ make selector ----------
	$selector = $first_opt = '';
	$first_id = false;
	$found = false;
	$lastcat = null;
//if($name=='stock_id') display_error($sql);
	if($result = db_query($sql)) {
		while ($contact_row = db_fetch($result)) {
			$value = $contact_row[0];
			$descr = $opts['format']==null ?  $contact_row[1] :
				call_user_func($opts['format'], $contact_row);
			$sel = '';
 		 	if (get_post($search_submit) && ($txt === $value)) {
 		 		$selected_id[] = $value;
 		 	}
 		 	if (in_array($value, $selected_id)) {
				$sel = 'selected';
				$found = $value;
			}
			// show selected option even if inactive 
			if (!$opts['show_inactive'] && @$contact_row['inactive'] && $sel==='') {
				continue;
			} else 
				$optclass = @$contact_row['inactive'] ? "class='inactive'" : '';

			if ($first_id === false) {
				$first_id = $value;
				$first_opt = $descr;
			}
			$cat = $contact_row[$opts['category']];
			if ($opts['category'] !== false && $cat != $lastcat){
				$selector .= "<optgroup label='".$cat."'>\n";
				$lastcat = $cat;
			}
			$selector .= "<option $sel $optclass value='$value'>$descr</option>\n";
		}
		db_free_result($result);
	}

	// Prepend special option.
	if ($spec_option !== false) { // if special option used - add it
		$first_id = $spec_id;
		$first_opt = $spec_option;
//	}
//	if($first_id !== false) {
		$sel = $found===false ? 'selected' : '';
		$optclass = @$contact_row['inactive'] ? "class='inactive'" : '';
		$selector = "<option $sel value='$first_id'>$first_opt</option>\n"
			. $selector;
	}

	if ($found===false) {
		$selected_id = array($first_id);
	}
	$_POST[$name] = $multi ? $selected_id : $selected_id[0];

	if ($by_id && $search_box != false) {
 		$txt = $found;
		$Ajax->addUpdate($name, $search_box, $txt ? $txt : '');
	}
	$selector = "<select ".($multi ? "multiple" : '')
		. ($opts['height']!==false ? ' size="'.$opts['height'].'"' : '')
		. "$disabled name='$name".($multi ? '[]':'')."' class='$class' title='"
		. $opts['sel_hint']."' $rel>".$selector."</select>\n";

	$Ajax->addUpdate($name, "_{$name}_sel", $selector);

	// because of bug which M$ cannot fix since IE 5.0
	// we must embed whole selector in span tags to enable proper ajax update
	$selector = "<span id='_{$name}_sel'>".$selector."</span>\n";

	if ($select_submit != false) { // if submit on change is used - add select button
		global $_select_button;
	// button class selects form reload/ajax selector update
		$selector .= sprintf($_select_button, $disabled, user_theme(),
			(fallback_mode() ? '' : 'display:none;'),
			 '_'.$name.'_update')."\n";
	}
// ------ make combo ----------

	$edit_entry = '';
	if ($search_box != false) {
		$edit_entry = "<input $disabled type='text' name='$search_box' id='$search_box' size='".
			$opts['size']."' maxlength='".$opts['max'].
			"' value='$txt' class='$class' rel='$name' autocomplete='off' title='"
			.$opts['box_hint']."'"
			.(!fallback_mode() && !$by_id ? " style=display:none;":'')
			.">\n";
		if ($search_submit != false) {
			global $_search_button;
			$edit_entry .= sprintf($_search_button, $disabled, user_theme(),
				(fallback_mode() ? '' : 'display:none;'),
				$search_submit)."\n";
		}
	}
	default_focus(($search_box && $by_id) ? $search_box : $name);

	if ($search_box && $opts['cells'])
		$str = ($edit_entry!='' ? "<td>$edit_entry</td>" : '')."<td>$selector</td>";
	else
		$str = $edit_entry.$selector;
	return $str;
}

//select ARR locations  ...includes/ui_lists.inc
function locations_list_wip($name, $selected_id=null, $all_option=false, 

$submit_on_change=false)
{
	global $all_items;

	$sql = "SELECT loc_code, location_name, inactive FROM ".TB_PREF."locations where location_type='ARR'";

return combo_input($name, $selected_id, $sql, 'loc_code', 'location_name',
	array(
		'spec_option' => $all_option === true ? _("All Locations") : 

$all_option,
		'spec_id' => $all_items,
		'select_submit'=> $submit_on_change
	) );
}

?>