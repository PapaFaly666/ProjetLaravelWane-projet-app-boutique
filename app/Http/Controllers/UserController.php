<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\CustumPassword;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Traits\ResponseTrait;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         example="john.doe@example.com"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-01-01T00:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-01-01T00:00:00Z"
 *     )
 * )
 */

class UserController extends Controller
{
    use ResponseTrait;


      /**
     * @OA\Post(
     *     path="/v1/users",
     *     summary="Create a new user",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"prenom", "nom", "email", "password", "role"},
     *                 @OA\Property(property="prenom", type="string", example="John"),
     *                 @OA\Property(property="nom", type="string", example="Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="password", type="string", example="Password123"),
     *                 @OA\Property(property="role", type="string", example="admin"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="Utilisateur créé avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=422),
     *             @OA\Property(property="message", type="string", example="Erreur de validation"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Erreur du serveur")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'prenom' => 'required|string|max:50',
                'nom' => 'required|string|max:50',
                'email' => 'required|email|unique:users',
                'password' => ['required', 'string', 'min:8', 'confirmed', new CustumPassword()],
                'role' => 'required|string|in:admin,boutiquier',
            ]);

            $user = User::create([
                'prenom' => $validated['prenom'],
                'nom' => $validated['nom'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => $validated['role'],
            ]);

            return $this->sendResponse(201, 'Utilisateur créé avec succès', $user);
        } catch (ValidationException $e) {
            return $this->sendResponse(422, 'Erreur de validation', $e->errors());
        } catch (Exception $e) {
            return $this->sendResponse(500, 'Erreur du serveur', $e->getMessage());
        }
    }


     /**
     * @OA\Get(
     *     path="/v1/users",
     *     summary="Retrieve a list of users",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         description="Filter users by role (admin or boutiquier)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"admin", "boutiquier"})
     *     ),
     *     @OA\Parameter(
     *         name="active",
     *         in="query",
     *         description="Filter users by active status (oui or non)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"oui", "non"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response with user data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Données reçues avec succès"),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="users",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/User")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid query parameter value",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Valeur du paramètre active invalide"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Erreur du serveur"),
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function index(Request $request){
        try {
            $query = User::query();
    
            // Filtrage par rôle uniquement si le paramètre `role` est présent
            if ($request->has('role')) {
                $role = $request->query('role');
                if (in_array($role, ['admin', 'boutiquier'])) {
                    $query->where('role', $role);
                }
            }
    
            // Filtrage par statut actif uniquement si le paramètre `active` est présent
            if ($request->has('active')) {
                $active = $request->query('active');
                if (in_array($active, ['oui', 'non'])) {
                    $isActive = $active === 'oui' ? true : false; // Assurez-vous que 'oui' signifie actif et 'non' signifie bloqué
                    $query->where('bloquer', !$isActive);
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Valeur du paramètre active invalide',
                        'success' => false,
                    ], 400);
                }
            }
    
            // Exécution de la requête
            $users = $query->get();
    
            return response()->json([
                'status' => 200,
                'message' => 'Données reçues avec succès',
                'success' => true,
                'data' => [
                    'users' => $users,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Erreur du serveur',
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    

      /**
     * @OA\Delete(
     *     path="/v1/users/{id}",
     *     summary="Delete a user by ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User successfully deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Utilisateur supprimé avec succès"),
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Utilisateur non trouvé"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Erreur du serveur"),
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */

    public function delete($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return $this->sendResponse(404, 'Utilisateur non trouvé');
            }

            $user->delete();

            return $this->sendResponse(200, 'Utilisateur supprimé avec succès');
        } catch (Exception $e) {
            return $this->sendResponse(500, 'Erreur du serveur', $e->getMessage());
        }
    }
}
