<?php
/**
 * Cancel a Payment using HTTP Post
 * 
 * Similarly to the capture modification, in order to cancel an authorised (card) 
 * payment you send a modification request to the cancel action.
 * This file shows how an authorised payment should be canceled by sending 
 * a modification request using HTTP Post. 
 * 
 * Please note: using our API requires a web service user. Set up your Webservice 
 * user: Adyen Test CA >> Settings >> Users >> ws@Company. >> Generate Password >> Submit 
 *  
 * @link    4.Modifications/httppost/cancel.php 
 * @author  Created by Adyen - Payments Made Easy
 */
  
 /**
  * Variables required in request:
  * - action: In this case, it's the capture payment: Payment.cancel
  * - merchantAccount: The merchant account the payment was processed with.
  * - originalReference: This is the pspReference that was assigned to the authorisation
  */
   
 $request = array(
    "action" => "Payment.cancel",
    "modificationRequest.merchantAccount" => "YourMerchantAccount",
    "modificationRequest.originalReference" => "PspReferenceOfTheAuthorisedPayment", 
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
	 * If the message was syntactically valid and merchantAccount is correct you will 
	 * receive a confirmation with the following fields:
	 * - modificationResult_pspReference: A new reference to uniquely identify this modification request. 
	 * - modificationResult_response: This will confirm [cancel-received]. 
	 * 
	 * Please note: The result of the cancellation is sent via a notification with eventCode CANCELLATION.
	 */
	 
 	parse_str($result,$result);
    print_r(($result));
 }
 
 curl_close($ch);
