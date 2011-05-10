<?php
/*
By: Spicer Matthews <spicer@cloudmanic.com>
Copyright: (c) 2011, Cloudmanic Labs, LLC.
Documents Url: 
http://skyclerk.com/api-v1/categories-labels/add-category-entry
http://skyclerk.com/api-v1/categories-labels/delete-category-entry
	
Description: Example of making a call to the API to insert a 
							Category entry, update the category, and delete a category entry 
*/

include('../config/ApiConfig.php');
include('../lib/Skyclerk.php');
	
$SK = new Skyclerk($_api_key_host, $_api_key);


// Add Category
if($id = $SK->add_category('My Test Category', 'Income')) {
  echo "OK - Add Category passed. Category Id: $id<br />";		
}	else {
  echo "FAILED - Add Category failed with message: " . $SK->error_to_string() . "<br />";
}

// Update Category Name
if($id = $SK->update_category_by_id($id, 'Rename Category')) {
  echo "OK - Update Category name passed. Category Id: $id<br />";		
}	else {
  echo "FAILED - Update Category name failed with message: " . $SK->error_to_string() . "<br />";
}

// Delete the label item we just created.
if($data = $SK->delete_category_by_id($id)) {
  echo "OK - Delete category by id. <br />";
} else {
  echo "FAILED - Delete category failed with message: " . $SK->error_to_string() . "<br />";		
}
?>