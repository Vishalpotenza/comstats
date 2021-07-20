<?php

namespace App\Controllers\Admin;
use App\Controllers\ApiBaseController;
use App\Models\Admin_model;
use App\Models\Club_model;
class Clubs extends ApiBaseController
{
	/**
	 * Load Dashboard function
	 *
	 * @return view
	 */
	public function index()
	{
		$club_model = new Club_model();
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
		$view['data'] = array("club_details" => $club_model->getallclubs());
		return view('default', $view);
	}
	/**
	 * Delete the club
	 * @param club_id : club_id
	 */
	public function delete_club(){
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

}
