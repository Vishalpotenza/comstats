<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
class Firebase_model extends Model {

    protected $table = 'tbl_firebase';

    protected $allowedFields = [
        'id',
        'f_key',
        'f_value',           
        'deletestatus',
        'created'
    ];
    
    /**
     * Get All Firebase data
     *
     * @return void
     */
    public function getallfirebase() {
		$data = $this->db->table($this->table);
		$data = $data->where('deletestatus',0)->get()->getResultArray();
		if($data){
			return $data;
		}else{
			return false;
		}
    }
	/**
     * Check team exist 
     *
     * @return void
     */
	public function check_firebase_exist($f_key) {
        $check = $this->db->table($this->table)->where('f_key', $f_key);
		if($check->countAllResults() > 0){
			return true;
		}else{
			return false;
		}
    }  
}
?>