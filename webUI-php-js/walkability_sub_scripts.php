<?php require_once('couch.php'); ?>
<!-- for putting the charts -->
<head>


<script type = "text/javascript">
function myMap() {
    var mapProp= {
      center:new google.maps.LatLng(-37.799495, 144.936916),
      zoom:9.5,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      mapTypeControl: false,
      mapTypeControlOptions: {
        mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.TERRAIN]
      }
    };


    var map = new google.maps.Map(document.getElementById("SA2MapWalkability"),mapProp);

    var map3 = new google.maps.Map(document.getElementById("SA3MapWalkability"),mapProp);

    var map4 = new google.maps.Map(document.getElementById("SA4MapWalkability"),mapProp);


    var stateLayer = new google.maps.Data();
      stateLayer.loadGeoJson('data_files/SA2-polygons-melb-greater_WGS84.geojson');
    var stateLayer3 = new google.maps.Data();
      stateLayer3.loadGeoJson('data_files/SA3-polygons-melb-greater_WGS84.geojson');
    var stateLayer4 = new google.maps.Data();
      stateLayer4.loadGeoJson('data_files/SA4-polygons-melb-greater_WGS84.geojson');




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
         printData2(e.feature.getProperty('SA2_MAIN16'));

    });

    stateLayer.addListener('mouseout', function(e) {
        stateLayer.revertStyle();


    });


    var infowindow = new google.maps.InfoWindow();
    stateLayer.addListener(map,'click', function(e) {
        //map.setZoom(20);
        //document.getElementById('info-box').textContent =
        // event.feature.getProperty('SumZScore');
        infowindow.setContent(e.feature.getProperty('SumZScore').toString())
         infowindow.open(map,this);

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


    //sa3

    stateLayer3.setStyle(function(feature) {
        return {
        fillColor: getColor3(feature.getProperty('SA3_CODE16')), // call function to get color 
        fillOpacity: 0.8,
        strokeColor: '#b3b3b3',
        strokeWeight: 1,
        zIndex: 1
        };
    });

    stateLayer3.addListener('mouseover', function(e) {
        stateLayer3.overrideStyle(e.feature, {
        strokeColor: '#2a2a2a',
        strokeWeight: 2,
        zIndex: 2
        });
        printData3(e.feature.getProperty('SA3_CODE16'));

    });

    stateLayer3.addListener('mouseout', function(e) {
        stateLayer3.revertStyle();
    });


    var infowindow3 = new google.maps.InfoWindow();
    stateLayer3.addListener(map3,'click', function(e) {
        //map.setZoom(20);
        //document.getElementById('info-box').textContent =
        // event.feature.getProperty('SumZScore');
        infowindow3.setContent(e.feature.getProperty('SumZScore').toString())
         infowindow3.open(map3,this);

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
    stateLayer3.setMap(map3)


    //sa4

    stateLayer4.setStyle(function(feature) {
        return {
        fillColor: getColor4(feature.getProperty('SA4_CODE16')), // call function to get color 
        fillOpacity: 0.8,
        strokeColor: '#b3b3b3',
        strokeWeight: 1,
        zIndex: 1
        };
    });

    stateLayer4.addListener('mouseover', function(e) {
        stateLayer4.overrideStyle(e.feature, {
        strokeColor: '#2a2a2a',
        strokeWeight: 2,
        zIndex: 2
        });

        printData4(e.feature.getProperty('SA4_CODE16'));

    });

    stateLayer4.addListener('mouseout', function(e) {
        stateLayer4.revertStyle();
    });


    var infowindow4 = new google.maps.InfoWindow();
    stateLayer4.addListener(map4,'click', function(e) {
        //map.setZoom(20);
        //document.getElementById('info-box').textContent =
        // event.feature.getProperty('SumZScore');
        infowindow4.setContent(e.feature.getProperty('SumZScore').toString())
         infowindow4.open(map4,this);

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
    stateLayer4.setMap(map4)

}

<?php
    // $variableu= file_get_contents("ZResult_obese_park_analysis.json");
    // echo "var tableSA2=".$variableu;

    $variable = $couch->send("GET", "/web-zresult-obese-park/data");
    $jsonVar = json_decode($variable);
    $jsonData = $jsonVar->data;
    echo "var tableSA2=".json_encode($jsonData).";";
?>

function getColor(score1) {


      var score = 25;
 for (var i = 0; i < tableSA2.length; i++) {
                if(tableSA2[i].SA2_MAIN16 == score1)
                score = tableSA2[i].SumZScore;
            }


 return score >= 11.76 ? '#89a844' :
 score > 7.84 ? '#acd033' :
 score > 3.8 ? '#cbd97c' :
 score > -0.03 ? '#c2c083' :
 '#d1ccad';
 }


<?php
    // $variableu1= file_get_contents("SA3ZScore.json");
    // echo "var tableSA3=".$variableu1;

    $variable = $couch->send("GET", "/web-sa3-zscore/data");
    $jsonVar = json_decode($variable);
    $jsonData = $jsonVar->data;
    echo "var tableSA3=".json_encode($jsonData).";";
?>

function getColor3(score1) {

      var score = 25;
 for (var i = 0; i < tableSA3.length; i++) {
                if(tableSA3[i].SA3_CODE16 == score1)
                score = tableSA3[i].SumZScore;
            }


 return score >= 6.4 ? '#89a844' :
 score > 4 ? '#acd033' :
 score > 1.8 ? '#cbd97c' :
 score > -0.5 ? '#c2c083' :
 '#d1ccad';
 }


<?php
    // $variableu2= file_get_contents("SA4ZScore.json");
    // echo "var tableSA4=".$variableu2;
    $variable = $couch->send("GET", "/web-sa4-zscore/data");
    $jsonVar = json_decode($variable);
    $jsonData = $jsonVar->data;
    echo "var tableSA4=".json_encode($jsonData).";";
?>

function getColor4(score1) {

      var score = 25;
 for (var i = 0; i < tableSA4.length; i++) {
                if(tableSA4[i].SA4_CODE16 == score1)
                score = tableSA4[i].SumZScore;
            }


 return score >= 2.5 ? '#89a844' :
 score > 1.62 ? '#acd033' :
 score > 0.681 ? '#cbd97c' :
 score > -0.25 ? '#c2c083' :
 '#d1ccad';
 }



 function printData2(code){
    for (var i = 0; i < tableSA2.length; i++) {
                if(tableSA2[i].SA2_MAIN16 == code)
                {
                    document.getElementById("SA2Name").innerHTML= "Name : " + tableSA2[i].SA2_NAME16;
                    document.getElementById("SA2Zscore").innerHTML= "Walkability ZScore : " + tableSA2[i].SumZScore;
                    document.getElementById("SA2MinScore").innerHTML= "Minimum walkability zscore recorded : " + tableSA2[i].MinZScore;
                    document.getElementById("SA2MaxScore").innerHTML= "Maximum walkability zscore recorded : " + tableSA2[i].MaxZScore;

                }
            }


 }


 function printData3(code){
    for (var i = 0; i < tableSA3.length; i++) {
                if(tableSA3[i].SA3_CODE16 == code)
                {
                    document.getElementById("SA3Name").innerHTML= "Name : " + tableSA3[i].SA3_NAME16;
                    document.getElementById("SA3Zscore").innerHTML= "Walkability ZScore : " + tableSA3[i].SumZScore;

                }
            }


 }

 function printData4(code){
    for (var i = 0; i < tableSA4.length; i++) {
                if(tableSA4[i].SA4_CODE16 == code)
                {
                    document.getElementById("SA4Name").innerHTML= "Name : " + tableSA4[i].SA4_NAME16;
                    document.getElementById("SA4Zscore").innerHTML= "Walkability ZScore : " + tableSA4[i].SumZScore;

                }
            }


 }
</script>





<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(draw);

        <?php
            // $variable= file_get_contents("SA2ZScore.json");
            // echo "var table=".$variable;

            $variable = $couch->send("GET", "/web-sa2-zscore/data");
            $jsonVar = json_decode($variable);
            $jsonData = $jsonVar->data;
            echo "var table=".json_encode($jsonData).";";
        ?>

<?php
    // $variable3= file_get_contents("SA3ZScore.json");
    // echo "var table3=".$variable3;

    $variable = $couch->send("GET", "/web-sa3-zscore/data");
    $jsonVar = json_decode($variable);
    $jsonData = $jsonVar->data;
    echo "var table3=".json_encode($jsonData).";";
?>

<?php
    // $variable4= file_get_contents("SA4ZScore.json");
    // echo "var table4=".$variable4;

    $variable = $couch->send("GET", "/web-sa4-zscore/data");
    $jsonVar = json_decode($variable);
    $jsonData = $jsonVar->data;
    echo "var table4=".json_encode($jsonData).";";
?>


function best(){

var obj = table;

let min = obj[0].SumZScore, max = obj[0].SumZScore;
var min1 = obj[0];
var max1 = obj[0];

  for (let i = 1, len=obj.length; i < len; i++) {
    let v = obj[i].SumZScore;

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

        document.getElementById("SA2Best").innerHTML= "Best walkable SA2 is " + max1.SA2_NAME16 + " with a walkability ZScore of " + max1.SumZScore;
        document.getElementById("SA2Worst").innerHTML= "Least walkable SA2 area is " + min1.SA2_NAME16 + " with a walkability ZScore of " + min1.SumZScore;

        var obj3 = table3;

let min3 = obj3[0].SumZScore, max3 = obj3[0].SumZScore;
var min13 = obj3[0];
var max13 = obj3[0];

  for (let i = 1, len=obj3.length; i < len; i++) {
    let v = obj3[i].SumZScore;

    if(v<min3)
    {
        min3 = v;
        min13 = obj3[i];
    }
    if(v>max3)
    {
        max3 = v;
        max13 = obj3[i];
    }
  }

        document.getElementById("SA3Best").innerHTML= "Best walkable SA3 area is " + max13.SA3_NAME16 + " with a walkability ZScore of " + max13.SumZScore;
        document.getElementById("SA3Worst").innerHTML= "Least walkable SA3 area is " + min13.SA3_NAME16 + " with a walkability ZScore of " + min13.SumZScore;

        var obj4 = table4;

        let min4 = obj4[0].SumZScore, max4 = obj4[0].SumZScore;
var min14 = obj4[0];
var max14 = obj4[0];

  for (let i = 1, len=obj4.length; i < len; i++) {
    let v = obj4[i].SumZScore;

    if(v<min4)
    {
        min4 = v;
        min14 = obj4[i];
    }
    if(v>max4)
    {
        max4 = v;
        max14 = obj4[i];
    }
  }

        document.getElementById("SA4Best").innerHTML= "Best walkable SA4 area is " + max14.SA4_NAME16 + " with a walkability ZScore of " + max14.SumZScore;
        document.getElementById("SA4Worst").innerHTML= "Least walkable SA4 area is " + min14.SA4_NAME16 + " with a walkability ZScore of " + min14.SumZScore;

    
    
}

// function getMinY() {
//   return data.reduce((min, p) => p.y < min ? p.y : min, data[0].y);
// }

      function draw() {

       best();
        var data = new google.visualization.DataTable();

        data.addColumn('string', 'SA2_NAME16');
        data.addColumn('number', 'SumZScore');

        for (var i = 0; i < table.length; i++) {
                data.addRow([table[i].SA2_NAME16, table[i].SumZScore]);
            }

        var options = {
          title: 'Walkability',
          is3D: true
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('SA2GraphWalkability'));

        chart.draw(data, options);



        //sa3

        var data3 = new google.visualization.DataTable();

data3.addColumn('string', 'SA3_NAME16');
data3.addColumn('number', 'SumZScore');

for (var i = 0; i < table3.length; i++) {
        data3.addRow([table3[i].SA3_NAME16, table3[i].SumZScore]);
    }

// var options = {
//   title: 'Walkability',
//   is3D: true
// };

var chart3 = new google.visualization.ColumnChart(document.getElementById('SA3GraphWalkability'));

chart3.draw(data3, options);

//sa4

var data4 = new google.visualization.DataTable();

data4.addColumn('string', 'SA4_NAME16');
data4.addColumn('number', 'SumZScore');

for (var i = 0; i < table4.length; i++) {
        data4.addRow([table4[i].SA4_NAME16, table4[i].SumZScore]);
    }

// var options = {
//   title: 'Walkability',
//   is3D: true
// };

var chart4 = new google.visualization.ColumnChart(document.getElementById('SA4GraphWalkability'));

chart4.draw(data4, options);


      }
   
    </script>

</head>