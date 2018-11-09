	<!-- Footer Start -->
	<footer class="footer">
	  <div class="container-fluid">
	    <div class="row">
	      <div class="col-md-6">
	      2018 © AquaPi ( <?php echo $site_settings['version'];?> )                                                                                                                                                                     
	      </div>
	      <div class="col-md-6">
        	<div class="text-md-right footer-links d-none d-md-block">
	          <a href="javascript: void(0);">About</a>
        	  <a href="javascript: void(0);">Support</a>
	          <a href="javascript: void(0);">Contact Us</a>
        	</div>
	      </div>
	    </div>
	  </div>
	</footer>
	<!-- end Footer -->
        </div> <!-- content -->

    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->
</div>
<!-- END wrapper -->
        <?php
        if ($currentPage != 'guest.php') {
        ?>
        <!-- Right Sidebar -->
        <div class="right-bar">

            <div class="rightbar-title">
                <a href="javascript:void(0);" class="right-bar-toggle float-right">
                    <i class="dripicons-cross noti-icon"></i>
                </a>
                <h5 class="m-0">Settings</h5>
            </div>

            <div class="slimscroll-menu">

                <!-- Settings -->
		<div class="mt-2 pl-2 pr-2">
			<div class="form-group mb-3">
				<label>Feed time (Mins.)</label>
				<input data-toggle="touchspin" type="text" value="<?php echo $site_settings['feedTime'] / 60;?>">
			</div>
			<div class="form-group mb-3">
				<label>Default Graph Limit</label>
	                        <select class="form-control" id="limit-select" name="limit">
					  <?php 
					    foreach ($graphLimit as $key => $value) { 
						$graphLimitSplit = explode(":", $value);
						# Set to 1 week if nothing is set
						if (!isset($_SESSION[$sessionId]['limit'])) {
						  $_SESSION[$sessionId]['limit'] = $site_settings['defaultGraphLimit'];
						}
						if ($site_settings['defaultGraphLimit'] == $graphLimitSplit[0]) {
				    		  echo '<option id="'.$key.'" selected value="'.$graphLimitSplit[0].'">'.$graphLimitSplit[1].'</option>';
						} else {	
						  echo '<option id="'.$key.'" value="'.$graphLimitSplit[0].'">'.$graphLimitSplit[1].'</option>';
						}
					    }
					  ?>
	                        </select>
			</div>
			<div class="form-group mb-3">
				<label>Your Timezone</label>
	                        <select class="form-control" id="limit-select" name="limit">
					<?php 
					    $tzArray = timezone_identifiers_list();
					    foreach ($tzArray as $key => $value) { 
						if ($site_settings['tz'] == $value) {
				    		  echo '<option id="'.$key.'" selected value="'.$value.'">'.$value.'</option>';
						} else {	
						  echo '<option id="'.$key.'" value="'.$value.'">'.$value.'</option>';
						}

					    }
					  ?>
	                        </select>
			</div>
			<div class="form-group mb-3">
				<label>Username</label>
				<input type="email" id="example-email" name="username" class="form-control" placeholder="Email" value="<?PHP echo $site_settings['username'];?>">
				
			</div>
			<div class="form-group mb-3">
				<label for="password">Password</label>
				<input type="password" id="password" name="password" class="form-control" value="">
			</div>
			<div class="form-group mb-3">

			</div>

                </div>
            </div>
        </div>
	<?php } ?>
        <!-- /Right-bar -->

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
        
        <!-- third party js -->
	<script src="assets/js/vendor/Chart.bundle.min.js"></script>
	<script src="assets/js/vendor/chartjs-plugin-annotation.min.js"></script>
	<script src="assets/js/vendor/chartjs-plugin-zoom.min.js"></script>
	<script src="assets/js/vendor/jquery-ui.min.js"></script>
        <script src="assets/js/vendor/fullcalendar.min.js"></script>
        <script src="assets/js/vendor/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="assets/js/vendor/jquery-jvectormap-world-mill-en.js"></script>
        <!-- third party js ends -->
	<!-- Custom javascript -->
