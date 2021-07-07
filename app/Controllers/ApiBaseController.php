<?php

namespace App\Controllers;

class ApiBaseController extends BaseController
{   /**
    * Used for api authentication function
    *
    * @return void
    */
	public function authenticate_api()
    {
        $response = array( "status" => "authentication falied!" );
        if(isset($_SERVER['PHP_AUTH_USER'])||isset($_SERVER['PHP_AUTH_PW']))
        {
            $cridentails=array("username"=>$_SERVER['PHP_AUTH_USER']
            ,"password"=>$_SERVER['PHP_AUTH_PW']);
            $value= $this->db->table('tbl_apiaauth')->where($cridentails)->countAllResults();
            if($value > 0)
            {
                return true;
            }
            else{
                $this->sendResponse($response);
            }
        }
        else{
              $this->sendResponse($response);
            
        }
    }
    /**
     * Used for Sending response
     */
    public function sendResponse($response) {
        header('Content-Type: application/json');
        echo json_encode($response);
        die;
    }
    /**
     * Check Required Param
     */
    public function verifyRequiredParams($fields) {
        $error = false;
        $error_fields = "";
        $request_params = array();
        $request_params = $_REQUEST;
        foreach ($fields as $field)
		{
			if (!isset($request_params[$field]))
			{
				$error = true;
				$error_fields .= $field . ', ';
			}
		}
		if ($error)
		{
			// Required field(s) are missing or empty
			// echo error json and stop the app
			$response = array();
			$response["error"] = true;
			$response["message"] = 'One or more fileds are required. ' . substr($error_fields, 0, -2);
			return false;
		}
		else
		{
			return true;
		}
    }
    /**
     * check if exists
     */
    public function ifexists($table_name, $value, $field)
    {
        $query = $this->db->table($table_name)->where($field, $value);
        if ($query->countAllResults() > 0){
            return true;
        }
        else{
            return false;
        }
    }
     /**
     * check if exists
     */
    public function ifexistsexcludeid($table_name, $value, $field, $user_id)
    {
        $query = $this->db->table($table_name)->where(array($field => $value, "user_id<>" => $user_id));
        if ($query->countAllResults() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    
    /**
     * Check if empty
     */
    public function ifempty($value, $field)
    {
        if(empty($value) == true){
            return "Please enter ".$field." value.";
        }
        return true;
    }
    /**
     * Check valid Email
     */
    public function is_valid_email(string $str = null): bool {
		if (function_exists('idn_to_ascii') && defined('INTL_IDNA_VARIANT_UTS46') && preg_match('#\A([^@]+)@(.+)\z#', $str, $matches))
		{
			$str = $matches[1] . '@' . idn_to_ascii($matches[2], 0, INTL_IDNA_VARIANT_UTS46);
		}

		return (bool) filter_var($str, FILTER_VALIDATE_EMAIL);
    }
    /**
     * Get date
     */
    public function get_date($date){
        //Creating a DateTime object
        $date_time_Obj = date_create($date);
        //formatting the date/time object
        $format = date_format($date_time_Obj, "Y-m-d");
        return $format;
    }
    /**
     * Calculate age
     */
    public function calculate_age($dob){
        $today = date("Y-m-d");
        $diff = date_diff(date_create($dob), date_create($today));
        return $diff->format('%y');
    }
}
