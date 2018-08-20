<?php
namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends FOSRestController
{

    private $em;
    private $userRepository;
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }
    public function getUsersAction(User $user)
    {
       // $users = $this->userRepository->findAll();
        return $this->view($user);
    }


    /**
     * @Rest\Post("/users")
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function postUsersAction(User $user, EntityManagerInterface $em)
    {
        $idd = uniqid ();
        $user->setApiKey($idd);
        $this->em->persist($user);
        $this->em->flush();
        return $this->view($user);
    }

    public function putUserAction(Request $request, $id)
    {
            $us = $this->userRepository->findBy(['id'=>$id]);
            $firstname = $request->get('firstname');
            $lastname = $request->get('lastname');
            $email = $request->get('email');
            $birthday = $request->get('birthday');
            $roles = $request->get('roles');
            $apikey = $request->get('apiKey');
            if($firstname != null && $firstname != $us->getFirstname()){
                $us->setFirstname($firstname);
            }


    } // "put_user" [PUT] /users/{id}
    public function deleteUserAction($id)
    {} // "delete_user" [DELETE] /users/{id}
}