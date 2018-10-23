		$(document).ready(function() {
                                  function captureCards() {
	                                    $.getJSON(`api/singleValue.php?check`, function (json) {
						for(var i = 0; i < json.length; i++) {
							var obj = json[i];
							var timestamp = obj.timestamp;
							var value = obj.value;
							var eventName = obj.eventName;
							var decimal = obj.decimals;
							var step = obj.step;
        	        	                      $(`#${eventName}-single-metric`).text(value);
					  	      $(`#${eventName}-single-metric-modal`).TouchSpin({
							    step: step,
							    decimals: decimal,
							    boostat: 5,
							    maxboostedstep: 10
					   	      });
					    	  // Don't refresh modal if we're in it
						      if(!$(`#smetric${eventName}`).hasClass('show')){
        	        	                        $(`#${eventName}-single-metric-modal`).val(value);
						      }
                        		              $(`#${eventName}-single-metric-date`).text(timestamp);
                                	    	};
				    	});
                                  }
                                  setInterval(captureCards, 10000);
                                  captureCards();

                                  function captureDevices() {
				    $('.list-unstyled li').each(function(index) {
					    var metricName = $(this).children('a').children('i').attr('id');
					    if (metricName == 'Temperature') {
						var apiHit = 'check';
					    } else {
						var apiHit = 'devices';
					    }
	                                    $.get(`api/singleValue.php?${apiHit}=${metricName}`, function (data) {
					      if (metricName == 'Temperature') {
						json = JSON.parse(data);
						var temperature = json[0].value
						$(`#${metricName}`).text(temperature).append("&#176;"); 
					      }
					      if (metricName == 'light') {
						if (data == 1) {
	                	                      	$(`#${metricName}`).css({ "color": "#ffbc00"});
						} else {
	                	                      	$(`#${metricName}`).css({ "color": "#313a46"});
						}
					      }
					      if (metricName == 'pump') {
						if (data == 1) {
	                	                      	$(`#${metricName}`).css({ "color": "#0acf97"});
						} else {
	                	                      	$(`#${metricName}`).css({ "color": "#fa5c7c"});
						}
					      }
					      if (metricName == 'weather') {
						if (data == 'resume') {
						  $(`#${metricName}`).attr('class', 'noti-icon mdi mdi-white-balance-sunny text-warning');
						} else if (data == 'daylight') {
						  $(`#${metricName}`).attr('class', 'noti-icon mdi mdi-white-balance-sunny text-warning');
						} else if (data == 'sunrise_sunset') {
						  $(`#${metricName}`).attr('class', 'noti-icon mdi mdi-weather-sunset text-warning');
						} else if (data == 'lunar_storm') {
						  $(`#${metricName}`).attr('class', 'noti-icon mdi mdi-weather-lightning text-primary');
						} else if (data == 'lunar') {
						  $(`#${metricName}`).attr('class', 'noti-icon mdi mdi-weather-night text-primary');
						} else if (data == 'moonlight') {
						  $(`#${metricName}`).attr('class', 'noti-icon mdi mdi-weather-night text-primary');
						} else if (data == 'lightning') {
						  $(`#${metricName}`).attr('class', 'noti-icon mdi mdi-weather-lightning text-dark');
						} else if (data == 'full_thunderstorm') {
						  $(`#${metricName}`).attr('class', 'noti-icon mdi mdi-weather-lightning-rainy text-dark');
						} else if (data == 'rolling_clouds') {
						  $(`#${metricName}`).attr('class', 'noti-icon mdi mdi-weather-partlycloudy text-info');
						} else if (data == 'random_clouds') {
						  $(`#${metricName}`).attr('class', 'noti-icon mdi mdi-weather-partlycloudy text-info');
						}
					      }	
                                	    });
				    });
                                  }
                                  setInterval(captureDevices, 5000);
                                  captureDevices();
		


				  function getQueryVariable(variable) {
				    var query = window.location.search.substring(1);
				    var vars = query.split("&");
				    for (var i=0;i<vars.length;i++) {
					var pair = vars[i].split("=");
					if(pair[0] == variable){return pair[1];}
				    }
				    return(false);
				  }
				  // Roll up alerts when they're done
				  $(".alert").delay(5000).slideUp(200, function(){
					$(this).alert('close');
				  });
               });
