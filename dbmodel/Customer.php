<?php

class Customer {
	
	// everything starts whit an object of this class,
	// initialize the customer and get one of his infoscreens,
	// WARNING: it's advised to have only one instance of each infoscreen
	// active at all times due to synchronisation
	
	private $customerid;
	private $username;
	
	function __construct($username, $password) {
		$db = new Db();
		$this->customerid = $db->getCustomerId($username, $password);
		$this->username = $username;
	}
	
	// get customerid
	public function getCustomerId() {
		return $this->customerid;
	}
	
	// get username
	public function getUsername() {
		return $this->username;
	}
	
	// return true or false whether the customer is valid or not
	public function isValid() {
		if($this->customerid == -1) {
			return false;
		} else {
			return true;
		}
	}
	
	// returns array of infoscreensids or throw exception if customer credentials are false
	public function getInfoscreenIds() {		
		if($this->isValid()) {
			$db = new Db();
			return $db->getInfoscreenIds($this->customerid);
		} else {
			throw new Exception("Customer credentials are not valid! Can't return infoscreenids.");
		}	
	}
	
	// return infoscreen or throw exception
	public function getInfoscreen($infoscreenid) {
		if(in_array($infoscreenid, $this->getInfoscreenIds())) {
			return new Infoscreen($infoscreenid);
		} else {
			if($this->isValid()) {
				throw new Exception("The provided infoscreenid isn't property of this customer. Can't return infoscreen.");
			} else {
				throw new Exception("Customer credentials are not valid! Can't return infoscreen.");
			}
		}
	}
	
}

?>