<section class="section">
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card card-info">
          <div class="card-header">
            <h4>Club Details</h4>
          </div>
            <div class="card-body">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="clubname">Clubs Name</label>
                  <input type="text" class="form-control" name="clubname" placeholder="Club Name" value="<?= $team_requests["club_name"]   ?>" disabled>
                </div>
                <div class="form-group col-md-6">
                  <label for="contactno">Contact No</label>
                  <input type="tel" class="form-control" name="contactno" placeholder="Contact number" value="<?= $team_requests["contact_no"]   ?>" disabled>
                </div>
              </div>
              <div class="form-group">
                <label for="inputAddress">Address</label>
                <input type="text" class="form-control" name="inputAddress" placeholder="1234 Main St" value="<?= $team_requests["address"]   ?>" disabled>
              </div>

              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="inputcountry">Country</label>
                  <select   name="inputcountry" class="form-control" disabled>
                    <option selected><?=   $team_requests["country_name"]    ?></option>
                  </select>
                </div>
              <div class="form-group col-md-4">
                  <label for="inputState">State</label>
                  <select  name="inputState" class="form-control"  disabled>
                    <option selected><?=   $team_requests["state_name"]    ?></option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="inputcity">City</label>
                  <select id="inputcity" name="inputcity" class="form-control">
                    <option selected>Choose...</option>
                  </select>
                </div>
              </div>
            </div>
       
        </div>
      </div>
    </div>
</section>
       

     
