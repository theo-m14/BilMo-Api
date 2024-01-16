<?php

namespace App\Tests;

use App\Entity\User;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class UserTest extends ApiTestCase
{
    private string $jwtToken;

    private array $users;

    public static function userLoggedIn(): string
    {
        $response = static::createClient()->request('POST', '/api/login_check', ['json' => [
            'username' => 'user0@example.com',
            'password' => 'password',
        ]]);
        return $response->toArray()['token'];
    }

    public function testUserGetAll(int $nbOfUsers = 10): void
    {
        $this->jwtToken = self::userLoggedIn();

        $response = static::createClient()->request('GET', '/api/users', ['auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains([
            "@context" => "/api/contexts/User",
            "@id" => "/api/users",
            "@type" => "hydra:Collection",
            "hydra:totalItems" => $nbOfUsers
        ]);

        $this->assertCount($nbOfUsers, $response->toArray()['hydra:member']);

        $this->assertMatchesResourceCollectionJsonSchema(User::class);

        $this->users = $response->toArray()['hydra:member'];
    }

    public function testProductGetOne(): void
    {
        $this->testUserGetAll();
        $first_user = array_shift($this->users);

        $response = static::createClient()->request('GET', '/api/users/' . $first_user['id'], ['auth_bearer' => $this->jwtToken]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            "@context" => "/api/contexts/User",
            "@id" => "/api/users/" . $first_user['id'],
            "@type" => "User",
            'id' => $first_user['id'],
            'last_name' => $first_user['last_name'],
            'first_name' => $first_user['first_name'],
            'email' => $first_user['email'],
            'password' => $first_user['password']
        ]);
    }

    public function testCreateUser() : void
    {   
        $this->jwtToken = self::userLoggedIn();

        $password = password_hash('testpassword', PASSWORD_DEFAULT);
        
        $response = static::createClient()->request('POST', '/api/users', ['json' => [
            'email' => 'test@email.com',
            'last_name' => 'Clinton',
            'first_name' => 'Margaret',
            'password' => $password,
        ],'auth_bearer' => $this->jwtToken,'headers' => ['content-type' => 'application/ld+json']]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@type' => 'User',
            'email' => 'test@email.com',
            'last_name' => 'Clinton',
            'first_name' => 'Margaret',
            'password' => $password
        ]);
        $this->assertMatchesRegularExpression('~^/api/users/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(User::class);
    }

    public function testCreateInvalidUser(): void
    {
        $this->jwtToken = self::userLoggedIn();

        $response = static::createClient()->request('POST', '/api/users', ['json' => [],'auth_bearer' => $this->jwtToken,'headers' => ['content-type' => 'application/ld+json']]);

        $this->assertJsonContains([
            'hydra:title' => 'An error occurred',
        ]);
    }
    
}
