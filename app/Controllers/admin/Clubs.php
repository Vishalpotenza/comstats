<?php

namespace App\Controllers\Admin;
use App\Controllers\ApiBaseController;
use App\Models\Admin_model;
class Clubs extends ApiBaseController
{
	/**
	 * Load Dashboard function
	 *
	 * @return view
	 */
	public function index()
	{
        $view['content'] = "clubs/index";
		return view('default', $view);
	}

}
