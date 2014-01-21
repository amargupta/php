<?php
/**
 * Submit Recurring Payment using HTTP Post
 * 
 * You can submit a recurring payment using a specific recurringDetails record or by using the last created
 * recurringDetails record. The request for the recurring payment is done using a paymentRequest.
 * This file shows how a recurring payment can be submitted using our HTTP Post API. 
 * 
 * Please note: using our API requires a web service user. Set up your Webservice 
 * user: Adyen Test CA >> Settings >> Users >> ws@Company. >> Generate Password >> Submit 
 *  
 * @link	5.Recurring/httppost/submit-recurring-payment.php 
 * @author	Created by Adyen - Payments Made Easy
 */
 
/**
  * A recurring payment can be submitted by sending a PaymentRequest 
  * to the authorise action, the request should contain the following
  * variables:
  * - action: Specifies which action on the API is required 
  * - selectedRecurringDetailReference: The recurringDetailReference you want to use for this payment. 
  *   The value LATEST can be used to select the most recently used recurring detail. See request-recurring-contract.php 
  *   on how to retrieve contract details for a shopper.
  * - recurring: This should be RECURRING. 
  * - merchantAccount: The merchant account the payment was processed with.
  * - amount: The amount of the payment
  * 	- currency: the currency of the payment
  * 	- amount: the amount of the payment
  * - reference: Your reference of this recurring transaction.
  * - shopperEmail: The e-mail address of the shopper 
  * - shopperReference: The shopper reference, i.e. the shopper ID
  * - shopperInteraction: Should be ContAuth which specifies it's a RECURRING payment. 
  * - fraudOffset: Numeric value that will be added to the fraud score (optional)
  * - shopperIP: The IP address of the shopper (optional)
  * - shopperStatement: Some acquirers allow you to provide a statement (optional)
  */
  
  $request = array(
    "action" => "Payment.authorise",
    "paymentRequest.selectedRecurringDetailReference" => "TheSelectedRecurringDetailReferenceContract",
    "paymentRequest.recurring.contract" => "RECURRING",
	"paymentRequest.merchantAccount" => "YourMerchantAccount",
	"paymentRequest.amount.currency" => "EUR",
	"paymentRequest.amount.value" => "199",
	"paymentRequest.reference" => "TEST-RECURRING-PAYMENT-" . date("Y-m-d-H:i:s"),
	"paymentRequest.shopperEmail" => "ShopperEmailAddress", 
	"paymentRequest.shopperReference" => "ShopperReference",
	"paymentRequest.shopperInteraction" => "ContAuth",
	"paymentRequest.fraudOffset" => "",
	"paymentRequest.shopperIP" => "ShopperIPAddress",
	"paymentRequest.shopperStatement" => "",
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
	 * If the recurring payment message passes validation a risk analysis will be done and, depending on the
	 * outcome, an authorisation will be attempted. You receive a
	 * payment response with the following fields:
	 * - pspReference: The reference we assigned to the payment;
	 * - resultCode: The result of the payment. One of Authorised, Refused or Error;
	 * - authCode: An authorisation code if the payment was successful, or blank otherwise;
	 * - refusalReason: If the payment was refused, the refusal reason.
	 */ 
 	parse_str($result,$result);
    print_r(($result));
 }
 
 curl_close($ch);
