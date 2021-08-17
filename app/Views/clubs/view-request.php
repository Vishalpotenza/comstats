<?php
if($team_requests){
// echo "<pre>";
// print_r($team_requests);
}
?>
<section class="section">
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card card-info">
          <div class="card-header">
            <h4>Requests</h4>
          </div>
		  <?php foreach($team_requests as $data){ ?>
            <div class="card-body">
              <div class="form-row">
                <div class="form-group col-md-3">
                  <label for="clubname">Clubs Name</label>
                  <input type="text" class="form-control" name="clubname" placeholder="Club Name" value="<?php echo $data["club"];   ?>" disabled>
                </div>
                <div class="form-group col-md-3">
                  <label for="contactno">coach Name</label>
                  <input type="tel" class="form-control" name="contactno" placeholder="Contact number" value="<?php echo $data["coach"];   ?>" disabled>
                </div>
				<div class="form-group col-md-3">
                  <label for="contactno">Team Name</label>
                  <input type="tel" class="form-control" name="contactno" placeholder="Contact number" value="<?php echo $data["team"];   ?>" disabled>
                </div>
				<div class="form-group col-md-3">
                  <!--<label for="contactno">Action</label>-->
				  <a href="<?= base_url()."/admin/clubs/request_action/?tm_id=".$data['id']."&action=1"  ?>" class="btn btn-primary accept_request form-control accept_request" id=<?= $data['id'] ?>  >Accept</a>
                  <a href="<?= base_url()."/admin/clubs/request_action/?tm_id=".$data['id']."&action=2"  ?>" class="btn btn-danger reject_request form-control reject_request" id=<?= $data['id'] ?> >Reject</a>
                  
                </div>
              </div>
              

              
            </div>
		  <?php
		  } 
		  if(empty($team_requests)){ ?>
			<div class="card-body">
              <div class="form-row">
			  No request 
			  </div>
              </div>
			  
			  <?php
		  }
		  ?>
			
			
			
       
        </div>
      </div>
    </div>
</section>
       

     
