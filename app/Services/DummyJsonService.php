<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class DummyJsonService
{
    protected $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    public function getCategories(){
        $response = $this->client->request('GET', 'https://dummyjson.com/products/categories', [
            'limit' => 10,
        ]);
        if ($response->getStatusCode() == 200) {
            return $categories = json_decode($response->getBody()->getContents());
        }
    }
}