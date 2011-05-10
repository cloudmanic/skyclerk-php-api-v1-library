<?php
/*
By: Spicer Matthews <spicer@cloudmanic.com>
Copyright: (c) 2011, Cloudmanic Labs, LLC.
Document Url: http://skyclerk.com/api-v1/categories-labels/get-category-entries
	
Description: Example of making a call to the API to to get categories information. 
*/

include('../config/ApiConfig.php');
include('../lib/Skyclerk.php');
	
$SK = new Skyclerk($_api_key_host, $_api_key);

// With a limit of 5, remove the limit to get everything.
/$SK->set_limit(5);
if($data = $SK->get_categories())
{
  echo "OK - Get categories with limit passed.<br />";
  echo '<pre>' . print_r($data, TRUE) . '</pre>';		
}	
else
{
  echo "FAILED - Get categories with limit failed with message: " . $SK->error_to_string() . "<br />";
}
?>