<?php
namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
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

// ceci va juste lister dans Entity/User les @Groups("user")
    /**
     * @Rest\View(serializerGroups={"user"})
     */
    public function getUsersAction()
    {
         $users = $this->userRepository->findAll();
        return $this->view($users);
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @return \FOS\RestBundle\View\View
     */
    public function getUserAction(User $user)
    {
        //return new JsonResponse('Not the same user');
        return $this->view($user);
        // "get_user"
    }


    /**
     *
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Post("/users")
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function postUsersAction(User $user, EntityManagerInterface $em)
    {
        $this->em->persist($user);
        $this->em->flush();
        return $this->view($user);
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     */
    public function putUserAction(Request $request, $id)
    {
        if ($id !== $this->getUser()->getId()){
            return new JsonResponse('error');
        }
        /** @var User $us */
        $us = $this->userRepository->find($id);

        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $email = $request->get('email');
        $birthday = $request->get('birthday');
        $roles = $request->get('roles');
       # $apikey = $request->get('apiKey');
        if(isset($firstname)){
            $us->setFirstname($firstname);
        }
        if(isset($lastname)){
            $us->setLastname($lastname);
        }
        if(isset($email)){
            $us->setEmail($email);
        }
        if(isset($birthday)){
            $us->setBirthday($birthday);
        }
        if(isset($roles)) {
            $us->setRoles($roles);
        }
        $this->em->persist($us);
        $this->em->flush();
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     */
    public function deleteUserAction($id)
    {
        /** @var User $us */
        $us = $this->userRepository->find($id);
        $this->em->remove($us);
        $this->em->flush();
    }
}