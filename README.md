eInvoices SDK for PHP (official)
==============================
Create an e-invoice in your application (PHP 5.4+ & Curl)


What is Yepster?
----------------
eInvoices is a free online invoicing tool. It is fully integrated with DigiWallet, a payment service provider.  
The app takes care of making, sending and managing your invoices.   
Payments are handled by DigiWallet and will be processed instantly.  


Requirements
------------
First of all you need an eInvoices (DigiWallet) account.   
Sign up on https://www.e-invoices.nl 
You'll receive a mail from DigiWallet with the details they need to process your payments. Please follow the instructions.  


Installation
------------
Copy the directory eInvoices to your webserver.   
Upload it in a 'vendor', 'extensions' or other module directory if you use a framework.  
The autoloader of your framework should be able to load the appropriate source files.   
If not, please manually 'require_once' the file /path/to/eInvoices/autoload.php   

API key
-------
Authentication is done using an API key (64-char). It can be found in your Profile in eInvoices.   
Find 'Profile...' in the upper-right menu in eInvoices.   
It looks like: 6ba19adb0af1c3ec31220072d73080df04b1ef271ac031ca508ef045bccec357


Creating invoice
------------------
To create an invoice you instantiate CreateInvoice.

	$invoiceCreator = new eInvoices\CreateInvoice;

Then you set the API key:

	$invoiceCreator->setApiKey('6ba19adb0af1c3ec31220072d73080df04b1ef271ac031ca508ef045bccec357');

A lot of the fields will be filled with defaults that should be appropriate. Only the name atrribute has to be set.   
You can change the rest, e.g.:

	$invoiceCreator->addDetail('name', 'Yellow Melon B.V.');

To add invoicelines to your invoice you use for example:  

	$invoiceCreator->addInvoiceLine ('First product', 2.0, 100, 21);

(this will create a line with First product, quantity of 2 @ 100 euro each. VAT is 21%)  

Then run:  

	$result = $invoiceCreator->exec();

The result is an array. In case of success:  

	['status' => 'success', 'externalid' => *your invoice ID*, 'id' => *eInvoices invoice id* ]

In case of failure  

	['status' => 'fail', 'error' => *error message* ]


Example
-------
Note: that the methods support 'chaining'.

	$result = eInvoices\CreateInvoice::model()
	     ->setApiKey ('6ba19adb0af1c3ec31220072d73080df04b1ef271ac031ca508ef045bccec357')
	     ->addDetail ('name', 'Yellow Melon B.V.')
	     ->addDetail ('street', 'Kleine koppel')
	     ->addDetail ('housenumber', '52')
	     ->addDetail ('postcode', '3812PH')
	     ->addDetail ('city', 'Amersfoort')
	     ->addDetail ('email', 'info@yellowmelon.nl')
	     ->addInvoiceLine ('First product', 2.0, 100, 21)
	     ->addInvoiceLine ('Second product', 2.0, 250, 21)
	     ->exec();
