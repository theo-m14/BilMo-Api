<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Product;

class ProductTest extends ApiTestCase
{
    private string $jwtToken;

    private array $products;

    public static function userLoggedIn(): string
    {
        $response = static::createClient()->request('POST', '/api/login_check', ['json' => [
            'username' => 'user0@example.com',
            'password' => 'password',
        ]]);
        return $response->toArray()['token'];
    }

    public function testProductGetAll(int $nbOfProducts = 30): void
    {
        $this->jwtToken = self::userLoggedIn();

        $response = static::createClient()->request('GET', '/api/products', ['auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains([
            "@context" => "/api/contexts/Product",
            "@id" => "/api/products",
            "@type" => "hydra:Collection",
            "hydra:totalItems" => $nbOfProducts
        ]);

        $this->assertCount($nbOfProducts, $response->toArray()['hydra:member']);

        $this->assertMatchesResourceCollectionJsonSchema(Product::class);

        $this->products = $response->toArray()['hydra:member'];
    }

    public function testProductGetOne(): void
    {
        $this->testProductGetAll();
        $first_product = array_shift($this->products);

        $response = static::createClient()->request('GET', '/api/products/' . $first_product['id'], ['auth_bearer' => $this->jwtToken]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            "@context" => "/api/contexts/Product",
            "@id" => "/api/products/" . $first_product['id'],
            "@type" => "Product",
            'id' => $first_product['id'],
            'brand' => $first_product['brand'],
            'name' => $first_product['name'],
            'description' => $first_product['description'],
            'price' => $first_product['price']
        ]);
    }
}
