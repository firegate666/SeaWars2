<?
class Rohstoffproduktion {
    function Rohstoffproduktion($insel_id){
                                          	global $mysql;
      $array = $mysql->select("SELECT rp.wachstum_prozent, rp.produktion_stunde, r.sem_id, r.name
                               FROM Rohstoffproduktion rp, Rohstoff r
                               WHERE rp.insel_id=".$insel_id." AND rp.rohstoff_id=r.id;");
      foreach($array as $item) {
          $this->data[] = array('name' => $item[3], 'id' => $item[2], 'wp' => $item[0], 'ps' => $item[1]);
      }
    }
}

class Insel extends AbstractTimestampClass {
      var $rohstoffproduktion;
      var $lager;

      function update() {
      	global $mysql;
        $query = "SELECT l.rohstoff_id, l.anzahl, rp.produktion_stunde, rp.insel_id, NOW() as now, l.lager_id
                  FROM rohstoff r, lagerenthaelt l, rohstoffproduktion rp, insel i
                  WHERE l.rohstoff_id = r.id AND rp.rohstoff_id=r.id AND rp.insel_id=".$this->id.";";
        $lastupdate = $this->data['timestamp'];
        $array = $mysql->select($query);
        foreach($array as $item) {
            $rohstoff_id = $item[0];
            $anzahl      = $item[1];
            $pps         = $item[2];
            $insel_id    = $item[3];
            $lager_id    = $item[5];
            $diff_sec    = strtotime($item[4])-strtotime($lastupdate);
            $wachstum    = ($pps / 3600) * $diff_sec;
            $neueAnzahl  = $anzahl + $wachstum;
            $query       = "UPDATE lagerenthaelt SET anzahl=$neueAnzahl WHERE rohstoff_id=$rohstoff_id AND lager_id=$lager_id;";
            $rows        = $mysql->update($query);
        }
        $this->store();
      }

      function Insel($id='') {
          AbstractTimestampClass::AbstractTimestampClass($id);
          $this->update();
          $this->rohstoffproduktion = new Rohstoffproduktion($this->id);
          $this->lager              = new Lager($this->data['lager_id']);
      }

	function acl($method) {
		if($method=='show') return (Login::isLoggedIn()) && ($this->data['spieler_id'] == Session::getCookie('spieler_id'));
		else return false;
	}

	function show(&$vars){
		$array['insel_name'] = $this->data['name'];
		foreach($this->rohstoffproduktion->data as $res) {
                    $array[$res['id']] = $this->lager->lagerenthaelt[$res['id']];
                    $array[$res['id'].'_wachstum'] = intval(($res['ps']));
		}
		return $this->getLayout($array, "page");
	}
	function setname($newname) {
	}
}
?>
