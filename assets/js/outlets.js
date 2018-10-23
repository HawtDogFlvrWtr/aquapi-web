function captureOutlets() {
	                                    $.getJSON(`api/outletStatus.php`, function (json) {
						for(var i = 0; i < json.length; i++) {
							var obj = json[i];
							var module = obj.moduleId;
							var outlet = obj.portNumber;
							var outletStatus = obj.outletStatus;
							var outletName = `${module}-${outlet}`;
					    		if (outletStatus == 1) {
	                		                	$(`#${outletName}`).removeClass('text-secondary').addClass('text-success');
								$(`#outlet-icon-${outletName}`).prop("href",`outlets.php?outlet-change=${outletName}-0`);
								$(`#${outletName}`).prop('title','Outlet On');
							} else {
			                	                $(`#${outletName}`).removeClass('text-success').addClass('text-secondary');
								$(`#outlet-icon-${outletName}`).prop("href",`outlets.php?outlet-change=${outletName}-1`);
								$(`#${outletName}`).prop('title','Outlet Off');
							}

						}
					    });
                                  }
                                  setInterval(function(){
					var window_focus = true;
					$(window).focus(function() {
						window_focus = true;
					});
					$(window).blur(function() {
						window_focus = false;
					});
                                        if(window_focus == true){
                                             captureOutlets();
                                        }
                                  }, 2000)
                                  captureOutlets();
