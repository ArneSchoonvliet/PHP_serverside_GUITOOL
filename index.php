<?php

/**
 * File to handle all API requests
 * Accepts GET and POST
 * 
 * Each request will be identified by TAG
 * Response will be JSON data

  /**
 * check for POST request 
 */
if (isset($_POST['tag']) && $_POST['tag'] != '') {
    // get tag
    $tag = $_POST['tag'];
	

    // include db handler
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();

    // response Array
    $response = array("tag" => $tag, "success" => 0, "error" => 0);

    // check for tag type
    if ($tag == 'login') {
        // Request type is check Login
        $name = $_POST['name'];
        $password = $_POST['password'];

        // check for user
        $user = $db->getUserByNameAndPassword($name, $password);
        if ($user != false) {
            // user found
            // echo json with success = 1
            $response["success"] = 1;
            $response["uid"] = $user["uid"];
            $response["user"]["name"] = $user["name"];
            $response["user"]["created_at"] = $user["created_at"];
            $response["user"]["updated_at"] = $user["updated_at"];
			
			print $db->getUserSessionDataFromId($user["uid"]);
			/*$session = $db->getUserSessionDataFromId($user["uid"]);
			
			if($session != false){
				$response["session"]["sid"] = $session["sid"];
				$response["session"]["place"] = $session["place"];
				$response["session"]["description"] = $session["description"];
				$response["session"]["datum"] = $session["datum"];
				$response["session"]["altitude"] = $session["altitude"];
				$response["session"]["duration"] = $session["duration"];
				
			}
            echo json_encode($response);
        } else {
            // user not found
            // echo json with error = 1
            $response["error"] = 1;
            $response["error_msg"] = "Incorrect name or password!";
            echo json_encode($response);
        }*/
    } else if ($tag == 'register') {
        // Request type is Register new user
        $name = $_POST['name'];
        $password = $_POST['password'];

        // check if user is already existed
        if ($db->isUserExisted($name)) {
            // user is already existed - error response
            $response["error"] = 2;
            $response["error_msg"] = "User already existed";
            echo json_encode($response);
        } else {
            // store user
            $user = $db->storeUser($name, $password);
            if ($user) {
                // user stored successfully
                $response["success"] = 1;
                $response["uid"] = $user["unique_id"];
                $response["user"]["name"] = $user["name"];
                $response["user"]["created_at"] = $user["created_at"];
                $response["user"]["updated_at"] = $user["updated_at"];
                echo json_encode($response);
            } else {
                // user failed to store
                $response["error"] = 1;
                $response["error_msg"] = "Error occured in Registartion";
                echo json_encode($response);
            }
        }
    }
    else if($tag == 'delete'){
    	$uid= $_POST['uid'];
    	$user = $db->deleteUser($uid);
		echo "I'm gone forever";
    }
    else if($tag == 'update'){
    	$uid= $_POST['uid'];
		$name= $_POST['name'];
		$password= $_POST['password'];
		
		$user = $db->updateUser($name, $password, $uid);
		echo "updated me :D";
	}
	
    else {
        echo "Invalid Request";
    }
} else {
    echo "Access Denied";
}
?>

<html>
	<head>
		
	</head>
	<body>
		<form action="" method="POST">
			<input type='text' name="tag"/>
			<input type="text" name="uid" />
			<input type="text" name="name" />
			<input type="text" name="password" />
			<input type='submit' value="Submit" />
		</form>
	</body>
</html>

