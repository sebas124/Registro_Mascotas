<?php

namespace App\Http\Controllers\Pets;

use Exception;
use App\Http\Enums\Codes;
use Illuminate\Http\Request;
use App\Models\Pets\PetsModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\MyController;

class PetsController extends MyController{

    private $model;
    private $validations;

    public function __construct(){
        parent::__construct();
        $this->initValidations();
        $this->model = new PetsModel();
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo usado para inicializar la validacion de campos en el controlador.
     */
    public function initValidations(){
        $this->validations = [
            'id'                 => 'prohibited',
            'pet_name'           => 'required|string|max:255',
            'pet_specie'         => 'required|string|max:255',
            'pet_breed'          => 'required|string|max:255',
            'pet_image'          => 'required|string|max:255',
            'pet_age'            => 'required|string|max:255',
            'person_id'          => 'required|integer',
            'pet_delete'         => 'prohibited',
            'created_at'         => 'prohibited',
            'updated_at'         => 'prohibited',
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
        
            $apiData = $this->getDogBreedInfo($objData->pet_breed);
            $imageUrl = isset($apiData) ?  $apiData['images']->get(0) : null;

            $data = [
                "pet_name"          => $objData->pet_name,
                "pet_specie"        => $objData->pet_specie,
                'pet_breed'         => $apiData ? $apiData['breed'] : $objData->pet_breed,
                "pet_age"           => $objData->pet_age,
                'pet_image'         => $imageUrl ? $imageUrl : $objData->pet_image,
                "person_id"         => $objData->person_id
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
                "pet_name"          => $objData->pet_name,
                "pet_specie"        => $objData->pet_specie,
                "pet_breed"         => $objData->pet_breed,
                "pet_age"           => $objData->pet_age,
                "pet_image"         => $objData->pet_image,
                "person_id"         => $objData->person_id
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
     * @internal Metodo usado para consultar datos de la raza de perro (Api externa)
     * @param string breedName - Nombre de raza mascota
     */
    public function getDogBreedInfo($breedName){
        try {
            // Buscar información de la raza
            $breedResponse = Http::withHeaders([
                'x-api-key' => config('services.dogapi.key'),
            ])->get(config('services.dogapi.url') . '/breeds/search', [
                'q' => $breedName
            ]);
    
            $breedData = $breedResponse->json()[0] ?? null;

            if (!$breedData || !isset($breedData['id'])) {
                return null;
            }
    
            $breedId = $breedData['id'];
    
            // Obtener hasta 10 imágenes asociadas a la raza
            $imageResponse = Http::withHeaders([
                'x-api-key' => config('services.dogapi.key'),
            ])->get(config('services.dogapi.url') . '/images/search', [
                'limit' => 10,
                'breed_id' => $breedId
            ]);
    
            $images = collect($imageResponse->json())->pluck('url');
    
            return [
                'breed' => $breedData['name'],
                'life_span' => $breedData['life_span'] ?? '',
                'images' => $images, // array con hasta 10 URLs
            ];
    
        } catch (Exception $e) {
            Log::error('Error fetching dog breed info: ' . $e->getMessage());
            return null;
        }
    }
}

