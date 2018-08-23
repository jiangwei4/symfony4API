<?php
namespace App\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class UsersControllerTest extends WebTestCase
{
    ///////////////////////////////////////////////tous les utilisateurs ////////////////////////////////////////
    public function testGetUsersAdmin()
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

    public function testGetUsersUser()
    {
        $client = static::createClient();
        $client->request('GET', '/api/users', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'apiKey2']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not Logged for this user or not an Admin",$arrayContent);
    }

    public function testGetUsersNotLogged()
    {
        $client = static::createClient();
        $client->request('GET', '/api/users');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(496, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not Logged",$arrayContent);
    }
    /////////////////////////////////////////////// utilisateurs n°3 ////////////////////////////////////////
    public function testGetUsers3Admin()
    {
        $client = static::createClient();
        $client->request('GET', '/api/users/3', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'fixtureApiKey']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertCount(6, $arrayContent);
    }

    public function testGetUsers3User()
    {
        $client = static::createClient();
        $client->request('GET', '/api/users/3', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'apiKey2']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not Logged for this user or not an Admin",$arrayContent);
    }

    public function testGetUsers3NotLogged()
    {
        $client = static::createClient();
        $client->request('GET', '/api/users/3');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(496, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not Logged",$arrayContent);
    }
    ///////////////////////////////////////////////post ////////////////////////////////////////
    public function testPostUser(){
        $data = [
            "firstname" => "ffff",
            "lastname"=> "Ebert",
            "email"=> "aletha.fley@muller.com"
        ];

        $client = static::createClient();
        $client->request('POST', '/api/users', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertCount(6, $arrayContent);
    }
    public function testPostUserBlank(){
        $data = [

        ];

        $client = static::createClient();
        $client->request('POST', '/api/users', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($content);
        dump($arrayContent = json_decode($content, true));

    }
    ///////////////////////////////////////////////put ////////////////////////////////////////
    public function testPutUserAdmin(){
        $data = [
            "firstname" => "ffff",
            "lastname"=> "Ebert",
            "email"=> "aletha.fley@muller.com"
        ];

        $client = static::createClient();
        $client->request('PUT', '/api/users', [], [], ['HTTP_CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'fixtureApiKey'], json_encode($data));
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertCount(6, $arrayContent);
    }
    public function testPutUserUser(){

    }
    public function testPutUserNotLogged(){

    }

}