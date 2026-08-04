<?php

namespace App\Catalog\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ListProductsService
{
	public function execute(): Collection
	{
		return Product::all();
	}
}
