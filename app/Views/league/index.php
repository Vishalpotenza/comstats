          <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-2 col-lg-2">
                <div class="card">
                <button type="button" class="btn btn-primary" id="btnaddleague" data-toggle="modal"
                      data-target=".bd-example-modal-lg">Add League
                      </button>
                </div>
              </div>
           
            </div>
          <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>League Lists</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
					
					<table class="table table-striped" id="leaguetable">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th>League Name</th>                            
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
							<?php
                           $i=0;
                            foreach ($league_details as $league):
                             
                          ?>
							  <tr>
								<td class="align-middle">
								  <?= ++$i ?>

								</td>
								<td><?= $league['name'] ?></td>
								
								
								
								<td>
									
									<a href="#" class="btn btn-primary edit_league" id=<?= $league['id'] ?>  >Edit</a>
									<a href="#" class="btn btn-danger deleteleague" id=<?= $league['id'] ?> >Delete</a>
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
                <h5 class="modal-title" id="myLargeModalLabel">ADD League</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                   <form class="add_league" id="add_league" method="POST">
                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="Leaguename">League Name</label>
                        <input type="text" class="form-control" id="leaguename" name="leaguename" placeholder="League Name">
                      </div>
                      
                    </div>        
                    
                  
                    <button type="submit" role="button" form="add_league"  id="btnaddleague" class="btn btn-primary btn-lg btn-block">
                      Add League
                    </button>
                  <!-- m-t-15 waves-effect -->
                </form>
              </div>
            </div>
          </div>
        </div>
        <!--edit model-->
                <!-- Large modal -->
        <div class="modal fade bd-edit-League-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Edit LEAGUE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                   <form class="edit_league_form" id="edit_league_form" method="POST">
                    
                    <div class="form-group">
						<input type="hidden" id="edit_data_id" name="edit_data_id" value="">
                      <label for="Leaguename">League Name</label>
                        <input type="text" class="form-control" id="leaguename1" name="leaguename" placeholder="League Name">
                    </div>
                    
                    <button type="submit" role="button" form="edit_league_form"  id="btneditleague" class="btn btn-primary btn-lg btn-block">
                      Edit League
                    </button>
                  <!-- m-t-15 waves-effect -->
                </form>
              </div>
            </div>
          </div>
        </div>
     
