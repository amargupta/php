<?php
/**
 * Open Invoice Server using SOAP
 * 
 * Open Invoice lowers the barrier substantially for shoppers to buy products on a 
 * merchant's website. By providing their personal data, like address and date of birth, 
 * an assessment is made if we allow the shopper to receive the product before the payment 
 * is made. The invoice is shipped along with the product. When the invoice is not paid and 
 * the goods are not returned the merchant has the option to turn the invoice over to a 
 * debt collection agency. 
 *
 * Please note that the Open Invoice paymentmethod is available in a limited number 
 * of countries and not enabled by default. If you like to enable it, please contact Adyen Support.
 *
 * How does the Open Invoice process works?
 * 1. A payment is created by sending in delivery- and billing address. See 1.HPP/create-payment-on-hpp-advanced.php
 *    on how to provide that in advance. Adyen sends a request to your Open Invoice service to 
 *    retrieve the order lines associated with the payment. 
 * 2. The merchant's Open Invoice server returns the order lines;
 * 3. Adyen receives the order lines and once the shopper passes the risk checks, the payment will be Authorised.
 * 4. The merchant send the goods to the shopper and Captures the payment, by sending a regular capture
 *    request, see 4.Modifications/soap/capture.php on how to do that.
 * 5. Adyen sends another request to the open invoice service to request the order lines; 
 * 
 * Please note: using our API requires a web service user. Set up your Webservice 
 * user: Adyen Test CA >> Settings >> Users >> ws@Company. >> Generate Password >> Submit 
 *
 * @link	7.OpenInvoice/soap/openinvoice-server.php 
 * @author	Created by Adyen - Payments Made Easy
 */

/**
  * Create a SoapServer which implements the SOAP protocol used by Adyen and 
  * implement the retrieveDetail action in order to call a function handling
  * the order lines.
  * 
  * new SoapServer($wsdl,$options)
  * - $wsdl points to the wsdl you are using;
  * - $options[cache_wsdl] = WSDL_CACHE_BOTH, we advice 
  *   to cache the WSDL since we usually never change it.
  */  
 $server = new SoapServer(
	"https://ca-test.adyen.com/ca/services/OpenInvoiceDetail?wsdl", array(
		"style" => SOAP_DOCUMENT,
		"encoding" => SOAP_LITERAL,
		"cache_wsdl" => WSDL_CACHE_BOTH,
		"trace" => 1
	)
 ); 
 $server->addFunction("retrieveDetail"); 
 $server->handle();
 

/**
 * retrieveDetail()
 * Implementation of the SOAP action retrieveDetail which will be called by Adyen. 
 * The action retrieveDetail will be called by Adyen in the following scenarios:
 * 1. Authorisation: When the initial payment is created Adyen will request the order lines.
 * 2. Capture: When the payment is captured Adyen will request the order lines, it is possible
 *    to capture less money than the initial payment.
 * 3. Refund: If the money is already collected but the shopper should be credited the refund 
 *    option refunds (part of) the transaction amount. If the money is already collected but the 
 *    shopper should be credited the refund option refunds (part of) the transaction amount. 
 *
 */
 function retrieveDetail($request) {
 	
 	/**
 	 * $request is an object containing the following: 
 	 * $request->request->amount->currency: Currency of the payment
 	 * $request->request->amount->value: the value in minor units so EUR 1,00 = 100
 	 * $request->request->merchantAccount: The merchant account the payment was processed with;
 	 * $request->request->reference: The merchant reference of the payment, which should be used to
 	 * find the order in your system. 
 	 */
 	 	
	$orderLines = array();
	
	/**
 	 * Authorisation / Capture
 	 * Let's assume the shopper paid EUR 50,00 for 2 products, the retrieveDetail
 	 * action was called with and the following order lines were returned. Please note
 	 * that the total amount should be equal to $request->request->amount->value in 
 	 * the same currency. 
 	 */
	if($request->request->amount->value > 0){
		$orderLines = array(
			array(
				"currency" => "EUR",
				"description" => "Product 1",
				"itemPrice" => "2000",
				"itemVAT" => "500",
				"lineReference" => "1",
				"numberOfItems" => "1",
				"vatCategory" => "High"
			),
			array(
				"currency" => "EUR",
				"description" => "Product 2",
				"itemPrice" => "2000",
				"itemVAT" => "500",
				"lineReference" => "2",
				"numberOfItems" => "1",
				"vatCategory" => "High"
			),
		);
	}
	/**
	 * Refund
	 * The value of the $request is negative, this indicates a refund. Assuming the merchant 
	 * knows which product the shopper wants to refund your open invoice service
	 * knows how to respond to the request. In this case the shopper likes to receive a refund
	 * for product 2, which requires the open invoice service to return the order line that 
	 * specifies product 2.
	 */
	else{
		$orderLines = array(
			array(
				"currency" => "EUR",
				"description" => "Product 2",
				"itemPrice" => "2000",
				"itemVAT" => "500",
				"lineReference" => "1",
				"numberOfItems" => "-1",
				"vatCategory" => "High"
			),
		);
	}
	 
	return array(
		"result" => array(
			"lines" => $orderLines
		)
	);
 }
