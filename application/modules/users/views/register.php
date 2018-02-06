<?php //echo "<pre>";print_r($a_settings['talents_category']);die; ?>
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
						<div class="panel-title ">Registration &raquo;</div>
					
						<div class="panel-options">
							<a href="#" data-rel="collapse"><i class="glyphicon glyphicon-refresh"></i></a>
							<a href="#" data-rel="reload"><i class="glyphicon glyphicon-cog"></i></a>
						</div>
					</div>

					<div class="content-box-large box-with-header">
						<div>
							<form action="<?php echo site_url(); ?>register" class="form-horizontal" id="registion_form" method="post" enctype="multipart/form-data">
								<fieldset>
										<div class="form-group">
											<div class="col-md-6">
												<label>First Name<span class="mandatory">*</span></label>
												<input type="text" name="first_name" class="form-control" placeholder="">
											</div>
											<div class="col-md-3">
												<label>Middle Name</label>
												<input type="text" name="middle_name" class="form-control" placeholder="">
											</div>
											<div class="col-md-3">
												<label>Last Name</label>
												<input type="text" name="last_name" class="form-control" placeholder="">
											</div>
										</div>
									<hr>
										<div class="form-group">
											<div class="col-md-3">
												<label>Username <span class="mandatory">*</span></label>
												<input type="text" name="user_name" class="form-control" placeholder="">
											</div>
											<div class="col-md-3">
												<label>Password <span class="mandatory">*</span></label>
												<input type="password" name="password" class="form-control" placeholder="">
											</div>
											<div class="col-md-3">
												<label>Confirm Password <span class="mandatory">*</span></label>
												<input type="password" name="confirm_password" class="form-control" placeholder="">
											</div>
										</div>
									<hr>
										<div class="form-group">
											<div class="col-md-3">
												<label for="h-input">Date Of Birth <span class="mandatory">*</span></label>
												<div class="input-group">
													<input type="text" class="form-control mask-date" name="dob" data-mask="99/99/9999" data-mask-placeholder="-">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												</div>
												<p class="note">
													Data format **/**/****
												</p>
											</div>
											<div class="col-md-3">
												<label>Gender <span class="mandatory">*</span></label>
												<select class="form-control" name="gender">
													<option value="">Select Gender</option>
													<option value="male">Male</option>
													<option value="female">Female</option>
												</select>
											</div>
											<div class="col-md-3">
												<label>State</label>
												
												<select class="form-control" name="state">
													<option value="">Select State</option>
													<?php
													if(!empty($a_settings['state'])){
														foreach ($a_settings['state'] as $state_id => $state) {
													?>
													<option value="<?php echo $state_id; ?>"><?php echo $state; ?></option>
													<?php } 
													} ?>
												</select>
											</div>
											<div class="col-md-3">
												<label>City <span class="mandatory">*</span></label>
												<input type="text" name="city" class="form-control" placeholder="">
											</div>
										</div>
									<hr>
										<div class="form-group">
											<div class="col-md-12">
												<label>Address <span class="mandatory">*</span></label>
												<textarea class="form-control" name="address" placeholder="Textarea" rows="3"></textarea>
											</div>
										</div>
									<hr>
										<div class="form-group">
											<div class="col-md-4">
												<label>Mobile <span class="mandatory">*</span></label>
												<input type="text" name="mobile_num" class="form-control" placeholder="">
											</div>
											<div class="col-md-4">
												<label>Phone</label>
												<input type="text" name="phone_num" class="form-control" placeholder="">
											</div>
											<div class="col-md-4">
												<label>Email <span class="mandatory">*</span></label>
												<input type="text" name="email" class="form-control" placeholder="">
											</div>
										</div>
									<hr>
										<div class="form-group">
											<label class="col-md-2 control-label">Registrtion type</label>
											<div class="col-md-10">
												<label class="radio-inline"><input name="register_type" value="talent" checked="checked" type="radio">Talent </label>
												<label class="radio-inline"><input name="register_type" value="director" type="radio">Director </label>
											</div>
										</div>
									<hr class="talent">
										<div class="form-group talent">
											<div class="col-md-6">
												<label>Talet Category <span class="mandatory">*</span></label>
												<select multiple="multiple" name="talent_category[]" id="talent_category" class="form-control custom-scroll" title="Click to Select a Category">
												<?php
												if(!empty($a_settings['talents_category'])){
													foreach ($a_settings['talents_category'] as $talent_key => $talent_value) {
														echo "<option value=".$talent_key.">".$talent_value."</option>";
													}
												}
												?>
												</select>
											</div>
											<div class="col-md-6">
												<label>Description</label>
												<textarea class="form-control" name="description" placeholder="Description" rows="4"></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-12">
						  						<label>Tags</label>
						  						<p>
						  							<div id="tags"></div>
						  						</p>
						  					</div>
			  							</div>
									<!-- <hr class="talent"> -->

										<div class="form-group speciality" style="display:none">
											<div class="col-md-4">
												<label>Hair Clour</label>
												<select name="hair_colour" id="hair_colour" class="form-control custom-scroll" title="Click to Select a Category">
												<option value="">Selct Hair Colour</option>											
												<?php
												if(!empty($a_settings['hair_colour'])){
													foreach ($a_settings['hair_colour'] as $hair_key => $hair_value) {
														echo "<option value=".$hair_key.">".ucwords($hair_value)."</option>";
													}
												}
												?>
												</select>
											</div>
											<div class="col-md-4">
												<label>Eye Clour</label>
												<select name="eye_colour" id="eye_colour" class="form-control custom-scroll" title="Click to Select a Category">
												<option value="">Selct eye Colour</option>											
												<?php
												if(!empty($a_settings['eye_colour'])){
													foreach ($a_settings['eye_colour'] as $eye_key => $eye_value) {
														echo "<option value=".$eye_key.">".ucwords($eye_value)."</option>";
													}
												}
												?>
												</select>
											</div>
											<div class="col-md-4">
												<label>Body Clour</label>
												<select name="body_colour" id="body_colour" class="form-control custom-scroll" title="Click to Select a Category">
												<option value="">Selct Body Colour</option>											
												<?php
												if(!empty($a_settings['body_colour'])){
													foreach ($a_settings['body_colour'] as $body_key => $body_value) {
														echo "<option value=".$body_key.">".ucwords($body_value)."</option>";
													}
												}
												?>
												</select>
											</div>
										</div>
										<div class="form-group speciality" style="display:none">
											<div class="col-md-4">
												<label>Body Type</label>
												<select name="body_type" id="body_type" class="form-control custom-scroll" title="Click to Select a Category">
												<option value="">Select Body Type</option>											
												<?php
												if(!empty($a_settings['body_type'])){
													foreach ($a_settings['body_type'] as $body_type_key => $body_type_value) {
														echo "<option value=".$body_type_key.">".ucwords($body_type_value)."</option>";
													}
												}
												?>
												</select>
											</div>

											<div class="col-md-4">
												<label>Hight</label>
												<input type="text" name="hight" class="form-control" placeholder="In centimeter">
											</div>

											<div class="col-md-4">
												<label>Weight</label>
												<input type="text" name="weight" class="form-control" placeholder="In kg.">
											</div>
										</div>
									<hr>
										<div class="form-group talent">

											<div class="col-md-6" id="plan_div">
												<label>Plan <span class="mandatory">*</span></label>
												<select name="plan" id="plan" class="form-control custom-scroll">
												<option value="">Selct Plan</option>											
												<?php
												if(!empty($plans)){
													foreach ($plans as $p_key => $p_value) {
														echo "<option value=".$p_key.">".ucwords($p_value)."</option>";
													}
												}
												?>
												</select>
											</div>

											<div class="col-md-6">
												<label class="control-label">File input</label>
													<input type="file" class="btn btn-default" id="photos" name="photos[]" multiple>
													<p class="help-block">
														You can upload photos.
													</p>
											</div>
										</div>
										<div class="form-group talent">
											<div class="col-md-12">
												<label>Embedded Videos</label>
												<div id=video_add>
													<div class="video_url" style="margin-bottom: 10px;"><input type="text" name="videos[]" id="videos" class="form-control" placeholder="https://youtu.be/YicuKTFPxX0"></div>
												</div>
												<a href="javascript:void(0);" id="add_more">Add More</a>
											</div>
										</div>
								<!-- <hr> -->
								<div>
									<div>
										<i class="fa fa-save"></i>
										<!-- <button class="btn btn-primary" id="save-reg">Submit</button> -->
										<input type="submit" name="register_submit" class="btn btn-primary" id="save-reg" valu="Submit">

										<input type="hidden" id="image_count" value="">
										<input type="hidden" id="video_count" value="">
									</div>
								</div>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>