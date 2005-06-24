<?php
/**
 * This is for solving the Traveling Salesman Problem
 */
class TSP {

	var $ids; // array of keys
	var $costs; // array of costs

	/**
	 * Public constructor
	 * 
	 * @param	int[]	$ids	array of ids from all nodes
	 */
	function TSP($ids){
		foreach($ids as $id) {
			$this->ids[$id] = $id;
		}
	}
	
	/**
	 * Calculate best route for given costs, first node from constructor is
	 * starting node
	 * 
	 * @param	int[][]	&$costs	array of costs array[id1][id2] = distanz, after
	 * calculation, $costs conatins total cost of evaluation
	 * @return	int[]	array of ids sorted by best route
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
}
?>
