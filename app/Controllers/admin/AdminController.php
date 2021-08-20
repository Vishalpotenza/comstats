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
		$team_model = new Team_model();
		$club_model = new Club_model();
		$admin_data = $this->db->table('')
		$view['view'] = array('title'=>'team Details');
        $view['content'] = '/team/index';
		$view['data'] = $team_model->getallteam();
		$view['club_list'] = $club_model->getclubslist();
		return view('default', $view);
	}

}
