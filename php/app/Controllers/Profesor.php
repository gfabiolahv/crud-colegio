<?php namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ProfesorModel;

class Profesor extends ResourceController
{
    use ResponseTrait;

    private $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    //GET
    public function index(){
        $profesores = $this->findAll();
        return $this->respond($profesores);
    }

    //GET by Id
    public function show($id = null) {
        $model = new ProfesorModel();
        $profesor = $this->findById($id);
        if($profesor) 
            return $this->respond($profesor);
        else
            return $this->failNotFound("El profesor con el id: ".$id.", no se encuentra registrado");
    }

    //POST
    public function create() {
        $model = new ProfesorModel();
        helper('form');

        $rules = [
            'name'      => 'required|max_length[25]',
            'lastname'  => 'required|max_length[50]',
            'gender'    => 'required|max_length[1]'
        ];
        if(!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        } else {
            $data = $this->request->getRawInput();
            $profesor_id = $model->insert($data);

            $response = [
                'status'    => 201,
                'profesor_id' => $profesor_id,
                'messages'  => [
                    'success' => 'El profesor ha sido creado correctamente'
                ]
            ];
            return $this->respondCreated($response);
        }
    }

    //PUT
    public function update($id = null) {
        $model = new ProfesorModel();
        $data = $this->request->getRawInput();
        if($this->findById($id)) {
            $data['updated'] = date('Y-m-d H:i:s');
            if($model->update($id, $data)) {
                $response = [
                    'status'    => 200,
                    'profesor_id' => $id,
                    'messages'  => [
                        'success' => 'El profesor ha sido actualizado correctamente'
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
            return $this->failNotFound("El profesor con el id: ".$id.", no se encuentra registrado");
    }

    //DELETE
    public function delete($id = null) {
        $model = new ProfesorModel();
        if($this->findById($id)) {
            if($model->delete($id)) {
                $response = [
                    'status'    => 200,
                    'profesor_id' => $id,
                    'messages'  => [
                        'success' => 'El profesor ha sido borrado correctamente'
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
            return $this->failNotFound("El profesor con el id: ".$id.", no se encuentra registrado");
    }

    private function findAll()
    {
        $builder = $this->db->table('profesor');
        $builder->select('profesor.*, CONCAT(profesor.name, " ", profesor.lastname) AS fullname');
        return $builder->get()->getResultArray();
    }

    private function findById($id = null) 
    {
        $model = new ProfesorModel();
        return $model->find($id);
    }
}