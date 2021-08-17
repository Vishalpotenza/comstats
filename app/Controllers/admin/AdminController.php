<?php

namespace App\Controllers\Admin;
use App\Controllers\ApiBaseController;
use App\Models\Admin_model;
class AdminController extends ApiBaseController
{
	/**
	 * Admin can find all played and upcoming matches
	 *
	 * @return view
	 */
	public function all_matches_get()
	{
		   
			$club_model = new Club_model();
			$view['view'] = array('title'=>"Match List");
			$view['content'] = "matchs/matches";
			$view['data'] = array("club_details" => $club_model->getallclubs());
			return view('default', $view);
            $result = array();
            /**
             * Tournament list
             */
			// $coachs_where['user_id'] = $user_id;
			$coachs_where['deletestatus'] = 0;
            $tournament = $this->db->table('tbl_tournament')->get()->getResultArray();
            $coachs = $this->db->table('tbl_team_member_relation')->select('team_id')->where($coachs_where)->get()->getRowArray();
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
                $trannie['tournament_name'] = $this->db->table('tbl_tournament')->select('name')->where('id', 1)->get()->getRowArray();
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
            
             
       
		
		
		
		
        $view['content'] = "dashboard/index";
		$view['data'] = array();
		return view('default', $view);
	}

}
