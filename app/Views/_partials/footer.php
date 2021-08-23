<footer class="main-footer">

        <div class="footer-left">

          <a href=<?= base_url() ?>>COMSTATS</a></a>

        </div>

        <div class="footer-right">

        </div>

</footer>

<!-- Large modal -->
        <div class="modal fade bd-View-User-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                   <form class="edit_club_form" id="edit_club_form" method="POST">
                    <div class="form-row">
                      <input type="hidden"  id="edit_club_id" name="club_id" >
                      <div class="form-group col-md-4">
                        <label for="clubname1">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" >
                      </div>
                      <div class="form-group col-md-4">
                        <label for="contactno1">Last Name</label>
                        <input type="tel" class="form-control" id="last_name" name="last_name" >
                      </div>
					  <div class="form-group col-md-4">
                        <label for="inputAddress1">Address</label>
						<input type="text" class="form-control" id="address" name="address" >
                      </div>
                    </div>                   

                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label for="inputcountry1">Nationality</label>
                        <input type="text" class="form-control" id="nationality" name="nationality" >
                      </div>
                    <div class="form-group col-md-4">
                        <label for="inputState1">Flag</label>
						
						<figure class="avatar mr-2 avatar-xl">
							<img src="https://kctherapy.com/wp-content/uploads/2019/09/default-user-avatar-300x293.png" id="flag_image" alt="...">
						</figure>
                      </div>
                      
                   
                    </div>
					<div class="form-row">
                      <div class="form-group col-md-4">
                        <label for="inputcountry1">age</label>
                        <input type="text" class="form-control" id="age" name="age" >
                      </div>
                    <div class="form-group col-md-4">
                        <label for="inputState1">gender</label>
						<input type="text" class="form-control" id="gender" name="gender" >
                      </div>
                      <div class="form-group col-md-4">
                        <label for="inputcity1">height</label>
                        <input type="text" class="form-control" id="height" name="height" >
                      </div>
                   
                    </div>
                   <div class="form-row">
						 <div class="form-group col-md-4">
							<label for="inputcountry1">weight</label>
							<input type="text" class="form-control" id="weight" name="weight" >
						 </div>
						<div class="form-group col-md-4">
							<label for="inputState1">image</label>
							<img src="" id="img" />
							<figure class="avatar mr-2 avatar-xl">
								<img src="https://kctherapy.com/wp-content/uploads/2019/09/default-user-avatar-300x293.png" id="profile_img" alt="...">
							</figure>
						</div>
                      
                   
                    </div>
                   
                  <!-- m-t-15 waves-effect -->
                </form>
              </div>
            </div>
          </div>
        </div> 
		
		
		
		<!-- Large modal -->
        <div class="modal fade bd-edit-firebasesetting-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Firebase</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                   <form class="edit_firebasesetting_form" id="edit_firebasesetting_form" method="POST" enctype="multipart/form-data">
                    
					
                    <div class="form-group">
						<input type="hidden" id="edit_data_id2" name="edit_data_id" value="">
                      <label for="f_value">Server Key</label>
                        <input type="text" class="form-control" id="firebase_server_key" name="firebase_server_key" placeholder="Firebase value">
                    </div>			
                    
                    <button type="submit" role="button" form="edit_firebasesetting_form"  id="btneditfirebase" class="btn btn-primary btn-lg btn-block">
                      Update
                    </button>
                  <!-- m-t-15 waves-effect -->
                </form>
              </div>
            </div>
          </div>
        </div>