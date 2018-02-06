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
						<div class="panel-title ">Subscriptions &raquo; <?php echo $subscription[0]->display_name.' ( #'.$user_id.' )'; ?></div>
					
						<div class="panel-options">
							<a href="#" data-rel="collapse"><i class="glyphicon glyphicon-refresh"></i></a>
							<a href="#" data-rel="reload"><i class="glyphicon glyphicon-cog"></i></a>
						</div>
					</div>

		  			<div class="content-box-large box-with-header">

		  				<div class="row">
		  					<!-- <div class="col-xs-8">
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
		  					</div> -->

	  						<div class="col-xs-12">
	  							<div class="dataTables_filter" id="example_filter">
	  								<label>Search: <input type="text" aria-controls="example"></label>
	  							</div>
	  						</div>
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

		  				<!-- <div class="row"><div class="col-xs-6"><div class="dataTables_info" id="example_info">Showing 1 to 10 of 57 entries</div></div><div class="col-xs-6"><div class="dataTables_paginate paging_bootstrap"><ul class="pagination"><li class="prev disabled"><a href="#">← Previous</a></li><li class="active"><a href="#">1</a></li><li><a href="#">2</a></li><li><a href="#">3</a></li><li><a href="#">4</a></li><li><a href="#">5</a></li><li class="next"><a href="#">Next → </a></li></ul></div></div></div> -->
		  			</div>
				</div>
			</div>
		</div>
	</div>
</div>