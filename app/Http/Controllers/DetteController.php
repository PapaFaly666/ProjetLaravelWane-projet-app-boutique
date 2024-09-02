<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Dette",
 *     type="object",
 *     title="Dette",
 *     description="Modèle représentant une dette",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID de la dette",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="montant",
 *         type="number",
 *         format="float",
 *         description="Montant de la dette",
 *         example=100.50
 *     ),
 *     @OA\Property(
 *         property="date_creation",
 *         type="string",
 *         format="date-time",
 *         description="Date de création de la dette",
 *         example="2024-09-01T14:00:00Z"
 *     )
 * )
 */

class DetteController extends Controller
{
    //
}
