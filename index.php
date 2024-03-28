<!DOCTYPE html>
<html>
	<head>
    <title>Study 2: Bubble Geomap</title>
    <style>
		body
		{
			background-color:black;
		}	
		#choosediv
		{
			width: 500px;
			height:35%;
		}
		#plotdiv
		{
			width: 1000px;
			height:1000px;
		}
		div.tooltip 
		{
			position: absolute;
			text-align: center;
			padding: 1.2rem;
			z-index: 25;
			background: #03f4fc;
			color: black;
			border: 1px solid;
			border-radius: 8px;
			pointer-events: none;
			font-size: 1rem;
			font-weight:bold;
		}
    </style>
    <script src='./jquery-3.6.1.js'></script>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

	   <!--D3 and Plotly Libraries-->
	<script src='https://d3js.org/d3.v4.js'></script>
	<script src="https://d3js.org/d3-geo-projection.v2.min.js"></script>
	<script src="https://d3js.org/d3-scale-chromatic.v1.min.js"></script>

	<script src="plot.js"></script>
	<script>
		$(document).ready(function() {
			   $('#countrydrp').select2();
			$('#countrydrp').select2({
				placeholder: 'Select a country',
				width:'500px',
				allowClear: true,
				theme:'classic'	   
			});
			$('#citydrp').select2();
			$('#citydrp').select2({
				placeholder: 'Select a city',
				width:'500px',
				allowClear: true,
				theme:'classic'	   
			});
		});

		function cityList()
		{
			$('#showcity').hide();
			var isoval = $('#countrydrp').children("option:selected").val();
			$("#citydrp").empty();
			$(".tooltip").remove();
	
			$.ajax({
				url:'citydrop.php',
				dataType: "text",
				data: {iso:isoval},
				type: 'POST',
				cache: false,
				success: function(response)
				{
					$('#showcity').hide();
					$("#citydrp").append(response);
					plot(isoval);

					$('#showcity').show();
				}
			});	
		}


	</script>
    </head>
    <body>
	<h2 style="text-align: center; color:#03f4fc;">Study 2: Bubble Geomap</h2>
	<div id='choosediv'>
        <p style="color:#03f4fc"><b>Please fill out the following form</b></p>
           <b><label for='country' style="color:#03f4fc">Select the country you'd like to see the population density of:</label></b>
		 <select name='country' id='countrydrp' onchange='cityList()'required>
		 <option></option>
		 <?php
			include('dbconfig.php');
			$con = mysqli_connect($host,$username,$password,$name) or die("Cannot connect to DB");	
			$query = 'SELECT country,iso3, SUM(population) as population from 2023S_okumueg.worldcities GROUP BY country  HAVING  population > 0  ORDER BY  country ASC;';
			$result = mysqli_query($con,$query);

			while($row = mysqli_fetch_array($result))
			{
				echo "<option value=" . $row['iso3'] .">" . $row['country'] ."</option>";
			}
			mysqli_close($con);
		 ?>
		 </select>
		<span style='display:none;' id='showcity'>	
			<b><label for='country'style="color:#03f4fc">Highlight A City:</label></b>
			<select name='country' id='citydrp' onchange='highlightCity()' required>
			</select>
		 </span>


	</div>
	
	<div>&nbsp;</div> 
	<div id='plotdiv'>
		 
	</div>
        </form>
    </body>
</html>
