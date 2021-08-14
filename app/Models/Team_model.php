<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
class Team_model extends Model {

    protected $table = 'tbl_team';

    protected $allowedFields = [
        'team_id',
        'club_id',
        'team_name',
        'team_logo',       
        'deletestatus',
        'created'
    ];
    
    /**
     * Get All Team function
     *
     * @return void
     */
    public function getallteam() {
		// $data = $this->db->table($this->table)->where('deletestatus',0)->get()->getResultArray();
        $data = $this->db->table($this->table);
		$data = $data->join('tbl_club', 'tbl_club.club_id = tbl_team.club_id', 'LEFT');
		$data = $data->where('tbl_team.deletestatus',0)->get()->getResultArray();
		
		return $data;
    }
	/**
     * Check team exist 
     *
     * @return void
     */
	public function check_team_exist($team_name) {
        $check = $this->db->table($this->table)->where('team_name', $team_name);
		if($check->countAllResults() > 0){
			return true;
		}else{
			return false;
		}
    }
   

   
}
?>