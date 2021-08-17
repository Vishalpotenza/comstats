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
						   // foreach ($data['team_matchs'] as $team):
                             
                          ?>
							  <tr>
								<td class="align-middle">
								  <?= ++$i ?>

								</td>
								
								
								
								
								
							  </tr>
                          <?php  //endforeach;  ?>
                          
                        </tbody>
                      </table>                     
                    </div>
                  </div>
                </div>
              </div>
            </div>
          
          </div>
        </section>
     
      