<?php
include('password.php');

class User extends Password{

    private $_db;

    function __construct($db){
    	parent::__construct();

    	$this->_db = $db;
    }

	private function get_user_hash($username){

		try {
			$stmt = $this->_db->prepare('SELECT password, username, memberID, UID, shelfTheme, active FROM users WHERE username = :username');
			$stmt->execute(array('username' => $username));

			return $stmt->fetch();

		} catch(PDOException $e) {
		    echo '<div class="errorMsg">'.$e->getMessage().'</div>';
		}
	}

	public function login($username,$password,$keepalive){

		$row = $this->get_user_hash($username);

		if($this->password_verify($password,$row['password']) == 1){
      if($row['active'] == 'Yes' && $row['UID'] != "") {
        if($keepalive == 'on') { $expiry = strtotime("+5 years"); } else { $expiry = strtotime("+24 minutes"); }
        setcookie('loggedin', $expiry, $expiry, "/");
        setcookie('id', $row['memberID'], $expiry, "/");
        setcookie('uid', $row['UID'], $expiry, "/");
        setcookie('theme', $row['shelfTheme'], $expiry, "/");
        return true;
      } elseif ($row['UID'] == "") {
        header('Location: /admin/upgrade.php?source=login');
      } elseif ($row['active'] != 'Yes') {
        header('Location: /admin/login.php?action=activation');
      }
		}
	}

  public function classicLogin($username,$password){

		$row = $this->get_user_hash($username);

		if($this->password_verify($password,$row['password']) == 1){
		  return true;
		}
	}

	public function logout(){
    $expiry = strtotime("-1 year");
    setcookie('loggedin', '', $expiry, "/");
    setcookie('id', '', $expiry, "/");
    setcookie('uid', '', $expiry, "/");
    setcookie('theme', '', $expiry, "/");
	}

	public function is_logged_in(){
		if(isset($_COOKIE['loggedin']) && isset($_COOKIE['uid'])){
      try {
  			$stmt = $this->_db->prepare('SELECT memberID, UID FROM users WHERE UID = :uid');
  			$stmt->execute(array('uid' => $_COOKIE['uid']));
  			$row = $stmt->fetch(PDO::FETCH_ASSOC);

  		} catch(PDOException $e) {
  		    echo '<p class="errorMsg">'.$e->getMessage().'</p>';
  		}

      if($_COOKIE['id'] == $row['memberID']) {
        return true;
      } else {
        $this->logout();
        header('Location: /admin/login.php?action=manipulation');
      }
		} else {
      $this->logout();
    }
	}

}


?>
