<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
class Admin_model extends Model {

    protected $table = 'tbl_admin';

    protected $allowedFields = [
        'id',
        'first_name',
        'last_name',
        'email',
        'password'
    ];

    public function authenticate($email, $password)
    {
        $query = $this->db->table($this->table)->where(array("email" => $email, "password" => $password));
        if ($query->countAllResults() > 0){
			
            $session = session();
			$data = $this->db->table($this->table)->where(array("email" => $email, "password" => $password))->get()->getRowArray();
			$ses_data['id'] = $data['id'];
			$ses_data['first_name'] = $data['first_name'];
			$ses_data['last_name'] = $data['last_name'];
			$ses_data['email'] = $data['email'];
			$ses_data['image'] = $data['image'];
			$ses_data['created'] = $data['created'];
			$ses_data['logged_in'] = TRUE;
			$session->set($ses_data);
			return true;
        }
        else{
            return false;
        }
    }
	public function eamil_exist_admin($email, $user=''){
		$table='tbl_admin';
		if($user=='user'){
			$table='tbl_user_login';
		}
		$query = $this->db->table($table)->where(array("email" => $email));
        if ($query->countAllResults() > 0){
            return true;
		}else{
            return false;        
		}
	}
   
}
?>