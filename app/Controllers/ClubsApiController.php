<?php

namespace App\Controllers;

class ClubsApiController extends ApiBaseController
{
    /**
     * Test Api function
     *
     * @return void
     */
	public function index() 
    {
        $response['status'] = "success";
		$response['message'] = "Api working fine!";
		$this->sendResponse($response);
    }

    /**
     * Get-Club-Team-List
     * @endpoint get-club-teams-list
     * @url: http://yourdomain.com/api/get-club-teams-list
     */
    public function get_club_team_list(){
        if($this->authenticate_api())
        {   $response = array( "status" => "error" );
            $result = $this->db->table('tbl_club')->get()->getResultArray();
            $club_team = array();
            foreach($result as $clubs)
            {
                $team = $clubs;
                $team['team'] = $this->db->table('tbl_team')
                                         ->where('club_id', $clubs['club_id'])
                                         ->get()->getResultArray();
                array_push($club_team, $team);
                
            }
            if(!empty($result)){
             
                return $club_team;
             
            } else {
                return false;
            }
        } 
    }
    /**
     * join-team
     * @endpoint join-team
     * @url: http://yourdomain.com/api/join-team
     * @param team_id : team_id
     * @param user_id : designation
     * @param designation : 1 for player 2 for Head Coach 3 for Assistant coach and 4 for manager	
     */
    public function join_team()
    {
        if($this->authenticate_api())
        {   
            $response = array( "status" => "error" );
            $required_fields = array("team_id", "user_id", 'designation');
            $status = $this->verifyRequiredParams($required_fields);
            $team_id = $this->request->getVar("team_id");
            $user_id = $this->request->getVar("user_id");
            $designation = $this->request->getVar("designation");
            
            if(!in_array($designation, array(0, 1, 2, 3))){
                $response['message'] = "Please enter for designation.";
                $this->sendResponse($response);
            }
            if($this->ifempty($designation, "Designation")!== true){
                $response['message'] = $this->ifempty($designation, "Designation");
                $this->sendResponse($response);
            }
            if($this->ifempty($team_id, "team id")!== true){
                $response['message'] = $this->ifempty($team_id, "team id");
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_team', $team_id, 'team_id') != true)
            {
                $response['message'] = "Please enter valid team id.";
                $this->sendResponse($response);
            }
            if($this->ifempty($user_id, "user id")!== true){
                $response['message'] = $this->ifempty($user_id, "user id");
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_user', $user_id, 'user_id') != true)
            {
                $response['message'] = "Please enter valid user id.";
                $this->sendResponse($response);
            }
           
            $insertdata = array( 
                "team_id"       =>   $team_id,
                "user_id"       =>   $user_id,
                "designation"   =>   $designation
              
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
     * check user in team or not
     * @endpoint verify-team
     * @url http://yourdomain.com/api/verify-team
     * @param user_id : user_id
     */
    public function verify_team()
    {
         if($this->authenticate_api())
        {   
            $response = array( "status" => "error" );
            $required_fields = array( "user_id");
            $status = $this->verifyRequiredParams($required_fields);
            $user_id = $this->request->getVar("user_id");
            
            if($this->ifempty($user_id, "user id")!== true){
                $response['message'] = $this->ifempty($user_id, "user id");
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_user', $user_id, 'user_id') != true)
            {
                $response['message'] = "Please enter valid user id.";
                $this->sendResponse($response);
            }
           
            if($this->ifexists('tbl_team_member_relation', $user_id, 'user_id') == true)
            {   
                $response['status'] = "success";
                $response['team_status'] = true; 
                $response['data'] = array();
                $response['message'] = "You have joined the team.";
                $this->sendResponse($response);
            } else {
                
                $response['status'] = "success";
                $response['team_status'] = false; 
                 $response['data'] = $this->get_club_team_list();
                $response['message'] = "You have not joined the team.";
                $this->sendResponse($response);
            }
          
           
        } 
    }
}
