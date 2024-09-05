<?php

namespace App\Services;

interface ClientService{
    public function getAllClient();
    public function getClientById(string $id);
    public function createClient(array $data);
    public function updateClient(string $id, array $data);
    public function deleteClient(string $id);

    public function findByTelephoneClient($telephone);
}