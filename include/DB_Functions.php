<?php

class DB_Functions {

    private $db;

    //put your code here
    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $this->db = new DB_Connect();
        $this->db->connect();
    }

    // destructor
    function __destruct() {
        
    }

    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $password) {
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
        $result = mysql_query("INSERT INTO users(name, encrypted_password, salt, created_at) VALUES('$name', '$encrypted_password', '$salt', NOW())");
        // check for successful store
        if ($result) {
            // get user details 
            $uid = mysql_insert_id(); // last inserted id
            $result = mysql_query("SELECT * FROM users WHERE uid = $uid");
            // return user details
            return mysql_fetch_array($result);
        } else {
            return false;
        }
    }

    /**
     * Get user by name and password
     */
    public function getUserByNameAndPassword($name, $password) {
        $result = mysql_query("SELECT * FROM users WHERE name = '$name'") or die(mysql_error());
        // check for result 
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysql_fetch_array($result);
            $salt = $result['salt'];
            $encrypted_password = $result['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $result;
            }
        } else {
            // user not found
            return false;
        }
    }

    //When logged in get session data of user.
    public function getUserSessionDataFromId($uid)
    {
    	$result = mysql_query("SELECT * FROM session WHERE uid = '$uid'");
		
		$no_of_rows = mysql_num_rows($result);
		if($no_of_rows > 0)
		{
//			$result = mysql_fetch_array($result);
			
			$arr = array();
			$tel = 0;
			$arr[$tel]="hallo";
			$tel++;
			while ($line = mysql_fetch_array($result))
			{
				
				$arr[$tel]=$line;
				$tel++;
			}
			print $arr[0];
			print $arr[1];
				print $arr[2];
				print $arr[3];
			return json_encode($arr);//$result;
		}
		else{
			//no session data of user
			return false;
		}
    }
    /**
     * Check user is existed or not
     */
    public function isUserExisted($name) {
        $result = mysql_query("SELECT name from users WHERE name = '$name'");
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            // user existed 
            return true;
        } else {
            // user not existed
            return false;
        }
    }
	
	public function deleteUser($uid){
		$result = mysql_query("DELETE FROM users WHERE uid = '$uid'");
		return true;
	}
	
	public function updateUser($name, $password, $uid){
		
		$hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
		$result = mysql_query("UPDATE users SET name = '$name', encrypted_password = '$encrypted_password', salt='$salt' WHERE uid = '$uid'");
		return true;
	}

    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }

}

?>
