<?
/**
 * This class ist for counting page statistics
 */
 
class PageStatistic extends AbstractNoNavigationClass {

	/**
	 * all fields used in class
	 */
	public function getFields() {
		$fields[] = array('name' => 'template', 'type' => 'integer', 'notnull' => true);
		$fields[] = array('name' => 'timestamp', 'type' => 'timestamp', 'notnull' => false);
		$fields[] = array('name' => 'user', 'type' => 'integer', 'notnull' => true);
		return $fields;
	}	

}
?>