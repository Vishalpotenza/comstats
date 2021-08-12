<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
class League_model extends Model {

    protected $table = 'tbl_tournament';

    protected $allowedFields = [
        'id',
        'slug',
        'name',       
        'deletestatus',
        'created'
    ];
    /**
     * Get All clubs function
     *
     * @return void
     */
    public function getallleague() {
        return $this->db->table($this->table)->where('deletestatus',0)->get()->getResultArray();
    }
	/**
     * Check slug exist 
     *
     * @return void
     */
	public function check_slug_exist($slug) {
        $check = $this->db->table($this->table)->where('slug', $slug);
		if($check->countAllResults() > 0){
			return true;
		}else{
			return false;
		}
                 // ->get()->getResultArray();
    } 
}
?>