          <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-2 col-lg-2">
                <div class="card">
                <button type="button" class="btn btn-primary" id="btnaddteam" data-toggle="modal"
                      data-target=".bd-example-modal-lg">Add Team
                      </button>
                </div>
              </div>
           
            </div>
          <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Team Lists</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
					
					<table class="table table-striped" id="teamtable">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th>Club Name</th>                            
                            <th>Team Name</th>                            
                            <th>Team Logo</th>                            
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
							<?php							
                           $i=0;
						   foreach ($data as $team):
                             
                          ?>
							  <tr>
								<td class="align-middle">
								  <?= ++$i ?>

								</td>
								<td><?= $team['club_name'] ?></td>
								<td><?= $team['team_name'] ?></td>
								<td><img alt="image" src="<?= base_url().'/public/uploads/team_images/'.$team['team_id'].'/'.$team['team_logo'] ?>" class="rounded-circle" width="50"
                              data-toggle="tooltip" title="<?= $team['team_name'] ?>"></td>
								
								
								
								<td>
									
									<a href="#" class="btn btn-primary edit_team" id=<?= $team['team_id'] ?>  >Edit</a>
									<a href="#" class="btn btn-danger deleteteam" id=<?= $team['team_id'] ?> >Delete</a>
								</td>
							  </tr>
                          <?php  endforeach;  ?>
                          
                        </tbody>
                      </table>                     
                    </div>
                  </div>
                </div>
              </div>
            </div>
          
          </div>
        </section>
     
       
        <!-- Large modal -->
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">ADD Team</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                   <form class="add_team" id="add_team" method="POST" enctype="multipart/form-data">
                    <div class="form-row">
						<div class="form-group col-md-12">
						  <label>Team Logo</label>
						  <input type="file"id="team_logo1" name="team_logo" class="form-control1">						  
						</div>
						<div class="form-group col-md-12">
							<label for="teamname">Team Name</label>
							<input type="text" class="form-control" id="team_name" name="team_name" placeholder="Team Name">
						</div>
						<div class="form-group col-md-12">
							<label>Select Club</label>
							<select class="form-control" id="club_id1" name="club_id">
								<option value=''>Select club</option>
								<?php foreach($club_list as $data){ ?>
									<option value="<?php echo $data['club_id']; ?>"><?php echo $data['club_name']; ?></option>
								<?php } ?>
							</select>
						</div>
                      
                    </div>        
                    
                  
                    <button type="submit" role="button" form="add_team"  id="btnaddteam" class="btn btn-primary btn-lg btn-block">
                      Add Team
                    </button>
                  <!-- m-t-15 waves-effect -->
                </form>
              </div>
            </div>
          </div>
        </div>
        <!--edit model-->
                <!-- Large modal -->
        <div class="modal fade bd-edit-team-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Edit Team</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                   <form class="edit_team_form" id="edit_team_form" method="POST" enctype="multipart/form-data">
                    
					<div class="form-group">
                      <label>Team Logo</label>
                      <input type="file"id="team_logo1" name="team_logo" class="form-control1">
                    </div>
                    <div class="form-group">
						<input type="hidden" id="edit_data_id" name="edit_data_id" value="">
                      <label for="teamname">Team Name</label>
                        <input type="text" class="form-control" id="team_name1" name="team_name" placeholder="Team Name">
                    </div>
					
					<!--<div class="form-group">
							<label>Select Club</label>
							<select class="form-control" id="club_id1" name="club_id" >
								<option value=''>Select club</option>
								<?php foreach($club_list as $data){ ?>
									<option value="<?php echo $data['club_id']; ?>"><?php echo $data['club_name']; ?></option>
								<?php } ?>
							</select>
						</div>-->
                    
                    <button type="submit" role="button" form="edit_team_form"  id="btneditteam" class="btn btn-primary btn-lg btn-block">
                      Edit Team
                    </button>
                  <!-- m-t-15 waves-effect -->
                </form>
              </div>
            </div>
          </div>
        </div>
     
