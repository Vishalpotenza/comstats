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
	
}
