<?php

namespace App\Controllers;

class PlayerApiController extends ApiBaseController
{

    /**
     * Get-players-to-add
     * @endpoint players-to-add
     * @url: http://yourdomain.com/api/players-to-add
     * @param user_id : user_id
     */
    public function players_to_add()
    {
         if($this->authenticate_api())
        {   
            $response = array( "status" => "error" );
            $required_fields = array("user_id");
            $status = $this->verifyRequiredParams($required_fields);
            $user_id = $this->request->getVar("user_id");
           
            if($this->ifempty($user_id, "User id")!== true){
                $response['message'] = $this->ifempty($user_id, "User Id");
                $this->sendResponse($response);
            }
            if($this->ifexistscustom('tbl_team_member_relation', array('user_id' => $user_id, 'deletestatus'=> 0))!= true){
                $response['message'] = "No team data available";
                $this->sendResponse($response);
            }
            $user_details = $this->db->table('tbl_user_login')
                                ->join('tbl_user', 'tbl_user.user_id = tbl_user_login.user_id')
                                ->where(array('tbl_user.user_id' => trim($user_id)));
            if ( $user_details->countAllResults() > 0)
            {
                /**
                 * Coach list
                 */
                $coachs = $this->db->table('tbl_team_member_relation')->where(array('user_id'=> $user_id))->get()->getRowArray();
              
                $coachs_in_team = $this->db->table('tbl_team_member_relation')->where(array('team_id'=> $coachs['team_id'],'designation<>'=> 1, 'deletestatus' => 0))->get()->getResultArray();
               
                $coach_in_team = array();
                foreach($coachs_in_team as $coach){
                    $details = $this->getUserInfo($coach['user_id']);
                    unset($details['position']);
                    unset($details['height']);
                    unset($details['weight']);
                    unset($details['total_game']);
                    unset($details['nation_id']);
                    $details["dob"] = $this->get_mobile_date($details["dob"]);
                    $details['designation'] = $coach['designation'];
                    array_push($coach_in_team, $details);
                }

                /**
                 * Player not in list
                 */
                $player_list = $this->db->table('tbl_user')->where('user_type',0)->get()->getResultArray();
                $not_in_team_players = array();
                foreach($player_list as $player){
                   $not_in_team = $this->db->table('tbl_team_member_relation')->where(array('user_id'=> $player['user_id'], 'designation' => 1, 'deletestatus' => 0));
                   if($not_in_team->countAllResults() == 0)
                   {

                       $details = $this->getUserInfo($player['user_id']);
                        unset($details['height']);
                        unset($details['weight']);
                        unset($details['total_game']);
                        unset($details['nation_id']);
                        unset($details['p_id']);
                        $details["dob"] = $this->get_mobile_date($details["dob"]);
                       $details['player_id'] = $player['user_id'];
                       array_push($not_in_team_players, $details);
                   }
                }
                
                /**
                 * Player in team
                 */
                $team_players = array();
                 $players_in_team = $this->db->table('tbl_team_member_relation')->where(array('team_id'=> $coachs['team_id'],'designation' => 1, 'deletestatus' => 0))->get()->getResultArray();
              
                foreach($players_in_team as $player){
                    $details = $this->getUserInfo($player['user_id']);
                        unset($details['height']);
                        unset($details['weight']);
                        unset($details['total_game']);
                        unset($details['nation_id']);
                        unset($details['p_id']);
                        $details["dob"] = $this->get_mobile_date($details["dob"]);
                       $details['player_id'] = $player['user_id'];
                       array_push($team_players, $details);
                }


                if(!empty($not_in_team_players) || !empty($coach_in_team) || !empty($team_players)){
                    $response['status'] = "success";
                    $response['message'] = "Successfully Got Players";
                    $response['data']['coach'] = $coach_in_team;
                    $response['data']['players_not_in_team'] = $not_in_team_players;
                    $response['data']['players_in_team'] = $team_players;
                    $this->sendResponse($response);
                }

            } else {
                $response['message'] = "Please insert valid user id.";
                $this->sendResponse($response);
            }
             
        }
    }
    /**
     * Add Player to Team
     * @endpoint add-player-team
     * @url: http://yourdomain.com/api/add-player-team
     * @param coach_id : coach_id
     * @param player_id : player_id
     */
    public function add_players_team(){
        if($this->authenticate_api())
        {   
            $response = array( "status" => "error" );
            $required_fields = array("coach_id", 'player_id');
            $status = $this->verifyRequiredParams($required_fields);
            $coach_id = $this->request->getVar("coach_id");
            $player_id = $this->request->getVar("player_id");
           
            if($this->ifempty($coach_id, "Coach ID")!== true){
                $response['message'] = $this->ifempty($coach_id, "Coach ID");
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_user', $coach_id, 'user_id') != true){
                $response['message'] = "User does no exist.";
                $this->sendResponse($response);
            }

             if($this->ifempty($player_id, "Coach ID")!== true){
                $response['message'] = $this->ifempty($player_id, "Coach ID");
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_user', $player_id, 'user_id') != true){
                $response['message'] = "Player does no exist.";
                $this->sendResponse($response);
            }
            if($this->ifexistscustom('tbl_team_member_relation', array( 'user_id' => $player_id, 'deletestatus' => 0 ))){
                $response['message'] = "Player Already in Team !.";
                $this->sendResponse($response);
            }
            $team_id =   $coachs = $this->db->table('tbl_team_member_relation')->select('team_id')->where(array('user_id'=> $coach_id))->get()->getRowArray();
            $insertdata = array( 
                        "team_id"       =>   $team_id,
                        "user_id"       =>   $player_id,
                        "designation"   =>   1,
                     );
                
            $result = $this->db->table('tbl_team_member_relation')->insert($insertdata);
            
            if(!empty($result))
            {
                $response['status'] = "success";
                $response['message'] = 'Successfully joined team.';
                $this->sendResponse($response); 
            }
            else{
                 $response['message'] = 'Not able to join team.';
                 $this->sendResponse($response);
            }            
        }
    }
	/**

     * Get Players detail

     * @endpoint get-players-detail

     * @url: http://yourdomain.com/api/get-players-detail

     * @param coach_id : coach_id

     * @param player_id : player_id

     */
    public function get_players_detail(){

        if($this->authenticate_api()){
			
			$response = array( "status" => "error" );

            $required_fields = array('player_id');

            $status = $this->verifyRequiredParams($required_fields);

            $coach_id = $this->request->getVar("coach_id") ? $this->request->getVar("coach_id") : '';

            $player_id = $this->request->getVar("player_id");            

            if($this->ifexists('tbl_user', $player_id, 'user_id') != true){

                $response['message'] = "Player does no exist";

                $this->sendResponse($response);

            }
			
			if($coach_id){
				if($this->ifempty($coach_id, "Coach ID")!== true){

					$response['message'] = $this->ifempty($coach_id, "Coach ID");

					$this->sendResponse($response);

				}
				
				if($this->ifexists('tbl_user', $coach_id, 'user_id') != true){

					$response['message'] = "User does no exist.";

					$this->sendResponse($response);

				}
				
			}
			$details = $this->getUserInfo($player_id);
			
			unset($details['gender']);
			unset($details['weight']);
			unset($details['match_id']);
			unset($details['position_slug']);
			unset($details['p_id']);
			unset($details['email']);
			unset($details['nation_id']);
			unset($details['slug']);
			
			
			$response['status'] = "success";
			$response['message'] = "Successfully got all Data";
			$player_team_id_array = $this->player_team_id_array($player_id);
			$year_list = $this->year_list_of_match_and_player("player_id", $player_id);
			
			foreach($year_list as $year){
				foreach($player_team_id_array as $array_match_id){
				
					$where['YEAR(datetime)'] = $year['year'];
					$where['player_id'] = $player_id;
					$where['tbl_tournament_match.team_id'] = $array_match_id['team_id'];
					$query = $this->db->table('tbl_match_team');
					$query = $query->select('tbl_match_team.id as main_id, YEAR(datetime) as year, g, a, yc, rc, match_id, player_id, tbl_tournament_match.team_id, tbl_tournament_match.id, SUM(g) as total_g, SUM(a) as total_a, SUM(yc) as total_yc, SUM(rc) as total_rc, tbl_team.team_name, tbl_team.club_id');
					$query = $query->join('tbl_tournament_match', 'tbl_tournament_match.id = tbl_match_team.match_id','left');
					 
					$query = $query->join('tbl_team', 'tbl_team.team_id = tbl_tournament_match.team_id','left');
					$query = $query->where($where);
					
					$query = $query->get()->getRowArray();
						
					if($query){
						if($query['main_id'] && $query['year'] && $query['player_id']){
							$data1['team_name'] = $query['team_name'] ? $query['team_name'] : 0;
							// $data1['club_id'] = $query['club_id'] ? $query['club_id'] : 0;
							// $data1['match_id'] = $query['match_id'] ? $query['match_id'] : 0;
							$data1['year'] = $query['year'] ? $query['year'] : 0;
							$data1['g'] = $query['total_g'] ? $query['total_g'] : 0;
							$data1['a'] = $query['total_a'] ? $query['total_a'] : 0;
							$data1['yc'] = $query['total_yc'] ? $query['total_yc'] : 0;
							$data1['rc'] = $query['total_rc'] ? $query['total_rc'] : 0;
							$data[] = $data1;
						}
					}			
					
				}				
			}
		
			$response['data'] = $details;
			$response['score'] = $data;
			$this->sendResponse($response);			
		}
		
	}
	/**

     * Get Coach detail

     * @endpoint get-coach-detail

     * @url: http://yourdomain.com/api/get-coach-detail

     * @param coach_id : coach_id

     * @param player_id : player_id

     */
    public function get_coach_detail(){

        if($this->authenticate_api()){
			
			$response = array( "status" => "error" );

            $required_fields = array('player_id');

            $status = $this->verifyRequiredParams($required_fields);

            $coach_id = $this->request->getVar("coach_id") ? $this->request->getVar("coach_id") : '';

            $player_id = $this->request->getVar("player_id");            

            if($this->ifexists('tbl_user', $player_id, 'user_id') != true){

                $response['message'] = "Player does no exist";

                $this->sendResponse($response);

            }
			
			if($coach_id){
				if($this->ifempty($coach_id, "Coach ID")!== true){

					$response['message'] = $this->ifempty($coach_id, "Coach ID");

					$this->sendResponse($response);

				}
				
				if($this->ifexists('tbl_user', $coach_id, 'user_id') != true){

					$response['message'] = "User does no exist.";

					$this->sendResponse($response);

				}
				
			}
			$details = $this->getUserInfo($player_id);
			
			unset($details['gender']);
			unset($details['weight']);
			unset($details['match_id']);
			unset($details['position_slug']);
			unset($details['p_id']);
			unset($details['email']);
			unset($details['nation_id']);
			unset($details['slug']);
			
			
			$response['status'] = "success";
			$response['message'] = "Successfully got all Data";
			$player_team_id_array = $this->player_team_id_array($player_id);
			$year_list = $this->year_list_of_match_and_player("player_id", $player_id);
			
			foreach($year_list as $year){
				foreach($player_team_id_array as $array_match_id){
				
					$where['YEAR(datetime)'] = $year['year'];
					$where['player_id'] = $player_id;
					$where['tbl_tournament_match.team_id'] = $array_match_id['team_id'];
					$query = $this->db->table('tbl_match_team');
					$query = $query->select('tbl_match_team.id as main_id, YEAR(datetime) as year, g, a, yc, rc, match_id, player_id, tbl_tournament_match.team_id, tbl_tournament_match.id, SUM(g) as total_g, SUM(a) as total_a, SUM(yc) as total_yc, SUM(rc) as total_rc, tbl_team.team_name, tbl_team.club_id');
					$query = $query->join('tbl_tournament_match', 'tbl_tournament_match.id = tbl_match_team.match_id','left');
					 
					$query = $query->join('tbl_team', 'tbl_team.team_id = tbl_tournament_match.team_id','left');
					$query = $query->where($where);
					
					$query = $query->get()->getRowArray();
						
					if($query){
						if($query['main_id'] && $query['year'] && $query['player_id']){
							$data1['team_name'] = $query['team_name'] ? $query['team_name'] : 0;
							// $data1['club_id'] = $query['club_id'] ? $query['club_id'] : 0;
							// $data1['match_id'] = $query['match_id'] ? $query['match_id'] : 0;
							$data1['year'] = $query['year'] ? $query['year'] : 0;
							$data1['g'] = $query['total_g'] ? $query['total_g'] : 0;
							$data1['a'] = $query['total_a'] ? $query['total_a'] : 0;
							$data1['yc'] = $query['total_yc'] ? $query['total_yc'] : 0;
							$data1['rc'] = $query['total_rc'] ? $query['total_rc'] : 0;
							$data[] = $data1;
						}
					}			
					
				}				
			}
		
			$response['data'] = $details;
			$response['score'] = $data;
			$this->sendResponse($response);			
		}
		
	}
	
   
}
