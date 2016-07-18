<?php
$path_to_root="..";
include_once('../reporting/firstpage.php');

function statement(&$page1_info, $record)
{
$page1_info['invoiceno']=$record[1];
$page1_info['billcycle']=$record[2].' - '.$record[3];
$page1_info['billperiod']=$record[4];
$page1_info['duedate']=$record[5];
//$page1_info['']=$record[];
}

function stmntSummary(&$page1_info, $record)
{
$page1_info['outstanding']=$record[1];
$page1_info['received']=$record[2];
$page1_info['monthtotal']=$record[4];
$page1_info['vat']=$record[4]*(1-1/1.05); //recalculate VAT element
$page1_info['total']=$record[6];
//$page1_info['']=$record[];
}

function contract(&$page1_info, $record)
{
$page1_info['contract']=$record[1];
$page1_info['customerno']=$record[2];
$page1_info['customertype']=$record[3];
$page1_info['title']=$record[4];
$page1_info['fullname']=$record[5];
//$page1_info['']=$record[];
}

function contractDelivery(&$page1_info, $record)
{
$page1_info['deliverytype']=$record[1];
$page1_info['deliveraddress']=$record[2];

}

$filename = "c:\info2.txt";
$fd = fopen ($filename, "r");

//$contents = fread ($fd,filesize ($filename));

$delimiter = "|";

$rec = 0 ;
while ( $line = fgets($fd, 2000) ) {
//print $line;
//echo " <br> ";
$rec +=1;
$counter = 0;

$splitcontents = explode($delimiter, $line);

//start building parameters for first page in an array
if ($splitcontents[0] == 'statement') statement($page1_info, $splitcontents);
if ($splitcontents[0] == 'contract') contract($page1_info, $splitcontents);
if ($splitcontents[0] == 'contractDelivery') contractDelivery($page1_info, $splitcontents);
if ($splitcontents[0] == 'stmntSummary') 
   {  stmntSummary($page1_info, $splitcontents);
 		//write_page1(&$pdf, $page_info);
		write_page1($page1_info);
 	  echo ' <br><br>hello there '.$page1_info['invoiceno'].' ,'.$page1_info['total'];
   }
//if ($splitcontents[0] == 'stmntTransCat') stmntTransCat();
   
/*
foreach ( $splitcontents as $col )
{

$counter +=1;
echo "<b>col $counter of record $rec: </b> $col<br>";
}
*/

}

fclose ($fd); 

echo "<br><br>" ;

//echo '<font color="blue" face="arial" size="4">Complete File Contents</font><hr>';
//echo $contents;

//echo "<br><br>" ;


//============================================================+
// END OF FILE                                                 
//============================================================+
?>
