<?php

class Infoscreen {
	
	// configuration values which are hardcoded for each infoscreen:
	// infoscreenid, customerid, infotext and message of the day,
	// all other configuration values are specified in a dynamic 'array' of settings,
	// all stations are specified in a dynamic 'array'
	
	private $infoscreenid;
	private $customerid;
	private $title;
	private $motd;
	
	private $settings;
	private $stations;
	
	function __construct($infoscreenid) {
		$this->infoscreenid = $infoscreenid;
		
		$db = new Db();
		$infoscreen = $db->getInfoscreen($this->infoscreenid);
		$this->customerid = $infoscreen['customerid'];
		$this->title = $infoscreen['title'];
		$this->motd = $infoscreen['motd'];
		
		$this->settings = new Settings($this->infoscreenid);
		$this->stations = new Stations($this->infoscreenid);
	}
	
	public function getCustomerId() {
		return $this->customerid;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setTitle($title) {
		$db = new Db();
		if($db->setInfoscreenTitle($this->infoscreenid, $title)) {
			$this->title = $title;
		}
	}
	
	public function getMotd() {
		return $this->motd;
	}
	
	public function setMotd($motd) {
		$db = new Db();
		if($db->setInfoscreenMotd($this->infoscreenid, $motd)) {
			$this->motd = $motd;
		}
	}
	
	// STATIONS
	
	public function getStationIds() {
		return $this->stations->getStationIds();
	}
	
	public function addStation($stationid) {
		$this->stations->addStation($stationid);
	}
	
	public function removeStation($stationid) {
		$this->stations->removeStation($stationid);
	}
	
	public function removeAllStations() {
		foreach($this->getStationIds() as $stationid) {
			$this->removeStation($stationid);
		}
	}
	
	// SETTINGS
	
	public function getSettingValue($key) {
		try {
			return $this->settings->getValue($key);
		} catch (Exception $exc) {
			return null;
		}		
	}
	
	public function setSettingValue($key, $value) {
		$this->settings->setValue($key, $value);
	}
	
	public function deleteSettingValue($key) {
		$this->settings->deleteValue($key);
	}
	
}

?>