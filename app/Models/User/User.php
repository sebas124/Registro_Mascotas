<?php

namespace App\Models\User;

use Exception;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable {
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password'
    ];

    protected $hidden = [
        'password'
    ];

    /**
     * @author Sebastián Eeyes
     * @version 1.0.0
     * @internal Metodo usado para obtener informacion del usuario
    */
    public function getUser($email){
        try {
            return User::where('email', $email)->first(); // Usamos first() para obtener un solo objeto
        } catch(Exception $e) {
            // Manejo de excepciones si ocurre algún error
            exit($this->returnError("0018", "Error querying record"));
        }
    }
}
