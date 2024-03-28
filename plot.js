function plot(isoval)
{
	$("#plotdiv").empty(); 
	$.ajax({ 
		   url:'mapplot.php', 
		   dataType: "json", 
		   data: {iso:isoval}, 
		   type: 'POST', 
		   cache: false, 
		   success: function(response) 
		   { 
				//var arr = JSON.stringify(response);
				var jsonFile = response;
				var mapJSON = getMap(isoval);
				centerLon = mapJSON.geo_point_2d[0];
				centerLat = mapJSON.geo_point_2d[1];
				
				var cityJSON = response;
				drawMap(mapJSON,isoval,cityJSON);	

	   }

	}); 
}


function getMap(isoval)
{
	var mapVal = '';
	$.ajax({ 
		   url:'getmap.php', 
		   dataType: "json", 
		   data: {iso:isoval}, 
		   type: 'POST', 
		   cache: false,
		   async: false,
		   success: function(response) 
		   {
			mapVal = response;	
		   }
	});
	return mapVal;
}

function drawMap(mapJSON,isoVal,cityJSON)
{
	var fillColor = "#FF1313";
	var hltColor = "#03f4fc"; 

	var centerLat = mapJSON.geo_point_2d[0];
	var centerLon = mapJSON.geo_point_2d[1];
	var projection;
	var width = 950;
	var height = 950;
	var rangeMin = 1
	var rangeMax = 100;
	if(cityJSON.length == 1){rangeMax = 200; rangeMin = rangeMax;}
	else if(cityJSON.length < 20){rangeMin = 10;}
	else if(cityJSON.length < 65){rangeMin = 20;}
	if(isoVal == 'CHN'){rangeMax = 35}
	if(isoVal == 'USA'){projection = d3.geoAlbersUsa();}
	else if(isoVal == "RUS"){
		   projection = d3.geoMercator()
			.scale(width / 2 / Math.PI)
			.translate([(width) / 2, height * 1.35 /2])
			.precision(.1)
			.center([30,39])
			.rotate([-11,0]);
	}
	else{projection = d3.geoMercator().center([centerLon,centerLat]);}

	var div = d3.select("body").append("div")
		.attr("class","tooltip")
		.style("opacity",0);
	var svg = d3.select('#plotdiv')
		.append("h2")
		.html(`Population Density of ${mapJSON.name}:`)
		.style("color","#03f4fc")
		.style("text-align","center")
		.append("svg")
		.attr("width",width)
		.attr("height",height);

	var popMinMax = d3.extent(cityJSON,function(d){return +d.population});
	var popScale = d3
		.scaleSqrt()
		.domain(popMinMax)
		.range([rangeMin,rangeMax]);
	var path = d3.geoPath().projection(projection);

	projection.fitSize([width,height],mapJSON.geo_shape);
	svg.append("path")
		.attr("d",path(mapJSON.geo_shape))
		.attr("fill","black")
		.attr("stroke","#03f4fc")
		.attr("width",width)
		.attr("height",height)
		.style("stroke-width","2px");
	

	var g = svg.append("g");
	
	g.selectAll("circle")
		.data(cityJSON)
		.enter().append("circle")
		.style('fill',fillColor)
		.attr('cx',function(city){return projection([city.lng,city.lat])[0];})
		.attr('cy',function(city){return projection([city.lng,city.lat])[1];})
		.attr("r", function(city){return popScale(city.population);})
		.attr("fill-opacity",0.6)
		.on('mouseover', function (city, i){
			d3.select(this).transition()
				.duration('100')
				.style("fill", hltColor)
				.attr("fill-opacity",0.6)
				.attr("stroke", fillColor)
				.attr("stroke-width","4px");
			div.html(city.city_ascii + ": " + d3.format(",d")(city.population))
				.style("left", (d3.event.pageX + 10) + "px")
				.style("top", (d3.event.pageY - 15) + "px");
			//Makes div appear
			div.transition()
				.duration(100)
				.style("opacity", 1);
		})
		.on('mouseout', function (d, i) {
			d3.select(this).transition()
				.duration(200)
				.style("fill", fillColor)
				.attr("fill-opacity",0.6)
				.attr("stroke","none")
				.attr("stroke-width", "0px");
			//Makes div disappear
			div.transition()
				.duration(100)
				.style("opacity", 0);
		})
		.attr("id",function(city){return city.city_ascii + "" + city.lat + "" + city.lng;}) ;


}

function highlightCity()
{	
	var hltColor = "#57FF00";
	var ogColor = "#FF1313";
	var cleanCircle = d3.selectAll("circle")
		.transition()
		.duration('100')
		.style("fill", ogColor)
		.attr("fill-opacity",0.6)
		.attr("stroke", "none")
		.attr("stroke-width","0px");

	var city = $('#citydrp').children("option:selected").val();
	var circle = d3.select(`[id="${city}"]`)
		.transition()
		.duration(100)
		.style("fill", hltColor)
		.attr("fill-opacity",0.8)
		.attr("stroke", ogColor)
		.attr("stroke-width","3px")
}


