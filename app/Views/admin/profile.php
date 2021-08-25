<div class="modal1 fade1 bd-edit-club-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body"><?php
					if(isset($data['admin_data']) && !empty($data['admin_data'])){
						$admin_data = $data['admin_data']; ?>
                   <form class="edit_admin_form" id="edit_admin_form" method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                      <input type="hidden"  id="edit_club_id1" name="club_id" >
                      <div class="form-group col-md-6">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name1" name="first_name" value="<?= $admin_data['first_name'] ?>" >
                      </div>
					  <div class="form-group col-md-6">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name1" name="last_name" value="<?= $admin_data['last_name'] ?>">
                      </div>
					  <div class="form-group col-md-6">
                        <label for="firebase_server_key">Firebase Server Key</label>
                        <input type="text" class="form-control" id="firebase_server_key1" name="firebase_server_key" value="<?= $admin_data['firebase_server_key'] ?>" >
                      </div>
					  <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" id="email1" name="email" value="<?= $admin_data['email'] ?>" disabled>
                      </div>
					  <div class="form-group col-md-6" align="center">
                        <label for="inputAddress1">Profile image</label><br>
						  <img src="<?= base_url(); ?>/public/uploads/admin/<?= $admin_data['id']."/".$admin_data['image'] ?>" class="rounded-circle author-box-picture" height="100" /><br><br><br>
						  <input type="file" class="form-control1" id="image" name="image" >
                      </div>
					  
                      
					  
                    </div>
                    <div class="form-group col-3" align="center">
                      <button type="submit" role="button" form="edit_admin_form"  id="btneditadmin" class="btn btn-primary btn-lg btn-block">
                      Update
                    </button>
                    </div>                  
                   
                    </div>
                    
                  <!-- m-t-15 waves-effect --><?php
					}
				  ?>
                </form>
              </div>
            </div>
          </div>
        </div>