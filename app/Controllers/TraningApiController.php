<?php

namespace App\Controllers;

class TraningApiController extends ApiBaseController
{
    /**
     * Player Attendance function
     * @endpoint player-attendance
     * @url http://yourdomain.com/api/player-attendance
     * @param traning_id : traning_id
     * @param player_id: player_id
     * @param availability : availability (1 for present, 0 for absent)
     */
    public function players_attendance(){
        if($this->authenticate_api())
        {   
            $response = array( "status" => "error" );
            $required_fields = array("traning_id", "player_id", "availability");
            $status = $this->verifyRequiredParams($required_fields);
            $traning = $this->request->getVar("traning_id");
            $player_id = $this->request->getVar("player_id");
            $availability = $this->request->getVar("availability");
            if($this->ifempty($traning, "traning id")!== true){
                $response['message'] = $this->ifempty($traning, "traning id");
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_traning', $traning, 'id') != true){
                $response['message'] = "traning id does no exist.";
                $this->sendResponse($response);
            }
            if($this->ifempty($player_id, "player id")!== true){
                $response['message'] = $this->ifempty($player_id, "player id");
                $this->sendResponse($response);
            }
            $connection = array(
                "user_id" => $player_id,
                "user_type" => 0
            );
            if($this->ifexistscustom('tbl_user', $connection) != true){
                $response['message'] = "player does not exist.";
                $this->sendResponse($response);
            }
            $check = array(
                "traning_id"   =>   $traning,
                "player_id"    =>   $player_id,
                "attendence_status" => 1
            );
            if($this->ifexistscustom('tbl_traning_attendance', $check)){
                $response['message'] = "player has already marked present.";
                $this->sendResponse($response);
            }
            if(!in_array($availability, array(0, 1))){
                $response['message'] = "Please enter valid availability value.";
                $this->sendResponse($response);
            }
            if($availability == 1){
                $attend = array(
                'traning_id'        => $traning,
                'player_id'         => $player_id,
                "attendence_status" => 1,
                );
                $attendrecord = $this->db->table('tbl_traning_attendance')->insert($attend);
                if(!empty($attendrecord)){
                    $response['status'] = "success.";
                    $response['message'] = "Player marked present.";
                    $this->sendResponse($response);
                } else {
                    $response['message'] = "something went wrong.";
                    $this->sendResponse($response);
                }
            } else {
                $attend = array(
                'traning_id'        => $traning,
                'player_id'         => $player_id,
                "attendence_status" => 0,
                );
                $attendrecord = $this->db->table('tbl_traning_attendance')->insert($attend);
                if(!empty($attendrecord)){
                    $response['status'] = "success.";
                    $response['message'] = "Player marked absent.";
                    $this->sendResponse($response);
                } else {
                    $response['message'] = "something went wrong.";
                    $this->sendResponse($response);
                }
             
            } 
        }
    }
}
