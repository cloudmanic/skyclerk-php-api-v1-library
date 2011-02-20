<?php
/*
By: Spicer Matthews <spicer@cloudmanic.com>
Copyright: (c) 2011, Cloudmanic Labs, LLC.
Document Url: http://skyclerk.com/api-v1/ledger/update-ledger-entry
	
Description: Example of making a call to the API to insert a 
							ledger entry, updating, get by id, and delete a ledger entry 
*/

include('../config/ApiConfig.php');
include('../lib/Skyclerk.php');
	
$SK = new Skyclerk($_api_key_host, $_api_key);

// Set Data
$SK->set_data("ContactsName", "Jane's Dancing School");
$SK->set_data("ContactsFirstName", "Jane");
$SK->set_data("ContactsLastName", "Wells");
$SK->set_data("CategoryName", "Sales");
$SK->set_data("LedgerAmount", "-143.17");
$SK->set_data("LedgerDate", "6/17/2010");
$SK->set_data("LedgerNote", "Test one, two, three");

if($id = $SK->add_ledger())
{
  echo "OK - Add ledger with no contact id passed. Ledger Id: $id <br />";		
}	
else
{
  echo "FAILED - Add ledger with no contact id failed with message: " . $SK->error_to_string() . "<br />";
}

// New data to update.
$SK->set_data("LedgerAmount", "-1043.17");
$SK->set_data("LedgerDate", "10/20/2010");
$SK->set_data("LedgerNote", "Test three, two, one");

if($id = $SK->update_ledger_by_id($id))
{
  echo "OK - Update ledger. Ledger Id: $id <br />";		
}	
else
{
  echo "FAILED - Update ledger failed with message: " . $SK->error_to_string() . "<br />";
}

// Make sure the ledger item stuck on the server side.
if($data = $SK->get_ledger_by_id($id)) {
  echo "OK - Get ledger by id passed <br />";
  echo '<pre>' . print_r($data, TRUE) . '</pre>';
} else {
  echo "FAILED - Get ledger by id failed with message: " . $SK->error_to_string() . "<br />";		
}

// Delete the ledger item we just created.
if($data = $SK->delete_ledger_by_id($id))
{
  echo "OK - Delete ledger by id passed \n";
} else {
  echo "FAILED - Delete ledger by id failed with message: " . $SK->error_to_string() . "\n";		
}
?>