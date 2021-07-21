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
                $response['message'] = "Please select your team";
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
    
   
}
