<?php

namespace App\Controllers;
use App\Models\Admin_model;
class Home extends ApiBaseController
{
	/**
	 * Load login function
	 *
	 * @return view
	 */
	public function index()
	{
		return view('login');
	}
	/**
	 * Autentication callback
	 *
	 * @return json
	 */
	public function autenticate()
	{
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
    
        helper(['form', 'url']);
		$validation=array(
			
			"email"=>array(
				"label"=>"Email Id",
				"rules"=>'required'
			),
			"password"=>array(
				"label"=>"Password",
				"rules"=>'required'
			)

		);
	
        if ($this->validate($validation)) {
            $error = null;
			$admin_model = new Admin_model();
            if($admin_model->authenticate($email, md5($password)) == true) {
				$message = "Successfully logged in";
				$this->session->set('admin_email', $email);
				echo $this->sendResponse(array('success' => true, 'message' => $message,'error'=>$error));
			}
			else{
				$message = "Please input valid cridentails";
				echo $this->sendResponse(array('success' => false, 'message' => $message,'error'=>$error));
			}
           
        }else{
            echo $this->sendResponse(array('success' => false, 'error'=>$this->validation->listErrors()));
        }
	}
	public function logout(){
		$session = session();
        $session->destroy();
        return redirect()->to('/');
	}
	/**
	 * Load register function
	 *
	 * @return view
	 */
	public function register()
	{
		return view('register');
	}
	/**
	 * register admin function ajax callback
	 *
	 * @return json
	 */
	public function registeradmin()
	{
		$first_name = $this->request->getPost('frist_name');
        $last_name = $this->request->getPost('last_name');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
    
        helper(['form', 'url']);
		$validation=array(
			"frist_name"=>array(
				"label"=>"First Name",
				"rules"=> 'required|alpha_space'
			),
			"last_name"=>array(
				"label"=>"last name",
				"rules"=>'required|alpha_space'
			),
			"email"=>array(
				"label"=>"Email Id",
				"rules"=>'required|is_unique[tbl_admin.email]'
			),
			"password"=>array(
				"label"=>"Password",
				"rules"=>'required|min_length[8]|matches[password-confirm]'
			)

		);
	
        if ($this->validate($validation)) {

            $data = array(
                'first_name'    => $first_name,
                'last_name'     => $last_name,
                'email'			=> $email,
                'password'		=> md5($password)
            );
			//print_r($data);
            $error = null;
			$admin_model = new Admin_model();
            $id = $admin_model->insert($data);
            echo $this->sendResponse(array('success' => true, 'id'=>$id, 'error'=>$error));
        }else{
            echo $this->sendResponse(array('success' => false, 'error'=>$this->validation->listErrors()));
        }
	}
	public function forgot()
	{		
        return view('forgot-password');
	}
	/** For api
	* Forgot Password 
	* @endpoint forgot-password
	* @url: http://yourdomain.com/api/forgot password
	* @param email : email
	* @param user: user (for Player : user, for coach : user) Both are user
	*/
	/** For admin
	*  Admin Panal
	* @param email : email
	*/
	public function forgot_password($d='')
	{
        // $email = $this->request->getPost('email');
        $email = $this->request->getVar('email');
        $user = $this->request->getVar('user');
		$email =trim($email);
		$user = trim($user);
       
        helper(['form', 'url']);
		$validation=array(
			
			"email"=>array(	
				// "label"=>"email",
				"rules"=>'required'
			)
		);
	
        if ($this->validate($validation)) {
            $error = null;
			$admin_model = new Admin_model();
            if($admin_model->eamil_exist_admin($email, $user) == true) {
				$email_id = $email;
				$email_send = \Config\Services::email();	
				
				$message = '<a href="'.base_url().'/admin/auth-reset-password?email='.$email.'" > Reset Password </a>';
				if($user){
					$data_update['token'] = md5($email.date("Y-m-d H:i:s"));
					$token_update = $this->db->table('tbl_user_login')->where("email", $email)->set($data_update)->update();
					if($token_update)
						$message = 'Token : '.$data_update['token'];
				}
				$email = \Config\Services::email();
				$email->setFrom('tester123456test123456@gmail.com', 'Tester');
				// $email->setTo('Vishal.Patel@potenzaglobalsolutions.com');
				$email->setTo($email_id);
				$email->setSubject('Forgot Password');
				$email->setMessage($message);//your message here  
				
				$email->send();				

				$response['message'] = "Please check your email for reset password";
				
				
				// $message = $email->printDebugger(['headers']);
				echo $this->sendResponse(array('status' => "success", 'message' => $response['message']));
			}
			else{
				$message = "email dosen't exist";
				echo $this->sendResponse(array('status' => "error", 'message' => $message));
			}
           
        }else{
			if($user && !empty($user)){
				echo $this->sendResponse(array('status' => "error", 'message'=>''));
			}
            echo $this->sendResponse(array('status' => "error", 'message'=>$this->validation->listErrors()));
        }
	}
	public function reset_password(){
		return view('reset-password');
	}
}
