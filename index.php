<?php$host="127.7.236.130"; //replace with database hostname$username="adminXRSMmKy"; //replace with database username$password="qTT65X9F3gSH"; //replace with database password$db_name="php"; //replace with database nameecho("Brecht is awesome! \r\n");$con=mysql_connect("$host", "$username", "$password")or die("cannot connect");mysql_select_db("$db_name")or die("cannot select DB");$sql = "select * from Users";$result = mysql_query($sql);$json = array();if(mysql_num_rows($result)){while($row=mysql_fetch_assoc($result)){$json['emp_info'][]=$row;}}mysql_close($con);echo json_encode($json);?>