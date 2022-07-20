<?php
namespace App\Models;
use CodeIgniter\Model;

class AlumnoGradoModel extends Model
{
    protected $table = 'alumno_grado';
    protected $primaryKey = 'id';
    protected $allowedFields = ['alumno_id', 'grado_grupo_id', 'created', 'updated'];
}