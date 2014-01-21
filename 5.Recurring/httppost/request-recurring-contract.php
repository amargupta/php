<?php
/**
 * Request recurring contract details using HTTP Post
 * 
 * Once a shopper has stored RECURRING details with Adyen you are able to process
 * a RECURRING payment. This file shows you how to retrieve the RECURRING contract(s) 
 * for a shopper using HTTP Post. 
 * 
 * Please note: using our API requires a web service user. Set up your Webservice 
 * user: Adyen Test CA >> Settings >> Users >> ws@Company. >> Generate Password >> Submit 
 *  
 * @link	5.Recurring/httppost/request-recurring-contract.php 
 * @author	Created by Adyen - Payments Made Easy 
 */

/**
 * The request should contain the following variables:
 * - action: Specifies which action on the API is required 
 * - merchantAccount: The merchant account the payment was processed with.
 * - shopperReference: The reference to the shopper. This shopperReference must be the same as the 
 *   shopperReference used in the initial payment.
 * - recurring->contract: This should be the same value as recurringContract in the payment where the recurring
 *   contract was created. However if ONECLICK,RECURRING was specified initially
 *   then this field can be either ONECLICK or RECURRING.
 */
 $request = array(
    "action" => "Recurring.listRecurringDetails",
    "recurringDetailsRequest.merchantAccount" => "YourMerchantAccount", 
    "recurringDetailsRequest.shopperReference" => "TheShopperreference",   
    "recurringDetailsRequest.recurring.contract" => "TheTypeOfContractToRequest", // i.e.: "ONECLICK","RECURRING" or "ONECLICK,RECURRING"
 ); 

 
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, "https://pal-test.adyen.com/pal/adapter/httppost");
 curl_setopt($ch, CURLOPT_HEADER, false); 
 curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC  );
 curl_setopt($ch, CURLOPT_USERPWD,"YourWSUser:YourWSUserPassword"); 
 curl_setopt($ch, CURLOPT_POST,count($request));
 curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($request));
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
 $result = curl_exec($ch);
 
 if($result === false)
    echo "Error: " . curl_error($ch);
 else{
	/**
	 * The response will be a result with a list of zero or more details at least containing the following:
	 * - recurringDetailReference: The reference the details are stored under.
	 * - variant: The payment method (e.g. mc, visa, elv, ideal, paypal)
	 * - creationDate: The date when the recurring details were created.
	 */
 	parse_str($result,$result);
    print_r(($result));
 }
 
 curl_close($ch);
