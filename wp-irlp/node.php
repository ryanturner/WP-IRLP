<?php

class Node
{
	public $number, $callsign, $city, $province, $country, $owner, $latitude, $longitude, $base, $offset, $tone, $avrs, $status, $last;
	private $url;
	
	public function __construct($_number)
	{
		$this->number = $_number;
		$this->getNodeDetails();
	}
	
	private function getNodeDetails()
	{
		libxml_use_internal_errors(true);
		$html = file_get_contents('http://status.irlp.net/index.php?PSTART=11&nodeid=' . (int) $this->number);
		$dom = new DOMDocument;
		$dom->loadHTML($html);
		$xpath = new DOMXPath($dom);
		$attributes = array(
			//Our name     text in the td beside the value
			'callsign' => 'Node Callsign',
			'city' => 'Node City',
			'province' => 'Node Province/State',
			'country' => 'Node Country',
			'owner' => 'Node Owner/Sponsor',
			'latitude' => 'Node Latitude',
			'longitude' => 'Node Longitude',
			'base' => 'Node Base Frequency (MHz)',
			'offset' => 'Node Offset Frequency (KHz)',
			'tone' => 'Node CTCSS (Hz)/DCS',
			'avrs' => 'AVRS Status',
			'url' => 'Node Website URL',
			'status' => 'Current Node Status',
			'last' => 'Last heard from Node'
		);
		foreach($attributes as $var => $selector)
		{
			$query = $xpath->query("//tr[td//text()[contains(., '" . $selector . "')]]/td[2]");
			foreach($query as $row) {
				if($var == 'latitude' || $var == 'longitude') //Special handling to get just the coordinate
				{
					$arr = explode(' ', trim($row->nodeValue)); //Split by the space
					$this->{$var} = $arr[0]; //Only get the number
					if($arr[1] == "South" || $arr[1] == "West") {
						$this->{$var} = $this->{$var} * -1; //Add a negative
					}
				}
				else 
				{
					$this->{$var} = $row->nodeValue; //This should always just end up being 1 row since we used text in xpath, but in case it's empty or 0, lets put it in foreach
				}
			}
		}
	}
}
?>