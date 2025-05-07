<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MyController;
use Illuminate\Http\Request;
use App\Models\User\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;

class LoginController extends MyController{

    private $model;
    private $validations;

    public function __construct(){
        parent::__construct();
        $this->initValidations();
        $this->model = new User();
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo usado para inicializar la validacion de campos en el controlador.
     */
    public function initValidations(){
        $this->validations = [
            'id'                => 'prohibited',
            'name'              => 'string|max:255',
            'email'             => 'required|string|max:255',
            'password'          => 'required|string|max:255',
            'created_at'        => 'prohibited',
            'updated_at'        => 'prohibited',
        ];
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * Realiza el login y devuelve el token para acceder a la API.
     * @param \Illuminate\Http\Request $request
     */
    public function login(Request $request) {
        // Validar los datos
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Buscar usuario
        $user  = $this->model->getUser($request->email);
        // Verificar si el usuario existe y contraseña es correcta
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Credenciales inválidas'], 401);
        }

        // Crear payload
        $payload = [
            'sub' => $user->id,
            'email' => $user->email,
            'expire' => encrypt(time() + (60 * 60 * 24)) // Expira en 24h (encriptado para coincidir con tu middleware)
        ];

        // Generar token
        $token = JWT::encode($payload, env('KEY_ACCESS'), 'HS256');

        return response()->json([
            'success' => true,
            'token' => $token
        ]);
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * Crea un nuevo usuario
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {

        // Validar los datos del formulario de registro
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        // Verificar si ya existe el correo
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'El correo ya está registrado'
            ], 409); 
        }

        // Crear el nuevo usuario
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password); // Encriptamos la contraseña
        $user->save();

        // Responder con el usuario creado
        return response()->json([
            'success' => true,
            'message' => 'Usuario creado con éxito',
            'user' => $user
        ]);
    }
}
