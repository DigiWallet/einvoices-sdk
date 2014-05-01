<?php
namespace Yepster;

/**
 * Create Yepster invoice
 * 
 * Send as many details for the invoice as you want. It will be created as Draft, 
 * you need to approve (complete) and send the invoice thereafter. 
 * Minimal needed is 'name' for the recipient. 
 * The API key can be found in Yepster in your 'Profile' (upper-right menu)
 *
 * Find a complete list of details at CreateInvoice::addDetail()
 *
 * @example 
 * $result = Yepster\CreateInvoice::model()  
 *      ->setApiKey ('6ba19adb0af1c3ec31220072d73080df04b1ef271ac031ca508ef045bccec357')  
 *      ->addDetail ('name', 'Yellow Melon B.V.')  
 *      ->addDetail ('street', 'Kleine koppel')  
 *      ->addDetail ('housenumber', '52')  
 *      ->addDetail ('postcode', '3812PH')    
 *      ->addDetail ('city', 'Amersfoort')  
 *      ->addDetail ('email', 'info@yellowmelon.nl')  
 *      ->addInvoiceLine ('First product', 2.0, 100, 21)  
 *      ->addInvoiceLine ('Second product', 2.0, 250, 21)  
 *      ->exec();   
 *
 * @author  Yellow Melon B.V.
 * @license BSD License
 * @version 1.0
 */

class CreateInvoice extends Communication
{
    /**
     *  Set action
     */

    protected $action = "createInvoice";

    /**
     * Set a detail of the invoice. 
     * Possible values are:
     * 
     * name         Invoice recipient *required
     * street       Street receipient
     * housenumber  Housenumber
     * postcode     Postal code
     * city         City
     * country      Country code, ISO-3166 [NL]
     * currency     Currency, ISO-4217 [EUR]
     * vatid        VAT ID
     * period       Invoice period [last month]
     * date         Invoice date [today]
     * expires      Expiration date [today minus expiration from user settings]
     * externalid   Your invoice ID [last invoice + 1, if none: year + 0001, e.g. 2014001]
     * remarkdebit  Remark for normal invoice
     * remarkcredit Remark for credit invoice
     * footer1      Footer line 1
     * footer2      Footer line 2
     *
     * Note: other values will be ignored
     *
     */

    public function addDetail ($detailName, $detailValue) {
        $this->setParameter ($detailName, $detailValue);
        return $this;
    }

    /**
     *  Add new line to the invoice
     *
     *  @param string $description Description of this line
     *  @param float $quantity Quantity 
     *  @param float $price Price
     *  @param float $vat VAT in %. 21% VAT will be => 21.0
     */

    public function addInvoiceLine ($description, $quantity, $price, $vat) {
        $nextId = 0;
        if (isset($this->params['lines'])) 
            $nextId = count($this->params['lines']) + 1; else
            $this->params['lines'] = [];

        $this->params['lines'][$nextId] = ['description' => $description, 'quantity' => $quantity, 'price' => $price, 'vat' => $vat];
        return $this;
    }

    /**
     * Static class for model creation. Just for aesthetic uses...
     */

    public static function model() {
        return new self;
    }

}

