<?php
	include('../config/All.inc.php');
	include('../include/All.inc.php');
 	include('../classes/All.inc.php');
 	
class MapGenerator {
	
	protected $width;
	protected $height;
	
	function saveisland($x, $y){
		$l = new Lager();
		$l->data['kapazitaet'] = 0;
		$l_id = $l->store();

		$i = new Insel();
		$i->data['name']        = "$x/$y";
		$i->data['groesse']     = 50;
		$i->data['x_pos']       = $x;
		$i->data['y_pos']       = $y;
		$i->data['spieler_id']  = 0;
		$i->data['archipel_id'] = 1;
		$i->data['lager_id']    = $l_id;
		$i->store();
	}

	function MapGenerator($size) {
		$this->width  = $size;
		$this->height = $size;
	}	
	
	function create() {
		$map = array(array(), array());
		
		// anzahl ringe ermitteln
		$n_ringe = $this->width / 200;
		$center  = $this->width / 2; 
		$counter = 0;
		for($i=0; $i<$center; $i+=100) {
			$x1 = array($center + $i, $center + 100 + $i);
			$x2 = array($center - $i, $center - 100 - $i);
			$y1 = array($center, $center + 100 * ($counter+1));
			$y2 = array($center, $center - 100 * ($counter+1));
			$number = ($center / 50)-$counter;
			for($j = 0; $j <= $number; $j++) {
				$rx1 = rand($x1[0], $x1[1]);
				$ry1 = rand($y1[0], $y1[1]);
				$rx2 = rand($x1[0], $x1[1]);
				$ry2 = rand($y2[0], $y2[1]);
				$rx3 = rand($x2[0], $x2[1]);
				$ry3 = rand($y1[0], $y1[1]);
				$rx4 = rand($x2[0], $x2[1]);
				$ry4 = rand($y2[0], $y2[1]);
				$map[$rx1][$ry1]=true;
				$map[$rx2][$ry2]=true;
				$map[$rx3][$ry3]=true;
				$map[$rx4][$ry4]=true;
			}
			$counter++;
		}
		header ("Content-type: image/png");
		$im = @imagecreatetruecolor($this->width, $this->height)
		      or die("Cannot Initialize new GD image stream");
		$text_color = imagecolorallocate($im, 233, 14, 91);
		foreach($map as $key => $value) {
			$x = $key;
			foreach($value as $key2 => $value) {
				$y = $key2;
				//$this->saveisland($x, $y);
				imagesetpixel ( $im, $x, $y, $text_color);
			}
		}
		imagepng($im);
		imagedestroy($im);
	}
}
$map = new MapGenerator(1000);
$map->create();
?>