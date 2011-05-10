<?php
/*
By: Spicer Matthews <spicer@cloudmanic.com>
Copyright: (c) 2011, Cloudmanic Labs, LLC.
Documents Url: 
http://skyclerk.com/api-v1/categories-labels/add-label-entry
http://skyclerk.com/api-v1/categories-labels/delete-label-entry
	
Description: Example of making a call to the API to insert a 
							Label entry, Get label by id, update the label, and delete a label entry 
*/

include('../config/ApiConfig.php');
include('../lib/Skyclerk.php');
	
$SK = new Skyclerk($_api_key_host, $_api_key);

// Add label
if($id = $SK->add_label('My First Test Label')) {
  echo "OK - Add label passed. Label Id: $id<br />";		
}	else {
  echo "FAILED - Add label failed with message: " . $SK->error_to_string() . "<br />";
}

// Get Label by Id.
if($data = $SK->get_label_by_id($id)) {
	echo '<pre>' . print_r($data, TRUE) . '</pre>';
  echo "OK - Get Label by id passed.<br />";		
}	else {
  echo "FAILED - Get label by id failed with message: " . $SK->error_to_string() . "<br />";
}

// Update Label Name
if($id = $SK->update_label_by_id($id, 'Rename Label')) {
  echo "OK - Update Label name passed. Label Id: $id<br />";		
}	else {
  echo "FAILED - Update Label name failed with message: " . $SK->error_to_string() . "<br />";
}

// Delete the label item we just created.
if($data = $SK->delete_label_by_id($id)) {
  echo "OK - Delete label by id. <br />";
} else {
  echo "FAILED - Delete label failed with message: " . $SK->error_to_string() . "<br />";		
}
?>