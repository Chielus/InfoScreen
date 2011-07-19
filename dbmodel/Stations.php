<?php
    
class Stations {
	
	private $infoscreenid;
	private $stationids;
	
	function __construct($infoscreenid) {
		$this->infoscreenid = $infoscreenid;
		
		$db = new Db();
		$this->stationids = $db->getStationIds($infoscreenid);
	}
	
	// return array of stationids
	// format: array(type = (..., ..., ...), type = (..., ..., ...), ...)
	public function getStationIds() {
		return $this->stationids;
	}	

	// add station or throw exception
	public function addStation($stationid) {
		if(!in_array($stationid, $this->stationids)) {
			$db = new Db();
			if($db->insertStation($this->infoscreenid, $stationid)) {
				array_push($this->stationids, $stationid);
			}
		} else {
			throw new Exception("This station has already been set.");
		}
	}
	
	// remove station or throw exception
	public function removeStation($stationid) {
		if(in_array($stationid, $this->stationids)) {
			$db = new Db();
			if($db->deleteStation($this->infoscreenid, $stationid)) {
				$this->stationsids = array_diff($this->stationids, array($stationid));
				$this->stationsids = array_values($this->stationids);
			}
		} else {
			throw new Exception("This station hasn't been set yet.");
		}
	}
	
}
    
?>