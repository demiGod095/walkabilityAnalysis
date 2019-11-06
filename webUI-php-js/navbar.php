<!-- Top container -->
<div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
  <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button>
  <span class="w3-bar-item w3-right">Computing Project</span>
</div>

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-collapse w3-black w3-animate-left" style="z-index:3;width:180px;" id="mySidebar"><br>
  <div class="w3-container w3-row">

    <div class="w3-col s8 w3-bar">
      <span>Walkability correlation Analysis</span><br>
      
    </div>
  </div>

  <hr>
 
  <div class="w3-bar-block">
    <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
    
    <a href="index.php" class="w3-bar-item w3-button w3-padding <?php if ($currentPage === 'index') {echo 'w3-blue';} ?>"><i class="fa fa-home fa-fw"></i>  Home</a>
    <!-- <a href="about.php" class="w3-bar-item w3-button w3-padding <?php if ($currentPage === 'about') {echo 'w3-blue';} ?>"><i class="fa fa-info fa-fw"></i>  About</a> -->

    <button class="dropdown-btn w3-bar-item w3-button w3-padding"><i class="fa fa-table fa-fw"></i>  Base Data
            <i class="fa fa-caret-down"></i>
          </button>
          <div class="dropdown-container">
            <a href="walkability_bd.php" class="w3-bar-item w3-button w3-padding <?php if ($currentPage === 'walkability_bd') {echo 'w3-blue';} ?>">             Walkability</a>
            <a href="obesity_bd.php" class="w3-bar-item w3-button w3-padding <?php if ($currentPage === 'obesity_bd') {echo 'w3-blue';} ?>">             Obesity</a>
            <a href="restaurants_bd.php" class="w3-bar-item w3-button w3-padding <?php if ($currentPage === 'restaurants_bd') {echo 'w3-blue';} ?>">             Restaurants</a>
            <a href="parks_bd.php" class="w3-bar-item w3-button w3-padding <?php if ($currentPage === 'parks_bd') {echo 'w3-blue';} ?>">             Parks</a>

          </div>
    <a href="analysis.php" class="w3-bar-item w3-button w3-padding <?php if ($currentPage === 'analysis') {echo 'w3-blue';} ?>"><i class="fa fa-line-chart fa-fw"></i>  Analysis</a>
    <a href="conclusion.php" class="w3-bar-item w3-button w3-padding <?php if ($currentPage === 'conclusion') {echo 'w3-blue';} ?>"><i class="fa fa-file fa-fw"></i>  Overall</a>
    
  </div>
</nav>


<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

