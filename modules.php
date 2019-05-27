<?php
include 'header.php';
$parameterList2 = $conn->query("SELECT id, eventName from parameter_types ORDER BY eventName");
?>                    
                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">Modules</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
 			<?php
				while($row = $moduleEntries->fetch_assoc()) {
					$portInfo = $conn->query("SELECT * FROM outlet_entries WHERE moduleId = ".$row['id']);
?>
				    <h4 class="font-weight-normal mt-0"><?php echo $row['moduleTypeName']?> (<?php echo $row['moduleSerial']?>) Firmware: <?php echo $row['moduleFirmware'];?> IP: <a class="" target="_blank" href="http://<?php echo $row['moduleAddress'];?>"><?php echo $row['moduleAddress'];?></a></h4>
			    <div class="row">
 				<?php
				while($portRow = $portInfo->fetch_assoc()) {
					$portDetails = $conn->query("SELECT * FROM outlet_types WHERE id = ".$portRow['outletType']);
					$portDetailsAssoc = $portDetails->fetch_assoc();
					if ($portDetailsAssoc['id'] != 0) {
						$icon = $portDetailsAssoc['typeIcon'];
					} else {
						$icon = $portRow['outletIcon'];
					}
					if ($portRow['outletNote'] == "") {
						if ($portDetailsAssoc['outletType'] != "") {
							$note = $portDetailsAssoc['outletType'];
						} else {
							$note = "None";
						}
					} else {
						$note = $portRow['outletNote'];
					}

				?>
                                    <div class="col-lg-2">
				    <div class="card widget-flat text-center">
					    <div class="card-body">
						<div class="text-muted dropdown float-right">
						    <a id="<?php echo $portRow['portNumber'];?>" href="#" class="card-drop" data-toggle="modal" data-target="#outlet<?php echo $portRow['moduleId'];?>-port<?php echo $portRow['portNumber'];?>" aria-expanded="false">
        	                                        <i title="Configure Outlet" class="mdi mdi-settings"></i>
                	                            </a>
						</div>
						<div class="mt-3">
						<p id="outlet-divider<?php echo $portRow['portNumber'] ?>" class="display-4 mt-1 mb-1"></p>
						<a id="outlet-icon-<?php echo $portRow['moduleId'];?>-<?php echo $portRow['portNumber'] ?>" href="#">
							<i title="updating..." id="<?php echo $portRow['moduleId'];?>-<?php echo $portRow['portNumber'] ?>" class="text-secondary display-2 mdi <?php echo $icon;?>"></i>
						
						</a>
						</div>
							<h6 class="text-nowrap"><?php echo $note;?></h6>
                                            </div> <!-- end card-body-->
                                        </div> <!-- end card-->
				    </div> <!-- end col-->
					<div class="modal fade" id="outlet<?php echo $portRow['moduleId'];?>-port<?php echo $portRow['portNumber'];?>" tabindex="40" role="dialog" aria-labelledby="outlet<?php echo $portRow['moduleId'];?>-port<?php echo $portRow['portNumber'];?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
       	                                        <div class="modal-content">
               	                                    <div class="modal-header">
						    <h4 class="modal-title" id="outlet-<?php echo $portRow['portNumber'];?>">Module <?php echo $portRow['moduleId'];?> Outlet <?php echo $portRow['portNumber'];?> Settings</h4>
                               	                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                       	            </div>
                                               	    <div class="modal-body">
							<form action="modules.php" method="post">
							  <div class="form-group mb-3">
								<div class="form-group mb-3">
									<div class="card">
									<div class="card-body bg-light">
									<label for="example-select">IF THE <i class="mdi mdi-arrow-down"></i> </label>
                                                			<select id="triggerParam" name="triggerParam" class="form-control">
                                                    				<option></option>
									<?php 
										mysqli_data_seek($parameterListModule, 0);
										while($pList = $parameterListModule->fetch_assoc()) {
											if ($pList['id'] == $portRow['outletTriggerParam']) {
												echo '<option selected value="'.$pList['id'].'">'.$pList['eventName'].'</option>';
											} else {
												echo '<option value="'.$pList['id'].'">'.$pList['eventName'].'</option>';
											}
										}
									?>
									</select>
                                                			<select id="triggerTest" name="triggerTest" class="form-control">
										<option></option>
									<?php	
										$possibleTests = array(">:Is greater than", "<:Is less than", "=:Equals", ">=:Is greater than or equal to", "<=:Is less than or equal to");
										foreach ($possibleTests as $test) {
											$testSplit = explode(":", $test);
											if ($testSplit[0] == $portRow['outletTriggerTest']) {
												echo '<option selected value="'.$testSplit[0].'">'.$testSplit[1].'</option>';
											} else {
												echo '<option value="'.$testSplit[0].'">'.$testSplit[1].'</option>';
											}
										}
									?>
									</select>
								    <input class="mb-1 form-control" type="text" id="triggerValue" name="triggerValue" value="<?php echo $portRow['outletTriggerValue'];?>">

									<label for="example-select">TURN THE OUTLET <i class="mdi mdi-arrow-down"></i></label>
                                                			<select id="triggerCommand" name="triggerCommand" class="form-control">
										<option></option>
									<?php	
										$possibleCommands = array("On", "Off",);
										foreach ($possibleCommands as $key => $command) {
											if ($command == $portRow['outletTriggerCommand']) {
												echo '<option selected value="'.$command.'">'.$command.'</option>';
											} else {
												echo '<option value="'.$command.'">'.$command.'</option>';
											}
										}
									?>
									</select>
									</div>
									</div>
									<label for="example-select">Outlet Type</label>
                                                			<select id="outletType" name="outletType" class="form-control">
										<option></option>
									<?php
										$outletTypes = $conn->query("SELECT id,outletType FROM outlet_types");	
										while ($oRow = $outletTypes->fetch_assoc()) {
											if ($oRow['id'] == $portRow['outletType']) {
												echo '<option selected value="'.$oRow['id'].'">'.$oRow['outletType'].'</option>';
											} else {
												echo '<option value="'.$oRow['id'].'">'.$oRow['outletType'].'</option>';
											}
										}
									?>
									</select>
		                                                        <label class="mt-1" title="Note that will appear below the outlet">Outlet Note</label>
									<input class="form-control" type="text" id="outletNote" name="outletNote" value="<?php echo $portRow['outletNote'];?>">
		                                                        <label class="mt-1" title="Turn this device off during feeding?">Off During Feeding</label>
                                                			<select id="feedCommand" name="feedCommand" class="form-control">
										<option></option>
									<?php	
										$possibleCommands = array("No", "Yes",);
										foreach ($possibleCommands as $key => $command) {
											
											if ($key == $portRow['offAtFeeding']) {
												echo '<option selected value="'.$key.'">'.$command.'</option>';
											} else {
												echo '<option value="'.$key.'">'.$command.'</option>';
											}
										}
									?>
									</select>
		                                                    <label class="mt-1" title="Turn this device off during cleaning?">Off During Cleaning</label>
                                                			<select id="cleanCommand" name="cleanCommand" class="form-control">
										<option></option>
									<?php	
										$possibleCommands = array("No", "Yes",);
										foreach ($possibleCommands as $key => $command) {
											
											if ($key == $portRow['offAtCleaning']) {
												echo '<option selected value="'.$key.'">'.$command.'</option>';
											} else {
												echo '<option value="'.$key.'">'.$command.'</option>';
											}
										}
									?>
									</select>
		                                                    <label class="mt-1" title="Ensure the outlet is on unless specified above">Always On</label>
                                                			<select id="AOCommand" name="AOCommand" class="form-control">
										<option></option>
									<?php	
										$possibleCommands = array("No", "Yes",);
										foreach ($possibleCommands as $key => $command) {
											
											if ($key == $portRow['alwaysOn']) {
												echo '<option selected value="'.$key.'">'.$command.'</option>';
											} else {
												echo '<option value="'.$key.'">'.$command.'</option>';
											}
										}
									?>
									</select>
                                		                </div>
							    <input type="hidden" name="outletId" id="outletId" value="<?php echo $portRow['moduleId']?>-<?php echo $portRow['portNumber'];?>">
							    <button id="submit" type="submit" class="float-right btn btn-secondary mt-2 mb-2">Submit</button>
							  </div>
							</form>
                                                    </div>
       	                                        </div><!-- /.modal-content -->
               	                            </div><!-- /.modal-dialog -->
                       	                </div><!-- /.modal -->
				<?php } ?>
				</div> <!--  end row -->
			<?php
				}
			?>
			</div>

                            </div> <!-- end col -->
                        <!-- end row -->

                    </div> <!-- container -->
			<?php
			  include 'footer.php';
			?>
		    <script src="assets/js/outlets.js"></script>

