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
use Swagger\Annotations as SWG;

class UsersController extends FOSRestController
{

    private $em;
    private $userRepository;
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }


    public function testUser($user)
    {
        if ($this->getUser() === $user || in_array("ROLE_ADMIN",$this->getUser()->getRoles()) ) {
            $return = true;
        } else {
            $return = false;
        }
        return $return;
    }
    public function testUserDroit()
    {
        if (in_array("ROLE_ADMIN",$this->getUser()->getRoles()) ) {
            $return = true;
        } else {
            $return = false;
        }
        return $return;
    }


// ceci va juste lister dans Entity/User les @Groups("user")
    /**
     * @Rest\View(serializerGroups={"user"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns all of an user"
     * )
     */
    public function getUsersAction()
    {
        if($this->testUserDroit()) {
            $users = $this->userRepository->findAll();
            return $this->view($users);
        } else {
            return new JsonResponse('tu n as pas les droits');
        }
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @return \FOS\RestBundle\View\View
     */
    public function getUserAction(User $user)
    {
        if ($this->testUser($user)) {
            return $this->view($user);
        } else {
            return new JsonResponse('Not the same user or tu n as pas les droits');
        }
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
     * @SWG\Parameter(
     *     name="AUTH-TOKEN",
     *     in="header",
     *     type="string",
     *     description="Api Token"
     * )
     * @SWG\Response(response=200, description="")
     * @Rest\View(serializerGroups={"user"})
     */
    public function deleteUserAction($id)
    {
        /** @var User $us */
        $us = $this->userRepository->find($id);
        if($us === $this->getUser()) {
            $this->em->remove($us);
            $this->em->flush();
        } else {
            return new JsonResponse('Not the same user or tu n as pas les droits');
        }
    }




}