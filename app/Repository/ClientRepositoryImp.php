<?php
namespace App\Repository;

use App\Models\Client;

class ClientRepositoryImp implements ClientRepository{

    public function getAll(){
        return Client::all();
    }

    public function getById(string $id){
        return Client::find($id);
    }

    public function create(array $data){
        return Client::create($data);
    }

    public function update(string $id, array $data): ?Client
    {
        $client = Client::find($id);

        if (!$client) {
            return null;
        }

        $client->update($data);
        return $client;
    }

    public function delete(string $id){
        $client = Client::find($id);
        return $client->delete();
    }

    public function findByTelephone($telephone){
        $client = Client::where('telephone', $telephone)->with('user')->first();
        
        return $client;
    }
}