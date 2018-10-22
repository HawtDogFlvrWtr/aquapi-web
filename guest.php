<?php
include 'header.php';

?>                    
                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
				    <div class="page-title-right">
					<form class="form-inline" action="guest.php" method="get">
	                                  <select class="form-control" id="limit-select" name="limit">
					  <?php 
					    foreach ($graphLimit as $key => $value) { 
						$graphLimitSplit = explode(":", $value);
						# Set to 1 week if nothing is set
						if (!isset($_GET['limit'])) {
						  $_GET['limit'] = "1-week";
						}
						if ($_GET['limit'] == $graphLimitSplit[0]) {
				    		  echo '<option id="'.$key.'" selected value="'.$graphLimitSplit[0].'">'.$graphLimitSplit[1].'</option>';
						} else {	
						  echo '<option id="'.$key.'" value="'.$graphLimitSplit[0].'">'.$graphLimitSplit[1].'</option>';
						}
					    }
					  ?>
	                                  </select>
					  <button type="submit" class="btn btn-primary ml-2"><i class="mdi mdi-autorenew"> </i></button>
					</form>
					
				    </div>
                                    <h4 class="page-title">Dashboard</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-9">
                                <div class="row">
 				<?php
				while($row = $singleMetric->fetch_assoc()) {
				    $query = $conn->query("SELECT eventName from parameter_types where id=".$row['type_id']);
				    $metricName = $query->fetch_array();
				?>
                                    <div class="col-lg-3">
                                        <div class="card widget-flat text-center">
                                            <div class="card-body">
                                                <h5 id="single-metric-title" class="text-muted font-weight-normal mt-0" title="Current tank temperature"><?php echo $metricName['eventName'] ?></h5>
                                                <p id="<?php echo $metricName['eventName'] ?>-single-metric" class="display-4 mt-1 mb-1"></p>
                                                <h5 class="mb-0 text-muted">
                                                    <span id="<?php echo $metricName['eventName'] ?>-single-metric-date" class="text-nowrap">Loading..</span>
                                                </h5>
                                            </div> <!-- end card-body-->
                                        </div> <!-- end card-->
                                    </div> <!-- end col-->

				<?php
				}
				?>
                        	</div>
	                        <!-- end row -->

                            </div> <!-- end col -->
                            <div class="col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title mb-2">Recent Maintenance</h4>

                                        <div class="slimscroll" style="max-height: 275px;">
                                            <div class="timeline-alt pb-0">
						<?php
						while($row = $maintenanceList->fetch_assoc()) {
						?>
                                                <div class="timeline-item">
                                                    <i title="Add Maintenance Item" class="mdi <?php echo $row['icon']; ?> bg-<?php echo $row['text-color']; ?>-lighten text-<?php echo $row['text-color']; ?> timeline-icon"></i>
                                                    <div class="timeline-item-info">
                                                        <a href="#" class="text-<?php echo $row['text-color']; ?> font-weight-bold mb-1 d-block"><?php echo str_replace("_", " ", $row['type']);?></a>
                                                        <small><?php echo $row['note'];?></small>
                                                        <p>
                                                            <small class="text-muted"> <?php echo correctTZ($row['timestamp'], $site_settings['tz']); ?></small>
                                                        </p>
                                                    </div>
                                                </div>
						<?php
						}
						?>
                                            </div>
                                            <!-- end timeline -->
                                        </div> <!-- end slimscroll -->
                                    </div>
                                    <!-- end card-body -->
                                </div>
                                <!-- end card-->
			    </div>
			    <!--endcol -->
                        </div>
                        <!-- end row -->

                        <div class="row">
 			    <?php
			    while($row = $graphs->fetch_assoc()) {
				    $query = $conn->query("SELECT eventName from parameter_types where id=".$row['type_id']);
				    $metricName = $query->fetch_array();
			    ?>
                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 id="chart-title" class="header-title mb-4"><?php echo $metricName['eventName'];?> Chart</h4>
                                        <div class="mt-3 chartjs-chart" style="min-height: 150px;">
                                            <canvas id="line-chart-<?php echo $metricName['eventName'];?>"></canvas>
                                        </div>
                                                                                                                                                                                                                   
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
			    <?php
			    }
			    ?>
                        </div>
                        <!-- end row -->
                    </div> <!-- container -->
			<?php
			  include 'footer.php';
			?>
