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
							<a href="/profile-detail/<?php echo ($action == false)?'edit':'view'; ?>/<?php echo $userdata->user_type; ?>/<?php echo $user_id; ?>" data-rel="collapse"><i class="glyphicon glyphicon-refresh"></i></a>
							<a href="#" data-rel="reload"><i class="glyphicon glyphicon-cog"></i></a>
						</div>
					</div>

		  			<div class="content-box-large box-with-header">
		  				<?php $b_edit = $action; ?>

		  				<h4>&raquo; User Info</h4>
		  				<hr>
		  				
		  				<div>
		  					<fieldset>
		  						<form action="<?php echo site_url(); ?>profile-update" id="registion_form" method="post" enctype="multipart/form-data">
		  						<?php
		  						if(!empty($userdata)) 
		  						{
		  						/*echo "<pre>";
		  						print_r($plans);
		  						echo "</pre>";*/
		  						?> 
		  						<div class="row">
		  							<div class="form-group">
		  								<div class="col-sm-6">
		  									<label>First Name</label>
		  									<?php if($b_edit): ?>
		  									<input type="text" name="first_name" class="form-control" value="<?php echo $userdata->first_name; ?>" placeholder="">
		  									<?php else: ?>
		  									<p><?php echo $userdata->first_name; ?></p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-sm-3">
		  									<label>Middle Name</label>
		  									<?php if($b_edit): ?>
		  									<input type="text" name="middle_name" class="form-control" value="<?php echo $userdata->middle_name; ?>" placeholder="">
		  									<?php else: ?>
		  									<p><?php echo $userdata->middle_name; ?></p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-sm-3">
		  									
		  									<label>Last Name</label>
		  									<?php if($b_edit): ?>
		  									<input type="text" name="last_name" class="form-control" value="<?php echo $userdata->last_name; ?>" placeholder="">
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
		  										<input type="text" name="dob" class="form-control mask-date" data-mask="99/99/9999" value="<?php echo $dob = date("d-m-Y", strtotime($userdata->dob)); ?>" data-mask-placeholder="-">
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
		  										<option <?php echo ( strtolower($userdata->gender) == 'male')?'selected':''; ?> >Male</option>
		  										<option <?php echo ( strtolower($userdata->gender) == 'female')?'selected':''; ?>>Female</option>
		  									</select>
		  									<?php else: ?>
		  									<p><?php echo $userdata->gender; ?></p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-sm-3">
		  									<label>State</label>
		  									<?php if($b_edit): ?>
		  									<select class="form-control" name="state">
		  										<option value="">Select State</option>
												<?php
												if(!empty($settings['state'])){
													foreach ($settings['state'] as $state_id => $state) {
														$selected_state = ($userdata->state == $state_id)?'selected':'';
												?>
												<option <?php echo $selected_state; ?> value="<?php echo $state_id; ?>"><?php echo $state; ?></option>
												<?php } 
												} ?>
		  									</select>
		  									<?php else: ?>
		  									<p><?php echo (isset($settings['state'][$userdata->state]))?$settings['state'][$userdata->state]:'--'; ?></p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-sm-3">
		  									<label>City</label>
		  									<?php if($b_edit): ?>
		  									<input type="text" name="city" class="form-control" value="<?php echo $userdata->city; ?>" placeholder="">
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
		  									<textarea class="form-control" name="address" placeholder="Textarea" rows="3"><?php echo $userdata->address; ?></textarea>
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
		  									<input type="text" name="mobile" class="form-control" value="<?php echo $userdata->mobile; ?>" placeholder="">
		  									<?php else: ?>
		  									<p><?php echo $userdata->mobile; ?></p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-sm-4">
		  									<label>Phone</label>
		  									<?php if($b_edit): ?>
		  									<input type="text" name="phone" class="form-control" value="<?php echo $userdata->phone; ?>" placeholder="">
		  									<?php else: ?>
		  									<p><?php echo $userdata->phone; ?></p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-sm-4">
		  									<label>Email</label>
		  									<?php if($b_edit): ?>
		  									<input type="text" name="email" class="form-control" value="<?php echo $userdata->email; ?>" placeholder="">
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
		  									<?php if($b_edit):

		  									$a_tc = explode(',', $userdata->talent_category);
		  									?>
		  									<select multiple="multiple" name="talent_category[]" id="talent-category" class="form-control custom-scroll" title="Click to Select a Category">
		  									<?php
												if(!empty($settings['talents_category'])){
													foreach ($settings['talents_category'] as $talent_key => $talent_value) {
														$selected_tc = (in_array($talent_key, $a_tc))?'selected':'';
														echo "<option ".$selected_tc." value=".$talent_key.">".$talent_value."</option>";
													}
												}
		  									?>
		  									</select>
		  									<?php else: ?>
		  									<p>
		  										<?php 
		  										$a_tc = explode(',', $userdata->talent_category); 
		  										foreach ($a_tc as $tc_ic) {
													echo $tc = (isset($settings['talents_category'][$tc_ic]))?$settings['talents_category'][$tc_ic].', ':'';
												} 
												?>	
											</p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-sm-4">
		  									<label>About You</label>
		  									<?php if($b_edit): ?>
		  									<textarea class="form-control" name="description" placeholder="Description" rows="3"><?php echo $userdata->description; ?></textarea>
		  									<?php else: ?>
		  									<p><?php echo $userdata->description; ?></p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-sm-4">
		  									<label>Experience</label>
		  									<?php if($b_edit): ?>
		  									<textarea class="form-control" name="experience" placeholder="experience" rows="3"><?php echo $userdata->experience; ?></textarea>
		  									<?php else: ?>
		  									<p><?php echo $userdata->experience; ?></p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-sm-12">
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
		  						<div class="row">
		  							<div class="form-group speciality">
		  								<div class="col-md-4">
		  									<label>Hair Clour</label>
		  									<?php if($b_edit): ?>
		  									<select name="hair_colour" id="hair_colour" class="form-control custom-scroll" title="Click to Select a Category">
		  									<option value="">Selct Hair Colour</option>											
		  									<?php
		  									if(!empty($settings['hair_colour'])){
		  										foreach ($settings['hair_colour'] as $hair_key => $hair_value) {
		  											$selected_hc = ($hair_key == $userdata->hair)?'selected':'';
		  											echo "<option {$selected_hc} value=".$hair_key.">".ucwords($hair_value)."</option>";
		  										}
		  									}
		  									?>
		  									</select>
		  									<?php else: ?>
		  									<p><?php echo isset($settings['hair_colour'][$userdata->hair])?$settings['hair_colour'][$userdata->hair]:' -- '; ?></p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-md-4">
		  									<label>Eye Clour</label>
		  									<?php if($b_edit): ?>
		  									<select name="eye_colour" id="eye_colour" class="form-control custom-scroll" title="Click to Select a Category">
		  									<option value="">Selct Eye Colour</option>											
		  									<?php
		  									if(!empty($settings['eye_colour'])){
		  										foreach ($settings['eye_colour'] as $eye_key => $eye_value) {
		  											$selected_ey = ($eye_key == $userdata->eye)?'selected':'';
		  											echo "<option {$selected_ey} value=".$eye_key.">".ucwords($eye_value)."</option>";
		  										}
		  									}
		  									?>
		  									</select>
		  									<?php else: ?>
		  									<p><?php echo isset($settings['eye_colour'][$userdata->eye])?$settings['eye_colour'][$userdata->eye]:' -- '; ?></p>
		  									<?php endif; ?>
		  								</div>
		  								<div class="col-md-4">
		  									<label>Body Clour</label>
		  									<?php if($b_edit): ?>
		  									<select name="body_colour" id="body_colour" class="form-control custom-scroll" title="Click to Select a Category">
		  									<option value="">Selct Body Colour</option>											
		  									<?php
		  									if(!empty($settings['body_colour'])){
		  										foreach ($settings['body_colour'] as $body_key => $body_value) {
		  											$selected_cl = ($body_key == $userdata->colour)?'selected':'';
		  											echo "<option {$selected_cl} value=".$body_key.">".ucwords($body_value)."</option>";
		  										}
		  									}
		  									?>
		  									</select>
		  									<?php else: ?>
		  									<p><?php echo isset($settings['body_colour'][$userdata->colour])?$settings['body_colour'][$userdata->colour]:' -- '; ?></p>
		  									<?php endif; ?>
		  								</div>
		  							</div>
		  						</div>
		  						<hr>
		  						<div class="row">
		  							<div class="form-group speciality">
		  								<div class="col-md-4">
		  									<label>Body Type</label>
		  									<?php if($b_edit): ?>
		  									<select name="body_type" id="body_type" class="form-control custom-scroll" title="Click to Select a Category">
		  									<option value="">Select Body Type</option>											
		  									<?php
		  									if(!empty($settings['body_type'])){
		  										foreach ($settings['body_type'] as $body_type_key => $body_type_value) {
		  											$selected_bt = ($body_type_key == $userdata->body_type)?'selected':'';
		  											echo "<option {$selected_bt} value=".$body_type_key.">".ucwords($body_type_value)."</option>";
		  										}
		  									}
		  									?>
		  									</select>
		  									<?php else: ?>
		  									<p><?php echo isset($settings['body_type'][$userdata->body_type])?$settings['body_type'][$userdata->body_type]:' -- '; ?></p>
		  									<?php endif; ?>
		  								</div>

		  								<div class="col-md-4">
		  									<label>Hight</label>
		  									<?php if($b_edit): ?>
		  									<input type="text" name="hight" value="<?php echo $userdata->height; ?>" class="form-control" placeholder="In centimeter">
		  									<?php else: ?>
		  									<p><?php echo $userdata->height; ?></p>
		  									<?php endif; ?>
		  								</div>

		  								<div class="col-md-4">
		  									<label>Weight</label>
		  									<?php if($b_edit): ?>
		  									<input type="text" name="weight" value="<?php echo $userdata->weight; ?>" class="form-control" placeholder="In kg.">
		  									<?php else: ?>
		  									<p><?php echo $userdata->weight; ?></p>
		  									<?php endif; ?>
		  								</div>
		  							</div>

		  						</div>
		  						
		  						<?php if($b_edit): ?>
		  						<hr>

		  						<div class="row">
		  							<div class="form-group">

		  								<div class="col-md-6" id="plan_div">
		  									<label>Plan <span class="mandatory">*</span></label>
		  									<select name="plan" id="plan" class="form-control custom-scroll">
		  									<option value="">Selct Plan</option>											
		  									<?php
		  									if(!empty($plans)){
		  										foreach ($plans as $p_key => $p_value) {
		  											$selected_plan = ($p_key == $userdata->plan_id)?'selected':'';
		  											echo "<option ".$selected_plan." value=".$p_key.">".ucwords($p_value)."</option>";
		  										}
		  									}
		  									?>
		  									</select>
		  								</div>

		  								<div class="col-md-6">
		  									<br>
			  								<div class="checkbox">
			  								<label id="approve">
				  								<input type="checkbox" name="approve" class="btn btn-primary" id="approve" checked="checked" value="1">
				  								Profile Status. If, it is checked then profile is active
				  							</label>
				  							</div>
		  								</div>

		  							</div>
		  						</div>

		  						<?php endif; ?>
		  						
		  						<hr>

		  						<div>
		  							<div>
		  								<i class="fa fa-save"></i>
		  								<!-- <button class="btn btn-primary" id="save-reg">Submit</button> -->
		  								<?php
		  								// print_r($userdata);
		  								?>
		  								<?php if($b_edit): ?>
		  								<input type="submit" name="update" class="btn btn-default" id="save-reg" value="Update Profile">
		  								<?php endif; ?>
		  								<a class="btn btn-success" href="/media/<?php echo $userdata->user_id; ?>">Media</a>
		  								<input type="hidden" name="user_id" id="user_id" value="<?php echo $userdata->user_id; ?>">
		  								<input type="hidden" name="user_type" id="user_type" value="<?php echo $userdata->user_type; ?>">
		  								<input type="hidden" name="plan_id" id="plan_id" value="<?php echo $userdata->plan_id; ?>">
		  								<input type="hidden" name="subscription_id" id="subscription_id" value="<?php echo $userdata->subscription_id; ?>">
		  							</div>
		  						</div>

		  						<hr>
		  					<?php } ?> 
		  					</form>
		  					</fieldset>
		  				</div>

		  				<?php //if($userdata->user_type == 3) { ?>
		  				<h4>&raquo; Subscription History</h4>
		  				<hr>
		  				<div>
		  					<?php 
		  					if(empty($subscription)){
		  						echo "<p> No subscription history !</p> <br>";
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
		  				<?php if(!empty($features)) { ?>
		  				<table cellpadding="0" cellspacing="0" border="0" class="table table-hover" id="example">
							<thead>
								<tr>
									<th>#</th>
									<th>Features</th>
									<th>Status / Count</th>
									<!-- <th> -- </th> -->
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
									<td><?php $fkey =  strtolower($f_key); echo (in_array($f_key, $a_count_filter))?$f_value.' no.s':(($f_value == 0)?'Unavilable':'Avilable'); ?></td>
									<!-- <td class="center">X</td> -->
								</tr>
								<?php
									}
								$j++;
								}
								?>
							</tbody>
						</table>
						<?php } else { echo "<p>No features !</p><br>"; }?>
		  				<h4>&raquo; Notifications</h4>
		  				<hr>
		  				<table cellpadding="0" cellspacing="0" border="0" class="table table-hover" id="example">
							<thead>
								<tr>
									<th>#</th>
									<th>Notification</th>
									<th>On</th>
									<!-- <th>From</th>
									<th>To</th> -->
									<th>Name</th>
									<th>Status / Count</th>
									<!-- <th> -- </th> -->
								</tr>
							</thead>
							<tbody>
								<?php 
								$k=1;
								if(!empty($notifyed)){
									foreach ($notifications as $notifyed_key => $notifyed_value) {
									?>
									<tr class="odd gradeX">
										<td><?php echo $k; ?></td>
										<td><?php $feature = implode(',', array_keys($notifyed_value['feature'])); echo ucwords(str_replace('_', 'd ', $feature)); ?></td>
										<td><?php echo $notifyed_value['notification_on']; ?></td>
										<!-- <td><?php echo $notifyed_value['from']; ?></td>
										<td><?php echo $notifyed_value['to']; ?></td> -->
										<td><?php echo $notifyed_value['name']; ?></td>
										<td><?php echo $notifyed_value['notification_status']; ?></td>
										<!-- <td class="center">X</td> -->
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
		  				
		  				<hr>
			  			<div class="row">
			  				<?php
			  				if( $permission == 2 ){
			  					
			  					if($notifyed) {
			  					?>
			  					<?php if(!isset($notifyed[2])) { ?>
			  					<button class="btn btn-info send-notification" data-permission="<?php echo $triggers['permission']; ?>" data-from="<?php echo $triggers['from']; ?>" data-to="<?php echo $triggers['to']; ?>" data-map_id="2" ><i class="glyphicon glyphicon-star-empty"></i> Send interest</button>
			  					<?php } else { ?>
			  					<button class="btn btn-default" ><i class="glyphicon glyphicon-star"></i> Interest Sent</button>
			  					<?php } ?>
			  					<?php if(!isset($notifyed[5])) { ?>
			  					<button class="btn btn-primary send-interview" data-toggle="modal" data-target="#interview-model" data-permission="<?php echo $triggers['permission']; ?>" data-from="<?php echo $triggers['from']; ?>" data-to="<?php echo $triggers['to']; ?>" data-map_id="5" ><i class="glyphicon glyphicon-pencil"></i> Interview letter</button>
			  					<?php } else { ?>
			  					<button class="btn btn-primary" data-toggle="modal" data-target="#interview-model" data-permission="<?php echo $triggers['permission']; ?>" data-from="<?php echo $triggers['from']; ?>" data-to="<?php echo $triggers['to']; ?>" data-map_id="5" data-interview="<?php echo $notifyed[5]['notification_relation']; ?>" ><i class="glyphicon glyphicon-pencil"></i> Interview Scheduled</button>
			  					<?php } ?>
			  					<a href="/chat/<?php echo $user_id; ?>" class="btn btn-success chat" ><i class="glyphicon glyphicon-comment"></i> Chat </a>

			  					<?php
			  					} else { ?>
			  					<button class="btn btn-info interview" data-permission="<?php echo $triggers['permission']; ?>" data-from="<?php echo $triggers['from']; ?>" data-to="<?php echo $triggers['to']; ?>" data-map_id="2" ><i class="glyphicon glyphicon-star-empty"></i> Send interest</button>
			  					<?php 
			  					}
			  				} else if($permission == 1){ ?>

			  					<button class="btn btn-primary" data-toggle="modal" data-target="#interview-model" data-permission="<?php echo $user_type; ?>" <?php if($user_type == 3){ ?> data-to="<?php echo $triggers['to']; ?>" <?php } ?> <?php if($user_type == 2){ ?> data-from="<?php echo $triggers['from']; ?>" <?php } ?> data-map_id="5" data-interview="" ><i class="glyphicon glyphicon-pencil"></i> Interviews </button>
			  				<?php
			  				}
			  				?>
			  				<?php require_once('interviews.php') ?>	
			  			</div>
		  			</div>
				</div>
			</div>
		</div>
	</div>
</div>

