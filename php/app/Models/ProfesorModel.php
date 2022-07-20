<?php
namespace App\Models;
use CodeIgniter\Model;

class ProfesorModel extends Model
{
    protected $table = 'profesor';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'lastname', 'gender', 'status', 'created', 'updated'];
}