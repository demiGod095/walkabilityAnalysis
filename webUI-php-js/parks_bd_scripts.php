<?php require_once('couch.php'); ?>
<head>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(draw);

    <?php
      // $variable= file_get_contents("ZResult_obese_park_analysis.json");
      // echo "var table=".$variable;
      $variable = $couch->send("GET", "/web-zresult-obese-park/data");
      $jsonVar = json_decode($variable);
      $jsonData = $jsonVar->data;
      echo "var table=".json_encode($jsonData).";";
    ?>

    function best()
    {
      var obj = table;
      let min = obj[0].Park_Density, max = obj[0].Park_Density;
      var min1 = obj[0];
      var max1 = obj[0];

      for (let i = 1, len=obj.length; i < len; i++)
      {
        let v = obj[i].Park_Density;
        if(v<min)
        {
          min = v;
          min1 = obj[i];
        }
        if(v>max)
        {
          max = v;
          max1 = obj[i];
        }
      }

      document.getElementById("ParksMost").innerHTML= "SA2 with highest park density is " + max1.SA2_NAME16 + " with a density of " + max1.Park_Density;
        //document.getElementById("ParksLeast").innerHTML= "SA2 with lowest park density is " + min1.SA2_NAME16 + " with a density of " + min1.Park_Density;
    }

    function draw()
    {
      best();
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'SA2_NAME16');
      data.addColumn('number', 'Park_Density');
      for (var i = 0; i < table.length; i++)
      {
        data.addRow([table[i].SA2_NAME16, table[i].Park_Density]);
      }

      var options = {
        title: 'Percentage of Park cover',
        is3D: true
      };

      var chart = new google.visualization.ColumnChart(document.getElementById('chartParks'));
      chart.draw(data, options);
    }

    function myMap()
    {
      var mapProp= {
        center:new google.maps.LatLng(-37.764092, 145.083624),
        zoom:9.5,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false,
        mapTypeControlOptions: {
          mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.TERRAIN]
        }
      };

      var map = new google.maps.Map(document.getElementById("MapParks"),mapProp);
      var stateLayer = new google.maps.Data();
      stateLayer.loadGeoJson('data_files/SA2-polygons-melb-greater_WGS84.geojson');
      stateLayer.setStyle(function(feature) {
        return {
          fillColor: getColor(feature.getProperty('SA2_MAIN16')), // call function to get color 
          fillOpacity: 0.8,
          strokeColor: '#b3b3b3',
          strokeWeight: 1,
          zIndex: 1
        };
      });

      // Add mouseover and mouse out styling for the GeoJSON State data
      stateLayer.addListener('mouseover', function(e)
      {
        stateLayer.overrideStyle(e.feature, {
          strokeColor: '#2a2a2a',
          strokeWeight: 2,
          zIndex: 2
        });
        printData(e.feature.getProperty('SA2_MAIN16'));
      });

      stateLayer.addListener('mouseout', function(e) {
        stateLayer.revertStyle();
      });

      var infowindow = new google.maps.InfoWindow();
      stateLayer.addListener(map,'click', function(e) {
        infowindow.setContent(e.feature.getProperty('SumZScore').toString());
        infowindow.open(map,this);
        //map.setZoom(20);
        //document.getElementById('info-box').textContent =
        // event.feature.getProperty('SumZScore');

             //alert(e.feature.getProperty('SumZScore'))
      });



      // var iwindow= new google.maps.InfoWindow;
      //     google.maps.event.addListener(map,'click',function(event)
      //     {
      //          iwindow.setContent(event.latLng.lat()+","+event.latLng.lng());
      //          iwindow.open(map,this); 
      //          iwindow.open(map,this);
      //     });

      // Final step here sets the stateLayer GeoJSON data onto the map
      stateLayer.setMap(map)
    }

    function getColor(score1)
    {
      var score = 25;
      for (var i = 0; i < table.length; i++)
      {
        if(table[i].SA2_MAIN16 == score1)
          score = table[i].Park_Density;
      }

      return score >= 53.5 ? '#89a844' :
      score > 40.1 ? '#acd033' :
      score > 27 ? '#cbd97c' :
      score > 13.4 ? '#c2c083' : '#d1ccad';
    }

    function printData(code)
    {
      for (var i = 0; i < table.length; i++)
      {
        if(table[i].SA2_MAIN16 == code)
        {
          document.getElementById("ParksName").innerHTML= "Name : " + table[i].SA2_NAME16;
          document.getElementById("ParksRate").innerHTML= "Percentage of park cover : " + table[i].Park_Density;
        }
      }
    }

  </script>
</head>