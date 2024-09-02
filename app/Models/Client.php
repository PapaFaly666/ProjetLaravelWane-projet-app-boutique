<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    public function user(){
        return $this->hasOne(User::class);
    }

    protected $fillable = ['surnom','telephone','adresse'];

    public function dettes(){
        return $this->hasMany(Dette::class);
    }
}
