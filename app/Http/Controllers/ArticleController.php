<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Traits\ResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;


/**
 * @OA\Schema(
 *     schema="ArticleResource",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="libelle",
 *         type="string",
 *         example="Article Exemple"
 *     ),
 *     @OA\Property(
 *         property="prixUnitaire",
 *         type="integer",
 *         example=1999
 *     ),
 *     @OA\Property(
 *         property="qteStock",
 *         type="integer",
 *         example=10
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
 *         example="2024-01-02T00:00:00Z"
 *     )
 * )
 */



class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use ResponseTrait;
/**
 * @OA\Get(
 *     path="/articles",
 *     summary="Lister les articles",
 *     description="Récupère la liste des articles, avec la possibilité de filtrer par disponibilité.",
 *     tags={"Articles"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="disponible",
 *         in="query",
 *         description="Filtre les articles par disponibilité (oui pour disponible, non pour non disponible).",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             enum={"oui", "non"}
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Liste des articles.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Liste des articles"),
 *             @OA\Property(property="data",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/ArticleResource")
 *             ),
 *             @OA\Property(property="success", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Pas d'articles disponibles.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="Pas d'articles disponibles"),
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


 public function index(Request $request)
 {
     $query = Article::query();
 
     if ($request->has('disponible')) {
         $disponible = $request->query('disponible');
         if ($disponible === 'oui') {
             $query->where('qteStock', '>', 0);  // Filtre pour les articles disponibles
         } elseif ($disponible === 'non') {
             $query->where('qteStock', '=', 0);  // Filtre pour les articles non disponibles
         }
     }
 
     $articles = $query->get();
 
     if ($articles->isEmpty()) {
         return $this->sendResponse(404, 'Pas d\'articles disponibles', null);
     } else {
         return $this->sendResponse(200, 'Liste des articles', ArticleResource::collection($articles));
     }
 }
 


   /**
 * @OA\Post(
 *     path="/articles",
 *     summary="Créer un nouvel article",
 *     description="Ajoute un nouvel article dans la base de données. Vérifie également si un article avec le même libellé existe déjà.",
 *     tags={"Articles"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"libelle", "prixUnitaire", "qteStock"},
 *             @OA\Property(property="libelle", type="string", example="Article Exemple", description="Libellé de l'article"),
 *             @OA\Property(property="prixUnitaire", type="integer", example=100, description="Prix unitaire de l'article"),
 *             @OA\Property(property="qteStock", type="integer", example=50, description="Quantité en stock de l'article")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Article ajouté avec succès.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Article ajouté avec succès"),
 *             @OA\Property(property="data", ref="#/components/schemas/ArticleResource"),
 *             @OA\Property(property="success", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=411,
 *         description="Le libellé existe déjà.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=411),
 *             @OA\Property(property="message", type="string", example="Le libellé existe déjà. Veuillez choisir un autre libellé."),
 *             @OA\Property(property="data", type="null"),
 *             @OA\Property(property="success", type="boolean", example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Erreur de validation.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=400),
 *             @OA\Property(property="message", type="string", example="Erreur de validation des données."),
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

    public function store(Request $request)
    {

        $request->validate([
            'libelle' => 'required|string|max:100',
            'prixUnitaire' => 'required|integer',
            'qteStock' => 'required|integer',
        ]);
    
        $existingArticle = Article::where('libelle', $request->input('libelle'))->first();
    
        if ($existingArticle) {

            return $this->sendResponse(411, 'Le libellé existe déjà. Veuillez choisir un autre libellé.', null);
        }
    
        $article = Article::create($request->all());
    
        return $this->sendResponse(200, 'Article ajouté avec succès', new ArticleResource($article));
    }


    public function updateStock(Request $request, $id){
        $request->validate([
            'qteStock' => 'required|integer|min:1',
        ]);

        $article = Article::find($id);
        if(!$article){
            return $this->sendResponse(411, "Article not found",'null');
        }

        $article->qteStock = $request->input('qteStock');
        $article->save();

        return $this->sendResponse(200, "Stock mis à jour", new ArticleResource($article));
    }
    


    /**
 * @OA\Post(
 *     path="/articles/libelle",
 *     summary="Récupère un article par son libellé",
 *     description="Retourne un article en fonction du libellé fourni. Le libellé doit être unique.",
 *     tags={"Articles"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"libelle"},
 *             @OA\Property(property="libelle", type="string", example="Article exemple", description="Libellé de l'article à rechercher")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Article trouvé.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Article trouvé"),
 *             @OA\Property(property="data", ref="#/components/schemas/ArticleResource"),
 *             @OA\Property(property="success", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=411,
 *         description="Article non trouvé.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=411),
 *             @OA\Property(property="message", type="string", example="Article not found"),
 *             @OA\Property(property="data", type="null"),
 *             @OA\Property(property="success", type="boolean", example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Erreur de validation.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=400),
 *             @OA\Property(property="message", type="string", example="Erreur de validation des données."),
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

    public function getByLibelle(Request $request){
        $request->validate([
            'libelle' => 'required|string|max:100',
        ]);

        $libelle = $request->input('libelle');
        $article = Article::where('libelle', $libelle)->first();
        if(!$article){
            return $this->sendResponse(411, "Article not found",'null');
        }

        return $this->sendResponse(200,"Article trouvé",new ArticleResource($article));
    }

    /**
 * @OA\Get(
 *     path="/articles/{id}",
 *     summary="Récupère un article par son ID",
 *     description="Retourne les détails d'un article spécifique en fonction de son ID.",
 *     tags={"Articles"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'article",
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Article trouvé.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Article trouvé"),
 *             @OA\Property(property="data", ref="#/components/schemas/ArticleResource"),
 *             @OA\Property(property="success", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Article non trouvé.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="Article not found"),
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

    public function show(string $id)
    {
        try{
            $article = Article::findOrFail($id);
            return new ArticleResource($article);
        }catch(ModelNotFoundException $e){
            return $this->sendResponse(404, "Article not found");
        }
        
    }

    /**
 * @OA\Put(
 *     path="/articles/{id}",
 *     summary="Modifier un article",
 *     description="Met à jour les informations d'un article existant en fonction de son ID. Les champs peuvent être modifiés partiellement.",
 *     tags={"Articles"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'article à modifier",
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="libelle",
 *                 type="string",
 *                 maxLength=100,
 *                 example="Article modifié"
 *             ),
 *             @OA\Property(
 *                 property="prixUnitaire",
 *                 type="integer",
 *                 example=150
 *             ),
 *             @OA\Property(
 *                 property="qteStock",
 *                 type="integer",
 *                 example=20
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Article modifié avec succès.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Article modifié avec succès"),
 *             @OA\Property(property="data", ref="#/components/schemas/ArticleResource"),
 *             @OA\Property(property="success", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Article non trouvé.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="Article not found"),
 *             @OA\Property(property="data", type="null"),
 *             @OA\Property(property="success", type="boolean", example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Requête invalide.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=400),
 *             @OA\Property(property="message", type="string", example="Invalid request data"),
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

    public function update(Request $request, string $id)
    {
        $request->validate([
            'libelle' => 'sometimes|required|string|max:100',
            'prixUnitaire' => 'sometimes|required|integer',
            'qteStock' => 'sometimes|required|integer',
        ]);
        try{
            $article = Article::findOrFail($id);
            $article->update($request->all());
            return $this->sendResponse(200,'Article modifié avec succès', new ArticleResource($article));
        }catch(ModelNotFoundException $e){
            return $this->sendResponse(404,'Article not found');
        }
    }
/**
 * @OA\Delete(
 *     path="/articles/{id}",
 *     summary="Supprimer un article",
 *     description="Supprime un article en fonction de son ID.",
 *     tags={"Articles"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'article à supprimer",
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Article supprimé avec succès.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Article supprimé avec succès"),
 *             @OA\Property(property="data", type="null"),
 *             @OA\Property(property="success", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Article non trouvé.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="Article non trouvé"),
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

    
    public function destroy(string $id)
{
    try {
        $article = Article::findOrFail($id);
        $article->delete(); 
        return $this->sendResponse(200, 'Article supprimé avec succès');
    } catch (ModelNotFoundException $e) {
        return $this->sendResponse(404, "Article non trouvé");
    }
}


/**
 * @OA\Post(
 *     path="/articles/stock",
 *     summary="Ajouter du stock à plusieurs articles",
 *     description="Permet d'ajouter du stock à plusieurs articles en une seule requête. Les articles non trouvés sont retournés dans la réponse.",
 *     tags={"Articles"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="articles",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="qteStock", type="integer", example=10)
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Stock ajouté avec succès.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Stock ajouté avec succès"),
 *             @OA\Property(property="data", type="null"),
 *             @OA\Property(property="success", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Certains articles sont incorrects.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=400),
 *             @OA\Property(property="message", type="string", example="Certains articles sont incorrects."),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="incorrect_articles",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="qteStock", type="integer", example=10)
 *                     )
 *                 )
 *             ),
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


    public function addStockArticle(Request $request)
    {
         $request->validate([
            'articles' => 'required|array',
            'articles.*.id' => 'required|integer',
            'articles.*.qteStock' => 'required|integer|min:1',
        ]);
    
        $updatedArticle = [];
        $incorrectArticles = [];
    
        foreach ($request->articles as $article) {
            $articleModel = Article::find($article['id']);
    
            if ($articleModel) {
                $articleModel->qteStock += $article['qteStock'];
                $articleModel->save();
                $updatedArticle[] = new ArticleResource($articleModel);
            } else {
                 $incorrectArticles[] = $article;
            }
        }
    
        if (!empty($incorrectArticles)) {
            return $this->sendResponse(400, 'Certains articles sont incorrects.', ['incorrect_articles' => $incorrectArticles]);
        }
    
         return $this->sendResponse(200, 'Stock ajouté avec succès', null);
    }


    /**
 * @OA\Post(
 *     path="/articles/restaurer/{id}",
 *     summary="Restaurer un article supprimé",
 *     description="Restaure un article qui a été supprimé (soft delete) en fonction de son ID.",
 *     tags={"Articles"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'article à restaurer",
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Article restauré avec succès.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Article restauré avec succès"),
 *             @OA\Property(property="data", type="null"),
 *             @OA\Property(property="success", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Article non trouvé.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="Article non trouvé"),
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

    public function restore(string $id)
{
    $article = Article::onlyTrashed()->find($id);

    if ($article) {
        $article->restore();
        return $this->sendResponse(200, 'Article restauré avec succès');
    }

    return $this->sendResponse(404, 'Article non trouvé');
}

    

}
