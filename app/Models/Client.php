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

    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['surnom'])) {
            $query->where('surnom', 'like', '%' . $filters['surnom'] . '%');
        } 

        if (!empty($filters['adresse'])) {
            $query->where('adresse', 'like', '%' . $filters['adresse'] . '%');
        }

        if (!empty($filters['telephone'])) {
            $query->where('telephone', 'like', '%' . $filters['telephone'] . '%');
        }

        if (!empty($filters['comptes'])) {
            if ($filters['comptes'] === 'oui') {
                $query->whereHas('user');
            } elseif ($filters['comptes'] === 'non') {
                $query->doesntHave('user');
            }
        }

        if (!empty($filters['active'])) {
            $query->whereHas('user', function ($query) use ($filters) {
                $query->where('bloquer', $filters['active'] === 'non');
            });
        }

        if (!empty($filters['sort_by'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_order'] ?? 'asc');
        }

        return $query;
    }
}
