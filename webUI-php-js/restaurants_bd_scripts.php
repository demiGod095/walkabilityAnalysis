<?php require_once('couch.php'); ?>
<head>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(draw);

    <?php
      // $variable= file_get_contents("ZResult_rest_analysis.json");
      // echo "var tableRestaurantLevel=".$variable;
      $variable = $couch->send("GET", "/web-zresult-rest/data");
      $jsonVar = json_decode($variable);
      $jsonData = $jsonVar->data;
      echo "var tableRestaurantLevel=".json_encode($jsonData).";";
    ?>

    function best()
    {
      var obj = tableRestaurantLevel;
      let min = obj[0]['Cafes and Restaurants'], max = obj[0]['Cafes and Restaurants'];
      var min1 = obj[0];
      var max1 = obj[0];
      for (let i = 1, len=obj.length; i < len; i++)
      {
        let v = obj[i]['Cafes and Restaurants'];
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

      document.getElementById("RestMost").innerHTML= "SA2 with most number of restaurants is " + max1.SA2_NAME16 + " with " + max1['Cafes and Restaurants']+" restaurants";
      document.getElementById("RestLeast").innerHTML= "SA2 with least number of restaurants is " + min1.SA2_NAME16 + " with " + min1['Cafes and Restaurants']+" restaurants";
    }

    function draw()
    {
      best();
      var dataRestaurantNum = new google.visualization.DataTable();
      dataRestaurantNum.addColumn('string', 'SA2_NAME16');
      dataRestaurantNum.addColumn('number', 'Cafes and Restaurants');
      for (var i = 0; i < tableRestaurantLevel.length; i++)
      {
        dataRestaurantNum.addRow([tableRestaurantLevel[i].SA2_NAME16, tableRestaurantLevel[i]['Cafes and Restaurants']]);
      }

      var options = {
        title: 'Number of Restaurants',
        is3D: true
      };

      var chart = new google.visualization.ColumnChart(document.getElementById('chartRestaurants'));
      chart.draw(dataRestaurantNum, options);
    }

    function myMap()
    {
      var mapProp= {
        center:new google.maps.LatLng(-37.799495, 144.936916),
        zoom:12.5,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false,
        mapTypeControlOptions: {
          mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.TERRAIN]
        }
      };
      var map = new google.maps.Map(document.getElementById("MapRestaurants"),mapProp);
      var stateLayer = new google.maps.Data();
      stateLayer.loadGeoJson('data_files/sa2-polygons_sa3-melb-inner-yarra.geojson');
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
      stateLayer.addListener('mouseover', function(e) {
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
      for (var i = 0; i < tableRestaurantLevel.length; i++)
      {
        if(tableRestaurantLevel[i].SA2_MAIN16 == score1)
          score = tableRestaurantLevel[i]['Cafes and Restaurants'];
      }

      return score >= 1000 ? '#89a844' :
      score > 252 ? '#acd033' :
      score > 168 ? '#cbd97c' :
      score > 84 ? '#c2c083' : '#d1ccad';
    }

    function printData(code)
    {
      for (var i = 0; i < tableRestaurantLevel.length; i++)
      {
        if(tableRestaurantLevel[i].SA2_MAIN16 == code)
        {
          document.getElementById("RestName").innerHTML= "Name : " + tableRestaurantLevel[i].SA2_NAME16;
          document.getElementById("RestRate").innerHTML= "Number of Restaurants : " + tableRestaurantLevel[i]['Cafes and Restaurants'];
        }
      }
    }
  </script>
</head>