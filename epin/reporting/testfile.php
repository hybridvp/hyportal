
<font face="arial" size="3" color="green">Reading a delimited file using PHP by <b>G.Ajaykumar</b> </font> Email: ajaykumar_g@hotmail.com <br><br>

<?php
$filename = "c:\info.txt";
$fd = fopen ($filename, "r");

//$contents = fread ($fd,filesize ($filename));

$delimiter = "|";

$rec = 0 ;
while ( $line = fgets($fd, 2000) ) {
print $line;
echo " <br> ";
$rec +=1;
$counter = 0;
$splitcontents = explode($delimiter, $line);
foreach ( $splitcontents as $color )
{

$counter +=1;
echo "<b>col $counter of record $rec: </b> $color<br>";
}

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
