<?php

/**
 * eInvoices SDK example
 */

// require_once 'eInvoices/autoload.php'; // Uncomment if autoloading doesn't work

$invoice = eInvoices\CreateInvoice::model()
     ->setApiKey ('6ba19adb0af1c3ec31220072d73080df04b1ef271ac031ca508ef045bccec357')
     ->addDetail ('name', 'Yellow Melon B.V.')
     ->addDetail ('street', 'Kleine koppel')
     ->addDetail ('housenumber', '52')
     ->addDetail ('postcode', '3812PH')
     ->addDetail ('city', 'Amersfoort')
     ->addDetail ('email', 'info@yellowmelon.nl');

// Add invoice line

$invoice->addInvoiceLine ('First product', 2.0, 100, 21);
$invoice->addInvoiceLine ('Second product', 2.0, 250, 21);

// Execute
try {
	$result = $invoice->exec();
} 
catch (Exception $e) {
	if ($e instanceof eInvoices\Exception) echo "eInvoices returned an error: ".$e->getMessage();
}

if ($result['status'] == 'success') 
	echo 'eInvoices created the invoice: #' . $result['externalid']; else
	echo 'Could not create invoice: ' . $result['error'];

