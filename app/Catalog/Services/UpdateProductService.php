<?php

namespace App\Catalog\Services;

use App\Models\Product;

class UpdateProductService
{
	public function execute(int $id, array $data): Product {
		$product = Product::findOrFail($id);
		$product->update($data);
		return $product->fresh();
	}
}
