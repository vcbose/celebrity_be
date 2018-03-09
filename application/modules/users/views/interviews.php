<!-- Modal -->
<div id="interview-model" class="modal fade" role="dialog">
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
      	  	<?php if($permission == 2){ ?>
      	  	<li><a data-toggle="pill" href="#new-intrw">Schedule</a></li>
      	  	<?php } ?>
      	</ul>

      	<hr>
      	<div class="tab-content" id="intrw-tab" <?php if($user_type == 2 || $permission == 2){ ?> data-from="<?php echo $triggers['from']; ?>" <?php } ?> data-to="<?php echo $triggers['to']; ?>">

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

      		<?php if($permission == 2){ ?>

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
			<?php } ?>

		</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>