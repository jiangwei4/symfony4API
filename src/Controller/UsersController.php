<?php
namespace App\Controller;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\FOSRestController;
class UsersController extends FOSRestController
{


    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function getUsersAction()
    {
        $users = $this->userRepository->findAll();
        return $this->view($users);
    }



    public function getUserAction($id)
    {} // "get_user" [GET] /users/{id}
    public function postUsersAction()
    {} // "post_users" [POST] /users
    public function putUserAction($id)
    {} // "put_user" [PUT] /users/{id}
    public function deleteUserAction($id)
    {} // "delete_user" [DELETE] /users/{id}
}