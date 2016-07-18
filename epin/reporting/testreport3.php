<?php

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
 * image logo
 */

//define ('PDF_HEADER_LOGO', 'tcpdf_logo.jpg');


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

define ('PDF_IMAGE_SCALE_RATIO', 0.1);


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

/**
 * document creator
 */

define ('PDF_CREATOR', 'TCPDF');

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
$pdf->SetAuthor('BlueChip Technologies');
$pdf->SetTitle('Customer Invoices');
$pdf->SetSubject('Etisalat Nigeria');
$pdf->SetKeywords('Invoices, Etisalat');

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


// add a page
$pdf->AddPage();

// set JPEG quality
$pdf->setJPEGQuality(75);

// Image example
//function Image($file, $x, $y, $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='') {
		
//$pdf->Image('../simplex/images/etisalat.jpg', 179, 5, 21, 28, '', '', '', false, 300, 'R');
$pdf->Image('../simplex/images/etisalat.jpg', 179, 5, 18, 24, '', '', '', false, 300, 'R');

		/**
		* Prints a cell (rectangular area) with optional borders, background color and character string. The upper-left corner of the cell corresponds to the current position. The text can be aligned or centered. After the call, the current position moves to the right or to the next line. It is possible to put a link on the text.<br />
		* If automatic page breaking is enabled and the cell goes beyond the limit, a page break is done before outputting.
		* @param float $w Cell width. If 0, the cell extends up to the right margin.
		* @param float $h Cell height. Default value: 0.
		* @param string $txt String to print. Default value: empty string.
		* @param mixed $border Indicates if borders must be drawn around the cell. The value can be either a number:<ul><li>0: no border (default)</li><li>1: frame</li></ul>or a string containing some or all of the following characters (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B: bottom</li></ul>
		* @param int $ln Indicates where the current position should go after the call. Possible values are:<ul><li>0: to the right (or left for RTL languages)</li><li>1: to the beginning of the next line</li><li>2: below</li></ul>
		Putting 1 is equivalent to putting 0 and calling Ln() just after. Default value: 0.
		* @param string $align Allows to center or align the text. Possible values are:<ul><li>L or empty string: left align (default value)</li><li>C: center</li><li>R: right align</li><li>J: justify</li></ul>
		* @param int $fill Indicates if the cell background must be painted (1) or transparent (0). Default value: 0.
		* @param mixed $link URL or identifier returned by AddLink().
		* @param int $stretch stretch carachter mode: <ul><li>0 = disabled</li><li>1 = horizontal scaling only if necessary</li><li>2 = forced horizontal scaling</li><li>3 = character spacing only if necessary</li><li>4 = forced character spacing</li></ul>
		* @since 1.0
		* @see SetFont(), SetDrawColor(), SetFillColor(), SetTextColor(), SetLineWidth(), AddLink(), Ln(), MultiCell(), Write(), SetAutoPageBreak()
		*/
//Add Company registration number and VAT number

// ---------------------------------------------------------

// set font
$pdf->SetFont('neotech-medium', '', 6); //Bold and italized

// ---------------------------------------------------------


//$pdf->SetFillColor('rgb',0,0,0);//white backround;
//$pdf->Text(180, 32, 'Co.Reg No: RC402011');//using text
//function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0)
$pdf->Cell(0, 0, 'Emerging Markets', 				0, 1, 'R',0, 'www.etisalat.com.ng');//using cell is better, I 
$pdf->Cell(0, 0, 'Telecomunication Serv. Ltd.', 	0, 1, 'R',0, 'www.etisalat.com.ng');//using cell is better, I 
$pdf->Cell(0, 0, 'www.etisalat.com.ng', 			0, 1, 'R',0, 'www.etisalat.com.ng');//using cell is better, I 

$pdf->SetXY(15 , $pdf->GetY()-15);

$pdf->Cell(40, 0, '', 0, 1, 'C',0);

//$pdf->Cell(180, 0, '', 1, 1, 'C',0); 180 MM TOTAL SPACE AVAILABLE

$pdf->Cell(40, 0, '', 0, 1, 'C',0);

$pdf->SetFont('neotech-medium', '', 16); //Bold and italized
$pdf->Text(50, $pdf->GetY(), 'statement of account ' ); 

