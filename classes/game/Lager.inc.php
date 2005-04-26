<?
  class Lager extends AbstractClass {
      var $lagerenthaelt;


      function Lager($id='') {
      	if(empty($id)) return;
          AbstractClass::AbstractClass($id);
          $this->loadres();
      }

      function loadres(){
               global $mysql;
               $array = $mysql->select("SELECT r.sem_id, l.anzahl
                                        FROM lagerenthaelt l, rohstoff r
                                        WHERE r.id = l.rohstoff_id AND l.lager_id=".$this->id.";");
               foreach($array as $item) {
                  $res[$item[0]] = $item[1];
               }
               $this->lagerenthaelt = $res;
      }
  }
?>