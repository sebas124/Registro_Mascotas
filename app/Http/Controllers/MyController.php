<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Validator;
use stdClass;

define("SECONDS", " seconds");

class MyController extends Controller{
    protected $start;
    
    public function __construct(){
        $this->start = microtime(true);
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo que valida la peticion.
     * @param Request $request - peticion del usuario.
     * @param array $validations - validaciones que se desean ejecutar.
     * @param array $msg - mensajes personalizados de respuestas a errores de peticion
     */
    protected function validateRequest($request,$validations,$msg=[]){
        return Validator::make($request->all(),$validations,$msg);
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo que valida los datos.
     * @param array $data - Datos enviados por el usuario.
     * @param array $validations - validaciones que se desean ejecutar.
     * @param array $msg - mensajes personalizados de respuestas a errores de peticion
    */
    protected function validateData($data, $validations, $msg=[]){
        $dataArray = json_decode(json_encode($data), true); // Convertir a array plano
        return Validator::make($dataArray, $validations, $msg);
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo para retornar un mensaje al usuario no autorizado
     */
    protected function notPermission(){
        $end = microtime(true);
        $time = $end - $this->start;
        $time = $time.SECONDS;

        $objData = array(
            'success'   => false,
            'code'      => '0000',
            'message'   => "0000",
            'internMessage' => 'You do not have permissions',
            'duration'  => $time
        );
        return json_encode($objData);
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @param string $var
     * @internal Codifica $var
     */
    protected function encrypt($var){
        return base64_encode(base64_encode($var));
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @param string $var
     * @internal Decodifica $var
     */
    protected function desencrypt($var){
        return base64_decode(base64_decode($var));
    }

    /**
     * @author Sebastian Reyes
     * @version 1.1.0
     * @internal Maneja el envio de data a frontend.
     * @param array $data - informacion que se envia al frontend.
     * @param string $msg - mensaje adicional para cuando la respuesta es vacia.
     * @param array $addData - Informacion adicional que se envia a frontend, es informacion complementaria que no viaja en la llave data.
     */
    protected function returnData($data,$msg=null,$addData=[]){
        $end = microtime(true);
        $time = $end - $this->start;
        $time = $time.SECONDS;

        $internMessage = "";

        if(is_array($msg)){
            $internMessage = $msg['message'];
            $msg = $msg['code'];
        }

        if(!empty($data) || $addData != null){
            $objData = [
                "success" => true,
                "data"    => $data,
                "duration"=> $time
            ];

            foreach($addData as $key => $val){
                $objData[$key] = $val;
            }
            return json_encode($objData);
        }else{
            $objData = [
                'success'       => true,
                'data'          => [],
                'message'       => $msg,
                'internMessage' => $internMessage,
                'duration'      => $time
            ];
            return json_encode($objData);
        }
    }

    /**
     * @author Sebastian Reyes
     * @version 1.1.0
     * @internal Maneja el envio de data a frontend cuando un registro es creado.
     * @param array $data - informacion que se envia al frontend.
     * @param string $msg - mensaje adicional para cuando la respuesta es vacia.
     * @param string $internMessage - mensaje interno de referencia
     */
    public function returnCreated($data,$msg, $internMessage=""){
        $end = microtime(true);
        $time = $end - $this->start;
        $time = $time.SECONDS;

        if(is_array($msg)){
            $objData = array(
                'success'       => true,
                'message'       => $msg['code'],
                'internMessage' => $msg['message'],
                'data'          => $data[0],
                'duration'      => $time
            );
            return response()->json($objData,201);
        }

        $objData = array(
            'success'       => true,
            'message'       => $msg,
            'internMessage' => $internMessage,
            'data'          => $data[0],
            'duration'      => $time
        );
        return response()->json($objData,201);
    }

    /**
     * @author Sebastian Reyes
     * @version 1.1.0
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
            return response()->json($objData,$errorCode);
        }
        $objData = array(
            'success'       => false,
            'message'       => $msg,
            'internMessage' => $internMessage,
            'code'          => $errorCode,
            'duration'      => $time
        );
        return response()->json($objData,$errorCode);
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Maneja el envio de errores de peticion a frontend.
     */
    protected function returnErrorFields($data, $errorCode = 403){
        $end = microtime(true);
        $time = $end - $this->start;
        $time = $time.SECONDS;
        $objData = array(
            'success'       => false,
            'message'       => "0085",
            'internMessage' => "Invalid request",
            'data'          => $data,
            'code'          => $errorCode,
            'duration'      => $time
        );
        return response()->json($objData,$errorCode);
    }

    /**
     * @author Sebastian Reyes
     * @version 1.1.0
     * @internal Maneja el envio de mensajes a frontend.
     */
    protected function returnOk($msg,$internMessage=""){
        $end = microtime(true);
        $time = $end - $this->start;
        $time = $time.SECONDS;
        if(is_array($msg)){
            $objData = array(
                'success'       => true,
                'message'       => $msg['code'],
                'internMessage' => $msg['message'],
                'duration'      => $time
            );
            return response()->json($objData);
        }
        $objData = array(
            'success'       => true,
            'message'       => $msg,
            'internMessage' => $internMessage,
            'duration'      => $time
        );
        return json_encode($objData);
    }

    // /**
    //  * @author Sebastian Reyes
    //  * @version 1.0.0
    //  * @internal Maneja si muestra o no el mensaje de error de la excepcion.
    //  */
    // protected function showException($e){
    //     if(env("APP_ENV") == "local"){
    //         print_r($e->getMessage());
    //     }
    // }
}