$pdf->SetFont('neotech-light', '', 16); 
$pdf->Text(111, $pdf->GetY(), 'etisalat services' ); 

$pdf->Cell(40, 0, '', 0, 1, 'C',0);
$pdf->Cell(40, 0, '', 0, 1, 'C',0);
$pdf->Cell(40, 0, '', 0, 1, 'C',0);

$y_pos =  $pdf->GetY() ;

$pdf->Image('../simplex/images/stmt_summ_image.jpg', 100, $y_pos , 60, 75, '', '', '', false, 300, 'R');

$pdf->SetXY(15 , $pdf->GetY()+80);

$pdf->ln(1) ;


$pdf->ln(1) ;



//$pdf->SetFont('neotech-medium', '', 16); //Bold and italized
//$pdf->Cell(0, 0, 'etisalat services', 0, 0, 'R',0, ''); 




 
$pdf->SetFont('times', 'B', 9); //BI=Bold and italized

//$pdf->Cell(0, 0, 'Account No: 1234567890', 0, 2, 'L',0);
//$pdf->Cell(0, 0, 'Name: Kolade Olawole', 0, 1, 'L',0);
//$pdf->Cell(0, 0, 'Address: ', 0, 1, 'L',0);

//$current_y = $pdf->GetY();
//go back up to write the address
//$pdf->SetXY($pdf->GetX()+13 , $current_y -4.1);

//$pdf->Cell(0, 0, 'Odunlami Street, Gbagada Phase I,', 0, 1, 'L',0);
//indent to position just after address:  on the next line.
//come back down to continue the address
//$pdf->SetXY($pdf->GetX()+13 , $current_y);
//$pdf->Cell(0, 0, 'Lagos.', 0, 1, 'L',0);
//$pdf->Cell(0, 0, 'Phone: 2348099441234', 0, 1, 'L',0);

$pdf->ln(1) ;


//$pdf->SetFillColor(0, 255, 0);//green fill colour;

$pdf->SetFillColor(211,211,211);//grey 

//function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0)

$pdf->Cell(40, 0, 'Parent Account', 1, 0, 'C',1);
$pdf->SetXY($pdf->GetX()+7 ,$pdf->GetY());

$pdf->Cell(40, 0, 'Invoice No.', 1, 0, 'C',1);
$pdf->SetXY($pdf->GetX()+7 ,$pdf->GetY());

$pdf->Cell(40, 0, 'Invoice Date', 1, 0, 'C',1);

$pdf->SetXY($pdf->GetX()+7 ,$pdf->GetY());
//write last one and move to the next line
$pdf->Cell(40, 0, 'Due Date', 1, 1, 'C',1);

//set back to white
//$pdf->SetFillColor('rgb',0,0,0);//white backround;

//function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0)

$pdf->Cell(40, 0, '54321', 1, 0, 'C',0);
$pdf->SetXY($pdf->GetX()+7 ,$pdf->GetY());

$pdf->Cell(40, 0, '67890', 1, 0, 'C',0);
$pdf->SetXY($pdf->GetX()+7 ,$pdf->GetY());

$pdf->Cell(40, 0, '25-Feb-2010', 1, 0, 'C',0);

$pdf->SetXY($pdf->GetX()+7 ,$pdf->GetY());
//write the last item and move to the a new line
$pdf->Cell(40, 0, '15-Mar-2010', 1, 1, 'C',0);


$pdf->ln(2) ;


//$pdf->SetFillColor(211,211,211);//grey 

//function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0)

$pdf->Cell(20, 0, 'Service', 1, 0, 'C',1);
$pdf->Cell(62, 0, 'Description', 1, 0, 'C',1);
$pdf->Cell(23, 0, 'Reference', 1, 0, 'C',1);
$pdf->Cell(20, 0, 'From', 1, 0, 'C',1);
$pdf->Cell(20, 0, 'To', 1, 0, 'C',1);
$pdf->Cell(35.7, 0, 'Amount', 1, 1, 'C',1);

