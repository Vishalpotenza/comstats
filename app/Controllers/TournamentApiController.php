<?php

namespace App\Controllers;

class TournamentApiController extends ApiBaseController
{

    /**
     * Sceduled Matchs
     * @endpoint match-lists
     * @url: http://yourdomain.com/api/match-lists
     * @param user_id : user_id
     */
    public function match_lists()
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
            if($this->ifexists('tbl_user', $user_id, 'user_id') != true){
                $response['message'] = "User does no exist.";
                $this->sendResponse($response);
            }
            // if($this->ifexistscustom('tbl_user', array( 'user_id' => $user_id, 'user_type' => 1 )) != true){
            //     $response['message'] = "Only Coach can schedule match !.";
            //     $this->sendResponse($response);
            // }
            $result = array();
            /**
             * Tournament list
             */
            $tournament = $this->db->table('tbl_tournament')->get()->getResultArray();
            $coachs = $this->db->table('tbl_team_member_relation')->select('team_id')->where(array('user_id'=> $user_id))->get()->getRowArray();
            if(empty($coachs))
            {
                $response['message'] = "No team data available.";
                $this->sendResponse($response);
            } 
            $result['tournament'] = $tournament;
            
            /**
             * Ground list
             */
            $grounds = $this->db->table('tbl_traning_ground')->get()->getResultArray();
            $result['ground'] = $grounds;
           
