<?php

namespace App\Controllers;

class ApiBaseController extends BaseController
{   /**
    * Used for api authentication function
    *
    * @return void
    */
	public function authenticate_api()
    {
        $response = array( "status" => "authentication falied!" );
        if(isset($_SERVER['PHP_AUTH_USER'])||isset($_SERVER['PHP_AUTH_PW']))
        {
            $cridentails=array("username"=>$_SERVER['PHP_AUTH_USER']
            ,"password"=>$_SERVER['PHP_AUTH_PW']);
            $value= $this->db->table('tbl_apiaauth')->where($cridentails)->countAllResults();
            if($value > 0)
            {
                return true;
            }
            else{
                $this->sendResponse($response);
            }
        }
        else{
              $this->sendResponse($response);
            
        }
    }
    /**
     * Used for Sending response
     */
    public function sendResponse($response) {
        header('Content-Type: application/json');
        echo json_encode($response);
        die;
    }
    /**
     * Check Required Param
     */
    public function verifyRequiredParams($fields) {
        $error = false;
        $error_fields = "";
        $request_params = array();
        $request_params = $_REQUEST;
        foreach ($fields as $field)
		{
			if (!isset($request_params[$field]))
			{
				$error = true;
				$error_fields .= $field . ', ';
			}
		}
		if ($error)
		{
			// Required field(s) are missing or empty
			// echo error json and stop the app
			$response = array();
			$response["error"] = true;
			$response["message"] = 'One or more fileds are required. ' . substr($error_fields, 0, -2);
			return false;
		}
		else
		{
			return true;
		}
    }
    /**
     * check if exists
     */
    public function ifexists($table_name, $value, $field)
    {
        $query = $this->db->table($table_name)->where($field, $value);
        if ($query->countAllResults() > 0){
            return true;
        }
        else{
            return false;
        }
    }
     /**
     * check if exists
     */
    public function ifexistsexcludeid($table_name, $value, $field, $user_id)
    {
        $query = $this->db->table($table_name)->where(array($field => $value, "user_id<>" => $user_id));
        if ($query->countAllResults() > 0){
            return true;
        }
        else{
            return false;
        }
    }
     /**
     * exculde id function
     *
     * @param [type] $table_name
     * @param [type] $value
     * @param [type] $field
     * @param [type] $exclude_field
     * @param [type] $exvalue
     * @return void
     */
    public function checkteamexists($table_name, $value, $field, $exclude_field, $exvalue)
    {
        $query = $this->db->table($table_name)->where(array($field => $value, $exclude_field."<>" => $exvalue));
        if ($query->countAllResults() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    /**
     * checkpositionoccupied function
     *
     * @param [type] $table_name
     * @param [type] $user_id
     * @param [type] $team_id
     * @param [type] $designation
     * @return void
     */
    public function checkpositionoccupied($table_name, $team_id, $designation)
    {
        $query = $this->db->table($table_name)->where(array( "team_id" => $team_id, 'designation' => $designation, 'deletestatus<>'=> 1));
        if ($query->countAllResults() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    
    /**
     * Check if empty
     */
    public function ifempty($value, $field)
    {
        if(empty($value) == true){
            return "Please enter ".$field." value.";
        }
        return true;
    }
    /**
     * Check valid Email
     */
    public function is_valid_email(string $str = null): bool {
		if (function_exists('idn_to_ascii') && defined('INTL_IDNA_VARIANT_UTS46') && preg_match('#\A([^@]+)@(.+)\z#', $str, $matches))
		{
			$str = $matches[1] . '@' . idn_to_ascii($matches[2], 0, INTL_IDNA_VARIANT_UTS46);
		}

		return (bool) filter_var($str, FILTER_VALIDATE_EMAIL);
    }
    /**
     * Get date
     */
    public function get_date($date){
        //Creating a DateTime object
        $date_time_Obj = date_create($date);
        //formatting the date/time object
        $format = date_format($date_time_Obj, "Y-m-d");
        return $format;
    }
    /**
     * Get mobile  date
     */
    public function get_mobile_date($date){
        //Creating a DateTime object
        $date_time_Obj = date_create($date);
        //formatting the date/time object
        $format = date_format($date_time_Obj, "d-m-Y");
        return $format;
    }
    /**
     * format_datetime
     *
     * @param [type] $datetime
     * @return void
     */
    public function format_datetime($datetime){
         //Creating a DateTime object
        $date_time_Obj = date_create($datetime);
        //formatting the date/time object
        $format = date_format($date_time_Obj, "Y-m-d H:i:s ");
        return $format;
    }
     /**
     * date_format_list function
     *
     * @param [type] $date
     * @return void
     */
    public function date_format_list($date){
         //Creating a DateTime object
        $date_time_Obj = date_create($date);
        //formatting the date/time object
        $format = date_format($date_time_Obj, "l d,F");
        return $format;
    }
     /**
     * date_format_list function
     *
     * @param [type] $date
     * @return void
     */
    public function time_format_list($date){
         //Creating a DateTime object
        $date_time_Obj = date_create($date);
        //formatting the date/time object
        $format = date_format($date_time_Obj, "ha");
        return $format;
    }
    /**
     * Calculate age
     */
    public function calculate_age($dob){
        $today = date("Y-m-d");
        $diff = date_diff(date_create($dob), date_create($today));
        return $diff->format('%y');
    }
    /**
	 * Use for upload file for case in the perticuler location
	 */
	public function uploadFilefunc($key, $type = 'image', $user_id)
	{
		$response = array("status" => "error");
		if (!empty($_FILES[$key]["name"])) {
			//$this->load->library('upload');

			$folder =  ROOTPATH . 'public/uploads/profile_images/' . $user_id;

			if (!is_dir($folder)) {
				mkdir($folder, 0777, TRUE);
			}

			$uid = uniqid();
			$ext = pathinfo($_FILES[$key]["name"], PATHINFO_EXTENSION);

			$filename = 'profileimage' . $uid . '.' . $ext;
			//$config['file_name'] = $filename;
			//$config['overwrite'] = TRUE;

			//$this->upload->initialize($config);

			if ($filedata = $this->request->getFiles()) {
				if ($file = $filedata[$key]) {

					if ($file->isValid() && !$file->hasMoved()) {
						//$newName = $file->getRandomName(); //This is if you want to change the file name to encrypted name
						$file->move($folder, $filename);

						// You can continue here to write a code to save the name to database
						// db_connect() or model format
						$response['status']  = 'success';
						$response['message'] = 'File successfully uploaded';
						$response['filename'] = $filename;
					} else {
						$response['status']  = 'error';
						$response['message'] = 'File could not be uploaded!. Please try again';
					}
				} else {
					$response['status']  = 'error';
					$response['message'] = 'File could not be uploaded!. Please try again';
				}
			} else {
				$response['status']  = 'error';
				$response['message'] = 'File could not be uploaded!. Please try again';
			}
		} else {
			$response['status']  = 'error';
			$response['message'] = 'Please select autograph file for upload';
		}
		return $response;
	}

    /**
     * Get user info
     */
    public function getUserInfo($user_id,$match_id='') {
        $result = $this->db->table('tbl_user_login')->select('first_name, last_name, address, tbl_nationality.nationality as nationality,tbl_nationality.id as nation_id, flag_image, age, position, gender,image, email, height, weight' )
                                        ->join('tbl_user', 'tbl_user.user_id = tbl_user_login.user_id')
                                        ->join('tbl_nationality', 'tbl_nationality.id = tbl_user.nationality')                        
                                        ->where(array('tbl_user.user_id' => trim($user_id)))->get()->getRowArray();       
        if(!empty($result)){
            $result['dob'] = $result['age'];
            $result['age'] = $this->calculate_age($result['age']);
            $result['total_game'] = count($this->player_match_id_array($user_id));
			$result['match_id'] = $match_id;
            if($result['position']!== 0){
                $position = $this->db->table('tbl_position')->select('position, slug, p_id')->where('p_id',$result['position'])->get()->getRowArray();
                
                if(!empty($position)){
                    $result['p_id'] = $position['p_id'];
                    $result['position_slug'] = $position['slug'];
                    $result['position'] = $position['position'];
					$result['slug'] = $position['slug'];										// $result['team_full_status'] = $match_team['team_full_status'];
                }
                else{
                    $result['position'] = null;										
					$result['slug'] = null;
                }
            }
			if($match_id != ''){
				$match_players = $this->db->table('tbl_match_team')->where('match_id', $match_id)->get()->getResultArray();
				foreach($match_players as $m_player){
					$result['jursey_no'] = $m_player['jursey_no'];
				  
				}
			}		
            
            if($result['gender'] == 0){
                $result['gender'] = "Male";
            } elseif($result['gender'] == 1){
                $result['gender'] = "Female";
            } elseif($result['gender'] == 1){
                $result['gender'] = "Transgender";
            }
            if(!empty($result['image']))
            {
                $imagepath = base_url() . "/public/uploads/profile_images/". $user_id."/" . $result['image'];
                $result['image'] = $imagepath;
            }else{
				$imagepath = "";
                $result['image'] = $imagepath;
			}
            return $result;
        }
        return false;
    }
    public function ifexistscustom($table_name, $condition){
        $query = $this->db->table($table_name)->where($condition);
        if ($query->countAllResults() > 0){
            return true;
        }
        else{
            return false;
        }
    }
	/**
     * CHECK IF team is full  match team full => true,  match team not full => false
	 * match_id : match_id
     */
	public function ifteamfull($match_id){        
		$query = $this->db->table('tbl_match_team')->where('match_id',$match_id);		
		if ($query->countAllResults() > (TEAM_TOTAL_MEMBER-1)){            
			return true;        
		}        
		else{            
			return false;        
		}    
	}
	/**
     * CHECK IF match time current or past :  current or past match time => true,  match future match time => false
     * match_id : match_id
     */
	public function match_time($match_id){
		$match_tournament = $this->db->table('tbl_tournament_match')->where('id', $match_id)->get()->getRowArray();
		$result['datetime'] = $match_tournament['datetime'];
		date_default_timezone_set("Asia/Kolkata");
		$match_time_date = strtotime($match_tournament['datetime']);
		$current_time_date = strtotime(date("Y-m-d H:i:s"));
		if(  $current_time_date >= $match_time_date){
			return true;
		}else{
			return false; 
		}			
	}
	/**
     * return team size
     * match_id : match_id
     */
	public function match_team_size($match_id){
		$query = $this->db->table('tbl_match_team')->where('match_id',$match_id);		
		return $query->countAllResults();
	}
	/**
     * check coach team or coach opponent team
     * match_id : match_id
     * team_id : team_id
     * team_or_opponent : team_id or opponent_team_id
     */
	public function check_coach_team($match_id,$team_id,$team_or_opponent=''){
		if($team_or_opponent && $team_or_opponent == 'team_id')
			$where['team_id'] = $team_id;
		if($team_or_opponent && $team_or_opponent == 'opponent_team_id')
			$where['opponent_team_id'] = $team_id;
		$where['id'] = $match_id;
		$query = $this->db->table('tbl_tournament_match')->where($where);
		
		if ($query->countAllResults() > 0){
            return true;
        }
        else{
            return false;
        }
	}
	/**
     * get List of year
     * feild : player_id,match_id,team_id => player_id,match_id,team_id
     * value : player_id,match_id,team_id => 1,2..
    */
	public function year_list_of_match_and_player($feild='',$value=''){		
		$query = $this->db->table('tbl_match_team');
		$query = $query->select('DISTINCT YEAR(datetime) as year');
		$query = $query->join('tbl_tournament_match', 'tbl_tournament_match.id = tbl_match_team.match_id','left');
		$query = $query->join('tbl_team', 'tbl_team.team_id = tbl_tournament_match.team_id','left');
		if($value){
			$where[$feild] = $value;
			$query = $query->where($where);
		}
		$query = $query->get()->getResultArray();
		return $query;
	}
	/**
     * get List of match_id, that attempt the player
     * player_id : player_id => 1,2..
    */
	public function player_match_id_array($player_id){
		$query = $this->db->table('tbl_match_team')->select('match_id');
		$query = $query->join('tbl_tournament_match', 'tbl_tournament_match.id = tbl_match_team.match_id','left');
		$query = $query->where('player_id',$player_id)->distinct('match_id')->get()->getResultArray();
		return $query;
	}
	/**
     * get List of team_id, that player participate
     * player_id : player_id => 1,2..
    */
	public function player_team_id_array($player_id){
		$query = $this->db->table('tbl_match_team')->select('team_id');
		$query = $query->join('tbl_tournament_match', 'tbl_tournament_match.id = tbl_match_team.match_id','left');
		$query = $query->where('player_id',$player_id)->distinct('team_id')->get()->getResultArray();
		return $query;
	}
	
}
