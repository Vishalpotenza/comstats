<?php

namespace App\Controllers\Admin;
use App\Controllers\ApiBaseController;
use App\Models\Admin_model;
class LocationsController extends ApiBaseController
{
	/**
     * Get All Country
     */
    public function getallcountry()
    {
        $country = $this->db->table('countries')->get()->getResultArray();
        return json_encode($country);
    }
    /**
     * Get All State
     */
    public function getallstate()
    {
        $country = $this->request->getVar('id');
        $state = $this->db->table('states')->where('country_id', $country)->get()->getResultArray();
        return json_encode($state);
    }
     /**
     * Get All State
     */
    public function getallcity()
    {
        $state = $this->request->getVar('id');
        $city = $this->db->table('cities')->where('state_id', $state)->get()->getResultArray();
        return json_encode($city);
    }

}
