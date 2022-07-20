<?php namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\GradoGrupoModel;

class Gradogrupo extends ResourceController
{
    use ResponseTrait;

    private $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function index()
    {
        $builder = $this->builderDefault();
        return $this->respond($builder->get()->getResultArray());
    }

    public function show($id = null)
    {
        $gradoGrupo = $this->findById($id);
        if($gradoGrupo)
            return $this->respond($gradoGrupo);
        else
            return $this->failNotFound("El grupo con el id: ".$id.", no se encuentra registrado");
    }

    public function create()
    {
        $model = new GradoGrupoModel();
        helper('form');

        $rules = [
            'grado'       => 'required|numeric',
            'grupo'       => 'required|max_length[1]',
            'profesor_id' => 'required|numeric'
        ];
        if(!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        } else {
            $data = $this->request->getRawInput();
            $gradoGrupo_id = $model->insert($data);
            $this->updateProfesorStatus($data['profesor_id'], 1);
            $response = [
                'status'    => 201,
                'gradoGrupo_id' => $gradoGrupo_id,
                'messages'  => [
                    'success' => 'El grupo ha sido creado correctamente'
                ]
            ];
            return $this->respondCreated($response);
        }
    }

    public function update($id = null)
    {
        $model = new GradoGrupoModel();
        $data = $this->request->getRawInput();
        if($this->findById($id)) {
            $data['updated'] = date('Y-m-d');
            if($model->update($id, $data)) {
                $response = [
                    'status'    => 200,
                    'gradoGrupo_id' => $id,
                    'messages'  => [
                        'success' => 'El grupo ha sido actualizado correctamente'
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
            return $this->failNotFound("El grupo con el id: ".$id.", no se encuentra registrado");
    }

    public function delete($id = null) 
    {
        $model = new GradoGrupoModel();
        if($this->findById($id)) {
            if($model->delete($id)) {
                $response = [
                    'status'    => 200,
                    'gradoGrupo_id' => $id,
                    'messages'  => [
                        'success' => 'El grupo ha sido borrado correctamente'
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
            return $this->failNotFound("El grupo con el id: ".$id.", no se encuentra registrado");
    }

    private function builderDefault() 
    {
        $builder = $this->db->table('grado_grupo');
        $builder->select('grado_grupo.*, CONCAT(grado_grupo.grado, "º", grado_grupo.grupo) AS name, CONCAT(profesor.name, " ", profesor.lastname) AS profesor');
        $builder->join('profesor', 'profesor.id = grado_grupo.profesor_id');
        return $builder;
    }

    private function findById($id = null)
    {
        $builder = $this->builderDefault();
        $builder->where('grado_grupo.id', $id);
        return $builder->get()->getRowArray();
    }

    private function updateProfesorStatus($profesor_id = null, $status = null)
    {
        $builder = $this->db->table('profesor');
        $builder->set('status', $status);
        $builder->where('id', $profesor_id);
        $builder->update();
    }
}