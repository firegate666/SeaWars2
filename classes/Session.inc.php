<?
  class Session extends AbstractClass {
    var $sid;

    function Session($userid) {
      $this->data["uid"]=$userid;
      $this->data["client_ip"]=getClientIP();
      $this->data["date"]=date("Y-m-d");
      $this->data["time"]=date("H:m:s");
      // in Datenbank speichern und ID übergeben
      $this->sid = $this->store();
    }

    function isRegistered() {
      return ( ($this->id!=NULL) && ($this->username!=NULL) );
    }
  }
?>