<?php namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\AlumnoModel;

class Alumno extends ResourceController
{
    use ResponseTrait;

    private $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    //GET
    public function index(){
        $alumnos = $this->findAll();
        return $this->respond($alumnos);
    }

    //GET by Id
    public function show($id = null) {
        $model = new AlumnoModel();
        $alumno = $this->findById($id);
        if($alumno) 
            return $this->respond($alumno);
        else
            return $this->failNotFound("El alumno con el id: ".$id.", no se encuentra registrado");
    }

    //POST
    public function create() {
        $model = new AlumnoModel();
        helper('form');

        $rules = [
            'name'      => 'required|max_length[25]',
            'lastname'  => 'required|max_length[50]',
            'gender'    => 'required|max_length[1]',
            'birthday'  => 'required'
        ];
        if(!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        } else {
            $data = $this->request->getRawInput();
            $data['birthday'] = date('Y-m-d', ($data['birthday']/1000)+86400);
            $alumno_id = $model->insert($data);

            $response = [
                'status'    => 201,
                'alumno_id' => $alumno_id,
                'messages'  => [
                    'success' => 'El alumno ha sido creado correctamente'
                ]
            ];
            return $this->respondCreated($response);
        }
    }

    //PUT
    public function update($id = null) {
        $model = new AlumnoModel();
        $data = $this->request->getRawInput();
        if($this->findById($id)) {
            $data['updated'] = date('Y-m-d H:i:s');
            if($data['birthday']) 
                $data['birthday'] = date('Y-m-d', ($data['birthday']/1000)+86400);
            else
                unset($data['birthday']);
            if($model->update($id, $data)) {
                $response = [
                    'status'    => 200,
                    'alumno_id' => $id,
                    'messages'  => [
                        'success' => 'El alumno ha sido actualizado correctamente'
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
        } else 
            return $this->failNotFound("El alumno con el id: ".$id.", no se encuentra registrado");
    }

    //DELETE
    public function delete($id = null) {
        $model = new AlumnoModel();
        if($this->findById($id)) {
            if($model->delete($id)) {
                $this->unlinkGradoGrupo($id);
                $response = [
                    'status'    => 200,
                    'alumno_id' => $id,
                    'messages'  => [
                        'success' => 'El alumno ha sido borrado correctamente'
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
        } else 
            return $this->failNotFound("El alumno con el id: ".$id.", no se encuentra registrado");
    }

    private function findAll()
    {
        $builder = $this->db->table('alumno');
        $builder->select('alumno.*, CONCAT(grado_grupo.grado, "º", grado_grupo.grupo) AS grupo');
        $builder->join('alumno_grado', 'alumno_grado.alumno_id = alumno.id', 'left');
        $builder->join('grado_grupo', 'grado_grupo.id = alumno_grado.grado_grupo_id', 'left');
        return $builder->get()->getResultArray();
    }

    private function findById($id = null) 
    {
        $model = new AlumnoModel();
        return $model->find($id);
    }

    private function unlinkGradoGrupo($alumno_id = null)
    {
        $builder = $this->db->table('alumno_grado');
        $builder->where('alumno_id', $alumno_id);
        $builder->delete();
    }
}