<?php
namespace App\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class UsersControllerTest extends WebTestCase
{
    public function testGetUsers()
    {
        $client = static::createClient();
        $client->request('GET', '/api/users', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'fixtureApiKey']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertCount(10, $arrayContent);
    }


    public function testGetUser()
    {
        $client = static::createClient();
        $client->request('GET', '/api/users/2', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'fixtureApiKey']);

        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
      //  $this->assertJson($content);
      //  $arrayContent = json_decode($content, true);
     //   $this->assertCount(1, $arrayContent);
    }
}