<?php
namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UsersController extends FOSRestController
{

    private $em;
    private $userRepository;
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }


    private function testUser(User $user)
    {
        if ($this->getUser() === $user || in_array("ROLE_ADMIN",$this->getUser()->getRoles()) ) {
            $return = true;
        } else {
            $return = false;
        }
        return $return;
    }
    private function testUserDroit()
    {
        if (in_array("ROLE_ADMIN",$this->getUser()->getRoles()) ) {
            $return = true;
        } else {
            $return = false;
        }
        return $return;
    }

    private function PostError($validationErrors){
        $error = array("error :");
        /** @var ConstraintViolationListInterface $validationErrors */
        /** @var ConstraintViolation $constraintViolation */
        foreach ($validationErrors as $constraintViolation) {
            $message = $constraintViolation->getMessage();
            $propertyPath = $constraintViolation->getPropertyPath();
            array_push($error,$propertyPath.' => '.$message);
        }
        return $error;
    }

// ceci va juste lister dans Entity/User les @Groups("user")
    /**
     *  @SWG\Parameter(
     *     name="AUTH-TOKEN",
     *     in="header",
     *     type="string",
     *     description="Api Token"
     * )
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="user")
     * @Rest\View(serializerGroups={"user"})
     */
    public function getUsersAction()
    {
        if($this->getUser() !== null )
        {
            if ($this->testUserDroit()) {
                return $this->view($this->userRepository->findAll());
            }
            return $this->view('Not Logged for this user or not an Admin', 401);
        } else {
            return $this->view('Not Logged', 401);
        }
    }


    //@return \FOS\RestBundle\View\View
    /**
     *  @SWG\Parameter(
     *     name="AUTH-TOKEN",
     *     in="header",
     *     type="string",
     *     description="Api Token"
     * )
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="user")
     * @Rest\View(serializerGroups={"user"})
     *
     */
    public function getUserAction(User $user)
    {
        if($this->getUser() !== null ) {
            if ($this->testUser($user)) {

                return $this->view($user);
            }
            return $this->view('Not Logged for this user or not an Admin', 401);
        } else {
            return $this->view('Not Logged', 401);
        }

    }


    /**
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="user")
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Post("/users")
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function postUsersAction(User $user, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $validationErrors = $validator->validate($user);
        if(!($validationErrors->count() > 0) ){
            $this->em->persist($user);
            $this->em->flush();
            return $this->view($user,200);
        } else {
            return $this->view($this->PostError($validationErrors),400);
        }
    }

    /**
     *  @SWG\Parameter(
     *     name="AUTH-TOKEN",
     *     in="header",
     *     type="string",
     *     description="Api Token"
     * )
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="user")
     * @Rest\View(serializerGroups={"user"})
     */
    public function putUserAction(Request $request, $id, ValidatorInterface $validator)
    {
        $users = $this->userRepository->find($id);
        if($users === null){
            return $this->view('User does note existe', 404);
        }
       // dump($this->getUser());die;
            if ($id == $this->getUser()->getId() || $this->testUserDroit()) {

                /** @var User $us */
                $us = $this->userRepository->find($id);

                $firstname = $request->get('firstname');
                $lastname = $request->get('lastname');
                $email = $request->get('email');
                $birthday = $request->get('birthday');

                if (isset($firstname)) {
                    $us->setFirstname($firstname);
                }
                if (isset($lastname)) {
                    $us->setLastname($lastname);
                }
                if (isset($email)) {
                    $us->setEmail($email);
                }
                if (isset($birthday)) {
                    $us->setBirthday($birthday);
                }
                $this->em->persist($us);
                /** @var ConstraintViolationList $valisationErrors */
                $validationErrors = $validator->validate($us);
                if(!($validationErrors->count() > 0) ) {
                    $this->em->flush();
                    return $this->view("ok",200);
                } else {
                    return $this->view($this->PostError($validationErrors),401);
                }
            } else {
                return $this->view('Not the same user or tu n as pas les droits',401);
            }

    }

    /**
     * @SWG\Parameter(
     *     name="AUTH-TOKEN",
     *     in="header",
     *     type="string",
     *     description="Api Token"
     * )
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="user")
     * @Rest\View(serializerGroups={"user"})
     */
    public function deleteUserAction($id)
    {
        /** @var User $us */
        $users = $this->userRepository->findBy(["id"=>$id]);
        if($users === []){
            return $this->view('User does note existe', 404);
        }
        if($this->getUser() !== null ) {
            $us = $this->userRepository->find($id);
            if ($us === $this->getUser() || $this->testUserDroit()) {
                $this->em->remove($us);
                $this->em->flush();
            } else {
                return $this->view('Not the same user or tu n as pas les droits',401);
            }
        } else {
            return $this->view('Not Logged', 401);
        }
    }




}