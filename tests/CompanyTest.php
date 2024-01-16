<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class CompanyTest extends ApiTestCase
{
    private string $jwtToken;

    public static function userLoggedIn(): string 
    {
        $response = static::createClient()->request('POST', '/api/login_check', ['json' => [
            'username' => 'user0@example.com',
            'password' => 'password',
        ]]);
        return $response->toArray()['token'];
    }

    public function testUnreachableApiWithoutAuth(): void
    {
        $response = static::createClient()->request('GET', '/api', ['headers' => ['Accept' => 'application/json']]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);
    }

    public function testAuth() : void
    {
        $this->jwtToken = self::userLoggedIn();
        
        $response = static::createClient()->request('GET', '/api', ['auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
    }
}
