<?php

namespace App\Controllers\Admin;
use App\Controllers\ApiBaseController;
use App\Models\Admin_model;
use App\Models\Firebase_model;
class AdminController extends ApiBaseController
{
	/**
	 * Admin can find all played and upcoming matches
	 *
	 * @return view
	 */
	public function profile()
	{
		if(! session()->get('logged_in')){
			return redirect()->to('/'); 
		}	
		$id = session()->get('id');
		// echo "id = ".$id;
		// die();
		$admin_data = $this->db->table('tbl_admin')->where('id', $id)->get()->getRowArray();
		$admin_data['firebase_server_key'] = $this->db->table('tbl_credential')->where('credential_key', 'firebase_server_key')->get()->getRowArray()['credential_value'];
		$view['title'] = "Admin Profile";
		$view['view'] = array('title'=>'team Details');
        $view['content'] = '/admin/profile';
		$view['data'] = array('admin_data'=>$admin_data);
		return view('default', $view);
	}
	public function edit_admin()
	{
		if(! session()->get('logged_in')){
			echo $this->sendResponse(array('success' => false, 'error'=>"please login first"));
		}
		$first_name = $this->request->getPost('first_name');
        $last_name = $this->request->getPost('last_name');
        $email = $this->request->getPost('email');
        $image = $this->request->getPost('image');
		$id = session()->get('id');
		helper(['form', 'url']);
		$image = '';
		$image_input_feild_name = 'image';
		$profile_images_or_team_images = 'admin';
		$admin_image = $this->uploadFilefunc($image_input_feild_name,'image',$id,$profile_images_or_team_images,'admin');
		if($admin_image){
			if($admin_image['status'] != 'error')
				$image = $admin_image['filename'];				
		}
		
		$data['first_name'] = $first_name;
		$data['last_name'] = $last_name;
		if($image != ''){
			$data['image'] = $image;
		}
		$error = null;
		if(!empty($data)){
			if($id){
				$firebase_server_key = $this->request->getPost('firebase_server_key');
				$data_server_key = array(
					'credential_value'    => $firebase_server_key,               
				);
				$update_server_key = $this->db->table('tbl_credential')->where('credential_key', 'firebase_server_key')->set($data_server_key)->update();
				$update = $this->db->table('tbl_admin')->where("id", $id)->set($data)->update();
				
			}
		}
		echo $this->sendResponse(array('success' => true, 'id'=>(isset($update) ? $update : ""), 'error'=>$error));
        
	}
	public function pass()
	{
		if(! session()->get('logged_in')){
			return redirect()->to('/'); 
		}	
		$view['title'] = "Change Password";
		$view['view'] = array('title'=>'Update Password');
        $view['content'] = '/admin/updatepassword';
		$view['data'] = array('admin_data'=>'');
		return view('default', $view);
	}
	public function pass_update()
	{
		if(! session()->get('logged_in')){
			echo $this->sendResponse(array('success' => false, 'error'=>"please login first"));
		}
		$error = null;
		helper(['form', 'url']);
		$validation=array(			
			"old_password"=>array(
				"label"=>"old_password",
				"rules"=>'required'
			),
			"confirm-password"=>array(
				"label"=>"Password",
				"rules"=>'required|min_length[8]'
			),
			"password"=>array(
				"label"=>"Password",
				"rules"=>'required|min_length[8]|matches[confirm-password]'
			)

		);
		if ($this->validate($validation)) {
			$old_pass=$this->request->getPost('old_password');
			$old_pass=md5($old_pass);
			$new_pass=$this->request->getPost('password');
			$confirm_pass=$this->request->getPost('confirm-password');
			$id = session()->get('id');
			$email = session()->get('email');
			$query = $this->db->table('tbl_admin')->where(array("email" => $email, "password" => $old_pass));
			// $que=$this->db->query("select * from user_login where id='$session_id'");
			// $row=$que->row();
			if ($query->countAllResults() > 0){
				$data_update['password'] = md5($new_pass);
				$update = $this->db->table('tbl_admin')->where("id", $id)->set($data_update)->update();
				$message = "Somthing wrong";
				if($update){
					$message = "Password changed successfully !";
				}
				echo $this->sendResponse(array('success' => true, 'message' => $message,'error'=>$error));
			}
			else{
				$message = "Please Enter valid old Password";
				$error = "Please Enter valid old Password";
				echo $this->sendResponse(array('success' => false, 'message' => $message,'error'=>$error));
			}
			
		}else{
		
			echo $this->sendResponse(array('success' => false, 'error'=>$this->validation->listErrors()));
		
		}
		
        
	}
	public function firebase(){
		if(! session()->get('logged_in')){
			return redirect()->to('/'); 
		}
		$firebase = new Firebase_model();
		$view['title'] = "Firebase";
		$view['view'] = array('title'=>'team Details');
        $view['content'] = '/firebase/index';
		$view['data'] = array('firebases'=>$firebase->getallfirebase());
		return view('default', $view); 
	}
	/**
	 * Add Team callbacks
	 * @return  json
	 */
	public function add_firebase()
	{
		if(! session()->get('logged_in')){
			echo $this->sendResponse(array('success' => false, 'error'=>"please login first"));
		}
		$firebase = new Firebase_model();
		$f_key = $this->request->getPost('f_key');       
		$f_value = $this->request->getPost('f_value');       
		
		helper(['form', 'url']);
		$validation=array(
			"f_key"=>array(
				"label"=>"f_key",
				"rules"=> 'required'
			),
			"f_value"=>array(
				"label"=>"f_value",
				"rules"=> 'required'
			)
		);
		$check_team_exist = $firebase->check_firebase_exist($f_key);
		if($check_team_exist){
			echo $this->sendResponse(array('success' => false, 'error'=>'Aleredy Exist'));
		}
		$team_logo = 'null';	
		if ($this->validate($validation)) {
			
            $data = array(
				'f_key'    => $f_key,               
				'f_value'    => $f_value,               
			);
			// echo "<pre>";
			// print_r($data);
			$error = null;
			$insert_id = $firebase->insert($data);		
            echo $this->sendResponse(array('success' => true, 'id'=>isset($insert_id) ? $insert_id : ''));
        }else{
            echo $this->sendResponse(array('success' => false, 'error'=>$this->validation->listErrors()));
        }
	}
	/**
	 * Delete the Firebase
	 * @param id : id
	 */
	public function delete_firebase(){
		
		if(! session()->get('logged_in')){
			echo $this->sendResponse(array('success' => false, 'error'=>"please login first"));
		}
		
		$id = $this->request->getVar('id');
		
		if(!empty($id)){
			$error = null;
			$firebase = new Firebase_model();
			$id = $firebase->where("id", $id)->set(array('deletestatus'=>1))->update();
			if(!empty($id)){
				echo $this->sendResponse(array('success' => true, 'id'=>$id, 'error'=>$error));
			}else{
				echo $this->sendResponse(array('success' => false, 'error'=>"Something went wrong!"));
			}
		}
		echo $this->sendResponse(array('success' => false, 'error'=>"Something went wrong!"));
	}
	/**
	 * Get Firebase details
	 * @param id : id
	 */
	public function get_firebase_details(){
		if(! session()->get('logged_in')){
			echo $this->sendResponse(array('success' => false, 'error'=>"please login first"));
		}
		$id = $this->request->getVar('id');
		if(!empty($id)){
			$error = null;
			$firebase = new Firebase_model();
			$result = $firebase->where("id", $id)->first();
			if(!empty($result)){
				echo $this->sendResponse($result);
			}else{
				echo $this->sendResponse(array('success' => false, 'error'=>"Something went wrong!"));
			}
		}
		echo $this->sendResponse(array('success' => false, 'error'=>"Something went wrong!"));
	}
	/**Edit firebase callback
	 * @return json
	 */
	public function edit_firebase()
	{
		if(! session()->get('logged_in')){
			echo $this->sendResponse(array('success' => false, 'error'=>"please login first"));
		}
		$id = $this->request->getPost('edit_data_id');       
		$f_key = $this->request->getPost('f_key');       
		$f_value = $this->request->getPost('f_value');       
		
		helper(['form', 'url']);
		$validation=array(
			"f_key"=>array(
				"label"=>"f_key",
				"rules"=> 'required'
			),
			"f_value"=>array(
				"label"=>"f_value",
				"rules"=> 'required'
			)
		);
		
		if ($this->validate($validation)) {
			$error = null;
			$firebase = new Firebase_model();
			$data = array(
				'f_key'    => $f_key,               
				'f_value'    => $f_value,               
			);
			$update='';
			if($id){
				$update = $firebase->where('id',$id)->set($data)->update();
			}			
            echo $this->sendResponse(array('success' => true, 'data'=>$data, 'id1'=>$id, 'id'=>$update, 'error'=>$error));
        }else{
            echo $this->sendResponse(array('success' => false, 'error'=>$this->validation->listErrors()));
        }
	}
	/**
	 * Get Firebase details (admin setting)
	 * @param id : id
	 */
	public function get_firebase_details1(){
		if(! session()->get('logged_in')){
			echo $this->sendResponse(array('success' => false, 'error'=>"please login first"));
		}
		$error = null;
		$firebase = new Firebase_model();
		// $result = $firebase->get()->getRowArray();
		$result = $this->db->table('tbl_credential')->where('credential_key', 'firebase_server_key')->get()->getRowArray();
		if(!empty($result)){
			echo $this->sendResponse($result);
		}else{
			echo $this->sendResponse(array('success' => false, 'error'=>"Something went wrong!"));
		}
		
	}
	/**Edit firebase callback
	 * @return json
	 */
	public function edit_firebase1()
	{
		if(! session()->get('logged_in')){
			echo $this->sendResponse(array('success' => false, 'error'=>"please login first"));
		}
		$firebase_server_key = $this->request->getPost('firebase_server_key');       
		helper(['form', 'url']);
		$validation=array(
			"firebase_server_key"=>array(
				"rules"=> 'required'
			)
		);
		
		if ($this->validate($validation)) {
			$error = null;
			$data = array(
				'credential_value'    => $firebase_server_key,               
			);
			$update = $this->db->table('tbl_credential')->where('credential_key', 'firebase_server_key')->set($data)->update();
						
            echo $this->sendResponse(array('success' => true, 'id'=>$update, 'error'=>$error));
        }else{
            echo $this->sendResponse(array('success' => false, 'error'=>$this->validation->listErrors()));
        }
	}
	/** For api
	* Forgot Password Update 
	* @endpoint forgot-password-update
	* @url: http://yourdomain.com/api/forgot-password-update
	* @param email : email
	* @param password: password
	* @param confirm-password: confirm-password
	* @param token: token
	* @param user: user (for Player : user, for coach : user) Both are user
	*/
	/** For admin
	*  Admin Panal
	* @param email : email
	* @param password: password
	* @param confirm-password: confirm-password
	*/
	public function forgot_pass_update()
	{
		$error = null;
		helper(['form', 'url']);
		$user=$this->request->getPost('user');
		$validation=array(			
			"email"=>array(
				"label"=>"Email",
				"rules"=>'required'
			),
			"confirm-password"=>array(
				"label"=>"Password",
				"rules"=>'required|min_length[8]'
			),
			"password"=>array(
				"label"=>"Password",
				"rules"=>'required|min_length[8]|matches[confirm-password]'
			)

		);
		if ($this->validate($validation)) {
			
			$email=$this->request->getPost('email');
			$new_pass=$this->request->getPost('password');
			$confirm_pass=$this->request->getPost('confirm-password');
			
			$table = 'tbl_admin';
			if($user == 'user'){
				$table = 'tbl_user_login';
			}
			
			$query = $this->db->table($table)->where(array("email" => $email));
			
			if ($query->countAllResults() > 0){
				$data_update['password'] = md5($new_pass);
				$where['email'] = $email;
				if($user == 'user'){
					$token=$this->request->getPost('token');
					if($token && !empty($token)){
						$where['token'] = $token;
						$where_check['token'] = $token;
						$where_check['email'] = $email;
					}else{
						echo $this->sendResponse(array('status' => 'error', 'message' => "Somthing Wrong ! token"));
					}
					$query_check = $this->db->table($table)->where($where_check);
					if ($query_check->countAllResults() < 1){
						echo $this->sendResponse(array('status' => 'error', 'message' => "Not authorised"));
					}
					
				}
				$update = $this->db->table($table)->where($where)->set($data_update)->update();
				$message = "Somthing wrong";
				if($update){
					$message = "Password changed successfully";
				}
				echo $this->sendResponse(array('status' => 'success', 'message' => $message));
			}
			else{
				
				$message = "email dosen't exist";
				echo $this->sendResponse(array('status' => 'error', 'message' => $message));
			}
			
		}else{
			if($user && !empty($user)){
				echo $this->sendResponse(array('status' => "error", 'message'=>''));
			}
		
			echo $this->sendResponse(array('status' => 'error', 'message'=>$this->validation->listErrors()));
		
		}
		
        
	}

}
