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

    public function testUserGetAll(): void
    {
        $this->jwtToken = self::userLoggedIn();

        $response = static::createClient()->request('GET', '/api/users', ['auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains([
            "@context" => "/api/contexts/User",
            "@id" => "/api/users",
            "@type" => "hydra:Collection"
        ]);

        $this->assertMatchesResourceCollectionJsonSchema(User::class);

        $this->users = $response->toArray()['hydra:member'];

        //Verify user ownership by current Company
        $currentCompanyId = null;
        foreach ($this->users as $user) {
            if(!$currentCompanyId){ 
                $currentCompanyId = $user['company']['id'];
            }
            else{
                $this->assertEquals($currentCompanyId, $user['company']['id']);
            }
        }
    }

    public function testUserGetOne(): void
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
        ]);

        $response = static::createClient()->request('GET', '/api/users/' . $this->foundNotOwnedUserId(), ['auth_bearer' => $this->jwtToken]);

        $this->assertResponseStatusCodeSame(404);
        $this->assertJsonContains(['status' => 404, 'detail' => 'User Not Found']);
    }

    public function testDeleteUser(): void
    {
        $this->testUserGetAll();
        $first_user = array_shift($this->users);

        $response = static::createClient()->request('DELETE', '/api/users/' . $first_user['id'], ['auth_bearer' => $this->jwtToken]);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
            static::getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['id' => $first_user['id']])
        );

        $response = static::createClient()->request('GET', '/api/users/' . $this->foundNotOwnedUserId(), ['auth_bearer' => $this->jwtToken]);

        $this->assertResponseStatusCodeSame(404);
        $this->assertJsonContains(['status' => 404, 'detail' => 'User Not Found']);
    }

    public function testCreateUser() : void
    {   
        $this->jwtToken = self::userLoggedIn();

        $this->testUserGetAll();
        $user = array_shift($this->users);
        $companyId = $user['company']['id'];

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

    public function foundNotOwnedUserId() : int
    {
        $users = $this->users;

        $previousUserId = null;

        foreach($users as $user){
            $currentUserId = $user['id'];
            if($previousUserId && $previousUserId + 1 !== $currentUserId){
                return $previousUserId + 1;
            }
            $previousUserId = $currentUserId;
        }


    }
    
}
