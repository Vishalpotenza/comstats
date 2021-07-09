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
            return true;
        }
        else{
            return false;
        }
    }

   
}
?>