$pdf->SetFont('times', '', 9);
/*Loop from here*/
$pdf->Cell(20, 0, 'GPRS', 1, 0, 'L',0);
$pdf->Cell(62, 0, 'GPRS GRPS Usage Charges', 1, 0, 'L',0);
$pdf->Cell(23, 0, '2348099441234', 1, 0, 'L',0);
$pdf->Cell(20, 0, '26-Jan-2010', 1, 0, 'C',0);
$pdf->Cell(20, 0, '26-Feb-2010', 1, 0, 'C',0);
$pdf->Cell(35.7, 0, '1,200', 1, 1, 'R',0);

//2
$pdf->Cell(20, 0, 'Telephony', 1, 0, 'L',0);
$pdf->Cell(62, 0, 'Blackberry (Bes+Bis) Monthly Fee', 1, 0, 'L',0);
$pdf->Cell(23, 0, '2348099441234', 1, 0, 'L',0);
$pdf->Cell(20, 0, '26-Jan-2010', 1, 0, 'C',0);
$pdf->Cell(20, 0, '26-Feb-2010', 1, 0, 'C',0);
$pdf->Cell(35.7, 0, '3,200', 1, 1, 'R',0);

//3
$pdf->Cell(20, 0, 'Telephony', 1, 0, 'L',0);
$pdf->Cell(62, 0, 'International SMS Charges', 1, 0, 'L',0);
$pdf->Cell(23, 0, '2348099441234', 1, 0, 'L',0);
$pdf->Cell(20, 0, '26-Jan-2010', 1, 0, 'C',0);
$pdf->Cell(20, 0, '26-Feb-2010', 1, 0, 'C',0);
$pdf->Cell(35.7, 0, '1,000', 1, 1, 'R',0);


//4
$pdf->Cell(20, 0, 'Telephony', 1, 0, 'L',0);
$pdf->Cell(62, 0, 'MMS Charge', 1, 0, 'L',0);
$pdf->Cell(23, 0, '2348099441234', 1, 0, 'L',0);
$pdf->Cell(20, 0, '26-Jan-2010', 1, 0, 'C',0);
$pdf->Cell(20, 0, '26-Feb-2010', 1, 0, 'C',0);
$pdf->Cell(35.7, 0, '500', 1, 1, 'R',0);



//5
$pdf->Cell(20, 0, 'Telephony', 1, 0, 'L',0);
$pdf->Cell(62, 0, 'National Call Charges', 1, 0, 'L',0);
$pdf->Cell(23, 0, '2348099441234', 1, 0, 'L',0);
$pdf->Cell(20, 0, '26-Jan-2010', 1, 0, 'C',0);
$pdf->Cell(20, 0, '26-Feb-2010', 1, 0, 'C',0);
$pdf->Cell(35.7, 0, '5000', 1, 1, 'R',0);


//6
$pdf->Cell(20, 0, 'Telephony', 1, 0, 'L',0);
$pdf->Cell(62, 0, 'National SMS Charges', 1, 0, 'L',0);
$pdf->Cell(23, 0, '2348099441234', 1, 0, 'L',0);
$pdf->Cell(20, 0, '26-Jan-2010', 1, 0, 'C',0);
$pdf->Cell(20, 0, '26-Feb-2010', 1, 0, 'C',0);
$pdf->Cell(35.7, 0, '5100', 1, 1, 'R',0);

//7
$pdf->Cell(20, 0, '', 0, 0, 'L',0);
$pdf->Cell(62, 0, '', 0, 0, 'L',0);
$pdf->Cell(23, 0, '', 0, 0, 'L',0);
$pdf->Cell(20, 0, '', 0, 0, 'C',0);
$pdf->Cell(20, 0, 'Total ', 1, 0, 'R',1);
$pdf->Cell(35.7, 0, '16000', 1, 1, 'R',0);


//		function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0)
//$pdf->Cell(100, 10, 'Customer Invoice for the Month of February 2010', 0, 1, 'C',1);


// ---------------------------------------------------------

// add a page
$pdf->AddPage();

// print a line using Cell()
$pdf->Cell(0, 10, 'Itemised Bill Goes Here', 1, 1, 'C');

// ---------------------------------------------------------


//Close and output PDF document
//$pdf->Output('example_009.pdf', 'I');


//Close and output PDF document
$pdf->Output('example_003.pdf', 'F');

//============================================================+
// END OF FILE                                                 
//============================================================+
?>
