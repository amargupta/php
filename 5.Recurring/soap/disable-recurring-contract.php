<?php
/**
 * Disable recurring contract using SOAP
 * 
 * Disabling a recurring contract (detail) can be done by calling the disable action 
 * on the Recurring service with a request. This file shows how you can disable
 * a recurring contract using SOAP. 
 * 
 * Please note: using our API requires a web service user. Set up your Webservice 
 * user: Adyen Test CA >> Settings >> Users >> ws@Company. >> Generate Password >> Submit 
 *  
 * @link	5.Recurring/soap/disable-recurring-contract.php 
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
 * - action: Specifies which action on the API is required 
 * - merchantAccount: The merchant account the payment was processed with.
 * - shopperReference: The reference to the shopper. This shopperReference must be the same as the 
 *   shopperReference used in the initial payment. 
 * - recurringDetailReference: The recurringDetailReference of the details you wish to 
 *   disable. If you do not supply this field all details for the shopper will be disabled 
 *   including the contract! This means that you can not add new details anymore.
 */
 try{
	 
	$result = $client->disable(array(
			"request" => array(
				"merchantAccount" => "YourMerchantAccount",
				"shopperReference" => "TheShopperreference", 
				"recurringDetailReference" => "TheReferenceToTheContract",	
			)
		)
	);
		
	/**
	 * The response will be a result object with a single field response. If a single detail was 
	 * disabled the value of this field will be [detail-successfully-disabled] or, if all 
	 * details are disabled, the value is [all-details-successfully-disabled].
	 */
	print_r($result);
						
 }catch(SoapFault $ex){
	 print("<pre>");
	 print($exception);
	 print("<pre>");
 }
