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


		  				<div>
		  					<?php //echo "<pre>"; print_r($plans); ?>
		  					<table cellpadding="0" cellspacing="0" border="0" class="table table-hover" id="profiles">
								<thead>
									<tr>
										<th>#</th>
										<th>Talent</th>
										<th>Gender</th>
										<th>Age</th>
										<th>Categories</th>
										<th>From</th>
										<th>Added On</th>
										<!-- <th>Subscription</th> -->
										<th>Subscription</th>
										<th>User status</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$i = 1;
									foreach ($userdata as $key => $value) {	
									?>
									<tr class="odd gradeX">
										<td><a href="/profile-detail/view/<?php echo $value->u_id; ?>"><?php echo $i; ?></a></td>
										<td><a href="/profile-detail/view/<?php echo $value->u_id; ?>"><?php echo ucfirst($value->display_name); ?></a></td>
										<td><?php echo $value->gender; ?></td>
										<td><?php $age = date_diff(date_create($value->dob), date_create('today'))->y; echo ($age <= 0)?'Baby':$age; ?></td>
										<td class="center"><?php $a_tc = explode(',', $value->talent_category); foreach ($a_tc as $tc_ic) {

											echo $tc = (isset($settings['talents_category'][$tc_ic]))?$settings['talents_category'][$tc_ic].', ':'';
										} ?></td>
										<td class="center"><?php echo $value->city; ?></td>
										<td class="center"><?php echo $value->created_on; ?></td>
										<!-- <td class="center"></td> -->
										<td class="center"><a href="/subscriptions/<?php echo $value->u_id; ?>"><?php echo isset($plans[$value->plan_id])?$plans[$value->plan_id]:' -- '; ?></a></td>
										<td class="center"><?php echo ($value->user_status == 1)?'Active':'Deactive'; ?></td>
									</tr>
									<?php
										$i++;
									}
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