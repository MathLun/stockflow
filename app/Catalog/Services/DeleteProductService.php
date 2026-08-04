<?php

namespace App\Catalog\Services;

use App\Models\Product;

class DeleteProductService
{
	public function execute(int $id): void
	{
		$product = Product::findOrFail($id);
		$product->delete();
	}
}
