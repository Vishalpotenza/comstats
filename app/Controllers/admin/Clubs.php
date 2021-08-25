<?php

namespace App\Controllers\Admin;
use App\Controllers\ApiBaseController;
use App\Models\Admin_model;
use App\Models\Club_model;use App\Models\Team_model;
class Clubs extends ApiBaseController
{
	/**
	 * Load Dashboard function
	 *
	 * @return view
	 */
	public function index()
	{
		if(! session()->get('logged_in')){
			return redirect()->to('/'); 
		}
		$club_model = new Club_model();
        $view['title'] = "Clubs";
        $view['content'] = "clubs/index";
		$view['data'] = array("club_details" => $club_model->getallclubs());
		return view('default', $view);
	}
	/**
	 * Add club callbacks
	 * @return  json
	 */
	public function add_club()
	{
		if(! session()->get('logged_in')){
			echo $this->sendResponse(array('success' => false, 'error'=>'Please login First'));
		}
		$clubname = $this->request->getPost('clubname');
        $contactno = $this->request->getPost('contactno');
        $inputAddress = $this->request->getPost('inputAddress');
        $inputcountry = $this->request->getPost('inputcountry');
		$inputState = $this->request->getPost('inputState');
		$inputcity = $this->request->getPost('inputcity');
    
        helper(['form', 'url']);
		$validation=array(
			"clubname"=>array(
				"label"=>"clubname",
				"rules"=> 'required|alpha_space'
			),
			"contactno"=>array(
				"label"=>"contactno",
				"rules"=>'required|regex_match[/^[0-9\-\(\)\/\+\s]*$/]'
			),
			"inputAddress"=>array(
				"label"=>"inputAddress",
				"rules"=>'required'
			),
			"inputcountry"=>array(
				"label"=>"inputcountry",
				"rules"=>'required|integer'
			),
			"inputState"=>array(
				"label"=>"inputState",
				"rules"=>'required|integer'
			)
			// "inputcity"=>array(
			// 	"label"=>"inputcity",
			// 	"rules"=>'required|integer'
			// )
		);
	
        if ($this->validate($validation)) {
            $data = array(
				'club_slug'	   => str_replace(' ', '-', strtolower($clubname)),
                'club_name'    => $clubname,
                'address'      => $inputAddress,
                'contact_no'   => $contactno,
                'country_id'   => $inputcountry,
				'state_id'	   => $inputState,
				'city_id'	   => $inputcity
            );
			//print_r($data);
            $error = null;
			$club_model = new Club_model();
            $id = $club_model->insert($data);
            echo $this->sendResponse(array('success' => true, 'id'=>$id, 'error'=>$error));
        }else{
            echo $this->sendResponse(array('success' => false, 'error'=>$this->validation->listErrors()));
        }
	}
	/**Edit club callback
	 * @return json
	 */
	public function edit_club()
	{
		if(! session()->get('logged_in')){
			echo $this->sendResponse(array('success' => false, 'error'=>'Please login First'));
		}
		$clubname = $this->request->getPost('clubname1');
        $contactno = $this->request->getPost('contactno1');
        $inputAddress = $this->request->getPost('inputAddress1');
        $inputcountry = $this->request->getPost('inputcountry1');
		$inputState = $this->request->getPost('inputState1');
		$inputcity = $this->request->getPost('inputcity1');
		$club_id = $this->request->getPost('club_id');
    
        helper(['form', 'url']);
		$validation=array(
			"clubname1"=>array(
				"label"=>"clubname",
				"rules"=> 'required|alpha_space'
			),
			"contactno1"=>array(
				"label"=>"contactno",
				"rules"=>'required|regex_match[/^[0-9\-\(\)\/\+\s]*$/]'
			),
			"inputAddress1"=>array(
				"label"=>"inputAddress",
				"rules"=>'required'
			),
			"inputcountry1"=>array(
				"label"=>"inputcountry",
				"rules"=>'required|integer'
			),
			"inputState1"=>array(
				"label"=>"inputState",
				"rules"=>'required|integer'
			)
			// "inputcity"=>array(
			// 	"label"=>"inputcity",
			// 	"rules"=>'required|integer'
			// )
		);
	
        if ($this->validate($validation)) {
            $data = array(
				'club_slug'	   => str_replace(' ', '-', strtolower($clubname)),
                'club_name'    => $clubname,
                'address'      => $inputAddress,
                'contact_no'   => $contactno,
                'country_id'   => $inputcountry,
				'state_id'	   => $inputState,
				'city_id'	   => $inputcity
            );
			//print_r($data);
            $error = null;
			$club_model = new Club_model();
            $id = $club_model->where("club_id", $club_id)->set($data)->update();
            echo $this->sendResponse(array('success' => true, 'id'=>$id, 'error'=>$error));
        }else{
            echo $this->sendResponse(array('success' => false, 'error'=>$this->validation->listErrors()));
        }
	}
	/**
	 * View Clubs Team Request 
	 * @param club_id : club_id
	 */
	public function club_request($club_id){
		$club_model = new Club_model();
			$view['content'] = "clubs/index";
			$view['title'] = "Request";
		$view['data'] = array("club_details" => $club_model->getallclubs());
		return view('default', $view);
	}
	/**
	 * Delete the club
	 * @param club_id : club_id
	 */
	public function delete_club(){
		if(! session()->get('logged_in')){
			echo $this->sendResponse(array('success' => false, 'error'=>'Please login First'));
		}
		$club_id = $this->request->getVar('club_id');
		if(!empty($club_id)){
		$error = null;
		$club_model = new Club_model();
		$id = $club_model->where("club_id", $club_id)->set(array('deletestatus'=>1))->update();
		if(!empty($id)){
			echo $this->sendResponse(array('success' => true, 'id'=>$id, 'error'=>$error));
		}else{
			echo $this->sendResponse(array('success' => false, 'error'=>"Something went wrong!"));
		}
		}
	}
	/**
	 * Get club details
	 * @param club_id : club_id
	 */
	public function get_club_details(){
		if(! session()->get('logged_in')){
			echo $this->sendResponse(array('success' => false, 'error'=>'Please login First'));
		}
		$club_id = $this->request->getVar('club_id');
		if(!empty($club_id)){
		$error = null;
		$club_model = new Club_model();
		$result = $club_model->where("club_id", $club_id)->first();
		if(!empty($result)){
			echo $this->sendResponse($result);
		}else{
			echo $this->sendResponse(array('success' => false, 'error'=>"Something went wrong!"));
		}
		}
	}
	public function view_members(){
		if(! session()->get('logged_in')){
			echo $this->sendResponse(array('success' => false, 'error'=>'Please login First'));
		}
		$club_id = $this->request->getVar('club_id');
		$team_model = new Team_model();
		if($club_id){
			$data = $team_model->view_club_members($club_id);
			if($data)
				echo $this->sendResponse(array('success' => true, 'error'=>'', 'data' => $data));
		}
		
		echo $this->sendResponse(array('success' => false, 'error'=>"Something went wrong!"));
		
	}
	/**
	 * Join request
	 * @param club_id : club_id
	 */
	public function join_request($club_id=''){		
		if(! session()->get('logged_in')){
			return redirect()->to('/'); 
		}
		$club_id = $this->request->getVar('club_id');
		$club_model = new Club_model();
		$view['title'] = "Request";
		$view['view'] = array('title'=>"View request");
		$view['content'] = "clubs/view-request";
		$view['data'] = array("team_requests" => $club_model->getclubrequest($club_id));
		return view('default', $view);
	}
	public function join_request_action($tm_id='',$action=''){
		if(! session()->get('logged_in')){
			return redirect()->to('/'); 
		}
		
		helper(['form', 'url']);
		$tm_id = $this->request->getVar("tm_id");
		$action = $this->request->getVar("action");
		if(empty($action) && empty($tm_id)){
			return redirect()->back();
			
		}
		if(!empty($tm_id) && !empty($action)){
			$club_model = new Club_model();
			$update = $club_model->join_request_action($tm_id,$action);
			if($update){				
				return redirect()->to(site_url("/admin/clubs"));
			}
			return redirect()->back();
		}
	}

}
