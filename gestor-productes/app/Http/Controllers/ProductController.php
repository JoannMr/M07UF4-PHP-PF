<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Listar todos los productos",
     *     description="Devuelve una lista de todos los productos disponibles.",
     *     tags={"Productos"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
     *     ),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function index()
    {
        return response()->json(Product::all());
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Crear un nuevo producto",
     *     description="Crea un producto (solo disponible para usuarios con rol admin).",
     *     tags={"Productos"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "price"},
     *             @OA\Property(property="name", type="string", example="Camiseta"),
     *             @OA\Property(property="description", type="string", example="Camiseta de algodón talla M"),
     *             @OA\Property(property="price", type="number", format="float", example=19.99)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Producto creado correctamente"),
     *     @OA\Response(response=401, description="No autorizado"),
     *     @OA\Response(response=403, description="No tienes permiso para crear productos"),
     *     @OA\Response(response=422, description="Datos inválidos")
     * )
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        return response()->json($product, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Actualizar un producto existente",
     *     description="Actualiza los datos de un producto existente (solo para admins).",
     *     tags={"Productos"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "price"},
     *             @OA\Property(property="name", type="string", example="Camiseta actualizada"),
     *             @OA\Property(property="description", type="string", example="Camiseta actualizada con nueva descripción"),
     *             @OA\Property(property="price", type="number", format="float", example=24.99)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Producto actualizado correctamente"),
     *     @OA\Response(response=401, description="No autorizado"),
     *     @OA\Response(response=403, description="No tienes permiso para actualizar productos"),
     *     @OA\Response(response=404, description="Producto no encontrado"),
     *     @OA\Response(response=422, description="Datos inválidos")
     * )
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());
        return response()->json($product);
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Eliminar un producto",
     *     description="Elimina un producto existente (solo para admins).",
     *     tags={"Productos"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Producto eliminado correctamente"),
     *     @OA\Response(response=401, description="No autorizado"),
     *     @OA\Response(response=403, description="No tienes permiso para eliminar productos"),
     *     @OA\Response(response=404, description="Producto no encontrado")
     * )
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
