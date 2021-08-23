          <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-2 col-lg-2">
                <div class="card">
                <button type="button" class="btn btn-primary" id="btnaddfirebase" data-toggle="modal"
                      data-target=".bd-example-modal-lg">Add Firebase
                      </button>
                </div>
              </div>
           
            </div>
          <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Firebase Lists</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
					
					<table class="table table-striped" id="table-5">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th>Key</th>                            
                            <th>Value</th>                            
                           <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
							<?php							
                           $i=0;
						   foreach ($data['firebases'] as $firebase):
                             
                          ?>
							  <tr>
								<td class="align-middle">
								  <?= ++$i ?>

								</td>
								<td><?= $firebase['f_key'] ?></td>
								<td><?= $firebase['f_value'] ?></td>
								<td>
									
									<a href="#" class="btn btn-primary edit_firebase" id=<?= $firebase['id'] ?>  >Edit</a>
									<a href="#" class="btn btn-danger deletefirebase" id=<?= $firebase['id'] ?> >Delete</a>
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
                <h5 class="modal-title" id="myLargeModalLabel">ADD Firebase</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                   <form class="add_firebase" id="add_firebase" method="POST" enctype="multipart/form-data">
                    <div class="form-row">
						<div class="form-group col-md-12">
						 <label for="f_key">Firebase Key</label>
							<input type="text" class="form-control" id="f_key" name="f_key" placeholder="Firebase Key">						  
						</div>
						<div class="form-group col-md-12">
							<label for="f_value">Firebase Value</label>
							<input type="text" class="form-control" id="f_value" name="f_value" placeholder="Firebase Value">
						</div>
						
                      
                    </div>        
                    
                  
                    <button type="submit" role="button" form="add_firebase"  id="btnaddfirebase" class="btn btn-primary btn-lg btn-block">
                      Add Firebase
                    </button>
                  <!-- m-t-15 waves-effect -->
                </form>
              </div>
            </div>
          </div>
        </div>
        <!--edit model-->
                <!-- Large modal -->
        <div class="modal fade bd-edit-firebase-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Edit Firebase</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                   <form class="edit_firebase_form" id="edit_firebase_form" method="POST" enctype="multipart/form-data">
                    
					<div class="form-group">
                      <label for="f_key">Firebase Key</label>
                        <input type="text" class="form-control" id="f_key1" name="f_key" placeholder="Firebase Key">
                    </div>
                    <div class="form-group">
						<input type="hidden" id="edit_data_id" name="edit_data_id" value="">
                      <label for="f_value">Firebase Value</label>
                        <input type="text" class="form-control" id="f_value1" name="f_value" placeholder="Firebase value">
                    </div>					
                    
                    <button type="submit" role="button" form="edit_firebase_form"  id="btneditfirebase" class="btn btn-primary btn-lg btn-block">
                      Edit Firebase
                    </button>
                  <!-- m-t-15 waves-effect -->
                </form>
              </div>
            </div>
          </div>
        </div>
     
