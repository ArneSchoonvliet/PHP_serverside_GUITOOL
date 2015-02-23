<?php
$host="XXXXX"; //replace with database hostname 
$username="XXXXX"; //replace with database username 
$password="XXXXX"; //replace with database password 
$db_name="XXXXXX"; //replace with database name
 
$con=mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");
$sql = "select * from emp_info"; 
$result = mysql_query($sql);
$json = array();
 
if(mysql_num_rows($result)){
while($row=mysql_fetch_assoc($result)){
$json['emp_info'][]=$row;
}
}
mysql_close($con);
echo json_encode($json); 
?> 

<html>
<head>
</head>

<body>
<h1>Brecht's awesome</h1>
</body>
</html>
