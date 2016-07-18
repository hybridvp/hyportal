<?php
/**********************************************************************
    Copyright (C) Simplex, Bluechip Technologies Ltd
***********************************************************************/
$page_security = 'SA_OPEN';
$path_to_root="..";
include($path_to_root . "/includes/session.inc");

include($path_to_root . "/includes/page/header.inc");
page_header(_("Logout"), true, false, '', get_js_png_fix());

echo "<table width='100%' border='0'>
  <tr>
	<td align='center'><img src='$path_to_root/themes/default/images/logo_epinmgmt.png' alt='EPIN mgmt' width='250' height='50' onload='fixPNG(this)' /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align='center'><font size=2>";
echo _("Thank you for using") . " ";

echo "<strong>$app_title $version</strong>";

echo "</font></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align='center'>";
echo "<a href='$path_to_root/index.php'><b>" . _("Click here to Login Again.") . "</b></a>";
echo "</div></td>
  </tr>
</table>
<br>\n";
end_page(false, true);
session_unset();
session_destroy();
?>


