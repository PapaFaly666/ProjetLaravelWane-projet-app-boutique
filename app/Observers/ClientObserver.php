<?php

namespace App\Observers;

use App\Events\ClientCreated;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClientObserver
{
    public function creating(Client $client)
    {
        $data = request()->all();

        // Validation des données utilisateur
        $validator = Validator::make($data['users'], [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'nom' => 'required|string',
            'prenom' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        // Créer l'utilisateur associé
        $user = new User();
        $user->email = $data['users']['email'];
        $user->password = Hash::make($data['users']['password']);
        $user->role = 'client';
        $user->nom = $data['users']['nom'];
        $user->prenom = $data['users']['prenom'];
        $user->client_id = $client->id;
        $user->save();

        // Déclencher l'événement
        event(new ClientCreated($client, $user));
    }
}
