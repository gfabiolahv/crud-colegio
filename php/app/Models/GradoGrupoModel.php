<?php
namespace App\Models;
use CodeIgniter\Model;

class GradoGrupoModel extends Model
{
    protected $table = 'grado_grupo';
    protected $primaryKey = 'id';
    protected $allowedFields = ['grado', 'grupo', 'profesor_id', 'created', 'updated'];
}