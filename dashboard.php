<?php
include 'header.php';
$wcQuery = $conn->query("SELECT timestamp FROM tankkeeping_entries WHERE type_id = 2 ORDER BY id DESC LIMIT 1");
$wcRecord = $wcQuery->fetch_assoc();
if (!$wcRecord['timestamp']) {
	$wcRecord = 'No water change has been performed yet.';
} else {
	$wcRecord = correctTZ($wcRecord['timestamp'], $site_settings['tz']);
}
?>                    
                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
				    <div class="page-title-right">
					<form class="form-inline" action="dashboard.php" method="post">
	                                  <select class="form-control" id="limit-select" name="limit">
					  <?php 
					    foreach ($graphLimit as $key => $value) { 
						$graphLimitSplit = explode(":", $value);
						# Set to 1 week if nothing is set
						if (!isset($_SESSION[$sessionId]['limit'])) {
						  $_SESSION[$sessionId]['limit'] = $site_settings['defaultGraphLimit'];
						}
						if ($_SESSION[$sessionId]['limit'] == $graphLimitSplit[0]) {
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
					if ($row['max'] > 0) {
						$maxVal = 'data-bts-max="'.$row['max'].'"';
					} else {
						$maxVal = '';
					}	
				?>
                                    <div class="col-lg-2">
                                        <div class="card widget-flat text-center">
                                            <div class="card-body">
						<div class="dropdown float-right">
						    <a id="<?php echo $row['eventName'];?>" href="#" class="card-drop" data-toggle="modal" data-target="#smetric<?php echo $row['eventName'];?>" aria-expanded="false">
        	                                        <i class="mdi mdi-plus"></i>
                	                            </a>
						</div>
                                                <h5 id="single-metric-title" class="text-muted font-weight-normal mt-3" title="Current tank temperature"><?php echo str_replace("_", " ", $row['eventName']) ?></h5>
						<h1 style="color:<?php echo $row['lineColor'];?>" id="<?php echo $row['eventName'] ?>-single-metric" class="text-nowrap mt-1 mb-1"></h1>
                                                <h5 class="mb-0 text-muted">
                                                    <span id="<?php echo $row['eventName'] ?>-single-metric-date" class="text-nowrap">Loading..</span>
                                                </h5>
                                            </div> <!-- end card-body-->
                                        </div> <!-- end card-->
                                    </div> <!-- end col-->
                                       	<div class="modal fade" id="smetric<?php echo $row['eventName'];?>" tabindex="40" role="dialog" aria-labelledby="smetric<?php echo $row['eventName'];?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-sm">
       	                                        <div class="modal-content">
               	                                    <div class="modal-header">
                       	                                <h4 class="modal-title" id="smetric-<?php echo $row['type_id'];?>"><?php echo str_replace("_", " ", $row['eventName']);?></h4>
                               	                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                       	            </div>
                                               	    <div class="modal-body">
							<form action="dashboard.php" method="post">
							  <div class="form-group mb-3">
								<div class="form-group mb-3">
								    <label>Date/Time</label>
								    <input class="form-control date" type="text" id="dateInput<?php echo $row['type_id'];?>" name="single-metric-date" data-toggle="date-picker" data-single-date-picker="true" data-time-picker="true" data-auto-update-input="true" data-show-dropdowns="true" data-time-picker-24-hour="false" data-locale='{"format":"YYYY-MM-DD HH:mm"}' data-start-date="<?php echo $calendarDate;?>">

								</div>
								<div class="form-group mb-3">
		                                                    <label>New value</label>
								    <input <?php echo $maxVal;?> type="text" id="<?php echo $row['eventName'];?>-single-metric-modal" name="single-metric-value" value="">
                                		                </div>
							    <input type="hidden" name="smetric-type[]" id="smetric-type<?php echo $row['type_id'];?>" value="<?php echo $row['type_id'];?>">
							    <button id="submitsmetric<?php echo $row['type_id'];?>" type="submit" class="btn btn-primary mt-2 mb-2">Submit</button>
							  </div>
							</form>
                                                    </div>
       	                                        </div><!-- /.modal-content -->
               	                            </div><!-- /.modal-dialog -->
                       	                </div><!-- /.modal -->
				<?php
				}
				?>
                                    <div class="col-lg-2">
                                        <div class="widget-flat text-center">
                                            <div class="card-body">
                                                <a id="add-single-metric" href="" class="display-1 mdi mdi-plus mt-1 mb-3" data-toggle="modal" data-target="#add-metric" aria-expanded="false"></a>
                                            </div> <!-- end card-body-->
                                        </div> <!-- end card-->
                                    </div> <!-- end col-->
                                       	<div class="modal fade" id="add-metric" tabindex="40" role="dialog" aria-labelledby="add-metric" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-sm">
       	                                        <div class="modal-content">
               	                                    <div class="modal-header">
                       	                                <h4 class="modal-title" id="add-metric">Add Reading</h4>
                               	                        <button id="add-single-entry" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                       	            </div>
                                               	    <div class="modal-body">
							<form action="dashboard.php" method="post">
							  <div class="form-group mb-3">
								<div class="form-group mb-3">
								    <label>Date/Time</label>
								    <input class="form-control date" type="text" name="single-metric-date" data-toggle="date-picker" data-single-date-picker="true" data-time-picker="true" data-auto-update-input="true" data-show-dropdowns="true" data-time-picker-24-hour="true" data-locale='{"format":"YYYY-MM-DD HH:mm"}' data-start-date="<?php echo $calendarDate;?>">


								</div>
                                                		<div class="form-group mb-3">
								    <label>Reading type</label>
                                                			<select name="smetric-type[]" class="input-sm form-control select2 select2-multiple" multiple="multiple" data-toggle="select2">
                                                    				<option>Select</option>
									<?php while($row = $parameterList->fetch_assoc()) {
										echo '<option value="'.$row['id'].'">'.$row['eventName'].'</option>';
									}?>
									</select>

		                                                    <label class="mt-1">New value</label>
                		                                    <input class="form-control" type="text" id="add-value" name="single-metric-value" value="">
                                		                </div>
							    <button id="submitsmetricAdd<?php echo $row['eventName'];?>" type="submit" class="btn btn-primary mt-2 mb-2">Submit</button>
							  </div>
							</form>
                                                    </div>
       	                                        </div><!-- /.modal-content -->
               	                            </div><!-- /.modal-dialog -->
                       	                </div><!-- /.modal -->
                        	</div>
	                        <!-- end row -->
                           <div class="row">
 			    <?php
			      while($row = $graphs->fetch_assoc()) {
			    ?>
			    <div id="col-<?php echo $row['eventName'];?>" class="col-xl-6">
                                <div class="card">
				    <div class="card-body">
					<h4 color="<?php echo $row['lineColor'];?>" id="chart-title" class="header-title"><?php echo $row['eventName'];?> Chart</h4>
                                        <div id="chart-<?php echo $row['eventName'];?>" class="chartjs-chart" style="min-height: 150px;">
                                            <canvas id="line-chart-<?php echo $row['eventName'];?>"></canvas>
					</div>
					<div id="annoLegend-<?php echo $row['eventName'];?>" class="float-left"></div>
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
			    <?php
			    }
			    ?>
			    </div>

                            </div> <!-- end col -->
                            <div class="col-xl-3">
                                <div class="card">
				    <div class="card-body">
					<h4 id="devices-title" class="header-title">Running Devices</h4>
						<ul class="list-unstyled mb-0">
						 <li id="device-list">
						 </li>
						<ul>
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                                <div class="card">
				    <div class="card-body">
					<h4 id="WC-title" class="header-title">Last Water change</h4>
					<span class="text-warning"><?php echo $wcRecord;?></span>
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                                <div class="card">
                                    <div class="card-body">
					<div class="dropdown float-right">
                                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
                                                <i class="mdi mdi-plus"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
						<?php
						  while($row = $maintenanceItems->fetch_assoc()) {
						?>
                                                <a id="<?php echo $row['id'];?>" data-toggle="modal" data-target="#maint<?php echo $row['id'];?>" class="dropdown-item"><?php echo str_replace("_", " ", $row['type']);?></a>
						<?php
						  }
						?>
                                                <!-- item-->
                                            </div>
					    <?php
					      mysqli_data_seek($maintenanceItems, 0);
					      while($row = $maintenanceItems->fetch_assoc()) {
					    ?>
                                        	<div class="modal fade" id="maint<?php echo $row['id'];?>" tabindex="40" role="dialog" aria-labelledby="maint<?php echo $row['id'];?>" aria-hidden="true">
	                                            <div class="modal-dialog modal-dialog-centered">
        	                                        <div class="modal-content">
                	                                    <div class="modal-header">
                        	                                <h4 class="modal-title" id="maintenance-<?php echo $row['id'];?>"><?php echo str_replace("_", " ", $row['type']);?></h4>
                                	                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        	            </div>
                                                	    <div class="modal-body">
								<form action="dashboard.php" method="post">
								  <div class="form-group mb-3">
								    <label>Date/Time</label>
								    <input class="form-control date" type="text" name="maintenance-date" data-toggle="date-picker" data-single-date-picker="true" data-time-picker="true" data-auto-update-input="true" data-show-dropdowns="true" data-time-picker-24-hour="true" data-locale='{"format":"YYYY-MM-DD HH:mm"}' data-start-date="<?php echo $calendarDate;?>">

								  </div>
								  <div class="form-group mb-3">
								    <label for="maintenance-note-textarea">Note</label>
								    <textarea class="form-control" name="maintenance-note-textarea" id="maintenance-note-textarea-<?php echo $row['id'];?>" rows="5"></textarea>
								    <input type="hidden" name="maintenance-type" id="maintenance-type<?php echo $row['id'];?>" value="<?php echo $row['id'];?>">
								    <button id="submitMaint<?php echo $row['id'];?>" type="submit" class="btn btn-primary mt-2 mb-2">Submit</button>
								  </div>
								</form>
	                                                    </div>
        	                                        </div><!-- /.modal-content -->
                	                            </div><!-- /.modal-dialog -->
                        	                </div><!-- /.modal -->
						
					    <?php
					      }
					    ?>
                                        </div>
                                        <h4 class="header-title mb-2">Recent Maintenance</h4>

                                        <div class="slimscroll" style="max-height: 700px;">
                                            <div class="timeline-alt pb-0">
						<?php
						while($row = $maintenanceList->fetch_assoc()) {
						?>
                                                <div class="timeline-item">
                                                    <i title="Add Maintenance Item" class="mdi <?php echo $row['icon']; ?> bg-<?php echo $row['word_color']; ?>-lighten text-<?php echo $row['word_color']; ?> timeline-icon"></i>
                                                    <div class="timeline-item-info">
                                                        <h5 class="text-<?php echo $row['text-color']; ?> font-weight-bold mb-1 d-block"><?php echo str_replace("_", " ", $row['type']);?></h5>
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
                    </div> <!-- container -->
			<?php
			  include 'footer.php';
			?>
