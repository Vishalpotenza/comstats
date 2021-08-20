          <section class="section">
          <div class="section-body">
           
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
                            <th>Team Name</th>
                            <th>Team Logo</th>                            
                            </tr>
                        </thead>
                        <tbody>
							<?php							
                           $i=0;
						   foreach ($data['club_member_team'] as $team):
                             
                          ?>
							  <tr>
								<td class="align-middle">
								  <?= ++$i ?>

								</td>
								<td><a href="<?= base_url()."/admin/team/team_match/?team_id=".$team['team_id'] ?>"><?= $team['team_name'] ?></a></td>
								<td><img alt="image" src="<?= base_url().'/public/uploads/team_images/'.$team['team_id'].'/'.$team['team_logo'] ?>" class="rounded-circle" width="50"
                              data-toggle="tooltip" title="<?= $team['team_name'] ?>"></td>
								
								
								
								<!--<td>
									
									<a href="#" class="btn btn-primary edit_team" id=<?= $team['team_id'] ?>  >Edit</a>
									<a href="#" class="btn btn-danger deleteteam" id=<?= $team['team_id'] ?> >Delete</a>
								</td>-->
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
     
      