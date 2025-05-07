<?php

namespace App\Http\Controllers\People;

use Exception;
use App\Http\Enums\Codes;
use Illuminate\Http\Request;
use App\Models\People\PeopleModel;
use App\Http\Controllers\MyController;

class PeopleController extends MyController{

    private $model;
    private $validations;

    public function __construct(){
        parent::__construct();
        $this->initValidations();
        $this->model = new PeopleModel();
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo usado para inicializar la validacion de campos en el controlador.
     */
    public function initValidations(){
        $this->validations = [
            'id'                => 'prohibited',
            'person_name'       => 'required|string|max:255',
            'person_lastname'   => 'required|string|max:255',
            'person_email'      => 'required|string|max:255',
            'person_phone'      => 'string|max:255',
            'person_birthdate'  => 'required|string|max:255',
            'person_delete'     => 'prohibited',
            'created_at'        => 'prohibited',
            'updated_at'        => 'prohibited',
        ];
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo usado para consultar datos en un listado
     */
    public function index() {
        try{
            $result     = $this->model->get();

            return $this->returnData($result,Codes::ERROR_NOT_FOUN_DATA->getMessage());
            
        }catch(Exception $e){
            $this->showException($e);
            return $this->returnError(Codes::ERROR_OCURRED->getMessage());
        }
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo usado para consultar datos especificos
     * @param $id - Id de la persona a consultar
     */
    public function show($id) {
        try{
            $result     = $this->model->getById($id);
            return $this->returnData($result,Codes::ERROR_NOT_FOUN_DATA->getMessage());
        } catch (Exception $e){
            $this->showException($e);
            return $this->returnError(Codes::ERROR_OCURRED->getMessage());
        }
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo usado para almacenar los datos
     * @param Request $request - Datos enviados por el cliente
     */
    public function store(Request $request){
        try {
            // validacion de request
            $validator = $this->validateRequest($request,$this->validations);

            if ($validator->fails()) {
                return $this->returnErrorFields($validator->errors());
            }

            $objData = json_decode($request->getContent());
            
            $data = [
                "person_name"       => $objData->person_name,
                "person_lastname"   => $objData->person_lastname,
                "person_email"      => $objData->person_email,
                "person_phone"      => $objData->person_phone,
                "person_birthdate"  => $objData->person_birthdate
            ];
            // insert data
            $result = $this->model->insertData($data);

            return $this->returnCreated($result, Codes::SUCCESS_CREATE->getMessage());
        } catch (Exception $e) {
            $this->showException($e);
            return $this->returnError(Codes::ERROR_OCURRED->getMessage());
        }
    }

    /**
     * @author Sebastian Reyes
     * @version 2.0.0
     * @internal Metodo usado para actualizar datos en DB.
     * @param Request $request - Datos enviados por el cliente
     * @param int id - id del registro a modificar
     */
    public function update(Request $request, $id){
        try {
            // validacion de request
            $validator = $this->validateRequest($request,$this->validations);
            if ($validator->fails()) {
                return $this->returnErrorFields($validator->errors());
            }
            $objData    = json_decode($request->getContent());

            $data = [
                "person_name"       => $objData->person_name,
                "person_lastname"   => $objData->person_lastname,
                "person_email"      => $objData->person_email,
                "person_phone"      => $objData->person_phone,
                "person_birthdate"  => $objData->person_birthdate
            ];

            $this->model->updateData($data, $id);

            return $this->returnOk(Codes::SUCCESS_UPDATE->getMessage()); // Actualizado exitosamente
        } catch (Exception $e) {
            return $this->returnError(Codes::ERROR_OCURRED->getMessage()); // Se produjo un error
        }
    }


    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo usado para eliminar dato en DB.
     * @param int id - id del registro a eliminar
     */
    public function destroy($id){
        try{
            $this->model->deleteById($id);
            return $this->returnOk(Codes::SUCCESS_DELETED->getMessage());
        }catch(Exception $e){
            return $this->returnError(Codes::ERROR_OCURRED->getMessage()); // Se produjo un error
        }
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo usado para consultar datos de personas con su mascota relacionada
     */
    public function getDataPeopleWithPets(){
        try{
            $result     = $this->model->getDataPeopleWithPets();
            return $this->returnData($result,Codes::ERROR_NOT_FOUN_DATA->getMessage());
            
        }catch(Exception $e){
            $this->showException($e);
            return $this->returnError(Codes::ERROR_OCURRED->getMessage());
        }
    }
}

