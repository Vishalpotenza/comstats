<?php

namespace App\Controllers\Admin;
use App\Controllers\ApiBaseController;
use App\Models\Admin_model;
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
			if($id)
				$update = $this->db->table('tbl_admin')->where("id", $id)->set($data)->update();
		}
		echo $this->sendResponse(array('success' => true, 'id'=>(isset($update) ? $update : ""), 'error'=>$error));
        
	}
	public function pass()
	{
		if(! session()->get('logged_in')){
			return redirect()->to('/'); 
		}	
		
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

}
