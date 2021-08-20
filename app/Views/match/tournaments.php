
<section class="section">
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3>Coaches</h3>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
        <?php if(!empty($data['team_coach_detail'])): ?>
          <?php foreach($data['team_coach_detail'] as $coach): ?>
            <div class="col-md-4 col-sm-12 col-4">
              <div class="card">
                <div class="card-body">  
                  <div class="row">
                    <div class="col-12">
                      <div class="d-flex align-items-center">
                        <div class="me-3">
                          <?php if($coach['image'] && !empty($coach['image'])){ ?>
                            <figure class="avatar mr-2 avatar-xl">
                              <img src="<?= base_url().'/public/uploads/profile_images/'.$coach['user_id'].'/'.$coach['image'] ?>" alt="...">
                            </figure>
                          <?php } else {?>
                            <figure class="avatar mr-2 avatar-xl">
                                <img src="https://kctherapy.com/wp-content/uploads/2019/09/default-user-avatar-300x293.png" alt="...">
                            </figure>
                          <?php }?>
                        </div>
                        <div class="">
                          <span><h5><?= $coach['first_name'] ?> <?= $coach['last_name'] ?></h5></span>
                          <span><h6><?php echo  $data['designation'][$coach['designation']] ?></h6></span>
                        </div>
                      </div>
                          
                    </div>     
                  </div>
                    </div>
                  
                </div>
              </div>
            <?php endforeach;?>
          <?php endif; ?>    
          </div>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3>Players</h3>
                  </div>
                  <div class="card-body">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Player Picture</th>
                          <th>Player Name</th>
                          <th style="text-align: center;" >Yellow Cards</th>
                          <th style="text-align: center;" >Red Cards</th>
                          <th style="text-align: center;" >Assists</th>
                          <th style="text-align: center;" >Goals</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i=0;?>
                      <?php if(!empty($data['player_list'])): ?>
                      <?php foreach($data['player_list'] as $player): ?> 
                        <tr>
                          <td><?= ++$i; ?></td>
                          <td>
                          <?php if($player['image'] && !empty($player['image'])): ?>
                            <figure class="avatar mr-1 ">
                              <img src="<?php echo base_url().'/public/uploads/profile_images/'.$player['player_id'].'/'.$player['image'] ?>" alt="image">
                            </figure>
                          <?php else: ?>
                            <figure class="avatar mr-2">
                              <img src="https://kctherapy.com/wp-content/uploads/2019/09/default-user-avatar-300x293.png" alt="image">
                            </figure>
                          <?php endif; ?>
                          </td>
                          <td><?= $player['first_name']; ?> &nbsp; <?= $player['last_name']; ?></td>
                          <td style="text-align: center;" ><?= $player['total_yc']; ?></td>
                          <td style="text-align: center;" ><?= $player['total_rc']; ?></td>
                          <td style="text-align: center;" ><?= $player['total_a']; ?></td>
                          <td style="text-align: center;" ><?= $player['total_g']; ?></td>
                        </tr>
                      <?php endforeach; ?>
                      <?php else:?>
                        <tr class="table-secondary"><td colspan="7" style="text-align: center;">there are no players</td></tr>
                      <?php endif; ?>
                      
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3>Matches</h3>
                  </div>
                  <div class="card-body">
                    <table class="table table-stripe" id="table-4">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Tournament</th>
                          <th>Team logo</th>
                          <th>Home Team</th>
                          <th>Scores</th>
                          <th>Opposing Team</th>
                          <th>Opposing Team logo</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $j=0; ?>
                        <?php if(!empty($data['team_matchs'])): ?>
                          <?php foreach($data['team_matchs'] as $match): ?>
                            <tr>
                              <td><?= ++$j; ?></td>
                              <td><?= $match['tournament_name'] ?></td>
                              <td>
                                <figure class="avatar mr-2 avatar-xl">
                                  <img src="<?php echo  base_url().'/public/uploads/team_images/'.$match['team_id'].'/'.$match['team_logo'] ?>" alt="...">
                                </figure>
                              </td>
                              <td style="color: <?php echo ($match['kit_color'] == 1)? 'blue' : 'red'; ?>;"><h5><?= $match['team_name']; ?></h5></td>
                              <td style="text-align: center;">
                                <h5><?php if(!empty($match['score'])){ echo $match['score']['team_score'] ?> &nbsp;&nbsp; - &nbsp;&nbsp; <?php echo $match['score']['opponent_score']; } ?></h5>
                                <?php if( !empty($match['score']) && $match['score']['winner_team'] == 0): ?>
                                  <h4><span class="badge badge-danger">Draw</span></h4>
                                <?php elseif(!empty($match['score']) && $match['score']['team_score'] > $match['score']['opponent_score']): ?>
                                  <h4><span class="badge badge-success"><?= $match['team_name']; ?> won</span></h4>
                                <?php elseif(!empty($match['score']) && $match['score']['team_score'] < $match['score']['opponent_score']): ?>
                                  <h4><span class="badge badge-success"><?= $match['opponent_team_name']; ?> won</span></h4>
                                <?php else: ?>
                                <?php endif; ?>
                              </td>
                              <td style="color: <?php echo !($match['kit_color'] == 1)? 'blue' : 'red'; ?>;"><h5><?= $match['opponent_team_name']; ?></h5></td>
                              <td>
                                <figure class="avatar mr-2 avatar-xl">
                                  <img src="<?php echo  base_url().'/public/uploads/team_images/'.$match['opponent_team_id'].'/'.$match['opponent_team_logo'] ?>" alt="...">
                                </figure>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php endif; ?>

                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            
