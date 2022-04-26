<?php
//redis connection

$redis = new Redis();
$redis->connect("127.0.0.1",6379);


if(!$redis->exists("petds"))
{

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pet";
	$conn = new mysqli($servername,$username,$password,$dbname);
	if($conn->connect_error)
	{
		die("Connection failed");
	}
	$name = $_POST["n"];
	$sql = "select * from pet where name like '%$name%'";
	$result = $conn->query($sql);
	if($result->num_rows>0)
	{
		$pets = array();
		while($row=$result->fetch_assoc())
		{
			$pets[] = $row;
		}
		$redis->set("petds", serialize($pets));
		$source = "MYSQL - с уншив";
		$redis->expire("petds", 200);
	}
}
else
{
	$source = "REDIS - с уншив";
	$pets = unserialize($redis->get("petds"));
}


?>