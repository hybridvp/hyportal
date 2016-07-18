<?php 
include_once($path_to_root . "/includes/manufacturing.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/inventory/includes/inventory_db.inc");
include_once($path_to_root . "/inventory/includes/db/items_db.inc");

function CheckReorderLevels()
{
	$item_details = get_items_reoders('DEF');	
	$retval = "";
	while ($myrow = db_fetch($item_details))
	{
		$qoh = get_qoh_on_date($myrow["stock_id"], 'DEF');
		if ( $qoh <= $myrow["reorder_level"] )
		{
			$retval.= "Reorder level for stock " . $myrow["stock_id"] . " reached , QOH = " . $qoh . " ||";
		}
		
	}
	return $retval;
}
?>