<?
	$template_classes[] = 'techtree';
	
/**
 * Main Tech-Tree class
 * Tech know which TTEntries a user knows and
 * what he can learn next
 */
class TechTree extends AbstractClass {
	
	/**
	 * Array of TTEntries a user knows or can learn
	 */
	var $ttentries = array();
	
	function TechTree($spieler_id) {
		// get all information
	}
	
	/**
	 * as the tech-tree himself has no table
	 * there have to be work arounds for load and save.
	 * MySQL does not support views I'm afraid
	 */
	function load() {
	}
	
	/**
	 * as the tech-tree himself has no table
	 * there have to be work arounds for load and save.
	 * MySQL does not support views I'm afraid
	 */
	function save() {
	}
}

/**
 * Categories for techs, no functionality, only gui use
 */
class TTCategory extends AbstractClass {
}

/**
 * the tech himself
 */
class TTEntry extends AbstractClass {
}

/**
 * tech type of tech
 */
class TTType extends AbstractClass {
}

/**
 * the fields for every tech depending on type
 */
class TTFields extends AbstractClass {
}

/**
 * who knows what
 */
class TTUserEntry extends AbstractClass {
}
?>