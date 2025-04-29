<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Gestor de Productes API",
 *     description="API per a la gestió de productes amb autenticació, rols i documentació via Swagger.",
 *     @OA\Contact(
 *         email="joan@example.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Servidor local"
 * )
 */
class SwaggerController extends Controller
{
    // Aquí no hace falta ningún método, es solo para las anotaciones
}
