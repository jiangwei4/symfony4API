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
        $this->assertEquals(401, $response->getStatusCode());
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
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not Logged",$arrayContent);
    }
    public function testGetUsers3456NotLogged()
    {
        $client = static::createClient();
        $client->request('GET', '/api/users/3456');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(404, $response->getStatusCode());
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
       // dump($arrayContent = json_decode($content, true));
    }

    public function testPostUserMailExistant(){
        $data = [
            "firstname" => "ffff",
            "lastname"=> "Ebert",
            "email"=> "aletha.fley@muller.com"

        ];

        $client = static::createClient();
        $client->request('POST', '/api/users', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($content);
        // dump($arrayContent = json_decode($content, true));
    }
    ///////////////////////////////////////////////delete utilisateur n°3 ////////////////////////////////////////
    public function testDeleteNotlogged(){
        $client = static::createClient();
        $client->request('DELETE', '/api/users/3', [], [], ['CONTENT_TYPE' => 'application/json']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not Logged",$arrayContent);
    }
    public function testDeleteNotGoodUser(){
        $client = static::createClient();
        $client->request('DELETE', '/api/users/3', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'apiKey2']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = json_decode($content, true);
        $this->assertSame("Not the same user or tu n as pas les droits",$arrayContent);
    }
    public function testDeleteGoodUserAdmin(){
        $client = static::createClient();
        $client->request('DELETE', '/api/users/3', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'fixtureApiKey']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(204, $response->getStatusCode());
    }
    public function testDeleteNotGoodUserAdmin(){
        $client = static::createClient();
        $client->request('DELETE', '/api/users/3456', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'fixtureApiKey']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(404, $response->getStatusCode());
        $arrayContent = json_decode($content, true);
        $this->assertSame("User does note existe",$arrayContent);
    }
    ///////////////////////////////////////////////put utilisateur n°3 ////////////////////////////////////////
    public function testPutUser3Admin(){
        $data = [
            "firstname"=>"lol",
            "email"=>"sdferty@yahoo.com"
        ];

        $client = static::createClient();
        $client->request('PUT', '/api/users/4', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'fixtureApiKey'], json_encode($data));
        $response = $client->getResponse();
        $content = $response->getContent();
        dump($arrayContent = json_decode($content, true));
        $this->assertEquals(200, $response->getStatusCode());

    }
    public function testPutUser3AdminError(){
        $data = [
            "email"=>"sdferty"
        ];

        $client = static::createClient();
        $client->request('PUT', '/api/users/4', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'fixtureApiKey'], json_encode($data));
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(401, $response->getStatusCode());
        dump($arrayContent = json_decode($content, true));
    }
    public function testPutUser3456AdminError(){
        $data = [
            "email"=>"sdferty"
        ];

        $client = static::createClient();
        $client->request('PUT', '/api/users/3456', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'fixtureApiKey'], json_encode($data));
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(404, $response->getStatusCode());
        $arrayContent = json_decode($content, true);
        $this->assertSame("User does note existe",$arrayContent);
    }
    public function testPutUserUser(){
        $data = [
            "email"=>"sdfertfgdfy@yahoo.com"
        ];

        $client = static::createClient();
        $client->request('PUT', '/api/users/2', [], [], ['CONTENT_TYPE' => 'application/json','HTTP_AUTH-TOKEN' => 'apiKey2'], json_encode($data));
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }
}