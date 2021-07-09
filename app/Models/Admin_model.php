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

   
}
?>