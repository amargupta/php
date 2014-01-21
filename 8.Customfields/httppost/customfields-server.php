<?php
/**
 * Customfields service using HTTP Post
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
 * @link	8.Customfields/httppost/customfields-server.php 
 * @author	Created by Adyen - Payments Made Easy
 */
 
 
/**
 * This example requires the shopper to agree to the terms & conditions.
 * Usually the request looks like this:
 * array(
 * [request_customFields_0_name] => SomeField1
 * [request_customFields_0_value] => SomeValue1
 * [request_customFields_1_name] => SomeField2
 * [request_customFields_1_value] => SomeValue2
 * [request_merchantAccount] => merchantAccount
 * [request_merchantReference] => My own reference
 * )
 */

 $response = null;
 $customfields = array();
 $customfieldsCount = 0;
 
 // Check how many customfields are submitted
 if(isset($_POST)&&count($_POST)>0)
 	foreach($_POST as $k => $v)
		if(stristr($k,"customField"))
			$customfieldsCount++;
 
 // Add customfields to seperate array
 for($i=0;$i<(ceil($customfieldsCount/2));$i++)
	$customfields[$_POST["request_customFields_".$i."_name"]] = $_POST["request_customFields_".$i."_value"];

/**
 * In this example we only expect one parameter: 
 * terms_conditions = true, a simple PHP check can be done to verify if the shopper
 * has thicked the checkbox. Obviously, more sophisticated validations can be added.
 */ 
 if(isset($customfields['terms_conditions']) && $customfields['terms_conditions']=="true"){
 	$response.= "response.sessionFields.0.name=terms_conditions&";
 	$response.= "response.sessionFields.0.value=true&";
	$response.= "response.response=[accepted]";
 }else{
 	$response.= "response.customFields.0.name=terms_conditions&";
    $response.= "response.customFields.0.value=Please agree with terms and conditions!&";
    $response.= "response.response=[invalid]";
 }
  	
 print $response;
