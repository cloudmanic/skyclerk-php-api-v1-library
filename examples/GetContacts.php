<?php
/*
By: Spicer Matthews <spicer@cloudmanic.com>
Copyright: (c) 2011, Cloudmanic Labs, LLC.
Document Url: http://skyclerk.com/api-v1/contacts/get-contact-entries
	
Description: Example of making a call to the API to to get contacts information. 
*/

include('../config/ApiConfig.php');
include('../lib/Skyclerk.php');
	
$SK = new Skyclerk($_api_key_host, $_api_key);

// With a limit of 5, remove the limit to get everything.
$SK->set_limit(5);
if($data = $SK->get_contacts())
{
  echo "OK - Get contacts with limit passed.\n";
  echo '<pre>' . print_r($data, TRUE) . '</pre>';		
}	
else
{
  echo "FAILED - Get contacts with limit failed with message: " . $this->_skyledger->error_to_string() . "\n";
}
?>