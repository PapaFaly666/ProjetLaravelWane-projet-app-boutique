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
        // Validation des données utilisateur
        $validator = Validator::make($data['users'], [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation de l'image
        ]);

        if ($validator->fails()) {
            // Gérer les erreurs de validation
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        // Créer le client
        $client = ClientRepository::create([
            'surnom' => $data['surnom'],
            'telephone' => $data['telephone'],
            'adresse' => $data['adresse'],
        ]);

        // Vérifier si l'image est présente dans les données
        if (isset($data['users']['image']) && $data['users']['image']->isValid()) {
            try {
                // Télécharger l'image sur Cloudinary
                $uploadedImage = Cloudinary::upload($data['users']['image']->getRealPath())->getSecurePath();
            } catch (\Exception $e) {
                // Gérer les erreurs de téléchargement
                throw new \Exception('Erreur lors du téléchargement de l\'image : ' . $e->getMessage());
            }
        }

        // Vérifier si les données utilisateur sont fournies
        if (isset($data['users'])) {
            $user = new User();
            $user->email = $data['users']['email'];
            $user->password = Hash::make($data['users']['password']);
            $user->role = 'client';
            $user->nom = $data['users']['nom'];
            $user->prenom = $data['users']['prenom'];
            $user->image_url = isset($uploadedImage) ? $uploadedImage : null; // Associer l'URL de l'image à l'utilisateur
            $user->client_id = $client->id; // Associer l'utilisateur au client
            $user->save();

            // Mettre à jour l'ID utilisateur dans le client
            $client->user_id = $user->id;
            $client->save();

            // Générer le QR code
            try {
                $qrCode = QrCode::format('png')->size(200)->generate($client->telephone);
                $qrCodeBase64 = base64_encode($qrCode);
            } catch (\Exception $e) {
                // Gérer les erreurs de génération du QR code
                throw new \Exception('Erreur lors de la génération du QR code : ' . $e->getMessage());
            }

            // Envoyer le QR code par e-mail
            try {
                Mail::to($user->email)->send(new ClientQRCodeMail($user, $qrCodeBase64));
            } catch (\Exception $e) {
                // Gérer les erreurs d'envoi d'email
                throw new \Exception('Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
            }
        }

        return $client;
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