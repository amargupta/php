<?php
/**
 * Cancel or Refund a Payment using HTTP Post
 * 
 * If you do not know if the payment is captured but you want to reverse 
 * the authorisation you can send a modification request to the cancelOrRefund action 
 * This file shows how a payment can be cancelled or refunded by a 
 * modification request using HTTP Post. 
 * 
 * Please note: using our API requires a web service user. Set up your Webservice 
 * user: Adyen Test CA >> Settings >> Users >> ws@Company. >> Generate Password >> Submit 
 *  
 * @link    4.Modifications/httppost/cancel-or-refund.php 
 * @author  Created by Adyen - Payments Made Easy
 */
  
 /**
  * Variables required in request:
  * - action: In this case, it's the capture payment: Payment.cancelorrefund
  * - merchantAccount: The merchant account the payment was processed with.
  * - originalReference: This is the pspReference that was assigned to the authorisation
  */
   
 $request = array(
    "action" => "Payment.cancelOrRefund",
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
	 * - modificationResult_response: This will be a confirmation: [cancelOrRefund-received]. 
	 * 
	 * Please note: The actual result of the cancel or refund is sent via a notification with eventCode CANCEL_OR_REFUND.
	 */
	 
 	parse_str($result,$result);
    print_r(($result));
 }
 
 curl_close($ch);
