<?php
/*
By: Spicer Matthews <spicer@cloudmanic.com>
Copyright: (c) 2011, Cloudmanic Labs, LLC.
Documents Url: 
http://skyclerk.com/api-v1/contacts/add-contact-entry
http://skyclerk.com/api-v1/contacts/delete-contact-entry
	
Description: Example of making a call to the API to insert a 
							contact entry, get by id, and delete a contact entry 
*/

include('../config/ApiConfig.php');
include('../lib/Skyclerk.php');
	
$SK = new Skyclerk($_api_key_host, $_api_key);

// Add a Contact
$SK->set_data("ContactsName", 'Acme Inc.');
$SK->set_data("ContactsFirstName", "Joe");
$SK->set_data("ContactsLastName", "Williams");
$SK->set_data("ContactsAddress", "1234 West Main Street");
$SK->set_data("ContactsCity", "Portland");
$SK->set_data("ContactsState", "OR");
$SK->set_data("ContactsZip", "55555");
$SK->set_data("ContactsPhone", "555-555-5555");
$SK->set_data("ContactsFax", "666-666-6666");
$SK->set_data("ContactsWebsite", "http://www.example.org");
$SK->set_data("ContactsType", "Vendor"); // Vendor, Customer, Both

if($id = $SK->add_contact()) {
  echo "OK - Add Contact passed. Contact Id: $id <br />";		
}	else {
  echo "FAILED - Add Contact failed with message: " . $SK->error_to_string() . "<br />";
}

// Get Contact by Id
if($data = $SK->get_contact_by_id($id)) {
  echo "OK - Get Contact by id passed.<br />";
  echo '<pre>' . print_r($data, TRUE) . '</pre>';		
}	else {
  echo "FAILED - Get Contact by id failed with message: " . $SK->error_to_string() . "<br />";
}

// Delete a Contact By Id.
if($SK->delete_contact_by_id($id)) {
  echo "OK - Delete contact by id. <br />";
} else {
  echo "FAILED - Delete contact failed with message: " . $SK->error_to_string() . "<br />";		
}
?>