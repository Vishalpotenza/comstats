<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
class Club_model extends Model {

    protected $table = 'tbl_club';

    protected $allowedFields = [
        'club_id',
        'club_slug',
        'club_name',
        'address',
        'contact_no',
        'country_id',
        'state_id',
        'city_id',
        'deletestatus',
        'created'
    ];
    /**
     * Get All clubs function
     *
     * @return void
     */
    public function getallclubs() {
        return $this->db->table($this->table)
                 ->select('tbl_team.club_id, club_name, address, contact_no , countries.name as country_name, states.name as state_name,  (select count(*) from tbl_team_member_relation where tbl_team_member_relation.team_id = tbl_team.team_id and tbl_team_member_relation.deletestatus = 0 and tbl_team_member_relation.designation<>1 and tbl_team_member_relation.request_status=0) as request') 
				->distinct()
				->where($this->table.'.deletestatus',0)
                 ->join('countries', 'countries.id ='.$this->table.'.country_id')
                 ->join('states', 'states.id ='.$this->table.'.state_id')
                 ->join('tbl_team','tbl_team.club_id ='.$this->table.'.club_id')
				 // ->where($this->table'.deletestatus',0)
                //  ->join('cities', 'cities.id ='.$this->table.'.city_id')
                 ->get()->getResultArray();
    }
    /**
     * Get single club details
     */
     public function getallclubsbyid($club_id) {
        $result = $this->db->table($this->table)
                 ->select('tbl_team.club_id, club_name, address, contact_no , countries.name as country_name, states.name as state_name')   
                 ->join('countries', 'countries.id ='.$this->table.'.country_id')
                 ->join('states', 'states.id ='.$this->table.'.state_id')
                 ->join('tbl_team','tbl_team.club_id ='.$this->table.'.club_id')
                 ->where($this->table.".club_id", $club_id)  
                 ->get()->getRowArray();
        $conditions = array(
            'club_id'=> $club_id,
            "tbl_team_member_relation.deletestatus" => 0,
            "tbl_team_member_relation.designation<>" => 1
        );
        $request = $this->db->table('tbl_team')->join('tbl_team_member_relation','tbl_team_member_relation.team_id=tbl_team.team_id')->where($conditions)->get()->getResultArray();
        $result['request'] = $request;
        return $result; 
    }
	/**
     * Get clubs list
     *
     * @return void
     */
	public function getclubslist() {
        return $this->db->table($this->table)
                 ->select('club_id, club_slug, club_name')
				 ->where('deletestatus',0)
                 ->get()->getResultArray();
    }
	public function getclubrequest($club_id){
		// return 1;
		$request_team_id = $this->db->table('tbl_team')
                 ->select('team_id')   
                 ->join($this->table,$this->table.'.club_id = tbl_team.club_id')
				 ->where('tbl_team.club_id',$club_id)
				 ->where('tbl_team.deletestatus',0)->where($this->table.'.deletestatus',0)
                 ->get()->getResultArray();
		 if($request_team_id){
			$request_team_id = array_column($request_team_id, 'team_id');
		 
			$request = $this->db->table('tbl_team_member_relation')
			 ->select($this->table.'.club_name as club, tbl_user.first_name as coach, tbl_team.team_name as team, tbl_team_member_relation.tm_id as id')
			 ->join('tbl_user','tbl_user.user_id = tbl_team_member_relation.user_id')
			 ->join('tbl_team','tbl_team.team_id = tbl_team_member_relation.team_id')
			 ->join($this->table,'tbl_team.club_id = '.$this->table.'.club_id')
			 ->whereIn('tbl_team_member_relation.team_id',$request_team_id)
			 ->where('tbl_team_member_relation.deletestatus',0)
			 ->where('tbl_team_member_relation.designation <>',1)
			 ->where('tbl_team_member_relation.request_status',0)
			 ->get()->getResultArray();
			 // print_r($request);
			 return $request;
			 
		 
		 }
		 return false;
	}
	public function join_request_action($tm_id,$action){
		$action_data['request_status'] = $action;
		$request_update = $this->db->table('tbl_team_member_relation')->where('deletestatus',0)->where('tm_id',$tm_id)->update($action_data);
		if($request_update){
			return true;
		}else{
			return false;
		}
		
	}

   
}
?>