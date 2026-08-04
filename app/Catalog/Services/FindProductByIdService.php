<?php

namespace App\Catalog\Services;

use App\Models\Product;

class FindProductByIdService
{
	public function execute(int $id): Product
	{
		return Product::findOrFail($id);
	}
}
