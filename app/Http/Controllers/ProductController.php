<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

/* Services */
use App\Catalog\Services\ListProductsService;
use App\Catalog\Services\CreateProductService;
use App\Catalog\Services\UpdateProductService;
use App\Catalog\Services\FindProductByIdService;
use App\Catalog\Services\DeleteProductService;

/* Requests */
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
	public function index(ListProductsService $service): JsonResponse
	{
		$products = $service->execute();
		return response()->json($products);
	}

	public function store(
		StoreProductRequest $request,
		CreateProductService $service): JsonResponse {
		$product = $service->execute($request->validated());
		return response()->json($product, 201);
	}

	public function update(
		UpdateProductRequest $request,
		int $id,
		UpdateProductService $service
	): JsonResponse {
		$product = $service->execute($id, $request->validated());
		return response()->json($product);
	}

	public function show(
		int $id,
		FindProductByIdService $service
	): JsonResponse {
		$product = $service->execute($id);
		return response()->json($product);
	}

	public function destroy(
		int $id,
		DeleteProductService $service
	): Response {
		$service->execute($id);
		return response()->noContent();
	}
}
