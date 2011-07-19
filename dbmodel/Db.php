<?php

class Db {
	
	// following methods should NOT be called by anyone
	// the database gets adjusted automatically by using the class model of the database structure
	// WARNING: it's advised to have only one instance of each infoscreen
	// active at all times due to synchronisation 
	
	private $dbconn;
	
	// construct database connection
	function __construct() {
		$dbhost = 'localhost';
		$dbuser = 'root';
		$dbpass = '702672rs';
		$db = 'flatturtle';		
		
 		$this->dbconn = new mysqli($dbhost, $dbuser, $dbpass, $db);
	}
	
	/*
	 * CUSTOMERS
	 */	
	 
	 // return customerid or -1 if the credentials are false
	 public function getCustomerId($username, $password) {
	 	$customerid = -1;
		
		if($stmt = $this->dbconn->prepare("SELECT id FROM customers WHERE username = ? AND password = ?")) {
			$stmt->bind_param('ss', $username, $password);
			$stmt->execute();
			$stmt->bind_result($id);
			while($stmt->fetch()) {
				$customerid = $id;
			}
		}
		
		return $customerid;
	 }
	 
	 // return array of infoscreenids for one customer
	 public function getInfoscreenIds($customerid) {
	 	$infoscreenids = array();
		
		if($stmt = $this->dbconn->prepare("SELECT id FROM infoscreens WHERE customerid = ?")) {
			$stmt->bind_param('i', $customerid);
			$stmt->execute();
			$stmt->bind_result($id);
			while($stmt->fetch()) {
				array_push($infoscreenids, $id);
			}
		}
		
		return $infoscreenids;
	 }
	 
	 /*
	 * INFOSCREENS
	 */	
	 
	 // return associative array with details infoscreen
	 public function getInfoscreen($infoscreenid) {
	 	$infoscreen = array();
		
		if($stmt = $this->dbconn->prepare("SELECT customerid, title, motd FROM infoscreens WHERE id = ?")) {
			$stmt->bind_param('i', $infoscreenid);
			$stmt->execute();
			$stmt->bind_result($customerid, $title, $motd);
			while($stmt->fetch()) {
				$infoscreen = array_merge($infoscreen, array('customerid' => $customerid, 'title' => $title, 'motd' => $motd));;
			}
			$stmt->close();
		}

		return $infoscreen;
	 }
	 
	 // set title for infoscreen
	 public function setInfoscreenTitle($infoscreenid, $title) {
	 	if($stmt = $this->dbconn->prepare("UPDATE infoscreens SET title = ? WHERE id = ?")) {
			$stmt->bind_param('si', $title, $infoscreenid);
			$stmt->execute();
			$stmt->close();
		
			return true;	
		}
		
		return false;
	 }
	 
	  // set motd for infoscreen
	 public function setInfoscreenMotd($infoscreenid, $motd) {
	 	if($stmt = $this->dbconn->prepare("UPDATE infoscreens SET motd = ? WHERE id = ?")) {
			$stmt->bind_param('si', $motd, $infoscreenid);
			$stmt->execute();
			$stmt->close();
		
			return true;	
		}
		
		return false;
	 }
	 
	 
	
	/*
	 * STATIONS
	 */	
	
	// return array of stationids for specific infoscreen
	// format: array(type = (..., ..., ...), type = (..., ..., ...), ...)
	public function getStationIds($infoscreenid) {
		$stationids = array();
		$stationids['NMBS'] = array();
		$stationids['MIVB'] = array();
		$stationids['DeLijn'] = array();
		
		if($stmt = $this->dbconn->prepare("SELECT stationid, type FROM stations WHERE infoscreenid = ?")) {
			$stmt->bind_param('i', $infoscreenid);
			$stmt->execute();
			$stmt->bind_result($stationid, $type);
			while($stmt->fetch()) {
				array_push($stationids[$type], $stationid);
			}
			$stmt->close();
		}

		return $stationids;
	}
	
	// insert station and return true or false if not successful
	public function insertStation($infoscreenid, $stationid) {
		if(strrpos($stationid, 'BE.NMBS') !== false) {
			$type = 'NMBS';
		} else if (strrpos($stationid, 'BE.MIVB') !== false) {
			$type = 'MIVB';
		}

		if($stmt = $this->dbconn->prepare("INSERT INTO stations VALUES(?, ?, ?)")) {
			$stmt->bind_param('iss', $infoscreenid, $stationid, $type);
			$stmt->execute();
			$stmt->close();
		
			return true;	
		}
		
		return false;
	}
	
	// delete station and return true or false if not successful
	public function deleteStation($infoscreenid, $stationid) {
		if($stmt = $this->dbconn->prepare("DELETE FROM stations WHERE infoscreenid = ? AND stationid = ?")) {
			$stmt->bind_param('is', $infoscreenid, $stationid);
			$stmt->execute();
			$stmt->close();
			
			return true;
		}

		return false;
	}
	
	
	/*
	 * SETTINGS
	 */
	
	// get setting and return associative array of settings: $key => $value
	public function getSettings($infoscreenid) {
		$settings = array();
		
		$stmt = $this->dbconn->stmt_init();
		if($stmt->prepare("SELECT * FROM settings WHERE infoscreenid = ?")) {
			$stmt->bind_param('i', $infoscreenid);
			$stmt->execute();
    		$stmt->bind_result($infoscreenid, $key, $value);			
    		while($stmt->fetch()) {
    			$settings = array_merge($settings, array($key => $value));
			}
			$stmt->close();
		}
		
		return $settings;
	}
	
	// insert or update setting and return true or false if not successful
	public function insertOrUpdateSetting($infoscreenid, $key, $value) {
		if($stmt = $this->dbconn->prepare("REPLACE INTO settings VALUES(?, ?, ?)")) {
			$stmt->bind_param('iss', $infoscreenid, $key, $value);
			$stmt->execute();
			$stmt->close();
			
			return true;
		}

		return false;
	}
	
	// delete setting and return true or false if not successful
	public function deleteSetting($infoscreenid, $key) {
		if($stmt = $this->dbconn->prepare("DELETE FROM settings WHERE infoscreenid = ? AND `key` = ?")) {
			$stmt->bind_param('is', $infoscreenid, $key);
			$stmt->execute();
			$stmt->close();
			
			return true;
		}		
		
		return false;
	}
	
	// make sure database connection is being closed 
	function __destruct() {
		$this->dbconn->close();
	}
	
	
}

?>