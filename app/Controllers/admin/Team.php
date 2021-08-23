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
		if(! session()->get('logged_in')){
			return redirect()->to('/'); 
		}
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
		if(! session()->get('logged_in')){
			return redirect()->to('/'); 
		}
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
		if(! session()->get('logged_in')){
			return redirect()->to('/'); 
		}
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
		if(! session()->get('logged_in')){
			return redirect()->to('/'); 
		}
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
		if(! session()->get('logged_in')){
			return redirect()->to('/'); 
		}
		$team_model = new Team_model();
		$view['view'] = array('title'=>"Team List");
		$view['content'] = "match/team";
		$club_id = $this->request->getVar('club_id');
		
		$where_club_member_team['tbl_team.deletestatus'] = 0;
		$where_club_member_team['tbl_team.club_id'] = $club_id;
		$club_member_team = $this->db->table('tbl_team')->where($where_club_member_team)->get()->getResultArray();
		$result["club_member_team"] = $club_member_team;		
		$view['data'] = array("data" => $result);
		return view('default', $view);
		
	}
	public function team_match($team_id='', $sort_by="all"){
		
	// date_default_timezone_set("Asia/Kolkata");
		
		
		$sort_by = $this->request->getVar('sort_by');
		$team_model = new Team_model();
		$view['view'] = array('title'=>"Match List");
		$view['content'] = "match/tournaments";
		$team_id = $this->request->getVar('team_id');
		$view['data'] = array("data" => array());
		$team_match = '';
		$where_team_match1['deletestatus'] = 0;
		$where_team_match['tbl_tournament_match.team_id'] = $team_id;

		$team_match = $this->db->table('tbl_tournament_match');
		$team_match = $team_match->select('tbl_tournament_match.id, name, datetime, kit_color, team_id, opponent_team_id');
		$team_match = $team_match->join('tbl_tournament','tbl_tournament.id = tbl_tournament_match.tournament_id', 'left');
		$team_match = $team_match->orderBy('datetime','DESC');
		if($sort_by == "past"){
			$where_team_match['datetime <'] = date("Y-m-d H:i:s");
		}else if($sort_by == "upcoming"){
			$where_team_match['datetime >'] = date("Y-m-d H:i:s");
		}else{
		}
		$team_match = $team_match->where($where_team_match);
		$team_match = $team_match->get()->getResultArray();

		$team_match_detail_array = [];
		foreach($team_match as $team_match_data){
			$team_match_detail1 = [];
			$team_match_detail['match_id'] = $team_match_data['id'];
			$team_match_detail['tournament_name'] = $team_match_data['name'];
			$team_match_detail['datetime'] = $team_match_data['datetime'];
			if($team_match_data['datetime'] < date("Y-m-d H:i:s")){
				$team_match_detail['status_time'] = "past";
			}else{
				$team_match_detail['status_time'] = "upcoming";
			}
			
			$team_match_detail['kit_color'] = $team_match_data['kit_color'];
			$team_match_detail['team_id'] = $team_match_data['team_id'];
			$team_match_detail['opponent_team_id'] = $team_match_data['opponent_team_id'];
			$team_id_to_team_name_team_logo_1 = $this->team_id_to_team_name_team_logo($team_match_data['team_id']);
			$team_match_detail['team_name'] = $team_id_to_team_name_team_logo_1['team_name'];
			$team_match_detail['team_logo'] = $team_id_to_team_name_team_logo_1['team_logo'];
			$team_id_to_team_name_team_logo_2 = $this->team_id_to_team_name_team_logo($team_match_data['opponent_team_id']);
			$team_match_detail['opponent_team_name'] = $team_id_to_team_name_team_logo_2['team_name'];
			$team_match_detail['opponent_team_logo'] = $team_id_to_team_name_team_logo_2['team_logo'];
			
			$result_score = $this->match_result($team_match_data['id']);
			if($result_score){
				$team_match_detail1['team_score'] = $result_score['team_id_score'];
				$team_match_detail1['opponent_score'] = $result_score['opponent_team_id_score'];
				$team_match_detail1['winner_team'] = $result_score['winner_team_id'];
				
			}
			$team_match_detail['score'] = $team_match_detail1;
			$team_match_detail_array[] = $team_match_detail;
		}
		
		$where_team_match1['designation <>'] = 1; 
		$where_team_match1['tbl_team_member_relation.deletestatus'] = 0; 
		$team_coach_detail = $this->db->table('tbl_team_member_relation')->join('tbl_user','tbl_user.user_id = tbl_team_member_relation.user_id')->where($where_team_match1)->get()->getResultArray();
		
		
		
		
		$result["team_matchs"] = $team_match_detail_array;
		$team_match_id_column = array_column($team_match,'id');
		
		if(!empty($team_match_id_column)){
			// $result["team_match_column"] = array_column($team_match,'id');
			$team_match_id_column_where['match_id'] = $team_match_id_column;
			$player_list = $this->db->table('tbl_match_team')->select('player_id')->whereIn('match_id',$team_match_id_column)->distinct()->get()->getResultArray();
			if($player_list){
				foreach($player_list as $key => $value){
					$whare = array('player_id' => $value['player_id']);
					$g_a_yc_rc = $this->db->table('tbl_match_team')->select('tbl_position.position as position, player_id, last_name, first_name, image, SUM(g) as total_g, SUM(a) as total_a, SUM(yc) as total_yc, SUM(rc) as total_rc')->join('tbl_user','tbl_user.user_id = tbl_match_team.player_id')->join('tbl_position','tbl_position.p_id = tbl_user.position ','left')->where($whare);
					$g_a_yc_rc = $g_a_yc_rc->get()->getRowArray();
								
					$player_list[$key]['position'] = $g_a_yc_rc['position'] ? $g_a_yc_rc['position'] : '';
					$player_list[$key]['user_id'] = $g_a_yc_rc['player_id'] ? $g_a_yc_rc['player_id'] : '';
					$player_list[$key]['first_name'] = $g_a_yc_rc['first_name'] ? $g_a_yc_rc['first_name'] : '';
					$player_list[$key]['last_name'] = $g_a_yc_rc['last_name'] ? $g_a_yc_rc['last_name'] : '';
					$player_list[$key]['image'] = $g_a_yc_rc['image'] ? $g_a_yc_rc['image'] : '';
					$player_list[$key]['total_yc'] = $g_a_yc_rc['total_yc'] ? $g_a_yc_rc['total_yc'] : 0;
					$player_list[$key]['total_rc'] = $g_a_yc_rc['total_rc'] ? $g_a_yc_rc['total_rc'] : 0;
					$player_list[$key]['total_g'] = $g_a_yc_rc['total_g'] ? $g_a_yc_rc['total_g'] : 0;
					$player_list[$key]['total_a'] = $g_a_yc_rc['total_a'] ? $g_a_yc_rc['total_a'] : 0;
				}
			}
			
			
			$designation['2'] = 'Head Coach';
			$designation['3'] = 'Assistant coach';
			$designation['4'] = 'Manager';
			$result["designation"] = $designation;
			$result["player_list"] = $player_list;
			$result["team_coach_detail"] = $team_coach_detail;
		}	
		$view['data'] = array("data" => $result);
		return view('default', $view);
		
	}
	public function team_id_to_team_name_team_logo($team_id){
		return $this->db->table('tbl_team')->where('team_id',$team_id)->get()->getRowArray();
	}
	public function match_player_score($match_id,$player_id){
		$where['match_id'] = $match_id;
		$where['player_id'] = $player_id;
		return $this->db->table('tbl_match_team')->select('sum(g) as total_g, sum(a) as total_a, sum(yc) as total_yc, sum(rc) as total_rc')->where($where)->get()->getRowArray();
	}
	public function match_player_list($match_id){
		return $this->db->table('tbl_match_team')->where('match_id',$match_id)->get()->getResultArray();
	}
	public function club_id_to_clubdetail($club_id){
		return $this->db->table('tbl_club')->where('club_id',$club_id)->get()->getRowArray();
	}
	public function match_result($match_id){
		return $this->db->table('tbl_tournament_match_result')->where('match_id',$match_id)->get()->getRowArray();
	}
	public function get_user(){
		$user_id = $this->request->getVar('user_id');
		if(!empty($user_id)){
			$error = null;
			$result = $this->db->table('tbl_user')->select('first_name, last_name, address, tbl_nationality.id as nationality_id, tbl_nationality.nationality as nationality, flag_image, age, gender, user_type, tbl_position.position as position, height, weight, image')->join('tbl_nationality', 'tbl_nationality.id = tbl_user.nationality','left')->join('tbl_position', 'tbl_position.p_id = tbl_user.position','left')->where("user_id", $user_id)->get()->getRowArray();
			if(!empty($result)){
				$result_data['user_id'] = $user_id;
				$result_data['first_name'] = $result['first_name'];
				$result_data['last_name'] = $result['last_name'];
				$result_data['address'] = $result['address'];
				$result_data['nationality_id'] = $result['nationality_id'];
				$result_data['nationality'] = $result['nationality'];
				$result_data['flag_image'] = $result['flag_image'];
				$result_data['age'] = $this->calculate_age($result['age']);
				if($result['gender'] == 0){
					$result_data['gender'] = 'Male';
				}else if($result['gender'] == 1){
					$result_data['gender'] = 'Female';
				}else{
					$result_data['gender'] = 'Transgender';	
				}
					
				if($result['user_type'] = '0'){
					$result_data['user'] = "Coach";
					
					
				}else{
					$result_data['user'] = "Player";
					$result_data['position'] = $result['position'];
				}
				$result_data['height'] = $result['height'];
				$result_data['weight'] = $result['weight'];
				$result_data['image'] = $result['image'];
				
				echo $this->sendResponse(array('success' => true, 'result'=>$result_data));
			}else{
				echo $this->sendResponse(array('success' => false, 'error'=>"Something went wrong!"));
			}
		}
		
	}
	public function team_match_detail($match_id = ''){
		$view['view'] = array('title'=>"Match List");
		$view['content'] = "match/match";
		$team_id = $this->request->getVar('team_id');
		$view['data'] = array("data" => array());
		
		$match_id = $this->request->getVar('match_id');
		if($match_id){
			
			$team_match = $this->db->table('tbl_tournament_match');
			// $team_match = $team_match->select('tbl_tournament_match');
			$team_match = $team_match->join('tbl_tournament','tbl_tournament.id = tbl_tournament_match.tournament_id','left');
			$team_match = $team_match->where('tbl_tournament_match.id',$match_id);
			$team_match = $team_match->get()->getRowArray();
			if($team_match){
				$match = [];
				$team = [];
				$opponent_team = [];
				
				$match['datetime'] = $team_match['datetime'];
				$match['tournament'] = $team_match['name'];
				$match['team_id'] = $team_match['team_id'];
				$match['opponent_team_id'] = $team_match['opponent_team_id'];
				$team1 = $this->team_id_to_team_name_team_logo($team_match['team_id']);
				if($team1){
					$match['club_name'] = $this->club_id_to_clubdetail($team1['club_id'])['club_name'];
					$match['team_name'] = $team1['team_name'];
					$match['team_logo'] = $team1['team_logo'];
					
				}				
				$team2 = $this->team_id_to_team_name_team_logo($team_match['opponent_team_id']);
				if($team1){
					$match['opponent_team_name'] = $team2['team_name'];
					$match['opponent_team_logo'] = $team2['team_logo'];
				}
				$result = $this->match_result($match_id);
				if($result){
					$match['team_id_score'] = $result['team_id_score'];
					$match['opponent_team_id_score'] = $result['opponent_team_id_score'];
					$match['winner_team_id'] = $result['winner_team_id'];
				}
				$player_list = $this->match_player_list($match_id);
				if($player_list){
					$player_list_detail = [];
					foreach($player_list as $key => $value){
						$user_info = $this->getUserInfo($value['player_id']);
						$player_list_detail[$key] = $user_info;
						$player_list_detail[$key]['match_id'] = $value['match_id'];
						$player_list_detail[$key]['player_id'] = $value['player_id'];
						$player_list_detail[$key]['jursey_no'] = $value['jursey_no'];
						$player_list_detail[$key]['total_g'] = $value['g'];
						$player_list_detail[$key]['total_a'] = $value['a'];
						$player_list_detail[$key]['total_yc'] = $value['yc'];
						$player_list_detail[$key]['total_rc'] = $value['rc'];		
						
					}
					$match['player_list'] = $player_list_detail;
				}
				
				
				
			}
		}
		$view['data'] = array("data" => $match);
		return view('default', $view);
		// echo "<pre>";
		// print_r($match);
		// die();
	}
	

}
