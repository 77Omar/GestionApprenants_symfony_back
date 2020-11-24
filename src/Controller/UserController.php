<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $userService;
    public  function  __construct(UserService $userService){
        $this->userService= $userService;
    }
    /**
     * @Route("api/admin/users", name="add_user",methods={"POST"})
     */
    public function addUsers(Request $request){
        $this->userService->addUser($request);
        return new JsonResponse("l'Utilisateur a été ajouté avec succés",Response::HTTP_CREATED);
    }

}
