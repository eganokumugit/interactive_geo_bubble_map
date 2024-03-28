<?php
	if(isset($_POST['iso']))
	{
		$isoval = $_POST['iso'];
		$path = 'jsonfiles/worldborders.json';
		$file = fopen($path,'r');
		$jsondata = fread($file,filesize($path));
		$json = json_decode($jsondata,true);
		fclose($file);

		foreach($json as $i)
		{
			if(isset($i['fields']['iso3']) && $i['fields']['iso3'] == $isoval)
			{
				$mapcoordinates = $i['fields'];
				break;
			} 
		}
		#print_r($mapcoordinates);
		echo json_encode($mapcoordinates);
	}
?>

	
