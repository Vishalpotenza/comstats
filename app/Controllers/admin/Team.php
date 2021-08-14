<?php

namespace App\Controllers\Admin;
use App\Controllers\ApiBaseController;
use App\Models\Admin_model;
use App\Models\Team_model;
use App\Models\Club_model;

class Team extends ApiBaseController
{
	/**
	 * Load Team Page
	 *
	 * @return view
	 */
	public function index()
	{
		$team_model = new Team_model();
		$club_model = new Club_model();
		$view['view'] = array('title'=>'team Details');
        $view['content'] = '/team/index';
		$view['data'] = $team_model->getallteam();
		$view['club_list'] = $club_model->getclubslist();
		return view('default', $view);
	}
	/**
	 * Add Team callbacks
	 * @return  json
	 */
	public function add_team()
	{
		$team_model = new Team_model();
		$club_id = $this->request->getPost('club_id');       
		$team_logo = $this->request->getFile('team_logo');       
		$team_name = $this->request->getPost('team_name');
		$image_input_feild_name = 'team_logo';
		$profile_images_or_team_images = 'team_images';
		$team_name = trim($team_name);
		helper(['form', 'url']);
		$validation=array(
			"team_name"=>array(
				"label"=>"team_name",
				"rules"=> 'required'
			),
			// "team_logo"=>array(
				// "label"=>"team_logo",
				// "rules"=> 'required'
			// ),
			"club_id"=>array(
				"label"=>"club_id",
				"rules"=> 'required'
			)
		);
		$check_team_exist = $team_model->check_team_exist($team_name);
		if($check_team_exist){
			echo $this->sendResponse(array('success' => false, 'error'=>'Aleredy Exist'));
		}
		$team_logo = 'null';	
		if ($this->validate($validation)) {
			
            $data = array(
				'club_id'    => $club_id,               
				'team_logo'    => $team_name,               
				'team_name'    => $team_name                
            );
			$error = null;
			$team_id = $team_model->insert($data);
			
			if($team_id){
				$team_logo_upload = $this->uploadFilefunc($image_input_feild_name,'image',$team_id,$profile_images_or_team_images,'team_logo');
				
				if($team_logo_upload && isset($team_logo_upload['filename'])){
					$data_image = array(
						'team_logo'    => $team_logo_upload['filename'],               
					);
					
					$team_model = new Team_model();
					$update = $team_model->where('team_id',$team_id)->set($data_image)->update();
				}
			}
			
            echo $this->sendResponse(array('success' => true, 'id'=>isset($team_id) ? $team_id : '', 'error'=>$error, 'image'=>$team_logo_upload));
        }else{
            echo $this->sendResponse(array('success' => false, 'error'=>$this->validation->listErrors()));
        }
	}
	/**Edit Team callback
	 * @return json
	 */
	public function edit_team()
	{
		$club_id = $this->request->getPost('club_id');       
		$team_logo = $this->request->getFile('team_logo');       
		$team_name = $this->request->getPost('team_name');
		$image_input_feild_name = 'team_logo';
		$profile_images_or_team_images = 'team_images';
		$team_name = trim($team_name);
		$team_id = $this->request->getPost('edit_data_id');
		
        helper(['form', 'url']);
		$validation=array(
			// "team_logo"=>array(
				// "label"=>"team_logo",
				// "rules"=> 'required'
			// ),
			"team_name"=>array(
				"label"=>"team_name",
				"rules"=> 'required'
			)
			
		);
		$team_model = new Team_model();
		$check_team_exist = $team_model->check_team_exist($team_name);
		
		
        if ($this->validate($validation)) {
			$team_logo_upload = $this->uploadFilefunc($image_input_feild_name,'image',$team_id,$profile_images_or_team_images,'team_logo');
			
			$data['team_name'] = $team_name;
			if($team_logo_upload && isset($team_logo_upload['filename']))
				$data['team_logo'] = $team_logo_upload['filename'];
			
			$error = null;
			$update = '';
			if($team_id){
				$team_model = new Team_model();
				$update = $team_model->where('team_id',$team_id)->set($data)->update();
			}			
            echo $this->sendResponse(array('success' => true, 'data'=>$data, 'id'=>$update, 'error'=>$error));
        }else{
            echo $this->sendResponse(array('success' => false, 'error'=>$this->validation->listErrors()));
        }
	}	
	/**
	 * Delete the Team
	 * @param team_id : team_id
	 */
	public function delete_team(){
		$team_id = $this->request->getVar('team_id');
		if(!empty($team_id)){
			$error = null;
			$team_model = new Team_model();
			$team_id = $team_model->where("team_id", $team_id)->set(array('deletestatus'=>1))->update();
			if(!empty($team_id)){
				echo $this->sendResponse(array('success' => true, 'id'=>$team_id, 'error'=>$error));
			}else{
				echo $this->sendResponse(array('success' => false, 'error'=>"Something went wrong!"));
			}
		}
	}
	/**
	 * Get Team details
	 * @param team_id : team_id
	 */
	public function get_team_details(){
		$team_id = $this->request->getVar('team_id');
		if(!empty($team_id)){
			$error = null;
			$team_model = new Team_model();
			$result = $team_model->where("team_id", $team_id)->first();
			if(!empty($result)){
				echo $this->sendResponse($result);
			}else{
				echo $this->sendResponse(array('success' => false, 'error'=>"Something went wrong!"));
			}
		}
	}
	

}
