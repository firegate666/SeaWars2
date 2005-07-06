<?
  class Session extends AbstractClass {
    var $sid;

    function getCookie($name) {
      return $_SESSION[$name];
      //return $_COOKIE[$name];
    }
    
    function unsetCookie($name) {
    	unset($_SESSION[$name]);
    }
    
    function setCookie($name, $value) {
		$_SESSION[$name] = $value;
      //setcookie($name, $value, NULL);
    }
    function removeCookie($name){
    	$_SESSION[$name] = '';
      	//setcookie($name,"",0);
    }
    function cleanUpCookies(){
    	$_SESSION = array();
        //$_COOKIES = array();
    }


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