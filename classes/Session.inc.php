<?
  class Session extends AbstractClass {
    protected $sid;

    function Session($userid) {
      $this->data["uid"]=$userid;
      $this->data["client_ip"]="'".getClientIP()."'";
      $this->data["date"]="NOW()";
      $this->data["time"]="NOW()";
      // in Datenbank speichern und ID übergeben
      $this->sid = $this->store();
    }

    function register($username, $password) {
    }

    function isRegistered() {
      return ( ($this->id!=NULL) && ($this->username!=NULL) );
    }
  }
?>

