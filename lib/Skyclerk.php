<?php
//
// By: Cloudmanic Labs, LLC <http://www.cloudmanic.com>
// Date: 12/12/2010
// Author: Spicer Matthews <spicer@cloudmanic.com>
//
class Skyclerk 
{
	private $_apihost;
	private $_apikey;
	private $_requesturl;
	private $_postdata;
	private $_rawresponse;
	private $_response;
	private $_error;
	private $_urlextra = '';

	//
	// Constructor.....
	//
	function __construct($host = NULL, $key = NULL)
	{
		// Set the host if passed in.
		if(! is_null($host)) 
		{
			$this->set_host($host);
		}
	
		// Set the api key if passed in.
		if(! is_null($key))
		{
			$this->set_key($key);
		}
	}
	
	//
	// Get raw response.
	//
	function get_raw()
	{
		return $this->_rawresponse;
	}
	
	//
	// Set the host url
	//
	function set_host($host)
	{
		$this->_apihost = $host;
	}

	//
	// Set the host api key
	//
	function set_key($key)
	{
		$this->_apikey = $key;
	}
	
	//
	// Return error message.
	//
	function get_error()
	{
		return $this->_error;
	}
	
	//
	// Return a string of errors.
	//
	function error_to_string()
	{
		$str = '';
		if(is_array($this->_error))
		{
			foreach($this->_error AS $key => $row)
			{
				$str .= $row['error'] . ' ';
			}
		}
		
		return trim($str);
	}
	
	//
	// Setup post data.
	//
	function set_data($key, $val)
	{
		$this->_postdata[$key] = $val;
	}

	//
	// Add a file to a ledger transaction.
	//
	function set_file($file)
	{
		if(isset($this->_postdata['Files'])) 
		{
			$this->_postdata['Files'] .= ',' . $file;
		}
		else
		{
			$this->_postdata['Files'] = $file;
		} 
	}
	
	//
	// Add a label to a ledger transaction.
	//
	function set_label($label)
	{
		if(isset($this->_postdata['Labels'])) 
		{
			$this->_postdata['Labels'] .= ',' . $label;
		}
		else
		{
			$this->_postdata['Labels'] = $label;
		} 
	}
	
	//
	// Set the limit for get returns.
	//
	function set_limit($limit, $offset = NULL)
	{
		if(is_null($offset))
		{
			$this->_urlextra .= "limit/$limit/";
		}
		else
		{
			$this->_urlextra .= "limit/$limit/offset/$offset/";		
		}
	}
	
	//
	// Set the return type to income or expense
	//
	function set_type($type)
	{
		if(($type == 'income') || ($type == 'expense'))
		{
			$this->_urlextra .= "type/$type/";		
		}
	}
	
	//
	// Get user API key. This is used when the user does not know their API key. 
	// We grab the API key by passing up the user's email address and password.
	// Once we have an API key we use the key for all future calls.
	//
	function get_api_key($email, $password)
	{
		$this->_requesturl = $this->_apihost . '/getapikey';
		$this->_postdata = array('Email' => $email, 'Password' => $password);	
		if($data = $this->_request())
		{
			return $data['accounts'];
		}
		else
		{
			return 0;
		}	
	}

	// -------------------- ContactNotes API Requests -------------------- //
	// For Key / Values See: http://skyclerk.com/api-v1/contactnotes
	// ------------------------------------------------------------------- //
	
	//
	// Get all ContactNotes
	//
	function get_contactnotes()
	{
		$this->_requesturl = $this->_apihost . "/contactnotes/" . $this->_urlextra;
		return $this->_request();
	} 

	//
	// Add A ContactNotes.
	//
	function add_contactnotes()
	{
		$this->_requesturl = $this->_apihost . '/contactnotes/action/add';
		if($data = $this->_request())
		{
			return $data['Id'];
		}
		else
		{
			return 0;
		}		
	}

	//
	// Get a ContactNotes by id.
	//
	function get_contactnotes_by_id($id)
	{
		$this->_requesturl = $this->_apihost . "/contactnotes/id/$id";
		if($data = $this->_request())
		{
			return $data;
		}
		else
		{
			return 0;
		}	
	}
	
	//
	// Delete a ContactNotes entry by id.
	//
	function delete_contactnotes_by_id($id)
	{
		$this->_requesturl = $this->_apihost . "/contactnotes/action/delete/id/$id";
		if($data = $this->_request())
		{
			return $data;
		}
		else
		{
			return 0;
		}
	}
	
	//
	// Update ContactNotes by id.
	//
	function update_contactnotes_by_id($id)
	{	
		$this->_requesturl = $this->_apihost . "/contactnotes/action/update/id/$id";
		if($data = $this->_request())
		{
			return $data['Id'];
		}
		else
		{
			return 0;
		}	
	}
	

	// ------------------------ Files API Requests ----------------------- //
	// For Key / Values See: http://cms.skyledger.com/api-v1/files
	// ------------------------------------------------------------------- //
	
