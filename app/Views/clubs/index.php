          <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-2 col-lg-2">
                <div class="card">
                <button type="button" class="btn btn-primary" id="btnaddclub" data-toggle="modal"
                      data-target=".bd-example-modal-lg">Add Club
                      </button>
                </div>
              </div>
           
            </div>
          <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Club Lists</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped" id="table-1">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th>Club Name</th>
                            <th>Country</th>
                            <th>State</th>
                            <th>Team Request</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                      
                          <?php
                           $i=0;
                            foreach ($club_details as $clubs):
                             
                          ?>
                              <tr>
                            <td class="align-middle">
                              <?= ++$i ?>

                            </td>
                            <td><?= $clubs['club_name'] ?></td>
                            <td class="align-middle">
                              <?= $clubs['country_name'] ?>
                            </td>
                            <td>
                              <?= $clubs['state_name'] ?>
                            </td>
                            <td>
                              <div class="badge badge-success badge-shadow">2</div>
                            </td>
                            <td>
                                <!-- <a href="#" class="btn btn-info">View Requests</a> -->
                                <a href="#" class="btn btn-primary edit_club" id=<?= $clubs['club_id'] ?>  >Edit</a>
                                <a href="#" class="btn btn-danger deleteclub" id=<?= $clubs['club_id'] ?> >Delete</a>
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
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">ADD CLUB</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                   <form class="add_club" id="add_club" method="POST">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="clubname">Clubs Name</label>
                        <input type="text" class="form-control" id="clubname" name="clubname" placeholder="Club Name">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="contactno">Contact No</label>
                        <input type="tel" class="form-control" id="contactno" name="contactno" placeholder="Contact number">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputAddress">Address</label>
                      <input type="text" class="form-control" id="inputAddress" name="inputAddress" placeholder="1234 Main St">
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label for="inputcountry">Country</label>
                        <select id="inputcountry"  name="inputcountry" class="form-control">
                          <option selected>Choose...</option>
                        </select>
                      </div>
                    <div class="form-group col-md-4">
                        <label for="inputState">State</label>
                        <select id="inputState" name="inputState" class="form-control">
                          <option selected>Choose...</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <label for="inputcity">City</label>
                        <select id="inputcity" name="inputcity" class="form-control">
                          <option selected>Choose...</option>
                        </select>
                      </div>
                      <!-- <div class="form-group col-md-2">
                        <label for="inputZip">Zip</label>
                        <input type="text" class="form-control" id="inputZip">
                      </div> -->
                    </div>
                  <!-- <button type="submit" name="btnaddclub" class="btn btn-primary">Add Club</button> -->
                    <button type="submit" role="button" form="add_club"  id="btnaddclub" class="btn btn-primary btn-lg btn-block">
                      Add Club
                    </button>
                  <!-- m-t-15 waves-effect -->
                </form>
              </div>
            </div>
          </div>
        </div>
        <!--edit model-->
                <!-- Large modal -->
        <div class="modal fade bd-edit-club-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">ADD CLUB</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                   <form class="edit_club_form" id="edit_club_form" method="POST">
                    <div class="form-row">
                      <input type="hidden"  id="edit_club_id" name="club_id" >
                      <div class="form-group col-md-6">
                        <label for="clubname1">Clubs Name</label>
                        <input type="text" class="form-control" id="clubname1" name="clubname1" placeholder="Club Name">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="contactno1">Contact No</label>
                        <input type="tel" class="form-control" id="contactno1" name="contactno1" placeholder="Contact number">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputAddress1">Address</label>
                      <input type="text" class="form-control" id="inputAddress1" name="inputAddress1" placeholder="1234 Main St">
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label for="inputcountry1">Country</label>
                        <select id="inputcountry1"  name="inputcountry1" class="form-control">
                          <option selected>Choose...</option>
                        </select>
                      </div>
                    <div class="form-group col-md-4">
                        <label for="inputState1">State</label>
                        <select id="inputState1" name="inputState1" class="form-control">
                          <option selected>Choose...</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <label for="inputcity1">City</label>
                        <select id="inputcity1" name="inputcity1" class="form-control">
                          <option selected>Choose...</option>
                        </select>
                      </div>
                   
                    </div>
                    <button type="submit" role="button" form="edit_club_form"  id="btneditclub" class="btn btn-primary btn-lg btn-block">
                      Edit Club
                    </button>
                  <!-- m-t-15 waves-effect -->
                </form>
              </div>
            </div>
          </div>
        </div>
     
