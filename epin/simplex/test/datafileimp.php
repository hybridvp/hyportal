<pre>
<?php
$fieldLengths=array(19,30,26,35,5,4,3,1,4,1,4,3,3,4,6,8,100,100,100);
$lines=file('C:\huawei_file.txt');
foreach($lines as $line){
  if(preg_match('/\S/',$line))
  {
      foreach($fieldLengths as $fieldLength){
            $t=substr($line, 0, $fieldLength);
            echo $t . "|"; //field/column separator
            $line=substr($line,$fieldLength);
      }
      echo "\n"; //end of record/line
  }
}
/*
$findThis = "Anrea";
$handle = @fopen("xyz.txt", "r"); // Open file form read.

if ($handle) {
while (!feof($handle)) // Loop til end of file.
{
$buffer = fgets($handle, 4096); // Read a line.
if ($buffer <> "Anrea") // Check for string.
{
echo "$buffer"; // If not string show contents.
} else {
$buffer = $findThis; // If string, 
change it.
echo "$buffer"; // and show changed contents.
}
}
fclose($handle); // Close the file.
*/
?>
</pre>


