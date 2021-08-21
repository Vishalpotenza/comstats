
<section class="section">
	<div class="section-body">
		<div class="row">
			<div class="col-12">
				<div class="card">
					 <div class="card-header">
						<h3>Match <?php echo isset($data['tournament']) ? " - ".$data['tournament'] : '' ?> </h3>
						
					</div>
				</div>
			</div>
		</div>
		<div class="row">        
            <div class="col-md-4 col-sm-12 col-4">
				<div class="card">
					<div class="card-body">  
						<div class="row">
							<div class="col-12">
								<div class="d-flex align-items-center">
									<div class="me-3">
									  <?php if($data['team_logo'] && !empty($data['team_logo'])){ ?>
										<figure class="avatar mr-2 avatar-xl">
										  <img src="<?= base_url().'/public/uploads/team_images/'.$data['team_id'].'/'.$data['team_logo'] ?>" alt="...">
										</figure>
									  <?php } else {?>
										<figure class="avatar mr-2 avatar-xl">
											<img src="https://kctherapy.com/wp-content/uploads/2019/09/default-user-avatar-300x293.png" alt="...">
										</figure>
									  <?php }?>
									</div>
									<div class="">
									  <span class="team_detail_view" id="<?= $data['team_id'] ?>"><h5><?= $data['team_name'] ?> </h5></span>
									  
									</div>
								</div>
							</div>     
						</div>
                    </div>
                  
                </div>
            </div>
			<div class=" offset-1 col-md-2 col-sm-12 col-2">
				<div class="card">
					<div class="card-body">  
						<div class="row">
							<div class="col-12">
								<div class="d-flex align-items-center">
									<div class="me-3">
									  
									</div>
									<div class=""><p>
										<?= date('H:i',strtotime($data['datetime'])) ?> 
									  </p>
									</div>
									<div class="">
										<?= date('y-m-d',strtotime($data['datetime'])) ?> 
									  
									</div>
								</div>
							</div>     
						</div>
                    </div>
                  
                </div>
            </div>
            <div class="offset-1 col-md-4 col-sm-12 col-4">
				<div class="card">
					<div class="card-body">  
						<div class="row">
							<div class="col-12">
								<div class="d-flex align-items-center">
									<div class="me-3">
									  <?php if($data['opponent_team_logo'] && !empty($data['opponent_team_logo'])){ ?>
										<figure class="avatar mr-2 avatar-xl">
										  <img src="<?= base_url().'/public/uploads/team_images/'.$data['opponent_team_id'].'/'.$data['opponent_team_logo'] ?>" alt="...">
										</figure>
									  <?php } else {?>
										<figure class="avatar mr-2 avatar-xl">
											<img src="https://kctherapy.com/wp-content/uploads/2019/09/default-user-avatar-300x293.png" alt="...">
										</figure>
									  <?php }?>
									</div>
									<div class="">
									  <span class="team_detail_view" id="<?= $data['opponent_team_id'] ?>"><h5><?= $data['opponent_team_name'] ?> </h5></span>
									  
									</div>
								</div>
							</div>     
						</div>
                    </div>
                  
                </div>
            </div>            
        </div>
		<div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3>Players</h3>
                  </div>
                  <div class="card-body">
                    <table class="table table-striped" id="table-3">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Picture</th>
                          <th>Name</th>
                          <th>Position</th>
                          <th style="text-align: center;" >Yellow Cards</th>
                          <th style="text-align: center;" >Red Cards</th>
                          <th style="text-align: center;" >Assists</th>
                          <th style="text-align: center;" >Goals</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i=0;?>
                      <?php if(isset($data['player_list']) && !empty($data['player_list'])){ ?>
                      <?php foreach($data['player_list'] as $player): ?> 
                        <tr>
                          <td><?= ++$i; ?></td>
                          <td>
                          <?php if($player['image'] && !empty($player['image'])): ?>		
                            <figure class="avatar mr-1 ">
                              <img src="<?php echo $player['image']; ?>" alt="image">
                            </figure>
                          <?php else: ?>
                            <figure class="avatar mr-2">
                              <img src="https://kctherapy.com/wp-content/uploads/2019/09/default-user-avatar-300x293.png" alt="image">
                            </figure>
                          <?php endif; ?>
                          </td>
                          <td><span class="user_detail_view" id="<?= $player['player_id'] ?>"><?= $player['first_name']; ?> &nbsp; <?= $player['last_name']; ?> </span></td>
                          <td style="text-align: center;" ><?= $player['position']; ?></td>
                          <td style="text-align: center;" ><?= $player['total_yc']; ?></td>
                          <td style="text-align: center;" ><?= $player['total_rc']; ?></td>
                          <td style="text-align: center;" ><?= $player['total_a']; ?></td>
                          <td style="text-align: center;" ><?= $player['total_g']; ?></td>
                        </tr>
                      <?php endforeach; ?>
                      <?php }else{?>
                        <tr class="table-secondary"><td colspan="7" style="text-align: center;">there are no players</td></tr>
                      <?php } ?>
                      
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
		
		
		
    </div>
</section>

  