<script>
 $('#calendar').fullCalendar({ 
	eventClick: function(calEvent, jsEvent, view) {
	 	alert('Note: ' + calEvent.note);
	},
	eventSources: [ 
	{ url: 'api/calendar.php',} 
] });
</script>
	<script>
	$(document).ready(function(){
		function getHiddenProp() {
			var prefixes = ['webkit', 'moz', 'ms', 'o'];
			// if 'hidden' is natively supported just return it
			if ('hidden' in document) return 'hidden';

			// otherwise loop over all the known prefixes until we find one
			for (var i = 0; i < prefixes.length; i++) {
				if ((prefixes[i] + 'Hidden') in document)
					return prefixes[i] + 'Hidden';
			}
			// otherwise it's not supported
			return null;
		}
		function isHidden() {
			var prop = getHiddenProp();
			if (!prop) return false;
			return document[prop];
		}

			function captureCharts() {
                                    $('.card-body #chart-title').each(function(index) {
					    var metricName = $(this).html();
					    var metricColor = $(this).attr("color");
                                            var splitName = metricName.split(' ');
                                            var realName = splitName[0];
                                            $.get(`api/chartData.php?check=${realName}&limit=<?php echo $_SESSION[$sessionId]['limit'];?>`, function (data) {
                                                var jsonData = JSON.parse(data);
						if (jsonData.jsonarray) {
							var count = Object.keys(jsonData.jsonarray).length;
						} else {
							var count = 0;
						}
						if (count <= 3) {
						  $(document.getElementById("chart-"+realName)).html("<h6>There isn't enough chart information for the timerange you've selected. You can view the last recorded value above.</h6>");
						} else {
                                                  var labels = jsonData.jsonarray.map(function(e) {
                                                    return e.label;
                                                  });
                                                  var values = jsonData.jsonarray.map(function(e) {
                                                    return e.value;
						  });
						  if (jsonData.annoList) {
						     if (Object.keys(jsonData.annoList).length >= 1) {
							  var annoList = jsonData.annoList;
						     }
						  } else {
							  var annoList = [];
						  }
						  if (jsonData.annotations) {
						     if (Object.keys(jsonData.annotations).length >= 1) {
							  var anno = jsonData.annotations;
						     }
						  } else {
							  var anno = [];
						  }
						  var ctx = document.getElementById("line-chart-"+realName).getContext('2d');
						  var i;
						  if (annoList) {	  
							  $("[id^=annoLegend]").each(function(index) {
							  	  $(this).empty();
								  for (i = 0; i < annoList.length; i++) {
									var splitVal = annoList[i].split(":");
									$(this).append(`<i title="${splitVal[1]}" class="noti-icon ml-1 mdi ${splitVal[2]}" style="color: ${splitVal[0]}"></i>`);
								  }

							  });
						  }
                                                  var config = {
                                                       type: 'line',
						       options: {
							 scales: {
							   xAxes: [{
							     gridLines: {
							       display:false
							     },
							     ticks: {
							       display: false
	  						     },
							     type: 'time',
							     //time: {
  							     //  unit: 'hour',
							     //  round: 'hour'
						  	     //}
							   }],
							   yAxes: [{
							     gridLines: {
							       display: true
							     },
							   }]
							 },
                                                         animation: {
                                                           duration: 2 
                                                         },
		  				         tooltips: {
		  				  	   mode: 'index',
		  					   intersect: false,
							  },
							 annotation: {
								drawTime: "afterDatasetsDraw",
								annotations: anno,
							 },
                                                       },
		  				       hover: {
		  				 	 mode: 'nearest',
		  					 intersect: true
	  					       },
                                                       data: {
                                                          labels: labels,
                                                          datasets: [{
                                                                  fill: true,
                                                                  label: realName,
                                                                  data: values,
                                                                  pointRadius: 0,
                                                                  pointBackgroundColor: metricColor,
								  borderColor: metricColor,
                                                                  borderWidth:1 
                                                          }]
                                                       },
                                                  };
                                                  var chart = new Chart(ctx, config);
						};
                                            });
                                    });
                                  }
				  setInterval(function(){
					if(!isHidden()){
						captureCharts();
					}
				  }, 30000);
                                  captureCharts();
	});
	</script>
				<script type="text/javascript">
					$(window).on('load',function(){
						$('#ecobee').modal('show');
					});
				</script>
	<script src="assets/js/custom.js"></script>
	<!-- end custom javascript -->
                                                                                                                                                                                                           
    </body>
</html>