<!--
            <?php if(!empty($data['team_matchs'])): ?>
            <div class="row">
              <?php foreach($data['team_matchs'] as $match): ?>
                
               <div class="col-md-4 col-sm-12 col-4">
                 <div class="card">
                   <div class="card-header"><h4><?= $match['tournament_name'] ?></h4></div>
                   <div class="card-body">
                     <div class="row">
                        <div class="col-6 d-flex justify-content-center">
                              <figure class="avatar mr-2 avatar-xl">
                                <img src="<?php echo  base_url().'/public/uploads/team_images/'.$match['team_id'].'/'.$match['team_logo'] ?>" alt="...">
                              </figure>
                              
                        </div>
                        <div class="col-6 d-flex justify-content-center">
                              <figure class="avatar mr-2 avatar-xl">
                                <img src="<?php echo  base_url().'/public/uploads/team_images/'.$match['opponent_team_id'].'/'.$match['opponent_team_logo'] ?>" alt="...">
                              </figure>
                        </div>
                     </div>

                     <div class="row mt-2">
                      <div class="col-6 d-flex justify-content-center">
                        <h5><?= $match['team_name']; ?></h5>

                      </div>
                      <div class="col-6 d-flex justify-content-center">
                        <h5><?= $match['opponent_team_name']; ?></h5>
                      </div>
                     </div>

                     <div class="row">
                      <div class="col-6 d-flex justify-content-center">
                        <span class="mt-3" style="height: 25px; width: 25px; background-color: <?php echo ($match['kit_color'] == 1)? 'blue' : 'red'; ?>; border-radius: 50%; display: inline-block;"></span>
                      </div>
                      <div class="col-6 d-flex justify-content-center">
                        <span class="mt-3" style="height: 25px; width: 25px; background-color: <?php echo !($match['kit_color'] == 1)? 'blue' : 'red'; ?>; border-radius: 50%; display: inline-block;"></span>
                      </div>
                     </div>

                     <?php if(!empty($match['score'])): ?>
                      <div class="row">
                        <div class="col-12 d-flex justify-content-center">

                              

                              <h4><?= $match['score']['team_score'] ?></h4>
                              &nbsp;&nbsp; <h4>-</h4> &nbsp;&nbsp;
                              <h4><?= $match['score']['opponent_score'] ?></h4>
                              
                        </div>
                        <div class="col-12 d-flex justify-content-center">
                          <?php if($match['score']['winner_team'] == 0): ?>
                          <h4><span class="badge badge-danger">Draw</span></h4>
                          <?php endif; ?>
                        </div>1
                      </div>
                     <?php else: ?>
                      <div class="row">
                        
                        <div class="col-12 d-flex justify-content-center">
                          
                          <h4><span class="badge badge-secondary">Upcoming</span></h4>
                          
                        </div>
                      </div>
                      <?php endif; ?>
                   </div>
                 </div>
               </div>
              <?php endforeach; ?>
              </div>
              <?php endif; ?>
				-->
            
          </div>
</section>
     
      