	//
	// Upload a file to the server and get back a filekey refering to that
	// file to later be used in a ledger add / update request.
	//
	function upload_file($path)
	{
		if(file_exists($path))
		{
			$this->set_data('Filedata', '@' . $path);
			$this->_requesturl = $this->_apihost . "/file/" . $this->_urlextra;
			if($data = $this->_request())
			{
				return $data['data']['filekey'];
			}	
			else
			{
				$this->_error[] = array('error' => 'File key not returned', 'field' => 'N/A');
				return 0;
			}
		}
		else
		{
			$this->_error[] = array('error' => 'File not found', 'field' => 'N/A');
			return 0;
		}
	}

	// ----------------------- Contacts API Requests --------------------- //
	// For Key / Values See: http://cms.skyledger.com/api-v1/contacts
	// ------------------------------------------------------------------- //

	//
	// Get all contacts
	//
	function get_contacts()
	{
		$this->_requesturl = $this->_apihost . "/contacts/" . $this->_urlextra;
		return $this->_request();
	} 

	//
	// Add A Contact.
	//
	function add_contact()
	{
		$this->_requesturl = $this->_apihost . '/contacts/action/add';
		if($data = $this->_request())
		{
			return $data['Id'];
		}
		else
		{
			return 0;
		}		
	}

	//
	// Get a Contact by id.
	//
	function get_contact_by_id($id)
	{
		$this->_requesturl = $this->_apihost . "/contacts/id/$id";
		if($data = $this->_request())
		{
			return $data;
		}
		else
		{
			return 0;
		}	
	}
	
	//
	// Delete a Contact entry by id.
	//
	function delete_contact_by_id($id)
	{
		$this->_requesturl = $this->_apihost . "/contacts/action/delete/id/$id";
		if($data = $this->_request())
		{
			return $data;
		}
		else
		{
			return 0;
		}
	}
	
	//
	// Update Contact by id.
	//
	function update_contact_by_id($id)
	{	
		$this->_requesturl = $this->_apihost . "/contacts/action/update/id/$id";
		if($data = $this->_request())
		{
			return $data['Id'];
		}
		else
		{
			return 0;
		}	
	}

	// ------------------- Pricing Plans API Requests --------------------- //
	// For Key / Values See: http://cms.skyledger.com/api-v1/pricing-plans
	// ------------------------------------------------------------------- //

	//
	// Get all pricing plans
	//
	function get_pricing_plans()
	{
		$this->_requesturl = $this->_apihost . "/pricingplans/" . $this->_urlextra;
		return $this->_request();
	} 

	//
	// Get a pricing plan by id.
	//
	function get_pricing_plan_by_id($id)
	{
		$this->_requesturl = $this->_apihost . "/pricingplans/id/$id";
		if($data = $this->_request())
		{
			return $data;
		}
		else
		{
			return 0;
		}	
	}

	//
	// Add A Pricing Plan.
	//
	function add_pricing_plan()
	{
		$this->_requesturl = $this->_apihost . '/pricingplans/action/add';
		if($data = $this->_request())
		{
			return $data['Id'];
		}
		else
		{
			return 0;
		}		
	} 

	//
	// Delete a Pricing Plan entry by id.
	//
	function delete_pricing_plan_by_id($id)
	{
		$this->_requesturl = $this->_apihost . "/pricingplans/action/delete/id/$id";
		if($data = $this->_request())
		{
			return $data;
		}
		else
		{
			return 0;
		}
	}

	//
	// Update Pricing Plan by id.
	//
	function update_pricing_plan_by_id($id)
	{	
		$this->_requesturl = $this->_apihost . "/pricingplans/action/update/id/$id";
		if($data = $this->_request())
		{
			return $data['Id'];
		}
		else
		{
			return 0;
		}	
	}

	// ------------------- Labels API Requests --------------------------- //
	// For Key / Values See: http://cms.skyledger.com/api-v1/labels
	// ------------------------------------------------------------------- //

	//
	// Get Labels.
	//
	function get_labels()
	{
		$this->_requesturl = $this->_apihost . "/labels/" . $this->_urlextra;
		return $this->_request();
	} 
	
	//
	// Get a label by id.
	//
	function get_label_by_id($id)
	{
		$this->_requesturl = $this->_apihost . "/labels/id/$id";
		if($data = $this->_request())
		{
			return $data;
		}
		else
		{
			return 0;
		}	
	}
	
	//
	// Add Label.
	//
	function add_label($label)
	{
		if(! empty($label))
		{
			$this->set_data('LabelsName', $label);
			$this->_requesturl = $this->_apihost . "/labels/action/add/" . $this->_urlextra;
			
			if($data = $this->_request())
			{
				return $data['Id'];
			}
			else
			{
				return 0;
			}
		}

		$this->_error[] = array('error' => 'Label cannot be empty.', 'field' => 'Label');
		return 0;	
	} 
	
	//
	// Update a Label name by id.
	//
	function update_label_by_id($id, $name)
	{
		// Check to see if we have an id. Fail if not set.
		if(empty($name))
		{
			$this->_error[] = array('error' => 'Label name cannot be empty', 'field' => 'LabelsName');
			return 0;
		}
		
		$this->set_data('LabelsName', $name);
	
		$this->_requesturl = $this->_apihost . "/labels/action/update/id/$id";
		if($data = $this->_request())
		{
			return $data['Id'];
		}
		else
		{
			return 0;
		}	
	}
	
