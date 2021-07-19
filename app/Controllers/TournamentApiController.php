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
            if($this->ifexistscustom('tbl_user', array( 'user_id' => $user_id, 'user_type' => 1 )) != true){
                $response['message'] = "Only Coach can schedule match !.";
                $this->sendResponse($response);
            }
            $result = array();
            /**
             * Tournament list
             */
            $tournament = $this->db->table('tbl_tournament')->get()->getResultArray();
            $coachs = $this->db->table('tbl_team_member_relation')->select('team_id')->where(array('user_id'=> $user_id))->get()->getRowArray();
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
            $result['teams'] = $teams;

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
     * @param ground_status : ground_status (pass only when not traning)
     * @param ground_id : ground_id (pass only if traning)
     * @param user_id: user_id
     */
    public function schedule_match(){
         if($this->authenticate_api())
        {   
            $response = array( "status" => "error" );
            $required_fields = array("user_id", "opponent_team_id", "tournament_id", "dateofmatch", "address");
            $status = $this->verifyRequiredParams($required_fields);
            $user_id = $this->request->getVar("user_id");
            $tournament_id = $this->request->getVar("tournament_id");
            $grounds_id = $this->request->getVar("ground_id");
            $opponent_team_id = $this->request->getVar("opponent_team_id");
            $dateofmatch = $this->request->getVar("dateofmatch");
            $ground_status = $this->request->getVar("ground_status");
            $address = $this->request->getVar("address");

            if($this->ifempty($user_id, "User id")!== true){
                $response['message'] = $this->ifempty($user_id, "User Id");
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_user', $user_id, 'user_id') != true){
                $response['message'] = "User does no exist.";
                $this->sendResponse($response);
            }
            if($this->ifexistscustom('tbl_user', array( 'user_id' => $user_id, 'user_type' => 1 )) != true){
                $response['message'] = "Only Coach can schedule match !.";
                $this->sendResponse($response);
            }
            if($this->ifempty($tournament_id, "Tournament id")!== true){
                $response['message'] = $this->ifempty($tournament_id, "Tournament Id");
                $this->sendResponse($response);
            }
            if($this->ifempty($opponent_team_id, "Opponent team id")!== true){
                $response['message'] = $this->ifempty($opponent_team_id, "Opponent team id");
                $this->sendResponse($response);
            }
            // if($this->ifexists('tbl_user', $user_id, 'user_id') != true){
            //     $response['message'] = "User does no exist.";
            //     $this->sendResponse($response);
            // }
           
            /**
             * Get user team
             */
            $team = $this->db->table('tbl_team_member_relation')->select('team_id')->where(array('user_id'=> $user_id))->get()->getRowArray();
            if(!empty($team))
            {
                if($team['team_id'] == $opponent_team_id){
                    $response['message'] = "Team and opponnet team can not be same";
                    $this->sendResponse($response);
                }
            }
             /**
             * Check tournament
             */
            $tournament = $this->db->table('tbl_tournament')->where('id', $tournament_id)->get()->getRowArray();
            if($tournament['slug'] == "traning"){
                if($this->ifempty($grounds_id, "Ground id")!== true){
                    $response['message'] = $this->ifempty($grounds_id, "Ground Id");
                    $this->sendResponse($response);
                }
            } else {
                if($this->ifempty($ground_status, "Ground status")!== true){
                    $response['message'] = $this->ifempty($ground_status, "Ground status");
                    $this->sendResponse($response);
                }
            }
            /**
             * Check teams
             */

            die();
            $result = array();
            /**
             * Tournament list
             */
            $tournament = $this->db->table('tbl_tournament')->get()->getResultArray();
            $coachs = $this->db->table('tbl_team_member_relation')->select('team_id')->where(array('user_id'=> $user_id))->get()->getRowArray();
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
            $result['teams'] = $teams;

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
   
   
}
