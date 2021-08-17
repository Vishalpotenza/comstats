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
	public function club_team($club_id=''){
		$team_model = new Team_model();
		$view['view'] = array('title'=>"Team List");
		$view['content'] = "match/team";
		$club_id = $this->request->getVar('club_id');
		
		$where_club_member_team['tbl_team.deletestatus'] = 0;
		$where_club_member_team['tbl_team.club_id'] = $club_id;
		$club_member_team = $this->db->table('tbl_team')->where($where_club_member_team)->get()->getResultArray();
		$result["club_member_team"] = $club_member_team;		
		// echo "<pre>";
		// print_r($result);
		// die();
		$view['data'] = array("data" => $result);
		return view('default', $view);
		
	}
	public function team_match($team_id=''){
		
		$team_model = new Team_model();
		$view['view'] = array('title'=>"Match List");
		$view['content'] = "match/tournaments";
		$team_id = $this->request->getVar('team_id');
		
		$team_match = '';
		$where_team_match1['deletestatus'] = 0;
		$where_team_match['team_id'] = $team_id;
		$team_match = $this->db->table('tbl_tournament_match')->where($where_team_match)->get()->getResultArray();
		
		$where_team_match1['designation <>'] = 1; 
		
		$team_coach_detail = $this->db->table('tbl_team_member_relation')->join('tbl_user','tbl_user.user_id = tbl_team_member_relation.user_id')->where($where_team_match1)->get()->getRowArray();
		
		
		
		
		$result["team_matchs"] = $team_match;
		$team_match_id_column = array_column($team_match,'id');
		// $result["team_match_column"] = array_column($team_match,'id');
		$team_match_id_column_where['match_id'] = $team_match_id_column;
		$player_list = $this->db->table('tbl_match_team')->select('id, player_id, jursey_no')->whereIn('match_id',$team_match_id_column)->get()->getResultArray();
		foreach($player_list as $key => $value){
			$whare = array('player_id' => $value['player_id']);
			$g_a_yc_rc = $this->db->table('tbl_match_team')->select('SUM(g) as total_g, SUM(a) as total_a, SUM(yc) as total_yc, SUM(rc) as total_rc')->where($whare);
			$g_a_yc_rc = $g_a_yc_rc->get()->getRowArray();
						
			$player_list[$key]['total_yc'] = $g_a_yc_rc['total_yc'] ? $g_a_yc_rc['total_yc'] : 0;
			$player_list[$key]['total_rc'] = $g_a_yc_rc['total_rc'] ? $g_a_yc_rc['total_rc'] : 0;
			$player_list[$key]['total_g'] = $g_a_yc_rc['total_g'] ? $g_a_yc_rc['total_g'] : 0;
			$player_list[$key]['total_a'] = $g_a_yc_rc['total_a'] ? $g_a_yc_rc['total_a'] : 0;
		}
		
		
		
		$result["player_list"] = $player_list;
		$result["team_coach_detail"] = $team_coach_detail;
		
		
		
		
		// echo "<pre>";
		// print_r($result); 
		// die();
		$view['data'] = array("data" => $result);
		return view('default', $view);
		
	}
	

}
