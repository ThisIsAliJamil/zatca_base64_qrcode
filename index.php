<?php

// First step is to generate Base64String
// Second step is to generate QRCode by passing Base64String

date_default_timezone_set('Asia/Riyadh');

require __DIR__ . "/vendor/autoload.php";

use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tag;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;

use chillerlan\QRCode\{QROptions, QRCode};
use chillerlan\QRCode\Data\{AlphaNum, Byte, Kanji, Number, QRCodeDataException};
use chillerlan\QRCode\Output\QRCodeOutputException;


// This function generate Base64 string
function generateBase64String($get_seller_name, $get_vat_number, $get_time_stamp, $get_invoice_total, $get_vat_total) {
	
	
	$generatedString = GenerateQrCode::fromArray([
    new Seller(strtoupper($get_seller_name)), // seller name        
    new TaxNumber($get_vat_number), // seller tax number
    new InvoiceDate($get_time_stamp), // invoice date as Zulu ISO8601 @see https://en.wikipedia.org/wiki/ISO_8601
    new InvoiceTotalAmount(number_format($get_invoice_total)), // invoice total amount
    new InvoiceTaxAmount(number_format($get_vat_total)) // invoice tax amount
		// TODO :: Support others tags
	])->toBase64();

	return $generatedString;

}

// This function generate QRCode image
function  generateQRCode($get_generatedBase64String) {
	
	$data = $get_generatedBase64String;

	// quick and simple:
	return '<img src="'.(new QRCode)->render($data).'" alt="QR Code" />';
	
}


// Sample 

$seller_name = "Heart of Balad";
$vat_number = "302043534543500003";
$time_stamp = date("Y-m-d") . "T" . date("H:i:s");
$invoice_total = 100;
$vat_total = 15;


// getting the Base64 String
$getBase64 = generateBase64String($seller_name, $vat_number, $time_stamp, $invoice_total, $vat_total);

// generate QRCode image

$getQRCode = generateQRCode($getBase64);

echo $getBase64;
echo "<br/>";
echo $getQRCode;




//echo $generatedString;
