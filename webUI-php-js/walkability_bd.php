<?php include('head.php'); ?>

<?php $currentPage = 'walkability_bd'; ?>

<?php include('walkability_sub_scripts.php'); ?>


<!-- end of charts -->
<body class="w3-grey">

    <?php include('navbar.php'); ?>


    <!-- !PAGE CONTENT! -->
    <div class="w3-main" style="margin-left:180px;margin-top:43px;">

        <!-- Header -->
        <header class="w3-container w3-dark-grey" style="padding-top:22px">
            <h5><b><i class="fa fa-table"></i> Walkability Data</b></h5>
        </header>


        <!-- <button class="tablink" onclick="openPage('SA1', this, '#e4e4e4');draw()">SA1 Level</button> -->
        <button class="tablink" onclick="openPage('SA2', this, '#e4e4e4');draw()" id="defaultOpen">SA2 Level</button>
        <button class="tablink" onclick="openPage('SA3', this, '#e4e4e4');draw()">SA3 Level</button>
        <button class="tablink" onclick="openPage('SA4', this, '#e4e4e4');draw()">SA4 Level</button>


        <div id="SA2" class="tabcontent">

            <h3>SA2 Level Walkability Data</h3>

            <div class="w3-panel">
 
 	            <!-- <div class="w3-twothird"> -->

 		            <div id="SA2GraphWalkability" class="card" style="height: 600px; width: 100%;"></div>
 	            <!-- </div> -->
                 <div class="w3-column-padding w3-margin-bottom"> </div>

	 
	            <!-- <div class="w3-third">

	                <div class="card" style="margin-left:10px;height: 370px; width: 100%; ">
                    <h4 id = "SA2Best"></h4>
                    <h4 id = "SA2Worst"></h4>
                    </div>
	 
                </div> -->

            </div>   

            <div class="w3-column-padding w3-margin-bottom"> </div>

            <div class="w3-panel">
                <div class="w3-twothird">

            <div id="SA2MapWalkability" class="card w3-container" style="height:800px; width: 100%"></div>

            </div>

                <div class="w3-third">

	                <div class="card w3-black" style="margin-left:10px;height: 370px; width: 100%; ">
                    <h4>    </h4>
                    <h4 id = "SA2Name"></h4>
                    <h4 id = "SA2Zscore"></h4>
                    <h4 id = "SA2MinScore"></h4>
                    <h4 id = "SA2MaxScore"></h4>
                    </div>
	 
                </div>

                <div class="w3-third">

	                <div class="card w3-black" style="margin-left:10px;height: 370px; width: 100%; ">
                    <h4>   </h4>
                    <h4 id = "SA2Best"></h4>
                    <h4 id = "SA2Worst"></h4>
                    </div>
	 
                </div>



            </div>


               <!--
                <div class="boxed card w3-third" style="width: 150px; height: 500px;">


                    <h4>Best Walkable area</h4>
                    
                    <!- populate the list here -->

        </div>

        <div id="SA3" class="tabcontent">

            <h3>SA3 Level Walkability Data</h3>

            <div class="w3-panel">
 
 	            <!-- <div class="w3-twothird"> -->

 		            <div id="SA3GraphWalkability" class="card" style="height: 600px; width: 100%;"></div>
 	            <!-- </div> -->

                 <div class="w3-column-padding w3-margin-bottom"> </div>

	 
	            <!-- <div class="w3-third">

	                <div class="card" style="margin-left:10px;height: 370px; width: 100%; "></div>
	 
                </div> -->

            </div>   

            <div class="w3-column-padding w3-margin-bottom"> </div>

            <div class="w3-panel">
                <div class="w3-twothird">

                    <div id="SA3MapWalkability" class="card w3-container" style="height:800px; width: 100%"></div>
                </div>

                <div class="w3-third">

	                <div class="card w3-black" style="margin-left:10px;height: 370px; width: 100%; ">
                    <h4 id = "SA3Name"></h4>
                    <h4 id = "SA3Zscore"></h4>
                    </div>
	 
                </div>

                <div class="w3-column-padding w3-margin-bottom"> </div>

                <div class="w3-third">

	                <div class="card w3-black" style="margin-left:10px;height: 370px; width: 100%; ">
                    <h4 id = "SA3Best"></h4>
                    <h4 id = "SA3Worst"></h4>
                    </div>
	 
                </div>

            </div>


               <!--
                <div class="boxed card w3-third" style="width: 150px; height: 500px;">


                    <h4>Best Walkable area</h4>
                    
                    <!- populate the list here -->

        </div>

        <div id="SA4" class="tabcontent">

            <h3>SA4 Level Walkability Data</h3>

            <div class="w3-panel">
 
 	            <!-- <div class="w3-twothird"> -->

 		            <div id="SA4GraphWalkability" class="card" style="height: 600px; width: 100%;"></div>
 	            <!-- </div>
	 
	            <div class="w3-third">

	                <div class="card w3-black" style="margin-left:10px;height: 370px; width: 100%; ">
                    <h4 id = "SA4Best"></h4>
                    <h4 id = "SA4Worst"></h4>
                    </div>
	 
                </div> -->

            </div>   

            <div class="w3-column-padding w3-margin-bottom"> </div>

            <div class="w3-panel">

                <div class="w3-twothird">


            <div id="SA4MapWalkability" class="card w3-container" style="height:800px; width: 100%"></div>

            </div>

                <div class="w3-third">

	                <div class="card  w3-black" style="margin-left:10px;height: 370px; width: 100%; ">
                    <h4 id = "SA4Name"></h4>
                    <h4 id = "SA4Zscore"></h4>
                    </div>

                    <div class="card w3-black" style="margin-left:10px;height: 370px; width: 100%; ">
                    <h4 id = "SA4Best"></h4>
                    <h4 id = "SA4Worst"></h4>
                    </div>
	 
                </div>

            </div>


               <!--
                <div class="boxed card w3-third" style="width: 150px; height: 500px;">


                    <h4>Best Walkable area</h4>
                    
                    <!- populate the list here -->

        </div>



        <!-- Footer -->
        <footer class="w3-container w3-padding-16 w3-dark-grey">
            <h4>Computing Project: Walkabiltiy Correlation Analysis </h4>

        </footer>

        <!-- End page content -->
    </div>


   


    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="javascripts/main.js"></script>
    


   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBlrDIe2aqNWvrleu1U4Mc-vId1O4GISyQ&callback=myMap"> 
    </script>

</body>

</html>