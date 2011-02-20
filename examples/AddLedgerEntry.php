<?php
/*
By: Spicer Matthews <spicer@cloudmanic.com>
Copyright: (c) 2011, Cloudmanic Labs, LLC.
Documents Url: 
http://skyclerk.com/api-v1/ledger/add-ledger-entry
http://skyclerk.com/api-v1/ledger/delete-ledger-entry
	
Description: Example of making a call to the API to insert a 
							ledger entry, get by id, and delete a ledger entry 
*/

include('../config/ApiConfig.php');
include('../lib/Skyclerk.php');
	
$SK = new Skyclerk($_api_key_host, $_api_key);
$files = array();

// Upload a file to attach. Returns filekey that refers to this uploaded file.
if($file = $SK->upload_file('../assets/blankinvoice1.jpg')) {
  echo "OK - Upload file #1 Passed. <br />";
  $files[] = $file;
} else {
  echo "FAILED - Upload file #1 failed with message: " . $SK->error_to_string() . "<br />";
}

// Set data
$SK->set_data("ContactsName", "Bob's Pool Cleaning Service");
$SK->set_data("ContactsFirstName", "Bob");
$SK->set_data("ContactsLastName", "Jennings");
$SK->set_data("CategoryName", "Customer Sales");
$SK->set_data("LedgerAmount", "345.89");
$SK->set_data("LedgerDate", "3/14/2010");
$SK->set_data("LedgerNote", "This is a test note from the test API script.");
$SK->set_label('Rental Property');
$SK->set_label('Tenant Income');		
$SK->set_label('Cash');	

// Add files to ledger add
foreach($files AS $row)
{
  $SK->set_file($row);	
}

if($id = $SK->add_ledger()) {
  echo "OK - Add ledger with no contact id passed. Ledger Id: $id <br />";		
}	else {
  echo "FAILED - Add ledger with no contact id failed with message: " . $SK->error_to_string() . "<br />";
}


// Make sure the ledger item stuck on the server side.
if($data = $SK->get_ledger_by_id($id)) {
  echo "OK - Get ledger by id passed \n";
  echo '<pre>' . print_r($data, TRUE) . '</pre>';
} else {
  echo "FAILED - Get ledger by id failed with message: " . $SK->error_to_string() . "\n";		
}

// Delete the ledger item we just created.
if($data = $SK->delete_ledger_by_id($id))
{
  echo "OK - Delete ledger by id passed \n";
} else {
  echo "FAILED - Delete ledger by id failed with message: " . $SK->error_to_string() . "\n";		
}
?>