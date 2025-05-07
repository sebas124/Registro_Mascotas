<?php

namespace App\Models\Pets;

use Exception;
use App\Models\MyModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetsModel extends MyModel{

    use HasFactory;
     
    protected $table              = "pets";
    protected $identificator      = "id";
    protected $delete             = "pet_delete";
    protected $creator            = "created_at";
    protected $fillable           = ['pet_name', 'pet_specie', 'pet_breed', 'pet_age', 'person_id'];

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
            return parent::insertGenericData($form, PetsModel::class, $this->creator);
        }catch(Exception $e){
            
            $this->showException($e);
            exit($this->returnError("0019","An error occurred while trying to create the record"));
        }
    }

    public function updateData($form, $id){
        try{
            $result = parent::getByIdGeneric($id,$this->table,$this->identificator, $this->delete);
            if(!empty($result)){
                parent::updateGenericData($form, $id, PetsModel::class, $this->identificator, $this->delete);
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
                parent::deleteGenericData($id,PetsModel::class,$this->identificator, $this->delete);
                return true;
            }else{
                exit($this->returnError("0084","The record not exist")); // record no exist
            }
        }catch(Exception $e){
            $this->showException($e);
            exit($this->returnError("0020", "An error occurred while trying to delete the record"));
        }
    }
}
