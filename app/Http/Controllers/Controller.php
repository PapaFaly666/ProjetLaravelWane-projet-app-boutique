<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use OpenApi\Attributes as OA;

#[
    OA\Info(version:"1.0.0", description:"Fusion Center Documentation", title:"Fusion Center Documentation"),
    OA\Server(url:"http://127.0.0.1:8000/api", description:"Local server"),
    OA\Server(url:"http://staging:exemple.com", description:"staging server"),
    OA\Server(url:"http://exemple.com", description:"production server"),
    OA\SecurityScheme(securityScheme:"bearerAuth", type:"http",name:"Authorization", in:"header", scheme:"bearer"),

]

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
