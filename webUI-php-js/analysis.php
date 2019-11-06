<?php include('head.php'); ?>
<?php require_once('couch.php'); ?>

<?php $currentPage = 'analysis'; ?>

<!-- for putting the charts -->
<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(draw);

      <?php
        //$variable= file_get_contents("ZResult_obese_park_analysis.json");

        $variable = $couch->send("GET", "/web-zresult-obese-park/data");

        $jsonVar = json_decode($variable);
        $jsonData = $jsonVar->data;

        echo "var table=".json_encode($jsonData).";";
      ?>


      <?php
        // $variableRestaurant= file_get_contents("ZResult_rest_analysis.json");
        // echo "var tableRestaurantLevel=".$variableRestaurant;

        $variable = $couch->send("GET", "/web-zresult-rest/data");

        $jsonVar = json_decode($variable);
        $jsonData = $jsonVar->data;

        echo "var tableRestaurantLevel=".json_encode($jsonData).";";

      ?>


      function draw()
      {
        var data = new google.visualization.DataTable();

        data.addColumn('number', 'SumZScore');
        data.addColumn('number', 'obese_p_me_2_rate_3_11_7_13');

        for (var i = 0; i < table.length; i++)
        {
          data.addRow([table[i].SumZScore, table[i].obese_p_me_2_rate_3_11_7_13]);
        }

        var options = {
          title: 'Walkability vs. Obesity Rate',
          is3D: true,
          legend: 'none',


          trendlines:{
             0: {
                 type: 'linear',
                 visibleInLegend: true,
              }
            }
        };

        var chart = new google.visualization.ScatterChart(document.getElementById('ObesityGraphWalkability'));

        chart.draw(data, options);

        var dataObesityNum = new google.visualization.DataTable();

        dataObesityNum.addColumn('number', 'SumZScore');
        dataObesityNum.addColumn('number', 'obese_p_me_1_no_3_11_7_13');

        for (var i = 0; i < table.length; i++)
        {
          dataObesityNum.addRow([table[i].SumZScore, table[i].obese_p_me_1_no_3_11_7_13]);
        }

        var optionsObesityNum = {
          title: 'Walkability vs. Obesity Number',
          is3D: true,
          legend: 'none',


          trendlines: {
             0: {
                 type: 'linear',
                 visibleInLegend: true,
              }
            }
        };

        var chartObesityNum = new google.visualization.ScatterChart(document.getElementById('ObesityNumberGraphWalkability'));

        chartObesityNum.draw(dataObesityNum, optionsObesityNum);


        //restaurant number

        var dataRestaurantNum = new google.visualization.DataTable();

        dataRestaurantNum.addColumn('number', 'SumZScore');
        dataRestaurantNum.addColumn('number', 'Cafes and Restaurants');

        for (var i = 0; i < tableRestaurantLevel.length; i++)
        {
          dataRestaurantNum.addRow([tableRestaurantLevel[i].SumZScore, tableRestaurantLevel[i]['Cafes and Restaurants']]);
        }

        var optionsRestNum = {
          title: 'Walkability vs. Number of Restaurants',
          is3D: true,
          legend: 'none',


          trendlines: {
             0: {
                 type: 'linear',
                 visibleInLegend: true,
              }
            }
        };

        var chartRestaurantNum = new google.visualization.ScatterChart(document.getElementById('RestaurantsGraphWalkability'));

        chartRestaurantNum.draw(dataRestaurantNum, optionsRestNum);



        //parks area

        var dataParksArea = new google.visualization.DataTable();

        dataParksArea.addColumn('number', 'SumZScore');
        dataParksArea.addColumn('number', 'Park_Density');

        for (var i = 0; i < table.length; i++)
        {
          dataParksArea.addRow([table[i].SumZScore, table[i].Park_Density]);
        }

        var optionsParksArea = {
          title: 'Walkability vs. Park Density',
          is3D: true,
          legend: 'none',


          trendlines: {
             0: {
                 type: 'linear',
                 visibleInLegend: true,
              }
            }
        };

        var chartParksArea = new google.visualization.ScatterChart(document.getElementById('ParksGraphWalkability'));

        chartParksArea.draw(dataParksArea, optionsParksArea);

      }
    </script>
</head>


<body class="w3-grey">

