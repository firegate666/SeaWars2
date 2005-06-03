<?php
class TSP {

	var $ids; // array of keys
	var $costs; // array of costs

	function TSP($ids){
		foreach($ids as $id) {
			$this->ids[$id] = $id;
		}
	}
	
	function calculate(&$costs) {
		$this->costs = $costs;
		$bag = $this->ids;
		reset($bag);
		$node = pos($bag);
		$result[] = $node; // den start da rein 
		unset($bag[$node]); // startknoten rauswerfen
		$totalcosts = 0;
		while(!empty($bag)) {
			$distance = null;
			$nextnode = null;
			foreach($bag as $item) { // die Verbindung zu jedem prüfen
				$tempcost = $costs[$node][$item];
				if($distance == null) { // first round
					$distance = $tempcost;
					$nextnode = $item;
				} else {
					if($tempcost < $distance ) {// better way found
						$distance = $tempcost;
						$nextnode = $item;
					}
				}
			} // foreach
			$node = $nextnode;
			unset($bag[$nextnode]); // aktuellen Knoten wegwerfen
			$totalcosts += $distance;
			$result[] = $node; 
		}
		$costs = $totalcosts;
		return $result;
	}
}
?>
