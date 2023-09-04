<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Http;

class DummyJsonService{
    public function getCategories(){
        $response = Http::get('https://dummyjson.com/products/categories', [
            'limit' => 10,
        ]);
    }
}