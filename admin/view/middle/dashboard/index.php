<div class="wrapper">
  <div class="container con-padding-tb">
      <?php include(APPLICATION_PATH . "/application/global/message.php"); ?>
    <div class="row col-md-12">
      <div class="col-md-6 col-sm-12">
        <h3 class="custom-heading"> Number of End User per Admin</h3>
        <div class="blue clearfix visitor-stats">
          <h6> WEDNESDAY <i>APRIL / 2013</i> </h6>
          <span>24% <i>VISITOR</i> </span>
          <div id="chart"> <svg></svg> </div>
        </div>
      </div>
      <div class="col-md-6 col-sm-12">
        <h3 class="custom-heading">New User Registration</h3>
        <div id="flot-placeholder" class="orange-brd height-370"></div>
      </div>
    </div>
    <div class="row col-md-12 clearfix padding-top-15">
      <div class="col-md-4 clearfix col-sm-12">
		<h3 class="custom-heading">Admin wise Query Status</h3>
        <div class="orange-brd pull-left col-md-12 padding-top-5 height-370">
          <div id="doughnutChart" class="chart1"></div>
        </div>
      </div>
      <div class="col-md-4 col-sm-12">
        <div id="graph-wrapper">
			<h3 class="custom-heading">Total Email Sent Graph</h3>
		<div class="orange-brd pull-left col-md-12 padding-top-5 height-370">
          <div class="graph-info"> <a href="" class="visitors"><span class="green"></span>Visitors</a> <a href="" class="returning"><span class="black"></span>Returning Visitors</a> <a href="#" id="bars"><span><i class="fa fa-bar-chart-o"></i></span></a> <a href="#" id="lines" class="active"><span><i class="fa fa-code-fork"></i></span></a> </div>
          <div class="graph-container">
            <div id="graph-lines"></div>
            <div id="graph-bars"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-12">
			<h3 class="custom-heading">Usage Graph</h3>
      	<div class="orange-brd pull-left col-md-12 padding-top-5 height-370">
        	<div id="pieChart" class="chart"></div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Container --> 
</div>
<!-- Wrapper --> 