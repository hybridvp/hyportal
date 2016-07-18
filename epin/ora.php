<?php
if ($conn=oci_connect("simplexaccount","simplex","localhost/orcl")) {
        echo "<B>SUCCESS ! Connected to database<B>\n";
} else {
        echo "<B>Failed :-( Could not connect to database<B>\n";
}
oci_close($conn);


	
phpinfo();
?>