<?php

namespace App\Controllers\Admin;
use App\Controllers\ApiBaseController;
use App\Models\Admin_model;
use App\Models\League_model;

class League extends ApiBaseController
{
	/**
	 * Load League Page
	 *
	 * @return view
	 */
	public function index()
	{
		if(! session()->get('logged_in')){
			return redirect()->to('/'); 
		}
		$league_model = new League_model();
		$view['title'] = "League";
		$view['view'] = array('title'=>"League Details");
        $view['content'] = "league/index";
		$view['data'] = array("league_details" => $league_model->getallleague());
		return view('default', $view);
	}
	/**
	 * Add League callbacks
	 * @return  json
	 */
	public function add_league()
	{
		if(! session()->get('logged_in')){
			echo $this->sendResponse(array('success' => false, 'error'=>'Please login First'));
		}
		$league_model = new League_model();
		$leaguename = $this->request->getPost('leaguename');       
		$leaguename = trim($leaguename);       
    
        helper(['form', 'url']);
		$validation=array(
			"leaguename"=>array(
				"label"=>"leaguename",
				"rules"=> 'required|alpha_space'
			)			
		);
		$leaguename_slug = strtolower($leaguename);
		$leaguename_slug = str_replace(" ","_",$leaguename_slug);
		$check_slug_exist = $league_model->check_slug_exist($leaguename_slug);
		if($check_slug_exist){
			echo $this->sendResponse(array('success' => false, 'error'=>'Aleredy Exist'));
		}
			
		if ($this->validate($validation)) {
            $data = array(
				'slug'	   => $leaguename_slug,
                'name'    => $leaguename                
            );
			$error = null;
			$id = $league_model->insert($data);
            echo $this->sendResponse(array('success' => true, 'id'=>$id, 'error'=>$error));
        }else{
            echo $this->sendResponse(array('success' => false, 'error'=>$this->validation->listErrors()));
        }
	}
	/**Edit League callback
	 * @return json
	 */
	public function edit_league()
	{
		if(! session()->get('logged_in')){
			echo $this->sendResponse(array('success' => false, 'error'=>'Please login First'));
		}
		$leaguename = $this->request->getPost('leaguename');
		// $leaguename = $this->request->getPost('leaguename');
		$leaguename = trim($leaguename);
		$id = $this->request->getPost('edit_data_id');
		
        helper(['form', 'url']);
		$validation=array(
			"leaguename"=>array(
				"label"=>"leaguename",
				"rules"=> 'required|alpha_space'
			)			
		);
		$league_model = new League_model();
		$leaguename_slug = strtolower($leaguename);
		$leaguename_slug = str_replace(" ","_",$leaguename_slug);
		$check_slug_exist = $league_model->check_slug_exist($leaguename_slug);
		
		
        if ($this->validate($validation)) {
			$data = array(
				// 'id' => $id,
				'slug'	   => $leaguename_slug,
				'name'    => $leaguename                
			); 
			$error = null;
			$update = '';
			if($id){
				$league_model = new League_model();
				$update = $league_model->where('id',$id)->set($data)->update();
			}			
            echo $this->sendResponse(array('success' => true, 'id'=>$update, 'error'=>$error));
        }else{
            echo $this->sendResponse(array('success' => false, 'error'=>$this->validation->listErrors()));
        }
	}	
	/**
	 * Delete the League
	 * @param id : id
	 */
	public function delete_League(){
		if(! session()->get('logged_in')){
			echo $this->sendResponse(array('success' => false, 'error'=>'Please login First'));
		}
		$id = $this->request->getVar('id');
		if(!empty($id)){
			$error = null;
			$league_model = new League_model();
			$id = $league_model->where("id", $id)->set(array('deletestatus'=>1))->update();
			if(!empty($id)){
				echo $this->sendResponse(array('success' => true, 'id'=>$id, 'error'=>$error));
			}else{
				echo $this->sendResponse(array('success' => false, 'error'=>"Something went wrong!"));
			}
		}
	}
	/**
	 * Get league details
	 * @param id : id
	 */
	public function get_league_details(){
		if(! session()->get('logged_in')){
			echo $this->sendResponse(array('success' => false, 'error'=>'Please login First'));
		}
		$id = $this->request->getVar('id');
		if(!empty($id)){
			$error = null;
			$League_model = new League_model();
			$result = $League_model->where("id", $id)->first();
			if(!empty($result)){
				echo $this->sendResponse($result);
			}else{
				echo $this->sendResponse(array('success' => false, 'error'=>"Something went wrong!"));
			}
		}
	}
	

}
