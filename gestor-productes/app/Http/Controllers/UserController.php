<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user",
     *     summary="Obtener el usuario autenticado",
     *     tags={"Autenticación"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Información del usuario autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Joan Merino"),
     *             @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
     *             @OA\Property(property="role", type="string", example="admin"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-24T12:34:56Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-24T12:34:56Z")
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function show(Request $request)
    {
        return response()->json($request->user());
    }
}
