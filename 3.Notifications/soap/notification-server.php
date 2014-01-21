<?php
/**
 * Receive notifcations from Adyen using SOAP
 * 
 * Whenever a payment is made, a modification is processed or a report is
 * available we will notify you. The notifications tell you for instance if
 * an authorisation was performed successfully. 
 * Notifications should be used to keep your backoffice systems up to date with
 * the status of each payment and modification. Notifications are sent 
 * using a SOAP call or using HTTP POST to a server of your choice. 
 * This file describes how SOAP notifcations can be received in PHP.
 * 
 * Security:
 * We recommend you to secure your notification server. You can secure it
 * using a username/password which can be configured in the CA. The username
 * and password will be available in the request in: $_SERVER['PHP_AUTH_USER'] and 
 * $_SERVER['PHP_AUTH_PW']. Alternatively, is to allow only traffic that
 * comes from Adyen servers. 
 * 
 * @link	3.Notifications/soap/notification_server.php 
 * @author	Created by Adyen - Payments Made Easy
 */
  
 /**
  * Create a SoapServer which implements the SOAP protocol used by Adyen and 
  * implement the sendNotification action in order to call a function handling
  * the notification.
  * 
  * new SoapServer($wsdl,$options)
  * - $wsdl points to the wsdl you are using;
  * - $options[cache_wsdl] = WSDL_CACHE_BOTH, we advice 
  *   to cache the WSDL since we usually never change it.
  */  
 $server = new SoapServer(
	"https://ca-test.adyen.com/ca/services/Notification?wsdl", array(
		"style" => SOAP_DOCUMENT,
		"encoding" => SOAP_LITERAL,
		"cache_wsdl" => WSDL_CACHE_BOTH,
		"trace" => 1
	)
 ); 
 $server->addFunction("sendNotification"); 
 $server->handle();
 
 function sendNotification($request) {
	
	/**
	 * In SOAP it's possible that we send you multiple notifications
	 * in one request. First we verify if the request the SOAP envelopes.
	 */
	if(isset($request->notification->notificationItems) && count($request->notification->notificationItems) >0)
		foreach($request->notification->notificationItems as $notificationRequestItem){
			
			/**
			 * Each $notificationRequestItem contains the following fields:
			 * $notificationRequestItem->amount->currency
			 * $notificationRequestItem->amount->value
			 * $notificationRequestItem->eventCode
			 * $notificationRequestItem->eventDate
			 * $notificationRequestItem->merchantAccountCode
			 * $notificationRequestItem->merchantReference
			 * $notificationRequestItem->originalReference
			 * $notificationRequestItem->pspReference
			 * $notificationRequestItem->reason
			 * $notificationRequestItem->success
			 * $notificationRequestItem->paymentMethod
			 * $notificationRequestItem->operations
			 * $notificationRequestItem->additionalData
			 * 
			 * 
			 * We reccomend you to handle the notifications based on the
			 * eventCode types available, please refer to the integration
			 * manual for a comprehensive list. For debug purposes we also
			 * recommend you to store the notification itself.
			 */
			
			switch($notificationRequestItem->eventCode){
				
					case 'AUTHORISATION':
							// Handle AUTHORISATION notification.
							// Confirms that the payment was authorised successfully. 
						break;
						
					case 'CANCELLATION':
							// Handle CANCELLATION notification.
							// Confirms that the payment was cancelled successfully. 
						break;
						
					case 'REFUND':
							// Handle REFUND notification.
							// Confirms that the payment was refunded successfully. 
						break;
						
					case 'CANCEL_OR_REFUND':
							// Handle CANCEL_OR_REFUND notification.
							// Confirms that the payment was refunded or cancelled successfully. 
						break;
						
					case 'CAPTURE':
							// Handle CAPTURE notification.
							// Confirms that the payment was successfully captured. 
						break;
						
					case 'REFUNDED_REVERSED':
							// Handle REFUNDED_REVERSED notification.
							// Tells you that the refund for this payment was successfully reversed. 
						break;
						
					case 'CAPTURE_FAILED':
							// Handle AUTHORISATION notification.
							// Tells you that the capture on the authorised payment failed. 
						break;
								
					case 'REQUEST_FOR_INFORMATION':
							// Handle REQUEST_FOR_INFORMATION notification.
							// Information requested for this payment .
						break;
						
					case 'NOTIFICATION_OF_CHARGEBACK':
							// Handle NOTIFICATION_OF_CHARGEBACK notification.
							// Chargeback is pending, but can still be defended 
						break;
						
					case 'CHARGEBACK':
							// Handle CHARGEBACK notification.
							// Payment was charged back. This is not sent if a REQUEST_FOR_INFORMATION or
							// NOTIFICATION_OF_CHARGEBACK notification has already been sent.
						break;
					
					case 'CHARGEBACK_REVERSED':
							// Handle CHARGEBACK_REVERSED notification.
							// Chargeback has been reversed (cancelled).
						break;
					
					case 'REPORT_AVAILABLE':
							// Handle REPORT_AVAILABLE notification.
							// There is a new report available, the URL of the report is in the "reason" field.
						break;
			}
		}
			
	 
	 /**
	  * Returning [accepted], please make sure you always
	  * return [accepted] to us, this is essential to let us 
	  * know that you received the notification. If we do NOT receive
	  * [accepted] we try to send the notification again which
	  * will put all other notification in a queue.
	  */
	 return array("notificationResponse" => "[accepted]");
 } 
 
 


