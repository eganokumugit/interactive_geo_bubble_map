<?php

	if(isset($_POST['iso']))
	{
		include('dbconfig.php');
		$con = mysqli_connect($host,$username,$password,$name) or die("Cannot connect to DB");
		$isoval = $_POST['iso'];
		$query = 'SELECT city_ascii,lat,lng  from 2023S_okumueg.worldcities WHERE iso3="' .$isoval . '" and population > 0 ORDER BY city_ascii ASC;';
		$result = mysqli_query($con,$query);
		echo "<option></option>";
		while($row = mysqli_fetch_array($result))
		{
			echo "<option value='" . $row['city_ascii']. "" . $row['lat'] . "" . $row['lng']  ."'>" . $row['city_ascii'] ."</option>";
		}
		mysqli_close($con);


	}
?>

	
