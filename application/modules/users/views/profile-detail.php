<body class="login-bg">
<div class="header">
    <div class="container">
        <div class="row">
           <div class="col-md-12">
              <!-- Logo -->
              <div class="logo">
                 <h1><a href="index.html">Be Celebrity !</a></h1>
              </div>
           </div>
        </div>
    </div>
</div>

<div class="page-content">
	<div class="row">

		<?php include("templates/side-bar.php"); ?>

	  	<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 panel-info">
					<div class="content-box-header panel-heading">
						<div class="panel-title ">Profile Detail &raquo; <?php echo $userdata->display_name; ?></div>
					
						<div class="panel-options">
							<a href="#" data-rel="collapse"><i class="glyphicon glyphicon-refresh"></i></a>
							<a href="#" data-rel="reload"><i class="glyphicon glyphicon-cog"></i></a>
						</div>
					</div>

		  			<div class="content-box-large box-with-header">
		  				<?php $b_edit = $action; ?>
		  				<!-- <div class="row">
		  					<div class="col-xs-8">
		  						<div id="example_length" class="dataTables_length">
		  							<label>
		  								<select size="1" name="example_length" aria-controls="example">
		  									<option value="10" selected="selected">10</option>
		  									<option value="25">25</option><option value="50">50</option>
		  									<option value="100">100</option>
		  								</select> 
		  								records per page
		  							</label>
		  						</div>
		  					</div>

	  						<div class="col-xs-12">
	  							<div class="dataTables_filter" id="example_filter">
	  								<label>Search: <input type="text" aria-controls="example"></label>
	  							</div>
	  						</div>
		  				</div> -->

		  				<h4>&raquo; User Info</h4>
		  				<hr>
		  				<div class="">
		  					
		  					<fieldset>
		  						<?php //echo "<pre>"; print_r($userdata); 
		  						if(!empty($userdata)) { ?> 
		  						<div class="row">
		  							<div class="form-group">
		  								<div class="col-sm-6">
		  									<label>First Name</label>
		  									<?php if($b_edit): ?>
		  									<input type="text" name="first_name" class="form-control" placeholder="">
		  									<?php else: ?>
		  									<p><?php echo $userdata->first_name; ?></p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-sm-3">
		  									<label>Middle Name</label>
		  									<?php if($b_edit): ?>
		  									<input type="text" name="middle_name" class="form-control" placeholder="">
		  									<?php else: ?>
		  									<p><?php echo $userdata->middle_name; ?></p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-sm-3">
		  									
		  									<label>Last Name</label>
		  									<?php if($b_edit): ?>
		  									<input type="text" name="last_name" class="form-control" placeholder="">
		  									<?php else: ?>
		  									<p><?php echo $userdata->last_name; ?></p>
		  									<?php endif; ?>
		  								</div>
		  							</div>
		  						</div>
		  						<hr>
		  						<div class="row">
		  							<div class="form-group">
		  								<div class="col-sm-3">
		  									<label for="h-input">Date Of Bith</label>
		  									<div class="input-group">
		  										<?php if($b_edit): ?>
		  										<input type="text" class="form-control mask-date" data-mask="99/99/9999" data-mask-placeholder="-">
		  										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
		  										<?php else: ?>
		  										<p><?php echo $dob = date("d-m-Y", strtotime($userdata->dob)); ?></p>
		  										<?php endif; ?>
		  									</div>
		  									<!-- <p class="note">
		  										Data format **/**/****
		  									</p> -->
		  								</div>
		  								<div class="col-sm-3">
		  									<label>Gender</label>
		  									<?php if($b_edit): ?>
		  									<select class="form-control" name="gender">
		  										<option>Male</option>
		  										<option>Female</option>
		  									</select>
		  									<?php else: ?>
		  									<p><?php echo $userdata->gender; ?></p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-sm-3">
		  									<label>State</label>
		  									<?php if($b_edit): ?>
		  									<select class="form-control" name="state">
		  										<option>Kerala</option>
		  										<option>Tamilnadu</option>
		  									</select>
		  									<?php else: ?>
		  									<p><?php echo (isset($settings['state'][$userdata->state]))?$settings['state'][$userdata->state]:'--'; ?></p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-sm-3">
		  									<label>City</label>
		  									<?php if($b_edit): ?>
		  									<input type="text" name="city" class="form-control" placeholder="">
		  									<?php else: ?>
		  									<p><?php echo $userdata->city; ?></p>
		  									<?php endif; ?>
		  								</div>
		  							</div>
		  						</div>
		  						<hr>
		  						<div class="row">
		  							<div class="form-group">
		  								<div class="col-sm-12">
		  									<label>Address</label>
		  									<?php if($b_edit): ?>
		  									<textarea class="form-control" placeholder="Textarea" rows="3"></textarea>
		  									<?php else: ?>
		  									<p><?php echo $userdata->address; ?></p>
		  									<?php endif; ?>
		  								</div>
		  							</div>
		  						</div>
		  						<hr>
		  						<div class="row">
		  							<div class="form-group">
		  								<div class="col-sm-4">
		  									<label>Mobile</label>
		  									<?php if($b_edit): ?>
		  									<input type="text" name="dob" class="form-control" placeholder="">
		  									<?php else: ?>
		  									<p><?php echo $userdata->mobile; ?></p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-sm-4">
		  									<label>Phone</label>
		  									<?php if($b_edit): ?>
		  									<input type="text" name="gender" class="form-control" placeholder="">
		  									<?php else: ?>
		  									<p><?php echo $userdata->phone; ?></p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-sm-4">
		  									<label>Email</label>
		  									<?php if($b_edit): ?>
		  									<input type="text" name="state" class="form-control" placeholder="">
		  									<?php else: ?>
		  									<p><?php echo $userdata->email; ?></p>
		  									<?php endif; ?>
		  								</div>
		  							</div>
		  						</div>
		  						<hr>
		  						<div class="row">
		  							<div class="form-group">
		  								<div class="col-sm-4">
		  									<label>Talet Category</label>
		  									<?php if($b_edit): ?>
		  									<select multiple="multiple" name="talent_category" id="talent-category" class="form-control custom-scroll" title="Click to Select a Category">
		  									<?php
		  									if(!empty($a_settings)){
		  										foreach ($a_settings as $key => $talent_cat) {
		  											foreach ($talent_cat as $setting_key => $setting_value) {
		  												// echo "<option value=''>".."</option>";
		  											}
		  										}
		  									}
		  									?>
		  									</select>
		  									<?php else: ?>
		  									<p>
		  										<?php 
		  										$a_tc = explode(',', $userdata->talent_category); foreach ($a_tc as $tc_ic) {
													echo $tc = (isset($settings['talents_category'][$tc_ic]))?$settings['talents_category'][$tc_ic].', ':'';
												} 
												?>	
											</p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-sm-4">
		  									<label>Description</label>
		  									<?php if($b_edit): ?>
		  									<textarea class="form-control" placeholder="Description" rows="3"></textarea>
		  									<?php else: ?>
		  									<p><?php echo $userdata->description; ?></p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-sm-4">
		  									<label>Tags</label>
		  									<?php if($b_edit): ?>
		  									<div>
		  				  						<div id="tags"></div>
		  				  					</div>
		  				  					<?php else: ?>
		  				  					<p><?php echo $userdata->tags_interest; ?></p>
		  				  					<?php endif; ?>
		  								</div>
		  							</div>
		  						</div>
		  						<hr>
		  						<?php if($b_edit): ?>
		  						<div class="row">
		  							<div class="form-group">
		  								<div class="col-sm-4">
		  									<label class="control-label">File input</label>
		  										<input type="file" class="btn btn-default" id="exampleInputFile1" name="photos">
		  										<p class="help-block">
		  											You can upload 3 phots.
		  										</p>
		  								</div>
		  								<div class="col-sm-8">
		  									<label>Embedded Videos</label>
		  									<input type="text" name="videos" class="form-control" placeholder="https://youtu.be/YicuKTFPxX0">
		  								</div>
		  							</div>
		  						</div>

		  						<?php endif; ?>
		  						
		  						<?php
		  						if( !empty($triggers) && $triggers['permission'] == 2 ){
		  							
		  							if($notifyed) {
		  							?>
		  							<?php if(!isset($notifyed[2])) { ?>
		  							<button class="btn btn-info send-notification" data-permission="<?php echo $triggers['permission']; ?>" data-from="<?php echo $triggers['from']; ?>" data-to="<?php echo $triggers['to']; ?>" data-map_id="2" ><i class="glyphicon glyphicon-star-empty"></i> Send interest</button>
		  							<?php } else { ?>
		  							<button class="btn btn-default" ><i class="glyphicon glyphicon-star"></i> Interest Sent</button>
		  							<?php } ?>

		  							<?php if(!isset($notifyed[5])) { ?>
		  							<button class="btn btn-primary send-interview" data-toggle="modal" data-target="#interview" data-permission="<?php echo $triggers['permission']; ?>" data-from="<?php echo $triggers['from']; ?>" data-to="<?php echo $triggers['to']; ?>" data-map_id="5" ><i class="glyphicon glyphicon-pencil"></i> Interview letter</button>
		  							<?php } else { ?>
		  							<button class="btn btn-primary" data-toggle="modal" data-target="#interview" data-permission="<?php echo $triggers['permission']; ?>" data-from="<?php echo $triggers['from']; ?>" data-to="<?php echo $triggers['to']; ?>" data-map_id="5" data-interview="<?php echo $notifyed[5]['notification_relation']; ?>" ><i class="glyphicon glyphicon-pencil"></i> Interview Scheduled</button>
		  							<?php } ?>
		  							<button class="btn btn-success chat" ><i class="glyphicon glyphicon-comment"></i> Chat </button>
		  							
		  							<!-- Modal -->
		  							<div id="interview" class="modal fade" role="dialog">
		  							  <div class="modal-dialog" style="width: 75%;">

		  							    <!-- Modal content-->
		  							    <div class="modal-content">
		  							      <div class="modal-header">
		  							        <button type="button" class="close" data-dismiss="modal">&times;</button>
		  							        <h4 class="modal-title">Interview call letter</h4>
		  							      </div>

		  							      <div id="tabs" class="modal-body">

		  							      	<ul class="nav nav-tabs">
		  							      	  	
		  							      	  	<li class="active"><a data-toggle="pill" href="#intrw-user">User's interviews</a></li>
		  							      	  	<li><a data-toggle="pill" href="#intrw-list">All</a></li>
		  							      	  	<li><a data-toggle="pill" href="#new-intrw">Schedule</a></li>
		  							      	</ul>

		  							      	<hr>
		  							      	<div class="tab-content">
			  							      	<div id="new-intrw" class="tab-pane fade">
				  							      	<form action="#" class="form-horizontal" id="itrw_form" method="post" enctype="multipart/form-data">
				  							      		<fieldset>
			        		  							<div class="form-group">
			        		  								<div class="col-sm-12">
			        		  									<label>Subject</label>
			        		  									<input type="text" name="intrw_subject" id="intrw_subject" class="form-control" placeholder="Interview for ...">
			        		  									<input type="hidden" name="interview_id" id="id" value="">
			        		  								</div>
			        		  							</div>
			        		  							<div class="form-group">
			        		  								<div class="col-sm-6">
			        		  									<label>Start Date</label>
			        		  									<input type="text" name="intrw_on" id="intrw_on" class="form-control" placeholder="">
			        		  								</div>
			        		  								<div class="col-sm-6">
			        		  									<label>End Date</label>
			        		  									<input type="text" class="form-control" name="intrw_due" id="intrw_due" placeholder="Textarea" >
			        		  								</div>
			        		  								
			        		  							</div>
			        		  							<div class="form-group">
			        		  								<div class="col-sm-12">
			        		  									<label>Location / Address</label>
			        		  									<input type="text" name="intrw_location" id="intrw_location" class="form-control" placeholder="Placid avenue, BH road">
			        		  								</div>
			        		  							</div>
			        		  							<div class="form-group">
			        		  								<div class="col-sm-12">
			        		  									<label>Description</label>
			        		  									<textarea class="form-control" name="intrw_description" id="intrw_description" placeholder="About interview" rows="3"></textarea>
			        		  								</div>
			        		  							</div>
			        		  							<div class="form-group">
			        		  								<div class="col-sm-4">
			        		  									<label>Organizer / Company</label>
			        		  									<input type="text" name="oganizer_name" id="oganizer_name" class="form-control" placeholder="">
			        		  								</div>
			        		  								<div class="col-sm-4">
			        		  									<label>Contact</label>
			        		  									<input type="text" name="oganizer_contact" id="oganizer_contact" class="form-control" placeholder="">
			        		  								</div>
			        		  								<div class="col-sm-4">
			        		  									<label>Website</label>
			        		  									<input type="text" name="oganizer_website" id="oganizer_website" class="form-control" placeholder="">
			        		  								</div>
			        		  							</div>

			        		  							<div class="form-group">
			        		  								<div class="col-sm-4">
				        		  								<button type="submit" class="btn btn-primary post-interview" data-permission="<?php echo $triggers['permission']; ?>" data-from="<?php echo $triggers['from']; ?>" data-to="<?php echo $triggers['to']; ?>" data-map_id="5">Post interview</button>
				        		  								<button type="button" class="btn btn-default reste-notification">Reset</button>
				        		  							</div>
			        		  							</div>

			        		  						</fieldset>
			        		  						</form>
		        		  						</div>

		        		  						<div id="intrw-user" class="tab-pane fade in active" data-from="<?php echo $triggers['from']; ?>" data-to="<?php echo $triggers['to']; ?>">
	  							  					<table cellpadding="0" cellspacing="0" border="0" class="table table-hover" id="intrw">
	  													<thead>
	  														<tr>
	  															<th>#</th>
	  															<th>intrw_subject</th>
	  															<th>intrw_description</th>
	  															<th>intrw_on</th>
	  															<th>intrw_location</th>
	  															<th>added_on</th>
	  															<th>intrw_status</th>
	  															<th></th>
	  														</tr>
	  													</thead>
	  													<tbody class="intrw-tbody" id="intrw-list-user-body">
	  														<tr class="odd gradeX">
	  															<td colspan="6"> No inter views</td>
	  														</tr>
	  													</tbody>
	  												</table>
		        		  						</div>

		        		  						<div id="intrw-list" class="tab-pane fade" data-from="<?php echo $triggers['from']; ?>" data-to="<?php echo $triggers['to']; ?>">
	  							  					<table cellpadding="0" cellspacing="0" border="0" class="table table-hover" id="intrw">
	  													<thead>
	  														<tr>
	  															<th>#</th>
	  															<th>intrw_subject</th>
	  															<th>intrw_description</th>
	  															<th>intrw_on</th>
	  															<th>intrw_location</th>
	  															<th>added_on</th>
	  															<th>intrw_status</th>
	  															<th></th>
	  														</tr>
	  													</thead>
	  													<tbody class="intrw-tbody" id="intrw-list-body">
	  														<tr class="odd gradeX">
	  															<td colspan="6"> No inter views</td>
	  														</tr>
	  													</tbody>
	  												</table>
		        		  						</div>

	        		  						</div>

		  							      </div>
		  							      <div class="modal-footer">
		  							        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		  							      </div>
		  							    </div>

		  							  </div>
		  							</div>

		  							<?php
		  							} else { ?>
		  							<button class="btn btn-info interview" data-permission="<?php echo $triggers['permission']; ?>" data-from="<?php echo $triggers['from']; ?>" data-to="<?php echo $triggers['to']; ?>" data-map_id="2" ><i class="glyphicon glyphicon-star-empty"></i> Send interest</button>
		  							<?php 
		  							}
		  						}
		  						?>

		  						<hr>
		  					<?php } ?> 
		  					</fieldset>
		  				</div>



		  				<h4>&raquo; Subscription History</h4>
		  				<hr>

		  				<div>
		  					<?php 
		  					if(empty($subscription)){

		  					} else {
		  					?>
		  					<table cellpadding="0" cellspacing="0" border="0" class="table table-hover" id="example">
								<thead>
									<tr>
										<th>#</th>
										<th>User Plans</th>
										<th>Purchased On</th>
										<th>Starts On</th>
										<th>Ended On</th>
										<th>Payment through</th>
										<th>Status</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$i=1;
									foreach ($subscription as $key => $value) {
										// print_r($value);
									?>
									<tr class="odd gradeX">
										<td><?php echo $i; ?></td>
										<td><?php echo $value->plan_name; ?></td>
										<td><?php echo $value->purchased_on; ?></td>
										<td class="center"><?php echo $value->started_on; ?></td>
										<td class="center"><?php echo $value->ends_on; ?></td>
										<td class="center">Online</td>
										<td class="center"><?php echo $value->subscription_status; ?></td>
										<td class="center">X</td>
									</tr>
									<?php
									$i++;
									}
									?>
									

								</tbody>
							</table>
							<?php } ?>
		  				</div>

		  				<h4>&raquo; Current plan features</h4>
		  				<hr>
		  				<table cellpadding="0" cellspacing="0" border="0" class="table table-hover" id="example">
							<thead>
								<tr>
									<th>#</th>
									<th>Features</th>
									<th>Status / Count</th>
									<th> -- </th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$j=1;
								$a_count_filter = array('Images', 'Videos');
								foreach ($features as $feature_key => $feature_value) {
									foreach ($feature_value as $f_key => $f_value) {
								?>
								<tr class="odd gradeX">
									<td><?php echo $j; ?></td>
									<td><?php echo $f_key; ?></td>
									<td><?php $fkey =  strtolower($f_key); echo (in_array($f_key, $a_count_filter))?$f_value:(($f_value == 0)?'<input type="checkbox" id="'.$fkey.'" name="feature_status['.$fkey.']" value="0"><lable for="'.$fkey.'">Avilable</label>':'<input type="checkbox" id="'.$fkey.'" name="feature_status['.$fkey.']" checked  value="1"><lable for="'.$fkey.'">Avilable</label>'); ?></td>
									<td class="center">X</td>
								</tr>
								<?php
									}
								$j++;
								}
								?>
							</tbody>
						</table>

		  				<h4>&raquo; Notifications</h4>
		  				<hr>
		  				<table cellpadding="0" cellspacing="0" border="0" class="table table-hover" id="example">
							<thead>
								<tr>
									<th>#</th>
									<th>Notification</th>
									<th>On</th>
									<th>Status / Count</th>
									<th> -- </th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$k=1;
								if(!empty($notifyed)){
									foreach ($notifyed as $notifyed_key => $notifyed_value) {
									?>
									<tr class="odd gradeX">
										<td><?php echo $j; ?></td>
										<td><?php echo $notifyed_key; ?></td>
										<td><?php echo $notifyed_value['notification_on']; ?></td>
										<td><?php echo $notifyed_value['notification_status']; ?></td>
										<td class="center">X</td>
									</tr>
									<?php
									$k++;
									}
								} else {
								?>
								<tr class="odd gradeX">
									<td colspan="5">No notification found for this profile!</td>
								</tr>
								<?php
								}
								?>
							</tbody>
						</table>


		  				<!-- <div class="row"><div class="col-xs-6"><div class="dataTables_info" id="example_info">Showing 1 to 10 of 57 entries</div></div><div class="col-xs-6"><div class="dataTables_paginate paging_bootstrap"><ul class="pagination"><li class="prev disabled"><a href="#">← Previous</a></li><li class="active"><a href="#">1</a></li><li><a href="#">2</a></li><li><a href="#">3</a></li><li><a href="#">4</a></li><li><a href="#">5</a></li><li class="next"><a href="#">Next → </a></li></ul></div></div></div> -->
		  			</div>
				</div>
			</div>
		</div>
	</div>
</div>