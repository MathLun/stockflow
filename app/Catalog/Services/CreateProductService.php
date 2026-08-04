<?php

namespace App\Catalog\Services;

use App\Models\Product;

class CreateProductService
{
	public function execute(array $data): Product 
	{
		return Product::create($data);
	}
}	
