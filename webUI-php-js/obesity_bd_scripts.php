<?php require_once('couch.php'); ?>
<?php 
  // $variable= file_get_contents("data_files/SA2_obesity.json");
  // echo "var table=".$variable;
  $variable = $couch->send("GET", "/web-result-obese/data");
  $jsonVar = json_decode($variable);
  $jsonData = $jsonVar->data;

  // echo "var table=".json_encode($jsonData).";";
?>

<?php
  // load SA2 polygons
  /*
  $meta = $couch->send("GET", "/sa2-polygons_melb-greater/meta");
  //echo "// ".gettype($meta)."\n";
  $tempObj = json_decode($meta);
  $tempObj->features = array();
  // echo "// ".json_encode($tempObj)."\n";

  // print_r($jsonData);
  foreach ($jsonData->features as $feature) 
  {
    $resp = $couch->send("GET", "/sa2-polygons_melb-greater/".$feature->properties->area_code);
    $jsonFeature = json_decode($resp);
    $jsonFeature = $jsonFeature->feature;
    array_push($tempObj->features, $jsonFeature);
    // break;
  }

  $tempObj->_id = "temp";
  unset($tempObj->_rev);
  // echo "// ".json_encode($tempObj);

  // echo $couch->send("PUT", "/tempdb");
  $couch->send("PUT", "/tempdb/temp", json_encode($tempObj));

  $loadUrl = "http://".$options["user"].":".$options["pass"]."@".$options["host"].":".$options["port"]."/tempdb/temp";

  $loadUrl = "http://".$options["host"].":".$options["port"]."/tempdb/temp";

  //echo $loadUrl;*/
?>

<head>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(draw);

    <?php echo "var table=".json_encode($jsonData).";"; ?>

    <?php //echo "var geoJsonUrl='".$loadUrl."';"; ?>

    var geoJsonUrl='data_files/SA2-polygons-melb-greater_WGS84.geojson';

    function best()
    {
      var obj = table;
      let min = obj.features[0].properties.obese_p_me_2_rate_3_11_7_13, max = obj.features[0].properties.obese_p_me_2_rate_3_11_7_13;
      var min1 = obj.features[0];
      var max1 = obj.features[0];

      for (let i = 1, len=obj.features.length; i < len; i++)
      {
        let v = obj.features[i].properties.obese_p_me_2_rate_3_11_7_13;
        if(v<min)
        {
          min = v;
          min1 = obj.features[i];
        }
        if(v>max)
        {
          max = v;
          max1 = obj.features[i];
        }
      }

      document.getElementById("ObeseMost").innerHTML= "Most obese SA2 area is " + max1.properties.area_name + " with a obesity rate of " + max1.properties.obese_p_me_2_rate_3_11_7_13;

      document.getElementById("ObeseLeast").innerHTML= "Least obese SA2 area is " + min1.properties.area_name + " with a obesity rate of " + min1.properties.obese_p_me_2_rate_3_11_7_13;
    }

    function draw()
    {
      best();
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'area_name');
      data.addColumn('number', 'obesity_rate');
      for (var i = 0; i < table.features.length; i++)
      {
        data.addRow([table.features[i].properties.area_name, table.features[i].properties.obese_p_me_2_rate_3_11_7_13]);
      }

      var options = {
        title: 'Obesity',
        is3D: true
      };

      var chart = new google.visualization.ColumnChart(document.getElementById('chartObesity'));
      chart.draw(data, options);
    }

    function myMap()
    {
      var mapProp=
      {
        center: new google.maps.LatLng(-37.799495, 144.936916),
        zoom:9.5,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false,
        mapTypeControlOptions: {
          mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.TERRAIN]
        }
      };

      var map = new google.maps.Map(document.getElementById("MapObesity"),mapProp);
      var stateLayer = new google.maps.Data();

      // stateLayer.loadGeoJson('data_files/SA2-polygons-melb-greater_WGS84.geojson');

      
      stateLayer.loadGeoJson(geoJsonUrl);
      
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
      
      stateLayer.addListener(map,'click', function(e){
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
      for (var i = 0; i < table.features.length; i++)
      {
        if(table.features[i].properties.area_code == score1)
          score = table.features[i].properties.obese_p_me_2_rate_3_11_7_13;
      }

      return score >= 49.55 ? '#89a844' :
      score > 40.1 ? '#acd033' :
      score > 30.66 ? '#cbd97c' :
      score > 21.216 ? '#c2c083' : '#d1ccad';
    }

    function printData(code)
    {
      for (var i = 0; i < table.features.length; i++)
      {
        if(table.features[i].properties.area_code == code)
        {
          document.getElementById("ObesityName").innerHTML= "Name : " + table.features[i].properties.area_name;
          document.getElementById("ObesityRate").innerHTML= "Obesity Rate : " + table.features[i].properties.obese_p_me_2_rate_3_11_7_13;
        }
      }
    }
  </script>
</head>