            /**
             * Teams List
             */
            $teams = $this->db->table('tbl_team')->select('team_id, team_name')->where('team_id<>', $coachs['team_id'])->get()->getResultArray();
            if(!empty($teams))
                $result['teams'] = $teams;
            /**
             * Future matches
             */
            $matches = $this->db->table('tbl_tournament_match')->where('team_id', $coachs['team_id'])->orderBy('date')->get()->getResultArray();
            $future_match =array();
            $past_match =array();
            foreach($matches as $key => $match){
                $opponent_team = $this->db->table('tbl_team')->select('team_name, team_logo')->where('team_id', $match['opponent_team_id'])->get()->getRowArray();
                $match['opponent_team'] = $opponent_team['team_name'];
                $match['opponent_team_logo'] = base_url()."/public/uploads/team_images/".$match['opponent_team_id']."/".$opponent_team['team_logo'];
                $participant_team =  $this->db->table('tbl_team')->select('team_name, team_logo')->where('team_id', $match['team_id'])->get()->getRowArray();
                $match['participant_team'] = $participant_team['team_name'];
                $match['participant_team_logo'] = base_url()."/public/uploads/team_images/".$match['team_id']."/".$participant_team['team_logo'];
                $match['tournament_name'] = $this->db->table('tbl_tournament')->select('name')->where('id', $match['tournament_id'])->get()->getRowArray()['name'];
                $match['date'] = $this->date_format_list($match['date']);
                $match['time'] = $this->time_format_list($match['datetime']);
                 // true is team is full
                // if($match['team_full_status'] == 0){
                    // $match['team_full_status'] = false;
                // } else {
                     // $match['team_full_status'] = true;
                // }
                $match['match_id'] = $match['id'];
				$match['match_result']=array();
                $match['players_details']=array();
				
                $match_players = $this->db->table('tbl_match_team')->where('match_id', $match['match_id'])->get()->getResultArray();
                $match_winning_score = $this->db->table('tbl_tournament_match_result')->where('match_id', $match['match_id'])->get()->getRowArray();
					if($match_winning_score){
						$match_winning_score_array['team_id_score'] = $match_winning_score['team_id_score'];
						$match_winning_score_array['opponent_team_id_score'] = $match_winning_score['opponent_team_id_score'];
						if($match_winning_score['winner_team_id'] == 0){
							$match_winning_score_array['match_status'] = "Draw";
						}else if($match_winning_score['winner_team_id'] == $match['team_id']){
							$match_winning_score_array['match_status'] = "Win";
							$match_winning_score_array['winner_team_id'] = $match_winning_score['winner_team_id'];
						}else{
							$match_winning_score_array['match_status'] = "lose";
							$match_winning_score_array['winner_team_id'] = $match_winning_score['winner_team_id'];
						}
						$match['match_result'] = $match_winning_score_array;
					}
				
                foreach($match_players as $m_player){
                    $player_details_match = $this->getUserInfo($m_player['player_id']);
                    $player_details_match['jursey_no'] = $m_player['jursey_no'];
                    $player_details_match1['g'] = $m_player['g'];
                    $player_details_match1['a'] = $m_player['a'];
                    $player_details_match1['yc'] = $m_player['yc'];
                    $player_details_match1['rc'] = $m_player['yc'];

					$player_details_match['score'] = $player_details_match1;
					// print_r($m_player);
                    array_push( $match['players_details'],$player_details_match);
                }
                // unset($match['players_details']);
                unset($match['id']);
                if($match['datetime'] > date('Y-m-d H:i:s') && $match['match_end_status'] == 0){
					unset($match['players_details']);
                    array_push($future_match, $match);
                } else {
                    if($match['datetime'] < date('Y-m-d H:i:s') && $match['match_end_status'] == 1){
						
                        array_push($past_match, $match);
                    } else {
						unset($match['players_details']);
                        array_push($future_match, $match);
                    }
                }
            }
            $traning = $this->db->table('tbl_traning')->where('team_id', $coachs['team_id'])->orderBy('date')->get()->getResultArray();
            foreach($traning as $trannie){
                $trannie['tournament_name'] = $this->db->table('tbl_tournament')->select('name')->where('id', 1)->get()->getRowArray()['name'];
                $participant_team =  $this->db->table('tbl_team')->select('team_name, team_logo')->where('team_id', $trannie['team_id'])->get()->getRowArray();
                $trannie['participant_team'] = $participant_team['team_name'];
                $trannie['participant_team_logo'] = base_url()."/public/uploads/team_images/".$trannie['team_id']."/".$participant_team['team_logo'];
                $trannie['ground_name'] = $this->db->table('tbl_traning_ground')->where('id', $trannie['ground'])->get()->getRowArray()['ground_name'];
               
                $trannie["tournament_id"] =  $this->db->table('tbl_tournament')->select('id')->where('slug', "traning")->get()->getRowArray()['id'];
                $trannie['time'] = $this->time_format_list($trannie['traningdatetime']);
                if($this->ifexistscustom('tbl_traning_attendance', array("traning_id"=>$trannie['id'], "player_id"=>$user_id))){
                    $trannie['attandance_status'] = true;
                } else {
                    $trannie['attandance_status'] = false;
                }
                $trannie['attendance'] = array();
                $attendence=$this->db->table('tbl_team_member_relation')->select('tbl_team_member_relation.user_id, first_name, last_name, tbl_position.position')->join('tbl_user', 'tbl_user.user_id = tbl_team_member_relation.user_id')->join('tbl_position', 'tbl_position.p_id=tbl_user.position')->where( array('team_id'=> $trannie['team_id'], 'designation'=> 1, 'deletestatus' => 0))->get()->getResultArray();
                foreach($attendence as $key => $attend){
                    if($this->ifexistscustom('tbl_traning_attendance', array('traning_id' => $trannie['id'], "player_id" => $attend['user_id'], 'attendence_status'=> 1))){
                        $attend['attend']="Yes";
                    }
                    else{
                        $attend['attend']="no";
                    }
                    array_push($trannie['attendance'], $attend);
                }
               // array_push($trannie['attendance'], $attendence);
                $trannie['traning_id']=$trannie['id'];
                unset($trannie['id']);
                if($trannie['date'] > date('Y-m-d')){
                     if($this->ifexistscustom('tbl_user', array( 'user_id' => $user_id, 'user_type' => 0 ))){
                        $trannie['attendance'] = array();
                    }
                    $trannie['date'] = $this->date_format_list($trannie['date']);
                    array_push($future_match, $trannie);
                } else {
                    $trannie['date'] = $this->date_format_list($trannie['date']);
                    array_push($past_match, $trannie);
               }
            }
        
        
            $result['future_match'] = $future_match;
            $result['past_match'] = $past_match; 
            if(!empty($result)){
                $response['status'] = "success";
                $response['message'] = 'Successfully got details';
                $response['data'] = $result;
                $this->sendResponse($response);
            } else {
                 $response['message'] = 'Something went wrong!';
                 $this->sendResponse($response);
            }
            
             
        }
    }
    /**
     * Schedule match
     * @endpoint schedule-match
     * @url: http://yourdomain.com/api/schedule-match
     * @param opponent_team_id : opponent_team_id
     * @param tournament_id : tournament_id
     * @param dateofmatch : dateofmatch
     * @param address : address
     * @param ground_status : ground_status (pass only when not traning 1 for home 2 for away)
     * @param ground_id : ground_id (pass only if traning)
     * @param user_id: user_id
     * @param time : time
     * @param datetime :  datetime
     */
    public function schedule_match(){
        if($this->authenticate_api())
        {   
            $response = array( "status" => "error" );
            $required_fields = array("user_id", "tournament_id", "dateofmatch", "time", "datetime");
            $status = $this->verifyRequiredParams($required_fields);
            $user_id = $this->request->getVar("user_id");
            $tournament_id = $this->request->getVar("tournament_id");
            $grounds_id = $this->request->getVar("ground_id");
            $opponent_team_id = $this->request->getVar("opponent_team_id");
            $dateofmatch = $this->request->getVar("dateofmatch");
            $ground_status = $this->request->getVar("ground_status");
            $address = $this->request->getVar("address");
            $time = $this->request->getVar("time");
            $datetime = $this->request->getVar("datetime");
            //user
            if($this->ifempty($user_id, "User id")!== true){
                $response['message'] = $this->ifempty($user_id, "User Id");
                $this->sendResponse($response);
            }
            if($this->ifempty($datetime, "Date Time")!== true){
                $response['message'] = $this->ifempty($datetime, "Date Time");
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_user', $user_id, 'user_id') != true){
                $response['message'] = "User does no exist.";
                $this->sendResponse($response);
            }
             if($this->ifexists('tbl_team_member_relation', $user_id, 'user_id')!= true){
                $response['message'] = "Please Add coach in team";
                $this->sendResponse($response);
            }

            if($this->ifexistscustom('tbl_user', array( 'user_id' => $user_id, 'user_type' => 1 )) != true){
                $response['message'] = "Only Coach can schedule match !.";
                $this->sendResponse($response);
            }
            //tournamnet
            if($this->ifempty($tournament_id, "Tournament id")!== true){
                $response['message'] = $this->ifempty($tournament_id, "Tournament Id");
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_tournament', $tournament_id, 'id') != true){
                $response['message'] = "Tournament id does not exist.";
                $this->sendResponse($response);
            }
            //time
            if($this->ifempty($time, "Time")!= true){
                $response['message'] = $this->ifempty($time, "Time");
                $this->sendResponse($response);
            }
           
            
            // dateofmatch
            if($this->ifempty($dateofmatch, "Date of Match")!= true){
                $response['message'] = $this->ifempty($dateofmatch, "Date of Match");
                $this->sendResponse($response);
            }
            if($this->get_date($dateofmatch) < date('Y-m-d'))
            {
                $response['message'] = "Please Select future date";
                $this->sendResponse($response);
            }
           
           
             /**
             * Check tournament
             */
            $tournament = $this->db->table('tbl_tournament')->where('id', $tournament_id)->get()->getRowArray();
            $team = $this->db->table('tbl_team_member_relation')->select('team_id')->where(array('user_id'=> $user_id))->get()->getRowArray();
            if($tournament['slug'] == "traning"){
                // if traning
                if($this->ifempty($grounds_id, "Ground id")!== true){
                    $response['message'] = $this->ifempty($grounds_id, "Ground Id");
                    $this->sendResponse($response);
                }
                if($this->ifexists('tbl_traning_ground', $grounds_id, 'id') != true){
                    $response['message'] = "Ground is not valid.";
                    $this->sendResponse($response);
                }
                if($this->ifexistscustom('tbl_traning', array( 'date' => $this->get_date($dateofmatch), 'ground' => $grounds_id ))){
                    $response['message'] = "Ground is booked on this date!.";
                    $this->sendResponse($response);
                }
                if($this->ifexistscustom('tbl_traning', array( 'date' => $this->get_date($dateofmatch), 'team_id' => $team['team_id'] ))){
                    $response['message'] = "Traning ground has already been taken to this day!.";
                    $this->sendResponse($response);
                }
                //Add traning records
                $traning =array(
                    "date" => $this->get_date($dateofmatch),
                    "ground" => $grounds_id,
                    "team_id" => $team['team_id'],
                    "time"   => $time,
                    "traningdatetime" => $this->format_datetime($datetime)
                );
                $result = $this->db->table('tbl_traning')->insert($traning);
                if(!empty($result)){
                    $response['status'] = "success";
                    $response['message'] = "Successfully schedule traning";
                    $this->sendResponse($response);
                }
            } else {
                // if not traning
                if($this->ifempty($opponent_team_id, "Opponent team id")!= true){
                    $response['message'] = $this->ifempty($opponent_team_id, "Opponent team id");
                    $this->sendResponse($response);
                }
                //team
                if($this->ifexists('tbl_team', $opponent_team_id, 'team_id') != true){
                    $response['message'] = "Opponent team id not exist.";
                    $this->sendResponse($response);
                }
                 if($this->ifexistscustom('tbl_tournament_match', array( 'date' => $this->get_date($dateofmatch), 'team_id' => $team['team_id'] ))){
                    $response['message'] = "Team has already a match at this date!.";
                    $this->sendResponse($response);
                }
                 /**
                 * Get user team
                 */
                
                if(!empty($team))
                {
                    if($team['team_id'] == $opponent_team_id){
                        $response['message'] = "Team and opponnet team can not be same";
                        $this->sendResponse($response);
                    }
                }
                if($this->ifempty($address, "address")!== true){
                    $response['message'] = $this->ifempty($address, "address");
                    $this->sendResponse($response);
                }
                if($this->ifempty($ground_status, "Ground status")!== true){
                    $response['message'] = $this->ifempty($ground_status, "Ground status");
                    $this->sendResponse($response);
                }
                if(!in_array($ground_status, array(1, 2))){
                    $response['message'] = "Please enter valid ground status";
                    $this->sendResponse($response);
                }
                $schedule_match = array(
                    "team_id"               => $team['team_id'],
                    "opponent_team_id"      => $opponent_team_id,
                    "tournament_id"         => $tournament_id,
                    "date"                  => $this->get_date($dateofmatch),
                    "address"               => trim($address),
                    "ground_status"         => $ground_status,
                    "time"                  => $time,
                    "datetime"              => $this->format_datetime($datetime)
                );
                $match = $this->db->table('tbl_tournament_match')->insert($schedule_match);
                if(!empty($match)){
                    $response['status'] = "success";
                    $response['message'] = "Successfully schedule match";
                    $this->sendResponse($response);
                }

            } 
        }
    }
     /**
     * Add player to match
     * @endpoint add-player-to-match
     * @url: http://yourdomain.com/api/add-player-to-match
     * @param match_id :match_id
     * @param players: json ([{"player_id":1,"jursey_no":3},{"player_id":1,"jursey_no":3}])
	 * @param kit_color: 1 For blue, 2 for red
     * 
     * @return void
     */
    public function add_player_to_match(){
        if($this->authenticate_api())
        {  								
            $response = array( "status" => "error" );
            $required_fields = array("match_id", "players");
            $status = $this->verifyRequiredParams($required_fields);
            $match_id = $this->request->getVar("match_id");
            $players = $this->request->getVar("players");
			$kit_color = $this->request->getVar("kit_color");
			// $result['team_size'] = $this->match_team_size($match_id);
            //user
            if($this->ifempty($match_id, "match id")!== true){
                $response['message'] = $this->ifempty($match_id, "match id");
                $this->sendResponse($response);
            }
            if($this->ifempty($players, "Players")!== true){
                $response['message'] = $this->ifempty($players, "players");
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_tournament_match', $match_id, 'id') != true){
                $response['message'] = "Please enter valid match id";
                $this->sendResponse($response);
            }
			if(!in_array($kit_color, array(1, 2))){
                    // $response['message'] = "Please select valid color";
                    // $this->sendResponse($response);
			}else{			
				$color_update = array("kit_color" =>$kit_color);
				$this->db->table('tbl_tournament_match')->where('id',$match_id)->set($color_update)->update();
			}
            $player_details = json_decode($players, true);
           
            $i=0;
            foreach($player_details as $player){
				if($this->ifteamfull($match_id)){				
					$response['message']  = "Team is full ";				
					$response['flag'] = 1;				
					$response['match_id'] = $match_id;				
					$flag = array("team_full_status" =>1);		
					$this->db->table('tbl_tournament_match')->where('id',$match_id)->set($flag)->update();				
					$this->sendResponse($response);			
				}
                $connection = array(
                    "user_id"  => $player['player_id'],
                    "user_type"=> 0
                );
                if($this->ifexistscustom("tbl_user",$connection) != true){
                    $response['message'] = "Please enter valid player id";
                    $this->sendResponse($response);
                }
                /**
                 * Check jursey_no
                 */
                $checkj=array(
                    "match_id" =>$match_id,
                    "jursey_no"=>$player['jursey_no']
                );
                if($this->ifexistscustom("tbl_match_team",$checkj)){
                    $response['message'] = "Please enter valid jurseynumber id";
                    $this->sendResponse($response);
                }
                $player_details_match = array(
                    "match_id" =>$match_id,
                    "player_id"=>$player['player_id'],
                    "jursey_no"=>$player['jursey_no']
                );
                $check_team = array(
                    "match_id" =>$match_id,
                    "player_id"=>$player['player_id']
                );
                $add_player=null;
                if($this->ifexistscustom("tbl_match_team", $check_team)){
                    $add_player = $this->db->table('tbl_match_team')->where($check_team)->set($player_details_match)->update();
                } else {
                    $add_player = $this->db->table('tbl_match_team')->insert($player_details_match);
                }
                if(!empty($add_player)){
                    $i++;
                }
            }
            if($i===count($player_details)){
                $response['status']="success";
                $response['message'] = "All players added in team";
                $this->sendResponse($response);
            }else{
                $response['message'] = "something went wrong";
                $this->sendResponse($response);
            }
            
        }
    }
    /**
     * Get match players
     * @endpoint get-match-players 
     * @url: http://yourdomain.com/api/get-match-players
     * @param match_id : match_id
     * @param coach_id : coach_id
	 * @param match_start_flag : 0 for upcoming, 1 for running match
     */
    public function get_match_players(){
        if($this->authenticate_api())
        {
            $response = array( "status" => "error" );
            $required_fields = array("match_id", "coach_id");
            $status = $this->verifyRequiredParams($required_fields);
            $match_id= $this->request->getVar("match_id");
            $coach_id = $this->request->getVar("coach_id");
			// $response['match_start_flag'] = 0;
			
            $connection = array(
                    "user_id"  => $coach_id,
                    "user_type"=> 1
                );
            if($this->ifexistscustom("tbl_user",$connection) != true){
                $response['message'] = "Please enter valid coach id";
                $this->sendResponse($response);
            }
            if($this->ifempty($match_id, "match id")!== true){
                $response['message'] = $this->ifempty($match_id, "match id");
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_tournament_match', $match_id, 'id') != true){
                $response['message'] = "Please enter valid match id";
                $this->sendResponse($response);
            }
            /**
             * all players in team"
             */
            //get team of coach
            $team = $this->db->table('tbl_team_member_relation')->where(array("user_id"=> $coach_id, "designation<>"=>1, "deletestatus"=>0))->get()->getRowArray();
            if(empty($team)){
                $response['message'] = "team not found";
                $this->sendResponse($response);
            }
            
            /**
             * get team players
             */
            $players = $this->db->table('tbl_team_member_relation')->where(array("team_id"=> $team['team_id'], "designation"=>1, "deletestatus"=>0))->get()->getResultArray();
            $result = array();
			$result['match_start_flag'] = 0;
            $result['in_match']=array();
            $result['not_in_match']=array();
			
			$match_tournament = $this->db->table('tbl_tournament_match')->where('id', $match_id)->get()->getRowArray();
			$result['kit_color'] = $match_tournament['kit_color'];
			$result_match['kit_color'] = $match_tournament['kit_color'];
			$result['team_size'] = $this->match_team_size($match_id);
            foreach ($players as $player){
                $details = $this->getUserInfo($player['user_id'], $match_id);
                $details["players_id"]=$player['user_id'];
                unset($details['position']);
                unset($details['height']);
                unset($details['weight']);
                unset($details['total_game']);
                unset($details['nation_id']);
                $details["dob"] = $this->get_mobile_date($details["dob"]);
                $details['designation'] = $player['designation'];
                $conditions = array(
                    "match_id"=> $match_id,
                    "player_id"=> $player['user_id'],
                );
                if($this->ifexistscustom("tbl_match_team",$conditions)){

                    array_push($result['in_match'],$details);
                }
                else{
                    array_push($result['not_in_match'],$details);
                }
            }
			if($this->ifteamfull($match_id)){
				if($this->match_time($match_id)){
					foreach($result['in_match'] as $key => $data ){
						$whare = array('player_id' => $data['players_id'],'match_id' =>$data['match_id'] );
						$g_a_yc_rc = $this->db->table('tbl_match_team')->where($whare);
						$g_a_yc_rc = $g_a_yc_rc->get()->getRowArray();
						
							$result['in_match'][$key]['yc'] = $g_a_yc_rc['yc'] ? $g_a_yc_rc['yc'] : 0;
							$result['in_match'][$key]['rc'] = $g_a_yc_rc['rc'] ? $g_a_yc_rc['rc'] : 0;
							$result['in_match'][$key]['g'] = $g_a_yc_rc['g'] ? $g_a_yc_rc['g'] : 0;
							$result['in_match'][$key]['a'] = $g_a_yc_rc['a'] ? $g_a_yc_rc['a'] : 0;
							
					}
					$result_match['match_start_flag'] = 1;
					$result_match['match_players_list'] = $result['in_match'];
					$response['status']  ="success";
					$response['message'] = "successfully got all players";
					$response['data']    = $result_match;
					$this->sendResponse($response);				
				}
			}
            if(!empty($result)){
                $response['status']  ="success";
                $response['message'] = "successfully got all players";
                $response['data']    = $result;
                $this->sendResponse($response);
            }


        } 
    }
	/**
     * Update kit color formation
     * @endpoint update-kit-color-formation
     * @url: http://yourdomain.com/api/update-kit-color
     * @param match_id : match_id
     * @param kit_color : kit_color => 1 for blue, 2 for red
     * @param formation : formation => 4-2-2
	*/
	
    public function update_kit_color_formation(){
		if($this->authenticate_api())
        {
			
			$response = array( "status" => "error" );
            $required_fields = array("kit_color", "formation", "match_id");
            $status = $this->verifyRequiredParams($required_fields);
			$kit_color = $this->request->getVar("kit_color");    
			$match_id= $this->request->getVar("match_id");
			
			
			if($this->ifempty($match_id, "match id")!== true){
                $response['message'] = $this->ifempty($match_id, "match id");
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_tournament_match', $match_id, 'id') != true){
                $response['message'] = "Please enter valid match id";
                $this->sendResponse($response);
            }
			if(!in_array($kit_color, array(1, 2))){
				$response['message'] = "Please select valid color";				
				$this->sendResponse($response);
			}else{
				$color_update = array("kit_color" =>$kit_color);
				$update_kit_color = $this->db->table('tbl_tournament_match')->where('id',$match_id)->set($color_update)->update();
				$response['status'] = "success";
				$response['message'] = "Somthing wrong Data not Updated";
				if($update_kit_color)
					$response['message'] = "Data Update Successfully";
				$this->sendResponse($response);
                
			}
			
		}
	}
	/**
     * update score
     * @endpoint update-score
     * @url: http://yourdomain.com/api/update-score
     * @param match_id : match_id
     * @param yc : yc => 1,2...
     * @param rc : rc => 1,2...
     * @param g : g => 1,2...
     * @param a : a => 1,2...
     * @param player_id : player_id => 1,2...
    */
	public function update_score(){
		if($this->authenticate_api())
        {			
			$response = array( "status" => "error" );
			$response['message'] = "Somthing Wrong";
            $required_fields = array("match_id", "players");
            $status = $this->verifyRequiredParams($required_fields);
			$match_id= $this->request->getVar("match_id");
			$players = $this->request->getVar("players");
			// $players_id= $this->request->getVar("players_id");
			
			if($this->ifempty($match_id, "match id")!== true){
                $response['message'] = $this->ifempty($match_id, "match id");
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_tournament_match', $match_id, 'id') != true){
                $response['message'] = "Please enter valid match id";
                $this->sendResponse($response);
            }
			if($this->ifempty($players, "Players")!== true){
                $response['message'] = $this->ifempty($players, "players");
                $this->sendResponse($response);
            }
			
						
            $player_details = json_decode($players, true); 
			foreach($player_details as $player){			
				$connection = array(
                    "user_id"  => $player['players_id'],
                    "user_type"=> 0
                );
                if($this->ifexistscustom("tbl_user",$connection) != true){
                    $response['message'] = "Please enter valid player id";
                    $this->sendResponse($response);
                }
				
				$yc = $player['yc'] ? $player['yc'] : 0;    
				$rc = $player['rc'] ? $player['rc'] : 0;    
				$g = $player['g'] ? $player['g'] : 0;    
				$a = $player['a'] ? $player['a'] : 0;				
				
				$whare = array('player_id' => $player['players_id'],'match_id' =>$match_id );
				
				$update_match_score = $this->db->table('tbl_match_team')->where($whare)->get()->getRowArray();
				if($update_match_score){
					$response['status'] = "success";					
					$score = array();
					$i=0;
					if($yc){					
						$score['yc'] = $yc;
						$i++;
					}
					if($rc){						
						$score['rc'] = $rc;
						$i++;
					}
					if($g){
						
						$score['g'] = $g;
						$i++;
					}
					if($a){
						// $a_data_old = $update_match_score['a'] ? $update_match_score['a'] : 0;
						// $a_data = $a_data_old + $a;									
						// $score = array('a' => $a_data );
						$score['a'] = $a;
						$i++;
					}
					
					
					if($i > 0)
						$upate = $this->db->table('tbl_match_team')->where($whare)->set($score)->update();
					
				}
			}
			if($upate)
				$response['message'] = "Score Updated";					
			$this->sendResponse($response);		
		}
	}
	/**
     * update score
     * @endpoint finish_match
     * @url: http://yourdomain.com/api/finish_match
     * @param match_id : match_id
     * match_end_status 1 => for end match, 0 => match running or remaining
	 *
    */
	public function finish_match(){
		if($this->authenticate_api()){
			
			$response = array( "status" => "error" );
			$response['message'] = "Somthing Wrong";
            $required_fields = array("match_id", "coach_id");
            $status = $this->verifyRequiredParams($required_fields);
			$match_id= $this->request->getVar("match_id");
			$coach_id= $this->request->getVar("coach_id");
			
			if($this->ifempty($match_id, "match id")!== true){
                $response['message'] = $this->ifempty($match_id, "match id");
                $this->sendResponse($response);
            }
			if($this->ifempty($coach_id, "coach id")!== true){
                $response['message'] = $this->ifempty($coach_id, "coach id");
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_tournament_match', $match_id, 'id') != true){
                $response['message'] = "Please enter valid match id";
                $this->sendResponse($response);
            }
			
			
			
			
			if($match_id){
				$where = array('id' => $match_id);
				$update_data = '';
				if($coach_id){
					
					$team_id_check = $this->db->table('tbl_team_member_relation')->where('user_id',$coach_id)->get()->getRowArray();
					$team_id = $team_id_check['user_id'];
					if($team_id){
						if($this->check_coach_team($match_id,$team_id,"team_id")){
							$update_data = array('match_end_status' => 1);
						}
						
						if($this->check_coach_team($match_id,$team_id,"opponent_team_id")){
							$update_data = array('match_end_status_for_opponent_team' => 1);
						}
						$status_team = $this->updated_team_score($match_id);
						if($status_team['message'] == 'Added Successfully' || $status_team['message'] == 'Already Added'){
							
						}
							
					}
					
				}
				if(!empty($update_data)){
					$update = $this->db->table('tbl_tournament_match')->where($where)->set($update_data)->update();
					$response['status'] = "Success";
					$response['message'] = "Something Wrong Data not updated";
					if($update)
						$response['message'] = "Data update Successfully";
				}
			}
			$this->sendResponse($response);
			
			
			
		}
	}
	/**
     * update Match team score 
     * @endpoint update_team_score
     * @url: http://yourdomain.com/api/update_team_score
     * @param match_id : match_id
     * winner_team_id 0 => for match draw , 1,2,.. => tournament match team id
	 *
    */
	public function update_team_score(){
		if($this->authenticate_api()){
			
			$response = array( "status" => "error" );
			$response['message'] = "Somthing Wrong";
            $required_fields = array("match_id");
            $status = $this->verifyRequiredParams($required_fields);
			$match_id= $this->request->getVar("match_id");
			
			if($this->ifempty($match_id, "match id")!== true){
                $response['message'] = $this->ifempty($match_id, "match id");
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_tournament_match', $match_id, 'id') != true){
                $response['message'] = "Please enter valid match id";
                $this->sendResponse($response);
            }			
			if($match_id){				
				$match_tournament_team = $this->db->table('tbl_tournament_match')->where('id', $match_id)->get()->getRowArray();
				if($match_tournament_team){
					$team_id = $match_tournament_team['team_id'];
					$opponent_team_id = $match_tournament_team['opponent_team_id'];
					$match_end_status = $match_tournament_team['match_end_status'];
					$match_end_status_for_opponent_team = $match_tournament_team['match_end_status_for_opponent_team'];
					if($match_end_status == 1 && $match_end_status_for_opponent_team == 1){
						$where_match_team['match_id'] = $team_id;
						$where_match_opponent_team['match_id'] = $opponent_team_id;
						
						$match_team = $this->db->table('tbl_match_team')->where($where_match_team);
						$match_opponent_team = $this->db->table('tbl_match_team')->where($where_match_opponent_team);
						
						$match_team_goal = $this->db->query("SELECT SUM(g) as Total_g FROM tbl_match_team where match_id = ".$team_id)->getRowArray();
						$match_opponent_team_goal = $this->db->query("SELECT SUM(g) as Total_g FROM tbl_match_team where match_id = ".$opponent_team_id)->getRowArray();
						
						$match_team_goal['Total_g'] = $match_team_goal['Total_g'] ? $match_team_goal['Total_g'] : 0;
						$match_team_goal['Total_g'] = $match_opponent_team_goal['Total_g'] ? $match_opponent_team_goal['Total_g'] : 0;
						
						if($match_team_goal['Total_g'] > $match_opponent_team_goal['Total_g']){
							$winner_team_id = $team_id;
						}else if($match_team_goal['Total_g'] < $match_opponent_team_goal['Total_g']){
							$winner_team_id = $opponent_team_id;
						}else{
							$winner_team_id = 0;
						}
						$tbl_tournament_match_result_data['match_id'] = $match_id; 
						$tbl_tournament_match_result_data['team_id_score'] = $match_team_goal['Total_g']; 
						$tbl_tournament_match_result_data['opponent_team_id_score'] = $match_opponent_team_goal['Total_g']; 
						$tbl_tournament_match_result_data['winner_team_id'] = $winner_team_id;
						$response['status'] = "Success";
						$table_name = 'tbl_tournament_match_result';
						$id = $match_id;
						$fild_to_check = 'match_id';
						if($this->ifexists($table_name,$id,$fild_to_check)){							
							$response['message'] = "Team score already Added";
						}else{
							
							$tbl_tournament_match_result = $this->db->table($table_name)->insert($tbl_tournament_match_result_data);
							if($tbl_tournament_match_result)
								$response['message'] = "Team score Added Successfully";
						}
					}
				}				
			}
			$this->sendResponse($response);			
			
		}
	}
	public function updated_team_score($match_id=''){
		$response['score_update_status'] = "error";
		$response['message'] = "error";
		if($match_id){				
			$match_tournament_team = $this->db->table('tbl_tournament_match')->where('id', $match_id)->get()->getRowArray();
			if($match_tournament_team){
				$team_id = $match_tournament_team['team_id'];
				$opponent_team_id = $match_tournament_team['opponent_team_id'];
				$match_end_status = $match_tournament_team['match_end_status'];
				$match_end_status_for_opponent_team = $match_tournament_team['match_end_status_for_opponent_team'];
				if($match_end_status == 1 && $match_end_status_for_opponent_team == 1){
					$where_match_team['match_id'] = $team_id;
					$where_match_opponent_team['match_id'] = $opponent_team_id;
					
					$match_team = $this->db->table('tbl_match_team')->where($where_match_team);
					$match_opponent_team = $this->db->table('tbl_match_team')->where($where_match_opponent_team);
					
					$match_team_goal = $this->db->query("SELECT SUM(g) as Total_g FROM tbl_match_team where match_id = ".$team_id)->getRowArray();
					$match_opponent_team_goal = $this->db->query("SELECT SUM(g) as Total_g FROM tbl_match_team where match_id = ".$opponent_team_id)->getRowArray();
					
					$match_team_goal['Total_g'] = $match_team_goal['Total_g'] ? $match_team_goal['Total_g'] : 0;
					$match_team_goal['Total_g'] = $match_opponent_team_goal['Total_g'] ? $match_opponent_team_goal['Total_g'] : 0;
					
					if($match_team_goal['Total_g'] > $match_opponent_team_goal['Total_g']){
						$winner_team_id = $team_id;
					}else if($match_team_goal['Total_g'] < $match_opponent_team_goal['Total_g']){
						$winner_team_id = $opponent_team_id;
					}else{
						$winner_team_id = 0;
					}
					$tbl_tournament_match_result_data['match_id'] = $match_id; 
					$tbl_tournament_match_result_data['team_id_score'] = $match_team_goal['Total_g']; 
					$tbl_tournament_match_result_data['opponent_team_id_score'] = $match_opponent_team_goal['Total_g']; 
					$tbl_tournament_match_result_data['winner_team_id'] = $winner_team_id;
					$response['status'] = "Success";
					$table_name = 'tbl_tournament_match_result';
					$id = $match_id;
					$fild_to_check = 'match_id';
					if($this->ifexists($table_name,$id,$fild_to_check)){							
						// $response['message'] = "Team score already Added";
						$response['message'] = "Already Added";
						// return true;
					}else{
						
						$tbl_tournament_match_result = $this->db->table($table_name)->insert($tbl_tournament_match_result_data);
						if($tbl_tournament_match_result){
							// $response['message'] = "Team score Added Successfully";
							$response['message'] = "Added Successfully";
							// return true;
						}
					}
				}
			}				
		}
		return $response;		
			
		
	}
   
}
