<?php
include 'header.php';

# Adding a maintenance note
if (isset($_POST['maintenance-note-textarea']) && isset($_POST['maintenance-type'])) {
  $note = mysql_real_escape_string($_POST['maintenance-note-textarea']);
  $type = mysql_real_escape_string($_POST['maintenance-type']);
  $insertData = mysql_query("INSERT INTO `tankkeeping_entries` (`id`, `type_id`, `timestamp`, `note`) VALUES (NULL, '".intval($type)."', CURRENT_TIMESTAMP, '".$note."')");
  header("Location: index.php");
}

# Adding an existing single entry
if (isset($_POST['single-metric-value']) && isset($_POST['smetric-type'])) {
  foreach ($_POST['smetric-type'] as $selectedOption) {
    $value = mysql_real_escape_string($_POST['single-metric-value']);
    $type = mysql_real_escape_string($selectedOption);
    $insertData = mysql_query("INSERT INTO `parameter_entries` (`id`, `type_id`, `timestamp`, `value`) VALUES (NULL, '".intval($type)."', CURRENT_TIMESTAMP, '".$value."')");
  }
  header("Location: index.php");
}
$singleMetric = mysql_query("SELECT type_id FROM parameter_entries WHERE id IN (SELECT MIN(id) FROM parameter_entries GROUP BY type_id DESC) ORDER BY `parameter_entries`.`type_id` DESC");
$parameterList = mysql_query("SELECT id, eventName from parameter_types ORDER BY eventName");
$graphs = mysql_query("SELECT type_id FROM parameter_entries WHERE id IN (SELECT MIN(id) FROM parameter_entries GROUP BY type_id DESC) ORDER BY `parameter_entries`.`type_id` DESC");
$maintenanceItems = mysql_query("SELECT * FROM tankkeeping_types");
$maintenanceList = mysql_query("SELECT `type_id`, `timestamp`, `note`, `type`, `icon`, `text-color`  FROM `tankkeeping_entries`, `tankkeeping_types`  WHERE tankkeeping_entries.type_id = tankkeeping_types.id GROUP BY tankkeeping_entries.timestamp DESC");
?>                    
                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">Dashboard</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-9">
                                <div class="row">
 				<?php
				while($row = mysql_fetch_assoc($singleMetric)) {
				    $metricName = mysql_fetch_array(mysql_query("SELECT eventName from parameter_types where id=".$row['type_id']));
				?>
                                    <div class="col-lg-3">
                                        <div class="card widget-flat text-center">
                                            <div class="card-body">
						<div class="dropdown float-right">
	                                            <a id="<?php echo $metricName['eventName'];?>" href="#" class="card-drop" data-toggle="modal" data-target="#smetric<?php echo $metricName['eventName'];?>" aria-expanded="false">
        	                                        <i class="mdi mdi-plus"></i>
                	                            </a>
						</div>
                                                <h5 id="single-metric-title" class="text-muted font-weight-normal mt-0" title="Current tank temperature"><?php echo $metricName['eventName'] ?></h5>
                                                <p id="<?php echo $metricName['eventName'] ?>-single-metric" class="display-4 mt-1 mb-1"></p>
                                                <h5 class="mb-0 text-muted">
                                                    <span id="<?php echo $metricName['eventName'] ?>-single-metric-date" class="text-nowrap">Loading..</span>
                                                </h5>
                                            </div> <!-- end card-body-->
                                        </div> <!-- end card-->
                                    </div> <!-- end col-->
                                       	<div class="modal fade" id="smetric<?php echo $metricName['eventName'];?>" tabindex="40" role="dialog" aria-labelledby="smetric<?php echo $metricName['eventName'];?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
       	                                        <div class="modal-content">
               	                                    <div class="modal-header">
                       	                                <h4 class="modal-title" id="smetric-<?php echo $row['id'];?>"><?php echo str_replace("_", " ", $metricName['eventName']);?></h4>
                               	                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                       	            </div>
                                               	    <div class="modal-body">
							<form action="index.php" method="post">
							  <div class="form-group mb-3">
                                                		<div class="form-group mb-3">
		                                                    <label>New value</label>
                		                                    <input type="text" id="<?php echo $metricName['eventName'];?>-single-metric-modal" name="single-metric-value" value="">
                                		                </div>
							    <input type="hidden" name="smetric-type[]" id="smetric-type" value="<?php echo $row['type_id'];?>">
							    <button id="submit" type="submit" class="btn btn-primary mt-2 mb-2">Submit</button>
							  </div>
							</form>
                                                    </div>
       	                                        </div><!-- /.modal-content -->
               	                            </div><!-- /.modal-dialog -->
                       	                </div><!-- /.modal -->
				<?php
				}
				?>
                                    <div class="col-lg-3">
                                        <div class="card widget-flat text-center">
                                            <div class="card-body">
                                                <a id="add-single-metric" href="" class="display-1 mdi mdi-plus mt-1 mb-3" data-toggle="modal" data-target="#add-metric" aria-expanded="false"></a>
                                            </div> <!-- end card-body-->
                                        </div> <!-- end card-->
                                    </div> <!-- end col-->
                                       	<div class="modal fade" id="add-metric" tabindex="40" role="dialog" aria-labelledby="add-metric" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
       	                                        <div class="modal-content">
               	                                    <div class="modal-header">
                       	                                <h4 class="modal-title" id="add-metric">Add Reading</h4>
                               	                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                       	            </div>
                                               	    <div class="modal-body">
							<form action="index.php" method="post">
							  <div class="form-group mb-3">
                                                		<div class="form-group mb-3">
								    <label>Reading type</label>
                                                			<select name="smetric-type[]" class="form-control select2 select2-multiple" multiple="multiple" data-toggle="select2">
                                                    				<option>Select</option>
									<?php while($row = mysql_fetch_assoc($parameterList)) {
										echo '<option value="'.$row['id'].'">'.$row['eventName'].'</option>';
									}?>
									</select>

		                                                    <label class="mt-1">New value</label>
                		                                    <input class="form-control" type="text" id="add-value" name="single-metric-value" value="">
                                		                </div>
							    <button id="submit" type="submit" class="btn btn-primary mt-2 mb-2">Submit</button>
							  </div>
							</form>
                                                    </div>
       	                                        </div><!-- /.modal-content -->
               	                            </div><!-- /.modal-dialog -->
                       	                </div><!-- /.modal -->
                        	</div>
	                        <!-- end row -->

                            </div> <!-- end col -->
                            <div class="col-xl-3">
                                <div class="card">
                                    <div class="card-body">
					<div class="dropdown float-right">
                                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
                                                <i class="mdi mdi-plus"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
						<?php
						  while($row = mysql_fetch_assoc($maintenanceItems)) {
						?>
                                                <a id="<?php echo $row['id'];?>" data-toggle="modal" data-target="#maint<?php echo $row['id'];?>" class="dropdown-item"><?php echo str_replace("_", " ", $row['type']);?></a>
						<?php
						  }
						?>
                                                <!-- item-->
                                            </div>
					    <?php
					      mysql_data_seek($maintenanceItems, 0);
					      while($row = mysql_fetch_assoc($maintenanceItems)) {
					    ?>
                                        	<div class="modal fade" id="maint<?php echo $row['id'];?>" tabindex="40" role="dialog" aria-labelledby="maint<?php echo $row['id'];?>" aria-hidden="true">
	                                            <div class="modal-dialog modal-dialog-centered">
        	                                        <div class="modal-content">
                	                                    <div class="modal-header">
                        	                                <h4 class="modal-title" id="maintenance-<?php echo $row['id'];?>"><?php echo str_replace("_", " ", $row['type']);?></h4>
                                	                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        	            </div>
                                                	    <div class="modal-body">
								<form action="index.php" method="post">
								  <div class="form-group mb-3">
								    <label for="maintenance-note-textarea">Note</label>
								    <textarea class="form-control" name="maintenance-note-textarea" id="maintenance-note-textarea" rows="5"></textarea>
								    <input type="hidden" name="maintenance-type" id="maintenance-type" value="<?php echo $row['id'];?>">
								    <button id="submit" type="submit" class="btn btn-primary mt-2 mb-2">Submit</button>
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

                                        <div class="slimscroll" style="min-height: 275px;">
                                            <div class="timeline-alt pb-0">
						<?php
						while($row = mysql_fetch_assoc($maintenanceList)) {
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
			    while($row = mysql_fetch_assoc($graphs)) {
				    $metricName = mysql_fetch_array(mysql_query("SELECT eventName from parameter_types where id=".$row['type_id']));
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
