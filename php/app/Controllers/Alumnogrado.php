<?php namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\AlumnoGradoModel;

class Alumnogrado extends ResourceController
{
    use ResponseTrait;

    private $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function index()
    {
        $data = $this->findAll();
        return $this->respond($data);
    }

    public function show($id = null)
    {
        $alumnoGrado = $this->findById($id);
        if($alumnoGrado)
            return $this->respond($alumnoGrado);
        else
            return $this->failNotFound("La asignación ".$id." no se encuentra");
    }

    public function create()
    {
        $model = new AlumnoGradoModel();
        helper('form');

        $rules = [
            'alumno_id'      => 'required|numeric',
            'grado_grupo_id' => 'required|numeric',
        ];
        if(!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        } else {
            $data = $this->request->getRawInput();
            $findByGradoGrupo = $this->findByGradoGrupo($data['grado_grupo_id']);
            $count_group = $findByGradoGrupo ? count($findByGradoGrupo) : 0; 
            if($count_group < 30) {
                $model->insert($data);
                $this->updateAlumnoStatus($data['alumno_id'], 1);
                $response = [
                    'status'    => 201,
                    'messages'  => [
                        'success' => 'El alumno se ha asignado con éxito al grupo'
                    ]
                ];
                return $this->respondCreated($response);
            } else {
                $data = $this->findGradoGrupoById($data['grado_grupo_id']);
                return $this->failValidationError("El grupo ".$data['grado']."º".$data['grupo']." actualmente tiene cupo lleno de alumnos");
            }
        }
    }

    public function update($id = null)
    {
        $model = new AlumnoGradoModel();
        $data = $this->request->getRawInput();
        $data['updated'] = date('Y-m-d');
        if($model->update($id, $data)) {
            $response = [
                'status'    => 200,
                'messages'  => [
                    'success' => 'El alumno ha sido actualizado de grupo correctamente'
                ]
            ];
            return $this->respond($response);
        } else {
            $this->fail([
                'status'   => 400,
                'messages' => [
                    "Ocurrió un error al momento de actualizar la información"
                ]
            ], 400);
        }
    }

    public function delete($id = null)
    {
        $model = new AlumnoGradoModel();
        $data = $this->findById($id);
        if($model->delete($id)) {
            $this->updateAlumnoStatus($data['alumno_id'], 0);
            $response = [
                'status'    => 200,
                'alumnogrado_id' => $id,
                'messages'  => [
                    'success' => 'El alumno se ha dado de bajo del grupo'
                ]
            ];
            return $this->respondDeleted($response);
        } else {
            $this->fail([
                'status'   => 400,
                'messages' => [
                    "Ocurrió un error al momento de borrar la información"
                ]
            ], 400);
        }
    }

    private function builderDefault() 
    {
        $builder = $this->db->table('alumno_grado');
        $builder->select('alumno_grado.*, grado_grupo.grado, grado_grupo.grupo, CONCAT(alumno.name, " ", alumno.lastname) AS alumno');
        $builder->join('grado_grupo', 'grado_grupo.id = alumno_grado.grado_grupo_id');
        $builder->join('alumno', 'alumno.id = alumno_grado.alumno_id');
        return $builder;
    }

    private function findAll()
    {
        $builder = $this->builderDefault();
        return $builder->get()->getResultArray();
    }

    private function findById($id = null)
    {
        $builder = $this->builderDefault();
        $builder->where('alumno_grado.id', $id);
        return $builder->get()->getRowArray();
    }

    private function findByGradoGrupo($gradoGrupo = null)
    {
        $builder = $this->db->table('alumno_grado');
        $builder->where('grado_grupo_id', $gradoGrupo);
        return $builder->get()->getRowArray();
    }

    private function findGradoGrupoById($gradoGrupo = null)
    {
        $builder = $this->db->table('grado_grupo');
        $builder->where('id', $gradoGrupo);
        return $builder->get()->getRowArray();
    }

    private function updateAlumnoStatus($alumno_id = null, $status = null)
    {
        $builder = $this->db->table('alumno');
        $builder->set('status', $status);
        $builder->set('updated', date('Y-m-d H:i:s'));
        $builder->where('id', $alumno_id);
        $builder->update();
    }
}