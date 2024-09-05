<?php
namespace App\Facades;
use Illuminate\Support\Facades\Facade;
class ClientRepositoryFacade extends Facade{
    public static function getFacadeAccessor(){
        return 'client_repository';
    }
}