<?php
// src/Service/UserService.php
namespace App\Service;

use App\Entity\User;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
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
    private $userRepository;

    public function __construct(EntityManagerInterface $manager, SerializerInterface $serializer,UserRepository $userRepository, ProfilRepository $profilRepository, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->encoder = $encoder;
        $this->profilRepository = $profilRepository;
        $this->userRepository = $userRepository;

    }

    //Ajouter un utilisateur
    public function addUser(Request $request)
    {
        $user=$request->request->all();
        $avatar=$request->files->get("avatar");
        $avatar=fopen($avatar->getRealPath(),"rb");

        $profils=$this->profilRepository->find($user['profils']);
        $profil=ucfirst($profils->getLibelle());
        //$class="App\Entity\\$profil";

       $users=$this->serializer->denormalize($user,"App\Entity\\$profil",true);

        $users->setProfil($profils);
        //dd($users);
        $password=$users->getPassword();
        $users->setPassword($this->encoder ->encodePassword($users,$password));
        $users->setAvatar($avatar);


        $this->manager->persist($users);
        $this->manager->flush();

        fclose($avatar);

        //return $this->json("vous avez ajouter un user success",Response::HTTP_CREATED);
        return new JsonResponse("l'Utilisateur a été ajouté avec succés",Response::HTTP_CREATED);

    }


    public function updateUser(Request $request, int $id){
        $dataUser= $request->request->all();
        //dd($dataUser);
        $avatar= $request->files->get("avatar");
        //dd($avatar);
        if ($avatar){
            $avatar= fopen($avatar->getRealPath(),'rb');
        }

        $typeUser=$this->userRepository->find($id);

        foreach ($dataUser as $key=>$value){
            if ($key !== "_method"){
                $key=ucfirst($key);
                $set= "set".$key;
                //dd($set);
                $typeUser->$set($value);
            }
        }
        $this->manager->persist($typeUser);
        $this->manager->flush();
        if ($avatar){
            fclose($avatar);
        }
        return new JsonResponse("l'Utilisateur a été modifié avec succés",Response::HTTP_CREATED);
    }
}