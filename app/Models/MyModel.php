<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class MyModel extends Model{
    abstract protected function insertData($form);
    abstract protected function updateData($form, $id);
    abstract protected function deleteById($id);

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Maneja el envio de errores a frontend.
     */
    protected function returnError($msg,$internMessage="",$errorCode="400"){
        $end = microtime(true);
        $time = $end - $this->start;
        $time = $time.SECONDS;
        if(is_array($msg)){
            $objData = array(
                'success'       => false,
                'message'       => $msg['code'],
                'internMessage' => $msg['message'],
                'code'          => $errorCode,
                'duration'      => $time
            );
            return json_encode($objData);
        }
        $objData = array(
            'success'       => false,
            'message'       => $msg,
            'internMessage' => $internMessage,
            'code'          => $errorCode,
            'duration'      => $time
        );
        return json_encode($objData);
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Maneja si muestra o no el mensaje de error de la excepcion.
     */
    protected function showException($e){
        if(env("APP_ENV") == "local"){
            print_r($e->getMessage());
        }
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo usado para devolver un elemento por id desde DB
     * @param object form - data a insertar en tabla
     * @param int user - Quien inserta el registro
     * @param string table - tabla a insertar los datos
     * @param string creator - nombre del campo de quien crea el registro.
     */
    public function getByIdGeneric($id, $table, $identificator, $delete){
        $sql = "SELECT $identificator from $table where $identificator = $id and $delete = 0";
        return DB::select($sql);
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Funcion usada para almacenar datos en DBs.
     * @param object form - data a insertar en tabla
     * @param string table - tabla a insertar los datos
     */
    public function insertGenericData($form, $model, $created) {
        $form[$created] = now();

        $filteredData = array_filter($form, function($value) {
            return $value !== '';
        });

        return $model::create($filteredData);
    }


    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo usado para actualizar datos en DB.
     * @param object form - data a insertar en tabla
     * @param int id - id del registro a modificar
     * @param string table - tabla a insertar los datos
     * @param string identificator - nombre del campo que identifica el registro en la tabla
     * @param string modifietAt - nombre del campo que actualiza la fecha de modificacion.
     */
    public function updateGenericData($form, $id, $model, $identificator, $delete) {

        $form['updated_at'] = now();

        return $model::where($identificator, $id)
            ->where($delete, 0)
            ->update($form);
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo usado para actualizar datos en DB.
     * @param int id - id del registro a modificar
     * @param string table - tabla a insertar los datos
     * @param string identificator - nombre del campo que identifica el registro en la tabla
     * @param string modifietAt - nombre del campo que actualiza la fecha de modificacion.
     */
    public function deleteGenericData($id, $model, $identificator, $delete) {
        return $model::where($identificator, $id)
            ->where($delete, 0)
            ->update([
                $delete => 1,
            ]);
    }

}
