<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Http\Resources\UserResource;
use App\Models\Client;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Facades\ClientServiceFacade as ClientService;



/**
 * @OA\Schema(
 *     schema="ClientResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="surnom", type="string", example="Doe"),
 *     @OA\Property(property="adresse", type="string", example="123 Rue Exemple"),
 *     @OA\Property(property="telephone", type="string", example="1234567890")
 * )
 */
class ClientController extends Controller
{
    use ResponseTrait;

    

    /**
 * @OA\Get(
 *     path="/clients",
 *     summary="Liste des clients",
 *     tags={"Clients"},
 *     @OA\Parameter(
 *         name="surnom",
 *         in="query",
 *         description="Filtrer par surnom",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="adresse",
 *         in="query",
 *         description="Filtrer par adresse",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="telephone",
 *         in="query",
 *         description="Filtrer par téléphone",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Liste des clients récupérée avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Clients récupérés avec succès"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/ClientResource")
 *             ),
 *             @OA\Property(property="success", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Aucun client trouvé",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="Aucun client trouvé"),
 *             @OA\Property(property="success", type="boolean", example=false)
 *         )
 *     )
 * )
 */

 public function index(Request $request)
 {
        $filters = $request->only(['surnom', 'adresse', 'telephone', 'comptes', 'active', 'sort_by', 'sort_order']);
         
        $isFiltered = false;
        foreach ($filters as $key => $value) {
             if (!empty($value)) {
                 $isFiltered = true;
                 break;
             }
        }
        if (!$isFiltered) {
             return ClientResource::collection(ClientService::getAllClient());
        }
        $clients = Client::filter($filters)->with('user')->paginate(5);
        return ClientResource::collection($clients);
 }
 
      /**
     * @OA\Post(
     *     path="/clients/telephone",
     *     summary="Search client by phone number",
     *     tags={"Clients"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"telephone"},
     *             @OA\Property(property="telephone", type="string", example="1234567890")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Client trouvé avec succès"),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/ClientResource"
     *             ),
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Client not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Client non trouvé."),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid phone number",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Numéro de téléphone invalide."),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Erreur du serveur"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         )
     *     )
     * )
     */

     public function searchParTelephone(Request $request)
     {
         $request->validate([
             'telephone' => 'required|string'
         ]);
         $telephone = $request->input('telephone');
 
         $client = ClientService::findByTelephoneClient($telephone);
 
         if ($client instanceof JsonResponse) {
             return $client;
         }
        return new ClientResource($client);
     }
 


    /**
     * @OA\Get(
     *     path="/clients/{id}",
     *     summary="Get a specific client by ID",
     *     tags={"Clients"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Client ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Client trouvé avec succès"),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/ClientResource"
     *             ),
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Client not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Client non trouvé."),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Erreur du serveur"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         )
     *     )
     * )
     */
    
    public function show($id)
    {
            $client = ClientService::getClientById($id);
            return new ClientResource($client);
    }


    /**
 * @OA\Post(
 *     path="/clients",
 *     summary="Create a new client",
 *     tags={"Clients"},
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"surnom", "adresse", "telephone"},
 *             @OA\Property(property="surnom", type="string", example="Doe"),
 *             @OA\Property(property="adresse", type="string", example="123 Rue Exemple"),
 *             @OA\Property(property="telephone", type="string", example="775933399"),
 *             @OA\Property(property="users", type="object",
 *                 @OA\Property(property="email", type="string", example="example@example.com"),
 *                 @OA\Property(property="password", type="string", example="p@ssword123"),
 *                 @OA\Property(property="nom", type="string", example="John"),
 *                 @OA\Property(property="prenom", type="string", example="Doe")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Client created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=201),
 *             @OA\Property(property="message", type="string", example="Client créé avec succès"),
 *             @OA\Property(
 *                 property="data",
 *                 ref="#/components/schemas/ClientResource"
 *             ),
 *             @OA\Property(property="success", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input data",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=400),
 *             @OA\Property(property="message", type="string", example="Données d'entrée invalides."),
 *             @OA\Property(property="success", type="boolean", example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=500),
 *             @OA\Property(property="message", type="string", example="Erreur du serveur"),
 *             @OA\Property(property="success", type="boolean", example=false)
 *         )
 *     )
 * )
 */

 public function store(StoreClientRequest $request)
{
    $client = ClientService::createClient($request->all());
    return new ClientResource($client);
}


    /**
 * @OA\Put(
 *     path="/clients/{id}",
 *     summary="Update an existing client",
 *     tags={"Clients"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Client ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"surnom", "adresse", "telephone"},
 *             @OA\Property(property="surnom", type="string", example="Doe"),
 *             @OA\Property(property="adresse", type="string", example="123 Rue Exemple"),
 *             @OA\Property(property="telephone", type="string", example="764829933")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Client updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Client mis à jour avec succès"),
 *             @OA\Property(
 *                 property="data",
 *                 ref="#/components/schemas/ClientResource"
 *             ),
 *             @OA\Property(property="success", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Client not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="Client non trouvé."),
 *             @OA\Property(property="success", type="boolean", example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input data",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=400),
 *             @OA\Property(property="message", type="string", example="Données d'entrée invalides."),
 *             @OA\Property(property="success", type="boolean", example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=500),
 *             @OA\Property(property="message", type="string", example="Erreur du serveur"),
 *             @OA\Property(property="success", type="boolean", example=false)
 *         )
 *     )
 * )
 */


