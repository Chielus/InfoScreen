<?php

class Settings {
	
	private $infoscreenid;
	private $settings;
	
	function __construct($infoscreenid) {
		$this->infoscreenid = $infoscreenid;
		
		$db = new Db();
		$this->settings = $db->getSettings($infoscreenid);
	}
	
	// return array of keys
	public function getKeys() {
		return array_keys($this->settings);
	}
	
	// return value or throw exception
	public function getValue($key) {
		if(in_array($key, $this->getKeys())) {
			return $this->settings[$key];
		} else {
			throw new Exception("This key is not yet available in settings.");
		}
	}
	
	// add key => value pair to settings
	public function setValue($key, $value) {
		$db = new Db();
		if($db->insertOrUpdateSetting($this->infoscreenid, $key, $value)) {
			$this->settings = array_merge(array($key => $value));
		}
	}
	
	// delete key => value pair or throw exception is key is missing
	public function deleteValue($key) {
		if(in_array($key, $this->getKeys())) {
			$db = new  Db();
			if($db->deleteSetting($this->infoscreenid, $key)) {
				unset($this->settings[$key]);
			}
		} else {
			throw new Exception("Key is not set.");
		}
	}
	
}

?>