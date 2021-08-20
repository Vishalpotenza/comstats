<?php

namespace App\Controllers\Admin;
use App\Controllers\ApiBaseController;
use App\Models\Admin_model;
class Dashboard extends ApiBaseController
{
	/**
	 * Load Dashboard function
	 *
	 * @return view
	 */
	public function index()
	{
        $view['content'] = "dashboard/index";
		$view['data'] = array();
		return view('default', $view);
	}

}
