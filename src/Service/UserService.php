<?php
// src/Service/UserService.php
namespace App\Service;

use App\Entity\User;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{

    private $manager;
    private $serializer;
    private $validator;
    private $encoder;
    private $profilRepository;

    public function __construct(EntityManagerInterface $manager, SerializerInterface $serializer, ProfilRepository $profilRepository, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->encoder = $encoder;
        $this->profilRepository = $profilRepository;

    }

    public function addUser(Request $request)
    {
        $user=$request->request->all();



       $avatar=$request->files->get("avatar");
       $avatar=fopen($avatar->getRealPath(),"rb");
        $profils=$this->profilRepository->find($user['profils']);

       $profil=ucfirst($profils->getLibelle());
        //$class="App\Entity\\$profil";

       $users=$this->serializer->denormalize($user,"App\Entity\\$profil",true);
        //dd($users);

        $password=$users->getPassword();
        $users->setPassword($this->encoder ->encodePassword($users,$password));
        $users->setAvatar($avatar);
        $users->setProfil($profils);

        $this->manager->persist($users);
        $this->manager->flush();

        fclose($avatar);

        //return $this->json("vous avez ajouter un user success",Response::HTTP_CREATED);
        return new JsonResponse("l'Utilisateur a été ajouté avec succés",Response::HTTP_CREATED);

    }

}