<?php

namespace App\Controller;

use App\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserCRUDController extends FOSRestController
{
    public function putUserAction(Request $request)
    {
        //$us = $this->getUser();
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $email = $request->get('email');
        $apikey = $request->get('apiKey');
        if(isset($firstname)){
            $us->setFirstname($firstname);
        }
        if(isset($lastname)){
            $us->setLastname($lastname);
        }
        if(isset($email)){
            $us->setEmail($email);
        }
        if(isset($apikey)) {
            $us->setApiKey($apikey);
        }
        $us->persist($us);
        $us->flush();


    }
}