<?php include('navbar.php'); ?>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:180px;margin-top:43px;">

 <!-- Header -->
 <header class="w3-container w3-dark-grey" style="padding-top:22px">
    <h5><b><i class="fa fa-line-chart"></i> Analysis</b></h5>
  </header>

  		<button class="tablink" onclick="openPage('Obesity', this, '#e4e4e4');draw();" id="defaultOpen">Obesity</button>
        <button class="tablink" onclick="openPage('Restaurants', this, '#e4e4e4');draw();">Restaurants</button>
        <button class="tablink" onclick="openPage('Parks', this, '#e4e4e4');draw();">Parks</button>
        <!-- <button class="tablink" onclick="openPage('Default', this, '#e4e4e4');draw();">Default</button> -->
        <div id="Obesity" class="tabcontent">

            <h3>Walkability vs Obesity Correlation</h3>

            <div id="ObesityGraphWalkability" class="card" style="height: 700px; width: 100%;"></div>
            <div class="w3-column-padding w3-margin-bottom"> </div>
            <div class="w3-column-padding w3-margin-bottom"> </div>
            <div id="ObesityNumberGraphWalkability" class="card" style="height: 700px; width: 100%;"></div>
            <div class="w3-column-padding w3-margin-bottom"> </div>
        </div>


        <div id="Restaurants" class="tabcontent">

            <h3>Walkability vs Number of Restaurants Correlation</h3>

            <!-- <div class="w3-panel"> -->
 
 	            <!-- <div class="w3-twothird"> -->

 		            <div id="RestaurantsGraphWalkability" class="card" style="height: 700px; width: 100%;"></div>
 	            <!-- </div> -->
	 
	            <!-- <div class="w3-third">

	                <div class="card" style="margin-left:10px;height: 370px; width: 100%; "></div>
	 
                </div> -->

            <!-- </div>    -->

            <div class="w3-column-padding w3-margin-bottom"> </div>


               <!--
                <div class="boxed card w3-third" style="width: 150px; height: 500px;">


                    <h4>Best Walkable area</h4>
                    
                    <!- populate the list here -->

        </div>

        <div id="Parks" class="tabcontent">

            <h3>Walkability vs Parks Area Correlation</h3>

            <!-- <div class="w3-panel"> -->
 
 	            <!-- <div class="w3-twothird"> -->

 		            <div id="ParksGraphWalkability" class="card" style="height: 700px; width: 100%;"></div>
 	            <!-- </div> -->
	 
	            <!-- <div class="w3-third">

	                <div class="card" style="margin-left:10px;height: 370px; width: 100%; "></div>
	 
                </div>

            </div>    -->

            <div class="w3-column-padding w3-margin-bottom"> </div>

            <!-- <div id="ParksNumGraphWalkability" class="card" style="height: 600px; width: 100%;"></div> -->

            <div class="w3-column-padding w3-margin-bottom"> </div>




               <!--
                <div class="boxed card w3-third" style="width: 150px; height: 500px;">


                    <h4>Best Walkable area</h4>
                    
                    <!- populate the list here -->

        </div>

        <!-- <div id="Default" class="tabcontent">

            <h3>Walkability vs Default Correlation</h3>

            <div class="w3-panel">
 
 	            <div class="w3-twothird">

 		            <div id="DefaultGraphWalkability" class="card" style="height: 370px; width: 100%;"></div>
 	            </div>
	 
	            <div class="w3-third">

	                <div class="card" style="margin-left:10px;height: 370px; width: 100%; "></div>
	 
                </div>

            </div>   

            <div class="w3-column-padding w3-margin-bottom"> </div>


               
                <div class="boxed card w3-third" style="width: 150px; height: 500px;">


                    <h4>Best Walkable area</h4>
                    
                    <!- populate the list here 

        </div> -->

 

    <!--

  <div class="w3-panel">
    <div class="w3-row-padding" style="margin:0 -16px">

-->

  
      
  <!-- Footer -->
  <footer class="w3-container w3-padding-16 w3-dark-grey">
    <h4>Computing Project: Walkabiltiy Correlation Analysis </h4>
    
  </footer>

  <!-- End page content -->
</div>

    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="javascripts/main.js"></script>
    <!-- <script type="text/javascript" src="javascripts/walkability_bd.js"></script> -->
  </body>
</html>