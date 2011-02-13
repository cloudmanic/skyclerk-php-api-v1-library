<?php
/*
By: Spicer Matthews <spicer@cloudmanic.com>
Copyright: (c) 2011, Cloudmanic Labs, LLC.
Document Url: http://skyclerk.com/api-v1/getting-started
	
Description: Example of making a call to the API to to get a user's API key.
this service all will post a valid email and password. The response will be
the account url, and api key of the user. If the user has more than one Skyclerk
account an array of accounts will be returned. This is the only API request that 
is done with the email and password. Any other requests are made with the API key. 
*/

include('../config/ApiConfig.php');
include('../lib/Skyclerk.php');
	
$SK = new Skyclerk($_api_key_host);

if($data = $SK->get_api_key($_api_email, $_api_password))
{
  echo "OK - Success Api key test passed. \n";
  echo '<pre>' . print_r($data, TRUE) . '</pre>';		
}
else
{
  echo "FAILED - Success Api key test passed with message: " . $SK->error_to_string() . "\n";
}
?>