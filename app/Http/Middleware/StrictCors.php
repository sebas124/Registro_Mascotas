<?php

namespace App\Http\Middleware;

use Closure;

class StrictCors{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        $header = apache_request_headers();
        if( ( !isset($header['Authorization'])) ){
            $objData = array(
                'success'   => false,
                'message'   => '0071',
                'internMessage' => 'Unauthorized access',
            );
            echo json_encode($objData);
            exit(0);
        }
        if($request->getMethod() == 'OPTIONS'){
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
            header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization, authorization, Profile, profile, Location, location, Company, company");
            header('Access-Control-Allow-Credentials: true');
            exit(0);
        }else{
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
            header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization, authorization, Profile, profile, Location, location, Company, company");
            header("Allow: GET, POST, OPTIONS, PUT, DELETE");
        }
        return $next($request);
    }
}