 public function update(UpdateClientRequest $request, int $id)
 {
         $client =  ClientService::updateClient($id, $request->validated());
         return new ClientResource($client);
 }


/**
 * @OA\Delete(
 *     path="/clients/{id}",
 *     summary="Delete a specific client",
 *     tags={"Clients"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Client ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Client deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Client supprimé avec succès"),
 *             @OA\Property(property="success", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Client not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="Client non trouvé."),
 *             @OA\Property(property="success", type="boolean", example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=500),
 *             @OA\Property(property="message", type="string", example="Erreur du serveur"),
 *             @OA\Property(property="success", type="boolean", example=false)
 *         )
 *     )
 * )
 */


    public function destroy(int $id): JsonResponse
    {
        try {
            $client = Client::findOrFail($id);

            $client->delete();

            return $this->sendResponse(200, 'Client supprimé avec succès');

        } catch (ModelNotFoundException $e) {
            return $this->sendResponse(404, 'Client non trouvé', $e->getMessage());
        } catch (Exception $e) {
            return $this->sendResponse(500, 'Erreur du serveur', $e->getMessage());
        }
    }

/**
 * @OA\Post(
 *     path="/clients/{clientId}/dettes",
 *     summary="Lister les dettes d'un client",
 *     description="Récupère les informations d'un client et la liste de ses dettes.",
 *     tags={"Dettes"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="clientId",
 *         in="path",
 *         required=true,
 *         description="ID du client",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Client trouvé.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Client trouvé."),
 *             @OA\Property(property="data", type="object",
 *                 oneOf={
 *                     @OA\Schema(type="null", description="Aucune dette"),
 *                     @OA\Schema(
 *                         @OA\Property(property="client", ref="#/components/schemas/ClientResource"),
 *                         @OA\Property(property="dettes", type="array",
 *                             @OA\Items(ref="#/components/schemas/Dette")
 *                         ),
 *                     )
 *                 }
 *             ),
 *             @OA\Property(property="success", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Client non trouvé.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="Client non trouvé."),
 *             @OA\Property(property="data", type="null"),
 *             @OA\Property(property="success", type="boolean", example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erreur du serveur.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=500),
 *             @OA\Property(property="message", type="string", example="Erreur du serveur."),
 *             @OA\Property(property="error", type="string", example="Détails de l'erreur."),
 *             @OA\Property(property="success", type="boolean", example=false)
 *         )
 *     )
 * )
 */

    public function listerDettes($clientId): JsonResponse
{
    try {
        // Récupérer le client avec ses dettes
        $client = Client::with('dettes')->find($clientId);

        if (!$client) {
            return response()->json([
                'status' => 404,
                'message' => 'Client non trouvé.',
                'data' => null,
                'success' => false,
            ], 404);
        }

        // Si le client n'a pas de dettes
        if ($client->dettes->isEmpty()) {
            return response()->json([
                'status' => 200,
                'message' => 'Client trouvé, aucune dette.',
                'data' => null,
                'success' => true,
            ], 200);
        }

        // Retourner les informations du client avec ses dettes
        return response()->json([
            'status' => 200,
            'message' => 'Client trouvé.',
            'data' => [
                'client' => new ClientResource($client),
                'dettes' => $client->dettes,
            ],
            'success' => true,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'message' => 'Erreur du serveur.',
            'error' => $e->getMessage(),
            'success' => false,
        ], 500);
    }
}


/**
 * @OA\Post(
 *     path="/clients/{clientId}/user",
 *     summary="Afficher les informations de l'utilisateur associé à un client",
 *     description="Récupère les informations de l'utilisateur associé à un client spécifique.",
 *     tags={"Utilisateurs"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="clientId",
 *         in="path",
 *         required=true,
 *         description="ID du client",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Utilisateur trouvé.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Utilisateur trouvé."),
 *             @OA\Property(property="data", ref="#/components/schemas/UserResource"),
 *             @OA\Property(property="success", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Non autorisé.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=401),
 *             @OA\Property(property="message", type="string", example="Non autorisé. Veuillez vous connecter."),
 *             @OA\Property(property="data", type="null"),
 *             @OA\Property(property="success", type="boolean", example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Client ou utilisateur non trouvé.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="Client ou utilisateur non trouvé."),
 *             @OA\Property(property="data", type="null"),
 *             @OA\Property(property="success", type="boolean", example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erreur du serveur.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=500),
 *             @OA\Property(property="message", type="string", example="Erreur du serveur."),
 *             @OA\Property(property="error", type="string", example="Détails de l'erreur."),
 *             @OA\Property(property="success", type="boolean", example=false)
 *         )
 *     )
 * )
 */



public function afficherCompteUser(Request $request, $clientId): JsonResponse
{
    $client = Client::find($clientId);

    if (!$client || !$client->user) {
        return response()->json([
            'status' => 404,
            'message' => 'Client ou utilisateur non trouvé.',
            'data' => null,
            'success' => false,
        ], 404);
    }

    return response()->json([
        'status' => 200,
        'message' => 'Utilisateur trouvé.',
        'data' => $client->user,
        'success' => true,
    ], 200);
}




    private function conditionNotAccomplished(): bool
    { 
        return false;
    }
}
