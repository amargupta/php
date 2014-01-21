<?php
/**
 * Disable recurring contract using HTTP Post
 * 
 * Disabling a recurring contract (detail) can be done by calling the disable action 
 * on the Recurring service with a request. This file shows how you can disable
 * a recurring contract using HTTP Post. 
 * 
 * Please note: using our API requires a web service user. Set up your Webservice 
 * user: Adyen Test CA >> Settings >> Users >> ws@Company. >> Generate Password >> Submit 
 *  
 * @link	5.Recurring/httppost/disable-recurring-contract.php 
 * @author	Created by Adyen - Payments Made Easy
 */
 
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
 $request = array(
    "action" => "Recurring.disable",
    "disableRequest.merchantAccount" => "YourMerchantAccount",  
    "disableRequest.shopperReference" => "TheShopperreference", 
    "disableRequest.recurringDetailReference" => "TheReferenceToTheContract", 
 ); 
  
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, "https://pal-test.adyen.com/pal/adapter/httppost");
 curl_setopt($ch, CURLOPT_HEADER, false); 
 curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC  );
 curl_setopt($ch, CURLOPT_USERPWD, "YourWSUser:YourWSUserPassword"); 
 curl_setopt($ch, CURLOPT_POST,count($request));
 curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($request));
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
 $result = curl_exec($ch);
 
 if($result === false)
    echo "Error: " . curl_error($ch);
 else{
	/**
	 * The response will be a result object with a single field response. If a single detail was 
	 * disabled the value of this field will be [detail-successfully-disabled] or, if all 
	 * details are disabled, the value is [all-details-successfully-disabled]. 
	 */
 	parse_str($result,$result);
    print_r(($result));
 }
 
 curl_close($ch);
