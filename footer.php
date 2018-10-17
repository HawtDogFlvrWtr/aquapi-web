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
                <hr class="mt-0" />
                <h5 class="pl-3">Basic Settings</h5>
                <hr class="mb-0" />

                <div class="p-3">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="notifications-check" checked>
                        <label class="custom-control-label" for="notifications-check">Notifications</label>
                    </div>

                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="api-access-check">
                        <label class="custom-control-label" for="api-access-check">API Access</label>
                    </div>

                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="auto-updates-check" checked>
                        <label class="custom-control-label" for="auto-updates-check">Auto Updates</label>
                    </div>

                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="online-status-check" checked>
                        <label class="custom-control-label" for="online-status-check">Online Status</label>
                    </div>

                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="auto-payout-check">
                        <label class="custom-control-label" for="auto-payout-check">Auto Payout</label>
                    </div>

                </div>


                <!-- Timeline -->
                <hr class="mt-0" />
                <h5 class="pl-3">Recent Activity</h5>
                <hr class="mb-0" />
                <div class="pl-2 pr-2">
                    <div class="timeline-alt">
                        <div class="timeline-item">
                            <i class="mdi mdi-upload bg-info-lighten text-info timeline-icon"></i>
                            <div class="timeline-item-info">
                                <a href="#" class="text-info font-weight-bold mb-1 d-block">You sold an item</a>
                                <small>Paul Burgess just purchased “Hyper - Admin Dashboard”!</small>
                                <p>
                                    <small class="text-muted">5 minutes ago</small>
                                </p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <i class="mdi mdi-airplane bg-primary-lighten text-primary timeline-icon"></i>
                            <div class="timeline-item-info">
                                <a href="#" class="text-primary font-weight-bold mb-1 d-block">Product on the Bootstrap Market</a>
                                <small>Dave Gamache added
                                    <span class="font-weight-bold">Admin Dashboard</span>
                                </small>
                                <p>
                                    <small class="text-muted">30 minutes ago</small>
                                </p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <i class="mdi mdi-microphone bg-info-lighten text-info timeline-icon"></i>
                            <div class="timeline-item-info">
                                <a href="#" class="text-info font-weight-bold mb-1 d-block">Robert Delaney</a>
                                <small>Send you message
                                    <span class="font-weight-bold">"Are you there?"</span>
                                </small>
                                <p>
                                    <small class="text-muted">2 hours ago</small>
                                </p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <i class="mdi mdi-upload bg-primary-lighten text-primary timeline-icon"></i>
                            <div class="timeline-item-info">
                                <a href="#" class="text-primary font-weight-bold mb-1 d-block">Audrey Tobey</a>
                                <small>Uploaded a photo
                                    <span class="font-weight-bold">"Error.jpg"</span>
                                </small>
                                <p>
                                    <small class="text-muted">14 hours ago</small>
                                </p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <i class="mdi mdi-upload bg-info-lighten text-info timeline-icon"></i>
                            <div class="timeline-item-info">
                                <a href="#" class="text-info font-weight-bold mb-1 d-block">You sold an item</a>
                                <small>Paul Burgess just purchased “Hyper - Admin Dashboard”!</small>
                                <p>
                                    <small class="text-muted">1 day ago</small>
                                </p>
                            </div>
                        </div>

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
        <script src="assets/js/vendor/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="assets/js/vendor/jquery-jvectormap-world-mill-en.js"></script>
        <!-- third party js ends -->
	<!-- Custom javascript -->
	<script>
	$(document).ready(function(){
	  var window_focus = true;
	  $(window).focus(function() {
	    window_focus = true;
	  });
	  $(window).blur(function() {
	    window_focus = false;
	  });


                                  function captureCharts() {
                                    $('.card-body #chart-title').each(function(index) {
                                            var metricName = $(this).html();
                                            var splitName = metricName.split(' ');
                                            var realName = splitName[0];
                                            $.get(`api/chartData.php?check=${realName}&limit=<?php echo $_SESSION[$sessionId]['limit'];?>`, function (data) {
                                                var jsonData = JSON.parse(data);
						if (jsonData.jsonarray) {
							var count = Object.keys(jsonData.jsonarray).length;
						} else {
							var count = 0;
						}
						//console.log(`${realName}` + count);
						if (count <= 3) {
						  $(document.getElementById("chart-"+realName)).html("<h6>There isn't enough chart information for the timerange you've selected. You can view the last recorded value above.</h6>");
						} else {
                                                  var labels = jsonData.jsonarray.map(function(e) {
                                                    return e.label;
                                                  });
                                                  var values = jsonData.jsonarray.map(function(e) {
                                                    return e.value;
                                                  });
                                                  var ctx = document.getElementById("line-chart-"+realName).getContext('2d');
                                                  var config = {
                                                       type: 'line',
                                                       options: {
                                                         animation: {
                                                           duration: 0
                                                         },
		  				         tooltips: {
		  				  	   mode: 'index',
		  					   intersect: false,
		  				         },
                                                       },
		  				       hover: {
		  				 	 mode: 'nearest',
		  					 intersect: true
		  				       },
                                                       data: {
                                                          labels: labels,
                                                          datasets: [{
                                                                  fill: false,
                                                                  label: realName,
                                                                  data: values,
                                                                  pointRadius: 0,
                                                                  pointBackgroundColor: '#fa5c7c',
                                                                  borderColor: 'rgba(114, 124, 245, 1)',
                                                                  borderWidth: 1
                                                                  //backgroundColor: 'rgba(108, 117, 125, 0.5)'
                                                          }]
                                                       }
                                                  };
                                                  var chart = new Chart(ctx, config);
						};
                                            });
                                    });
                                  }
				  setInterval(function(){
					if(window_focus == true){
						captureCharts();
					}
				  }, 10000);
                                  captureCharts();
	});
	</script>
	<script src="assets/js/custom.js"></script>
	<!-- end custom javascript -->
                                                                                                                                                                                                           
    </body>
</html>
