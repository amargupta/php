<?php
/**
 * Customfields service using SOAP
 *
 * Custom fields are a powerful feature of the payment pages that allow you to 
 * add form fields to the HPP. These will be sent to you before final payment submission 
 * for approval; you may use this feature to capture any additional data or permissions \
 * that you may require, such as collecting shipping data, forcing the shopper to accept 
 * terms and conditions, or checking a validation code.
 *
 * Customfields are not enabled by default, they have to be enabled per skin.
 * How can I enable customfields?
 * 1. Enable for skin: CA >> Skins >> Select Skin >> Custom Fields >> Set up URLs
 * 2. Add HTML input fields in Skin they should have names like: name="customfields.subscribe:
 *    Please see the Skin Creation Manual on how to add customfields to your skin.
 *
 * @link	8.Customfields/soap/customfields-server.php 
 * @author	Created by Adyen - Payments Made Easy
 */

/**
  * Create a SoapServer which implements the SOAP protocol used by Adyen and 
  * implement the check action to receive the custom fields.
  * 
  * new SoapServer($wsdl,$options)
  * - $wsdl points to the wsdl you are using;
  * - $options[cache_wsdl] = WSDL_CACHE_BOTH, we advice 
  *   to cache the WSDL since we usually never change it.
  */  
 $server = new SoapServer(
	"https://ca-test.adyen.com/ca/services/CustomAmount?wsdl", array(
		"style" => SOAP_DOCUMENT,
		"encoding" => SOAP_LITERAL,
		"cache_wsdl" => WSDL_CACHE_BOTH,
		"trace" => 1
	)
 ); 
  	
 function check($request){
 	
 	/**
 	 * $request should containt the following variables:
 	 * $request->request->customFields->CustomField = array with the customfields; 
 	 * $request->request->merchantAccount = The merchant account from which the payment is processed with
 	 * $request->request->merchantReference = The merchant reference.
 	 *
	 * In this example we only expect one parameter: 
	 * terms_conditions = true, a simple PHP check can be done to verify if the shopper
	 * has thicked the checkbox. Obviously, more sophisticated validations can be added.
	 */
	
	$response = array();
	$customfields = array();
	
	if(isset($request->request->customFields->CustomField) && count($request->request->customFields->CustomField)>0)
		foreach($request->request->customFields->CustomField as $kv)
			$customfields[$kv['name']] = $kv['value'];
	
	
	if(isset($customfields['terms_conditions']) && $customfields['terms_conditions']=="true"){
		$response = array(
			"response" => "[accepted]",
			"customFields" => array(),
			"sessionFields" => array(
				"name" => "terms_conditions",
				"value" => "true"
			)
		);
	}else{
		$response = array(
			"response" => "[invalid]",
			"customFields" => array(
				"name" => "terms_conditions",
				"value" => "Please agree with terms and conditions!"
			),
			"sessionFields" => array(),
		);
		
	}
	
	return array("checkResponse" => $response); array("response" => array(
 }
 
 $server->addFunction("check"); 
 $server->handle();
 
