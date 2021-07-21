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
     */
    public function user_login()
    {
        if($this->authenticate_api())
        {   
            $response = array( "status" => "error" );
            $required_fields = array("email", "password");
            $status = $this->verifyRequiredParams($required_fields);
            $email_id = $this->request->getVar("email_id");
            $password = $this->request->getVar("password");
            if($this->ifempty($email_id, "email_id")!== true){
                $response['message'] = $this->ifempty($email_id, "Email id");
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
     * @param user_id : user_id
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
                $result = $this->db->table('tbl_user_login')->select('first_name, last_name, address, tbl_nationality.nationality as nationality,tbl_nationality.id as nation_id, flag_image, age, position, gender,image, email, height, weight' )
                                        ->join('tbl_user', 'tbl_user.user_id = tbl_user_login.user_id')
                                        ->join('tbl_nationality', 'tbl_nationality.id = tbl_user.nationality')                        
                                        ->where(array('tbl_user.user_id' => trim($user_id)))->get()->getRowArray();
                                  
                
               
                if(!empty($result)){
                    $result['dob'] = $this->get_mobile_date($result['age']);
                    $result['age'] = $this->calculate_age($result['age']);
                    $result['total_game'] = 5;
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
   
}
