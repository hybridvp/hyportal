<?php
//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2009-09-30
// 
// Description : Example 001 for TCPDF class
//               Default Header and Footer
// 
// Author: Nicola Asuni
// 
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com s.r.l.
//               Via Della Pace, 11
//               09044 Quartucciu (CA)
//               ITALY
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @copyright 2004-2009 Nicola Asuni - Tecnick.com S.r.l (www.tecnick.com) Via Della Pace, 11 - 09044 - Quartucciu (CA) - ITALY - www.tecnick.com - info@tecnick.com
 * @link http://tcpdf.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 * @since 2008-03-04
 */


//require_once('tcpdf.php');

$path_to_root="..";
//include_once($path_to_root . "/includes/session.inc");
include_once('../includes/lang/language.php');
//include_once('../pdfreps/tcpdf.php');
include_once('../reporting/includes/tcpdf.php');


include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");

include_once('../reporting/includes/class.pdf.inc');


/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////

/* By default it is automatically calculated but you can also set it as a fixed string to improve performances.
 */
//define ('K_PATH_URL', $sTcPdfUrl );



/**
 * path for PDF fonts
 * use K_PATH_MAIN.'fonts/old/' for old non-UTF8 fonts
 */

define ('K_PATH_MAIN', "..");
//define ('K_PATH_FONTS', K_PATH_MAIN.'fonts/');


/**
 * cache directory for temporary files (full path)
 */

//define ('K_PATH_CACHE', K_PATH_MAIN.'cache/');


/**
 * cache directory for temporary files (url path)
 */

//define ('K_PATH_URL_CACHE', K_PATH_URL.'cache/');


/**
 *images directory
 */

define ('K_PATH_IMAGES', K_PATH_MAIN.'images/');


/**
 * blank image
 */

define ('K_BLANK_IMAGE', K_PATH_IMAGES.'_blank.png');


/**
 * page format
 */

define ('PDF_PAGE_FORMAT', 'A4');


/**
 * page orientation (P=portrait, L=landscape)
 */

define ('PDF_PAGE_ORIENTATION', 'P');


/**
 * document creator
 */

define ('PDF_CREATOR', 'TCPDF');


/**
 * document author
 */

define ('PDF_AUTHOR', 'TCPDF');


/**
 * header title
 */

define ('PDF_HEADER_TITLE', 'TCPDF Example');


/**
 * header description string
 */

define ('PDF_HEADER_STRING', "by Nicola Asuni - Tecnick.com\nwww.tcpdf.org");


/**
 * image logo
 */

define ('PDF_HEADER_LOGO', 'tcpdf_logo.jpg');


/**
 * header logo image width [mm]
 */

define ('PDF_HEADER_LOGO_WIDTH', 30);


/**
 *  document unit of measure [pt=point, mm=millimeter, cm=centimeter, in=inch]
 */

define ('PDF_UNIT', 'mm');


/**
 * header margin
 */

define ('PDF_MARGIN_HEADER', 5);


/**
 * footer margin
 */

define ('PDF_MARGIN_FOOTER', 10);


/**
 * top margin
 */

define ('PDF_MARGIN_TOP', 27);


/**
 * bottom margin
 */

define ('PDF_MARGIN_BOTTOM', 25);


/**
 * left margin
 */

define ('PDF_MARGIN_LEFT', 15);


/**
 * right margin
 */

define ('PDF_MARGIN_RIGHT', 15);


/**
 * default main font name
 */

define ('PDF_FONT_NAME_MAIN', 'helvetica');


/**
 * default main font size
 */

define ('PDF_FONT_SIZE_MAIN', 10);


/**
 * default data font name
 */

define ('PDF_FONT_NAME_DATA', 'helvetica');


/**
 * default data font size
 */

define ('PDF_FONT_SIZE_DATA', 8);


/**
 * default monospaced font name
 */

define ('PDF_FONT_MONOSPACED', 'courier');


/**
 * ratio used to adjust the conversion of pixels to user units
 */

define ('PDF_IMAGE_SCALE_RATIO', 1);


/**
 * magnification factor for titles
 */

define('HEAD_MAGNIFICATION', 1.1);


/**
 * height of cell repect font height
 */

//define('K_CELL_HEIGHT_RATIO', 1.25);


/**
 * title magnification respect main font size
 */

define('K_TITLE_MAGNIFICATION', 1.3);


/**
 * reduction factor for small font
 */

define('K_SMALL_RATIO', 2/3);




/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////
// create new PDF document
///////
		$rtl = ($_SESSION['language']->dir === 'rtl' ? 'rtl' : 'ltr');
		$code = $_SESSION['language']->code;
		$enc = strtoupper($_SESSION['language']->encoding);
		// for the language array in class.pdf.inc
		$l = array('a_meta_charset' => $enc, 'a_meta_dir' => $rtl, 'a_meta_language' => $code, 'w_page' => 'page');
/////////////		
//$pdf = new Cpdf('A4', $l, 'P');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 

//$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false); 

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 002');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

//set some language-dependent strings
$pdf->setLanguageArray($l); 

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', 'BI', 20);

// add a page
$pdf->AddPage();

// print a line using Cell()
$pdf->Cell(0, 10, 'Example 002', 1, 1, 'C');

$pdf->Cell(0, 10, 'My Babies are pretty', 1, 1, 'C');

$pdf->Cell(0, 10, '1 2 3 4 5 6 7 8 9 10', 1, 1, 'C');
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_003.pdf', 'F');

//============================================================+
// END OF FILE                                                 
//============================================================+
?>
