<?php


$path_to_root = "../..";


include($path_to_root . "/includes/session.inc");



echo ($OurRefs->get_next_simple_ref(1001));

 $OurRefs->save(1001, $OurRefs->get_next_simple_ref(1001), $OurRefs->get_next_simple_ref(1001));
  

?>
