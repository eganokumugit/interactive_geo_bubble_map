<?php
	if(isset($_POST['iso']))
	{
		include('dbconfig.php');
		$con = mysqli_connect($host,$username,$password,$name) or die("Cannot connect to DB");
		$isoval = $_POST['iso'];
		$query = 'SELECT city_ascii,lat,lng,iso3,population  
			from 2023S_okumueg.worldcities 
			WHERE iso3="' .$isoval . '"  
			AND population > 0 
			ORDER BY population DESC;';
		$result = mysqli_query($con,$query);
		$arr = array();
		while($row = mysqli_fetch_assoc($result))
		{
			$arr[] = $row;
		}
		echo json_encode($arr);
		mysqli_close($con);
		
	}
	else
	{
		   echo "ERROR";
	}
?>

	
