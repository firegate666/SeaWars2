<?php
class TSP {

	var $ids; // array of keys
	var $costs; // array of costs

	function TSP($ids){
		foreach($ids as $id) {
			$this->ids[$id] = $id;
		}
	}
	
	/**
	 * normal calculation
	 * 
	 * @return	int[]	return best route retaining start node
	 */
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

	/**
	 * complex problem solution, try every node as start node
	 * 
	 * @return	int[]	best result
	 */
	function calculatecomplex(&$costs) {
		$this->costs = $costs;
		$bag1 = $this->ids;
		reset($bag1);
		$anzahl_knoten = count($bag1);
		$count = 0;
		foreach($bag1 as $id) {
			$bigbag[0][$count++] = $id;
		}

		$permut = 0;
		for($permut = 0; $permut < $anzahl_knoten; $permut++) {
 			for($i = 0; $i < $anzahl_knoten-1; $i++) {
				$bigbag[$permut+1][$i] = $bigbag[$permut][$i+1];	
			}
			$bigbag[$permut+1][$anzahl_knoten-1] = $bigbag[$permut][0];
		}

		// reorder
		$count = 0;
		$bigbag2 = array();
		foreach($bigbag as $bag) {
			foreach($bag as $id) {
				$bigbag2[$count][$id] = $id;
			}
			$count++;
		}
		
		$totalresults = array();
		$bestcost = 10000000000;		
		foreach($bigbag2 as $bag) {
			$node = pos($bag);
			$result = array();
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
			if($totalcosts < $bestcost) {
				$bestcost = $totalcosts;
				$totalresults = $result;
			}
		}
		$costs = $bestcost;
		return $totalresults;
	}
}
?>
