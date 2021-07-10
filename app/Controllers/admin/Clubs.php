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

}
