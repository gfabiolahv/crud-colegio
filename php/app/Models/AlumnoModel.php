<?php
namespace App\Models;
use CodeIgniter\Model;

class AlumnoModel extends Model
{
    protected $table = 'alumno';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'lastname', 'gender', 'birthday', 'status', 'created', 'updated'];
}