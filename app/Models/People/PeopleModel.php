<?php

namespace App\Models\People;

use Exception;
use App\Models\MyModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class PeopleModel extends MyModel{

    use HasFactory;
    
    protected $table              = "people";
    protected $identificator      = "id";
    protected $delete             = "person_delete";
    protected $creator            = "created_at";
    protected $fillable           = ['person_name', 'person_lastname', 'person_email', 'person_phone', 'person_birthdate'];

    public function get(){
        try{
            return $this::where($this->delete, 0)
                            ->orderBy($this->identificator, 'asc')
                            ->get();

        }catch(Exception $e){
            $this->showException($e);
            exit($this->returnError("0018","Error querying record")); // record no exist
        }
    }

    public function getById($id){
        try{
            return $this::where('id', $id)
                                ->where($this->delete, 0)
                                ->first();
        }catch(Exception $e){
            $this->showException($e);
            exit($this->returnError("0018","Error querying record")); // record no exist
        }
    }

    public function insertData($form){
        try{
            return parent::insertGenericData($form, PeopleModel::class, $this->creator);
        }catch(Exception $e){
            
            $this->showException($e);
            exit($this->returnError("0019","An error occurred while trying to create the record"));
        }
    }

    public function updateData($form, $id){
        try{
            $result = parent::getByIdGeneric($id,$this->table,$this->identificator, $this->delete);
            if(!empty($result)){
                parent::updateGenericData($form, $id, PeopleModel::class, $this->identificator, $this->delete);
                return true;
            }else{
                exit($this->returnError("0084","The record not exist"));
            }
        }catch(Exception $e){
            $this->showException($e);
            exit($this->returnError("0017", "An error occurred while updating data")); //Se produjo un error al actualizar datos
        }
    }

    public function deleteById($id){
        try{
            $result = parent::getByIdGeneric($id,$this->table,$this->identificator, $this->delete);
            if(!empty($result)){
                parent::deleteGenericData($id,PeopleModel::class,$this->identificator, $this->delete);
                return true;
            }else{
                exit($this->returnError("0084","The record not exist")); // record no exist
            }
        }catch(Exception $e){
            $this->showException($e);
            exit($this->returnError("0020", "An error occurred while trying to delete the record"));
        }
    }

    public function getDataPeopleWithPets(){
        try {
            $sql = "SELECT 
                    p.id AS person_id,
                    p.person_name,
                    p.person_lastname,
                    p.person_email,
                    pet.id AS pet_id,
                    pet.pet_name,
                    pet.pet_specie,
                    pet.pet_breed,
                    pet.pet_age
                FROM people p
                INNER JOIN pets pet ON pet.person_id = p.id
                WHERE p.person_delete = 0";

            return DB::select($sql);
        } catch (Exception $e) {
            $this->showException($e);
            exit($this->returnError("0018", "Error querying record"));  // Error si no se encuentra el registro
        }
    }
}
