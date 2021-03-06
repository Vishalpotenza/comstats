<?php

namespace App\Controllers;

class ApiController extends ApiBaseController
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
     * Register Player/Coach
     * @endpoint
     * @url: http://yourdomain.com/api/register
     * @param first_name: first name
     * @param last_name: last name
     * @param address: address
     * @param nationality: nationality-id
     * @param dob: date
     * @param gender: 0 for male , 1 for female , 2 for transgender
     * @param postion: position-id
     * @param height: height in cm
     * @param weight: Weight in kg
     * @param role: 0 for player, 1 for coach
     * @param email_id: email_id
     * @param password: password
     */
    public function register()
    {
        if($this->authenticate_api())
        {   
            $response = array( "status" => "error" );
            $required_fields = array("first_name", "last_name", 'address', 'nationality', 'dob', 'gender', 'role', 'email_id', 'password');
            $status = $this->verifyRequiredParams($required_fields);
            $role = $this->request->getVar("role");
            $first_name = $this->request->getVar("first_name");
            $last_name = $this->request->getVar("last_name");
            $address = $this->request->getVar("address");
            $nationality = $this->request->getVar("nationality");
            $dob = $this->request->getVar("dob");
            $position = $this->request->getVar("position");
            $gender = $this->request->getVar("gender");
            $email_id = $this->request->getVar("email_id");
            $password = $this->request->getVar("password");
            $height = $this->request->getVar('height');
            $weight = $this->request->getVar('weight');
           
            if(!in_array($role, array(0, 1))){
                $response['message'] = "Please enter for value 0 for player or 1 for coach.";
                $this->sendResponse($response);
            }
            if($this->ifempty($first_name, "First Name")!== true){
                $response['message'] = $this->ifempty($first_name, "first name");
                $this->sendResponse($response);
            }
            if($this->ifempty($last_name, "Last name")!== true){
                $response['message'] = $this->ifempty($last_name, "last name");
                $this->sendResponse($response);
            }
            if($this->ifempty($nationality, "Nationality")!== true){
                $response['message'] = $this->ifempty($nationality, "nationality");
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_nationality', $nationality, 'id') != true)
            {
                $response['message'] = "Please enter valid nationality.";
                $this->sendResponse($response);
            }
            if($this->ifempty($dob, "dob")!== true){
                $response['message'] = $this->ifempty($dob, "dob");
                $this->sendResponse($response);
            }
          
            if(!in_array($gender, array(0, 1, 2))){
                $response['message'] = "Please enter for value 0 for male or 1 for female and 2 for transgender.";
                $this->sendResponse($response);
            }
            if($this->ifempty($email_id, "email_id")!== true){
                $response['message'] = $this->ifempty($email_id, "Email id");
                $this->sendResponse($response);
            }
            if(!$this->is_valid_email($email_id)){
                $response['message'] = "Please Enter Valid email address.";
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_user_login', $email_id, 'email') == true)
            {
                $response['message'] = "Email Id already exists.";
                $this->sendResponse($response);
            }
            if($this->ifempty($password, "password")!== true){
                $response['message'] = $this->ifempty($password, "password");
                $this->sendResponse($response);
            }
            if($this->ifempty($height, "height")!== true){
                $response['message'] = $this->ifempty($height, "height");
                $this->sendResponse($response);
            }
            if($this->ifempty($weight, "weight")!== true){
                $response['message'] = $this->ifempty($weight, "weight");
                $this->sendResponse($response);
            }
            
            if($role == 0){
                if($this->ifempty($position, "Position")!== true){
                $response['message'] = $this->ifempty($position, "Position");
                $this->sendResponse($response);
                }
                if($this->ifexists('tbl_position', $position, 'p_id') != true)
                {
                    $response['message'] = "Please enter valid position.";
                    $this->sendResponse($response);
                }
            }
            $insertdata = array( 
                "first_name"   =>   $first_name,
                "last_name"    =>   $last_name,
                "address"      =>   $address,
                "nationality"  =>   $nationality,
                "age"          =>   $this->get_date($dob),
                "gender"       =>   $gender,
                "user_type"    =>   $role,
                "height"       =>   $height,
                "weight"       =>   $weight 
            );
            if($role == 0){
                $insertdata['position'] = $position;
            }
            $result = $this->db->table('tbl_user')->insert($insertdata);
            if(!empty($result))
            {
                $user_login = array(
                    "user_id"    =>   $this->db->insertID(),
                    "email"      =>   $email_id,
                    "password"   =>   md5($password) 
                );
                $result = $this->db->table('tbl_user_login')->insert($user_login);
                if(!empty($result)){
                    $response['status'] = "success";
                    $response['message'] = 'Successfully Registered details.';
                    $this->sendResponse($response);
                }
                else{
                    $response['message'] = 'Not able to registered.';
                    $this->sendResponse($response);
                }
            }
            else{
                 $response['message'] = 'Not able to registered.';
                 $this->sendResponse($response);
            }
           
        } 
    }
    /**
     * Get nationality
     * @endpoint get-nationality
     * @url: http://yourdomain.com/api/get-nationality
     */
    public function get_nationality()
    {
        
        if($this->authenticate_api())
        {   $response = array( "status" => "error" );
            $result = $this->db->table('tbl_nationality')->get()->getResultArray();
            if(!empty($result)){
                $response['status'] = "success";
                $response['message'] = 'Successfully got nationality details.';
                $response['data'] = $result;
                $this->sendResponse($response);
            }
            else{
                $response['message'] = 'Nationality not found.';
                $this->sendResponse($response);
            }
        } 
    }
    /**
     * Get Position
     * @endpoint get-postions
     * @url: http://yourdomain.com/api/get-postion
     */
    public function get_postions()
    {
         if($this->authenticate_api())
        {   $response = array( "status" => "error" );
            $result = $this->db->table('tbl_position')->get()->getResultArray();
            if(!empty($result)){
                $response['status'] = "success";
                $response['message'] = 'Successfully got position details.';
                $response['data'] = $result;
                $this->sendResponse($response);
            }
            else{
                $response['message'] = 'position not found.';
                $this->sendResponse($response);
            }
        } 
    }
    /**
     * User login
     * @endpoint user-login
     * @url: http://yourdomain.com/api/user-login
     * @param email_id : email
     * @param password : password
     * @param device_token : device_token
     * 
     */
    public function user_login()
    {
        if($this->authenticate_api())
        {   
            $response = array( "status" => "error" );
            $required_fields = array("email", "password", "device_token");
            $status = $this->verifyRequiredParams($required_fields);
            $email_id = $this->request->getVar("email_id");
            $password = $this->request->getVar("password");
            $device_token = $this->request->getVar("device_token");
            if($this->ifempty($email_id, "email_id")!== true){
                $response['message'] = $this->ifempty($email_id, "Email id");
                $this->sendResponse($response);
            }
            if($this->ifempty($device_token, "device_token")!== true){
                $response['message'] = $this->ifempty($device_token, "device_token");
                $this->sendResponse($response);
            }
            if(!$this->is_valid_email($email_id)){
                $response['message'] = "Please Enter Valid email address.";
                $this->sendResponse($response);
            }
            if($this->ifempty($password, "password")!== true){
                $response['message'] = $this->ifempty($password, "password");
                $this->sendResponse($response);
            }
            $user_login = $this->db->table('tbl_user_login')->join('tbl_user', 'tbl_user.user_id = tbl_user_login.user_id')->where(array('email' => trim($email_id), 'password' => md5(trim($password))));
            if ( $user_login->countAllResults() > 0)
            {
                $result = $user_login->join('tbl_user', 'tbl_user.user_id = tbl_user_login.user_id')->select('tbl_user.user_id, tbl_user.user_type, tbl_user_login.email')->where(array('email' => trim($email_id), 'password' => md5(trim($password))))->get()->getRowArray();
                if(!empty($result)){
                    $response['status'] = "success";
                    $this->db->table('tbl_user_login')->update(array('device_token'=>$device_token), "user_id = ".$result['user_id']."");
                    $response['message'] = 'Successfully logged in.';
                    $response['data'] = $result;
                    $this->sendResponse($response);
                }
                else{
                    $response['message'] = "Something went wrong.";
                    $this->sendResponse($response);
                }

            } else {
                $response['message'] = "Invalid Email id or Password.";
                $this->sendResponse($response);
            }
             
        }
    }
    /**
     * Get-user-info
     * @endpoint user-info
     * @url: http://yourdomain.com/api/user-info
     * @param user_id : user_id => palyer id or coach id
	 * @responce  user_type => "Coach" For coach, "player" for player
	 * 
     */
    public function user_info()
    {
         if($this->authenticate_api())
        {   
            $response = array( "status" => "error" );
            $required_fields = array("user_id");
            $status = $this->verifyRequiredParams($required_fields);
            $user_id = $this->request->getVar("user_id");
           
            if($this->ifempty($user_id, "User id")!== true){
                $response['message'] = $this->ifempty($email_id, "Email id");
                $this->sendResponse($response);
            }
            $user_details = $this->db->table('tbl_user_login')
                                ->join('tbl_user', 'tbl_user.user_id = tbl_user_login.user_id')
                                ->where(array('tbl_user.user_id' => trim($user_id)));
            if ( $user_details->countAllResults() > 0)
            {
                $result = $this->db->table('tbl_user_login')->select('first_name, last_name, address, tbl_nationality.nationality as nationality,tbl_nationality.id as nation_id, flag_image, age, position, gender,image, email, height, weight, tbl_user.user_type' )
                                        ->join('tbl_user', 'tbl_user.user_id = tbl_user_login.user_id')
                                        ->join('tbl_nationality', 'tbl_nationality.id = tbl_user.nationality')                        
                                        ->where(array('tbl_user.user_id' => trim($user_id)))->get()->getRowArray();
                                  
                
				if(!empty($result)){
                    $result['dob'] = $this->get_mobile_date($result['age']);
                    $result['age'] = $this->calculate_age($result['age']);
                    $result['total_game'] = count($this->player_match_id_array($user_id));
                    if($result['position']!== 0){
                        $position = $this->db->table('tbl_position')->select('position, p_id')->where('p_id',$result['position'])->get()->getRowArray();
                        
                        if(!empty($position)){
                            $result['p_id'] = $position['p_id'];
                            $result['position'] = $position['position'];
                        }
                        else{
                            $result['position'] = null;
                        }
                    }
                    $result['flag_image']= base_url()."/public/uploads/flags/".$result["nation_id"]."/".$result['flag_image'];
                    
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
                    }
					$player_id = $user_id;
					
					
					
					if($result['user_type'] == 0){
						/* ============================> player score ==========================================> */
						$result['user_type'] = "Player";
						$player_team_id_array = $this->player_team_id_array($player_id);
						$year_list = $this->year_list_of_match_and_player("player_id", $player_id);
						$data = [];
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
						$result['score'] = $data;
						/* ============================>player score ============================================> */	
					}else{
						
						// unset($result['position']);
						$result['user_type'] = "Coach";
						
						$player_team_id_array = $this->db->table('tbl_team_member_relation')->select('team_id')->where("user_id", $user_id)->distinct('team_id')->get()->getResultArray();
						$data = [];
						
						if($player_team_id_array){
							
							$player_team_id_array = array_column($player_team_id_array,"team_id");
							
							// $total_match = $this->db->table('tbl_tournament_match')->select('id')->whereIn("team_id", $player_team_id_array)->countAllResults();
							// $result['total_game'] = $total_match;
							
							$total_game = 0;
							$year_list = $this->db->table('tbl_tournament_match')->select('DISTINCT YEAR(datetime) as year')->whereIn("team_id", $player_team_id_array);
							$year_list = $year_list->get()->getResultArray();
							
							if($year_list){
								
								$year_list = array_column($year_list,"year");
								
								foreach($year_list as $year){
									
									foreach($player_team_id_array as $array_team_id){
										
										$data1 = [];
										
										$where['YEAR(datetime)'] = $year;
										$where['tbl_tournament_match.team_id'] = $array_team_id;
										
										
										$query = $this->db->table('tbl_tournament_match_result');
										$query = $query->select('tbl_tournament_match_result.id, YEAR(datetime) as year, tbl_tournament_match_result.*, , tbl_team.team_name, tbl_team.team_id');
										$query = $query->join('tbl_tournament_match', 'tbl_tournament_match.id = tbl_tournament_match_result.match_id','left');
										 
										$query = $query->join('tbl_team', 'tbl_team.team_id = tbl_tournament_match.team_id','left');
										$query = $query->where($where);
										
										$query = $query->get()->getResultArray();			
										
										
										$i=0;
										
										foreach($query as $data_result){
											$i++;
											$data1['team_name'] = $data_result['team_name'] ? $data_result['team_name'] : 0;
										}
										$data1['total_matchs'] = $i;
										
										if($i > 0){
											$data1['year'] = $year;
											$query_sub = $this->db->table('tbl_tournament_match_result');
											$query_sub = $query_sub->join('tbl_tournament_match', 'tbl_tournament_match.id = tbl_tournament_match_result.match_id','left');
											$query_sub = $query_sub->where($where);
											$query_sub = $query_sub->get()->getResultArray();
											$draw = $win = $lose = 0;
											
											foreach($query_sub as $data_sub){										
												if($data_sub['winner_team_id'] == 0){
													$draw++;
													$total_game++;
												}else if($data_sub['winner_team_id'] == $data_sub['team_id']){
													$win++;
													$total_game++;
												}else{
													$lose++;
													$total_game++;
												}
											}
											
											$data1['draw'] = $draw;
											$data1['win'] = $win;
											$data1['lose'] = $lose;
											$data1['win_per'] = ($data1['win']*100)/$data1['total_matchs'];	
											$data[] = $data1;
											
										}
																											
									}				
								}
							}
							
							$result['total_game'] = $total_game;
						}
						
						$result['score'] = $data;					
						
					}
					
					
                    $response['status'] = "success";
                    $response['message'] = 'Successfully retrived  user data.';
                    $response['data'] = $result;
                    $this->sendResponse($response);
                }
                else{
                    $response['message'] = "Something went wrong.";
                    $this->sendResponse($response);
                }

            } else {
                $response['message'] = "Please insert valid user id.";
                $this->sendResponse($response);
            }
             
        }
    }
    /**
     * update-user
     * @param first_name: first name
     * @param last_name: last name
     * @param address: address
     * @param nationality: nationality-id
     * @param dob: date
     * @param gender: 0 for male , 1 for female , 2 for transgender
     * @param postion: position-id
     * @param height: height in cm
     * @param weight: Weight in kg
     * @param role: 0 for player, 1 for coach
     * @param email_id: email_id
     * @param password: password
     * @param user_id : user_id
     * @param profile_image : file
     */
    public function update_user()
    {
        if($this->authenticate_api())
        {   
            $response = array( "status" => "error" );
            $required_fields = array("first_name", "last_name", 'address', 'nationality', 'dob', 'gender', 'role', 'email_id');
            $status = $this->verifyRequiredParams($required_fields);
            $role = $this->request->getVar("role");
            $first_name = $this->request->getVar("first_name");
            $last_name = $this->request->getVar("last_name");
            $address = $this->request->getVar("address");
            $nationality = $this->request->getVar("nationality");
            $dob = $this->request->getVar("dob");
            $position = $this->request->getVar("position");
            $gender = $this->request->getVar("gender");
            $email_id = $this->request->getVar("email_id");
            $height = $this->request->getVar('height');
            $weight = $this->request->getVar('weight');
            $user_id = $this->request->getVar('user_id');
            
            if($this->ifempty($user_id, "User Id")!== true){
                $response['message'] = $this->ifempty($user_id, "User Id");
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_user', $user_id, 'user_id') != true)
            {
                $response['message'] = "Please enter valid user id.";
                $this->sendResponse($response);
            }
            if(!in_array($role, array(0, 1))){
                $response['message'] = "Please enter for value 0 for player or 1 for coach.";
                $this->sendResponse($response);
            }
            if($this->ifempty($first_name, "First Name")!== true){
                $response['message'] = $this->ifempty($first_name, "first name");
                $this->sendResponse($response);
            }
            if($this->ifempty($last_name, "Last name")!== true){
                $response['message'] = $this->ifempty($last_name, "last name");
                $this->sendResponse($response);
            }
            if($this->ifempty($nationality, "Nationality")!== true){
                $response['message'] = $this->ifempty($nationality, "nationality");
                $this->sendResponse($response);
            }
            if($this->ifexists('tbl_nationality', $nationality, 'id') != true)
            {
                $response['message'] = "Please enter valid nationality.";
                $this->sendResponse($response);
            }
            if($this->ifempty($dob, "dob")!== true){
                $response['message'] = $this->ifempty($dob, "dob");
                $this->sendResponse($response);
            }
          
            if(!in_array($gender, array(0, 1, 2))){
                $response['message'] = "Please enter for value 0 for male or 1 for female and 2 for transgender.";
                $this->sendResponse($response);
            }
            if($this->ifempty($email_id, "email_id")!== true){
                $response['message'] = $this->ifempty($email_id, "Email id");
                $this->sendResponse($response);
            }
            if(!$this->is_valid_email($email_id)){
                $response['message'] = "Please Enter Valid email address.";
                $this->sendResponse($response);
            }
            if($this->ifexistsexcludeid('tbl_user_login', $email_id, 'email',$user_id) == true)
            {
                $response['message'] = "Email Id already exists.";
                $this->sendResponse($response);
            }
            if($this->ifempty($height, "height")!== true){
                $response['message'] = $this->ifempty($height, "height");
                $this->sendResponse($response);
            }
            if($this->ifempty($weight, "weight")!== true){
                $response['message'] = $this->ifempty($weight, "weight");
                $this->sendResponse($response);
            }
            $response = $this->uploadFilefunc('profile_image', 'image',  $user_id);
            if (!empty($response['status']) && $response['status'] == "success") {
                        $filename_single = $response['filename'];
                        $save = $this->db->table('tbl_user')->where('user_id', $user_id)->update(array('image' => $filename_single));
						if ($save) {
							$response['status'] = 'success';
							$response['message'] = 'Files uploaded successfully.';
						} else {
							$response['status'] = 'error';
							$response['message'] = 'cannot update profile image';
						}
            } else {
						$response['status'] = 'error';
						$response['message'] = 'File cannot be uploaded.';
			}
            if($role == 0){
                if($this->ifempty($position, "Position")!== true){
                $response['message'] = $this->ifempty($position, "Position");
                $this->sendResponse($response);
                }
                if($this->ifexists('tbl_position', $position, 'p_id') != true)
                {
                    $response['message'] = "Please enter valid position.";
                    $this->sendResponse($response);
                }
            }
            $insertdata = array( 
                "first_name"   =>   $first_name,
                "last_name"    =>   $last_name,
                "address"      =>   $address,
                "nationality"  =>   $nationality,
                "age"          =>   $this->get_date($dob),
                "gender"       =>   $gender,
                "user_type"    =>   $role,
                "height"       =>   $height,
                "weight"       =>   $weight 
            );
            if($role == 0){
                $insertdata['position'] = $position;
            }
            $result = $this->db->table('tbl_user')->where('user_id', $user_id)->update($insertdata);
            if(!empty($result))
            {
                $user_login = array(
                    "email"      =>   $email_id 
                );
                $result = $this->db->table('tbl_user_login')->where('user_id', $user_id)->update($user_login);
                if(!empty($result)){
                    $response['status'] = "success";
                    $response['message'] = 'Successfully updated details.';
                    $this->sendResponse($response);
                }
                else{
                    $response['message'] = 'Not able to update.';
                    $this->sendResponse($response);
                }
            }
            else{
                 $response['message'] = 'Not able to registered.';
                 $this->sendResponse($response);
            }
           
        } 
    }
    /**
     * Get-Club-Team-List
     * @endpoint get-club-teams-list
     * @url: http://yourdomain.com/api/get-club-teams-list
     */
    public function get_club_team_list(){

    }
	/**
     * user season info
     * @endpoint user-season-detail
     * @url: http://yourdomain.com/api/user-season-detail
	 * @param user_id : user_id (Required)
	 * @param year : year
	 * @param tournament (leage) : tournament 
     */
	public function user_season_info()
    {
         if($this->authenticate_api())
        {   
			$result_data['coach'] = (object)array();
			$result_data['player'] = (object)array();
            $response = array( "status" => "error" );
            $required_fields = array("user_id");
            $status = $this->verifyRequiredParams($required_fields);
            $user_id = $this->request->getVar("user_id");
			$year_list = [];            
           
            if($this->ifempty($user_id, "User id")!== true){
                $response['message'] = $this->ifempty($user_id, "User id");
                $this->sendResponse($response);
            }
			
			$check_player_coach = $this->db->table('tbl_user_login')->select('tbl_user.user_type' )->join('tbl_user', 'tbl_user.user_id = tbl_user_login.user_id')->where(array('tbl_user.user_id' => trim($user_id)))->get()->getRowArray();
			
			$get_data['tournament_list'] = $this->db->table('tbl_tournament')->get()->getResultArray();
			
			if($check_player_coach['user_type'] == 0){							
				// for player
				$year_list = $this->year_list_of_match_and_player("player_id", $user_id);
			}else{
				// for Coach
				$coach_team_id_array = $this->db->table('tbl_team_member_relation')->select('team_id')->where("user_id", $user_id)->distinct('team_id')->get()->getResultArray();
				$coach_team_id_array = array_column($coach_team_id_array, 'team_id');
				
				$query_year_list_get = $this->db->table('tbl_match_team');
				$query_year_list_get = $query_year_list_get->select('DISTINCT YEAR(datetime) as year');
				$query_year_list_get = $query_year_list_get->join('tbl_tournament_match', 'tbl_tournament_match.id = tbl_match_team.match_id','left');
				$query_year_list_get = $query_year_list_get->whereIn('team_id',$coach_team_id_array);				
				$query_year_list_get = $query_year_list_get->get()->getResultArray();
				if($query_year_list_get)
					$year_list = $query_year_list_get;
			}
			$year_list = array_column($year_list, 'year');
			$get_data['year_list'] = $year_list;
			
            $user_details = $this->db->table('tbl_user_login')
                                ->join('tbl_user', 'tbl_user.user_id = tbl_user_login.user_id')
                                ->where(array('tbl_user.user_id' => trim($user_id)));
            if ( $user_details->countAllResults() > 0)
            {				
                $result = $this->db->table('tbl_user_login')->select('first_name, last_name, address, tbl_nationality.nationality as nationality,tbl_nationality.id as nation_id, flag_image, age, position, gender,image, email, height, weight, tbl_user.user_type' )
                                        ->join('tbl_user', 'tbl_user.user_id = tbl_user_login.user_id')
                                        ->join('tbl_nationality', 'tbl_nationality.id = tbl_user.nationality')                        
                                        ->where(array('tbl_user.user_id' => trim($user_id)))->get()->getRowArray();                                  
                
				if(!empty($result)){
                   
					$year_input = $this->request->getVar("year");
					$tournament_input = $this->request->getVar("tournament");
					$response1 = [];
					$response1['data'] = $result_data;
					if($year_input && $tournament_input && $year_input !='' && $tournament_input !=''){
						$result_data = [];
						if($result['user_type'] == 0){
							
							// for player
							
							// $result_data['season'] = "player season";
							$player_id = $user_id;
							$where['YEAR(datetime)'] = $year_input;
							$where['player_id'] = $player_id;
							$where['tournament_id'] = $tournament_input;
							
							$match_id_list = $this->db->table('tbl_match_team')->select('match_id')->join('tbl_tournament_match', 'tbl_tournament_match.id = tbl_match_team.match_id','left')->where($where)->distinct('match_id')->get()->getResultArray();
							$match_id_list = array_column($match_id_list, 'match_id');
							
							
							$query_check = $this->db->table('tbl_match_team');
							$query_check = $query_check->select('YEAR(datetime) as year, SUM(g) as total_g, SUM(a) as total_a, SUM(yc) as total_yc, SUM(rc) as total_rc, tbl_tournament.name');
							$query_check = $query_check->join('tbl_tournament_match', 'tbl_tournament_match.id = tbl_match_team.match_id','left');
										 
							// $query = $query->join('tbl_team', 'tbl_team.team_id = tbl_tournament_match.team_id','left');
										
							$query_check = $query_check->join('tbl_tournament', 'tbl_tournament.id = tbl_tournament_match.tournament_id','left');
										
							$query_check = $query_check->where($where);										
							$data2 = [];
							
							if($query_check->countAllResults() > 0 ){
							
								$query = $this->db->table('tbl_match_team');
								$query = $query->select('YEAR(datetime) as year, SUM(g) as total_g, SUM(a) as total_a, SUM(yc) as total_yc, SUM(rc) as total_rc, tbl_tournament.name as tournament_name, tbl_tournament.id as tournament');
								$query = $query->join('tbl_tournament_match', 'tbl_tournament_match.id = tbl_match_team.match_id','left');
											 
								// $query = $query->join('tbl_team', 'tbl_team.team_id = tbl_tournament_match.team_id','left');
											
								$query = $query->join('tbl_tournament', 'tbl_tournament.id = tbl_tournament_match.tournament_id','left');
											
								$query = $query->where($where);
											
								$query = $query->get()->getResultArray();
								
								$where1['YEAR(datetime)'] = $year_input;
								$where1['tournament_id'] = $tournament_input;
								
								$query_match_result = $this->db->table('tbl_tournament_match_result');
								$query_match_result = $query_match_result->select('tbl_tournament_match_result.id, YEAR(datetime) as year, tbl_tournament_match_result.*');
								$query_match_result = $query_match_result->join('tbl_tournament_match', 'tbl_tournament_match.id = tbl_tournament_match_result.match_id','left');
								 
								// $query_match_result = $query_match_result->join('tbl_team', 'tbl_team.team_id = tbl_tournament_match.team_id','left');
								$query_match_result = $query_match_result->where($where1);							
								$query_match_result = $query_match_result->get()->getResultArray();					
								// $query['0']['total_matchs'] = 0;
								$i=0;
								
								foreach($query_match_result as $data_result){
									$i++;									
								}
								$data2_total_matchs = $i;
								$data2['total_matchs'] = $i;
								
								if($i > 0 && !empty($match_id_list)){
									$query_sub = $this->db->table('tbl_tournament_match_result');
									$query_sub = $query_sub->join('tbl_tournament_match', 'tbl_tournament_match.id = tbl_tournament_match_result.match_id','left');
									$query_sub = $query_sub->whereIn('tbl_tournament_match_result.match_id', $match_id_list);
									$query_sub = $query_sub->get()->getResultArray();
									$draw = $win = $lose = $total_game = 0;
									
									foreach($query_sub as $data_sub){										
										if($data_sub['winner_team_id'] == 0){
											$draw++;
											$total_game++;
										}else if($data_sub['winner_team_id'] == $data_sub['team_id']){
											$win++;
											$total_game++;
										}else{
											$lose++;
											$total_game++;
										}
									}
									
									$query['0']['total_matchs'] = count($match_id_list);
									$query['0']['total_matchs_play'] = $data2_total_matchs;
									$query['0']['draw'] = $draw;
									$query['0']['win'] = $win;
									$query['0']['lose'] = $lose;
									$query['0']['win_per'] = ($query['0']['win']*100)/$data2_total_matchs;
									$data2 = $query;
									$data2['total_matchs'] = $data2_total_matchs;
								}	
								
									
							}
							if(isset($data2["0"])){
								$result_data['player'] = $data2["0"];
								$result_data['coach'] = (object)array();
							}else{
								$result_data['coach'] = (object)array();
								$result_data['player'] = (object)array();
							}
							
						}else{
							
							// for Coach
							
							// $result_data['season'] = "Coach season";
							$data2 = [];
							
							$coach_id = $user_id;
							$where['YEAR(datetime)'] = $year_input;
							$where['user_id'] = $user_id;
							$where['tournament_id'] = $tournament_input;							
							
							$player_team_id_array = $this->db->table('tbl_team_member_relation')->select('team_id')->where("user_id", $coach_id)->distinct('team_id')->get()->getResultArray();
							$player_team_id_array = array_column($player_team_id_array, 'team_id');
							
							$where1['YEAR(datetime)'] = $year_input;
							// $where['user_id'] = $user_id;
							$where1['tournament_id'] = $tournament_input;
							
							/* ----------------------------team goal yallow ----------------------- */
								$query_goal_score_check = $this->db->table('tbl_match_team');
								$query_goal_score_check = $query_goal_score_check->select('YEAR(datetime) as year, SUM(g) as total_g, SUM(a) as total_a, SUM(yc) as total_yc, SUM(rc) as total_rc, tbl_tournament.name as tournament_name, tbl_tournament.id as tournament');
								$query_goal_score_check = $query_goal_score_check->join('tbl_tournament_match', 'tbl_tournament_match.id = tbl_match_team.match_id','left');
											 
								// $query = $query->join('tbl_team', 'tbl_team.team_id = tbl_tournament_match.team_id','left');
											
								$query_goal_score_check = $query_goal_score_check->join('tbl_tournament', 'tbl_tournament.id = tbl_tournament_match.tournament_id','left');
											
								$query_goal_score_check = $query_goal_score_check->where($where1);
								
								$query_goal_score_check = $query_goal_score_check->whereIn('tbl_tournament_match.team_id',$player_team_id_array);
								
								if($query_goal_score_check->countAllResults() > 0){
								
									$query_goal_score = $this->db->table('tbl_match_team');
									$query_goal_score = $query_goal_score->select('YEAR(datetime) as year, SUM(g) as total_g, SUM(a) as total_a, SUM(yc) as total_yc, SUM(rc) as total_rc, tbl_tournament.name as tournament_name, tbl_tournament.id as tournament');
									$query_goal_score = $query_goal_score->join('tbl_tournament_match', 'tbl_tournament_match.id = tbl_match_team.match_id','left');
												 
									// $query = $query->join('tbl_team', 'tbl_team.team_id = tbl_tournament_match.team_id','left');
												
									$query_goal_score = $query_goal_score->join('tbl_tournament', 'tbl_tournament.id = tbl_tournament_match.tournament_id','left');
												
									$query_goal_score = $query_goal_score->where($where1);
									
									$query_goal_score = $query_goal_score->whereIn('tbl_tournament_match.team_id',$player_team_id_array);
												
									$query_goal_score = $query_goal_score->get()->getResultArray();
									// $query_goal_score = $query_goal_score->get()->getRowArray();
									
									$query = $query_goal_score;
								
								}
							
							/* ----------------------------team goal yellow ----------------------- */
							/* ----------------------------result score win ----------------------- */
							$query_check = $this->db->table('tbl_tournament_match_result');
							$query_check = $query_check->select('tbl_tournament_match_result.id, YEAR(datetime) as year, tbl_tournament_match_result.*, , tbl_team.team_name, tbl_team.team_id, tbl_tournament_match.tournament_id');
							$query_check = $query_check->join('tbl_tournament_match', 'tbl_tournament_match.id = tbl_tournament_match_result.match_id','left');
							 
							$query_check = $query_check->join('tbl_team', 'tbl_team.team_id = tbl_tournament_match.team_id','left');
							$query_check = $query_check->where($where1);
							$query_check = $query_check->whereIn('tbl_tournament_match.team_id',$player_team_id_array);
							
							// $query_check = $query_check->get()->getResultArray();
							
							if($query_check->countAllResults() > 0){
								
								$query_sub = $this->db->table('tbl_tournament_match_result');
								$query_sub = $query_sub->select('tbl_tournament_match_result.id, YEAR(datetime) as year, tbl_tournament_match_result.*, , tbl_team.team_name, tbl_team.team_id, tbl_tournament_match.tournament_id');
								$query_sub = $query_sub->join('tbl_tournament_match', 'tbl_tournament_match.id = tbl_tournament_match_result.match_id','left');
								 
								$query_sub = $query_sub->join('tbl_team', 'tbl_team.team_id = tbl_tournament_match.team_id','left');
								$query_sub = $query_sub->where($where1);
								$query_sub = $query_sub->whereIn('tbl_tournament_match.team_id',$player_team_id_array);
								
								$query_sub = $query_sub->get()->getResultArray();
								
								$draw = $win = $lose = $total_game = 0;
									
									foreach($query_sub as $data_sub){										
										if($data_sub['winner_team_id'] == 0){
											$draw++;
											$total_game++;
										}else if($data_sub['winner_team_id'] == $data_sub['team_id']){
											$win++;
											$total_game++;
										}else{
											$lose++;
											$total_game++;
										}
									}
									$total_match_for_coach = $this->db->table('tbl_tournament_match')->whereIn('tbl_tournament_match.team_id',$player_team_id_array)->countAllResults();
									$data2_total_matchs = $total_game;
									$query['0']['total_matchs'] = $total_match_for_coach;
									$query['0']['total_matchs_play'] = $data2_total_matchs;
									// $query['0']['game'] = $data2_total_matchs;
									$query['0']['draw'] = $draw;
									$query['0']['win'] = $win;
									$query['0']['lose'] = $lose;
									$query['0']['win_per'] = ($query['0']['win']*100)/$data2_total_matchs;
									$data2 = $query;
									$data2['total_matchs'] = $data2_total_matchs;					
							// $data2["0"]['total_g'] = null;	
							// $data2["0"]['total_a'] = null;	
							// $data2["0"]['total_yc'] = null;	
							// $data2["0"]['total_rc'] = null;
							unset($data2["0"]['total_g']);	
							unset($data2["0"]['total_a']);	
							unset($data2["0"]['total_yc']);	
							unset($data2["0"]['total_rc']);	
							}
							/* ----------------------------result score win ----------------------- */
							
							if(isset($data2["0"])){
								$result_data['coach'] = $data2["0"];
								$result_data['player'] = (object)array();
							}else{
								$result_data['coach'] = (object)array();
								$result_data['player'] = (object)array();
							}
						}					
							
						$response1['data'] = $result_data;
					}
					
                    $response['status'] = "success";
                    $response['message'] = 'Successfully retrived data.';
                    $response['get_data'] = $get_data;
					// $response['data'] = (object)array();
					// if($year_input && $tournament_input && $year_input !='' && $tournament_input !='')
					$response['data'] = $response1['data'];
                    $this->sendResponse($response);
                }
                else{
                    $response['message'] = "Something went wrong.";
                    $this->sendResponse($response);
                }

            } else {
                $response['message'] = "Please insert valid user id.";
                $this->sendResponse($response);
            }
             
        }
    }
}
