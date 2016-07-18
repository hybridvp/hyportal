<?php
/**********************************************************************
    Copyright (C) SIMPLEX
	@laolu olapegba
***********************************************************************/
//---------------------------------------------------------------------------------------------------

function display_srlz_summary(&$po, $editable=false)
{
	global $table_style2, $Refs;
    start_table("$table_style2 width=90%");
    
    start_row();
	label_cells(_("Supplier"), $po->supplier_name, "class='tableheader2'");
    
    if (!is_company_currency($po->curr_code))
    	label_cells(_("Order Currency"), $po->curr_code, "class='tableheader2'");
    
    label_cells(_("For Purchase Order"), get_trans_view_str(ST_PURCHORDER, $po->order_no),
    	"class='tableheader2'");
    
	label_cells(_("Ordered On"), $po->orig_order_date, "class='tableheader2'");
	label_cells(_("Supplier's Reference"), $po->requisition_no, "class='tableheader2'");
	end_row();
		
   	start_row();
    if ($editable) 
    {
    	if (!isset($_POST['ref']))
    	    $_POST['ref'] = $Refs->get_next(ST_SUPPRECEIVE);
    	ref_cells(_("Reference"), 'ref', '', null, "class='tableheader2'");
    	    	
        if (!isset($_POST['Location']))
        	$_POST['Location'] = $po->Location;
        label_cell(_("Serialize Into Location"), "class='tableheader2'");
        locations_list_cells(null, "Location", $_POST['Location']);
        
    	if (!isset($_POST['DefaultReceivedDate']))
     	  	$_POST['DefaultReceivedDate'] = new_doc_date();
    	
    	date_cells(_("Date Items Received"), 'DefaultReceivedDate', '', true, 0, 0, 0, "class='tableheader2'");    
        
    } 
    else 
    {
    	label_cells(_("Reference"), $po->reference, "class='tableheader2'");
    	label_cells(_("Serialize Into Location"), get_location_name($po->Location), "class='tableheader2'");
    }
    
    end_row();
    
    if (!$editable)
    
    	label_row(_("Delivery Address"), $po->delivery_address, "class='tableheader2'", "colspan=9");
    
    if ($po->Comments != "")
    	label_row(_("Order Comments"), $po->Comments, "class='tableheader2'", "colspan=9");
    end_table(1);	
}
//---------------------------------------------------------------------------------------------------

function display_grn_summary_2(&$po, $editable=false)
{
	global $table_style2, $Refs;
    start_table("$table_style2 width=90%");
    
    start_row();
	label_cells(_("Supplier"), $po->supplier_name, "class='tableheader2'");
    
    if (!is_company_currency($po->curr_code))
    	label_cells(_("Order Currency"), $po->curr_code, "class='tableheader2'");
    
    label_cells(_("For Purchase Order"), get_trans_view_str(ST_PURCHORDER, $po->order_no),
    	"class='tableheader2'");
    
	label_cells(_("Ordered On"), $po->orig_order_date, "class='tableheader2'");
	label_cells(_("Supplier's Reference"), $po->requisition_no, "class='tableheader2'");
	end_row();
		
   	start_row();
    if ($editable) 
    {
    	if (!isset($_POST['ref']))
    	    $_POST['ref'] = $Refs->get_next(ST_SUPPRECEIVE);
    	ref_cells(_("Reference"), 'ref', '', null, "class='tableheader2'");
    	    	
        if (!isset($_POST['Location']))
        	$_POST['Location'] = $po->Location;
        label_cell(_("Deliver Into Location"), "class='tableheader2'");
        locations_list_cells_wip(null, "Location", $_POST['Location']);
        
    	if (!isset($_POST['DefaultReceivedDate']))
     	  	$_POST['DefaultReceivedDate'] = new_doc_date();
    	
    	date_cells(_("Date Items Received"), 'DefaultReceivedDate', '', true, 0, 0, 0, "class='tableheader2'");    
        
    } 
    else 
    {
    	label_cells(_("Reference"), $po->reference, "class='tableheader2'");
    	label_cells(_("Deliver Into Location"), get_location_name($po->Location), "class='tableheader2'");
    }
    
    end_row();
    
    if (!$editable)
    
    	label_row(_("Delivery Address"), $po->delivery_address, "class='tableheader2'", "colspan=9");
    
    if ($po->Comments != "")
    	label_row(_("Order Comments"), $po->Comments, "class='tableheader2'", "colspan=9");
    end_table(1);	
}

?>