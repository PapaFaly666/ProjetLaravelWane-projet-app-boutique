<?php 
namespace App\Services;

use App\Exceptions\ClientNotFoundException;
use App\Exceptions\ClientRepositoryException;
use App\Facades\ClientRepositoryFacade as ClientRepository;
use App\Mail\ClientQRCodeMail;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Client;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ClientServiceImpl implements ClientService{

    private $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
{
    $this->cloudinary = $cloudinary;
}

    public function getAllClient(){
        return ClientRepository::getAll();
    }

    public function getClientById($id){
        $client = ClientRepository::getById($id);
        if(!$client){
            throw new ClientRepositoryException();
        }
        return ClientRepository::getById($id);
    }
    

    public function createClient(array $data): Client
    {
        return DB::transaction(function () use ($data) {
            // Créer le client en appelant le dépôt, l'observer s'occupera du reste
            return ClientRepository::create([
                'surnom' => $data['surnom'],
                'telephone' => $data['telephone'],
                'adresse' => $data['adresse'],
            ]);
        });
    }

    

    public function updateClient(string $id,array $data){
        $client = ClientRepository::update($id, $data);
        if(!$client){
            throw new ClientRepositoryException();
        }
        return ClientRepository::update($id, $data);
    }

    public function deleteClient(string $id){
        return ClientRepository::delete($id);
    }

    public function findByTelephoneClient($telephone)
    {
        // Appeler le dépôt pour trouver le client par téléphone
        $client = ClientRepository::findByTelephone($telephone);

        if (!$client) {
            throw new ClientRepositoryException();
        }
        return $client;
    }
}