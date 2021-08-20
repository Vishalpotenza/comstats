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
	public function forgot_password()
	{
        $email = $this->request->getPost('email');
        // $email = $this->request->getVar('email');
       
        helper(['form', 'url']);
		$validation=array(
			
			"email"=>array(	
				"label"=>"email",
				"rules"=>'required'
			)
		);
	
        if ($this->validate($validation)) {
            $error = null;
			$admin_model = new Admin_model();
            if($admin_model->eamil_exist_admin($email) == true) {
				
				$email_send = \Config\Services::email();
				
				// $config['SMTPUser'] = 'Vishal.Patel@potenzaglobalsolutions.com';
				// $config['SMTPPass'] = 'Potenza@123';
				$config['SMTPUser'] = 'tester123456test123456@gmail.com';
				$config['SMTPPass'] = 'tester123456test12345647';
				// $config['SMTPHost'] = 'sendmail';
				// $config['SMTPPort'] = 'sendmail';
				$config['protocol'] = 'sendmail';
$config['mailPath'] = '/usr/sbin/sendmail';
$config['charset']  = 'iso-8859-1';
$config['wordWrap'] = true;

$email_send->initialize($config);

$email_send->setFrom('tester123456test123456@gmail.com', 'tester');
$email_send->setTo($email);
// $email_send->setCC('another@another-example.com');
// $email_send->setBCC('them@their-example.com');

$email_send->setSubject('Email Test');
$email_send->setMessage('Testing the email class.');

$status = $email_send->send();

				$message = "Mail sent";
				echo $this->sendResponse(array('success' => true, 'responce'=>$status, 'message' => $message,'error'=>$error));
			}
			else{
				$message = "Account does not exist".$email."-";
				echo $this->sendResponse(array('success' => false, 'responce'=>1, 'message' => $message,'error'=>$error));
			}
           
        }else{
            echo $this->sendResponse(array('success' => false, 'error'=>$this->validation->listErrors()));
        }
	}
	public function reset_password(){
		
	}
}
