<?php
include 'header.php';
?>                    
                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">Outlets</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
 			<?php
				while($row = $moduleEntries->fetch_assoc()) {
					$portInfo = $conn->query("SELECT * FROM outlet_entries WHERE moduleId = ".$row['id']);
			?>
			<div id="<?php echo $row['id'];?>" class="row">
                            <div class="col-5">
			    <div class="card bg-<?php echo $row['moduleColor'];?> bg-warning widget-flat">
				    <div class="card-body">
					    <h5 class="text-white font-weight-normal mt-0"><?php echo $row['moduleTypeName']?> (<?php echo $row['moduleSerial']?>)</h5>
					    <p class="mb-0 text-white">
						<strong class="font-13 text-nowrap">Firmware:</strong> <?php echo $row['moduleFirmware'];?>
						<strong class="font-13 text-nowrap">IP:</strong> <?php echo $row['moduleAddress'];?>
					    </p>
				    </div>
                                </div>
                            </div>
			</div>
				<div class="row">
 				<?php
				while($portRow = $portInfo->fetch_assoc()) {
					if ($portRow['outletStatus'] == 0) {
						$command = "on";
					} else {
						$command = "off";
					}

				?>
                                    <div class="col-lg-2">
				    <div class="card widget-flat text-center">
					    <div class="card-body">
<!--						<h5 id="outlet-title" class="text-muted font-weight-normal mt-0" title="Outlet port <?php echo $portRow['portNumber'] ?>">Outlet <?php echo $portRow['portNumber'] ?></h5>-->
						<div class="text-muted dropdown float-right">
						    <a id="<?php echo $portRow['portNumber'];?>" href="#" class="card-drop" data-toggle="modal" data-target="#outlet<?php echo $portRow['moduleId'];?>-port<?php echo $portRow['portNumber'];?>" aria-expanded="false">
        	                                        <i title="Configure Outlet" class="mdi mdi-settings"></i>
                	                            </a>
						</div>
						<p id="outlet-divider<?php echo $portRow['portNumber'] ?>" class="display-4 mt-1 mb-1"></p>
						<a id="outlet-icon-<?php echo $portRow['moduleId'];?>-<?php echo $portRow['portNumber'] ?>" href="updating...">
							<i title="updating..." id="<?php echo $portRow['moduleId'];?>-<?php echo $portRow['portNumber'] ?>" class="text-secondary display-1 mdi <?php echo $portRow['outletIcon'];?>"></i>
						</a>
<!--
						<p id="outlet-status-<?php echo $portRow['portNumber'] ?>" class="text-center">
							<input type="checkbox" id="outlet<?php echo $portRow['moduleId'];?>-port<?php echo $portRow['portNumber'];?>" checked="" data-switch="success">
							<label for="outlet<?php echo $portRow['moduleId'];?>-port<?php echo $portRow['portNumber'];?>" data-on-label="On" data-off-label="Off"></label>
						</p>
-->
                                            </div> <!-- end card-body-->
                                        </div> <!-- end card-->
				    </div> <!-- end col-->
					<div class="modal fade" id="outlet<?php echo $portRow['moduleId'];?>-port<?php echo $portRow['portNumber'];?>" tabindex="40" role="dialog" aria-labelledby="outlet<?php echo $portRow['moduleId'];?>-port<?php echo $portRow['portNumber'];?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-sm">
       	                                        <div class="modal-content">
               	                                    <div class="modal-header">
                       	                                <h4 class="modal-title" id="outlet-<?php echo $portRow['portNumber'];?>">Port Settings</h4>
                               	                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                       	            </div>
                                               	    <div class="modal-body">
							<form action="outlets.php" method="post">
							  <div class="form-group mb-3">
                                                		<div class="form-group mb-3">
		                                                    <label>New value</label>
                		                                    <input type="text" id="<?php echo $portRow['portNumber'];?>-single-metric-modal" name="single-metric-value" value="">
                                		                </div>
							    <input type="hidden" name="outlet-port" id="outlet-port" value="<?php echo $portRow['moduleId']?>-<?php echo $portRow['portNumber'];?>">
							    <button id="submit" type="submit" class="btn btn-primary mt-2 mb-2">Submit</button>
							  </div>
							</form>
                                                    </div>
       	                                        </div><!-- /.modal-content -->
               	                            </div><!-- /.modal-dialog -->
                       	                </div><!-- /.modal -->
				<?php } ?>
				</div> <!-- end row -->
			<?php
				}
			?>

                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                    </div> <!-- container -->
			<?php
			  include 'footer.php';
			?>
