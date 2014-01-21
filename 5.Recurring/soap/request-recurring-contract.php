<?php
/**
 * Request recurring contract details using SOAP
 * 
 * Once a shopper has stored RECURRING details with Adyen you are able to process
 * a RECURRING payment. This file shows you how to retrieve the RECURRING contract(s) 
 * for a shopper using SOAP. 
 * 
 * Please note: using our API requires a web service user. Set up your Webservice 
 * user: Adyen Test CA >> Settings >> Users >> ws@Company. >> Generate Password >> Submit 
 *  
 * @link	5.Recurring/soap/request-recurring-contract.php 
 * @author	Created by Adyen - Payments Made Easy
 */

/**
  * Create SOAP Client = new SoapClient($wsdl,$options)
  * - $wsdl points to the wsdl you are using;
  * - $options[login] = Your WS user;
  * - $options[password] = Your WS user's password.
  * - $options[cache_wsdl] = WSDL_CACHE_BOTH, we advice 
  *   to cache the WSDL since we usually never change it.
  */  
  $client = new SoapClient(
	"https://pal-test.adyen.com/pal/Recurring.wsdl", array(
		"login" => "YourWSUser",    
		"password" => "YourWSUserPassword",    
		"style" => SOAP_DOCUMENT,
		"encoding" => SOAP_LITERAL,
		"cache_wsdl" => WSDL_CACHE_BOTH,
		"trace" => 1
	)
 );
 
/**
  * The request should contain the following variables:
  * - merchantAccount: The merchant account the payment was processed with.
  * - shopperReference: The reference to the shopper. This shopperReference must be the same as the 
  *   shopperReference used in the initial payment.
  * - recurring->contract: This should be the same value as recurringContract in the payment where the recurring
  *   contract was created. However if ONECLICK,RECURRING was specified initially
  *   then this field can be either ONECLICK or RECURRING.
  */
 try{
	 
	$result = $client->listRecurringDetails(array(
			"request" => array (
				"merchantAccount" => "YourMerchantAccount",
				"shopperReference" => "TheShopperreference",  
	            "recurring"=> array(
					"contract" => "TheTypeOfContractToRequest" // i.e.: "ONECLICK","RECURRING" or "ONECLICK,RECURRING"
				) 
			)
		)
	);
		
	/**
	 * The response will be a result with a list of zero or more details containing at least the following:
	 * - recurringDetailReference: The reference the details are stored under.
	 * - variant: The payment method (e.g. mc, visa, elv, ideal, paypal)
	 * - creationDate: The date when the recurring details were created.
	 * 
	 * The recurring contracts are stored in the same object types as you would have 
	 * submitted in the initial payment. 
	 */
	print_r($result);
						
 }catch(SoapFault $ex){
	 print("<pre>");
	 print($exception);
	 print("<pre>");
 }
