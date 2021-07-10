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
        'created'
    ];

    public function getallclubs() {
        return $this->db->table($this->table)
                 ->join('countries', 'countries.id ='.$this->table.'.country_id')
                 ->join('states', 'states.id ='.$this->table.'.state_id')
                //  ->join('cities', 'cities.id ='.$this->table.'.city_id')
                 ->get()->getResultArray();
    }
   

   
}
?>