	//
	// Delete a Label entry by id.
	//
	function delete_label_by_id($id)
	{
		$this->_requesturl = $this->_apihost . "/labels/action/delete/id/$id";
		if($data = $this->_request())
		{
			return $data;
		}
		else
		{
			return 0;
		}
	}

	// ------------------- Categories API Requests ----------------------- //
	// For Key / Values See: http://cms.skyledger.com/api-v1/categories
	// ------------------------------------------------------------------- //

	//
	// Get categories.
	//
	function get_categories()
	{
		$this->_requesturl = $this->_apihost . "/categories/" . $this->_urlextra;
		return $this->_request();
	} 
	
	//
	// Add Category.
	//
	// Args: Category name, Type {income, expense}
	//
	function add_category($category, $type)
	{
		if((strtolower($type) != 'income') && (strtolower($type) != 'expense'))
		{
			$this->_error[] = array('error' => 'Category type must be income or expense.', 'field' => 'Type');
			return 0;		
		}
	
		if(! empty($category))
		{
			// Setup Category Type: Income or Expense
			if(strtolower($type) == 'expense')
			{
				$this->set_data('CategoriesType', '1');				
			} else if(strtolower($type) == 'income')
			{
				$this->set_data('CategoriesType', '2');				
			}
		
			$this->set_data('CategoriesName', $category);
			$this->_requesturl = $this->_apihost . "/categories/action/add/" . $this->_urlextra;
			
			if($data = $this->_request())
			{
				return $data['Id'];
			}
			else
			{
				return 0;
			}
		}

		$this->_error[] = array('error' => 'Category cannot be empty.', 'field' => 'Label');
		return 0;	
	}
	
	//
	// Update a Category name by id.
	//
	function update_category_by_id($id, $name)
	{
		// Check to see if we have an id. Fail if not set.
		if(empty($name))
		{
			$this->_error[] = array('error' => 'Category name cannot be empty', 'field' => 'CategoriesName');
			return 0;
		}
		
		$this->set_data('CategoriesName', $name);
	
		$this->_requesturl = $this->_apihost . "/categories/action/update/id/$id";
		if($data = $this->_request())
		{
			return $data['Id'];
		}
		else
		{
			return 0;
		}	
	}
	
	//
	// Delete a Category entry by id. Will not delete if category is in use by more than one ledger entry.
	//
	function delete_category_by_id($id)
	{
		$this->_requesturl = $this->_apihost . "/categories/action/delete/id/$id";
		if($data = $this->_request())
		{
			return $data;
		}
		else
		{
			return 0;
		}
	}

	
	// ----------------------- Ledger API Requests ----------------------- //
	// For Key / Values See: http://cms.skyledger.com/api-v1/ledgers
	// ------------------------------------------------------------------- //

	//
	// Get ledger entries.
	//
	function get_ledger()
	{
		$this->_requesturl = $this->_apihost . "/ledger/" . $this->_urlextra;
		return $this->_request();
	} 

	//
	// Add a Ledger entry.
	//
	function add_ledger()
	{
		$this->_requesturl = $this->_apihost . '/ledger/action/add';
		if($data = $this->_request())
		{
			return $data['Id'];
		}
		else
		{
			return 0;
		}	
	}
	
	//
	// Update a Ledger entry.
	//
	function update_ledger_by_id($id)
	{	
		$this->_requesturl = $this->_apihost . "/ledger/action/update/id/$id";
		if($data = $this->_request())
		{
			return $data['Id'];
		}
		else
		{
			return 0;
		}	
	}
	
	//
	// Delete a Ledger entry by id.
	//
	function delete_ledger_by_id($id)
	{
		$this->_requesturl = $this->_apihost . "/ledger/action/delete/id/$id";
		if($data = $this->_request())
		{
			return $data;
		}
		else
		{
			return 0;
		}
	}
	
	//
	// Get a ledger entry by id.
	//
	function get_ledger_by_id($id)
	{
		$this->_requesturl = $this->_apihost . "/ledger/id/$id";
		if($data = $this->_request())
		{
			return $data;
		}
		else
		{
			return 0;
		}	
	}

	// ----------------- Private Functions --------------- //
	
	//
	// Make request to Skyclerk
	//
	private function _request()
	{
		$this->_error = array();

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->_requesturl);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_postdata);
		curl_setopt($ch, CURLOPT_USERPWD, "X:$this->_apikey");
		
		$this->_rawresponse = curl_exec($ch);
		$this->_response = json_decode($this->_rawresponse, TRUE);
		$this->_postdata = array();
		$this->_urlextra = '';
		curl_close($ch);

		// Check for any errors.
		if(isset($this->_response['status']))
		{
			if($this->_response['status'] == 0)
			{
				$this->_error = $this->_response['errors'];
				return 0;				
			}
			else
			{
				return $this->_response; 
			}
		}
		else
		{
			$this->_error[] = array('error' => 'Request failed', 'field' => 'N/A');
			return 0;
		}
	}
}
?>