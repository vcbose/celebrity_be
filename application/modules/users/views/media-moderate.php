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
						<div class="panel-title ">Profiles &raquo;</div>
					
						<div class="panel-options">
							<a href="#" data-rel="collapse"><i class="glyphicon glyphicon-refresh"></i></a>
							<a href="#" data-rel="reload"><i class="glyphicon glyphicon-cog"></i></a>
						</div>
					</div>

		  			<div class="content-box-large box-with-header">
		  				<form name="media_moderate" action="" method="post">
			  				<table cellpadding="0" cellspacing="0" border="0" class="table table-hover">
								<thead>
									<tr>
										<th><input class="form-control" type="text" name="user_name"></th>
										<th><select name="user_type" class="form-control"><option value="">Select Type</option><option value="3">Talent</option><option value="2">Director</option></select></th>
										<th>
											<select name="plan" class="form-control">
												<option value="">Select Plan</option>
												<?php
												foreach ($plans as $p_key => $p_value) {
													echo '<option value="'.$p_key.'">'.$p_value.'</option>';
												}
												?>
											</select>
										</th>
										<th>
											<div class="checkbox" >
												<label><input type="checkbox" name="user_status" value="1">Active users</label>
											</div>
										</th>
										<th><input class="form-control" type="date" name="date"></th>
										<th>
											<div class="checkbox" >
												<label><input class="checkbox" type="checkbox" name="dp" value="1">Only profile pic</label>
											</div>
										</th>
										<th><input class="btn btn-primary" type="submit" name="search" value="Search"></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</form>

		  				</table>
		  				<div>
		  					<?php //echo "<pre>"; print_r($plans); ?>
		  					<table cellpadding="0" cellspacing="0" border="0" class="table table-hover" id="profiles">
								<thead>
									<tr>
										<th>#</th>
										<th>Name</th>
										<th>Type</th>
										<th>Subscription</th>
										<th>User status</th>
										<th>Media</th>
										<th>Uploaded On</th>
										<th>Is Profile</th>
										<!-- <th>Subscription</th> -->
										
									</tr>
								</thead>
								<tbody>
									<?php 
									$i = 1;
									if( !empty($list) ):
									foreach ($list as $key => $value) {	
									?>
									<tr class="odd gradeX">
										<td><a href="/profile-detail/view/<?php echo $value->user_type; ?>/<?php echo $value->user_id; ?>"><?php echo $i; ?></a></td>
										<td><a href="/profile-detail/view/<?php echo $value->user_type; ?>/<?php echo $value->user_id; ?>"><?php echo strtolower($value->user_name); ?></a></td>
										<td><?php echo ($value->user_type == 3)?'Talent':'Director'; ?></td>
										<td class="center"><?php echo $value->plan_name; ?></td>
										<td class="center"><?php echo ($value->user_status == 1)?'Active':'De active'; ?></td>
										<td><a href="http://celebritybe.local/assets/uploads/<?php echo $value->user_id.'/'.$value->media_name; ?>"><?php echo $value->media_name; ?></td>
										<td class="center"><?php echo $value->uploaded_on; ?></td>
										<td class="center"><?php echo ($value->dp)?'Yes':'No'; ?></td>
										<!-- <td class="center"></td> -->
										
									</tr>
									<?php
										$i++;
									}
									endif;
									?>
									

								</tbody>
							</table>
		  				</div>

		  				
		  			</div>
				</div>
			</div>
		</div>
	</div>
</div>